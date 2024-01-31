<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed, onBeforeMount, onUnmounted, inject } from "vue";
import TextInput from "@/Components/TextInput.vue";
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
  showModal: {
    type: Boolean,
  },

  userDetail: {
    type: Object,
  },
  currentPage: Number,
  callTypes: Array,
  roles: {
    type: Object,
  },
  states: Array,
  route: String,
  user_type: String,
  levels: Array,
  agents: Array,
  roles:Array,
});
let emit = defineEmits(["close"]);
let originalClient = props.userDetail;
let close = () => {
  emit("close");
};
let firstStepErrors = ref({});
let uiEmailValidation = ref({
  isValid: false,
});
let balanceChange = ref(false);
let form = useForm({
  first_name: props.userDetail.first_name,
  last_name: props.userDetail.last_name,
  email: props.userDetail.email,
  phone: props.userDetail.phone,
  balance: props.userDetail.balance,
  phone_code: props.userDetail.phone_code ?? "+1",
  level: props.userDetail.get_agent_level?.id ?? '-- Select an option --',
  phone_country: props.userDetail.phone_country ?? 'USA',
  upline_id: props.userDetail.upline_id ?? '',
  comment: "",
  call_types: props.callTypes,
  roles: props.userDetail.role_id,
  selected_states: props.callTypes.map((type) => {
    return {
      typeId: type.id,
      selectedStateIds: (props.userDetail?.states || [])
        .filter((state) => {
          return state.pivot.call_type_id == type.id ? true : false;
        })
        .map((state) => {
          return state.id;
        }),
    };
  }),
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
const selectedTypeIds = ref([]);

selectedTypeIds.value = props.userDetail.call_types.map((callType) => callType.id);

const onTypeUpdate = (typeId, event) => {
  console.log(event.target.value);
  // delete form.selected_states[Number(event.target.value)];
  if (event.target.checked) {
    selectedTypeIds.value.push(Number(event.target.value));
  } else {
    selectedTypeIds.value.splice(
      selectedTypeIds.value.indexOf(Number(event.target.value)), 1
    );
    const formItemIndex = form.selected_states.findIndex(item => item.typeId === Number(typeId));
    if (formItemIndex !== -1) {
      form.selected_states.splice(formItemIndex, 1, { typeId: typeId, selectedStateIds: [] });
    }

  }
};


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
// Check if balance change
let changeBalance = (newBalance) => {
  return newBalance != props.userDetail.balance;
};
let validateEmail = (email) => {
  return /\S+@\S+\.\S+/.test(email); // Simple regex for email validation
};

const isLoading = ref(false);
let saveChanges = () => {
  isLoading.value = true;
  if (typeof form.roles !== 'object') {
    form.roles = form.roles;
  } else {
    form.roles = "";
  }
  if (form.level == '-- Select an option --') {
    form.level = ''
  }
  if (validateEmail(form.email)) {
    if (changeBalance(form.balance) && !balanceChange.value) {
      balanceChange.value = true;
      isLoading.value = false;
    } else {
      console.log(changeBalance(form.balance), !form.comment);
      if (changeBalance(form.balance) && !form.comment) {
        firstStepErrors.value = { comment: "Comment Required" };
        isLoading.value = false;
      } else {
        return axios
          .post(`${props.route}/${props.userDetail.id}`, form)
          .then((response) => {
            toaster("success", response.data.message);
            if (props.currentPage != 1) {
              router.visit(`${props.route}s?page=${props.currentPage}`);
            } else {
              router.visit(`${props.route}s`);
            }
            isLoading.value = false;
            balanceChange.value = false;
          })
          .catch((error) => {
            if (error.response.status === 400) {
              if (error.response.data.errors.selected_states) {
                tab.value = 1
              }
              tab.value = 0
              uiEmailValidation.value.isValid = false;
              firstStepErrors.value = error.response.data.errors;
              isLoading.value = false;
              balanceChange.value = false;
            } else {
              //   console.log(error);
              toaster("error", "Something went wrong");
            }
          });
      }
    }
  } else {
    uiEmailValidation.value.isValid = true;
    isLoading.value = false;
  }
};
const tab = ref(0);

let ChangeTab = (val) => {
  tab.value = val
}

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
<style>
.chemical-formula {
  display: inline-block;
  padding: 5px 10px;
  background-color: #0b4c73;
  color: white;
  font-size: 16px;
  border-radius: 50px;
  border: none;
  cursor: pointer;
  text-align: center;
  text-decoration: none;
  outline: none;
}

.active-tab {
  border-bottom: 2px solid #0b4c73;

}

.slide-enter-active,
.slide-leave-active {
  /* transition: all 0.2s ease; */
}

.slide-enter,
.slide-leave-to {
  opacity: 0;
  transform: translateX(-20px);
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
  <!-- This is the overlay -->

  <div class="relative w-full max-w-4xl max-h-full mx-auto">
    <div class="relative bg-white border border-gray-300 rounded-lg shadow-lg">
      <div class="flex items-start justify-between p-4 border-b border-gray-300 rounded-t">
        <h3 class="text-xl font-small text-gray-700">Edit Agent Details</h3>
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
      <div class="mb-4  border-gray-200 dark:border-gray-700 px-6">

        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myTab" data-tabs-toggle="#myTabContent"
          role="tablist">
          <li @click="ChangeTab(0)" class="mr-6 " :class="{ 'active-tab': tab == 0 }" role="presentation">
            <button
              class="inline-block py-4  border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
              id="profile-tab" type="button" role="tab" aria-controls="profile" aria-selected="false"> User Information
              <!-- <span class="chemical-formula">{{ transactionsCount }}</span>  -->
            </button>
          </li>
          <li @click="ChangeTab(1)" class="mr-2  " :class="{ 'active-tab': tab == 1 }" role="presentation">
            <button
              class="inline-block py-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
              id="profile-tab" type="button" role="tab" aria-controls="profile" aria-selected="false">Edit State
              <!-- <span class="chemical-formula">{{ transactionsCount }}</span>  -->
            </button>
          </li>
          <li @click="ChangeTab(2)" class="mr-2  " :class="{ 'active-tab': tab == 2 }" role="presentation">
            <button
              class="inline-block py-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
              id="profile-tab" type="button" role="tab" aria-controls="profile" aria-selected="false">Roles
              <!-- <span class="chemical-formula">{{ transactionsCount }}</span>  -->
            </button>
          </li>
        </ul>

      </div>
      <form @submit.prevent="saveChanges" class="p-6">
        <div v-if="tab == 0">
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
            <div v-if="uiEmailValidation.isValid" class="text-red-500">
              Please enter valid email address.
            </div>
            <!-- <InputError class="mt-2" :message="form.errors.email" /> -->
            <div v-if="firstStepErrors.email" class="text-red-500" v-text="firstStepErrors.email[0]"></div>
          </div>

          <!-- <div class="mt-4">
            <GuestInputLabel for="phone" value="Phone" />

            <GuestTextInput id="phone" type="text" class="mt-1 block w-full" v-model="form.phone" minlength="10"
              maxlength="10" onkeyup="this.value=this.value.replace(/[^\d]/,&#39;&#39;)" />
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

              <GuestTextInput style="border-radius: 0px 5px 5px 0px;"
                onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" @focus="closeDropDown" id="phone" type="text"
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
            <div v-if="firstStepErrors.phone" class="text-red-500" v-text="firstStepErrors.phone[0]"></div>
            <div v-if="firstStepErrors.phone_code" class="text-red-500" v-text="firstStepErrors.phone_code[0]"></div>
            <div v-if="firstStepErrors.phone_country" class="text-red-500" v-text="firstStepErrors.phone_country[0]">
            </div>
          </div>

          <div id="dropdown_main_id_2" class="mt-4">
            <GuestInputLabel for="Upline ID" value="Upline ID" />
            <GuestTextInput id="upline_id" autocomplete="off" @focus="SugestAgent"  type="text" class="mt-1 block w-full" v-model="form.upline_id" />
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

          <div class="mt-4">
            <GuestInputLabel for="balance" value="balance" />

            <GuestTextInput id="balance" type="text" class="mt-1 block w-full" v-model="form.balance" />
            <div v-if="firstStepErrors.balance" class="text-red-500" v-text="firstStepErrors.balance[0]"></div>
          </div>

          <div class="mt-4" v-if="balanceChange">
            <GuestInputLabel for="comment" value="comment" />
            <GuestTextInput id="comment" type="text" class="mt-1 block w-full" v-model="form.comment" required />
            <div v-if="firstStepErrors.comment" class="text-red-500" v-text="firstStepErrors.comment"></div>
          </div>
        </div>
        <div v-if="tab == 1">
          <div>
            <div v-for="callType in form.call_types" :key="callType.id" class="mb-4">
              <input :id="`insurance_type_${callType.id}`" type="checkbox"
                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                :checked="selectedTypeIds.includes(callType.id)" @change="onTypeUpdate(callType.id, $event)"
                :value="callType.id" />

              <label class="ml-2 text-md font-medium text-custom-indigo" :for="`insurance_type_${callType.id}`"
                v-text="callType.type"></label>

              <div v-if="selectedTypeIds.includes(callType.id) && user_type === 'Customer'" class="pt-2 mb-8">
                <label class="ml-2 text-xs font-medium">States you're licensed in:</label>
                <Multiselect :options="stateOptions" v-model="form.selected_states[form.call_types.indexOf(callType)]
                  .selectedStateIds
                  " track-by="value" label="label" mode="tags" :close-on-select="false" placeholder="Select a state">
                </Multiselect>
              </div>
              <div v-if="selectedTypeIds.includes(callType.id) && user_type === 'Internal Agent'" class="pt-2 mb-8">
                <label class="ml-2 text-xs font-medium">States you're licensed in:</label>
                <Multiselect :options="filteredStateOptions" v-model="form.selected_states[form.call_types.indexOf(callType)]
                  .selectedStateIds
                  " track-by="value" label="label" mode="tags" :close-on-select="false" placeholder="Select a state">
                </Multiselect>
              </div>
            </div>
            <div v-if="firstStepErrors">
              <div v-if="firstStepErrors.selected_states" class="text-red-500"
                v-text="firstStepErrors.selected_states[0]"></div>
            </div>
          </div>
        </div>
        <div v-if="tab == 2">
          <div>
            <div class="mb-4">
              <label for="roles" class="block mb-2 text-sm font-medium text-gray-700">Select Role</label>
              <!-- <select v-model="role" id='role' name="role"  class="select-custom" required>
                  <option v-for="roles in form.roles" :key="roles" :value="roles" selected="">Select Role</option>
              </select> -->
              <select v-model="form.roles" id='roles' name="roles" class="select-custom" required>
                <option selected="" disabled="" value="">Select Role</option>
                <option v-for="role in roles" :value="role.id">{{ role.name }}</option>
              </select>
            </div>
            <div v-if="firstStepErrors">
              <div v-if="firstStepErrors.selected_states" class="text-red-500"
                v-text="firstStepErrors.selected_states[0]"></div>
            </div>
          </div>
        </div>
        <div class="flex justify-end mt-6">
          <PrimaryButton type="submit" :disabled="isLoading" @click.prevent="saveChanges">
            <global-spinner :spinner="isLoading" /> Save Changes
          </PrimaryButton>

          <PrimaryButton @click.prevent="close" type="button" class="ml-3">
            Close
          </PrimaryButton>
        </div>
      </form>
    </div>
  </div>
</template>
