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

const form = useForm({
  first_name: "",
  last_name: "",
  username: "",
  email: "",
  password: "",
  password_confirmation: "",
  insurance_type: "Life Insurance",
  license_state: "",
});

const submit = () => {
  form.post(route("register"), {
    onFinish: () => form.reset("password", "password_confirmation"),
  });
};

const insuranceTypes = ref([
  "Lead / call types",
  "Auto Insurance",
  "Final Expense",
  "U65 Health",
  "ACA",
  "Medicare/Medicaid",
  "Debt Relief",
]);

let selectedTypes = ref([]);

let selectedTypesWithStates = ref({
  "Lead / call types": [],
  "Auto Insurance": [],
  "Final Expense": [],
  "U65 Health": [],
  ACA: [],
  "Medicare/Medicaid": [],
  "Debt Relief": [],
});

const onTypeUpdate = (event) => {
  if (event.target.checked) {
    selectedTypes.value.push(event.target.value);
  } else {
    selectedTypes.value.splice(
      selectedTypes.value.indexOf(event.target.value),
      1
    );
  }

  console.log("SELECTED TYPES NOW", selectedTypes.value);
};

const customLabel = function(options, select$) {
    console.log('All options: ', options);

    let labels = options.map(option => option.label).join(', ');

    return labels;
};
</script>

<template>
  <GuestLayout>
    <Head title="Register" />

    <form @submit.prevent="submit">
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
        <InputLabel for="username" value="Username" />

        <TextInput
          id="username"
          type="text"
          class="mt-1 block w-full"
          v-model="form.username"
          required
        />

        <InputError class="mt-2" :message="form.errors.username" />
      </div>

      <div class="mt-4">
        <InputLabel for="email" value="Email" />

        <TextInput
          id="email"
          type="email"
          class="mt-1 block w-full"
          v-model="form.email"
          required
          autocomplete="username"
        />

        <InputError class="mt-2" :message="form.errors.email" />
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

        <InputError class="mt-2" :message="form.errors.password_confirmation" />
      </div>

      <div class="mt-4">
        <InputLabel class="mb-3" for="insurance_type" value="What types of calls do you want to receive?" />

        <div v-for="insuranceType in insuranceTypes" :key="insuranceType" class="mb-4">
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
            >States you're licensed in:</label>
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
              v-model="selectedTypesWithStates[insuranceType]"
              track-by="value"
              label="label"
              mode="tags"
            >
            </Multiselect>
          </div>
        </div>

        <!-- <Multiselect
                    v-model="form.insurance_type"
                    :options="[
                        'First type',
                        'Second type',
                        'Third type',
                    ]"
                /> -->

        <InputError class="mt-2" :message="form.errors.insurance_type" />
      </div>

      <div class="mt-4">
        <InputLabel for="license_state" value="License State" />

        <SelectInput
          required
          class="mt-1 block w-full"
          id="license_state"
          v-model="form.license_state"
        >
          <option value="">Please select one</option>
          <option value="AL">Alabama</option>
          <option value="AK">Alaska</option>
          <option value="AZ">Arizona</option>
          <option value="AR">Arkansas</option>
          <option value="CA">California</option>
          <option value="CO">Colorado</option>
          <option value="CT">Connecticut</option>
          <option value="DE">Delaware</option>
          <option value="FL">Florida</option>
          <option value="GA">Georgia</option>
          <option value="HI">Hawaii</option>
          <option value="ID">Idaho</option>
          <option value="IL">Illinois</option>
          <option value="IN">Indiana</option>
          <option value="IA">Iowa</option>
          <option value="KS">Kansas</option>
          <option value="KY">Kentucky</option>
          <option value="LA">Louisiana</option>
          <option value="ME">Maine</option>
          <option value="MD">Maryland</option>
          <option value="MA">Massachusetts</option>
          <option value="MI">Michigan</option>
          <option value="MN">Minnesota</option>
          <option value="MS">Mississippi</option>
          <option value="MO">Missouri</option>
          <option value="MT">Montana</option>
          <option value="NE">Nebraska</option>
          <option value="NV">Nevada</option>
          <option value="NH">New Hampshire</option>
          <option value="NJ">New Jersey</option>
          <option value="NM">New Mexico</option>
          <option value="NY">New York</option>
          <option value="NC">North Carolina</option>
          <option value="ND">North Dakota</option>
          <option value="OH">Ohio</option>
          <option value="OK">Oklahoma</option>
          <option value="OR">Oregon</option>
          <option value="PA">Pennsylvania</option>
          <option value="RI">Rhode Island</option>
          <option value="SC">South Carolina</option>
          <option value="SD">South Dakota</option>
          <option value="TN">Tennessee</option>
          <option value="TX">Texas</option>
          <option value="UT">Utah</option>
          <option value="VT">Vermont</option>
          <option value="VA">Virginia</option>
          <option value="WA">Washington</option>
          <option value="WV">West Virginia</option>
          <option value="WI">Wisconsin</option>
          <option value="WY">Wyoming</option>
        </SelectInput>

        <InputError class="mt-2" :message="form.errors.license_state" />
      </div>

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
