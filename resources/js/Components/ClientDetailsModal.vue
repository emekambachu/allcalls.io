<script setup>
import { defineEmits } from "vue";

const { showModal, selectedClient } = defineProps([
  "showModal",
  "selectedClient",
]);
const emit = defineEmits(["close"]);

const close = () => {
  emit("close");
};
</script>

<template>
  <Transition
    name="modal"
    enter-active-class="transition ease-out duration-300 transform"
    enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    enter-to-class="opacity-100 translate-y-0 sm:scale-100"
    leave-active-class="transition ease-in duration-200 transform"
    leave-from-class="opacity-100 translate-y-0 sm:scale-100"
    leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
  >
    <div
      id="defaultModal"
      v-if="showModal"
      tabindex="-1"
      class="flex items-center justify-center fixed inset-0 z-50 w-full h-full overflow-x-hidden overflow-y-auto max-h-full mx-4 sm:mx-0"
    >
      <div class="fixed inset-0 bg-black opacity-60"></div>
      <!-- This is the overlay -->

      <div class="relative w-full max-w-4xl max-h-full mx-auto">
        <div
          class="relative bg-sky-950 border border-custom-darksky rounded-lg shadow-lg"
        >
          <div
            class="flex items-start justify-between p-4 border-b rounded-t border-gray-600"
          >
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
              Client Details
            </h3>
            <button
              @click="close"
              type="button"
              class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
              data-modal-hide="defaultModal"
            >
              <svg
                class="w-3 h-3"
                aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 14 14"
              >
                <path
                  stroke="currentColor"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"
                />
              </svg>
              <span class="sr-only">Close modal</span>
            </button>
          </div>
          <div class="p-6">
            <div v-if="selectedClient">
              <h4 class="text-2xl font-semibold text-custom-sky mb-2">
                Personal Details
              </h4>
              <div
                class="grid sm:grid-cols-1 md:grid-cols-2 gap-2 text-gray-500 dark:text-gray-300 mb-10"
              >
                <div>
                  <strong class="text-lg">First Name: </strong>
                  {{ selectedClient.first_name }}
                </div>
                <div>
                  <strong class="text-lg">Last Name: </strong>
                  {{ selectedClient.last_name }}
                </div>
                <div>
                  <strong class="text-lg">Date of Birth: </strong>
                  {{ selectedClient.dob || "N/A" }}
                </div>
              </div>

              <h4 class="text-2xl font-semibold text-custom-sky mb-2">
                Contact Information
              </h4>
              <div
                class="grid sm:grid-cols-1 md:grid-cols-2 gap-2 text-gray-500 dark:text-gray-300 mb-10"
              >
                <div>
                  <strong class="text-lg">Phone: </strong>
                  {{ selectedClient.phone }}
                </div>
                <div>
                  <strong class="text-lg">Email: </strong>
                  {{ selectedClient.email || "N/A" }}
                </div>
                <div>
                  <strong class="text-lg">Address: </strong>
                  {{ selectedClient.address || "N/A" }}
                </div>
                <div>
                  <strong class="text-lg">Zip Code: </strong>
                  {{ selectedClient.zipCode || "N/A" }}
                </div>
              </div>

              <h4 class="text-2xl font-semibold text-custom-sky mb-2">
                Call Details
              </h4>
              <div
                class="grid sm:grid-cols-1 md:grid-cols-2 gap-2 text-gray-500 dark:text-gray-300 mb-10"
              >
                <div>
                  <strong class="text-lg">Call Taken: </strong>
                  {{ selectedClient.call_taken || "N/A" }}
                </div>
                <div>
                  <strong class="text-lg">Call Duration (Seconds): </strong>
                  {{
                    String(
                      Math.floor(selectedClient.call_duration_in_seconds / 60)
                    ).padStart(2, "0") +
                    ":" +
                    String(
                      selectedClient.call_duration_in_seconds % 60
                    ).padStart(2, "0")
                  }}
                </div>
                <div>
                  <strong class="text-lg">Hung Up By: </strong>
                  {{ selectedClient.hung_up_by }}
                </div>
                <div>
                  <strong class="text-lg">Call ID: </strong>
                  {{ selectedClient.call_id }}
                </div>
                <div>
                  <strong class="text-lg">Recording URL: </strong>
                  <a :href="selectedClient.recording_url" target="_blank">{{
                    selectedClient.recording_url || "N/A"
                  }}</a>
                </div>
                <div>
                  <strong class="text-lg">Call Type: </strong>
                  {{ selectedClient.call_type.type }}
                </div>
              </div>

              <h4 class="text-2xl font-semibold text-custom-sky mb-2">
                Financial Details
              </h4>
              <div
                class="grid sm:grid-cols-1 md:grid-cols-2 gap-2 text-gray-500 dark:text-gray-300 mb-10"
              >
                <div>
                  <strong class="text-lg">Amount Spent: </strong>
                  {{ "$" + (selectedClient.amount_spent / 100).toFixed(2) }}
                </div>
              </div>
            </div>

            <div class="flex justify-end">
              <button
                @click="showModal = false"
                class="border border-gray-400 ease-in cursor-pointer bg-white bg-opacity-5 hover:shadow-2xl hover:bg-white hover:text-custom-blue hover:bg-opacity-80 rounded px-3 py-3 font-bold text-md text-custom-white"
              >
                Close
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>