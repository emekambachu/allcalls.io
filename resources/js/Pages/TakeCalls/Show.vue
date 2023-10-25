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

console.log(props.callTypes);

let setupFlashMessages = () => {
  console.log(page.props.flash);
  if (page.props.flash.message) {
    toaster("success", page.props.flash.message);
  }

  if (page.props.errors.balance) {
    toaster("error", page.props.errors.balance);
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
      return { callType: callType, toggle: false, bidAmount: Number(callType.bidAmount), topBid: Number(callType.topBid), averageBid: Number(callType.averageBid) };
    })
);

let openedEditMenus = reactive([]);

let openEditMenu = (callTypeId) => {
  openedEditMenus.push(Number(callTypeId));
};

let closeEditMenu = (callTypeId) => {
  openedEditMenus.splice(openedEditMenus.indexOf(Number(callTypeId)), 1);
  router.visit('/web-api/bids/' + callTypeId, {
    method: "PATCH",
    data: {
      amount: callTypesWithToggles.value.find((callType) => callType.callType.id === callTypeId).bidAmount,
    },
  });
}

let cancelEditMenu = (callTypeId) => {
  openedEditMenus.splice(openedEditMenus.indexOf(Number(callTypeId)), 1);
}

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
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
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
      <div class="mx-auto max-w-screen-xl sm:px-12 grid grid-cols-2 gap-2" style="grid-template-columns: 2fr 3fr;">
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

          <h1 class="text-2xl font-bold mb-4 text-gray-700 mt-6">Tools:</h1>
          <div>
            <a href="https://insurancetoolkits.com/signup?paidAgency=lat4w7gAOHlVGSZJJONBUZKYLDQUAWKH2GB9fy4y"
              target="_blank"
              class="inline-flex items-center px-4 py-3 border border-transparent rounded-md font-semibold text-md uppercase tracking-widest transition ease-in-out duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 bg-custom-blue text-custom-green hover:bg-white hover:drop-shadow-2xl hover:text-custom-blue hover:ring-2 hover:ring-custom-sky focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 my-4">Underwriting
              Toolkit</a>
          </div>
        </div>

        <!-- List here -->
        <div>
          <h1 class="text-2xl font-bold mb-4 text-gray-700">Verticals</h1>
          <!-- Call Types and Bids List -->
          <ul>
            <li v-for="callType in callTypesWithToggles" :key="callType.callType.id" class="py-3 sm:py-4">
              <div class="flex justify-end items-center">
                <div class="text-md text-gray-800">Turn calls on <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4 relative my-1" style="left: 25px">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 15l-6 6m0 0l-6-6m6 6V9a6 6 0 0112 0v3" />
                  </svg>
                </div>

              </div>
              <div class="flex items-center justify-between">
                <!-- Call Type Title on the left -->
                <p class="text-xl text-gray-900 font-medium" v-text="callType.callType.type"></p>

                <!-- Toggle and Bids on the right -->
                <div class="flex items-center space-x-4">
                  <!-- Toggle Button -->
                  <!-- <label
                    class="relative inline-flex items-center cursor-pointer"
                  >
                    <input
                      type="checkbox"
                      v-model="callType.toggle"
                      class="sr-only peer"
                      @change="toggled($event, callType.callType)"
                    />
                    <div
                      class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"
                    ></div>
                  </label> -->
                  <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" v-model="callType.toggle" class="sr-only peer"
                      @change="toggled($event, callType.callType)">
                    <div
                      class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                    </div>
                  </label>

                </div>
              </div>

              <div v-if="page.props.auth.role !== 'internal-agent'" class="flex items-center mt-2 mb-2">
                <h4 class="text-gray-800 text-sm text-bold mr-1 font-medium">
                  Your Bid:
                </h4>
                <div class="text-gray-600 hover:text-gray-800 cursor-pointer text-sm rounded-md flex">
                  <div class="mr-2">${{ callType.bidAmount }}</div>

                  <svg @click.prevent="openEditMenu(callType.callType.id)"
                    v-if="!openedEditMenus.includes(callType.callType.id)" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                  </svg>
                </div>
              </div>

              <div v-if="page.props.auth.role !== 'internal-agent' && openedEditMenus.includes(callType.callType.id)"
                class="flex items-center mt-2 mb-2">
                <input v-on:keyup.enter="closeEditMenu(callType.callType.id)" type="number"
                  class="border-gray-400 rounded-lg font-xs mr-2 py-1 px-2 text-sm bg-sky" v-model="callType.bidAmount" />

                <button class="bg-custom-sky hover:bg-custom-darksky text-white px-2 py-0.5 text-sm rounded"
                  @click="closeEditMenu(callType.callType.id)">Save</button>
                <button class="px-2 py-0.5 text-sm rounded" @click="cancelEditMenu(callType.callType.id)">Cancel</button>
              </div>

              <div v-if="page.props.auth.role !== 'internal-agent'" class="flex items-center mt-2 mb-2">
                <h4 class="text-gray-800 text-sm text-bold mr-1 font-medium">
                  Top Bid:
                </h4>
                <p class="text-gray-600 text-sm rounded-md">${{ callType.topBid }}</p>
              </div>

              <div v-if="page.props.auth.role !== 'internal-agent'" class="flex items-center mt-2 mb-2">
                <h4 class="text-gray-800 text-sm text-bold mr-1 font-medium">
                  Average Bid:
                </h4>
                <p class="text-gray-600 text-sm rounded-md">${{ callType.averageBid }}</p>
              </div>

              <div v-if="page.props.auth.role !== 'internal-agent'" class="flex items-center mt-2 mb-2">
                <h4 class="text-gray-800 text-sm text-bold mr-1 font-medium">
                  Minimum Bid:
                </h4>
                <p class="text-gray-600 text-sm rounded-md">$35</p>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </section>
  </AuthenticatedLayout>
</template>
