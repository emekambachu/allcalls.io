<script setup>
import { ref, reactive, watchEffect } from "vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { toaster } from "@/helper.js";
import Modal from "@/Components/Modal.vue";
import { Device } from "@twilio/voice-sdk";

let page = usePage();
let props = defineProps({
  callTypes: Array,
  onlineCallType: Object,
});

let showRinging = ref(false);
let showOngoing = ref(false);
let call = reactive(null);

let setupFlashMessages = () => {
  console.log(page.props.flash);
  if (page.props.flash.message) {
    toaster("success", page.props.flash.message);
  }
};
let setOnlineCallType = () => {
  callTypesWithToggles.value.map((type) => {
    return { callType: type.callType, toggle: false };
  });

  for (let i = 0; i < callTypesWithToggles.value.length; i++) {
    if (
      props.onlineCallType &&
      props.onlineCallType.call_type_id ===
        callTypesWithToggles.value[i].callType.id
    ) {
      callTypesWithToggles.value[i].toggle = true;
      return;
    }
  }
};

let callTypesWithToggles = ref(
  props.callTypes
    .filter((callType) => callType.selected)
    .map((callType) => {
      return { callType: callType, toggle: false };
    })
);

let userCallTypesToggles = ref(
  props.callTypes
    .filter((callType) => callType.selected)
    .map((callType) => {
      return {};
    })
);

let toggled = (event, callType) => {
  console.log("toggled!");
  console.log(event.target.checked);

  if (event.target.checked) {
    // Add to the online users
    console.log("add");
    router.visit("/take-calls/online-users", {
      method: "POST",
      data: {
        call_type_id: callType.id,
      },
    });
  } else {
    console.log("remove");
    // Remove from the online users
    router.visit(`/take-calls/online-users/${callType.id}`, {
      method: "DELETE",
    });
  }
};

let setupTwilioDevice = () => {
  axios.get("/twilio-device-token").then((response) => {
    let token = response.data.token;
    console.log("token is ", token);

    let device = new Device(token, {
      // Set Opus as our preferred codec. Opus generally performs better, requiring less bandwidth and
      // providing better audio quality in restrained network conditions. Opus will be default in 2.0.
      codecPreferences: ["opus", "pcmu"],
      // Use fake DTMF tones client-side. Real tones are still sent to the other end of the call,
      // but the client-side DTMF tones are fake. This prevents the local mic capturing the DTMF tone
      // a second time and sending the tone twice. This will be default in 2.0.
      fakeLocalDTMF: true,
      // Use `enableRingingState` to enable the device to emit the `ringing`
      // state. The TwiML backend also needs to have the attribute
      // `answerOnBridge` also set to true in the `Dial` verb. This option
      // changes the behavior of the SDK to consider a call `ringing` starting
      // from the connection to the TwiML backend to when the recipient of
      // the `Dial` verb answers.
      enableRingingState: true,
      debug: true,
    });
    console.log("deviceee", device);

    device.on("ready", function (device) {
      console.log("Twilio.Device Ready!");
    });

    device.on("registered", function () {
      console.log("REGISTERED!");
    });

    device.on("error", function (error) {
      console.log("Twilio.Device Error: " + error.message);
    });

    device.on("connect", function (conn) {
      console.log("Successfully established call ! ");
    });

    device.on("disconnect", function (conn) {
      console.log("Call ended.");
    });

    device.on("incoming", incomingCall => {
      console.log("Incoming!");
      call = incomingCall;
      showIncomingCall(call);
    });

    device.register();
  });
};

let showIncomingCall = (conn) => {
  console.log("show incoming call now");
  console.log(conn);
  showRinging.value = true;
};

watchEffect(async () => {
  setOnlineCallType();
  setupFlashMessages();
  // setupTwilioDevice();
});

let acceptCall = () => {
  console.log('accept call now');
  if (call) {
    call.accept();
    showRinging.value = false;
    showOngoing.value = true;
  } else {
    console.log('call not found');
  }
};

let rejectCall = () => {
  console.log('reject call now');
  if (call) {
    call.reject();
    showRinging.value = false;
  } else {
    console.log('call not found while rejecting')
  }
}

let disconnectCall = () => {
  console.log('disconnect call now');
  if (call) {
    call.disconnect();
    showOngoing.value = false;
  } else {
    console.log('call not found while disconnecting')
  }
}
</script>

<template>
  <Head title="Take Calls" />
  <AuthenticatedLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
      >
        Take Calls
      </h2>
    </template>

    <div class="pt-14">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="px-4 sm:px-8 sm:rounded-lg">
          <div class="text-4xl text-custom-sky font-bold mb-6">Take Calls</div>
          <hr class="mb-4" />
        </div>
      </div>
    </div>
    <section class="p-3">
      <div class="mx-auto max-w-screen-xl sm:px-12">
        <!-- List here -->
        <ul class="max-w-md divide-y divide-gray-200 mb-8">
          <li
            v-for="callType in callTypesWithToggles"
            :key="callType.callType.id"
            class="py-3 sm:py-4 flex items-center justify-between"
          >
            <!-- Title on the left -->
            <p
              class="text-xl text-gray-900"
              v-text="callType.callType.type"
            ></p>

            <!-- Toggle on the right -->
            <label class="relative inline-flex items-center cursor-pointer">
              <input
                type="checkbox"
                v-model="callType.toggle"
                class="sr-only peer"
                @change="toggled($event, callType.callType)"
              />
              <div
                class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"
              ></div>
            </label>
          </li>
        </ul>
      </div>
    </section>

  </AuthenticatedLayout>
</template>
