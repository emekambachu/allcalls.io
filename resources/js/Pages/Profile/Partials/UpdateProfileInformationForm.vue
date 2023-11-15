<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import AuthenticatedButton from "@/Components/AuthenticatedButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";
import { ref, reactive, computed, onMounted, watch, onBeforeMount, onUnmounted, inject } from "vue";

import Multiselect from "@vueform/multiselect";
const countryList = inject('countryList');

import { toaster } from "@/helper.js";
let page = usePage();
if (page.props.flash.message) {
    toaster("success", page.props.flash.message);
}

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
  timezones: {
    type: Object,
  }
});

let user = usePage().props.auth.user;

let form = useForm({
  first_name: user.first_name,
  last_name: user.last_name,
  email: user.email,
  phone: user.phone,
  phone_code: user.phone_code ?? "+1",
  phone_country: user.phone_country ?? 'USA',
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
  timezone: user.timezone
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
  console.log("selectedTypes", selectedTypes);
};

let customLabel = function (options, select$) {
  let labels = options.map((option) => option.label).join(", ");

  return labels;
};

let submitForm = () => {
  console.log("form", form);
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

        <TextInput id="first_name" type="text" class="mt-1 block w-full" v-model="form.first_name" required autofocus />

        <InputError class="mt-2" :message="form.errors.first_name" />
      </div>

      <div>
        <InputLabel for="last_name" value="Last Name" />

        <TextInput id="last_name" type="text" class="mt-1 block w-full" v-model="form.last_name" required />

        <InputError class="mt-2" :message="form.errors.last_name" />
      </div>

      <div>
        <InputLabel for="email" value="Email" />

        <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required />

        <InputError class="mt-2" :message="form.errors.email" />
      </div>
      <!-- 
      <div>
        <InputLabel for="phone" value="Phone" />

        <TextInput id="phone" type="text" class="mt-1 block w-full" v-model="form.phone" required />

        <InputError class="mt-2" :message="form.errors.phone" />
      </div> -->

      <div id="dropdown_main_id" class="mt-4">
        <GuestInputLabel for="phone" value="Phone" />
        <div class="flex">
          <button @click="toggleDropdown" class="drop_down_main mr-1" id="states-button"
            data-dropdown-toggle="dropdown-states" type="button">
            <span class="ml-1">{{ form.phone_country }}</span> <span class="mx-1">{{ form.phone_code }}</span>
            <span><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-4 ml-1 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
              </svg></span>
          </button>

          <TextInput style="border-radius: 0px 5px 5px 0px;" onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" @focus="closeDropDown" id="phone" type="text"
            placeholder="0000000000" class="mt-1  block w-full" v-model="form.phone" maxlength="10" minlength="10" />


        </div>
        <div v-if="isOpen" class="items-center justify-center ">
          <div class="relative">
            <input v-model="search" type="text"
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

        <InputError class="mt-2" :message="form.errors.phone" />
        <InputError class="mt-2" :message="form.errors.phone_country" />
        <InputError class="mt-2" :message="form.errors.phone_code" />
      </div>

      <div>
        <InputLabel for="last_name" value="Timezone" />

        <select id='timezone' name="timezone" class="select-custom" required v-model="form.timezone">
          <option selected="" disabled="" value="">Select Timezone</option>
          <option v-for="timezone in timezones" :value="timezone.name">{{ timezone.name }}
          </option>

        </select>

        <InputError class="mt-2" :message="form.errors.last_name" />
      </div>

      <div v-if="mustVerifyEmail && user.email_verified_at === null">
        <p class="text-sm mt-2 text-gray-200">
          Your email address is unverified.
          <Link :href="route('verification.send')" method="post" as="button"
            class="underline text-sm text-gray-400 hover:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800">
          Click here to re-send the verification email.
          </Link>
        </p>

        <div v-show="status === 'verification-link-sent'" class="mt-2 font-medium text-sm text-green-600">
          A new verification link has been sent to your email address.
        </div>
      </div>

      <div v-for="callType in form.call_types" :key="callType.id">
        <input type="checkbox" :id="`call-type-${callType.id}`" v-model="callType.selected" />
        <label :for="`call-type-${callType.id}`" class="ml-2">{{
          callType.type
        }}</label>

        <div class="text-white">
          <label class="ml-2 text-xs font-medium text-gray-500">
            States you're licensed in:
          </label>

          <Multiselect :options="optionsForStates(callType)" v-model="form.selected_states[form.call_types.indexOf(callType)]
            .selectedStateIds
            " track-by="value" label="label" mode="tags" :close-on-select="false">
          </Multiselect>
        </div>
      </div>

      <div class="flex items-center gap-4">
        <AuthenticatedButton class="button-custom px-4 py-3 rounded-md " :disabled="form.processing">Save
        </AuthenticatedButton>

        <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
          <p v-if="form.recentlySuccessful" class="text-sm text-gray-400">
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

.drop_down_main {
  background: #e8f0fe;
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
