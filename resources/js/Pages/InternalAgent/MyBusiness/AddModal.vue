<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed, onUnmounted } from "vue";
import GuestTextInput from "@/Components/GuestTextInput.vue";
import GuestInputLabel from "@/Components/GuestInputLabel.vue";
import PreviewInfo from "@/Pages/InternalAgent/MyBusiness/PreviewInfo.vue";
import { toaster } from "@/helper.js";
import { Head, router, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { companies, coverageLengthArray, questions, uplineManagerArray, currentQuestion } from "@/constants.js";
let emits = defineEmits();
let props = defineProps({
  addBusinessModal: Boolean,
  agents: Array,
  states: Array,
  businessData: Object,
  clients: Array,
});
let firstStepErrors = ref({});

let loading = ref(false);
let showConfirmationWindow = ref(false);
let index = ref(0);
let step = ref(1);


let page = usePage();
let form = ref({
  agent_id: page.props.auth.role === "internal-agent" ? page.props.auth.user.id : "",
  client_full_name: "",
  agent_email: "",
  insurance_company: "AETNA/CVS",
  product_name: "Select",
  application_date: "",
  coverage_amount: "",
  coverage_length: "Select",
  premium_frequency: "Select",
  premium_amount: "",
  premium_volumn: "",
  carrier_writing_number: "",
  this_app_from_lead: "Select",
  source_of_lead: "Select",
  policy_draft_date: "",
  first_name: "",
  mi: "",
  last_name: "",
  beneficiary_name: "",
  beneficiary_relationship: "",
  notes: "",
  dob: "",
  gender: "Select",
  client_street_address_1: "",
  client_street_address_2: "",
  client_city: "",
  client_state: "Select",
  client_state_name: "",
  client_zipcode: "",
  client_phone_no: "",
  client_email: "",
});
let edit_data = ref(false)
if (props.businessData) {
  form.value = props.businessData
  if(!props.businessData.source_of_lead){
    form.value.source_of_lead = 'Select'
  }
  edit_data.value = true
}
const isValidEmail = (email) => {
  // Regular expression for validating an Email address
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
};

let checkRequiredField = () => {
  for (const key in firstStepErrors.value) {
    if (firstStepErrors.value.hasOwnProperty(key)) {
      firstStepErrors.value[key] = [];
    }
  }
  let requiredFields = [
    "client_full_name",
    "agent_email",
    "agent_id",
    "insurance_company",
    "product_name",
    "application_date",
    "coverage_amount",
    "coverage_length",
    "premium_frequency",
    "premium_amount",
    "premium_volumn",
    "this_app_from_lead",
    "policy_draft_date",
    "first_name",
    "last_name",
    "beneficiary_name",
    "beneficiary_relationship",
    "dob",
    "gender",
    "client_street_address_1",
    "client_street_address_2",
    "client_city",
    "client_state",
    "client_zipcode",
    "client_phone_no",
    "client_email",
  ];
  const addFieldIfPresent = (field, condition, value) => {
    if (condition) {
      requiredFields = [...new Set([...requiredFields, value])];
    }
  };
  addFieldIfPresent(
    "source_of_lead",
    form.value.this_app_from_lead === "YES",
    "source_of_lead"
  );

  requiredFields.forEach((fieldName) => {
    if (!form.value[fieldName] || form.value[fieldName] === "Select") {
      firstStepErrors.value[fieldName] = [`This  field is required.`];
    }
  });

  let emails = ["agent_email", "client_email"];
  emails.forEach((fieldName) => {
    if (!isValidEmail(form.value[fieldName])) {
      firstStepErrors.value[fieldName] = ["Invalid email format"];
    }
  });
  let phones = ["client_phone_no"];
  phones.forEach((fieldName) => {
    if (form.value[fieldName].length < 10) {
      firstStepErrors.value[fieldName] = ["Please enter a valid phone number."];
    }
  });
  let numericFields = ["coverage_amount", "premium_amount", "premium_volumn"];

  numericFields.forEach((fieldName) => {
    const fieldValue = form.value[fieldName];

    // Check if the field is not a number
    if (isNaN(fieldValue)) {
      firstStepErrors.value[fieldName] = ["Please enter a valid number."];
    }
  });

  const hasErrors = Object.values(firstStepErrors.value).some(
    (errors) => errors.length > 0
  );

  if (hasErrors) {
    var element = document.getElementById("modal_main_id");
    element.scrollIntoView();
  } else {
    step.value = 2;
  }
};

let ChangeProducName = () => {
  form.value.product_name = "Select";
};
let getInsuranceCompanyOptions = () => {
  return Object.keys(companies);
};
let getProductNameOptions = () => {
  return Object.keys(companies[form.value.insurance_company]);
};

const Next = (data) => {
  checkRequiredField();
  var element = document.getElementById("modal_main_id");
  element.scrollIntoView();
};
let Previous = (data) => {
  step.value -= 1;
};
let dateFormat = (val) =>  {
  const date = new Date(val);
  const toMonth = date.getMonth() + 1;
  const toDate = date.getDate();
  const toYear = date.getFullYear();

  // Format the components as desired (e.g., as "MM-DD-YYYY")
  return `${toMonth}/${toDate}/${toYear}`;

}
// save business data start
let isLoading = ref(false);
let SaveBussinessData = async () => {
  form.value.application_date =  dateFormat(form.value.application_date)
  form.value.policy_draft_date = dateFormat(form.value.policy_draft_date)
  form.value.dob = dateFormat(form.value.dob)
  isLoading.value = true
  await axios.post(page.url, form.value)
    .then((response) => {
      toaster("success", response.data.message);
      router.visit(page.url);
    })
    .catch((error) => {
      console.log("error", error);
      isLoading.value = false;
      firstStepErrors.value = error.response.data.errors;
      console.log("firstStepErrors.value", firstStepErrors.value);
      console.log("error.response.data.errors", error.response.data.errors);
      if (firstStepErrors.value) {
        step.value = 1;
        var element = document.getElementById("modal_main_id");
        element.scrollIntoView();
      }
    })
}
// save business data end 

// update business data start
let isLoading2 = ref(false)
let UpdateBussinessData = async () => {
  form.value.application_date =  dateFormat(form.value.application_date)
  form.value.policy_draft_date = dateFormat(form.value.policy_draft_date)
  form.value.dob = dateFormat(form.value.dob)
  isLoading2.value = true
  await axios.post(`${page.url}/update`, form.value)
    .then((response) => {
      toaster("success", response.data.message);
      router.visit(page.url)
    }).catch((error) => {
      console.log('error', error);
      if (error.response.status == 400) {
        firstStepErrors.value = error.response.data.errors
        console.log('firstStepErrors.value', firstStepErrors.value);
        console.log('error.response.data.errors', error.response.data.errors);
      }
      if (error.response.status == 401) {
        toaster("error", error.response.data.message);
      }
      isLoading2.value = false

      if (firstStepErrors.value) {
        step.value = 1
        var element = document.getElementById("modal_main_id");
        element.scrollIntoView();
      }
    })
}
// update business data end

let close = () => {
  emits("close");
};
let StateChange = () => {
  let selectedState = props.states.find((state) => state.id === form.value.client_state);
  form.value.client_state_name = selectedState.full_name;
};
let maxDate = ref(new Date());
maxDate.value.setHours(23, 59, 59, 999);

let enforceFiveDigitInput = (fieldName, val) => {
  form.value[val] = fieldName.replace(/[^0-9]/g, "");
  if (fieldName.length > 5) {
    form.value[val] = fieldName.slice(0, 5);
  }
};
let numberOfmonth = ref(null);
let ChangeFrequency = (frequency) => {
  if (frequency == "Monthly") {
    numberOfmonth.value = 12;
  } else if (frequency == "Quarterly") {
    numberOfmonth.value = 4;
  } else if (frequency == "Semi-Annual") {
    numberOfmonth.value = 2;
  } else if (frequency == "Annual") {
    numberOfmonth.value = 1;
  }
  setAnnualPremium();
};
watch(
  () => form.value.premium_frequency,
  (newVal) => {
    if (newVal) {
      setAnnualPremium();
    }
  }
);
watch(
  () => form.value.premium_amount,
  (newVal) => {
    if (newVal) {
      setAnnualPremium();
    } else {
      form.value.premium_volumn = "";
    }
  }
);
let setAnnualPremium = () => {
  if (form.value.premium_amount && numberOfmonth.value) {
    form.value.premium_volumn = Number(form.value.premium_amount) * numberOfmonth.value;
  }
};
let CurrencyValidation = (val, fieldName) => {
  // form.value[fieldName] = val.replace(/[^0-9]/g, "");
  const cleanedValue = val.replace(/[^0-9.]/g, "");
  // Ensure there is only one decimal point
  const decimalCount = cleanedValue.split('.').length - 1;
  if (decimalCount > 1) {
    form.value[fieldName] = cleanedValue.slice(0, cleanedValue.lastIndexOf('.'));
  } else {
    form.value[fieldName] = cleanedValue;
  }
};
let changeAppLead = () => {
  if (form.value.this_app_from_lead == "NO") {
    form.value.source_of_lead = "Select";
  }
};
let changeSpliteScalte = () => {
  if (form.value.split_sale == "NO") {
    form.value.split_sale_type = "Select";
  }
}
let isOpen2 = ref(false)
let search = ref('')
const SugestAgent = () => {
  isOpen2.value = !isOpen2.value;
  search.value = ''
};
const filteredClients = computed(() => {
  return props.clients.filter((agent) => {
    return (
      agent.upline_id !== null &&
      (agent.first_name.toLowerCase().includes(search.value.toLowerCase()) ||
        agent.last_name.toLowerCase().includes(search.value.toLowerCase()))
    );
  });
});
onMounted(() => {
  document.addEventListener("click", handleOutsideClick);
  ChangeFrequency(form.value.premium_frequency)
});

onUnmounted(() => {
  document.removeEventListener("click", handleOutsideClick);
});
const handleOutsideClick = (event) => {
  const dropdownElement = document.getElementById("dropdown_main_id");
  if (!dropdownElement.contains(event.target)) {
    isOpen2.value = false;
  }

};
let selectclient = (client) => {
  form.value.client_full_name = client.first_name + ' ' + client.last_name
  form.value.client_email = client.email
  form.value.client_id = client.id
  isOpen2.value = false;

}
</script>
<style scoped>
.drop_down_main {
  width: 100%;
  background: white;
  height: 39.5px;
  margin-top: 5px;
  border-radius: 5px 5px;
  display: flex;
  align-items: center;
  justify-content: start;
  padding: 0px 5px;
}

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
  <Transition name="modal" enter-active-class="transition ease-out  duration-300 transform"
    enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    enter-to-class="opacity-100 translate-y-0 sm:scale-100" leave-active-class="transition ease-in duration-200 transform"
    leave-from-class="opacity-100 translate-y-0 sm:scale-100"
    leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
    <div id="defaultModal" v-show="addBusinessModal" tabindex="-1"
      class="flex items-center justify-center fixed inset-0 z-50 w-full h-full overflow-x-hidden overflow-y-auto max-h-full mx-4 sm:mx-0">
      <div class="fixed inset-0 bg-black opacity-90 blurred-overlay"></div>

      <!-- This is the overlay -->
      <div style="width: 80%; height: 90%" class="relative" id="modal_main_id">
        <div class="relative bg-white rounded-lg shadow-lg transition-all">
          <div class="flex justify-end">
            <h3 class="text-xl font-small ml-5 mt-5 text-gray-700"> <span v-if="edit_data">Edit</span> Report Application
            </h3>
            <button @click="close" type="button"
              class="text-gray-400 bg-transparent mr-2 mt-2 hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center"
              data-modal-hide="defaultModal">
              <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
              </svg>
              <span class="sr-only">Close modal</span>
            </button>
          </div>
          <div class="px-12 py-2">
            <br />
            <div class="mb-3">
              <form @submit.prevent="" class="question-card-list">
                <div class="question-card animate__animated" style="position: relative">
                  <div v-show="step == 1">
                    <div >
                      <h1 style="background-color: #134576" class="my-0 text-center rounded-md py-2 text-white">
                        Agent Information
                      </h1>
                      <div class="grid xl:grid-cols-3 lg:grid-cols-2 md:grid-cols-1 sm:grid-cols-1 gap-x-8">
                        <div id="dropdown_main_id">
                          <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black">Select
                            Client<span class="text-red-400">*</span></label>
                          <button @click="SugestAgent"
                            class="bg-gray-50 mt-1 mb-2 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 flex"
                            id="states-button" data-dropdown-toggle="dropdown-states" type="button">

                            <span><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 mt-1 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                              </svg></span><span v-if="!form.client_full_name" class="ml-2">Select Client</span>
                            <span class="ml-2">{{ form.client_full_name }}</span>
                          </button>

                          <div v-if="firstStepErrors.client_full_name" class="text-red-500"
                            v-text="firstStepErrors.client_full_name[0]">
                          </div>

                          <div v-if="isOpen2 > 0" class="items-center justify-center ">

                            <div class="relative">


                              <ul style="width: 100%; max-height:250px;"
                                class="absolute z-10 pb-2    overflow-auto bg-white rounded-md shadow-md">
                                <div class="mx-2 mt-1">
                                  <input v-model="search" autocomplete="off" type="text" id="client_full_name"
                                    class="bg-gray-50  mb-1  border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="" required />
                                </div>
                                <li v-for="(client, index) in filteredClients" :key="index" @click="selectclient(client)"
                                  class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                  {{ client.first_name }} {{ client.last_name }}

                                </li>
                              </ul>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <h1 style="background-color: #134576" class="mt-5 text-center rounded-md py-2 text-white">
                      Client Information
                    </h1>

                    <div class="grid xl:grid-cols-3 lg:grid-cols-2 md:grid-cols-1 sm:grid-cols-1 gap-x-8">
                      <div>
                        <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black">First Name<span
                            class="text-red-400">*</span></label>
                        <input v-model="form.first_name" type="text" id="first_name"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder="" required />
                        <div v-if="firstStepErrors.first_name" class="text-red-500"
                          v-text="firstStepErrors.first_name[0]"></div>
                      </div>

                      <div>
                        <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black">MI</label>
                        <input v-model="form.mi" type="text" id="mi"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder="" required />
                        <div v-if="firstStepErrors.mi" class="text-red-500" v-text="firstStepErrors.mi[0]"></div>
                      </div>

                      <div>
                        <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black">Last Name<span
                            class="text-red-400">*</span></label>
                        <input v-model="form.last_name" type="text" id="last_name"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder="" required />
                        <div v-if="firstStepErrors.last_name" class="text-red-500" v-text="firstStepErrors.last_name[0]">
                        </div>
                      </div>

                      <div>
                        <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black">Beneficiary
                          Name<span class="text-red-400">*</span></label>
                        <input v-model="form.beneficiary_name" type="text" id="beneficiary_name"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder="" required />
                        <div v-if="firstStepErrors.beneficiary_name" class="text-red-500"
                          v-text="firstStepErrors.beneficiary_name[0]"></div>
                      </div>

                      <div>
                        <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black">Beneficiary
                          Relationship<span class="text-red-400">*</span></label>
                        <input v-model="form.beneficiary_relationship" type="text" id="beneficiary_relationship"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder="" required />
                        <div v-if="firstStepErrors.beneficiary_relationship" class="text-red-500"
                          v-text="firstStepErrors.beneficiary_relationship[0]"></div>
                      </div>

                      <div>
                        <label for="EFNumber"
                          class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black">Street Address 1<span
                            class="text-red-400">*</span></label>
                        <input v-model="form.client_street_address_1" type="text" id="client_street_address_1"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder="" required />
                        <div v-if="firstStepErrors.client_street_address_1" class="text-red-500"
                          v-text="firstStepErrors.client_street_address_1[0]"></div>
                      </div>

                      <div>
                        <label for="EFNumber"
                          class="block mb-2 mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black">Street Address
                          2<span class="text-red-400">*</span></label>
                        <input v-model="form.client_street_address_2" type="text" id="client_street_address_2"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder="" required />
                        <div v-if="firstStepErrors.client_street_address_2" class="text-red-500"
                          v-text="firstStepErrors.client_street_address_2[0]"></div>
                      </div>

                      <div>
                        <label for="EFNumber"
                          class="block mb-2 mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black">City<span
                            class="text-red-400">*</span></label>
                        <input v-model="form.client_city" type="text" id="client_city"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder="" required />
                        <div v-if="firstStepErrors.client_city" class="text-red-500"
                          v-text="firstStepErrors.client_city[0]"></div>
                      </div>

                      <div>
                        <label for="EFNumber"
                          class="block mb-2 mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black">State<span
                            class="text-red-400">*</span></label>
                        <select v-model="form.client_state" @change="StateChange(this)" id="countries"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                          <option disabled>Select</option>
                          <option v-for="state in states" :value="state.id">
                            {{ state.full_name }}
                          </option>
                        </select>
                        <div v-if="firstStepErrors.client_state" class="text-red-500"
                          v-text="firstStepErrors.client_state[0]"></div>
                      </div>
                      <div>
                        <label for=""
                          class="block mb-2 mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black">Zip-code<span
                            class="text-red-400">*</span></label>
                        <input v-model="form.client_zipcode" @input="
                          enforceFiveDigitInput(form.client_zipcode, 'client_zipcode')
                          " type="text" id="client_zipcode"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder="" required />
                        <div v-if="firstStepErrors.client_zipcode" class="text-red-500"
                          v-text="firstStepErrors.client_zipcode[0]"></div>
                      </div>
                      <div>
                        <label for="EFNumber"
                          class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black">Client Phone
                          Number<span class="text-red-400">*</span></label>

                        <input v-model="form.client_phone_no" type="text" maxLength="15" @input="
                          CurrencyValidation(form.client_phone_no, 'client_phone_no')
                          "
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder="" required />
                        <div v-if="firstStepErrors.client_phone_no" class="text-red-500"
                          v-text="firstStepErrors.client_phone_no[0]"></div>
                      </div>

                      <div>
                        <label for="EFNumber"
                          class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black">Client Email<span
                            class="text-red-400">*</span></label>
                        <input v-model="form.client_email" type="text" id="client_email"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder="" required />
                        <div v-if="firstStepErrors.client_email" class="text-red-500"
                          v-text="firstStepErrors.client_email[0]"></div>
                      </div>

                      <div>
                        <label for="EFNumber"
                          class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black">Gender<span
                            class="text-red-400">*</span></label>

                        <select v-model="form.gender" id="countries"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                          <option disabled>Select</option>
                          <option value="Male">Male</option>
                          <option value="Female">Female</option>
                          <option value="Other">Other</option>
                        </select>
                        <div v-if="firstStepErrors.gender" class="text-red-500" v-text="firstStepErrors.gender[0]"></div>
                      </div>

                      <div>
                        <label for="EFNumber"
                          class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black">Date of Birth<span
                            class="text-red-400">*</span></label>

                        <VueDatePicker v-model="form.dob" format="dd-MMM-yyyy" :maxDate="maxDate" auto-apply>
                        </VueDatePicker>
                        <div v-if="firstStepErrors.dob" class="text-red-500" v-text="firstStepErrors.dob[0]"></div>
                      </div>
                    </div>

                    <label for="message"
                      class="block mt-5 mb-2 text-sm font-medium text-gray-900 dark:text-white">Notes</label>
                    <textarea v-model="form.notes" rows="5"
                      class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                      placeholder="Write your thoughts here..."></textarea>
                    <div v-if="firstStepErrors.notes" class="text-red-500" v-text="firstStepErrors.notes[0]"></div>

                    <h1 style="background-color: #134576" class="mt-5 text-center rounded-md py-2 text-white">
                      Policy Information
                    </h1>

                    <div class="grid xl:grid-cols-3 lg:grid-cols-2 md:grid-cols-1 sm:grid-cols-1 gap-x-8">
                      <div>
                        <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black">Insurance
                          Company<span class="text-red-400">*</span></label>
                        <select v-model="form.insurance_company" id="countries" @change="ChangeProducName()"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                          <option disabled>Select</option>
                          <option v-for="option in getInsuranceCompanyOptions()" v-text="option"></option>
                        </select>
                        <div v-if="firstStepErrors.insurance_company" class="text-red-500"
                          v-text="firstStepErrors.insurance_company[0]"></div>
                      </div>

                      <div>
                        <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black">Product Name<span
                            class="text-red-400">*</span></label>
                        <select v-model="form.product_name" id="countries"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                          <option disabled>Select</option>
                          <option v-for="option in getProductNameOptions()" :value="option" v-text="option"></option>
                        </select>
                        <div v-if="firstStepErrors.product_name" class="text-red-500"
                          v-text="firstStepErrors.product_name[0]"></div>
                      </div>

                      <div>
                        <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black">Application
                          Date<span class="text-red-400">*</span></label>
                        <VueDatePicker v-model="form.application_date" format="dd-MMM-yyyy" :maxDate="maxDate" auto-apply>
                        </VueDatePicker>
                        <div v-if="firstStepErrors.application_date" class="text-red-500"
                          v-text="firstStepErrors.application_date[0]"></div>
                      </div>

                      <div>
                        <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black">Coverage
                          Amount<span class="text-red-400">*</span></label>
                        <input v-model="form.coverage_amount" @input="
                          CurrencyValidation(form.coverage_amount, 'coverage_amount')
                          " type="text" id="text"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder="$ 0.00" required />
                        <div v-if="firstStepErrors.coverage_amount" class="text-red-500"
                          v-text="firstStepErrors.coverage_amount[0]"></div>
                      </div>

                      <div>
                        <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black">Coverage
                          Length<span class="text-red-400">*</span></label>
                        <select v-model="form.coverage_length" id="countries"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                          <option disabled>Select</option>
                          <option v-for="option in coverageLengthArray" :value="option" v-text="option"></option>
                        </select>
                        <div v-if="firstStepErrors.coverage_length" class="text-red-500"
                          v-text="firstStepErrors.coverage_length[0]"></div>
                      </div>

                      <div>
                        <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black">Premium
                          Frequency<span class="text-red-400">*</span></label>
                        <select v-model="form.premium_frequency" @change="ChangeFrequency(form.premium_frequency)"
                          id="countries"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                          <option disabled>Select</option>
                          <option value="Monthly">Monthly</option>
                          <option value="Quarterly">Quarterly</option>
                          <option value="Semi-Annual">Semi-Annual</option>
                          <option value="Annual">Annual</option>
                        </select>
                        <div v-if="firstStepErrors.premium_frequency" class="text-red-500"
                          v-text="firstStepErrors.premium_frequency[0]"></div>
                      </div>

                      <div>
                        <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black">{{
                          form.premium_frequency != "Select"
                          ? form.premium_frequency
                          : ""
                        }}
                          Premium Amount<span class="text-red-400">*</span></label>
                        <input v-model="form.premium_amount" @input="
                          CurrencyValidation(form.premium_amount, 'premium_amount')
                          " type="text" id="text"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder="$ 0.00" required />
                        <div v-if="firstStepErrors.premium_amount" class="text-red-500"
                          v-text="firstStepErrors.premium_amount[0]"></div>
                      </div>

                      <div>
                        <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black">Annual Premium
                          Volume<span class="text-red-400">*</span></label>
                        <input v-model="form.premium_volumn" disabled @input="
                          CurrencyValidation(form.premium_volumn, 'premium_volumn')
                          " type="text" id="text"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder="$ 0.00" required />
                        <div v-if="firstStepErrors.premium_volumn" class="text-red-500"
                          v-text="firstStepErrors.premium_volumn[0]"></div>
                      </div>

                      <!-- <div>
                        <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black">Do you have an
                          Equis
                          writing number with this carrier?<span class="text-red-400">*</span></label>
                        <select v-model="form.equis_writing_number_carrier" id="countries"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                          <option disabled>Select</option>
                          <option value="YES">YES</option>
                          <option value="NO">NO</option>
                        </select>
                        <div v-if="firstStepErrors.equis_writing_number_carrier" class="text-red-500"
                          v-text="firstStepErrors.equis_writing_number_carrier[0]"></div>
                      </div> -->

                      <div>
                        <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black">Carrier Writing
                          Number</label>
                        <input v-model="form.carrier_writing_number" type="text" id="carrier_writing_number"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder="" required />
                        <div v-if="firstStepErrors.carrier_writing_number" class="text-red-500"
                          v-text="firstStepErrors.carrier_writing_number[0]"></div>
                      </div>

                      <div>
                        <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black">Was this app from
                          a lead?<span class="text-red-400">*</span></label>
                        <select v-model="form.this_app_from_lead" @change="changeAppLead()" id="countries"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                          <option disabled>Select</option>
                          <option value="YES">YES</option>
                          <option value="NO">NO</option>
                        </select>
                        <div v-if="firstStepErrors.this_app_from_lead" class="text-red-500"
                          v-text="firstStepErrors.this_app_from_lead[0]"></div>
                      </div>

                      <div>
                        <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black">Source of the
                          lead<span class="text-red-400">*</span></label>
                        <!-- <input v-model="form.source_of_lead" :class="{'bg-slate-300': form.this_app_from_lead == 'NO'}" :disabled="form.this_app_from_lead == 'NO'" type="text" id="source_of_lead"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder="" required /> -->
                        <select v-model="form.source_of_lead" :class="{ 'bg-slate-300': form.this_app_from_lead == 'NO' }"
                          :disabled="form.this_app_from_lead == 'NO'" id="countries"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                          <option disabled>Select</option>
                          <option value="Allcalls Client">Allcalls</option>
                          <option value="Outside Lead">Outside Lead</option>
                        </select>
                        <div v-if="firstStepErrors.source_of_lead" class="text-red-500"
                          v-text="firstStepErrors.source_of_lead[0]"></div>
                      </div>

                      <div>
                        <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black">What Is the
                          Policy Draft Date?<span class="text-red-400">*</span></label>
                        <VueDatePicker v-model="form.policy_draft_date" format="dd-MMM-yyyy" auto-apply>
                        </VueDatePicker>
                        <div v-if="firstStepErrors.policy_draft_date" class="text-red-500"
                          v-text="firstStepErrors.policy_draft_date[0]"></div>
                      </div>
                    </div>
                  </div>

                  <div v-show="step == 2">
                    <PreviewInfo :form="form" :heading="'Please confirm all your information is correct?'" />
                  </div>

                  <div class="flex mt-8 mb-10" :class="step > 1 ? 'justify-between' : 'justify-end '">
                    <a v-if="step > 1" @click.prevent="Previous(step)"
                      class="button-custom-back px-3 py-2 rounded-md flex items-center"
                      v-text="step < 2 ? 'Previous' : 'Change Entries'" href="#">
                    </a>
                    <a v-if="step < 2" @click.prevent="Next(step)"
                      class="button-custom px-3 py-2 rounded-md flex items-center" href="#">
                      Next
                    </a>
                    <button v-if="step === 2 && !edit_data" :class="{ 'opacity-25': isLoading === true }"
                      :disabled="isLoading == true" @click="SaveBussinessData()"
                      class="button-custom px-3 py-2 rounded-md flex items-center" href="#">
                      <global-spinner :spinner="isLoading" /> Confirm
                    </button>
                    <button v-if="step === 2 && edit_data" :class="{ 'opacity-25': isLoading2 === true }"
                      :disabled="isLoading2 == true" @click="UpdateBussinessData()"
                      class="button-custom px-3 py-2 rounded-md flex items-center" href="#">
                      <global-spinner :spinner="isLoading2" /> Update
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>
