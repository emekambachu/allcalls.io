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
  bids: props.callTypes.map((type) => {
    return { call_type_id: type.id, amount: 20 };
  }),
});

let text = ref([]);

let check = () => {};

// next() {
//   this.step = 1
//   // alert ('its working though')
// }

let submit = () => {
  if (step.value === 0) {
    // return form.post('/register-step-one', {
    //   onFinish: () => step.value = 1,
    // })

    return axios
      .post("/register-step-one", form)
      .then((response) => {
        // This block handles the success case.
        // console.log('Validation passed', response.data);
        step.value = 1;
      })
      .catch((error) => {
        // This block handles the error case.
        // Error details are in error.response.data.

        if (error.response) {
          // The request was made and the server responded with a status code
          // that falls out of the range of 2xx
          console.log(error.response.data);

          if (error.response.status === 400) {
            // Handle validation errors here.
            console.log("Validation errors", error.response.data.errors);
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
  form.post(route("register"), {
    // onFinish: () => form.reset("password", "password_confirmation"),
  });
};
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

    <template v-slot:smallStepOneLoading>
      <div v-show="step === 0" class="flex flex-col items-center w-full">
        <div
          class="w-[80%] max-w-xl bg-gray-200 rounded-full h-1.5 dark:bg-gray-700 mb-1"
        >
          <div
            class="bg-blue-600 h-1.5 rounded-full dark:bg-blue-500"
            style="width: 15%"
          ></div>
        </div>
        <div class="text-sm text-gray-500">
          Create Your Account: Step 1 of 3
        </div>
      </div>

      <div v-show="step === 1" class="flex flex-col items-center w-full">
        <div
          class="w-[80%] max-w-xl bg-gray-200 rounded-full h-1.5 dark:bg-gray-700 mb-1"
        >
          <div
            class="bg-blue-600 h-1.5 rounded-full dark:bg-blue-500"
            style="width: 50%"
          ></div>
        </div>
        <div class="text-sm text-gray-500">
          Create Your Account: Step 2 of 3
        </div>
      </div>

      <div v-show="step === 2" class="flex flex-col items-center w-full">
        <div
          class="w-[80%] max-w-xl bg-gray-200 rounded-full h-1.5 dark:bg-gray-700 mb-1"
        >
          <div
            class="bg-blue-600 h-1.5 rounded-full dark:bg-blue-500"
            style="width: 95%"
          ></div>
        </div>
        <div class="text-sm text-gray-500">
          Create Your Account: Step 3 of 3
        </div>
      </div>
    </template>

    <template v-slot:largeStepOneLoading>
      <div v-show="step === 0" class="flex flex-col items-center w-full">
        <div class="w-full bg-gray-300 rounded-full h-1.5 mb-1">
          <div
            class="bg-blue-600 h-1.5 rounded-full dark:bg-blue-500"
            style="width: 15%"
          ></div>
        </div>
        <div class="text-sm text-gray-300">
          Create Your Account: Step 1 of 3
        </div>
      </div>

      <div v-show="step === 1" class="flex flex-col items-center w-full">
        <div class="w-full bg-gray-300 rounded-full h-1.5 mb-1">
          <div
            class="bg-blue-600 h-1.5 rounded-full dark:bg-blue-500"
            style="width: 50%"
          ></div>
        </div>
        <div class="text-sm text-gray-300">
          Create Your Account: Step 2 of 3
        </div>
      </div>

      <div v-show="step === 2" class="flex flex-col items-center w-full">
        <div class="w-full bg-gray-300 rounded-full h-1.5 mb-1">
          <div
            class="bg-blue-600 h-1.5 rounded-full dark:bg-blue-500"
            style="width: 95%"
          ></div>
        </div>
        <div class="text-sm text-gray-300">
          Create Your Account: Step 3 of 3
        </div>
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
            >Next</PrimaryButton
          >
        </div>
      </div>

      <div v-if="step === 1">
        <h6 class="text-black text-2xl font-bold text-center mb-4">
          How It Works
        </h6>

        <Carousel :items-to-show="1">
          <Slide :key="1">
            <div class="px-12">
              <p class="text-gray-700 text-lg text-left leading-relaxed">
                Our dynamic bidding system allows you to set a maximum bid for
                each type of call you're interested in as you configure your
                account.
              </p>
            </div>
          </Slide>

          <Slide :key="2">
            <div class="px-12">
              <p class="text-gray-700 text-lg text-left leading-relaxed">
                Each call type has a base bid of $20, but you can bid higher to
                increase your chances of securing the call. The user with the
                highest bid wins the call but pays only $1 more than the second
                highest bid.
              </p>
            </div>
          </Slide>
          <Slide :key="3">
            <div class="px-12">
              <p class="text-gray-700 text-lg text-left leading-relaxed">
                To start, select your desired call types and indicate your
                maximum bid for each. Don't forget to select the states where
                you're licensed to operate. Remember, banks will see your bid
                amounts as they set the prices they allow for payment
                processing, so bid wisely!


              </p>

              <div class="text-center mt-4 mb-2">
                <PrimaryButton type="button" @click.prevent="step = 2">Configure Call Types</PrimaryButton>
              </div>

            </div>
          </Slide>

          <template #addons>
            <navigation />
            <pagination />
          </template>
        </Carousel>
      </div>

      <div v-if="step === 2">
        <div class="mt-4">
          <GuestInputLabel
            class="mb-3"
            for="insurance_type"
            value="What types of calls do you want to receive?"
          />

          <div
            v-for="(callType, index) in callTypes"
            :key="callType.id"
            class="mb-4"
          >
            <input
              :id="`insurance_type_${callType.id}`"
              type="checkbox"
              class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
              @change="onTypeUpdate"
              :value="callType.id"
            />

            <label
              class="ml-2 text-md font-medium text-custom-indigo"
              :for="`insurance_type_${callType.id}`"
              v-text="callType.type"
            ></label>

            <div v-if="selectedTypes.includes(callType.id)" class="pt-2 mb-8">
              <div>
                <label class="ml-2 text-xs font-medium">Your Bid</label>
                <div class="relative mb-2">
                  <div
                    class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-white"
                  >
                    $
                  </div>
                  <input
                    type="number"
                    class="bg-custom-blue border border-gray-300 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5"
                    placeholder="20.00"
                    v-model="form.bids[index].amount"
                  />
                </div>
              </div>
              <label class="ml-2 text-xs font-medium"
                >States you're licensed in:</label
              >
              <Multiselect
                :options="stateOptions"
                v-model="form.typesWithStates[callType.id]"
                track-by="value"
                label="label"
                mode="tags"
                :close-on-select="false"
                placeholder="Select a state"
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
  background-color: #03243d;
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
