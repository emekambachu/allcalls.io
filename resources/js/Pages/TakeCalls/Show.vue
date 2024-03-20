<script setup>
import { ref, reactive, watchEffect } from "vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { toaster } from "@/helper.js";

let page = usePage();
let props = defineProps({
  callTypes: Array,
  onlineCallTypes: Array,
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

let setOnlineCallTypes = () => {
  callTypesWithToggles.value.forEach((type) => {
    type.toggle = props.onlineCallTypes.some(
      (onlineType) => onlineType.call_type_id === type.callType.id
    );
  });
};

let callTypesWithToggles = ref(
  props.callTypes
    .filter((callType) => callType.selected)
    .map((callType) => {
      return {
        callType: callType,
        toggle: false,
        bidAmount: Number(callType.bidAmount),
        topBid: Number(callType.topBid),
        averageBid: Number(callType.averageBid),
      };
    })
);

let openedEditMenus = reactive([]);

let openEditMenu = (callTypeId) => {
  openedEditMenus.push(Number(callTypeId));
};

let closeEditMenu = (callTypeId) => {
  openedEditMenus.splice(openedEditMenus.indexOf(Number(callTypeId)), 1);
  router.visit("/web-api/bids/" + callTypeId, {
    method: "PATCH",
    data: {
      amount: callTypesWithToggles.value.find(
        (callType) => callType.callType.id === callTypeId
      ).bidAmount,
    },
  });
};

let cancelEditMenu = (callTypeId) => {
  openedEditMenus.splice(openedEditMenus.indexOf(Number(callTypeId)), 1);
};

// let toggled = (event, callType) => {
//   console.log("toggled!");
//   console.log(event.target.checked);

//   if (event.target.checked) {
//     console.log("add");
//     router.visit("/take-calls/online-users", {
//       method: "POST",
//       data: {
//         call_type_id: callType.id,
//       },
//     });
//   } else {
//     console.log("remove");
//     router.visit(`/take-calls/online-users/${callType.id}`, {
//       method: "DELETE",
//     });
//   }
// };

watchEffect(async () => {
  setOnlineCallTypes();
  setupFlashMessages();
});

let isOnline = (callTypeId) => {
  return props.onlineCallTypes.some((type) => type.call_type_id === callTypeId);
};


let toggled = (event, callType) => {
  console.log("toggled!");
  console.log(event.target.checked);

  // Define a callback function to reload the page
  let onSuccess = () => router.reload();

  if (event.target.checked) {
    console.log("add");
    // Use the onSuccess callback after the POST request
    router.visit("/take-calls/online-users", {
      method: "POST",
      data: {
        call_type_id: callType.id,
      },
      onSuccess,
    });
  } else {
    console.log("remove");
    // Use the onSuccess callback after the DELETE request
    router.visit(`/take-calls/online-users/${callType.id}`, {
      method: "DELETE",
      onSuccess,
    });
  }
};
</script>

<template>
  <Head title="Take Calls" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Take Calls
      </h2>
    </template>

    <div class="pt-14 pb-14">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="px-4 sm:px-8 sm:rounded-lg">
          <div class="text-4xl text-custom-sky font-bold mb-6">Take Calls</div>
          <hr class="mb-4" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-2 px-4 sm:px-8">
          <div class="mt-5">
            <h1 class="text-2xl font-bold mb-4 text-gray-700">Guidelines</h1>
            <ul class="pl-2 max-w-md text-gray-500 list-disc list-inside">
              <li>
                Please keep in mind there is a 80-second qualifying timer before you are
                charged for a call.
              </li>
              <li>
                The client's name, state, and basic info is displayed on your call
                immediately upon answering. Clients personal contact information will
                appear after 80 seconds of being connected to the call.
              </li>
              <li>
                If you turn your status to active and miss a call, you will be billed a $5
                missed call fee.
              </li>
              <li>Please make sure notifications are turned on for this app.</li>
            </ul>

            <h1
              v-if="page.props.auth.role === 'internal-agent'"
              class="text-2xl font-bold mb-4 text-gray-700 mt-6"
            >
              Tools:
            </h1>
            <div v-if="page.props.auth.role === 'internal-agent'">
              <a
                href="https://insurancetoolkits.com/signup?paidAgency=lat4w7gAOHlVGSZJJONBUZKYLDQUAWKH2GB9fy4y"
                target="_blank"
                class="inline-flex items-center px-4 py-3 border border-transparent rounded-md font-semibold text-md uppercase tracking-widest transition ease-in-out duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 bg-custom-blue text-custom-green hover:bg-white hover:drop-shadow-2xl hover:text-custom-blue hover:ring-2 hover:ring-custom-sky focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 my-4"
                >Underwriting Toolkit</a
              >
            </div>
          </div>

          <!-- List here -->
          <div class="mt-5">
            <h1 class="text-2xl font-bold text-gray-700">Verticals</h1>
            <!-- Call Types and Bids List -->
            <ul>
              <li
                v-for="callType in callTypesWithToggles"
                :key="callType.callType.id"
                class="py-3 sm:py-4"
              >
                <div class="flex justify-end items-center">
                  <div class="text-md text-gray-800">
                    Turn calls on
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      class="w-4 h-4 relative my-1"
                      style="left: 35px"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15 15l-6 6m0 0l-6-6m6 6V9a6 6 0 0112 0v3"
                      />
                    </svg>
                  </div>
                </div>
                <div class="flex items-center justify-between">
                  <!-- Call Type Title on the left -->
                  <p
                    class="text-xl text-gray-900 font-medium"
                    v-text="callType.callType.type"
                  ></p>

                  <!-- Toggle and Bids on the right -->
                  <div class="flex items-center space-x-4">
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input
                        type="checkbox"
                        v-model="callType.toggle"
                        class="sr-only peer"
                        @change="toggled($event, callType.callType)"
                        :checked="isOnline(callType.callType.id)"
                      />
                      <div
                        class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"
                      ></div>
                    </label>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!--    <section class="p-3">-->
    <!--      -->
    <!--    </section>-->
  </AuthenticatedLayout>
</template>
