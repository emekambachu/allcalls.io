<script setup>
import { ref, reactive, defineEmits } from "vue";
import TextInput from "@/Components/TextInput.vue";
import { router } from "@inertiajs/vue3"

const { showModal, selectedClient } = defineProps([
  "showModal",
  "selectedClient",
]);
const editScreen = ref(false);
const emit = defineEmits(["close"]);

const close = () => {
  editScreen.value = false;
  emit("close");
};

let form = reactive(selectedClient)

const saveChanges = () => {
  router.visit(`/clients/${selectedClient.id}`, {
    method: 'PATCH',
    data: form,
  });
}
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

      <div
        v-if="!editScreen"
        class="relative w-full max-w-4xl max-h-full mx-auto"
      >
        <div class="relative bg-white rounded-lg shadow-lg">
          <div
            class="flex items-start justify-between p-4 border-b rounded-t border-gray-600"
          >
            <h3 class="text-xl font-semibold text-custom-blue">
              Client Details
            </h3>
            <button
              @click="close"
              type="button"
              class="text-gray-500 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center"
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
              <div class="flex justify-between items-center">
                <h4 class="text-2xl font-semibold text-custom-sky mb-2">
                  Personal Details
                </h4>

                <button
                  @click="editScreen = true"
                  class="border border-gray-400 ease-in cursor-pointer bg-gray-400 bg-opacity-5 hover:shadow-2xl hover:bg-white hover:text-custom-blue hover:bg-opacity-80 rounded px-3 py-3 font-bold text-md text-gray-500 transition"
                >
                  Edit Client
                </button>
              </div>

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
                  <strong class="text-lg">State: </strong>
                  {{ selectedClient.state || "N/A" }}
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
                class="border border-gray-400 ease-in cursor-pointer bg-white bg-opacity-5 hover:shadow-2xl hover:bg-white hover:text-custom-blue hover:bg-opacity-80 rounded px-3 py-3 font-bold text-md text-gray-500 transition"
              >
                Close
              </button>
            </div>
          </div>
        </div>
      </div>

      <div v-else class="relative w-full max-w-4xl max-h-full mx-auto">
        <div
          class="relative bg-sky-950 border border-custom-darksky rounded-lg shadow-lg"
        >
          <div
            class="flex items-start justify-between p-4 border-b rounded-t border-gray-600"
          >
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
              Edit Client Details
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
          <form class="p-6">
            <h4 class="text-2xl font-semibold text-custom-sky mb-2">
              Personal Details
            </h4>
            <div
              class="grid sm:grid-cols-1 md:grid-cols-2 gap-2 text-gray-500 dark:text-gray-300 mb-10"
            >
              <div>
                <label class="text-lg">First Name:</label>
                <TextInput
                  type="text"
                  name="first_name"
                  id="first_name"
                  class="w-full"
                  placeholder="John"
                  required
                  v-model="form.first_name"
                />
              </div>
              <div>
                <label class="text-lg">Last Name:</label>
                <TextInput
                  type="text"
                  name="last_name"
                  id="last_name"
                  class="w-full"
                  placeholder="Doe"
                  required
                  v-model="form.last_name"
                />
              </div>
              <div>
                <label class="text-lg">Date of Birth:</label>
                <TextInput
                  type="text"
                  name="dob"
                  id="dob"
                  class="w-full"
                  required
                  v-model="form.dob"
                />
              </div>
            </div>

            <h4 class="text-2xl font-semibold text-custom-sky mb-2">
              Contact Information
            </h4>
            <div
              class="grid sm:grid-cols-1 md:grid-cols-2 gap-2 text-gray-500 dark:text-gray-300 mb-10"
            >
              <div>
                <label class="text-lg">Phone:</label>
                <TextInput
                  type="text"
                  name="phone"
                  id="phone"
                  class="w-full"
                  required
                  v-model="form.phone"
                />
              </div>
              <div>
                <label class="text-lg">Email:</label>
                <TextInput
                  type="email"
                  name="email"
                  id="email"
                  class="w-full"
                  placeholder="john@example.com"
                  required
                  v-model="form.email"
                />
              </div>
              <div>
                <label class="text-lg">Address:</label>
                <TextInput
                  type="text"
                  name="address"
                  id="address"
                  class="w-full"
                  placeholder="10001"
                  required
                  v-model="form.address"
                />
              </div>
              <div class="w-full">
                <label
                  for="state"
                  class="block mb-2 text-sm font-medium text-gray-700"
                  >State</label
                >
                <select
                  name="state"
                  id="state"
                  class="bg-custom-blue text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 text-white outline-none border-none"
                  v-model="form.state"
                  required
                >
                  <option selected="" disabled="" value="">Select State</option>
                  <option value="AL">Alabama</option>
                  <option value="AK">Alaska</option>
                  <option value="AZ">Arizona</option>
                  <option value="AR">Arkansas</option>
                  <option value="CA">California</option>
                  <option value="CO">Colorado</option>
                  <option value="CT">Connecticut</option>
                  <option value="DE">Delaware</option>
                  <option value="FL">Florida</option>
                  <option value="GA">Georgia</option>
                  <option value="HI">Hawaii</option>
                  <option value="ID">Idaho</option>
                  <option value="IL">Illinois</option>
                  <option value="IN">Indiana</option>
                  <option value="IA">Iowa</option>
                  <option value="KS">Kansas</option>
                  <option value="KY">Kentucky</option>
                  <option value="LA">Louisiana</option>
                  <option value="ME">Maine</option>
                  <option value="MD">Maryland</option>
                  <option value="MA">Massachusetts</option>
                  <option class="text-black" value="MI">Michigan</option>
                  <option class="text-black" value="MN">Minnesota</option>
                  <option class="text-black" value="MS">Mississippi</option>
                  <option class="text-black" value="MO">Missouri</option>
                  <option class="text-black" value="MT">Montana</option>
                  <option class="text-black" value="NE">Nebraska</option>
                  <option class="text-black" value="NV">Nevada</option>
                  <option class="text-black" value="NH">New Hampshire</option>
                  <option class="text-black" value="NJ">New Jersey</option>
                  <option class="text-black" value="NM">New Mexico</option>
                  <option class="text-black" value="NY">New York</option>
                  <option class="text-black" value="NC">North Carolina</option>
                  <option class="text-black" value="ND">North Dakota</option>
                  <option class="text-black" value="OH">Ohio</option>
                  <option class="text-black" value="OK">Oklahoma</option>
                  <option class="text-black" value="OR">Oregon</option>
                  <option class="text-black" value="PA">Pennsylvania</option>
                  <option class="text-black" value="RI">Rhode Island</option>
                  <option class="text-black" value="SC">South Carolina</option>
                  <option class="text-black" value="SD">South Dakota</option>
                  <option class="text-black" value="TN">Tennessee</option>
                  <option class="text-black" value="TX">Texas</option>
                  <option class="text-black" value="UT">Utah</option>
                  <option class="text-black" value="VT">Vermont</option>
                  <option class="text-black" value="VA">Virginia</option>
                  <option class="text-black" value="WA">Washington</option>
                  <option class="text-black" value="WV">West Virginia</option>
                  <option class="text-black" value="WI">Wisconsin</option>
                  <option class="text-black" value="WY">Wyoming</option>
                </select>
              </div>
              <div>
                <label class="text-lg">Zip Code:</label>
                <TextInput
                  type="text"
                  name="zip"
                  id="zip"
                  class="w-full"
                  placeholder="10001"
                  required
                  v-model="form.zipCode"
                />
              </div>
            </div>

            <!-- (The rest of your sections would follow a similar structure) -->

            <div class="flex justify-end mt-6">
              <button
                type="submit"
                class="border border-gray-400 ease-in cursor-pointer bg-white bg-opacity-5 hover:shadow-2xl hover:bg-white hover:text-custom-blue hover:bg-opacity-80 rounded px-3 py-3 font-bold text-md text-custom-white"
                @click.prevent="saveChanges"
              >
                Save Changes
              </button>
              <button
                @click.prevent="close"
                type="button"
                class="ml-4 border border-gray-400 ease-in cursor-pointer bg-white bg-opacity-5 hover:shadow-2xl hover:bg-white hover:text-custom-blue hover:bg-opacity-80 rounded px-3 py-3 font-bold text-md text-custom-white"
              >
                Close
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </Transition>
</template>