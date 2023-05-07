<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";
import { ref, reactive } from "vue";
import Multiselect from "@vueform/multiselect";

defineProps({
  mustVerifyEmail: {
    type: Boolean,
  },
  status: {
    type: String,
  },
});

let user = usePage().props.auth.user;

let form = useForm({
  first_name: user.first_name,
  last_name: user.last_name,
  email: user.email,
  phone: user.phone,
  states_info: user.states_info,
});

let statesInfo = reactive(JSON.parse(user.states_info));
let selectedTypes = ref([]);

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
  console.log("All options: ", options);

  let labels = options.map((option) => option.label).join(", ");

  return labels;
};

let submitForm = () => {
    form.states_info = JSON.stringify(statesInfo);
    form.patch(route('profile.update'));
}
</script>

<template>
  <section>
    <header>
      <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        Profile Information
      </h2>

      <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        Update your account's profile information and email address.
      </p>
    </header>

    <form
      @submit.prevent="submitForm"
      class="mt-6 space-y-6"
    >
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

      <div>
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

      <div>
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

      <div>
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

      <div v-if="mustVerifyEmail && user.email_verified_at === null">
        <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
          Your email address is unverified.
          <Link
            :href="route('verification.send')"
            method="post"
            as="button"
            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
          >
            Click here to re-send the verification email.
          </Link>
        </p>

        <div
          v-show="status === 'verification-link-sent'"
          class="mt-2 font-medium text-sm text-green-600 dark:text-green-400"
        >
          A new verification link has been sent to your email address.
        </div>
      </div>

      <div>
        <div
          v-for="(value, key, index) in statesInfo"
          :key="index"
          class="dark:text-white"
        >
          <input
            :id="`insurance-${index}`"
            type="checkbox"
            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
            @change="onTypeUpdate"
            :value="key"
            :checked="statesInfo[key].length"
          />
          <label
            class="ml-2 text-md font-medium text-gray-900 dark:text-gray-300"
            :for="`insurance-${index}`"
            v-text="key"
          ></label>

          <div
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
              track-by="value"
              label="label"
              mode="tags"
              v-model="statesInfo[key]"
              :close-on-select="false"
            >
            </Multiselect>
          </div>
        </div>
      </div>

      <div class="flex items-center gap-4">
        <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

        <Transition
          enter-from-class="opacity-0"
          leave-to-class="opacity-0"
          class="transition ease-in-out"
        >
          <p
            v-if="form.recentlySuccessful"
            class="text-sm text-gray-600 dark:text-gray-400"
          >
            Saved.
          </p>
        </Transition>
      </div>
    </form>
  </section>
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
