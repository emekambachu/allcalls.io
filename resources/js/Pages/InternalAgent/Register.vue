<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import GuestInputLabel from "@/Components/GuestInputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import GuestTextInput from "@/Components/GuestTextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import NewGuestLayout from "@/Layouts/NewGuestLayout.vue";
import Footer from "@/Components/Footer.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import Multiselect from "@vueform/multiselect";
import { ref, reactive, computed, watch } from "vue";
// import { useVeeValidate } from '@vee-validate/vue3';
import axios from "axios";
// If you are using PurgeCSS, make sure to whitelist the carousel CSS classes
import "vue3-carousel/dist/carousel.css";
import { Carousel, Slide, Pagination, Navigation } from "vue3-carousel";
import { router } from "@inertiajs/vue3"
import { toaster } from "@/helper.js";
let step = ref(0);
let firstStepErrors = ref({});
let uiEmailValidation = ref({
  "isValid": false
});

let props = defineProps({
  callTypes: Array,
  states: Array,
});

let stateOptions = computed(() => {
  return props.states.map((state) => {
    return {
      value: state.id,
      label: state.full_name,
    };
  });
});

let form = useForm({
  first_name: "",
  last_name: "",
  email: "",
  password: "",
  password_confirmation: "",
  phone: "",
  consent: false,
});
let isFormValid = ref(true);

watch(
  () => [
    form.first_name,
    form.last_name,
    form.email,
    form.password,
    form.password_confirmation,
    form.phone,
    form.consent
  ],
  async ([firstName, lastName, email, password, passwordConfirmation, phone, consent]) => {
    if (
      firstName === "" ||
      lastName === "" ||
      email === "" ||
      password === "" ||
      passwordConfirmation === "" ||
      password !== passwordConfirmation || // Check for password mismatch
      phone === "" ||
      consent === false
    ) {
      isFormValid.value = true;
    } else {
      isFormValid.value = false;
    }
  }
);



let validateEmail = (email) => {
  return /\S+@\S+\.\S+/.test(email); // Simple regex for email validation
}
const isLoading = ref(false);
let submit = () => {

  isLoading.value = true
  if (validateEmail(form.email)) {
    return axios
      .post("/internal-agent/register", form)
      .then((response) => {
        toaster("success", response.data.message);
        router.visit('/dashboard')
        isLoading.value = false
      })
      .catch((error) => {
        if (error.response) {
          isLoading.value = false
          if (error.response.status === 400) {
            firstStepErrors.value = error.response.data.errors;
            uiEmailValidation.value.isValid = false;
            isLoading.value = false
          } else if(error.response.status === 401) {
            toaster("error", error.response.data.message);
            router.visit('/')
          }
        }
      });
  }
  else {
    uiEmailValidation.value.isValid = true;
    isLoading.value = false
  }
};
let StepChange = (val) => {
  step.value = val
}
// let goBack = () => {
//   step.value = 0;
// };
</script>
<style scoped>
.active\:bg-gray-900:active {
  color: white;
}

.hover\:drop-shadow-2xl:hover {
  background-color: white;
  color: black;
}

.button-custom {
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  font-weight: 600;
  border-width: 1px;
  align-items: center;
  display: inline-flex;
  border-color: rgb(107 114 128 / var(--tw-border-opacity));
  background-color: #03243d;
  color: #3cfa7a;
  cursor: pointer;
}

.button-custom:hover {
  transition-duration: 150ms;
  background-color: white;
  color: black;
}

.button-custom-back {
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  font-weight: 600;
  border-width: 1px;
  align-items: center;
  display: inline-flex;
  border-color: rgb(107 114 128 / var(--tw-border-opacity));
}

.button-custom-back:hover {
  background-color: #03243d;
  color: #3cfa7a;
  transition-duration: 150ms;
}

