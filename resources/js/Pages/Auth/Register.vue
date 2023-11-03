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
import { ref, reactive, computed, watchEffect } from "vue";
// import { useVeeValidate } from '@vee-validate/vue3';
import axios from "axios";
// If you are using PurgeCSS, make sure to whitelist the carousel CSS classes
import "vue3-carousel/dist/carousel.css";
import { Carousel, Slide, Pagination, Navigation } from "vue3-carousel";
import { router } from "@inertiajs/vue3"

let step = ref(0);
let firstStepErrors = ref({});
let uiEmailValidation = ref({
  "isValid": false
});

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
  agentToken: {
    required: false,
    type: String,
  }
});


console.log(props);

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
  phone: "",
  selectedCountryCode: '+1',
});
let countryList = ref([
  { "name": "United States", "code": "1", "codeName": "USA" },
  { "name": "Afghanistan", "code": "93", "codeName": "AFG" },
  { "name": "Albania", "code": "355", "codeName": "ALB" },
  { "name": "Algeria", "code": "213", "codeName": "DZA" },
  { "name": "American Samoa", "code": "1,684", "codeName": "ASM" },
  { "name": "Andorra", "code": "376", "codeName": "AND" },
  { "name": "Angola", "code": "244", "codeName": "AGO" },
  { "name": "Anguilla", "code": "1,264", "codeName": "AIA" },
  { "name": "Antarctica", "code": "672, 64", "codeName": "ATA" },
  { "name": "Antigua and Barbuda", "code": "1,268", "codeName": "ATG" },
  { "name": "Argentina", "code": "54", "codeName": "ARG" },
  { "name": "Armenia", "code": "374", "codeName": "ARM" },
  { "name": "Aruba", "code": "297", "codeName": "ABW" },
  { "name": "Ascension Island", "code": "247", "codeName": "ASC" },
  { "name": "Australia", "code": "61", "codeName": "AUS" },
  { "name": "Austria", "code": "43", "codeName": "AUT" },
  { "name": "Azerbaijan", "code": "994", "codeName": "AZE" },
  { "name": "Bahamas", "code": "1,242", "codeName": "BHS" },
  { "name": "Bahrain", "code": "973", "codeName": "BHR" },
  { "name": "Bangladesh", "code": "880", "codeName": "BGD" },
  { "name": "Barbados", "code": "1,246", "codeName": "BRB" },
  { "name": "Belarus", "code": "375", "codeName": "BLR" },
  { "name": "Belgium", "code": "32", "codeName": "BEL" },
  { "name": "Belize", "code": "501", "codeName": "BLZ" },
  { "name": "Benin", "code": "229", "codeName": "BEN" },
  { "name": "Bermuda", "code": "1,441", "codeName": "BMU" },
  { "name": "Bhutan", "code": "975", "codeName": "BTN" },
  { "name": "Bolivia", "code": "591", "codeName": "BOL" },
  { "name": "Bosnia and Herzegovina", "code": "387", "codeName": "BIH" },
  { "name": "Botswana", "code": "267", "codeName": "BWA" },
  { "name": "Brazil", "code": "55", "codeName": "BRA" },
  { "name": "British Virgin Islands", "code": "1,284", "codeName": "VGB" },
  { "name": "Brunei", "code": "673", "codeName": "BRN" },
  { "name": "Bulgaria", "code": "359", "codeName": "BGR" },
  { "name": "Burkina Faso", "code": "226", "codeName": "BFA" },
  { "name": "Burma (Myanmar)", "code": "95", "codeName": "MMR" },
  { "name": "Burundi", "code": "257", "codeName": "BDI" },
  { "name": "Cambodia", "code": "855", "codeName": "KHM" },
  { "name": "Cameroon", "code": "237", "codeName": "CMR" },
  { "name": "Canada", "code": "1", "codeName": "CAN" },
  { "name": "Cape Verde", "code": "238", "codeName": "CPV" },
  { "name": "Cayman Islands", "code": "1,345", "codeName": "CYM" },
  { "name": "Central African Republic", "code": "236", "codeName": "CAF" },
  { "name": "Chad", "code": "235", "codeName": "TCD" },
  { "name": "Chile", "code": "56", "codeName": "CHL" },
  { "name": "China", "code": "86", "codeName": "CHN" },
  { "name": "Christmas Island", "code": "61", "codeName": "CXR" },
  { "name": "Cocos (Keeling) Islands", "code": "61", "codeName": "CCK" },
  { "name": "Colombia", "code": "57", "codeName": "COL" },
  { "name": "Comoros", "code": "269", "codeName": "COM" },
  { "name": "Congo", "code": "242", "codeName": "COG" },
  { "name": "Cook Islands", "code": "682", "codeName": "COK" },
  { "name": "Costa Rica", "code": "506", "codeName": "CRC" },
  { "name": "Croatia", "code": "385", "codeName": "HRV" },
  { "name": "Cuba", "code": "53", "codeName": "CUB" },
  { "name": "Cyprus", "code": "357", "codeName": "CYP" },
  { "name": "Czech Republic", "code": "420", "codeName": "CZE" },
  { "name": "Democratic Republic of the Congo", "code": "243", "codeName": "COD" },
  { "name": "Denmark", "code": "45", "codeName": "DNK" },
  { "name": "Diego Garcia", "code": "246", "codeName": "DGA" },
  { "name": "Djibouti", "code": "253", "codeName": "DJI" },
  { "name": "Dominica", "code": "1,767", "codeName": "DMA" },
  { "name": "Dominican Republic", "code": "1 809, 1 829, 1 849", "codeName": "DOM" },
  { "name": "Ecuador", "code": "593", "codeName": "ECU" },
  { "name": "Egypt", "code": "20", "codeName": "EGY" },
  { "name": "El Salvador", "code": "503", "codeName": "SLV" },
  { "name": "Equatorial Guinea", "code": "240", "codeName": "GNQ" },
  { "name": "Eritrea", "code": "291", "codeName": "ERI" },
  { "name": "Estonia", "code": "372", "codeName": "EST" },
  { "name": "Ethiopia", "code": "251", "codeName": "ETH" },
  { "name": "Falkland Islands", "code": "500", "codeName": "FLK" },
  { "name": "Faroe Islands", "code": "298", "codeName": "FRO" },
  { "name": "Fiji", "code": "679", "codeName": "FJI" },
  { "name": "Finland", "code": "358", "codeName": "FIN" },
  { "name": "France", "code": "33", "codeName": "FRA" },
  { "name": "French Guiana", "code": "594", "codeName": "GUF" },
  { "name": "French Polynesia", "code": "689", "codeName": "PYF" },
  { "name": "Gabon", "code": "241", "codeName": "GAB" },
  { "name": "Gambia", "code": "220", "codeName": "GMB" },
  { "name": "Georgia", "code": "995", "codeName": "GEO" },
  { "name": "Germany", "code": "49", "codeName": "DEU" },
  { "name": "Ghana", "code": "233", "codeName": "GHA" },
  { "name": "Gibraltar", "code": "350", "codeName": "GIB" },
  { "name": "Greece", "code": "30", "codeName": "GRC" },
  { "name": "Greenland", "code": "299", "codeName": "GRL" },
  { "name": "Grenada", "code": "1,473", "codeName": "GRD" },
  { "name": "Guadeloupe", "code": "590", "codeName": "GLP" },
  { "name": "Guam", "code": "1,671", "codeName": "GUM" },
  { "name": "Guatemala", "code": "502", "codeName": "GTM" },
  { "name": "Guinea", "code": "224", "codeName": "GIN" },
  { "name": "Guinea-Bissau", "code": "245", "codeName": "GNB" },
  { "name": "Guyana", "code": "592", "codeName": "GUY" },
  { "name": "Haiti", "code": "509", "codeName": "HTI" },
  { "name": "Holy See (Vatican City)", "code": "39", "codeName": "VAT" },
  { "name": "Honduras", "code": "504", "codeName": "HND" },
  { "name": "Hong Kong", "code": "852", "codeName": "HKG" },
  { "name": "Hungary", "code": "36", "codeName": "HUN" },
  { "name": "Iceland", "code": "354", "codeName": "IS" },
  { "name": "India", "code": "91", "codeName": "IND" },
  { "name": "Indonesia", "code": "62", "codeName": "IDN" },
  { "name": "Iran", "code": "98", "codeName": "IRN" },
  { "name": "Iraq", "code": "964", "codeName": "IRQ" },
  { "name": "Ireland", "code": "353", "codeName": "IRL" },
  { "name": "Isle of Man", "code": "44", "codeName": "IMN" },
  { "name": "Israel", "code": "972", "codeName": "ISR" },
  { "name": "Italy", "code": "39", "codeName": "ITA" },
  { "name": "Ivory Coast (Côte d'Ivoire)", "code": "225", "codeName": "CIV" },
  { "name": "Jamaica", "code": "1,876", "codeName": "JAM" },
  { "name": "Japan", "code": "81", "codeName": "JPN" },
  { "name": "Jersey", "code": "44", "codeName": "JEY" },
  { "name": "Jordan", "code": "962", "codeName": "JOR" },
  { "name": "Kazakhstan", "code": "7", "codeName": "KAZ" },
  { "name": "Kenya", "code": "254", "codeName": "KEN" },
  { "name": "Kiribati", "code": "686", "codeName": "KIR" },
  { "name": "Kuwait", "code": "965", "codeName": "KWT" },
  { "name": "Kyrgyzstan", "code": "996", "codeName": "KGZ" },
  { "name": "Laos", "code": "856", "codeName": "LAO" },
  { "name": "Latvia", "code": "371", "codeName": "LVA" },
  { "name": "Lebanon", "code": "961", "codeName": "LBN" },
  { "name": "Lesotho", "code": "266", "codeName": "LSO" },
  { "name": "Liberia", "code": "231", "codeName": "LBR" },
  { "name": "Libya", "code": "218", "codeName": "LBY" },
  { "name": "Liechtenstein", "code": "423", "codeName": "LIE" },
  { "name": "Lithuania", "code": "370", "codeName": "LTU" },
  { "name": "Luxembourg", "code": "352", "codeName": "LUX" },
  { "name": "Macau", "code": "853", "codeName": "MAC" },
  { "name": "Macedonia", "code": "389", "codeName": "MKD" },
  { "name": "Madagascar", "code": "261", "codeName": "MDG" },
  { "name": "Malawi", "code": "265", "codeName": "MWI" },
  { "name": "Malaysia", "code": "60", "codeName": "MYS" },
  { "name": "Maldives", "code": "960", "codeName": "MDV" },
  { "name": "Mali", "code": "223", "codeName": "MLI" },
  { "name": "Malta", "code": "356", "codeName": "MLT" },
  { "name": "Marshall Islands", "code": "692", "codeName": "MHL" },
  { "name": "Martinique", "code": "596", "codeName": "MTQ" },
  { "name": "Mauritania", "code": "222", "codeName": "MRT" },
  { "name": "Mauritius", "code": "230", "codeName": "MUS" },
  { "name": "Mayotte", "code": "262", "codeName": "MYT" },
  { "name": "Mexico", "code": "52", "codeName": "MEX" },
  { "name": "Micronesia", "code": "691", "codeName": "FSM" },
  { "name": "Moldova", "code": "373", "codeName": "MDA" },
  { "name": "Monaco", "code": "377", "codeName": "MCO" },
  { "name": "Mongolia", "code": "976", "codeName": "MNG" },
  { "name": "Montenegro", "code": "382", "codeName": "MNE" },
  { "name": "Montserrat", "code": "1,664", "codeName": "MSR" },
  { "name": "Morocco", "code": "212", "codeName": "MAR" },
  { "name": "Mozambique", "code": "258", "codeName": "MOZ" },
  { "name": "Namibia", "code": "264", "codeName": "NAM" },
  { "name": "Nauru", "code": "674", "codeName": "NRU" },
  { "name": "Nepal", "code": "977", "codeName": "NPL" },
  { "name": "Netherlands", "code": "31", "codeName": "NLD" },
  { "name": "Netherlands Antilles", "code": "599", "codeName": "ANT" },
  { "name": "New Caledonia", "code": "687", "codeName": "NCL" },
  { "name": "New Zealand", "code": "64", "codeName": "NZL" },
  { "name": "Nicaragua", "code": "505", "codeName": "NIC" },
  { "name": "Niger", "code": "227", "codeName": "NER" },
  { "name": "Nigeria", "code": "234", "codeName": "NGA" },
  { "name": "Niue", "code": "683", "codeName": "NIU" },
  { "name": "Norfolk Island", "code": "672", "codeName": "NFK" },
  { "name": "North Korea", "code": "850", "codeName": "PRK" },
  { "name": "Northern Mariana Islands", "code": "1,670", "codeName": "MNP" },
  { "name": "Norway", "code": "47", "codeName": "NOR" },
  { "name": "Oman", "code": "968", "codeName": "OMN" },
  { "name": "Pakistan", "code": "92", "codeName": "PAK" },
  { "name": "Palau", "code": "680", "codeName": "PLW" },
  { "name": "Palestine", "code": "970", "codeName": "PSE" },
  { "name": "Panama", "code": "507", "codeName": "PAN" },
  { "name": "Papua New Guinea", "code": "675", "codeName": "PNG" },
  { "name": "Paraguay", "code": "595", "codeName": "PRY" },
  { "name": "Peru", "code": "51", "codeName": "PER" },
  { "name": "Philippines", "code": "63", "codeName": "PHL" },
  { "name": "Pitcairn Islands", "code": "870", "codeName": "PCN" },
  { "name": "Poland", "code": "48", "codeName": "POL" },
  { "name": "Portugal", "code": "351", "codeName": "PRT" },
  { "name": "Puerto Rico", "code": "1 787, 1 939", "codeName": "PRI" },
  { "name": "Qatar", "code": "974", "codeName": "QAT" },
  { "name": "Republic of the Congo", "code": "242", "codeName": "COG" },
  { "name": "Reunion Island", "code": "262", "codeName": "REU" },
  { "name": "Romania", "code": "40", "codeName": "ROU" },
  { "name": "Russia", "code": "7", "codeName": "RUS" },
  { "name": "Rwanda", "code": "250", "codeName": "RWA" },
  { "name": "Saint Barthelemy", "code": "590", "codeName": "BLM" },
  { "name": "Saint Helena", "code": "290", "codeName": "SHN" },
  { "name": "Saint Kitts and Nevis", "code": "1,869", "codeName": "KNA" },
  { "name": "Saint Lucia", "code": "1,758", "codeName": "LCA" },
  { "name": "Saint Martin", "code": "590", "codeName": "MAF" },
  { "name": "Saint Pierre and Miquelon", "code": "508", "codeName": "SPM" },
  { "name": "Saint Vincent and the Grenadines", "code": "1,784", "codeName": "VCT" },
  { "name": "Samoa", "code": "685", "codeName": "WSM" },
  { "name": "San Marino", "code": "378", "codeName": "SMR" },
  { "name": "Sao Tome and Principe", "code": "239", "codeName": "STP" },
  { "name": "Saudi Arabia", "code": "966", "codeName": "SAU" },
  { "name": "Senegal", "code": "221", "codeName": "SEN" },
  { "name": "Serbia", "code": "381", "codeName": "SRB" },
  { "name": "Seychelles", "code": "248", "codeName": "SYC" },
  { "name": "Sierra Leone", "code": "232", "codeName": "SLE" },
  { "name": "Singapore", "code": "65", "codeName": "SGP" },
  { "name": "Sint Maarten", "code": "1,721", "codeName": "SXM" },
  { "name": "Slovakia", "code": "421", "codeName": "SVK" },
  { "name": "Slovenia", "code": "386", "codeName": "SVN" },
  { "name": "Solomon Islands", "code": "677", "codeName": "SLB" },
  { "name": "Somalia", "code": "252", "codeName": "SOM" },
  { "name": "South Africa", "code": "27", "codeName": "ZAF" },
  { "name": "South Korea", "code": "82", "codeName": "KOR" },
  { "name": "South Sudan", "code": "211", "codeName": "SSD" },
  { "name": "Spain", "code": "34", "codeName": "ESP" },
  { "name": "Sri Lanka", "code": "94", "codeName": "LKA" },
  { "name": "Sudan", "code": "249", "codeName": "SDN" },
  { "name": "Suriname", "code": "597", "codeName": "SUR" },
  { "name": "Svalbard", "code": "47", "codeName": "SJM" },
  { "name": "Swaziland", "code": "268", "codeName": "SWZ" },
  { "name": "Sweden", "code": "46", "codeName": "SWE" },
  { "name": "Switzerland", "code": "41", "codeName": "CHE" },
  { "name": "Syria", "code": "963", "codeName": "SYR" },
  { "name": "Taiwan", "code": "886", "codeName": "TWN" },
  { "name": "Tajikistan", "code": "992", "codeName": "TJK" },
  { "name": "Tanzania", "code": "255", "codeName": "TZA" },
  { "name": "Thailand", "code": "66", "codeName": "THA" },
  { "name": "Timor-Leste (East Timor)", "code": "670", "codeName": "TLS" },
  { "name": "Togo", "code": "228", "codeName": "TGO" },
  { "name": "Tokelau", "code": "690", "codeName": "TKL" },
  { "name": "Tonga Islands", "code": "676", "codeName": "TON" },
  { "name": "Trinidad and Tobago", "code": "1,868", "codeName": "TTO" },
  { "name": "Tunisia", "code": "216", "codeName": "TUN" },
  { "name": "Turkey", "code": "90", "codeName": "TUR" },
  { "name": "Turkmenistan", "code": "993", "codeName": "TKM" },
  { "name": "Turks and Caicos Islands", "code": "1,649", "codeName": "TCA" },
  { "name": "Tuvalu", "code": "688", "codeName": "TUV" },
  { "name": "Uganda", "code": "256", "codeName": "UGA" },
  { "name": "Ukraine", "code": "380", "codeName": "UKR" },
  { "name": "United Arab Emirates", "code": "971", "codeName": "ARE" },
  { "name": "United Kingdom", "code": "44", "codeName": "GBR" },
  { "name": "Uruguay", "code": "598", "codeName": "URY" },
  { "name": "US Virgin Islands", "code": "1,340", "codeName": "VIR" },
  { "name": "Uzbekistan", "code": "998", "codeName": "UZB" },
  { "name": "Vanuatu", "code": "678", "codeName": "VUT" },
  { "name": "Venezuela", "code": "58", "codeName": "VEN" },
  { "name": "Vietnam", "code": "84", "codeName": "VNM" },
  { "name": "Wallis and Futuna", "code": "681", "codeName": "WLF" },
  { "name": "Western Sahara", "code": "212", "codeName": "ESH" },
  { "name": "Yemen", "code": "967", "codeName": "YEM" },
  { "name": "Zambia", "code": "260", "codeName": "ZMB" },
  { "name": "Zimbabwe", "code": "263", "codeName": "ZWE" }

],)

