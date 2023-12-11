<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed, onUnmounted, inject } from "vue";
import Multiselect from "@vueform/multiselect";
import InputError from "@/Components/InputError.vue";
import { router, usePage } from "@inertiajs/vue3";
import GuestTextInput from "@/Components/GuestTextInput.vue";
import GuestInputLabel from "@/Components/GuestInputLabel.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { toaster } from "@/helper.js";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import axios from "axios";

let page = usePage();
if (page.props.flash.message) {
  toaster("success", page.props.flash.message);
}
const countryList = inject('countryList');
let props = defineProps({
  agentModal: {
    type: Boolean,
  },
  currentPage: Number,
  callTypes: Array,
  states: Array,
  levels: Array,
  agents: Array,
});

let firstStepErrors = ref({});
let uiEmailValidation = ref({
  isValid: false,
});
let form = useForm({
  first_name: "",
  last_name: "",
  email: "",
  password: "",
  password_confirmation: "",
  phone: "",
  phone_code: "+1",
  phone_country: 'USA',
  balance: 0,
  level: "-- Select an option --",
  upline_id: "",
  typesWithStates: props.callTypes.reduce((acc, obj) => {
    acc[obj.id] = [];
    return acc;
  }, {}),
});
  const filteredAgents = computed(() => {
    return props.agents.filter((agent) => {
      return (
        agent.upline_id !== null &&
        (agent.first_name.toLowerCase().includes(form.upline_id.toLowerCase()) ||
          agent.last_name.toLowerCase().includes(form.upline_id.toLowerCase()) ||
          agent.upline_id.toLowerCase().includes(form.upline_id.toLowerCase()))
      );
    });
  });

// Initialize the validation variable
let isFormValid = ref(true);
let step = ref(0);
const isLoading = ref(false);
let selectedTypes = ref([]);

let stateOptions = computed(() => {
  return props.states.map((state) => {
    return {
      value: state.id,
      label: state.full_name,
    };
  });
});
const filteredStateOptions = computed(() => {
  // Filter out the option with label 'New York' and mark it as disabled
  return stateOptions.value.map((option) => ({
    ...option,
    disabled: option.label === 'New York',
  }));
});
watch(
  () => [
    form.first_name,
    form.last_name,
    form.email,
    form.password,
    form.password_confirmation,
    form.phone,
    form.upline_id,
    form.level,
  ],
  async ([firstName, lastName, email, password, passwordConfirmation, phone, upline_id, level]) => {
    if (
      firstName === "" ||
      lastName === "" ||
      email === "" ||
      password === "" ||
      passwordConfirmation === "" ||
      password !== passwordConfirmation || // Check for password mismatch
      phone === "" ||
      upline_id === "" ||
      level === "" ||
      level === "-- Select an option --"
    ) {
      isFormValid.value = true;
    } else {
      isFormValid.value = false;
    }
  }
);

let NextTab = (val) => {
  if (validateEmail(form.email)) {
    step.value = val;
    uiEmailValidation.value.isValid = false;
  } else {
    uiEmailValidation.value.isValid = true;
  }
};
let onTypeUpdate = (event) => {
  if (event.target.checked) {
    selectedTypes.value.push(Number(event.target.value));
  } else {
    selectedTypes.value.splice(
      selectedTypes.value.indexOf(Number(event.target.value)),
      1
    );
    delete form.typesWithStates[Number(event.target.value)];
  }
};
let validateEmail = (email) => {
  return /\S+@\S+\.\S+/.test(email); // Simple regex for email validation
};
const areAllArraysEmpty = computed(() => {
  const arrays = Object.values(form.typesWithStates); // Get all arrays
  return arrays.every((array) => array.length === 0); // Check if all arrays are empty
});

