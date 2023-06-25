<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import NewGuestLayout from "@/Layouts/NewGuestLayout.vue";
import Footer from "@/Components/Footer.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import Multiselect from "@vueform/multiselect";
import { ref, reactive, computed } from "vue";
// import { useVeeValidate } from '@vee-validate/vue3';
import axios from 'axios';

let step = ref(0);
let firstStepErrors = ref({});

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
      label: state.name,
    };
  });
});

let form = useForm({
  first_name: "",
  last_name: "",
  email: "",
  password: "",
  password_confirmation: "",
  insurance_type: "Life Insurance",
  license_state: "",
  phone: "",
  consent: false,
  typesWithStates: props.callTypes.reduce((acc, obj) => {
    acc[obj.id] = [];
    return acc;
  }, {}),
});

let text = ref([]);

let check = () => {

}

// next() { 
//   this.step = 1
//   // alert ('its working though')
// }

let submit = () => {
  if (step.value === 0) {
    // return form.post('/register-step-one', {
    //   onFinish: () => step.value = 1,
    // })

    return axios.post('/register-step-one', form)
    .then(response => {
        // This block handles the success case.
        console.log('Validation passed', response.data);
    })
    .catch(error => {
        // This block handles the error case.
        // Error details are in error.response.data.

        if (error.response) {
            // The request was made and the server responded with a status code
            // that falls out of the range of 2xx
            console.log(error.response.data);

            if (error.response.status === 400) {
                // Handle validation errors here.
                console.log('Validation errors', error.response.data.errors);
                firstStepErrors.value = error.response.data.errors;
            } else {
                // Handle other types of errors.
                console.log('Other errors', error.response.data);
            }
        } else if (error.request) {
            // The request was made but no response was received
            console.log('No response received', error.request);
        } else {
            // Something happened in setting up the request that triggered an Error
            console.log('Error', error.message);
        }
    });
  }
  form.post(route("register"), {
    onFinish: () => form.reset("password", "password_confirmation"),
  });
};
</script>

