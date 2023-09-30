<script setup>
import { ref, watchEffect } from "vue";
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

    device.on("incoming", (conn) => {
      console.log(conn.parameters);
      console.log("Incoming connection from " + conn.parameters.From);
      showIncomingCall(conn);
    });

    device.register();
  });
};

let showIncomingCall = (conn) => {
  console.log("show incoming call now");
};

watchEffect(async () => {
  setOnlineCallType();
  setupFlashMessages();
  setupTwilioDevice();
  showIncomingCall({});
});
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

    <Modal :show="false" maxWidth="sm" :closeable="false">
      <div class="bg-white p-8 py-24 rounded-lg shadow-xl w-full">
        <div class="flex flex-col items-center">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="currentColor"
            class="w-16 h-16 text-green-700 animate-pulse mb-4"
          >
            <path
              fill-rule="evenodd"
              d="M19.5 9.75a.75.75 0 01-.75.75h-4.5a.75.75 0 01-.75-.75v-4.5a.75.75 0 011.5 0v2.69l4.72-4.72a.75.75 0 111.06 1.06L16.06 9h2.69a.75.75 0 01.75.75z"
              clip-rule="evenodd"
            />
            <path
              fill-rule="evenodd"
              d="M1.5 4.5a3 3 0 013-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 01-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 006.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 011.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 01-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5z"
              clip-rule="evenodd"
            />
          </svg>

          <h1 class="text-2xl font-bold text-black">Incoming Call</h1>
          <p class="text-md text-gray-600 mt-2">AllCalls Client</p>

          <div class="flex mt-20 space-x-10">
            <div class="bg-red-500 hover:bg-red-600 p-3 rounded-full">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-6 h-6 text-white cursor-pointer"
                style="transform: rotate(133deg)"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"
                />
              </svg>
            </div>
            <div class="bg-green-500 hover:bg-green-400 p-3 rounded-full">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-6 h-6 text-white cursor-pointer"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"
                />
              </svg>
            </div>
          </div>
        </div>
      </div>
    </Modal>

    <Modal :show="true" maxWidth="lg" :closeable="false">
      <div
        class="flex flex-col items-center justify-between h-full p-8 bg-white space-y-8"
      >
        <!-- Call Duration -->
        <!-- <div>
          <p class="text-2xl font-medium text-black">00:05</p>
        </div> -->

        <!-- Client's Basic Info -->
        <!-- <div class="w-full">
          <p class="text-md text-center text-black mb-2">Client's Basic Info</p>
          <ul class="w-full p-4 bg-gray-100 rounded-md space-y-2">
            <li class="flex justify-between">
              <span class="text-gray-600">Name:</span>
              <span class="text-black">John Doe</span>
            </li>
            <li class="flex justify-between">
              <span class="text-gray-600">Age:</span>
              <span class="text-black">32</span>
            </li>
            <li class="flex justify-between">
              <span class="text-gray-600">Contact:</span>
              <span class="text-black">+1 (234) 567-8900</span>
            </li>
          </ul>
        </div> -->

        <!-- Info Populating After 60 seconds -->
        <!-- <div class="w-full">
          <p class="text-md text-center text-black mb-2">
            Info will populate after 60 seconds
          </p>
          <ul class="w-full p-4 bg-gray-100 rounded-md space-y-2">
            <li class="flex justify-between">
              <span class="text-gray-600">Address:</span>
              <span class="text-black">123 Main St</span>
            </li>
            <li class="flex justify-between">
              <span class="text-gray-600">Email:</span>
              <span class="text-black">johndoe@email.com</span>
            </li>
          </ul>
        </div> -->

        <h3 class="text-2xl font-medium">Ongoing Call</h3>

        <!-- Hang Up Button -->
        <div>
          <button class="bg-red-500 hover:bg-red-400 text-white rounded-full py-2 px-6">
            Hang Up
          </button>
        </div>
      </div>
    </Modal>
  </AuthenticatedLayout>
</template>