let saveChanges = () => {
  isLoading.value = true;
  if (validateEmail(form.email)) {
    return axios
      .post(`/admin/agent`, form)
      .then((response) => {
        toaster("success", response.data.message);
        router.visit(`/admin/agents?page=${props.currentPage}`);
        isLoading.value = false;
      })
      .catch((error) => {
        if (error.response.status === 400) {
          uiEmailValidation.value.isValid = false;
          firstStepErrors.value = error.response.data.errors;
          if (
            firstStepErrors.value.first_name ||
            firstStepErrors.value.last_name ||
            firstStepErrors.value.email ||
            firstStepErrors.value.password ||
            firstStepErrors.value.password_confirmation ||
            firstStepErrors.value.phone ||
            firstStepErrors.value.balance
          ) {
            step.value = 0;
          } else if (firstStepErrors.value.typesWithStates) {
            step.value = 1;
          }

          isLoading.value = false;
        }
      });
  } else {
    uiEmailValidation.value.isValid = true;
    isLoading.value = false;
    step.value = 0;
  }
};
let emit = defineEmits(["close"]);
let close = () => {
  form.reset();
  step.value = 0;
  emit("close");
  firstStepErrors.value = {};
  isLoading.value = false;
};
let goBack = () => {
  step.value -= 1;
};

const search = ref('');
const isOpen = ref(false);
const isOpen2 = ref(false);

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
  const dropdownElement2 = document.getElementById('dropdown_main_id_2');
  if (!dropdownElement.contains(event.target)) {
    // Call your desired function here
    closeDropDown();
  }
  if (!dropdownElement2.contains(event.target)) {
    isOpen2.value = false
  }
};
const closeDropDown = () => {
  isOpen.value = false
};
 