<template>
  <!-- <GuestLayout> -->
  <NewGuestLayout>

    <Head title="Register" />

    <template v-slot:smallStepOneLoading >
      <div v-show="step === 0" class="flex flex-col items-center w-full">
        <div class="w-[80%] max-w-xl bg-gray-200 rounded-full h-1.5 dark:bg-gray-700 mb-1">
            <div class="bg-blue-600 h-1.5 rounded-full dark:bg-blue-500" style="width: 45%"></div>
        </div>
        <div class="text-sm text-gray-500">Create Your Account: Step 1 of 2</div>
      </div>

      <div v-show="step === 1" class="flex flex-col items-center w-full">
        <div class="w-[80%] max-w-xl bg-gray-200 rounded-full h-1.5 dark:bg-gray-700 mb-1">
            <div class="bg-blue-600 h-1.5 rounded-full dark:bg-blue-500" style="width: 95%"></div>
        </div>
        <div class="text-sm text-gray-500">Create Your Account: Step 2 of 2</div>
      </div>
    </template>

    <template v-slot:largeStepOneLoading >
      <div v-show="step === 0" class="flex flex-col items-center w-full">
        <div class="w-full bg-gray-300 rounded-full h-1.5  mb-1">
            <div class="bg-blue-600 h-1.5 rounded-full dark:bg-blue-500" style="width: 45%"></div>
        </div>
        <div class="text-sm text-gray-300">Create Your Account: Step 1 of 2</div>
      </div>

      <div v-show="step === 1" class="flex flex-col items-center w-full">
        <div class="w-full bg-gray-300 rounded-full h-1.5  mb-1">
            <div class="bg-blue-600 h-1.5 rounded-full dark:bg-blue-500" style="width: 95%"></div>
        </div>
        <div class="text-sm text-gray-300">Create Your Account: Step 2 of 2</div>
      </div>
    </template>

    <form @submit.prevent="submit">
      <div v-show="step === 0">

        <div>
          <InputLabel for="first_name" value="First Name" />

          <TextInput
            id="first_name"
            type="text"
            class="mt-1 block w-full"
            v-model="form.first_name"
            minlength="2" 
            required 
            pattern="[A-Za-z]{1,32}" 
            onkeyup="this.value=this.value.replace(/[0-9]/g,'');"
          />
          <!-- <InputError class="mt-2" :message="form.errors.first_name" /> -->
          <div v-if="firstStepErrors.first_name" class="text-red-500" v-text="firstStepErrors.first_name[0]"></div>
        </div>

        <div class="mt-4">
          <InputLabel for="last_name" value="Last Name" />

          <TextInput
            id="last_name"
            type="text"
            class="mt-1 block w-full"
            v-model="form.last_name"
            required 
            pattern="[A-Za-z]{1,32}" 
            onkeyup="this.value=this.value.replace(/[0-9]/g,'');"
          />

          <!-- <InputError class="mt-2" :message="form.errors.last_name" /> -->
          <div v-if="firstStepErrors.last_name" class="text-red-500" v-text="firstStepErrors.last_name[0]"></div>
        </div>

        <div class="mt-4">
          <InputLabel for="email" value="Email" />

          <TextInput
            id="email"
            type="email"
            class="mt-1 block w-full"
            v-model="form.email"
            required
            pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}"
          />

          <!-- <InputError class="mt-2" :message="form.errors.email" /> -->
          <div v-if="firstStepErrors.email" class="text-red-500" v-text="firstStepErrors.email[0]"></div>
        </div>

        <div class="mt-4">
          <InputLabel for="phone" value="Phone" />

          <TextInput
            id="phone"
            type="text"
            class="mt-1 block w-full"
            v-model="form.phone"
            minlength="10" 
            maxlength="10" 
            onkeyup="this.value=this.value.replace(/[^\d]/,&#39;&#39;)" 
          />

          <!-- <InputError class="mt-2" :message="form.errors.phone" /> -->
          <div v-if="firstStepErrors.phone" class="text-red-500" v-text="firstStepErrors.phone[0]"></div>
        </div>

        <div class="mt-4">
          <InputLabel for="password" value="Password" />

          <TextInput
            id="password"
            type="password"
            class="mt-1 block w-full"
            v-model="form.password"
            autocomplete="new-password"
          />

          <!-- <InputError class="mt-2" :message="form.errors.password" /> -->
          <div v-if="firstStepErrors.password" class="text-red-500" v-text="firstStepErrors.password[0]"></div>
        </div>

        <div class="mt-4">
          <InputLabel for="password_confirmation" value="Confirm Password" />

          <TextInput
            id="password_confirmation"
            type="password"
            class="mt-1 block w-full"
            v-model="form.password_confirmation"
            autocomplete="new-password"
          />

          <InputError
            class="mt-2"
            :message="form.errors.password_confirmation"
          />
        </div>

        <div class="flex items-center justify-end mt-4">
          <PrimaryButton type="button" class="ml-4" @click.prevent="submit"
            >Next</PrimaryButton
          >
        </div>
      </div>

      <div v-show="step === 1">
        <div class="mt-4">
          <InputLabel
            class="mb-3"
            for="insurance_type"
            value="What types of calls do you want to receive?"
          />

          <div v-for="callType in callTypes" :key="callType.id" class="mb-4">
            <input
              :id="`insurance_type_${callType.id}`"
              type="checkbox"
              class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
              @change="onTypeUpdate"
              :value="callType.id"
            />
            <label
              class="ml-2 text-md font-medium text-gray-900 dark:text-gray-300"
              :for="`insurance_type_${callType.id}`"
              v-text="callType.type"
            ></label>

            <div
              v-if="selectedTypes.includes(callType.id)"
              class="py-2 dark:text-white"
            >
              <label
                class="ml-2 text-xs font-medium text-gray-900 dark:text-gray-300"
                >States you're licensed in:</label
              >
              <Multiselect
                :options="stateOptions"
                v-model="form.typesWithStates[callType.id]"
                track-by="value"
                label="label"
                mode="tags"
                :close-on-select="false"
              >
              </Multiselect>
            </div>
          </div>

          <InputError class="mt-2" :message="form.errors.insurance_type" />
        </div>

        <div class="flex mt-10">
          <input
            id="checked-checkbox"
            type="checkbox"
            v-model="form.consent"
            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
          />
          <label
            for="checked-checkbox"
            class="ml-2 text-xs font-medium text-gray-900 dark:text-gray-400"
          >
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

        <InputError class="mt-2" :message="form.errors.consent" />

        <div class="flex items-center justify-end mt-4">
          <Link
            :href="route('login')"
            class="underline text-sm text-custom-blue hover:text-custom-green rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
          >
            Already registered?
          </Link>

          <PrimaryButton
            class="ml-4"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
          >
            Register
          </PrimaryButton>
        </div>
      </div>
    </form>



    
    <template v-slot:titles>
      <div class="text-4xl lg:text-5xl xl:text-8xl text-white mb-10 ">Getting Started is Easy.<span class="text-custom-green">Create your account today!</span></div>
    </template>
    
    <template v-slot:subtitles>
      <div class="text-custom-blue font-bold text-2xl lg:text-2xl xl:text-5xl text-3xl">Try our Calls for Yourself!</div>
      
      <div class="text-custom-blue text-sm md:text-lg lg:text-2xl mt-6 font-bold">
        We pride ourselves on ease of use. Once you have created an account, you will be able to select what kind of calls you would like to receive and begin speaking with customers right away! Create an account to get started.
      </div>
    </template>
    
  </NewGuestLayout>
  <!-- </GuestLayout> -->

  <Footer />

</template>

<style src="@vueform/multiselect/themes/default.css"></style>
<style>
.multiselect {
  color: black !important;
  border: none;
  border-radius: 10px;
}

.multiselect-wrapper {
  background-color: rgb(17 24 39 / var(--tw-bg-opacity));
  border-radius: 5px;
}
</style>
