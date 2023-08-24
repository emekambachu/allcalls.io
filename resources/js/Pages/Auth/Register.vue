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
import { ref, reactive, computed } from "vue";
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
  phone: "",
});


let text = ref([]);

let check = () => {

};

let errors = ref(null);

let validateEmail = (email) => {
    return /\S+@\S+\.\S+/.test(email); // Simple regex for email validation
}

let submit = () => {
    if(validateEmail(form.email)) {

        return axios
          .post("/register", form)
          .then((response) => {
            router.visit('/login')
          })
          .catch((error) => {
            if (error.response) {
              // The request was made and the server responded with a status code
              // that falls out of the range of 2xx
              console.log(error.response.data);
              errors.value = error.response.data.errors;
              if (error.response.status === 400) {
                  uiEmailValidation.value.isValid = false;
                  // Handle validation errors here.
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
};

// let goBack = () => {
//   step.value = 0;
// };
</script>

<template>
  <!-- <GuestLayout> -->
  <NewGuestLayout>
    <Head title="Register" />

    <template v-slot:loadingText>
      <div
        class="px-10 text-center text-4xl xl:text-5xl text-custom-white font-extrabold tracking-tighter"
      >
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

          <GuestTextInput
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
          <div
            v-if="firstStepErrors.first_name"
            class="text-red-500"
            v-text="firstStepErrors.first_name[0]"
          ></div>
        </div>

        <div class="mt-4">
          <GuestInputLabel for="last_name" value="Last Name" />

          <GuestTextInput
            id="last_name"
            type="text"
            class="mt-1 block w-full"
            v-model="form.last_name"
            required
            pattern="[A-Za-z]{1,32}"
            onkeyup="this.value=this.value.replace(/[0-9]/g,'');"
          />

          <!-- <InputError class="mt-2" :message="form.errors.last_name" /> -->
          <div
            v-if="firstStepErrors.last_name"
            class="text-red-500"
            v-text="firstStepErrors.last_name[0]"
          ></div>
        </div>

        <div class="mt-4">
          <GuestInputLabel for="email" value="Email" />

          <GuestTextInput
            id="email"
            type="email"
            class="mt-1 block w-full"
            v-model="form.email"
            required
            pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}"
          />
            <div
                v-if="uiEmailValidation.isValid"
                class="text-red-500">Please enter valid email address.</div>
          <!-- <InputError class="mt-2" :message="form.errors.email" /> -->
          <div
            v-if="firstStepErrors.email"
            class="text-red-500"
            v-text="firstStepErrors.email[0]"
          ></div>
        </div>

        <div class="mt-4">
          <GuestInputLabel for="phone" value="Phone" />

          <GuestTextInput
            id="phone"
            type="text"
            class="mt-1 block w-full"
            v-model="form.phone"
            minlength="10"
            maxlength="10"
            onkeyup="this.value=this.value.replace(/[^\d]/,&#39;&#39;)"
          />

          <!-- <InputError class="mt-2" :message="form.errors.phone" /> -->
          <div
            v-if="firstStepErrors.phone"
            class="text-red-500"
            v-text="firstStepErrors.phone[0]"
          ></div>
        </div>

        <div class="mt-4">
          <GuestInputLabel for="password" value="Password" />

          <GuestTextInput
            id="password"
            type="password"
            class="mt-1 block w-full"
            v-model="form.password"
            autocomplete="new-password"
          />

          <!-- <InputError class="mt-2" :message="form.errors.password" /> -->
          <div
            v-if="firstStepErrors.password"
            class="text-red-500"
            v-text="firstStepErrors.password[0]"
          ></div>
        </div>

        <div class="mt-4">
          <GuestInputLabel
            for="password_confirmation"
            value="Confirm Password"
          />

          <GuestTextInput
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
            >Submit</PrimaryButton
          >
        </div>
      </div>
    </form>

    <template v-slot:titles>
      <div class="text-4xl lg:text-5xl xl:text-8xl text-white mb-10">
        Getting Started is Easy.<span class="text-custom-green"
          >Create your account today!</span
        >
      </div>
    </template>

    <template v-slot:subtitles>
      <div
        class="text-custom-blue font-bold text-2xl lg:text-2xl xl:text-5xl text-3xl"
      >
        Try our Calls for Yourself!
      </div>

      <div
        class="text-custom-blue text-sm md:text-lg lg:text-2xl mt-6 font-bold"
      >
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
