<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed, onUnmounted } from "vue";
import GuestTextInput from "@/Components/GuestTextInput.vue";
import GuestInputLabel from "@/Components/GuestInputLabel.vue";
import PreviewInfo from "@/Pages/InternalAgent/MyBusiness/PreviewInfo.vue";
import { toaster } from "@/helper.js";
import { Head, router, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {
  companies,
  coverageLengthArray,
  questions,
  uplineManagerArray,
  currentQuestion,
} from "@/constants.js";
let emits = defineEmits();
let props = defineProps({
  addBusinessModal: Boolean,
  agents: Array,
  states: Array,
  businessData: Object,
  is_client: Boolean,
  clientData: Object,
  AttachClientData: Object,
  clients: Array,
  businessesFilter: Array,
});
let firstStepErrors = ref({});

let loading = ref(false);
let showConfirmationWindow = ref(false);
let index = ref(0);
let step = ref(1);

let page = usePage();
let form = ref({
  agent_id: page.props.auth.role === "internal-agent" ? page.props.auth.user.id : "",
  agent_full_name:
    page.props.auth.role === "internal-agent"
      ? page.props.auth.user.first_name + " " + page.props.auth.user.last_name
      : "",
  agent_email:
    page.props.auth.role === "internal-agent" ? page.props.auth.user.email : "",
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
  client_id: "",
  client_full_name: "",
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
  label: "",
  business_label: "",
  status: "Submitted",
  business_id: "",
  existing_business: false,
});

let edit_data = ref(false);
let disabledDob = ref(false);
let disabledPolicydraftdate = ref(false);
let applicationDate = ref(false);
let updateFormAndDisableElement = (
  property,
  value,
  elementId,
  formObject,
  disabledFlag
) => {
  if (
    value &&
    property !== "dob" &&
    property !== "policy_draft_date" &&
    property !== "application_date"
  ) {
    formObject[property] = value;
    var element = document.getElementById(elementId);
    if (element) {
      if (formObject[property] !== "Select") {
        element.disabled = true;
      }
    }
  } else if (value && property == "dob") {
    formObject[property] = value;
    disabledDob.value = true;
  } else if (value && property == "policy_draft_date") {
    formObject[property] = value;
    disabledPolicydraftdate.value = true;
  } else if (value && property == "application_date") {
    formObject[property] = value;
    applicationDate.value = true;
  }
};
if (props.businessData) {
  form.value.insurance_company = props.businessData.insurance_company
    ? props.businessData.insurance_company
    : "AETNA/CVS";
  form.value.product_name = props.businessData.product_name
    ? props.businessData.product_name
    : "Select";
  form.value.application_date = props.businessData.application_date;
  form.value.coverage_amount = props.businessData.coverage_amount;
  form.value.coverage_length = props.businessData.coverage_length
    ? props.businessData.coverage_length
    : "Select";
  form.value.premium_frequency = props.businessData.premium_frequency
    ? props.businessData.premium_frequency
    : "Select";
  form.value.premium_amount = props.businessData.premium_amount;
  form.value.premium_volumn = props.businessData.premium_volumn;
  form.value.carrier_writing_number = props.businessData.carrier_writing_number;
  form.value.this_app_from_lead = props.businessData.this_app_from_lead
    ? props.businessData.this_app_from_lead
    : "Select";
  form.value.source_of_lead = props.businessData.source_of_lead
    ? props.businessData.source_of_lead
    : "Select";
  form.value.policy_draft_date = props.businessData.policy_draft_date;
  form.value.first_name = props.businessData.first_name;
  form.value.mi = props.businessData.mi;
  form.value.last_name = props.businessData.last_name;
  form.value.beneficiary_name = props.businessData.beneficiary_name;
  form.value.beneficiary_relationship = props.businessData.beneficiary_relationship;
  form.value.notes = props.businessData.notes;
  form.value.dob = props.businessData.dob;
  form.value.gender = props.businessData.gender ? props.businessData.gender : "Select";
  form.value.client_street_address_1 = props.businessData.client_street_address_1;
  form.value.client_street_address_2 = props.businessData.client_street_address_2;
  form.value.client_city = props.businessData.client_city;
  form.value.client_state = props.businessData.client_state;
  form.value.client_zipcode = props.businessData.client_zipcode;
  form.value.client_phone_no = props.businessData.client_phone_no;
  form.value.client_email = props.businessData.client_email;
  form.value.status = props.businessData.status ? props.businessData.status : "Submitted";
  form.value.label = props.businessData.label;
  form.value.business_id = props.businessData.id;

  form.value.agent_full_name = props.businessData.agent_full_name;
  form.value.agent_email = props.businessData.agent_email;
  form.value.agent_id = props.businessData.agent_id;

  if (props.businessData?.client) {
    form.value.client_id = props.businessData?.client.id;
  }
  // form.value.client_id = props.businessData?.client.id

  // if (props.businessData.this_app_from_lead == 'NO') {
  //   form.value.source_of_lead = 'Select'
  // }
  edit_data.value = true;
}

let getInsuranceCompanyOptions = () => {
  return Object.keys(companies);
};
let getProductNameOptions = () => {
  // console.log('form.value.insurance_company',form.value.insurance_company);
  // console.log('form.value.product_name',form.value.product_name);
  return Object.keys(companies[form.value.insurance_company]);
};

let attactClientEdit = ref(false);
if (props.AttachClientData) {
  form.value.business_id = props.AttachClientData.id;
  form.value.insurance_company = props.AttachClientData.insurance_company;
  form.value.product_name = props.AttachClientData.product_name;
  form.value.application_date = props.AttachClientData.application_date;
  form.value.coverage_amount = props.AttachClientData.coverage_amount;
  form.value.coverage_length = props.AttachClientData.coverage_length;
  form.value.premium_frequency = props.AttachClientData.premium_frequency;
  form.value.premium_amount = props.AttachClientData.premium_amount;
  form.value.premium_volumn = props.AttachClientData.premium_volumn;
  form.value.carrier_writing_number = props.AttachClientData.carrier_writing_number;
  form.value.this_app_from_lead = props.AttachClientData.this_app_from_lead;
  form.value.source_of_lead = props.AttachClientData.source_of_lead;
  form.value.policy_draft_date = props.AttachClientData.policy_draft_date;
  form.value.label = props.AttachClientData.label;
  form.value.status = props.AttachClientData.status
    ? props.AttachClientData.status
    : "Select";

  if (!props.AttachClientData.source_of_lead) {
    form.value.source_of_lead = "Select";
  }
  attactClientEdit.value = true;
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
    "agent_full_name",
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
    "client_city",
    "client_state",
    "client_zipcode",
    "client_phone_no",
    "client_email",
    "status",
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
  // if (page.props.auth.role !== 'admin') {
  //   if (!form.value.client_full_name) {
  //     firstStepErrors.value.client_full_name = ["This  field is required."];
  //   }
  // }

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

const Next = (data) => {
  checkRequiredField();
  var element = document.getElementById("modal_main_id");
  element.scrollIntoView();
};
let Previous = (data) => {
  step.value -= 1;
};
let dateFormat = (val) => {
  const date = new Date(val);
  const toMonth = date.getMonth() + 1;
  const toDate = date.getDate();
  const toYear = date.getFullYear();

  // Format the components as desired (e.g., as "MM-DD-YYYY")
  // return `${toMonth}/${toDate}/${toYear}`;
  return `${toYear}-${toMonth}-${toDate}`;
};
// save business data start
let isLoading = ref(false);

let SaveBussinessData = async () => {
  let route = "";
  if (page.props.auth.role == "admin") {
    route = "/admin/my-business";
  } else if (page.props.auth.role == "internal-agent") {
    route = "/internal-agent/my-business";
  }
  console.log("SAVE BUSINESS DATA", {
    currentUrl: page.url,
    redirectUrl:
      new URL(window.location.href).origin + new URL(window.location.href).pathname,
  });

  form.value.application_date = dateFormat(form.value.application_date);
  form.value.policy_draft_date = dateFormat(form.value.policy_draft_date);
  form.value.dob = dateFormat(form.value.dob);
  isLoading.value = true;
  await axios
    .post(route, form.value)
    .then((response) => {
      toaster("success", response.data.message);

      console.log(
        "Redirect URL",
        new URL(window.location.href).origin + new URL(window.location.href).pathname
      );
      router.visit(
        new URL(window.location.href).origin + new URL(window.location.href).pathname
      );
    })
    .catch((error) => {
      isLoading.value = false;
      if (error.response.status !== 400) {
        firstStepErrors.value = error.response.data.errors;
      }
      toaster("error", error.response.data.error);
      if (firstStepErrors.value) {
        step.value = 1;
        var element = document.getElementById("modal_main_id");
        element.scrollIntoView();
      }
    });
};
// save business data end

// update business data start
let isLoading2 = ref(false);
let UpdateBussinessData = async () => {
  let route = "";
  if (page.props.auth.role == "admin") {
    route = "/admin/my-business";
  } else if (page.props.auth.role == "internal-agent") {
    route = "/internal-agent/my-business";
  }
  form.value.application_date = dateFormat(form.value.application_date);
  form.value.policy_draft_date = dateFormat(form.value.policy_draft_date);
  form.value.dob = dateFormat(form.value.dob);
  isLoading2.value = true;
  await axios
    .post(`${route}/update`, form.value)
    .then((response) => {
      toaster("success", response.data.message);
      // router.visit(page.url)
      console.log(
        "Redirect URL",
        new URL(window.location.href).origin + new URL(window.location.href).pathname
      );
      router.visit(
        new URL(window.location.href).origin + new URL(window.location.href).pathname
      );
    })
    .catch((error) => {
      if (error.response.status == 400) {
        firstStepErrors.value = error.response.data.errors;
      }
      if (error.response.status == 401) {
        toaster("error", error.response.data.message);
      }
      isLoading2.value = false;

      if (firstStepErrors.value) {
        step.value = 1;
        var element = document.getElementById("modal_main_id");
        element.scrollIntoView();
      }
    });
};
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
  const decimalCount = cleanedValue.split(".").length - 1;
  if (decimalCount > 1) {
    form.value[fieldName] = cleanedValue.slice(0, cleanedValue.lastIndexOf("."));
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
};

let isOpen = ref(false);
let isOpen2 = ref(false);
let isOpen3 = ref(false);
let search = ref("");
let search2 = ref("");
let search3 = ref("");
let get_client_by_name = ref("");
const SugestAgent = () => {
  isOpen.value = !isOpen.value;
  search.value = "";
};
const SugestClient = () => {
  isOpen2.value = !isOpen2.value;
  search2.value = "";
};
let SugestBusiness = () => {
  isOpen3.value = !isOpen3.value;
  search3.value = "";
};

const filteredAgents = computed(() => {
  return props.agents.filter((agent) => {
    return (
      agent.upline_id !== null &&
      (agent.first_name.toLowerCase().includes(search.value.toLowerCase()) ||
        agent.last_name.toLowerCase().includes(search.value.toLowerCase()))
    );
  });
});

let filteredBusiness = computed(() => {
  return props.businessesFilter.filter((business) => {
    const searchValue = search3.value.toLowerCase();
    return (
      (business.agent_full_name &&
        business.agent_full_name.toLowerCase().includes(searchValue)) ||
      (business.label && business.label.toLowerCase().includes(searchValue)) ||
      (business.product_name &&
        business.product_name.toLowerCase().includes(searchValue)) ||
      (business.insurance_company &&
        business.insurance_company.toLowerCase().includes(searchValue))
    );
  });
});

let filteredClients = computed(() => {
  return props.clients.filter((user) => {
    return user.phone.toLowerCase().includes(search2.value.toLowerCase());
    // ||
    //  user.first_name.toLowerCase().includes(search2.value) ||
    //  user.last_name.toLowerCase().includes(search2.value)
  });
  // const sanitizedSearch = search2.value.replace(/\D/g, ''); // Remove non-digit characters
  // return props.clients.filter(user => {
  //   const sanitizedPhone = user.phone.replace(/\D/g, ''); // Remove non-digit characters

  //   return sanitizedPhone.includes(sanitizedSearch);
  // })
});

let updateFormAndEnableElement = (
  property,
  value,
  elementId,
  formObject,
  disabledFlag
) => {
  if (
    value &&
    property !== "dob" &&
    property !== "policy_draft_date" &&
    property !== "application_date" &&
    property !== "this_app_from_lead"
  ) {
    formObject[property] = value;
    var element = document.getElementById(elementId);
    if (element) {
      element.disabled = false;
    }
  } else if (value && property == "policy_draft_date") {
    formObject[property] = value;
    disabledPolicydraftdate.value = false;
  } else if (value && property == "application_date") {
    formObject[property] = value;
    applicationDate.value = false;
  } else if (value && property == "this_app_from_lead") {
    if (value == "NO") {
      var element = document.getElementById("source_of_lead");
      if (element) {
        element.disabled = true;
      }
      var element2 = document.getElementById(elementId);
      if (element2) {
        element2.disabled = false;
      }
    }
  }
};

onMounted(() => {
  document.addEventListener("click", handleOutsideClick);
  ChangeFrequency(form.value.premium_frequency);
  if (props.businessData?.client) {
    if (page.props.auth.role === "internal-agent") {
      updateFormAndDisableElement(
        "client_street_address_1",
        props.businessData.client.address,
        "client_street_address_1",
        form.value,
        disabledDob
      );
      updateFormAndDisableElement(
        "dob",
        props.businessData.client.dob,
        "dob",
        form.value,
        disabledDob
      );
      updateFormAndDisableElement(
        "client_email",
        props.businessData.client.email,
        "client_email",
        form.value
      );
      updateFormAndDisableElement(
        "first_name",
        props.businessData.client.first_name,
        "first_name",
        form.value
      );
      updateFormAndDisableElement(
        "last_name",
        props.businessData.client.last_name,
        "last_name",
        form.value
      );
      updateFormAndDisableElement(
        "client_zipcode",
        props.businessData.client.zipCode,
        "client_zipcode",
        form.value
      );
      updateFormAndDisableElement(
        "client_phone_no",
        props.businessData.client.phone,
        "client_phone_no",
        form.value
      );
    }

    form.value.client_full_name =
      props.businessData.client.first_name + " " + props.businessData.client.last_name;
  }

  if (props.clientData) {
    selectClient(props.clientData);
  }
  if (props.AttachClientData) {
    var element = document.getElementById("label");
    if (element) {
      element.disabled = true;
    }

    let policy_validation = ref([
      "insurance_company",
      "product_name",
      "application_date",
      "coverage_amount",
      "coverage_length",
      "premium_frequency",
      "premium_amount",
      "premium_volumn",
      "carrier_writing_number",
      "this_app_from_lead",
      "source_of_lead",
      "policy_draft_date",
      "status",
    ]);
    policy_validation.value.forEach((key) => {
      if (form.value.hasOwnProperty(key)) {
        const value = form.value[key];
        updateFormAndDisableElement(key, value, key, form.value);
      }
    });
  }
});

onUnmounted(() => {
  document.removeEventListener("click", handleOutsideClick);
});
const handleOutsideClick = (event) => {
  if (page.props.auth.role === "admin") {
    const dropdownElement = document.getElementById("dropdown_main_id");
    if (!dropdownElement.contains(event.target)) {
      isOpen.value = false;
    }
  }
  if (form.value.existing_business) {
    const dropdownElement3 = document.getElementById("dropdown_main_id3");

    if (!dropdownElement3.contains(event.target)) {
      isOpen3.value = false;
    }
  }
  if (page.props.auth.role === "internal-agent") {
    const dropdownElement2 = document.getElementById("dropdown_main_id2");

    if (!dropdownElement2.contains(event.target)) {
      isOpen2.value = false;
    }
  }
};
let selectagent = (agent) => {
  form.value.agent_full_name = agent.first_name + " " + agent.last_name;
  form.value.agent_email = agent.email;
  form.value.agent_id = agent.id;
  console.log("form.value", form.value);
  isOpen.value = false;
};

let selectClient = (client) => {
  // updateFormAndDisableElement('client_street_address_1', client.address, 'client_street_address_1', form.value, disabledDob);
  // updateFormAndDisableElement('dob', client.dob, 'dob', form.value, disabledDob);
  // updateFormAndDisableElement('client_email', client.email, 'client_email', form.value);
  // updateFormAndDisableElement('first_name', client.first_name, 'first_name', form.value);
  // updateFormAndDisableElement('last_name', client.last_name, 'last_name', form.value);
  // updateFormAndDisableElement('client_zipcode', client.zipCode, 'client_zipcode', form.value);
  updateFormAndDisableElement(
    "client_phone_no",
    client.phone,
    "client_phone_no",
    form.value
  );

  form.value.client_full_name = client.first_name + " " + client.last_name;
  form.value.client_id = client.id;

  form.value.client_street_address_1 = client.address;
  form.value.dob = client.dob;
  form.value.client_email = client.email;
  form.value.first_name = client.first_name;
  form.value.last_name = client.last_name;
  form.value.client_zipcode = client.zipCode;

  // form.value.beneficiary_name = ''
  // form.value.beneficiary_relationship = ''
  // form.value.client_street_address_2 = ''
  // form.value.client_city = ''
  // form.value.client_state = ''
  // form.value.gender = ''
  isOpen2.value = false;
};
let clearClient = () => {
  isOpen2.value = false;
  disabledDob.value = false;
  form.value.client_full_name = "";
  form.value.client_id = "";
  let policy_validation = ref([
    "client_street_address_1",
    "dob",
    "client_email",
    "first_name",
    "last_name",
    "client_zipcode",
    "client_phone_no",
    "mi",
    "beneficiary_name",
    "beneficiary_relationship",
    "client_street_address_2",
    "client_city",
    "client_state",
    "gender",
    "notes",
  ]);
  policy_validation.value.forEach((key) => {
    if (form.value.hasOwnProperty(key)) {
      const value = form.value[key];
      updateFormAndEnableElement(key, value, key, form.value);
      resetValue(key);
    }
  });
};

let selectBusiness = (business) => {
  isOpen3.value = false;
  form.value.insurance_company = business.insurance_company;
  form.value.product_name = business.product_name;
  form.value.application_date = business.application_date;
  form.value.coverage_amount = business.coverage_amount;
  form.value.coverage_length = business.coverage_length;
  form.value.premium_frequency = business.premium_frequency;
  form.value.premium_amount = business.premium_amount;
  form.value.premium_volumn = business.premium_volumn;
  form.value.carrier_writing_number = business.carrier_writing_number;
  form.value.this_app_from_lead = business.this_app_from_lead;
  form.value.source_of_lead = business.source_of_lead;
  form.value.policy_draft_date = business.policy_draft_date;
  // form.value.business_label = business.label ? business.label : business.insurance_company + ' - ' + business.product_name
  // form.value.label = business.label ? business.label : ''
  if (business.this_app_from_lead == "NO") {
    form.value.source_of_lead = "Select";
  }
  form.value.business_id = business.id;
  let policy_validation = ref([
    "insurance_company",
    "product_name",
    "application_date",
    "coverage_amount",
    "coverage_length",
    "premium_frequency",
    "premium_amount",
    "premium_volumn",
    "carrier_writing_number",
    "this_app_from_lead",
    "source_of_lead",
    "policy_draft_date",
  ]);
  policy_validation.value.forEach((key) => {
    if (form.value.hasOwnProperty(key)) {
      const value = form.value[key];
      updateFormAndDisableElement(key, value, key, form.value);
    }
  });
};
let resetValue = (key) => {
  form.value[key] = "";
  if (key == "insurance_company") {
    form.value[key] = "AETNA/CVS";
  }
  if (key == "product_name") {
    form.value[key] = "Select";
  }
  if (key == "coverage_length") {
    form.value[key] = "Select";
  }
  if (key == "premium_frequency") {
    form.value[key] = "Select";
  }
  if (key == "this_app_from_lead") {
    form.value[key] = "Select";
  }
  if (key == "source_of_lead") {
    form.value[key] = "Select";
  }
  if (key == "client_state") {
    form.value[key] = "Select";
  }
  if (key == "gender") {
    form.value[key] = "Select";
  }
  form.value.business_label = "";
  form.value.id = "";
};
let existingBusiness = () => {
  if (!form.value.existing_business) {
    let policy_validation = ref([
      "insurance_company",
      "product_name",
      "application_date",
      "coverage_amount",
      "coverage_length",
      "premium_frequency",
      "premium_amount",
      "premium_volumn",
      "carrier_writing_number",
      "this_app_from_lead",
      "source_of_lead",
      "policy_draft_date",
      "label",
    ]);
    policy_validation.value.forEach((key) => {
      if (form.value.hasOwnProperty(key)) {
        const value = form.value[key];
        updateFormAndEnableElement(key, value, key, form.value);
        resetValue(key);
      }
    });
  }
};
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
  <Transition
    name="modal"
    enter-active-class="transition ease-out  duration-300 transform"
    enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    enter-to-class="opacity-100 translate-y-0 sm:scale-100"
    leave-active-class="transition ease-in duration-200 transform"
    leave-from-class="opacity-100 translate-y-0 sm:scale-100"
    leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
  >
    <div
      id="defaultModal"
      v-show="addBusinessModal"
      tabindex="-1"
      class="flex items-center justify-center fixed inset-0 z-50 w-full h-full overflow-x-hidden overflow-y-auto max-h-full mx-4 sm:mx-0"
    >
      <div class="fixed inset-0 bg-black opacity-90 blurred-overlay"></div>

      <!-- This is the overlay -->
      <div style="width: 80%; height: 90%" class="relative" id="modal_main_id">
        <div class="relative bg-white rounded-lg shadow-lg transition-all">
          <div class="flex justify-between">
            <h3 class="text-xl font-small mx-12 mt-5 text-gray-700">
              <span v-if="edit_data">Edit </span>
              <span v-if="attactClientEdit">Attach Client</span>
              <span v-if="!attactClientEdit">Report Application</span>
            </h3>
            <button
              @click="close"
              type="button"
              class="text-gray-400 bg-transparent mr-2 mt-2 hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center"
              data-modal-hide="defaultModal"
            >
              <svg
                class="w-3 h-3"
                aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 14 14"
              >
                <path
                  stroke="currentColor"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"
                />
              </svg>
              <span class="sr-only">Close modal</span>
            </button>
          </div>

          <div v-if="is_client " class="mx-12 mt-4">
            <div
              class="mb-4 flex flex-col md:flex-row rounded-lg bg-blue-100 p-4 text-blue-900"
              role="alert"
            >
              <svg
                class="mr-4 h-6 w-6 flex-shrink-0 text-blue-500"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                aria-hidden="true"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
              <div class="flex-1">
                <h3 class="text-lg font-semibold">Attention Required</h3>
                <p class="mt-1 text-sm">
                  Please review the pre-filled client information for accuracy and
                  complete the necessary policy details.
                </p>
                <ul class="list-disc pl-5 mt-2 text-sm">
                  <li>Verify the accuracy of the auto-filled client details.</li>
                  <li>
                    Fill in the remaining sections with the appropriate policy
                    information and click next.
                  </li>
                  <li>Double-check all data to ensure there are no errors.</li>
                </ul>
              </div>
            </div>
          </div>

          <div class="px-12 py-2">
            <br />
            <div class="mb-3">
              <form @submit.prevent="" class="question-card-list">
                <div class="question-card animate__animated" style="position: relative">
                  <div v-show="step == 1">
                    <!-- <div v-if="is_client"
                      class="grid xl:grid-cols-4 mb-3 lg:grid-cols-2 md:grid-cols-1 sm:grid-cols-1 gap-x-8">
                      <div class="flex items-center mb-4">
                        <input id="default-radio-1" v-model="form.existing_business" @change="existingBusiness()"
                          type="radio" :value="false" name="existing_business"
                          class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="default-radio-1" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">New
                          Business</label>
                      </div>
                      <div class="flex items-center mb-4">
                        <input id="default-radio-2" v-model="form.existing_business" @change="existingBusiness()"
                          type="radio" :value="true" name="existing_business"
                          class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="default-radio-2"
                          class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Existing Business</label>
                      </div>
                    </div> -->

                    <!-- <div class="grid xl:grid-cols-3 lg:grid-cols-2 md:grid-cols-1 sm:grid-cols-1 gap-x-8">
                      <div id="dropdown_main_id3">

                        <div v-if="form.existing_business">
                          <label class="block  text-sm mb-2 font-medium text-gray-900 dark:text-black">Select
                            Business<span class="text-red-400">*</span></label>
                          <button @click="SugestBusiness"
                            class="bg-gray-50 mt-1 mb-2 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 flex"
                            id="states-button" data-dropdown-toggle="dropdown-states" type="button">

                            <span><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 mt-1 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                              </svg></span><span v-if="!form.business_label" class="ml-2">Select Business</span>
                            <span class="ml-2"> {{ form.business_label }}</span>
                          </button>


                          <div v-if="firstStepErrors.business_label" class="text-red-500"
                            v-text="firstStepErrors.business_label[0]">
                          </div>

                          <div v-if="isOpen3 > 0" class="items-center justify-center ">

                            <div class="relative">


                              <ul style="width: 100%; max-height:250px;"
                                class="absolute z-10 pb-2    overflow-auto bg-white rounded-md shadow-md">


                                <div class="mx-2 mt-1">
                                  <input v-model="search3" autocomplete="off" type="text" id="agent_full_name"
                                    class="bg-gray-50  mb-1  border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="" required />
                                </div>
                                <global-spinner class="text-center" :spinner="businessLoader" />
                                <li v-if="!businessLoader" v-for="(business, index) in filteredBusiness" :key="index"
                                  @click="selectBusiness(business)" class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                  {{ business.label ? business.label : business.insurance_company + ' -' +
                                    business.product_name }}

                                </li>
                              </ul>
                            </div>
                          </div>
                        </div>

                      </div>
                    </div> -->

                    <div v-if="$page.props.auth.role === 'admin'">
                      <h1
                        style="background-color: #134576"
                        class="my-0 text-center rounded-md py-2 text-white"
                      >
                        Agent Information
                      </h1>
                      <div
                        class="grid xl:grid-cols-3 lg:grid-cols-2 md:grid-cols-1 sm:grid-cols-1 gap-x-8"
                      >
                        <div id="dropdown_main_id">
                          <label
                            class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black"
                            >Select Agent<span class="text-red-400">*</span></label
                          >
                          <button
                            @click="SugestAgent"
                            class="bg-gray-50 mt-1 mb-2 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 flex"
                            id="states-button"
                            data-dropdown-toggle="dropdown-states"
                            type="button"
                          >
                            <span
                              ><svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="w-4 mt-1 h-4"
                              >
                                <path
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                                /></svg></span
                            ><span v-if="!form.agent_full_name" class="ml-2"
                              >Select Agent</span
                            >
                            <span class="ml-2">{{ form.agent_full_name }}</span>
                          </button>

                          <div
                            v-if="firstStepErrors.agent_full_name"
                            class="text-red-500"
                            v-text="firstStepErrors.agent_full_name[0]"
                          ></div>

                          <div v-if="isOpen > 0" class="items-center justify-center">
                            <div class="relative">
                              <ul
                                style="width: 100%; max-height: 250px"
                                class="absolute z-10 pb-2 overflow-auto bg-white rounded-md shadow-md"
                              >
                                <div class="mx-2 mt-1">
                                  <input
                                    v-model="search"
                                    autocomplete="off"
                                    type="text"
                                    id="agent_full_name"
                                    class="bg-gray-50 mb-1 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Filter By Name"
                                    required
                                  />
                                </div>
                                <global-spinner
                                  class="text-center"
                                  :spinner="agentLoader"
                                />
                                <li
                                  v-if="!agentLoader"
                                  v-for="(agent, index) in filteredAgents"
                                  :key="index"
                                  @click="selectagent(agent)"
                                  class="px-4 py-2 hover:bg-gray-100 cursor-pointer"
                                >
                                  {{ agent.first_name }} {{ agent.last_name }}
                                </li>
                              </ul>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <h1
                      style="background-color: #134576"
                      class="mt-5 text-center rounded-md py-2 text-white"
                    >
                      Client Information
                    </h1>

                    <div
                      class="grid xl:grid-cols-3 lg:grid-cols-2 md:grid-cols-1 sm:grid-cols-1 gap-x-8"
                    >
                      <div
                        v-if="$page.props.auth.role === 'internal-agent'"
                        id="dropdown_main_id2"
                      >
                        <div v-if="$page.props.auth.role === 'internal-agent'">
                          <label
                            id="select_client"
                            class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black"
                            >Select Client
                          </label>
                          <button
                            @click="SugestClient"
                            :disabled="is_client"
                            class="bg-gray-50 mt-1 mb-2 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 flex"
                            id="states-button"
                            data-dropdown-toggle="dropdown-states"
                            type="button"
                          >
                            <span
                              ><svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="w-4 mt-1 h-4"
                              >
                                <path
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                                /></svg></span
                            ><span v-if="!form.client_full_name" class="ml-2"
                              >Select Client</span
                            >
                            <span class="ml-2">{{ form.client_full_name }} </span>
                          </button>

                          <div
                            v-if="firstStepErrors.client_full_name"
                            class="text-red-500"
                            v-text="firstStepErrors.client_full_name[0]"
                          ></div>

                          <div v-if="isOpen2 > 0" class="items-center justify-center">
                            <div class="relative">
                              <ul
                                style="width: 100%; max-height: 250px"
                                class="absolute z-10 pb-2 overflow-auto bg-white rounded-md shadow-md"
                              >
                                <div class="mx-2 mt-1">
                                  <input
                                    v-if="!is_client"
                                    v-model="search2"
                                    autocomplete="off"
                                    type="text"
                                    id="agent_full_name"
                                    class="bg-gray-50 mb-1 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Filter By Phone"
                                    required
                                  />
                                </div>
                                <global-spinner
                                  class="text-center"
                                  :spinner="clientsLoader"
                                />
                                <ul class="max-w-md divide-y divide-gray-200">
                                  <li>
                                    <div
                                      @click="clearClient()"
                                      class="cursor-pointer flex items-center space-x-4 rtl:space-x-reverse hover:bg-gray-50 p-2"
                                    >
                                      Clear
                                    </div>
                                  </li>
                                  <li v-for="client in filteredClients" :key="client.id">
                                    <div
                                      @click.prevent="selectClient(client)"
                                      class="cursor-pointer flex items-center space-x-4 rtl:space-x-reverse hover:bg-gray-50 p-2"
                                    >
                                      <div class="flex-1 min-w-0">
                                        <p
                                          class="text-sm font-medium text-gray-900 truncate"
                                        >
                                          {{ client.first_name }} {{ client.last_name }}
                                        </p>
                                        <p class="text-sm text-gray-500 truncate">
                                          {{ client.phone }}
                                        </p>
                                      </div>
                                    </div>
                                  </li>
                                </ul>
                                <!-- <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer ">Clear</li> -->
                              </ul>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div>
                        <label
                          class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black"
                          >First Name<span class="text-red-400">*</span></label
                        >
                        <input
                          v-model="form.first_name"
                          type="text"
                          id="first_name"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder=""
                          required
                        />
                        <div
                          v-if="firstStepErrors.first_name"
                          class="text-red-500"
                          v-text="firstStepErrors.first_name[0]"
                        ></div>
                      </div>

                      <div>
                        <label
                          class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black"
                          >MI</label
                        >
                        <input
                          v-model="form.mi"
                          type="text"
                          id="mi"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder=""
                          required
                        />
                        <div
                          v-if="firstStepErrors.mi"
                          class="text-red-500"
                          v-text="firstStepErrors.mi[0]"
                        ></div>
                      </div>

                      <div>
                        <label
                          class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black"
                          >Last Name<span class="text-red-400">*</span></label
                        >
                        <input
                          v-model="form.last_name"
                          type="text"
                          id="last_name"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder=""
                          required
                        />
                        <div
                          v-if="firstStepErrors.last_name"
                          class="text-red-500"
                          v-text="firstStepErrors.last_name[0]"
                        ></div>
                      </div>

                      <div>
                        <label
                          class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black"
                          >Beneficiary Name<span class="text-red-400">*</span></label
                        >
                        <input
                          v-model="form.beneficiary_name"
                          type="text"
                          id="beneficiary_name"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder=""
                          required
                        />
                        <div
                          v-if="firstStepErrors.beneficiary_name"
                          class="text-red-500"
                          v-text="firstStepErrors.beneficiary_name[0]"
                        ></div>
                      </div>

                      <div>
                        <label
                          class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black"
                          >Beneficiary Relationship<span class="text-red-400"
                            >*</span
                          ></label
                        >
                        <input
                          v-model="form.beneficiary_relationship"
                          type="text"
                          id="beneficiary_relationship"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder=""
                          required
                        />
                        <div
                          v-if="firstStepErrors.beneficiary_relationship"
                          class="text-red-500"
                          v-text="firstStepErrors.beneficiary_relationship[0]"
                        ></div>
                      </div>

                      <div>
                        <label
                          for="EFNumber"
                          class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black"
                          >Street Address 1<span class="text-red-400">*</span></label
                        >
                        <input
                          v-model="form.client_street_address_1"
                          type="text"
                          id="client_street_address_1"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder=""
                          required
                        />
                        <div
                          v-if="firstStepErrors.client_street_address_1"
                          class="text-red-500"
                          v-text="firstStepErrors.client_street_address_1[0]"
                        ></div>
                      </div>

                      <div>
                        <label
                          for="EFNumber"
                          class="block mb-2 mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black"
                          >Street Address 2</label
                        >
                        <input
                          v-model="form.client_street_address_2"
                          type="text"
                          id="client_street_address_2"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder=""
                          required
                        />
                        <div
                          v-if="firstStepErrors.client_street_address_2"
                          class="text-red-500"
                          v-text="firstStepErrors.client_street_address_2[0]"
                        ></div>
                      </div>

                      <div>
                        <label
                          for="EFNumber"
                          class="block mb-2 mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black"
                          >City<span class="text-red-400">*</span></label
                        >
                        <input
                          v-model="form.client_city"
                          type="text"
                          id="client_city"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder=""
                          required
                        />
                        <div
                          v-if="firstStepErrors.client_city"
                          class="text-red-500"
                          v-text="firstStepErrors.client_city[0]"
                        ></div>
                      </div>

                      <div>
                        <label
                          for="EFNumber"
                          class="block mb-2 mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black"
                          >State<span class="text-red-400">*</span></label
                        >
                        <select
                          v-model="form.client_state"
                          @change="StateChange(this)"
                          id="client_state"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        >
                          <option disabled>Select</option>
                          <option v-for="state in states" :value="state.id">
                            {{ state.full_name }}
                          </option>
                        </select>
                        <div
                          v-if="firstStepErrors.client_state"
                          class="text-red-500"
                          v-text="firstStepErrors.client_state[0]"
                        ></div>
                      </div>
                      <div>
                        <label
                          for=""
                          class="block mb-2 mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black"
                          >Zip-code<span class="text-red-400">*</span></label
                        >
                        <input
                          v-model="form.client_zipcode"
                          @input="
                            enforceFiveDigitInput(form.client_zipcode, 'client_zipcode')
                          "
                          type="text"
                          id="client_zipcode"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder=""
                          required
                        />
                        <div
                          v-if="firstStepErrors.client_zipcode"
                          class="text-red-500"
                          v-text="firstStepErrors.client_zipcode[0]"
                        ></div>
                      </div>
                      <div>
                        <label
                          for="EFNumber"
                          class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black"
                          >Client Phone Number<span class="text-red-400">*</span></label
                        >

                        <input
                          v-model="form.client_phone_no"
                          type="text"
                          id="client_phone_no"
                          maxLength="15"
                          @input="
                            CurrencyValidation(form.client_phone_no, 'client_phone_no')
                          "
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder=""
                          required
                        />
                        <div
                          v-if="firstStepErrors.client_phone_no"
                          class="text-red-500"
                          v-text="firstStepErrors.client_phone_no[0]"
                        ></div>
                      </div>

                      <div>
                        <label
                          for="EFNumber"
                          class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black"
                          >Client Email<span class="text-red-400">*</span></label
                        >
                        <input
                          v-model="form.client_email"
                          type="text"
                          id="client_email"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder=""
                          required
                        />
                        <div
                          v-if="firstStepErrors.client_email"
                          class="text-red-500"
                          v-text="firstStepErrors.client_email[0]"
                        ></div>
                      </div>

                      <div>
                        <label
                          for="EFNumber"
                          class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black"
                          >Gender<span class="text-red-400">*</span></label
                        >

                        <select
                          v-model="form.gender"
                          id="gender"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        >
                          <option disabled>Select</option>
                          <option value="Male">Male</option>
                          <option value="Female">Female</option>
                          <option value="Other">Other</option>
                        </select>
                        <div
                          v-if="firstStepErrors.gender"
                          class="text-red-500"
                          v-text="firstStepErrors.gender[0]"
                        ></div>
                      </div>

                      <div>
                        <label
                          for="EFNumber"
                          class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black"
                          >Date of Birth<span class="text-red-400">*</span></label
                        >

                        <VueDatePicker
                          v-model="form.dob"
                          :disabled="disabledDob"
                          format="dd-MMM-yyyy"
                          :maxDate="maxDate"
                          auto-apply
                        >
                        </VueDatePicker>
                        <div
                          v-if="firstStepErrors.dob"
                          class="text-red-500"
                          v-text="firstStepErrors.dob[0]"
                        ></div>
                      </div>
                    </div>

                    <label
                      for="message"
                      class="block mt-5 mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >Notes</label
                    >
                    <textarea
                      v-model="form.notes"
                      id="notes"
                      rows="5"
                      class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                      placeholder="Write your thoughts here..."
                    ></textarea>
                    <div
                      v-if="firstStepErrors.notes"
                      class="text-red-500"
                      v-text="firstStepErrors.notes[0]"
                    ></div>

                    <h1
                      style="background-color: #134576"
                      class="mt-5 text-center rounded-md py-2 text-white"
                    >
                      Policy Information
                    </h1>

                    <div
                      class="grid xl:grid-cols-3 lg:grid-cols-2 md:grid-cols-1 sm:grid-cols-1 gap-x-8"
                    >
                      <!-- <div>
                        <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black">Business
                          Label</label>
                        <input v-model="form.label" type="text" id="label"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder="" required />
                        <div v-if="firstStepErrors.label" class="text-red-500" v-text="firstStepErrors.label[0]"></div>
                      </div> -->

                      <div>
                        <label
                          class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black"
                          >Status<span class="text-red-400">*</span></label
                        >
                        <select
                          v-model="form.status"
                          id="status"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        >
                          <option disabled>Select</option>
                          <option value="Submitted">Submitted</option>
                          <option value="Approved">Approved</option>
                          <option value="Paid">Paid</option>
                          <option value="Lapsed">Lapsed</option>
                          <option value="Declined">Declined</option>
                          <option value="Canceled/Withdrawn">Canceled/Withdrawn</option>
                          <option value="Carrier Missing Information">
                            Carrier Missing Information
                          </option>
                        </select>
                        <div
                          v-if="firstStepErrors.status"
                          class="text-red-500"
                          v-text="firstStepErrors.status[0]"
                        ></div>
                      </div>
                      <div>
                        <label
                          class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black"
                          >Insurance Company<span class="text-red-400">*</span></label
                        >
                        <select
                          v-model="form.insurance_company"
                          id="insurance_company"
                          @change="ChangeProducName()"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        >
                          <option disabled>Select</option>
                          <option
                            v-for="option in getInsuranceCompanyOptions()"
                            v-text="option"
                          ></option>
                        </select>
                        <div
                          v-if="firstStepErrors.insurance_company"
                          class="text-red-500"
                          v-text="firstStepErrors.insurance_company[0]"
                        ></div>
                      </div>

                      <div>
                        <label
                          class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black"
                          >Product Name<span class="text-red-400">*</span></label
                        >
                        <select
                          v-model="form.product_name"
                          id="product_name"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        >
                          <option disabled>Select</option>
                          <option
                            v-for="option in getProductNameOptions()"
                            :value="option"
                            v-text="option"
                          ></option>
                        </select>
                        <div
                          v-if="firstStepErrors.product_name"
                          class="text-red-500"
                          v-text="firstStepErrors.product_name[0]"
                        ></div>
                      </div>

                      <div>
                        <label
                          class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black"
                          >Application Date<span class="text-red-400">*</span></label
                        >
                        <VueDatePicker
                          v-model="form.application_date"
                          :disabled="applicationDate"
                          format="dd-MMM-yyyy"
                          :maxDate="maxDate"
                          auto-apply
                        >
                        </VueDatePicker>
                        <div
                          v-if="firstStepErrors.application_date"
                          class="text-red-500"
                          v-text="firstStepErrors.application_date[0]"
                        ></div>
                      </div>

                      <div>
                        <label
                          class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black"
                          >Coverage Amount<span class="text-red-400">*</span></label
                        >
                        <input
                          v-model="form.coverage_amount"
                          @input="
                            CurrencyValidation(form.coverage_amount, 'coverage_amount')
                          "
                          type="text"
                          id="coverage_amount"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder="$ 0.00"
                          required
                        />
                        <div
                          v-if="firstStepErrors.coverage_amount"
                          class="text-red-500"
                          v-text="firstStepErrors.coverage_amount[0]"
                        ></div>
                      </div>

                      <div>
                        <label
                          class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black"
                          >Coverage Length<span class="text-red-400">*</span></label
                        >
                        <select
                          v-model="form.coverage_length"
                          id="coverage_length"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        >
                          <option disabled>Select</option>
                          <option
                            v-for="option in coverageLengthArray"
                            :value="option"
                            v-text="option"
                          ></option>
                        </select>
                        <div
                          v-if="firstStepErrors.coverage_length"
                          class="text-red-500"
                          v-text="firstStepErrors.coverage_length[0]"
                        ></div>
                      </div>

                      <div>
                        <label
                          class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black"
                          >Premium Frequency<span class="text-red-400">*</span></label
                        >
                        <select
                          v-model="form.premium_frequency"
                          @change="ChangeFrequency(form.premium_frequency)"
                          id="premium_frequency"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        >
                          <option disabled>Select</option>
                          <option value="Monthly">Monthly</option>
                          <option value="Quarterly">Quarterly</option>
                          <option value="Semi-Annual">Semi-Annual</option>
                          <option value="Annual">Annual</option>
                        </select>
                        <div
                          v-if="firstStepErrors.premium_frequency"
                          class="text-red-500"
                          v-text="firstStepErrors.premium_frequency[0]"
                        ></div>
                      </div>

                      <div>
                        <label
                          class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black"
                          >{{
                            form.premium_frequency != "Select"
                              ? form.premium_frequency
                              : ""
                          }}
                          Premium Amount<span class="text-red-400">*</span></label
                        >
                        <input
                          v-model="form.premium_amount"
                          @input="
                            CurrencyValidation(form.premium_amount, 'premium_amount')
                          "
                          type="text"
                          id="premium_amount"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder="$ 0.00"
                          required
                        />
                        <div
                          v-if="firstStepErrors.premium_amount"
                          class="text-red-500"
                          v-text="firstStepErrors.premium_amount[0]"
                        ></div>
                      </div>

                      <div>
                        <label
                          class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black"
                          >Annual Premium Volume<span class="text-red-400">*</span></label
                        >
                        <input
                          v-model="form.premium_volumn"
                          disabled
                          @input="
                            CurrencyValidation(form.premium_volumn, 'premium_volumn')
                          "
                          type="text"
                          id="premium_volumn"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder="$ 0.00"
                          required
                        />
                        <div
                          v-if="firstStepErrors.premium_volumn"
                          class="text-red-500"
                          v-text="firstStepErrors.premium_volumn[0]"
                        ></div>
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
                        <label
                          class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black"
                          >Carrier Writing Number</label
                        >
                        <input
                          v-model="form.carrier_writing_number"
                          type="text"
                          id="carrier_writing_number"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder=""
                          required
                        />
                        <div
                          v-if="firstStepErrors.carrier_writing_number"
                          class="text-red-500"
                          v-text="firstStepErrors.carrier_writing_number[0]"
                        ></div>
                      </div>

                      <div>
                        <label
                          class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black"
                          >Was this app from a lead?<span class="text-red-400"
                            >*</span
                          ></label
                        >
                        <select
                          v-model="form.this_app_from_lead"
                          @change="changeAppLead()"
                          id="this_app_from_lead"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        >
                          <option disabled>Select</option>
                          <option value="YES">YES</option>
                          <option value="NO">NO</option>
                        </select>
                        <div
                          v-if="firstStepErrors.this_app_from_lead"
                          class="text-red-500"
                          v-text="firstStepErrors.this_app_from_lead[0]"
                        ></div>
                      </div>

                      <div>
                        <label
                          class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black"
                          >Source of the lead<span class="text-red-400">*</span></label
                        >
                        <!-- <input v-model="form.source_of_lead" :class="{'bg-slate-300': form.this_app_from_lead == 'NO'}" :disabled="form.this_app_from_lead == 'NO'" type="text" id="source_of_lead"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder="" required /> -->
                        <select
                          v-model="form.source_of_lead"
                          :class="{ 'bg-slate-300': form.this_app_from_lead == 'NO' }"
                          :disabled="form.this_app_from_lead == 'NO'"
                          id="source_of_lead"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        >
                          <option disabled>Select</option>
                          <option value="Allcalls Client">Allcalls</option>
                          <option value="Outside Lead">Outside Lead</option>
                        </select>
                        <div
                          v-if="firstStepErrors.source_of_lead"
                          class="text-red-500"
                          v-text="firstStepErrors.source_of_lead[0]"
                        ></div>
                      </div>

                      <div>
                        <label
                          class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-black"
                          >What Is the Policy Draft Date?<span class="text-red-400"
                            >*</span
                          ></label
                        >
                        <VueDatePicker
                          v-model="form.policy_draft_date"
                          :disabled="disabledPolicydraftdate"
                          format="dd-MMM-yyyy"
                          auto-apply
                        >
                        </VueDatePicker>
                        <div
                          v-if="firstStepErrors.policy_draft_date"
                          class="text-red-500"
                          v-text="firstStepErrors.policy_draft_date[0]"
                        ></div>
                      </div>
                    </div>
                  </div>

                  <div v-show="step == 2">
                    <PreviewInfo
                      :form="form"
                      :heading="'Please confirm all your information is correct?'"
                    />
                  </div>

                  <div
                    class="flex mt-8 mb-10"
                    :class="step > 1 ? 'justify-between' : 'justify-end '"
                  >
                    <a
                      v-if="step > 1"
                      @click.prevent="Previous(step)"
                      class="button-custom-back px-3 py-2 rounded-md flex items-center"
                      v-text="step < 2 ? 'Previous' : 'Change Entries'"
                      href="#"
                    >
                    </a>
                    <a
                      v-if="step < 2"
                      @click.prevent="Next(step)"
                      class="button-custom px-3 py-2 rounded-md flex items-center"
                      href="#"
                    >
                      Next
                    </a>
                    <button
                      v-if="step === 2 && !edit_data"
                      :class="{ 'opacity-25': isLoading === true }"
                      :disabled="isLoading == true"
                      @click="SaveBussinessData()"
                      class="button-custom px-3 py-2 rounded-md flex items-center"
                      href="#"
                    >
                      <global-spinner :spinner="isLoading" />
                      <span v-if="!attactClientEdit">Confirm</span>
                      <span v-if="attactClientEdit">Update</span>
                    </button>
                    <button
                      v-if="step === 2 && edit_data"
                      :class="{ 'opacity-25': isLoading2 === true }"
                      :disabled="isLoading2 == true"
                      @click="UpdateBussinessData()"
                      class="button-custom px-3 py-2 rounded-md flex items-center"
                      href="#"
                    >
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
