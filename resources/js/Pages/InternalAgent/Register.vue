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
import { ref, reactive, computed, watch ,watchEffect, onMounted, onUnmounted , inject} from "vue";

// import { useVeeValidate } from '@vee-validate/vue3';
import axios from "axios";
// If you are using PurgeCSS, make sure to whitelist the carousel CSS classes
import "vue3-carousel/dist/carousel.css";
import { Carousel, Slide, Pagination, Navigation } from "vue3-carousel";
import { router } from "@inertiajs/vue3"
import { toaster } from "@/helper.js";
const countryList = inject('countryList');
let step = ref(0);
let firstStepErrors = ref({});
let uiEmailValidation = ref({
  "isValid": false
});

let props = defineProps({
  callTypes: Array,
  states: Array,
  tokenData:Object,
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
  phone_code:'+1',
  phone_country:'USA',
  level_id:props.tokenData.level_id,
  upline_id:props.tokenData.upline_id,
  invited_by :props.tokenData.invited_by,
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
          } else if (error.response.status === 401) {
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
  // search.value = country.codeName;
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
</style>
<template>
  <!-- <GuestLayout> -->
  <NewGuestLayout>

    <Head title="Register" />
    <template v-slot:smallStepRegister>
      <div
        class="px-10 text-center  text-4xl font-black text-sky-900 tracking-tighter font-extrabold"
      >
      Start Earning High Commissions Today!
      </div>
      <div
        class="text-md text-custom-blue font-semibold text-center px-10 mb-6"
      >
      Receive Competitive Top-Level Commissions - Fully Vested.
      </div>
    </template>
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

            <GuestTextInput onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" style="border-radius: 0px 5px 5px 0px;" @focus="closeDropDown" id="phone" type="text"
              placeholder="0000000000" class="mt-1  block w-full" v-model="form.phone" maxlength="10"
              minlength="10" />


          </div>
          <div v-if="isOpen" class="items-center justify-center ">
            <div class="relative">
              <input v-model="search" @input="filterCountries" type="text"
                class=" px-4 w-full mt-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600"
                placeholder="Search" />

              <ul style="width: 100%; max-height:250px;"
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

          <div v-if="firstStepErrors.password" class="text-red-500" v-text="firstStepErrors.password[0]"></div>
        </div>

        <div class="mt-4">
          <GuestInputLabel for="password_confirmation" value="Confirm Password" />

          <GuestTextInput id="password_confirmation" type="password" class="mt-1 block w-full"
            v-model="form.password_confirmation" autocomplete="new-password" />

          <InputError v-if="firstStepErrors.password_confirmation" class="mt-2"
            :message="firstStepErrors.password_confirmation[0]" />
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
        Welcome to AllCalls and <br><span class="text-custom-green">Congratulations on Your New Career</span>
      </div>
    </template>

    <template v-slot:subtitles>
      <div class="text-custom-blue font-bold text-2xl lg:text-2xl xl:text-5xl text-3xl">
        Why AllCalls.io?
      </div>

      <div class="text-custom-blue text-sm md:text-lg lg:text-2xl mt-6 font-bold">
        With AllCalls leading-edge technology we make selling
        life insurance easier than ever before. Developed from
        scratch by industry veterans - we know exactly what
        you need to have a successful  career in life insurance and
        we've built our business around just that. Helping agents
        succeed is our job.
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
