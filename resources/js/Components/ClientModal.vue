<style scoped>
.spnClassLocked {
  color: #fb4040;
}


::v-deep .dp__input_icon_pad {
    cursor: pointer;
    background: #e8f0fe;
    height: 44px;
    border-radius: 8px;
}

</style>
<script setup>
import { ref, reactive, defineEmits, onMounted } from "vue";
import TextInput from "@/Components/TextInput.vue";
import { router } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { toaster } from "@/helper.js";

let props = defineProps({
  showModal: {
    type: Boolean,
  },

  ClientDetail: {
    type: Object,
  },
  states: Array,
  route: String,
  editScreen:Boolean,
});
let maxDate = ref(new Date)
maxDate.value.setHours(23, 59, 59, 999);
// let editScreen = ref(false);
let emit = defineEmits(["close"]);

let form = ref({});
let close = () => {
  // editScreen.value = false;
  emit("close");
  form.value = {};
  props.ClientDetail = {};
};

let isLoading = ref(false);
let saveChanges = () => {
  isLoading.value = true;
  router.visit(`${props.route}/${form.value.client_id}`, {
    method: "PATCH",
    data: form.value,
  });
  isLoading.value = false;
};
if(props.editScreen){
  console.log('ClientDetail',props.ClientDetail);
  form.value.first_name = props.ClientDetail.first_name;
  form.value.last_name = props.ClientDetail.last_name;
  form.value.unlocked = props.ClientDetail.unlocked;
  form.value.phone = props.ClientDetail.phone;
  form.value.dob = props.ClientDetail.dob;
  form.value.email = props.ClientDetail.email;
  form.value.address = props.ClientDetail.address;
  form.value.state = props.ClientDetail.state;
  form.value.zipCode = props.ClientDetail.zipCode;
  form.value.status = props.ClientDetail.status;
  form.value.client_id = props.ClientDetail.id;
}
let openEdit = () => {
  // editScreen.value = true;
  form = JSON.parse(JSON.stringify(props.ClientDetail));
};
</script>