const SugestAgent = () => {
    isOpen2.value = true;
};
let selectagent = (agent) => {
    form.upline_id = agent.upline_id
    isOpen2.value = false;

}
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
</style>
<template>
  <div>
    <!-- <Transition
        name="modal"
        enter-active-class="transition ease-out duration-300 transform"
        enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        enter-to-class="opacity-100 translate-y-0 sm:scale-100"
        leave-active-class="transition ease-in duration-200 transform"
        leave-from-class="opacity-100 translate-y-0 sm:scale-100"
        leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
      >
        <div
          id="defaultModal"
          tabindex="-1"
          class="flex items-center justify-center fixed inset-0 z-50 w-full h-full overflow-x-hidden overflow-y-auto max-h-full mx-4 sm:mx-0"
        >
          <div class="fixed inset-0 bg-black opacity-60"></div> -->
    <!-- This is the overlay -->

    <div class="relative w-full max-w-4xl max-h-full mx-auto">
      <div class="relative bg-white border border-gray-300 rounded-lg shadow-lg">
        <div class="flex items-start justify-between p-4 border-b border-gray-300 rounded-t">
          <h3 class="text-xl font-small text-gray-700">Add Agent</h3>
          <button @click="close" type="button"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center"
            data-modal-hide="defaultModal">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
        </div>

        <div v-if="step == 0" class="p-6">
          <div>
            <div>
              <GuestInputLabel for="first_name" value="First Name" />

              <GuestTextInput id="first_name" type="text" class="mt-1 block w-full" v-model="form.first_name"
                minlength="2" required pattern="[A-Za-z]{1,32}" onkeyup="this.value=this.value.replace(/[0-9]/g,'');" />
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
              <div v-if="uiEmailValidation.isValid" class="text-red-500">
                Please enter valid email address.
              </div>
              <!-- <InputError class="mt-2" :message="form.errors.email" /> -->
              <div v-if="firstStepErrors.email" class="text-red-500" v-text="firstStepErrors.email[0]"></div>
            </div>

            <div class="mt-4">
              <GuestInputLabel for="balance" value="balance" />

              <GuestTextInput id="balance" type="number" step="any" class="mt-1 block w-full" v-model="form.balance" />
              <div v-if="firstStepErrors.balance" class="text-red-500" v-text="firstStepErrors.balance[0]"></div>
            </div>

            <!-- <div class="mt-4">
              <GuestInputLabel for="phone" value="Phone" />

              <GuestTextInput id="phone" type="text" class="mt-1 block w-full" v-model="form.phone" />
              <div v-if="firstStepErrors.phone" class="text-red-500" v-text="firstStepErrors.phone[0]"></div>
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

                <GuestTextInput onkeyup="this.value=this.value.replace(/[^0-9]/g,'')"
                  style="border-radius: 0px 5px 5px 0px;" @focus="closeDropDown" id="phone" type="text"
                  placeholder="0000000000" class="mt-1  block w-full" v-model="form.phone" maxlength="10"
                  minlength="10" />


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
              <div v-if="firstStepErrors.phone" class="text-red-500" v-text="firstStepErrors.phone[0]"></div>
              <div v-if="firstStepErrors.phone_code" class="text-red-500" v-text="firstStepErrors.phone_code[0]"></div>
              <div v-if="firstStepErrors.phone_country" class="text-red-500" v-text="firstStepErrors.phone_country[0]">
              </div>
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

            <div id="dropdown_main_id_2" class="mt-4">
              <GuestInputLabel for="Upline ID" value="Upline ID" />
              <GuestTextInput id="upline_id" autocomplete="off"  type="text"  @focus="SugestAgent" class="mt-1 block w-full" v-model="form.upline_id" />
              <InputError class="mt-2" :message="form.errors.upline_id" />
              <div v-if="isOpen2 &&form.upline_id.length > 0" class="items-center justify-center ">
                <div class="relative">
                  <ul style="width: 100%; max-height:250px;"
                    class="absolute z-10 pb-2 mt-1  overflow-auto bg-white rounded-md shadow-md">
                    <li v-for="(agent, index) in filteredAgents" :key="index" @click="selectagent(agent)"
                      class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                      {{ agent.first_name }} {{ agent.last_name }} - ( {{ agent.upline_id }} )

                    </li>
                  </ul>
                </div>
              </div>
            </div>

            <div class="mt-4">
              <GuestInputLabel for="Agent Level" value="Agent Level" />
              <select style="background: #d7d7d7;" v-model="form.level" id="countries"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                <option disabled selected>-- Select an option -- </option>
                <option v-for="level in levels" :value="level.id">{{ level.name }} </option>
              </select>
              <div v-if="firstStepErrors.gender" class="text-red-500" v-text="firstStepErrors.gender[0]">
              </div>
            </div>

            <div class="flex justify-end mt-6">
              <PrimaryButton type="button" @click="NextTab(1)" :class="{ 'opacity-25': isFormValid || isLoading }"
                :disabled="isFormValid || isLoading">
                <global-spinner :spinner="isLoading" /> Next
              </PrimaryButton>

              <PrimaryButton @click.prevent="close" type="button" class="ml-3">
                Close
              </PrimaryButton>
            </div>
          </div>
        </div>

        <div v-if="step === 1" class="pt-6">
          <div class="px-12">
            <div class="mt-4">
              <GuestInputLabel class="mb-3" for="insurance_type" value="What types of calls do you want to receive?" />

              <div v-for="(callType, index) in callTypes" :key="callType.id" class="mb-4">
                <input :id="`insurance_type_${callType.id}`" type="checkbox"
                  class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                  :checked="selectedTypes.includes(callType.id)" @change="onTypeUpdate" :value="callType.id" />

                <label class="ml-2 text-md font-medium text-custom-indigo" :for="`insurance_type_${callType.id}`"
                  v-text="callType.type"></label>

                <div v-if="selectedTypes.includes(callType.id)" class="pt-2 mb-8">
                  <label class="ml-2 text-xs font-medium">States you're licensed in:</label>
                  <Multiselect :options="filteredStateOptions" v-model="form.typesWithStates[callType.id]"
                    track-by="value" label="label" mode="tags" :close-on-select="false" placeholder="Select a state">
                  </Multiselect>
                </div>
              </div>

              <InputError class="mt-2" :message="form.errors.insurance_type" />
              <div v-if="firstStepErrors">
                <div v-if="firstStepErrors.typesWithStates" class="text-red-500"
                  v-text="firstStepErrors.typesWithStates[0]"></div>
              </div>
            </div>
            <div class="flex justify-between">
              <div class="my-6">
                <a href="#" @click.prevent="goBack()" class="button-custom-back px-4 py-3 rounded-md">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-4 h-4 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                  </svg>
                  Back</a>
              </div>
              <div class="flex justify-end my-6">
                <PrimaryButton type="button" @click="saveChanges"
                  :class="{ 'opacity-25': areAllArraysEmpty || isLoading }" :disabled="areAllArraysEmpty || isLoading">
                  <global-spinner :spinner="isLoading" /> Submit
                </PrimaryButton>

                <PrimaryButton @click.prevent="close" type="button" class="ml-3">
                  Close
                </PrimaryButton>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- </div>
      </Transition> -->
  </div>
</template>
