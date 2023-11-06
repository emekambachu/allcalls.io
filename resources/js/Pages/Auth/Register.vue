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
import { ref, reactive, computed, watchEffect, onUnmounted , onMounted, inject  } from "vue";
// import { useVeeValidate } from '@vee-validate/vue3';
import axios from "axios";
// If you are using PurgeCSS, make sure to whitelist the carousel CSS classes
import "vue3-carousel/dist/carousel.css";
import { Carousel, Slide, Pagination, Navigation } from "vue3-carousel";
import { router } from "@inertiajs/vue3"
const countryList = inject('countryList');
// let countryList = ref(countries)
// console.log('countries',countryList.value);
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
  }
};

let customLabel = function (options, select$) {
  let labels = options.map((option) => option.label).join(", ");

  return labels;
};

let props = defineProps({
  callTypes: Array,
  states: Array,
  agentToken: {
    required: false,
    type: String,
  }
});


console.log(props);

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
  phone: "",
  phone_code:'+1',
  phone_country:'USA',
});
let text = ref([]);

let check = () => {

};

let errors = ref(null);

let validateEmail = (email) => {
  return /\S+@\S+\.\S+/.test(email); // Simple regex for email validation
}
const isLoading = ref(false);


// let goBack = () => {
//   step.value = 0;
// };










const search = ref('');
const isOpen = ref(false);

const toggleDropdown = () => {
  isOpen.value = !isOpen.value;
  search.value = ''
};
const filteredCountries = computed(() => {
  return countryList.filter(
    (country) =>
      country.name.toLowerCase().includes(search.value.toLowerCase()) ||
      country.codeName.toLowerCase().includes(search.value.toLowerCase())
  );
});

const selectCountry = (country) => {
  form.phone_country = country.codeName
  form.phone_code = '+' + country.code
  isOpen.value = false;
};
onMounted(() => {
  document.addEventListener('click', handleOutsideClick);
});

onUnmounted(() => {
  document.removeEventListener('click', handleOutsideClick);
});
const handleOutsideClick = (event) => {
  const dropdownElement = document.getElementById('dropdown_main_id');
  if (!dropdownElement.contains(event.target)) {
    // Call your desired function here
    closeDropDown();
  }
};
const closeDropDown = () => {
  isOpen.value = false
};

let submit = () => {
 
  isLoading.value = true
  if (validateEmail(form.email)) {

    let registerUrl = "/register";

    if (props.agentToken) {
      registerUrl += `?agentToken=${props.agentToken}`;
    }

    return axios
      .post(registerUrl, form)
      .then((response) => {

        let postRegistrationUrl = '/login';
        if (props.agentToken) {
          postRegistrationUrl = '/registration-steps?agentToken=' + props.agentToken
        }

        router.visit(postRegistrationUrl)
        isLoading.value = false
      })
      .catch((error) => {
        if (error.response) {
          // The request was made and the server responded with a status code
          // that falls out of the range of 2xx
          errors.value = error.response.data.errors;
          isLoading.value = false

          firstStepErrors.value = error.response.data.errors;
          if (error.response.status === 400) {
            uiEmailValidation.value.isValid = false;
            // Handle validation errors here.
            firstStepErrors.value = error.response.data.errors;
            isLoading.value = false
          } else {
            // Handle other types of errors.
            console.log("Other errors", error.response.data);
            isLoading.value = false
          }
        } else if (error.request) {
          // The request was made but no response was received
          console.log("No response received", error.request);
        } else {
          // Something happened in setting up the request that triggered an Error
          console.log("Error", error.message);
        }
        if(error){
          isLoading.value = false
        }
      });
  }
  else {
    uiEmailValidation.value.isValid = true;
    isLoading.value = false
  }
};
</script>

<template>
  <!-- <GuestLayout> -->
  <NewGuestLayout>

    <Head title="Register" />
    <template v-slot:smallStepRegister>
      <div
        class="px-10  text-center text-4xl font-black text-sky-900 tracking-tighter font-extrabold"
      >
        Start Receiving Live Calls Now!
      </div>
      <div
        class="text-md text-custom-blue font-semibold text-center px-10 mb-6"
      >
        No risk, no contracts, and no long-term commitment. Cancel anytime,
        hassle-free.
      </div>
    </template>
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

        <!-- <div class="mt-4">
          <GuestInputLabel for="phone" value="Phone" />

          <GuestTextInput id="phone" type="text" placeholder="+1 (000) 000-0000" class="mt-1 block w-full"
            v-model="form.phone" minlength="10" />

          <div v-if="firstStepErrors.phone" class="text-red-500" v-text="firstStepErrors.phone[0]"></div>
        </div> -->




        <div id="dropdown_main_id" class="mt-4">
          <GuestInputLabel for="phone" value="Phone" />
          <div class="flex">
            <button @click="toggleDropdown" class="drop_down_main mr-1" id="states-button"
              data-dropdown-toggle="dropdown-states" type="button">
              <span class="ml-1">{{ form.phone_country }}</span> <span class="mx-1">{{ form.phone_code }}</span> <span><svg
                  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor" class="w-4 ml-1 h-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg></span>
            </button>
            
            <GuestTextInput style="border-radius: 0px 5px 5px 0px;" @focus="closeDropDown" id="phone" type="text"
              placeholder="0000000000" class="mt-1  block w-full" v-model="form.phone" maxlength="15" minlength="10" />
              

          </div>
          <div v-if="isOpen" class="items-center justify-center ">
            <div class="relative">
              <input v-model="search"  type="text"
                class=" px-4 w-full mt-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600"
                placeholder="Search" />

              <ul style="width: 100%; height:250px;"
                class="absolute z-10 py-2 mt-1 overflow-auto bg-white rounded-md shadow-md">
                <li v-for="(country, index) in filteredCountries" :key="index" @click="selectCountry(country)"
                  class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                  {{ country.name }}
                </li>
              </ul>
            </div>
          </div>
          <div v-if="firstStepErrors.phone" class="text-red-500" v-text="firstStepErrors.phone[0]"></div>
          <div v-if="firstStepErrors.phone_code" class="text-red-500" v-text="firstStepErrors.phone_code[0]"></div>
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
          <PrimaryButton type="button" class="ml-4" @click.prevent="submit">
            <global-spinner :spinner="isLoading" />Submit
          </PrimaryButton>
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

.drop_down_main {
  background: #d7d7d7;
  height: 39.5px;
  margin-top: 5px;
  border-radius: 5px 0px 0px 5px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0px 5px;
}



.country-select-container {
  display: flex;
}

.country-code,
.country-dropdown {
  margin-right: 10px;
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