<template>
  <div v-if="!editScreen" class="relative w-full max-w-4xl max-h-full mx-auto">
    <div class="relative bg-white rounded-lg shadow-lg">
      <div class="flex items-start justify-between p-4 border-b rounded-t border-gray-600">
        <h3 class="text-xl font-small text-custom-blue">Client Details</h3>

        <button @click="close" type="button"
          class="text-gray-500 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center"
          data-modal-hide="defaultModal">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
      </div>
      <div class="p-6">
        <div v-if="ClientDetail">

          <div class="flex justify-between items-center"
            v-if="ClientDetail.unlocked !== 0 || $page.props.auth.role == 'admin'">
            <h4 class="text-2xl font-small text-custom-sky mb-2">Personal Details</h4>

            <!-- <PrimaryButton @click="openEdit"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
              </svg>
            </PrimaryButton> -->
          </div>

          <div class="grid sm:grid-cols-1 md:grid-cols-2 gap-2 text-gray-700 mb-10">
            <div>
              <strong class="text-lg">First Name: </strong>
              {{ ClientDetail.first_name }}
            </div>
            <div>
              <strong class="text-lg">Last Name: </strong>
              {{ ClientDetail.last_name }}
            </div>
            <div v-if="ClientDetail.unlocked === 1 || $page.props.auth.role == 'admin'">
              <strong class="text-lg">Date of Birth: </strong>
              {{ ClientDetail.dob || "N/A" }}
            </div>
            <div v-if="ClientDetail.unlocked !== 1 || $page.props.auth.role == 'admin'">
              <strong class="text-lg">Status: </strong>
              <span v-if="ClientDetail.status == 'not_sold'"
                class="bg-red-600 text-white text-xs px-2 py-1 rounded-2xl">Not Sold</span>
              <span v-else-if="ClientDetail.status == 'sold'"
                class="bg-green-600 text-white text-xs px-2 py-1 rounded-2xl">Sold</span>
              <span v-else-if="ClientDetail.status" class="bg-yellow-600 text-white text-xs px-2 py-1 rounded-2xl">{{
                ClientDetail.status }}</span>
              <span v-else>-</span>
            </div>
            <div v-if="$page.props.auth.role == 'admin'" class="flex">
              <strong class="text-lg">Locked </strong>
              <div class="ml-3">
                <div
                  :class="ClientDetail.unlocked ? 'relative grid items-center px-2 py-1 font-sans text-xs font-bold text-green-900 uppercase rounded-md select-none whitespace-nowrap bg-green-500/20' : 'relative grid items-center px-2 py-1 font-sans text-xs font-bold text-red-900 uppercase rounded-md select-none whitespace-nowrap bg-red-500/20'">
                  <div class="flex">
                    <svg v-if="ClientDetail.unlocked" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                      stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                      <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13.5 10.5V6.75a4.5 4.5 0 1 1 9 0v3.75M3.75 21.75h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H3.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                    <svg v-if="!ClientDetail.unlocked" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                      stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                      <path stroke-linecap="round" stroke-linejoin="round"
                        d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                    <span class="ml-2" v-text="ClientDetail.unlocked ? 'Unlocked' : 'Locked'"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <h4 class="text-2xl font-small text-custom-sky mb-2">Contact Information</h4>
          <div class="grid sm:grid-cols-1 md:grid-cols-2 gap-2 text-gray-700 mb-10">
            <div v-if="ClientDetail.unlocked === 1 || $page.props.auth.role == 'admin'">
              <strong class="text-lg">Phone: </strong>
              {{ ClientDetail.phone }}
            </div>
            <div v-if="ClientDetail.unlocked === 1 || $page.props.auth.role == 'admin'">
              <strong class="text-lg">Email: </strong>
              {{ ClientDetail.email || "N/A" }}
            </div>
            <div v-if="ClientDetail.unlocked === 1 || $page.props.auth.role == 'admin'">
              <strong class="text-lg">Address: </strong>
              {{ ClientDetail.address || "N/A" }}
            </div>
            <div>
              <strong class="text-lg">State: </strong>
              {{ ClientDetail.state || "N/A" }}
            </div>
            <div v-if="ClientDetail.unlocked === 1 || $page.props.auth.role == 'admin'">
              <strong class="text-lg">Zip Code: </strong>
              {{ ClientDetail.zipCode || "N/A" }}
            </div>
          </div>

          <h4 v-if="$page.props.auth.role == 'admin'" class="text-2xl font-small text-custom-sky mb-2">User Information
          </h4>
          <div v-if="$page.props.auth.role == 'admin'"
            class="grid sm:grid-cols-1 md:grid-cols-2 gap-2 text-gray-700 mb-10">
            <div>
              <strong class="text-lg">Phone: </strong>
              {{ ClientDetail.user?.phone }}
            </div>
            <div>
              <strong class="text-lg">Name: </strong>
              {{ ClientDetail.user?.first_name }} {{ ClientDetail.user?.last_name }}
            </div>
            <div>
              <strong class="text-lg">Email: </strong>
              {{ ClientDetail.user?.email }}
            </div>
          </div>

          <h4 v-if="$page.props.auth.role == 'admin'" class="text-2xl font-small text-custom-sky mb-2">Call Information
          </h4>
          <div v-if="$page.props.auth.role == 'admin'"
            class="grid sm:grid-cols-1 md:grid-cols-2 gap-2 text-gray-700 mb-10">
            <div>
              <strong class="text-lg">Call Date: </strong>
              {{ ClientDetail.call?.call_taken }}
            </div>
            <div>
              <strong class="text-lg">Ring Duration: </strong>
              {{
                String(Math.floor(ClientDetail.call?.ringing_duration / 60)).padStart(
                  2,
                  "0"
                ) +
                ":" +
                String(ClientDetail.call?.ringing_duration % 60).padStart(2, "0")
              }}
            </div>
            <div>
              <strong class="text-lg">Connected Duration: </strong>
              {{
                String(
                  Math.floor(ClientDetail.call?.call_duration_in_seconds / 60)
                ).padStart(2, "0") +
                ":" +
                String(ClientDetail.call?.call_duration_in_seconds % 60).padStart(
                  2,
                  "0"
                )
              }}
            </div>
            <div>
              <strong class="text-lg">Vertical: </strong>
              {{ ClientDetail.call?.call_type?.type }}
            </div>
          </div>
        </div>

        <div class="flex justify-end">
          <PrimaryButton @click="close"> Close </PrimaryButton>
        </div>
      </div>
    </div>
  </div>

  <div v-else class="relative w-full max-w-4xl max-h-full mx-auto">
    <div class="relative bg-white border border-gray-300 rounded-lg shadow-lg">
      <div class="flex items-start justify-between p-4 border-b border-gray-300 rounded-t">
        <h3 class="text-xl font-small text-gray-700">Edit Client Details</h3>
        <button @click="close" type="button"
          class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center"
          data-modal-hide="defaultModal">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
      </div>
      <form v-if="form" class="p-6">
        <h4 class="text-2xl font-small text-gray-700 mb-2">Personal Details</h4>
        <div class="grid sm:grid-cols-1 md:grid-cols-2 gap-2 text-gray-700 mb-6">
          <div>
            <label class="text-lg">First Name:</label>
            <TextInput type="text" name="first_name" id="first_name" class="w-full" placeholder="John" required
              v-model="form.first_name" />
          </div>
          <div>
            <label class="text-lg">Last Name:</label>
            <TextInput type="text" name="last_name" id="last_name" class="w-full" placeholder="Doe" required
              v-model="form.last_name" />
          </div>
          <div>
            <label class="text-lg">Date of Birth:</label>
            <!-- <TextInput type="text" name="dob" id="dob" class="w-full" required v-model="form.dob" /> -->
            <VueDatePicker  v-model="form.dob"
                    format="dd-MMM-yyyy" :maxDate="maxDate" auto-apply>
                </VueDatePicker>
          </div>
          
        </div>
        <label class="text-lg ">Locked Status:</label>
        <div v-if="$page.props.auth.role == 'admin'" class="grid sm:grid-cols-1 md:grid-cols-2 gap-2 text-gray-700 mb-6">
          <div class="flex mt-1">
                <div class="flex items-center">
                    <input id="link-radio" type="radio" v-model="form.unlocked"  value="0" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="link-radio"  class="ms-2 text-sm  font-medium text-gray-900 dark:text-gray-300"> Locked</label>
                </div>
                <div class="flex ml-5 items-center">
                    <input id="link-radio-2" type="radio" v-model="form.unlocked" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="link-radio-2" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">UnLocked</label>
                </div>
            </div>
        </div>
        <h4 class="text-2xl font-small text-custom-sky mb-2">Contact Information</h4>
        <div class="grid sm:grid-cols-1 md:grid-cols-2 gap-2 text-gray-700 mb-10">
          <div>
            <label class="text-lg">Phone:</label>
            <TextInput type="text" name="phone" id="phone" class="w-full" required v-model="form.phone" />
          </div>
          <div>
            <label class="text-lg">Email:</label>
            <TextInput type="email" name="email" id="email" class="w-full" placeholder="john@example.com" required
              v-model="form.email" />
          </div>
          <div>
            <label class="text-lg">Address:</label>
            <TextInput type="text" name="address" id="address" class="w-full" placeholder="10001" required
              v-model="form.address" />
          </div>
          <div class="w-full">
            <label for="state" class="block mb-2 text-sm font-medium text-gray-700">State</label>
            <select name="state" id="state" class="select-custom" v-model="form.state" required>
              <option v-for="(state, index) in states" :key="index" :value="state.full_name">
                {{ state.full_name }}
              </option>
            </select>
          </div>
          <div>
            <label class="text-lg">Zip Code:</label>
            <TextInput type="text" name="zip" id="zip" class="w-full" placeholder="10001" required
              v-model="form.zipCode" />
          </div>
        </div>

        <h4 class="text-2xl font-small text-custom-sky mb-2">Other Information</h4>
        <div class="grid sm:grid-cols-1 md:grid-cols-2 gap-2 text-gray-700 mb-10">
          <div>
            <label class="text-lg">Status:</label>
            <select name="status" id="status" class="select-custom" v-model="form.status" required>
              <option selected="" disabled="" value="">Select status</option>
              <option value="Sale - Simplified Issue">Sale - Simplified Issue</option>
              <option value="Sale - Guaranteed Issue">Sale - Guaranteed Issue</option>
              <option value="Follow Up Needed">Follow Up Needed</option>
              <option value="Quoted - Not Interested">Quoted - Not Interested</option>
              <option value="Not Interested">Not Interested</option>
              <option value="Transfer Handoff Too Long">Transfer Handoff Too Long</option>
              <option value="Client Hung Up">Client Hung Up</option>
              <option value="No Income">No Income</option>
              <option value="Wrong State">Wrong State</option>
              <option value="Not Qualified Age">Not Qualified Age</option>
              <option value="Not Qualified Nursing Home">
                Not Qualified Nursing Home
              </option>
              <option value="Not Qualified Memory Issues">
                Not Qualified Memory Issues
              </option>
              <option value="Language Barrier">Language Barrier</option>
              <option value="Do Not Call">Do Not Call</option>
              <option value="Dead Air/No Response">Dead Air/No Response</option>
              <option value="Thought Was Free">Thought Was Free</option>
            </select>
          </div>
        </div>

        <div class="flex justify-end mt-6">
          <PrimaryButton type="submit" @click.prevent="saveChanges">
            <global-spinner :spinner="isLoading" /> Save Changes
          </PrimaryButton>
          <PrimaryButton @click.prevent="close" type="button" class="ml-3">
            Close
          </PrimaryButton>
        </div>
      </form>
    </div>
  </div>
</template>