.blurred-overlay {
  backdrop-filter: blur(10px);
  /* Adjust the blur intensity as needed */
  background-color: rgba(0, 0, 0, 0.6);
  /* Adjust the background color and opacity as needed */
}
</style>
<template>
  <!-- <GuestLayout> -->
  <NewGuestLayout>

    <Head title="Register" />

    <template v-slot:loadingText>
      <div class="px-10 text-center text-4xl xl:text-5xl text-custom-white font-extrabold tracking-tighter">
        Start Earning High Commissions Today!
      </div>
      <div class="text-md text-blue-400 font-semibold text-center px-10 mb-6">
        Receive Competitive Top-Level Commissions - Fully Vested.
      </div>
    </template>

    <form @submit.prevent="submit">
      <div v-show="step === 0">
        <div>
          <GuestInputLabel for="first_name" value="First Name" />

          <GuestTextInput id="first_name" type="text" class="mt-1 block w-full" v-model="form.first_name" minlength="2"
            required pattern="[A-Za-z]{1,32}" onkeyup="this.value=this.value.replace(/[0-9]/g,'');" />
          <div v-if="firstStepErrors.first_name" class="text-red-500" v-text="firstStepErrors.first_name[0]"></div>
        </div>

        <div class="mt-4">
          <GuestInputLabel for="last_name" value="Last Name" />

          <GuestTextInput id="last_name" type="text" class="mt-1 block w-full" v-model="form.last_name" required
            pattern="[A-Za-z]{1,32}" onkeyup="this.value=this.value.replace(/[0-9]/g,'');" />

          <div v-if="firstStepErrors.last_name" class="text-red-500" v-text="firstStepErrors.last_name[0]"></div>
        </div>

        <div class="mt-4">
          <GuestInputLabel for="email" value="Email" />

          <GuestTextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
            pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}" />
          <div v-if="uiEmailValidation.isValid" class="text-red-500">Please enter valid email address.</div>
          <div v-if="firstStepErrors.email" class="text-red-500" v-text="firstStepErrors.email[0]"></div>
        </div>

        <div class="mt-4">
          <GuestInputLabel for="phone" value="Phone" />

          <GuestTextInput id="phone" type="text" placeholder="+1 (000) 000-0000" class="mt-1 block w-full" v-model="form.phone" minlength="10" />

          <div v-if="firstStepErrors.phone" class="text-red-500" v-text="firstStepErrors.phone[0]"></div>
        </div>

        <div class="mt-4">
          <GuestInputLabel for="password" value="Password" />

          <GuestTextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password"
            autocomplete="new-password" />

          <div v-if="firstStepErrors.password" class="text-red-500" v-text="firstStepErrors.password[0]"></div>
        </div>

        <div class="mt-4">
          <GuestInputLabel for="password_confirmation" value="Confirm Password" />

          <GuestTextInput id="password_confirmation" type="password" class="mt-1 block w-full"
            v-model="form.password_confirmation" autocomplete="new-password" />

          <InputError v-if="firstStepErrors.password_confirmation" class="mt-2" :message="firstStepErrors.password_confirmation[0]" />
        </div>
        <div class="flex pt-6 box-shadow">
          <input id="checked-checkbox" type="checkbox" v-model="form.consent"
            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
          <label for="checked-checkbox" class="ml-2 text-xs font-medium text-gray-900 dark:text-gray-400">
            By checking this box, I verify that this is my mobile number and that I would like to sign up to receive
            messages from “AllCalls.io” program by AllCalls LLC. I understand that I am not required to provide my consent
            as a condition of purchasing any products or services. Msg freq may vary. Msg data rates may apply. Reply HELP
            for help or STOP to optout. Read <a href="https://allcalls.io/terms.php">Terms and Conditions</a>. Read <a
              href="https://allcalls.io/privacy.php">Privacy Policy</a>.
          </label>
        </div>
        <div v-if="firstStepErrors.consent" class="text-red-500 ml-5" v-text="firstStepErrors.consent[0]"></div>

        <div class="flex items-center justify-end mt-4" :class="{ 'opacity-25': form.processing || isLoading }"
          :disabled="form.processing">
          <button @click="submit" type="button" class="button-custom px-3 py-2 rounded-md"
            :class="{ 'opacity-25': isFormValid || isLoading }" :disabled="isFormValid || isLoading">
            <global-spinner :spinner="isLoading" /> Register
          </button>
        </div>
      </div>

    </form>

    <template v-slot:titles>
      <div class="text-4xl lg:text-5xl xl:text-8xl text-white mb-10">
        Welcome to AllCalls and <br><span class="text-custom-green">Congratulation on Your New Career.</span>
      </div>
    </template>

    <template v-slot:subtitles>
      <div class="text-custom-blue font-bold text-2xl lg:text-2xl xl:text-5xl text-3xl">
        Try our Calls for Yourself!
      </div>

      <div class="text-custom-blue text-sm md:text-lg lg:text-2xl mt-6 font-bold">
        We pride ourselves on ease of use. Once you have created an account, you
        will be able to select what kind of calls you would like to receive and
        begin speaking with customers right away! Create an account to get
        started.
      </div>
    </template>
  </NewGuestLayout>
  <!-- </GuestLayout> -->

  <Footer />
</template>

<style src="@vueform/multiselect/themes/default.css"></style>
<style>
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

input[type="number"] {
  -moz-appearance: textfield;
}

.multiselect {
  color: black !important;
  border: none;
  border-radius: 10px;
}

.multiselect-wrapper {
  background-color: #d7d7d7;
  border-radius: 5px;
}
</style>
