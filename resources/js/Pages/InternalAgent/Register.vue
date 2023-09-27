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

let step = ref(0);
let firstStepErrors = ref({});
let uiEmailValidation = ref({
  "isValid": false
});

let insuranceTypes = ref([
  "Auto Insurance",
  "Final Expense",
  "U65 Health",
  "ACA",
  "Medicare/Medicaid",
  "Debt Relief",
]);

let selectedTypes = ref([]);

let selectedTypesWithStates = ref({
  "Auto Insurance": [],
  "Final Expense": [],
  "U65 Health": [],
  ACA: [],
  "Medicare/Medicaid": [],
  "Debt Relief": [],
});

let onTypeUpdate = (event) => {
  if (event.target.checked) {
    selectedTypes.value.push(Number(event.target.value));
  } else {
    selectedTypes.value.splice(
      selectedTypes.value.indexOf(Number(event.target.value)),
      1
    );
    delete form.typesWithStates[Number(event.target.value)];
  }
};

let customLabel = function (options, select$) {
  let labels = options.map((option) => option.label).join(", ");

  return labels;
};

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
  typesWithStates: props.callTypes.reduce((acc, obj) => {
    acc[obj.id] = [];
    return acc;
  }, {}),
  consent:false,
});
let isFormValid = ref(true);
const areAllArraysEmpty = computed(() => {
  const arrays = Object.values(form.typesWithStates); // Get all arrays
  return arrays.every((array) => array.length === 0); // Check if all arrays are empty
});
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
let text = ref([]);

let check = () => {

};

