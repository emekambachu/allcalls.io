<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import AuthenticatedButton from "@/Components/AuthenticatedButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";
import { ref, reactive, computed } from "vue";
import Multiselect from "@vueform/multiselect";

let props = defineProps({
  mustVerifyEmail: {
    type: Boolean,
  },
  status: {
    type: String,
  },
  callTypes: {
    type: Array,
  },
});

let user = usePage().props.auth.user;

let form = useForm({
  first_name: user.first_name,
  last_name: user.last_name,
  email: user.email,
  phone: user.phone,
  call_types: props.callTypes,
  selected_states: props.callTypes.map((type) => {
    return {
      typeId: type.id,
      selectedStateIds: type.statesWithSelection
        .filter((state) => {
          return state.selected;
        })
        .map((state) => {
          return state.id;
        }),
    };
  }),
});

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
  let labels = options.map((option) => option.label).join(", ");

  return labels;
};

let submitForm = () => {
  form.patch(route("profile.update"));
};

let optionsForStates = (callType) => {
  return callType.statesWithSelection.map((state) => {
    return {
      value: state.id,
      label: state.full_name,
    };
  });
};
</script>

<template>
  <section>
    <header>
      <h2 class="text-lg font-medium text-gray-700">
        Profile Information
      </h2>

      <p class="mt-1 text-sm text-gray-400">
        Update your account's profile information and email address.
      </p>
    </header>

    <form @submit.prevent="submitForm" class="mt-6 space-y-6">
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
        <p class="text-sm mt-2 text-gray-200">
          Your email address is unverified.
          <Link
            :href="route('verification.send')"
            method="post"
            as="button"
            class="underline text-sm text-gray-400 hover:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800"
          >
            Click here to re-send the verification email.
          </Link>
        </p>

        <div
          v-show="status === 'verification-link-sent'"
          class="mt-2 font-medium text-sm text-green-600"
        >
          A new verification link has been sent to your email address.
        </div>
      </div>

      <div v-for="callType in form.call_types" :key="callType.id">
        <input
          type="checkbox"
          :id="`call-type-${callType.id}`"
          v-model="callType.selected"
        />
        <label :for="`call-type-${callType.id}`" class="ml-2">{{
          callType.type
        }}</label>

        <div class="text-white">
          <label
            class="ml-2 text-xs font-medium text-gray-500"
          >
            States you're licensed in:
          </label>

          <Multiselect
            :options="optionsForStates(callType)"
            v-model="
              form.selected_states[form.call_types.indexOf(callType)]
                .selectedStateIds
            "
            track-by="value"
            label="label"
            mode="tags"
            :close-on-select="false"
          >
          </Multiselect>
        </div>
      </div>

      <div class="flex items-center gap-4">
        <AuthenticatedButton class="button-custom px-4 py-3 rounded-md " :disabled="form.processing">Save</AuthenticatedButton>

        <Transition
          enter-from-class="opacity-0"
          leave-to-class="opacity-0"
          class="transition ease-in-out"
        >
          <p
            v-if="form.recentlySuccessful"
            class="text-sm text-gray-400"
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