let text = ref([]);

let check = () => {

};

let errors = ref(null);

let validateEmail = (email) => {
  return /\S+@\S+\.\S+/.test(email); // Simple regex for email validation
}
const isLoading = ref(false);
let submit = () => {
  isLoading.value = true
  if (validateEmail(form.email)) {

    let registerUrl = "/register";

    if (props.agentToken) {
      registerUrl += `?agentToken=${props.agentToken}`;
    }

    return axios
      .post(registerUrl, form)
      .then((response) => {

        let postRegistrationUrl = '/login';
        if (props.agentToken) {
          postRegistrationUrl = '/registration-steps?agentToken=' + props.agentToken
        }

        router.visit(postRegistrationUrl)
        isLoading.value = false
      })
      .catch((error) => {
        if (error.response) {
          // The request was made and the server responded with a status code
          // that falls out of the range of 2xx
          errors.value = error.response.data.errors;
          isLoading.value = false

          firstStepErrors.value = error.response.data.errors;
          if (error.response.status === 400) {
            uiEmailValidation.value.isValid = false;
            // Handle validation errors here.
            firstStepErrors.value = error.response.data.errors;
            isLoading.value = false
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
  else {
    uiEmailValidation.value.isValid = true;
    isLoading.value = false
  }
};

// let goBack = () => {
//   step.value = 0;
// };
let countyFocus = ref(false)
let handleFocus = () => {
  countyFocus.value = false
}
let handleBlur = () => {
  countyFocus.value = false
}
let toggleCountryFocus = () => {
  countyFocus.value = !countyFocus.value
}







const searchTerm = ref('');
const selectedCountry = ref('');
const selectedCountryCode = ref('');

const filteredCountries = computed(() => {
  console.log('computed');
  return countryList.value.filter(
    (country) =>
      country.name.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
      country.codeName.toLowerCase().includes(searchTerm.value.toLowerCase())
  );
});
const updateSelectedCountry = () => {
  if (selectedCountry.value) {
    selectedCountryCode.value = selectedCountry.value.codeName;
  }
};

const onInput = (event) => {
  console.log('onInput');
  selectedCountry.value = event.target.value;
  const foundCountry = countryList.value.find(
    (country) => 
      country.name.toLowerCase().includes(selectedCountry.value.toLowerCase()) ||
      country.codeName.toLowerCase().includes(selectedCountry.value.toLowerCase())
  );
  if (foundCountry) {
    selectedCountryCode.value = foundCountry.name;
  }
};
</script>

<template>
  <!-- <GuestLayout> -->
  <NewGuestLayout>

    <Head title="Register" />

    <template v-slot:loadingText>
      <div class="px-10 text-center text-4xl xl:text-5xl text-custom-white font-extrabold tracking-tighter">
        Start Receiving Live Calls Now!
      </div>
      <div class="text-md text-blue-400 font-semibold text-center px-10 mb-6">
        No risk, no contracts, and no long-term commitment. Cancel anytime,
        hassle-free.
      </div>
    </template>

    <form @submit.prevent="submit">
      <div v-show="step === 0">
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
          <div v-if="uiEmailValidation.isValid" class="text-red-500">Please enter valid email address.</div>
          <!-- <InputError class="mt-2" :message="form.errors.email" /> -->
          <div v-if="firstStepErrors.email" class="text-red-500" v-text="firstStepErrors.email[0]"></div>
        </div>

        <div class="mt-4">
          <GuestInputLabel for="phone" value="Phone" />

          <GuestTextInput id="phone" type="text" placeholder="+1 (000) 000-0000" class="mt-1 block w-full"
            v-model="form.phone" minlength="10" />

          <!-- <InputError class="mt-2" :message="form.errors.phone" /> -->
          <div v-if="firstStepErrors.phone" class="text-red-500" v-text="firstStepErrors.phone[0]"></div>
        </div>





        <div class="flex">
          <button @click="toggleCountryFocus" class="drop_down_main" id="states-button"
            data-dropdown-toggle="dropdown-states" type="button">
            <span>USA</span> <span><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor" class="w-4 ml-1 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
              </svg></span>
          </button>
          <GuestTextInput @focus="handleFocus" id="phone" type="text" placeholder="+1 (000) 000-0000"
            class="mt-1  block w-full" v-model="form.phone" minlength="10" />

        </div>

        <div v-if="countyFocus" class="mt-3" style="height:300px; overflow:auto;">

          <div v-for="(country, index) in countryList" :value="index" :key="index" @click="ChangeCountry(index)"
            class="flex cursor-pointer rounded-lg  hover:bg-gray-300 p-2 mr-2">{{
              country.name
            }}</div>
        </div>










        <div>
          <div class="country-select-container">
            <div class="country-code">
              <input type="text" v-model="selectedCountryCode" placeholder="Country Code" />
            </div>
            <div class="country-dropdown">
              <input list="countries" v-model="selectedCountry" @input="onInput" placeholder="Search country..." />
              <datalist id="countries">
                <option v-for="country in filteredCountries" :value="country.name" :key="country.code">
                  {{ country.name }}
                </option>
              </datalist>
            </div>
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
        <div class="flex pt-6 box-shadow">
          <input id="checked-checkbox" type="checkbox" v-model="form.consent"
            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
          <label for="checked-checkbox" class="ml-2 text-xs font-medium text-gray-900 dark:text-gray-400">
            By checking this box, I verify that this is my mobile number and that I would like to sign up to receive
            messages from “AllCalls.io” program by AllCalls LLC. I understand that I am not required to provide my consent
            as a condition of purchasing any products or services. Msg freq may vary. Msg data rates may apply. Reply HELP
            for help or STOP to optout. Read <a href="https://allcalls.io/terms.php">Terms and Conditions</a>. Read <a
              href="https://allcalls.io/privacy.php">Privacy Policy</a>.
          </label>
        </div>
        <div v-if="firstStepErrors.consent" class="text-red-500 ml-5" v-text="firstStepErrors.consent[0]"></div>
        <div class="flex items-center justify-end mt-4" :class="{ 'opacity-25': form.processing || isLoading }"
          :disabled="form.processing">
          <PrimaryButton type="button" class="ml-4" @click.prevent="submit">
            <global-spinner :spinner="isLoading" />Submit
          </PrimaryButton>
        </div>
      </div>
    </form>

    <template v-slot:titles>
      <div class="text-4xl lg:text-5xl xl:text-8xl text-white mb-10">
        Getting Started is Easy.<span class="text-custom-green">Create your account today!</span>
      </div>
    </template>

    <template v-slot:subtitles>
      <div class="text-custom-blue font-bold text-2xl lg:text-2xl xl:text-5xl text-3xl">
        Try our Calls for Yourself!
      </div>

      <div class="text-custom-blue text-sm md:text-lg lg:text-2xl mt-6 font-bold">
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
  background-color: #d7d7d7;
  border-radius: 5px;
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