let errors = ref(null);

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
        router.visit('/login')
        isLoading.value = false
      })
      .catch((error) => {
        if (error.response) {
          // The request was made and the server responded with a status code
          // that falls out of the range of 2xx
          errors.value = error.response.data.errors;
          isLoading.value = false

          firstStepErrors.value = error.response.data.errors;
          if(error.response.data.errors.typesWithStates){
            step.value = 1
          }else{
            step.value = 0
          }
          if (error.response.status === 400) {
            uiEmailValidation.value.isValid = false;
            // Handle validation errors here.
            firstStepErrors.value = error.response.data.errors;
            isLoading.value = false
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
        Start Receiving Live Calls Now!
      </div>
      <div class="text-md text-blue-400 font-semibold text-center px-10 mb-6">
        No risk, no contracts, and no long-term commitment. Cancel anytime,
        hassle-free.
      </div>
    </template>

    <form @submit.prevent="submit">
      <div v-show="step === 0">
        <div>
          <GuestInputLabel for="first_name" value="First Name" />

          <GuestTextInput id="first_name" type="text" class="mt-1 block w-full" v-model="form.first_name" minlength="2"
            required pattern="[A-Za-z]{1,32}" onkeyup="this.value=this.value.replace(/[0-9]/g,'');" />
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

          <GuestTextInput id="phone" type="text" class="mt-1 block w-full" v-model="form.phone" minlength="10" />

          <!-- <InputError class="mt-2" :message="form.errors.phone" /> -->
          <div v-if="firstStepErrors.phone" class="text-red-500" v-text="firstStepErrors.phone[0]"></div>
        </div>

        <div class="mt-4">
          <GuestInputLabel for="password" value="Password" />

          <GuestTextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password"
            autocomplete="new-password" />

          <!-- <InputError class="mt-2" :message="form.errors.password" /> -->
          <div v-if="firstStepErrors.password" class="text-red-500" v-text="firstStepErrors.password[0]"></div>
        </div>

        <div class="mt-4">
          <GuestInputLabel for="password_confirmation" value="Confirm Password" />

          <GuestTextInput id="password_confirmation" type="password" class="mt-1 block w-full"
            v-model="form.password_confirmation" autocomplete="new-password" />

          <InputError class="mt-2" :message="form.errors.password_confirmation" />
        </div>
        <div class="flex pt-6 box-shadow">
          <input id="checked-checkbox" type="checkbox" v-model="form.consent"
            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
          <label for="checked-checkbox" class="ml-2 text-xs font-medium text-gray-900 dark:text-gray-400">
            By clicking Continue, I agree to email marketing, the Terms and
            Conditions (which include mandatory arbitration), Privacy Policy,
            and site visit recordation by Trusted Form and Jornaya. I provide my
            express written consent and electronic signature to receive
            monitored or recorded phone sales calls and text messages from
            AllCalls.io regarding products and services including Medicare
            Supplement, Medicare Advantage, and Prescription Drug Plans on the
            landline or mobile number I provided even if I am on a federal or
            State do not call registry. I confirm that the phone number set
            forth above is accurate and I am the regular user of the phone. I
            understand these calls may be generated using an automated dialing
            system and may contain pre-recorded and artificial voice messages
            and that consenting is not required to receive a quote or speak with
            an agent and that I can revoke my consent at any time by any
            reasonable means. To receive a quote without providing consent,
            please call (866) 523-1718. For SMS message campaigns: Text STOP to
            stop and HELP for help. Msg and data rates may apply. Periodic
            messages; max. 30/month.
          </label>
        </div>
        <div v-if="firstStepErrors.consent" class="text-red-500 ml-5" v-text="firstStepErrors.consent[0]"></div>
        <div class="flex items-center justify-end mt-4" :class="{ 'opacity-25': form.processing || isLoading }"
          :disabled="form.processing">
          <button type="button" class="button-custom px-3 py-2 rounded-md" :class="{ 'opacity-25': isFormValid || isLoading }"
                  :disabled="isFormValid || isLoading" @click="StepChange(1)">
            <global-spinner :spinner="isLoading" />Next
          </button>
        </div>
      </div>
      <div v-show="step === 1">
        <div class="px-0">
          <div class="mt-4">
            <GuestInputLabel class="mb-3" for="insurance_type" value="What types of calls do you want to receive?" />

            <div v-for="(callType, index) in callTypes" :key="callType.id" class="mb-4">
              <input :id="`insurance_type_${callType.id}`" type="checkbox"
                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                :checked="selectedTypes.includes(callType.id)" @change="onTypeUpdate" :value="callType.id" />

              <label class="ml-2 text-md font-medium text-custom-indigo" :for="`insurance_type_${callType.id}`"
                v-text="callType.type"></label>

              <div v-if="selectedTypes.includes(callType.id)" class="pt-2 mb-8">
                <label class="ml-2 text-xs font-medium">States you're licensed in:</label>
                <Multiselect :options="stateOptions" v-model="form.typesWithStates[callType.id]" track-by="value"
                  label="label" mode="tags" :close-on-select="false" placeholder="Select a state">
                </Multiselect>
              </div>
            </div>

            <InputError class="mt-2" :message="form.errors.insurance_type" />
            <div v-if="firstStepErrors">
              <div v-if="firstStepErrors.typesWithStates" class="text-red-500"
                v-text="firstStepErrors.typesWithStates[0]"></div>
            </div>
          </div>
        </div>
        <div class="px-0 pb-6">
          <div class="flex justify-between">
            <div class="mt-4">
              <a v-if="step != 0" href="#" @click.prevent="StepChange(0)" class="button-custom-back px-3 py-2 rounded-md">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor" class="w-4 h-4 mr-2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                </svg>
                Back</a>
            </div>
            <div class="mt-4">
              <button @click="submit" type="button"  class="button-custom px-3 py-2 rounded-md"
                :class="{ 'opacity-25': areAllArraysEmpty || isLoading }" :disabled="areAllArraysEmpty || isLoading">
                <global-spinner :spinner="isLoading" /> Register
              </button>
            </div>
          </div>
        </div>
      </div>
    </form>

    <template v-slot:titles>
      <div class="text-4xl lg:text-5xl xl:text-8xl text-white mb-10">
        Getting Started is Easy.<span class="text-custom-green">Create your account today!</span>
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

/*
.carousel__item {
  min-height: 200px;
  width: 100%;
  background-color: var(--vc-clr-primary);
  color: var(--vc-clr-white);
  font-size: 20px;
  border-radius: 8px;
  display: flex;
  justify-content: center;
  align-items: center;
}

.carousel__slide {
  padding: 10px;
}

.carousel__prev,
.carousel__next {
  box-sizing: content-box;
  border: 5px solid white;
} */
</style>
