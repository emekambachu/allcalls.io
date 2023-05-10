<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import Multiselect from "@vueform/multiselect";
import { ref, reactive } from "vue";

let step = ref(0);

let form = useForm({
  first_name: "",
  last_name: "",
  email: "",
  password: "",
  password_confirmation: "",
  insurance_type: "Life Insurance",
  license_state: "",
  phone: "",
  states_info: {
    "Auto Insurance": [],
    "Final Expense": [],
    "U65 Health": [],
    ACA: [],
    "Medicare/Medicaid": [],
    "Debt Relief": [],
  },
  consent: false,
});

let submit = () => {
  form.post(route("register"), {
    onFinish: () => form.reset("password", "password_confirmation"),
  });
};

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
    selectedTypes.value.push(event.target.value);
  } else {
    selectedTypes.value.splice(
      selectedTypes.value.indexOf(event.target.value),
      1
    );
  }
};

let customLabel = function (options, select$) {
  let labels = options.map((option) => option.label).join(", ");

  return labels;
};
</script>

<template>
  <GuestLayout>
    <Head title="Register" />

    <form @submit.prevent="submit">
      <div v-show="step === 0">
        <div>
          <InputLabel for="first_name" value="First Name" />

          <TextInput
            id="first_name"
            type="text"
            class="mt-1 block w-full"
            v-model="form.first_name"
            required
            autofocus
          />

          <InputError class="mt-2" :message="form.errors.first_name" />
        </div>

        <div class="mt-4">
          <InputLabel for="last_name" value="Last Name" />

          <TextInput
            id="last_name"
            type="text"
            class="mt-1 block w-full"
            v-model="form.last_name"
            required
          />

          <InputError class="mt-2" :message="form.errors.last_name" />
        </div>

        <div class="mt-4">
          <InputLabel for="email" value="Email" />

          <TextInput
            id="email"
            type="email"
            class="mt-1 block w-full"
            v-model="form.email"
            required
          />

          <InputError class="mt-2" :message="form.errors.email" />
        </div>

        <div class="mt-4">
          <InputLabel for="phone" value="Phone" />

          <TextInput
            id="phone"
            type="text"
            class="mt-1 block w-full"
            v-model="form.phone"
            required
          />

          <InputError class="mt-2" :message="form.errors.phone" />
        </div>

        <div class="mt-4">
          <InputLabel for="password" value="Password" />

          <TextInput
            id="password"
            type="password"
            class="mt-1 block w-full"
            v-model="form.password"
            required
            autocomplete="new-password"
          />

          <InputError class="mt-2" :message="form.errors.password" />
        </div>

        <div class="mt-4">
          <InputLabel for="password_confirmation" value="Confirm Password" />

          <TextInput
            id="password_confirmation"
            type="password"
            class="mt-1 block w-full"
            v-model="form.password_confirmation"
            required
            autocomplete="new-password"
          />

          <InputError
            class="mt-2"
            :message="form.errors.password_confirmation"
          />
        </div>

        <div class="flex items-center justify-end mt-4">
          <PrimaryButton type="button" class="ml-4" @click.prevent="step = 1">Next</PrimaryButton>
        </div>
      </div>

      <div v-show="step === 1">
        <div class="mt-4">
          <InputLabel
            class="mb-3"
            for="insurance_type"
            value="What types of calls do you want to receive?"
          />

          <div
            v-for="insuranceType in insuranceTypes"
            :key="insuranceType"
            class="mb-4"
          >
            <input
              :id="`insurance_type_${insuranceTypes.indexOf(insuranceType)}`"
              type="checkbox"
              class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
              @change="onTypeUpdate"
              :value="insuranceType"
            />
            <label
              class="ml-2 text-md font-medium text-gray-900 dark:text-gray-300"
              :for="`insurance_type_${insuranceTypes.indexOf(insuranceType)}`"
              v-text="insuranceType"
            ></label>

            <div
              v-if="selectedTypes.includes(insuranceType)"
              class="py-2 dark:text-white"
            >
              <label
                class="ml-2 text-xs font-medium text-gray-900 dark:text-gray-300"
                >States you're licensed in:</label
              >
              <Multiselect
                :options="[
                  { value: 'AL', label: 'Alabama' },
                  { value: 'AK', label: 'Alaska' },
                  { value: 'AZ', label: 'Arizona' },
                  { value: 'AR', label: 'Arkansas' },
                  { value: 'CA', label: 'California' },
                  { value: 'CO', label: 'Colorado' },
                  { value: 'CT', label: 'Connecticut' },
                  { value: 'DE', label: 'Delaware' },
                  { value: 'FL', label: 'Florida' },
                  { value: 'GA', label: 'Georgia' },
                  { value: 'HI', label: 'Hawaii' },
                  { value: 'ID', label: 'Idaho' },
                  { value: 'IL', label: 'Illinois' },
                  { value: 'IN', label: 'Indiana' },
                  { value: 'IA', label: 'Iowa' },
                  { value: 'KS', label: 'Kansas' },
                  { value: 'KY', label: 'Kentucky' },
                  { value: 'LA', label: 'Louisiana' },
                  { value: 'ME', label: 'Maine' },
                  { value: 'MD', label: 'Maryland' },
                  { value: 'MA', label: 'Massachusetts' },
                  { value: 'MI', label: 'Michigan' },
                  { value: 'MN', label: 'Minnesota' },
                  { value: 'MS', label: 'Mississippi' },
                  { value: 'MO', label: 'Missouri' },
                  { value: 'MT', label: 'Montana' },
                  { value: 'NE', label: 'Nebraska' },
                  { value: 'NV', label: 'Nevada' },
                  { value: 'NH', label: 'New Hampshire' },
                  { value: 'NJ', label: 'New Jersey' },
                  { value: 'NM', label: 'New Mexico' },
                  { value: 'NY', label: 'New York' },
                  { value: 'NC', label: 'North Carolina' },
                  { value: 'ND', label: 'North Dakota' },
                  { value: 'OH', label: 'Ohio' },
                  { value: 'OK', label: 'Oklahoma' },
                  { value: 'OR', label: 'Oregon' },
                  { value: 'PA', label: 'Pennsylvania' },
                  { value: 'RI', label: 'Rhode Island' },
                  { value: 'SC', label: 'South Carolina' },
                  { value: 'SD', label: 'South Dakota' },
                  { value: 'TN', label: 'Tennessee' },
                  { value: 'TX', label: 'Texas' },
                  { value: 'UT', label: 'Utah' },
                  { value: 'VT', label: 'Vermont' },
                  { value: 'VA', label: 'Virginia' },
                  { value: 'WA', label: 'Washington' },
                  { value: 'WV', label: 'West Virginia' },
                  { value: 'WI', label: 'Wisconsin' },
                  { value: 'WY', label: 'Wyoming' },
                ]"
                v-model="form.states_info[insuranceType]"
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
            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-400"
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
            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
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
  </GuestLayout>
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
