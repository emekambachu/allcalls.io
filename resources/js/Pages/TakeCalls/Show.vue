<script setup>
import { ref, reactive, watchEffect } from "vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { toaster } from "@/helper.js";

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
watchEffect(async () => {
  setOnlineCallType();
  setupFlashMessages();
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
      <div class="mx-auto max-w-screen-xl sm:px-12 grid grid-cols-2 gap-3">
        <div>
          <h1 class="text-2xl font-bold mb-4 text-gray-700">Guidelines</h1>
          <ul class="pl-2 max-w-md text-gray-500 list-disc list-inside">
            <li>
              Please keep in mind there is a 60-second qualifying timer before
              you are charged for a call.
            </li>
            <li>
              The client's name, state, and basic info is displayed on your call
              immediately upon answering. Clients personal contact information
              will appear after 60 seconds of being connected to the call.
            </li>
            <li>
              If you turn your status to active and miss a call, you will be
              billed a $5 missed call fee.
            </li>
            <li>Please make sure notifications are turned on for this app.</li>
          </ul>
        </div>

        <!-- List here -->
        <div>
          <h1 class="text-2xl font-bold mb-4 text-gray-700">Verticals</h1>
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
      </div>
    </section>
  </AuthenticatedLayout>
</template>
