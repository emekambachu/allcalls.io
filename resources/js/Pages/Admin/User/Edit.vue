<script setup>
import { ref, reactive, defineEmits, onMounted, watch } from "vue";
import TextInput from "@/Components/TextInput.vue";
import { router } from "@inertiajs/vue3"
import GuestTextInput from "@/Components/GuestTextInput.vue";
import GuestInputLabel from "@/Components/GuestInputLabel.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import axios from "axios";
let props = defineProps({
  showModal: {
    type: Boolean,
  },

  userDetail: {
    type: Object,
  },
});
let emit = defineEmits(["close"]);
let originalClient = props.userDetail;

let close = () => {

  emit("close");

};
let firstStepErrors = ref({});
let uiEmailValidation = ref({
  "isValid": false
});
let form = useForm({
  first_name: "",
  last_name: "",
  email: "",
  password: "",
  phone: "",
});

form = JSON.parse(JSON.stringify(props.userDetail));
watch(() => props.showModal, async (newValue) => {
  if (newValue) {
    // Call APIs here and update the form data accordingly
    form = JSON.parse(JSON.stringify(props.userDetail));
  }
});
let validateEmail = (email) => {
  return /\S+@\S+\.\S+/.test(email); // Simple regex for email validation
}
let saveChanges = () => {

  if (validateEmail(form.email)) {

    return axios
      .post(`/admin/customer/${form.id}`, form)
      .then((response) => {
      router.visit('/admin/customers')
      })
      .catch((error) => {
        if (error.response) {
          // The request was made and the server responded with a status code
          // that falls out of the range of 2xx
          firstStepErrors.value = error.response.data.errors;
          if (error.response.status === 400) {
            uiEmailValidation.value.isValid = false;
            // Handle validation errors here.
            console.log(error.response.data);
            firstStepErrors.value = error.response.data.errors;
          } else {
            // Handle other types of errors.
            console.log("Other errors", error.response.data);
          }
        } else if (error.request) {
          // The request was made but no response was received
          console.log("No response received", error.request);
        } else {
          // Something happened in setting up the request that triggered an Error
          console.log("Error", error.message);
        }
      });
  }
  else {
    uiEmailValidation.value.isValid = true;
  }

  // console.log('userDetail save changes', props.userDetail);
  // console.log('form upon save changes', form);
  // router.visit(`/admin/customer/${form.id}`, {
  //   method: 'PATCH',
  //   data: form,
  // });
}

</script>

<template>
  <Transition name="modal" enter-active-class="transition ease-out duration-300 transform"
    enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    enter-to-class="opacity-100 translate-y-0 sm:scale-100" leave-active-class="transition ease-in duration-200 transform"
    leave-from-class="opacity-100 translate-y-0 sm:scale-100"
    leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
    <div id="defaultModal" v-if="showModal" tabindex="-1"
      class="flex items-center justify-center fixed inset-0 z-50 w-full h-full overflow-x-hidden overflow-y-auto max-h-full mx-4 sm:mx-0">
      <div class="fixed inset-0 bg-black opacity-60"></div>
      <!-- This is the overlay -->


      <div class="relative w-full max-w-4xl max-h-full mx-auto">
        <div class="relative bg-white border border-gray-300 rounded-lg shadow-lg">
          <div class="flex items-start justify-between p-4 border-b border-gray-300 rounded-t">
            <h3 class="text-xl font-semibold text-gray-700">
              Edit Customer Details
            </h3>
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
          <form @submit.prevent="saveChanges" class="p-6">

            <div>
              <GuestInputLabel for="first_name" value="First Name" />

              <GuestTextInput id="first_name" type="text" class="mt-1 block w-full" v-model="form.first_name"
                minlength="2" required pattern="[A-Za-z]{1,32}" onkeyup="this.value=this.value.replace(/[0-9]/g,'');" />
              <!-- <InputError class="mt-2" :message="form.errors.first_name" /> -->
              <div v-if="firstStepErrors.first_name" class="text-red-500" v-text="firstStepErrors.first_name[0]"></div>
            </div>
            <div class="mt-4">
              <GuestInputLabel for="last_name" value="Last Name" />

              <GuestTextInput id="last_name" type="text" class="mt-1 block w-full" v-model="form.last_name" required
                pattern="[A-Za-z]{1,32}" onkeyup="this.value=this.value.replace(/[0-9]/g,'');" />

              <!-- <InputError class="mt-2" :message="form.errors.last_name" /> -->
              <div v-if="firstStepErrors.last_name" class="text-red-500" v-text="firstStepErrors.last_name[0]"></div>
            </div>

            <div class="mt-4">
              <GuestInputLabel for="email" value="Email" />

              <GuestTextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}" />
              <div v-if="uiEmailValidation.isValid" class="text-red-500">Please enter valid email address.</div>
              <!-- <InputError class="mt-2" :message="form.errors.email" /> -->
              <div v-if="firstStepErrors.email" class="text-red-500" v-text="firstStepErrors.email[0]"></div>
            </div>

            <div class="mt-4">
              <GuestInputLabel for="phone" value="Phone" />

              <GuestTextInput id="phone" type="text" class="mt-1 block w-full" v-model="form.phone" minlength="10"
                maxlength="10" onkeyup="this.value=this.value.replace(/[^\d]/,&#39;&#39;)" />

              <!-- <InputError class="mt-2" :message="form.errors.phone" /> -->
              <div v-if="firstStepErrors.phone" class="text-red-500" v-text="firstStepErrors.phone[0]"></div>
            </div>
            <!-- <h4 class="text-2xl font-semibold text-custom-sky mb-2">
              Contact Information
            </h4>
            <div class="grid sm:grid-cols-1 md:grid-cols-2 gap-2 text-gray-700 mb-10">
              <div>
                <label class="text-lg">Phone:</label>
                <TextInput type="text" name="phone" id="phone" class="w-full" required v-model="form.phone" />
              </div>
              <div>
                <label class="text-lg">Email:</label>
                <TextInput type="email" name="email" id="email" class="w-full" placeholder="john@example.com" required
                  v-model="form.email" readonly/>
              </div>
            </div>

            <h4 class="text-2xl font-semibold text-custom-sky mb-2">
              Other Information
            </h4>
            <div class="grid sm:grid-cols-1 md:grid-cols-2 gap-2 text-gray-700 mb-10">
              <div>
                <label class="text-lg">Balance:</label>
                <TextInput type="number" step="any" name="balance" id="balance" class="w-full" placeholder="0.0"
                  v-model="form.balance" />
              </div>
            </div> -->

            <!-- (The rest of your sections would follow a similar structure) -->

            <div class="flex justify-end mt-6">
              <button type="submit"
                class="border border-gray-400 ease-in cursor-pointer bg-white hover:shadow-2xl hover:text-gray-700 rounded px-3 py-3 font-bold text-md text-gray-700"
                @click.prevent="saveChanges">
                Save Changes
              </button>
              <button @click.prevent="close" type="button"
                class="ml-4 border border-gray-400 ease-in cursor-pointer bg-white hover:shadow-2xl hover:text-gray-700 rounded px-3 py-3 font-bold text-md text-gray-700">
                Close
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </Transition>
</template>