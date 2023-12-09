<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed } from "vue";
import GuestTextInput from "@/Components/GuestTextInput.vue";
import GuestInputLabel from "@/Components/GuestInputLabel.vue";
import PreviewInfo from "@/Pages/InternalAgent/MyBusiness/PreviewInfo.vue";
import { toaster } from "@/helper.js";
import { Head, router, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";


let emits = defineEmits();
let props = defineProps({
  addBusinessModal: Boolean,

  states: Array,
});
let firstStepErrors = ref({})

let loading = ref(false);
let showConfirmationWindow = ref(false);
let index = ref(0);
let step = ref(1);

let typeA = [31, 32, 33, 34, 35, 36, 37, 38, 39, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 40, 19, 20, 21, 22, 23, 41, 29, 30];
let typeB = [31, 32, 33, 34, 35, 36, 37, 38, 39, 0, 2, 14, 6, 7, 8, 9, 10, 15, 16, 40, 19, 20, 21, 22, 23, 41, 29, 30];
let typeC = [31, 32, 33, 34, 35, 36, 37, 38, 39, 0, 1, 2, 17, 3, 4, 5, 6, 7, 8, 9, 10, 15, 16, 40, 19, 20, 21, 22, 23, 41, 29, 30];
let typeD = [31, 32, 33, 34, 35, 36, 37, 38, 39, 0, 1, 2, 11, 12, 13, 6, 7, 8, 9, 10, 15, 16, 40, 19, 20, 21, 22, 23, 41, 29, 30];
let typeE = [31, 32, 33, 34, 35, 36, 37, 38, 39, 0, 1, 2, 11, 12, 13, 6, 7, 8, 9, 10, 40, 19, 31, 20, 21, 22, 23, 41, 29, 30];
let typeF = [31, 32, 33, 34, 35, 36, 37, 38, 39, 0, 2, 14, 18, 6, 7, 8, 9, 10, 15, 16, 40, 19, 31, 20, 21, 22, 23, 41, 29, 30];

let form = ref({
  agent_full_name: "",
  agent_email: "",
  ef_number: "",
  upline_manager: "Select",
  split_sale: "Select",
  split_sale_type: "Select",
  split_agent_email: "",
  insurance_company: "AETNA/CVS",
  product_name: "Select",
  application_date: "",
  coverage_amount: '',
  coverage_length: "Select",
  premium_frequency: "Select",
  premium_amount: '',
  premium_volumn: '',
  equis_writing_number_carrier: 'Select',
  carrier_writing_number: '',
  this_app_from_lead: "Select",
  source_of_lead: '',
  appointment_type: 'Select',
  policy_draft_date: '',
  first_name: '',
  mi: '',
  annual_target_premium: '',
  annual_planned_premium: '',
  annual_excess_premium: '',
  intial_investment_amount: '',
  refer_another_agent: 'Select',
  this_an_sdic: 'Select',
  recurring_premium: 'Select',
  last_name: '',
  dob: '',
  gender: 'Select',
  client_street_address_1: '',
  client_street_address_2: '',
  client_city: '',
  client_state: 'Select',
  client_zipcode: '',
  client_phone_no: '',
  client_email: '',


});
let companies = ref({
  "AETNA/CVS": {
    "Individual Whole Life": {
      questions: typeA,
    },
  },
  AIG: {
    GIWL: {
      questions: typeA,
    },
    "Power 10 Protector": {
      questions: typeB,
    },
    "Power 7 Protector": {
      questions: typeB,
    },
    "SimpliNow Legacy": {
      questions: typeA,
    },
  },
  "American Amicable": {
    "Easy Term": {
      questions: typeA,
    },
    "Express UL": {
      questions: typeA,
    },
    "Family Choice": {
      questions: typeA,
    },
    "Home Protector": {
      questions: typeA,
    },
    OBA: {
      questions: typeA,
    },
    "Security Protector": {
      questions: typeA,
    },
    "Senior Choice": {
      questions: typeA,
    },
    "Survivor Protector": {
      questions: typeA,
    },
    "Term Made Simple": {
      questions: typeA,
    },
  },
  "American Equity": {
    "AssetShield 10 with Enhancements": {
      questions: typeB,
    },
    "AssetShield 5 with Enhancements": {
      questions: typeB,
    },
    "AssetShield 7 with Enhancements": {
      questions: typeB,
    },
    "Bonus Gold": {
      questions: typeB,
    },
    "CA AssetShield 5": {
      questions: typeB,
    },
    "CA AssetShield 9": {
      questions: typeB,
    },
    "CA Destinations 9": {
      questions: typeB,
    },
    "CA IncomeShield 7": {
      questions: typeB,
    },
    "CA IncomeShield 9 With LIBR": {
      questions: typeB,
    },
    "CA IncomeShield 9 Without LIBR": {
      questions: typeB,
    },
    "EstateShield 10": {
      questions: typeB,
    },
    "FL Retirement Gold": {
      questions: typeB,
    },
    "FlexShield 10": {
      questions: typeB,
    },
    "Guarantee 6": {
      questions: typeB,
    },
    "Guarantee Series (5,6,7)": {
      questions: typeB,
    },
    "GuaranteeShield 3": {
      questions: typeB,
    },
    "GuaranteeShield 5": {
      questions: typeB,
    },
    "Immediate Annuity": {
      questions: typeB,
    },
    "IncomeSheild 10 With LIBR": {
      questions: typeB,
    },
    "IncomeShield 10 Without LIBR": {
      questions: typeB,
    },
    "IncomeShield 7": {
      questions: typeB,
    },
    "Retirement Gold": {
      questions: typeB,
    },
  },
  Americo: {
    "Advantage Whole Life": {
      questions: typeA,
    },
    Continuation: {
      questions: typeA,
    },
    "Eagle Guaranteed Series": {
      questions: typeA,
    },
    "Eagle Premier Series": {
      questions: typeA,
    },
    "Payment Protector Continuation": {
      questions: typeA,
    },
    "Term /ADB": {
      questions: typeA,
    },
    "Term 100": {
      questions: typeA,
    },
    "Term 125": {
      questions: typeA,
    },
    "Term CBO-100": {
      questions: typeA,
    },
    "Term CBO-50": {
      questions: typeA,
    },
    "Term Payment Protector": {
      questions: typeA,
    },
  },
  Ameritas: {
    "Access Whole Life": {
      questions: typeC,
    },
    "Access Whole Life Simplified": {
      questions: typeC,
    },
    "Compass Flexible Premium": {
      questions: typeB,
    },
    "Excel Essential": {
      questions: typeD,
    },
    "FLX Living Benefits IUL": {
      questions: typeD,
    },
    "Growth 10 Pay Whole Life": {
      questions: typeC,
    },
    "Growth Index UL": {
      questions: typeC,
    },
    "Growth Whole Life": {
      questions: typeC,
    },
    "Value Plus Index UL": {
      questions: typeC,
    },
    "Value Plus Survivor IUL": {
      questions: typeC,
    },
    "Value Plus Term": {
      questions: typeA,
    },
    "Value Plus UL": {
      questions: typeC,
    },
    "Value Plus Whole Life": {
      questions: typeC,
    },
    "Value Plus Whole Life Under 25K": {
      questions: typeC,
    },
  },
  "Assurant Life": {
    "Level Benefit Whole Life": {
      questions: typeA,
    },
    "Modified Benefit Whole Life": {
      questions: typeA,
    },
  },
  Athene: {
    "Agility 10": {
      questions: typeB,
    },
    "Agility 7": {
      questions: typeB,
    },
    "Ascent Series": {
      questions: typeB,
    },
    "Max Rate 3": {
      questions: typeB,
    },
    "Max Rate 5": {
      questions: typeB,
    },
    "Max Rate 7": {
      questions: typeB,
    },
    "Performance Elite 10": {
      questions: typeB,
    },
    "Performance Elite 15": {
      questions: typeB,
    },
    "Performance Elite 7": {
      questions: typeB,
    },
    "Single Premium Immediate": {
      questions: typeB,
    },
  },
  CFG: {
    "Dignified Choice Series": {
      questions: typeA,
    },
    "SafeShield Simplified Issue": {
      questions: typeA,
    },
  },
  "Columbus Life": {
    "Advantage Single Premium Fixed Indexed": {
      questions: typeB,
    },
    "Expedition Survivorship IUL": {
      questions: typeD,
    },
    "Explorer Plus UL": {
      questions: typeE,
    },
    "Indexed Explorer Plus UL": {
      questions: typeD,
    },
    "Nautical Term": {
      questions: typeA,
    },
    "Voyager UL": {
      questions: typeA,
    },
  },
  EquiTrust: {
    "Certainty Select": {
      questions: typeB,
    },
    ChoiceFour: {
      questions: typeB,
    },
    "Confidence Income Annuity": {
      questions: typeB,
    },
    "MarketForce Bonus Index": {
      questions: typeB,
    },
    "MarketMax Index": {
      questions: typeB,
    },
    "MarketPower Bonus Index": {
      questions: typeB,
    },
    "MarketSeven Index": {
      questions: typeB,
    },
    "MarketTen Bonus Index": {
      questions: typeB,
    },
    "MarketValue Index": {
      questions: typeB,
    },
  },
  Ethos: {
    "Ethos Final Expense": {
      questions: typeA,
    },
    "Ethos Term Life Prime (LGA)": {
      questions: typeC,
    },
    "Ethos Term Life Select (LGA-Elevated)": {
      questions: typeC,
    },
    "Ethos Term Life Spectrum (Ameritas)": {
      questions: typeC,
    },
    "TruStage Advantage Whole Life": {
      questions: typeC,
    },
    "TruStage Simplified Issue Term Life": {
      questions: typeC,
    },
    "TrueStage Guaranteed Acceptance Whole Life": {
      questions: typeA,
    },
  },
  "Fidelity & Guaranty Life": {
    "Accelerator Plus 10": {
      questions: typeB,
    },
    "Accelerator Plus 14": {
      questions: typeB,
    },
    "Accumulator Plus 10": {
      questions: typeB,
    },
    "Accumulator Plus 7": {
      questions: typeB,
    },
    "Dynamic Accumulator": {
      questions: typeB,
    },
    Everlast: {
      questions: typeD,
    },
    ExecuDex: {
      questions: typeD,
    },
    "Flex Accumulator": {
      questions: typeB,
    },
    "Guarantee-Platinum Series": {
      questions: typeB,
    },
    "Immediate Income": {
      questions: typeB,
    },
    Pathsetter: {
      questions: typeD,
    },
    "Performance Pro": {
      questions: typeB,
    },
    "Power Accumulator 10": {
      questions: typeA,
    },
    "Power Accumulator 7": {
      questions: typeB,
    },
    "Prosperity Elite": {
      questions: typeB,
    },
    "Retirement Pro": {
      questions: typeB,
    },
    "Safe Income Plus": {
      questions: typeB,
    },
  },
  Foresters: {
    "Advantage Plus Fully Underwritten": {
      questions: typeC,
    },
    "Advantage Plus Simplified Issue": {
      questions: typeC,
    },
    "BrightFuture Children's Whole Life": {
      questions: typeA,
    },
    "PlanRight Basic": {
      questions: typeA,
    },
    "PlanRight Preferred": {
      questions: typeA,
    },
    "PlanRight Standard": {
      questions: typeA,
    },
    Prepared: {
      questions: typeA,
    },
    "Smart UL Fully Underwritten": {
      questions: typeA,
    },
    "Smart UL Simplified Issue": {
      questions: typeA,
    },
    "Strong Foundation Simplified": {
      questions: typeA,
    },
    "Your Legacy": {
      questions: typeA,
    },
    "Your Term Medical": {
      questions: typeA,
    },
  },
  Gerber: {
    "Accident Protection": {
      questions: typeA,
    },
    "Gerber Life College Plan": {
      questions: typeB,
    },
    "Grow Up Plan": {
      questions: typeA,
    },
    "Grow Up Plan *Advance Payment*": {
      questions: typeA,
    },
    "Guaranteed Issue Whole Life": {
      questions: typeA,
    },
    "Simplified Senior Life": {
      questions: typeA,
    },
    "Whole Life Age 18-70": {
      questions: typeA,
    },
  },
  GILICO: {
    "Choice 4": {
      questions: typeB,
    },
    "Flex 10": {
      questions: typeB,
    },
    "Flex 12": {
      questions: typeB,
    },
    "Flex 7": {
      questions: typeB,
    },
    "FlexPlus 10": {
      questions: typeB,
    },
    "FlexPlus 5": {
      questions: typeB,
    },
    "FlexPlus 7": {
      questions: typeB,
    },
    "GILICO Form 1046": {
      questions: typeB,
    },
    "GILICO Form 1049": {
      questions: typeB,
    },
    "Guaranty 4": {
      questions: typeB,
    },
    "Guaranty 6": {
      questions: typeB,
    },
    "Guaranty 8": {
      questions: typeB,
    },
    "Guaranty Growth Builder": {
      questions: typeB,
    },
    "Guaranty Growth Plus": {
      questions: typeB,
    },
    "Guaranty Immediate Annuity": {
      questions: typeB,
    },
    "Guaranty Rate Lock": {
      questions: typeB,
    },
    SPDA: {
      questions: typeB,
    },
    "SPDA Deposits": {
      questions: typeB,
    },
    "Security Plus": {
      questions: typeB,
    },
    "Ultra Choice": {
      questions: typeB,
    },
    "Ultra Flex 10": {
      questions: typeB,
    },
    "Ultra Flex 7": {
      questions: typeB,
    },
    WealthChoice: {
      questions: typeB,
    },
  },
  "Government Personnel Mutual": {
    "20 Pay Whole Life": {
      questions: typeA,
    },
    "Equity Protector": {
      questions: typeA,
    },
    "Estate Builder": {
      questions: typeA,
    },
    "Estate Builder for Kids": {
      questions: typeA,
    },
    "Final Expense": {
      questions: typeA,
    },
    "Life Alliance Term": {
      questions: typeA,
    },
    "Life Alliance UL": {
      questions: typeA,
    },
    "Traditional Whole Life": {
      questions: typeA,
    },
  },
  "Great Western": {
    "Day One/Assurance Plus": {
      questions: typeA,
    },
    "Graded Benefit Plan": {
      questions: typeA,
    },
    "Guaranteed Assurance": {
      questions: typeA,
    },
    "The Great Assurance": {
      questions: typeA,
    },
  },
  HMA: {
    "HMA 10000": {
      questions: typeA,
    },
    "HMA 15000": {
      questions: typeA,
    },
    "HMA 20000": {
      questions: typeA,
    },
    "HMA 2500": {
      questions: typeA,
    },
    "HMA 25000": {
      questions: typeA,
    },
    "HMA 30000": {
      questions: typeA,
    },
    "HMA 40000": {
      questions: typeA,
    },
    "HMA 5000": {
      questions: typeA,
    },
    "HMA 50000": {
      questions: typeA,
    },
    "HMA 60000": {
      questions: typeA,
    },
    "HMA 7500": {
      questions: typeA,
    },
  },
  "John Hancock": {
    "Simple Term": {
      questions: typeA,
    },
  },
  "Lafayette Life": {
    "10 Pay Life WL": {
      questions: typeC,
    },
    "Contender WL": {
      questions: typeC,
    },
    "Continental Term Series": {
      questions: typeA,
    },
    "Group Marquis® Fixed Indexed Annuities": {
      questions: typeB,
    },
    "Heritage WL": {
      questions: typeC,
    },
    "Horizon Single Premium Immediate Annuity": {
      questions: typeB,
    },
    "Liberty WL": {
      questions: typeC,
    },
    "Marquis® Centennial Deferred Fixed Indexed Annuity": {
      questions: typeB,
    },
    "Marquis® Single Premium Fixed Indexed Annuity": {
      questions: typeB,
    },
    "Patriot WL": {
      questions: typeC,
    },
    "Protector Simplified WL": {
      questions: typeA,
    },
    "Sentinel WL": {
      questions: typeC,
    },
  },
  "Mutual of Omaha": {
    "Accum UL Fully Underwritten": {
      questions: typeA,
    },
    "Children's Whole Life": {
      questions: typeA,
    },
    "Critical Advantage Cancer": {
      questions: typeA,
    },
    "Critical Advantage Critical": {
      questions: typeA,
    },
    "Critical Advantage Heart Attack & Stroke": {
      questions: typeA,
    },
    "DI Mutual Income Solutions": {
      questions: typeA,
    },
    "Guaranteed Advantage": {
      questions: typeA,
    },
    "Income Advantage IUL": {
      questions: typeA,
    },
    "Indexed UL Express": {
      questions: typeA,
    },
    "Life Protection Advantage IUL": {
      questions: typeA,
    },
    "Living Promise Graded": {
      questions: typeA,
    },
    "Living Promise Level": {
      questions: typeA,
    },
    "Long-Term Care": {
      questions: typeA,
    },
    "Medicare Supplement": {
      questions: typeA,
    },
    "Medicare Supplement Basic": {
      questions: typeA,
    },
    "Medicare Supplement Extended Basic": {
      questions: typeA,
    },
    "Medicare Supplement High Deductible Basic": {
      questions: typeA,
    },
    "Medicare Supplement Plan A": {
      questions: typeA,
    },
    "Medicare Supplement Plan B": {
      questions: typeA,
    },
    "Medicare Supplement Plan C": {
      questions: typeA,
    },
    "Medicare Supplement Plan D": {
      questions: typeA,
    },
    "Medicare Supplement Plan F": {
      questions: typeA,
    },
    "Medicare Supplement Plan G": {
      questions: typeA,
    },
    "Medicare Supplement Plan M": {
      questions: typeA,
    },
    "Medicare Supplement Plan N": {
      questions: typeA,
    },
    "Mutual Dental Preferred": {
      questions: typeA,
    },
    "Mutual Dental Protection": {
      questions: typeA,
    },
    "Term Life Answers Fully Underwritten": {
      questions: typeA,
    },
    "Term Life Express Simplified Issue": {
      questions: typeA,
    },
  },
  "Nassau Re": {
    "Phoenix Personal Income": {
      questions: typeB,
    },
    "Remembrance Life": {
      questions: typeA,
    },
    "Safe Harbor Express": {
      questions: typeA,
    },
    "Safe Harbor Term": {
      questions: typeA,
    },
    "Simplicity IUL Simplified": {
      questions: typeA,
    },
  },
  NLG: {
    BasicSecure: {
      questions: typeA,
    },
    "FIT Certain Income": {
      questions: typeB,
    },
    "FIT Focus Growth": {
      questions: typeB,
    },
    "FIT Focus Income": {
      questions: typeB,
    },
    "FIT Horizon Growth": {
      questions: typeB,
    },
    "FIT Horizon Income": {
      questions: typeB,
    },
    "FIT Rewards Growth Select Income": {
      questions: typeF,
    },
    "FIT Secure Growth": {
      questions: typeF,
    },
    "FIT Select Income": {
      questions: typeF,
    },
    "Flexlife (2019)": {
      questions: typeD,
    },
    "LSW Term": {
      questions: typeA,
    },
    "PeakLife (2019)": {
      questions: typeD,
    },
    "SummitLife IUL": {
      questions: typeD,
    },
    SurvivorLife: {
      questions: typeD,
    },
    "Total Secure": {
      questions: typeA,
    },
  },
  "North American Co": {
    "Charter Plus 10": {
      questions: typeB,
    },
    "Charter Plus 14": {
      questions: typeB,
    },
    "Guarantee Choice 10": {
      questions: typeB,
    },
    "Guarantee Choice 3": {
      questions: typeB,
    },
    "Guarantee Choice 5": {
      questions: typeB,
    },
    "Guarantee Choice 7": {
      questions: typeB,
    },
    "NA Income": {
      questions: typeB,
    },
    "NAC Benefit Solutions 10": {
      questions: typeB,
    },
    "NAC Income Choice 10": {
      questions: typeB,
    },
    "NAC Versa Choice 10": {
      questions: typeB,
    },
    "Performance Choice 8": {
      questions: typeB,
    },
    "Prime Path Pro 10": {
      questions: typeB,
    },
    "Prime Path Pro 12": {
      questions: typeB,
    },
  },
  Occidental: {
    "EZ Term": {
      questions: typeA,
    },
    "Express UL": {
      questions: typeA,
    },
    "Family Choice": {
      questions: typeA,
    },
    "Home Protector": {
      questions: typeA,
    },
    OBA: {
      questions: typeA,
    },
    "Security Protector": {
      questions: typeA,
    },
    "Sr. Choice": {
      questions: typeA,
    },
    "Survivor Protector": {
      questions: typeA,
    },
    "Term Made Simple": {
      questions: typeA,
    },
  },
  Oxford: {
    "Assurance Final Expense": {
      questions: typeA,
    },
    "Assurance One Single Premium": {
      questions: typeA,
    },
    "Prosperity Select Single Premium": {
      questions: typeA,
    },
  },
  "Pet HMA": {
    "PHMA 10000": {
      questions: typeA,
    },
    "PHMA 15000": {
      questions: typeA,
    },
    "PHMA 20000": {
      questions: typeA,
    },
    "PHMA 2500": {
      questions: typeA,
    },
    "PHMA 5000": {
      questions: typeA,
    },
    "PHMA 7500": {
      questions: typeA,
    },
  },
  Prosperity: {
    "Family Freedom Term": {
      questions: typeA,
    },
    "New Vista Final Expense": {
      questions: typeA,
    },
    PrimeTerm: {
      questions: typeA,
    },
  },
  "Royal Neighbors": {
    "Graded Death Benefit": {
      questions: typeA,
    },
    "JETerm Fully Underwritten": {
      questions: typeA,
    },
    "JETerm Simplified Issue": {
      questions: typeA,
    },
    "Simplified Issue": {
      questions: typeA,
    },
  },
  "Security Benefit": {
    "Strategic Growth": {
      questions: typeA,
    },
    "Strategic Growth Plus": {
      questions: typeA,
    },
    "Total Value": {
      questions: typeA,
    },
  },
  "Sentinel Security Life": {
    "Accumulation Protector Plus": {
      questions: typeB,
    },
    "Guaranteed Income Annuity": {
      questions: typeB,
    },
    "Medicare Supplement / Select": {
      questions: typeA,
    },
    "Personal Choice Annuity": {
      questions: typeB,
    },
    "Personal Choice Plus 5 Year Index": {
      questions: typeB,
    },
    "Retirement Plus Multiplier": {
      questions: typeB,
    },
    "Summit Bonus Index": {
      questions: typeB,
    },
  },
  SILAC: {
    "Teton Bonus Series": {
      questions: typeF,
    },
  },
  "Tier One Aflac": {},
  TransAmerica: {
    "Accumulation UL": {
      questions: typeA,
    },
    "Final Expense Solutions": {
      questions: typeA,
    },
    "Financial Foundation": {
      questions: typeA,
    },
    "Lifetime Whole Life": {
      questions: typeA,
    },
    "Trendsetter LB": {
      questions: typeA,
    },
    "Trendsetter Super": {
      questions: typeA,
    },
  },
});
let currentQuestion = ref({
  text: "Agent Full Name",
  heading: "Agent Information",
  type: "text",
  propertyName: "agentName",
  validationMethod: "validateRequiredField",
  required: true,
});

let questions = ref([
  {
    text: "Application Date",
    type: "date",
    propertyName: "applicationDate",
    validationMethod: "validateRequiredField",
    heading: "Policy Information",
    required: true,
  },
  {
    text: "Coverage Amount",
    type: "currency",
    propertyName: "coverageAmount",
    validationMethod: "validatePositiveNumberField",
    heading: "Policy Information",
    required: true,
  },
  {
    text: "Coverage Length",
    heading: "Policy Information",
    type: "select",
    options: [
      "N/A",
      "3 Years",
      "5 Years",
      "5 Years w/ 5 Year Guaranty",
      "5 Years w/ ROP",
      "7 Years",
      "10 Years",
      "15 Years",
      "15 Years w/ ROP",
      "20 Years",
      "20 Years w/ 5 Year Guaranty",
      "20 Years w/ ROP",
      "25 Years",
      "25 Years w/ ROP",
      "30 Years",
      "30 Years w/ 5 Year Guaranty",
      "30 Years w/ ROP",
      "Issue Ages",
    ],
    propertyName: "coverageLength",
    validationMethod: "validateRequiredField",
    required: true,
  },

  {
    text: "Premium Frequency",
    heading: "Policy Information",
    type: "select",
    options: ["Monthly", "Quarterly", "Semi-Annual", "Annual"],
    propertyName: "premiumFrequency",
    validationMethod: "validateRequiredField",
    required: true,
  },

  {
    text: "Premium Amount",
    textMethod: "getAnnualPremiumVolumeText",
    heading: "Policy Information",
    type: "currency",
    propertyName: "premiumAmount",
    validationMethod: "validatePositiveNumberField",
    required: true,
  },
  {
    text: "Annual Premium Volume",
    heading: "Policy Information",
    type: "currency",
    propertyName: "annualPremiumVolume",
    validationMethod: "validatePositiveNumberField",
    required: true,
  },
  {
    text: "Do you have an Equis writing number with this carrier?",
    heading: "Policy Information",
    type: "select",
    options: ["Yes", "No"],
    propertyName: "doYouHaveAnEquisWritingNumberWithThisCarrier",
    validationMethod: "validateOptionalField",
    required: false,
  },
  {
    text: "Carrier Writing Number",
    heading: "Policy Information",
    type: "text",
    propertyName: "carrierWritingNumber",
    validationMethod: "validateOptionalField",
    required: false,
  },
  {
    text: "Was this app from a lead?",
    heading: "Policy Information",
    type: "select",
    options: ["Yes", "No"],
    propertyName: "wasThisAppFromALead",
    validationMethod: "validateRequiredField",
    required: true,
  },
  {
    text: "Source of the lead",
    heading: "Policy Information",
    type: "select",
    options: [
      "Transfer Program",
      "Callback Lead",
      "Internet Leads",
      "Opt Leads",
      "Referral",
    ],
    propertyName: "sourceOfTheLead",
    validationMethod: "validateRequiredField",
    required: true,
  },
  {
    text: "Was this appointment virtual or face-to-face?",
    heading: "Policy Information",
    type: "select",
    options: ["Virtual", "Face to face"],
    propertyName: "wasThisAppointmentVirtualOrFaceToFace",
    validationMethod: "validateRequiredField",
    required: true,
  },
  {
    text: "Annual Target Premium",
    heading: "Policy Information",
    type: "currency",
    propertyName: "annualTargetPremium",
    validationMethod: "validatePositiveNumberField",
    required: true,
  },
  {
    text: "Annual Planned Premium",
    heading: "Policy Information",
    type: "currency",
    propertyName: "annualPlannedPremium",
    validationMethod: "validatePositiveNumberField",
    required: true,
  },
  {
    text: "Annual Excess Premium",
    heading: "Policy Information",
    type: "currency",
    propertyName: "annualExcessPremium",
    validationMethod: "validatePositiveNumberField",
    required: true,
  },
  {
    text: "Initial Investment Amount",
    heading: "Policy Information",
    type: "currency",
    propertyName: "initialInvestmentAmount",
    validationMethod: "validatePositiveNumberField",
    required: true,
  },
  {
    text: "Did another agent refer this application to you?",
    heading: "Policy Information",
    type: "select",
    options: ["Yes", "No"],
    propertyName: "didAnotherAgentReferThisApplicationToYou",
    validationMethod: "validateRequiredField",
    required: true,
  },
  {
    text: "referringAgentEFNumber",
    heading: "Policy Information",
    type: "text",
    propertyName: "referringAgentEFNumber",
    validationMethod: "validateRequiredField",
    required: true,
  },
  {
    text: "Is this AN SDIC?",
    heading: "Policy Information",
    type: "select",
    options: ["Yes", "No"],
    propertyName: "isThisAnSDIC",
    validationMethod: "validateRequiredField",
    required: true,
  },
  {
    text: "Will there be a recurring premium?",
    heading: "Policy Information",
    type: "select",
    options: ["Yes", "No"],
    propertyName: "willThereBeARecurringPremium",
    validationMethod: "validateRequiredField",
    required: true,
  },
  {
    text: "First Name",
    heading: "Client Information",
    type: "text",
    propertyName: "firstName",
    validationMethod: "validateRequiredField",
    required: true,
  },
  {
    text: "MI",
    heading: "Client Information",
    type: "text",
    propertyName: "MI",
    validationMethod: "validateOptionalField",
    required: false,
  },
  {
    text: "Last name",
    heading: "Client Information",
    type: "text",
    propertyName: "lastName",
    validationMethod: "validateRequiredField",
    required: true,
  },
  {
    text: "Date of Birth",
    heading: "Client Information",
    heading: "Client Information",
    type: "date",
    propertyName: "dateOfBirth",
    validationMethod: "validateRequiredField",
    required: true,
  },
  {
    text: "Gender",
    heading: "Client Information",
    type: "select",
    options: ["Male", "Female", "Other"],
    propertyName: "gender",
    validationMethod: "validateRequiredField",
    required: true,
  },
  {
    text: "Street Address 1",
    heading: "Client Contact Information",
    type: "text",
    propertyName: "streetLine1",
    validationMethod: "validateRequiredField",
    required: true,
  },
  {
    text: "Street Address 2",
    heading: "Client Contact Information",
    type: "text",
    propertyName: "streetLine2",
    validationMethod: "validateOptionalField",
    required: false,
  },
  {
    text: "City",
    heading: "Client Contact Information",
    type: "text",
    propertyName: "city",
    validationMethod: "validateRequiredField",
    required: true,
  },
  {
    text: "State",
    heading: "Client Contact Information",
    type: "select",
    options: [
      "Alaska",
      "Alabama",
      "Arkansas",
      "Arizona",
      "California",
      "Colorado",
      "Connecticut",
      "District of Columbia",
      "Delaware",
      "Florida",
      "Georgia",
      "Guam",
      "Hawaii",
      "Iowa",
      "Idaho",
      "Illinois",
      "Indiana",
      "Kansas",
      "Kentucky",
      "Louisiana",
      "Massachusetts",
      "Maryland",
      "Maine",
      "Michigan",
      "Minnesota",
      "Missouri",
      "Mississippi",
      "Montana",
      "North Carolina",
      "North Dakota",
      "Nebraska",
      "New Hampshire",
      "New Jersey",
      "New Mexico",
      "Nevada",
      "New York",
      "Ohio",
      "Oklahoma",
      "Oregon",
      "Pennsylvania",
      "Puerto Rico",
      "Rhode Island",
      "South Carolina",
      "South Dakota",
      "Tennessee",
      "Texas",
      "Utah",
      "Virginia",
      "Virgin Islands",
      "Vermont",
      "Washington",
      "Wisconsin",
      "West Virginia",
      "Wyoming",
    ],
    propertyName: "state",
    validationMethod: "validateRequiredField",
    required: true,
  },
  {
    text: "Zip-code",
    heading: "Client Contact Information",
    type: "text",
    propertyName: "zipcode",
    validationMethod: "validateZipCodeField",
    required: true,
  },
  {
    text: "Client Phone Number",
    heading: "Client Contact Information",
    type: "text",
    propertyName: "phoneNumber",
    validationMethod: "validatePhoneNumberField",
    required: true,
  },
  {
    text: "Client Email",
    heading: "Client Contact Information",
    type: "text",
    propertyName: "email",
    validationMethod: "validateEmailField",
    required: true,
  },

  {
    text: "Agent Full Name",
    heading: "Agent Information",
    type: "text",
    propertyName: "agentName",
    validationMethod: "validateRequiredField",
    required: true,
  },

  {
    text: "Agent Email",
    heading: "Agent Information",
    type: "text",
    propertyName: "agentEmail",
    validationMethod: "validateEmailField",
    required: true,
  },

  {
    text: "Enter Your FULL EF Number. EXAMPLE: EF123456",
    heading: "Agent Information",
    type: "text",
    propertyName: "EFNumber",
    validationMethod: "validateRequiredField",
    required: true,
  },

  {
    text: "Select Your Upline Manager",
    heading: "Agent Information",
    type: "select",
    options: [
      "Aurora Financial - Vincent Hall ",
      "Imperial Group - Ruben Basurto",
      "1st Choice Agency - Paul & Lauren Vassallo",
      "Advisor Guild - David Richter",
      "Peace Financial - Timothy Charpentier",
      "Straus Agency - Cynthia Straus",
    ],
    propertyName: "uplineManager",
    validationMethod: "validateRequiredField",
    required: true,
  },

  {
    text: "Was this a split sale?",
    heading: "Agent Information",
    type: "select",
    options: ["Yes", "No"],
    propertyName: "wasThisASplitSale",
    validationMethod: "validateRequiredField",
    required: true,
  },

  {
    text: "Choose Type of Split Sale",
    heading: "Agent Information",
    type: "select",
    options: ["Transfer Program", "Retirement Specialist", "Other"],
    propertyName: "choose",
    validationMethod: "validateRequiredField",
    required: true,
  },

  {
    text: "Split Agent Email",
    heading: "Agent Information",
    type: "text",
    propertyName: "splitAgentEmail",
    validationMethod: "validateEmailField",
    required: true,
  },

  {
    text: "Insurance Company",
    heading: "Policy Information",
    type: "dynamicSelect",
    propertyName: "insuranceCompany",
    dynamicOptionsMethod: "getInsuranceCompanyOptions",
    validationMethod: "validateRequiredField",
    required: true,
  },

  {
    text: "Product Name",
    heading: "Policy Information",
    type: "dynamicSelect",
    propertyName: "productName",
    dynamicOptionsMethod: "getProductNameOptions",
    validationMethod: "validateRequiredField",
    required: true,
  },

  {
    text: "What Is the Policy Draft Date?",
    heading: "Policy Information",
    type: "date",
    propertyName: "whatIsThePolicyDraftDate",
    validationMethod: "validateRequiredField",
    required: true,
  },

  {
    text: "Address Fields",
    text: "Client Information",
    type: "collection",
    questions: [
      {
        text: "Street Address 1",
        heading: "Client Contact Information",
        type: "text",
        propertyName: "streetLine1",
        validationMethod: "validateStreetAddress",
        required: true,
      },
      {
        text: "Street Address 2",
        heading: "Client Contact Information",
        type: "text",
        propertyName: "streetLine2",
        validationMethod: "validateOptionalField",
        required: false,
      },
      {
        text: "City",
        heading: "Client Contact Information",
        type: "text",
        propertyName: "city",
        validationMethod: "validateCity",
        required: true,
      },
      {
        text: "State",
        heading: "Client Contact Information",
        type: "select",
        options: [
          "Alaska",
          "Alabama",
          "Arkansas",
          "Arizona",
          "California",
          "Colorado",
          "Connecticut",
          "District of Columbia",
          "Delaware",
          "Florida",
          "Georgia",
          "Guam",
          "Hawaii",
          "Iowa",
          "Idaho",
          "Illinois",
          "Indiana",
          "Kansas",
          "Kentucky",
          "Louisiana",
          "Massachusetts",
          "Maryland",
          "Maine",
          "Michigan",
          "Minnesota",
          "Missouri",
          "Mississippi",
          "Montana",
          "North Carolina",
          "North Dakota",
          "Nebraska",
          "New Hampshire",
          "New Jersey",
          "New Mexico",
          "Nevada",
          "New York",
          "Ohio",
          "Oklahoma",
          "Oregon",
          "Pennsylvania",
          "Puerto Rico",
          "Rhode Island",
          "South Carolina",
          "South Dakota",
          "Tennessee",
          "Texas",
          "Utah",
          "Virginia",
          "Virgin Islands",
          "Vermont",
          "Washington",
          "Wisconsin",
          "West Virginia",
          "Wyoming",
        ],
        propertyName: "state",
        validationMethod: "validateState",
        required: true,
      },
      {
        text: "Zip-code",
        heading: "Client Contact Information",
        type: "text",
        propertyName: "zipcode",
        validationMethod: "validateZipCodeField",
        required: true,
      },
    ],
  },
]);

let uplineManagerArray = ref([
  { text: "Aurora Financial - Vincent Hall" },
  { text: "Imperial Group - Ruben Basurto" },
  { text: "1st Choice Agency - Paul & Lauren Vassallo" },
  { text: "Advisor Guild - David Richter" },
  { text: "Peace Financial - Timothy Charpentier" },
  { text: "Straus Agency - Cynthia Straus" },
]);
let coverageLengthArray = ref([
  "N/A",
  "3 Years",
  "5 Years",
  "5 Years w/ 5 Year Guaranty",
  "5 Years w/ ROP",
  "7 Years",
  "10 Years",
  "15 Years",
  "15 Years w/ ROP",
  "20 Years",
  "20 Years w/ 5 Year Guaranty",
  "20 Years w/ ROP",
  "25 Years",
  "25 Years w/ ROP",
  "30 Years",
  "30 Years w/ 5 Year Guaranty",
  "30 Years w/ ROP",
  "Issue Ages",
])
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
    "agent_full_name", 'agent_email', "ef_number", "upline_manager", 'split_sale',
    "split_agent_email", "insurance_company", "product_name", "application_date", "coverage_amount", "coverage_length",
    "premium_frequency", 'premium_amount', 'premium_volumn', "equis_writing_number_carrier",
    "this_app_from_lead", "appointment_type", "policy_draft_date", "first_name", "mi",
    "annual_target_premium", "annual_planned_premium", "annual_excess_premium", "intial_investment_amount",
    "refer_another_agent", "this_an_sdic", "recurring_premium", "last_name", "dob", "gender", "client_street_address_1",
    "client_street_address_2", "client_city", "client_state", "client_zipcode", "client_phone_no", "client_email",
  ]
  const addFieldIfPresent = (field, condition, value) => {
    if (condition) {
      requiredFields = [...new Set([...requiredFields, value])];
    }
  };
  addFieldIfPresent('split_sale_type', form.value.split_sale === "YES", 'split_sale_type');
  addFieldIfPresent('carrier_writing_number', form.value.equis_writing_number_carrier === "YES", 'carrier_writing_number');
  addFieldIfPresent('source_of_lead', form.value.this_app_from_lead === "YES", 'source_of_lead');


  requiredFields.forEach(fieldName => {
    if (!form.value[fieldName] || form.value[fieldName] === "Select") {
      firstStepErrors.value[fieldName] = [`This  field is required.`];
    }
  });

  let emails = ['agent_email', 'split_agent_email', 'client_email']
  emails.forEach(fieldName => {
    if (!isValidEmail(form.value[fieldName])) {
      firstStepErrors.value[fieldName] = ["Invalid email format"];
    }
  });
  let phones = ['client_phone_no']
  phones.forEach(fieldName => {
    if (form.value[fieldName].length < 10) {
      firstStepErrors.value[fieldName] = ["Please enter a valid phone number."];
    }
  });
  let numericFields = ['coverage_amount', 'premium_amount',  'premium_volumn', 'annual_target_premium', 'annual_planned_premium',  'annual_excess_premium', 'intial_investment_amount'];

numericFields.forEach(fieldName => {
  const fieldValue = form.value[fieldName];

  // Check if the field is not a number
  if (isNaN(fieldValue)) {
    firstStepErrors.value[fieldName] = ['Please enter a valid number.'];
  }
});

  const hasErrors = Object.values(firstStepErrors.value).some(errors => errors.length > 0);

  if (hasErrors) {
    var element = document.getElementById("modal_main_id");
    element.scrollIntoView();
  } else {
    step.value = 2;
  }


}


let ChangeProducName = () => {
  form.value.product_name = "Select";
};
let getInsuranceCompanyOptions = () => {
  return Object.keys(companies.value);
};
let getProductNameOptions = () => {
  return Object.keys(companies.value[form.value.insurance_company]);
};

const Next = (data) => {
  checkRequiredField()
  var element = document.getElementById("modal_main_id");
  element.scrollIntoView();
};
let Previous = (data) => {
  if (data === 7 && form.value.split_sale === "NO") {
    step.value = 5
    return
  }
  if (data === 18 && form.value.equis_writing_number_carrier === "NO") {
    step.value = 16
    return
  }
  if (data === 20 && form.value.this_app_from_lead === "NO") {
    step.value = 18
    return
  }
  step.value -= 1;
};

// save business data start 
let isLoading = ref(false)
let SaveBussinessData = async () => {
  isLoading.value = true
  await axios.post('/internal-agent/my-business', form.value)
    .then((response) => {
      toaster("success", response.data.message);
      router.visit('/internal-agent/my-business')
    }).catch((error) => {
      console.log('error', error);
      isLoading.value = false
      firstStepErrors.value = error.response.data.errors
      console.log('firstStepErrors.value', firstStepErrors.value);
      console.log('error.response.data.errors', error.response.data.errors);
      if (firstStepErrors.value) {
        step.value = 1
        var element = document.getElementById("modal_main_id");
        element.scrollIntoView();
      }
    })
}
// save business data end 
let close = () => {
  emits("close");
};
let StateChange = (state) => {
  console.log('');
}
let maxDate = ref(new Date)
maxDate.value.setHours(23, 59, 59, 999);

let enforceFiveDigitInput = (fieldName, val) => {
  form.value[val] = fieldName.replace(/[^0-9]/g, '');
  if (fieldName.length > 5) {
    form.value[val] = fieldName.slice(0, 5);
  }
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
  <AuthenticatedLayout>
    <Transition name="modal" enter-active-class="transition ease-out  duration-300 transform"
      enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
      enter-to-class="opacity-100 translate-y-0 sm:scale-100"
      leave-active-class="transition ease-in duration-200 transform"
      leave-from-class="opacity-100 translate-y-0 sm:scale-100"
      leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

      <div id="defaultModal" v-show="addBusinessModal" tabindex="-1"
        class="flex  items-center justify-center fixed inset-0 z-50 w-full h-full overflow-x-hidden overflow-y-auto max-h-full mx-4 sm:mx-0">

        <div class="fixed inset-0 bg-black opacity-90 blurred-overlay"></div>

        <!-- This is the overlay -->
        <div style="width:80%;height:90%;" class="relative " id="modal_main_id">
          <div class="relative bg-white rounded-lg shadow-lg transition-all">
            <div class="flex justify-end">
              <button @click="close" type="button"
                class="text-gray-400 bg-transparent mr-2 mt-2 hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center"
                data-modal-hide="defaultModal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                  viewBox="0 0 14 14">
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
                      <h1 style="background-color: #134576;" class="mb-4	text-center rounded-md py-2 text-white">
                        Agent Information
                      </h1>
                      <div class="grid xl:grid-cols-3 lg:grid-cols-2 md:grid-cols-1 sm:grid-cols-1 gap-x-8">
                        <div>
                          <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">Full Name<span
                              class="text-red-400">*</span></label>
                          <input v-model="form.agent_full_name" type="text" id="full name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="" required />
                          <div v-if="firstStepErrors.agent_full_name" class="text-red-500"
                            v-text="firstStepErrors.agent_full_name[0]">
                          </div>
                          <div v-if="firstStepErrors.business_name" class="text-red-500"
                            v-text="firstStepErrors.business_name[0]">
                          </div>
                        </div>
                        <div>
                          <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">Agent
                            Email<span class="text-red-400">*</span></label>
                          <input v-model="form.agent_email" type="text" id="Email"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="" required />
                          <div v-if="firstStepErrors.agent_email" class="text-red-500"
                            v-text="firstStepErrors.agent_email[0]">
                          </div>
                        </div>
                      </div>

                      <h1 style="background-color: #134576;" class="my-5	text-center rounded-md py-2 text-white">
                        Policy Information
                      </h1>

                      <div class="grid xl:grid-cols-3 lg:grid-cols-2 md:grid-cols-1 sm:grid-cols-1 gap-x-8">
                        <div>
                          <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">Insurance
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
                          <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">Product
                            Name<span class="text-red-400">*</span></label>
                          <select v-model="form.product_name" id="countries"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option disabled>Select</option>
                            <option v-for="option in getProductNameOptions()" :value="option" v-text="option"></option>
                          </select>
                          <div v-if="firstStepErrors.product_name" class="text-red-500"
                            v-text="firstStepErrors.product_name[0]">
                          </div>
                        </div>

                        <div>
                          <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">Application
                            Date<span class="text-red-400">*</span></label>
                          <VueDatePicker v-model="form.application_date" format="dd-MMM-yyyy" :maxDate="maxDate"
                            auto-apply>
                          </VueDatePicker>
                          <div v-if="firstStepErrors.application_date" class="text-red-500"
                            v-text="firstStepErrors.application_date[0]"></div>
                        </div>

                        <div>
                          <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">Coverage
                            Amount<span class="text-red-400">*</span></label>
                          <input v-model="form.coverage_amount" onkeyup="this.value=this.value.replace(/[^0-9]/g,'')"
                            type="text" id="text"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="$ 0.00" required />
                          <div v-if="firstStepErrors.coverage_amount" class="text-red-500"
                            v-text="firstStepErrors.coverage_amount[0]"></div>
                        </div>

                        <div>
                          <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">Coverage
                            Length<span class="text-red-400">*</span></label>
                          <select v-model="form.coverage_length" id="countries"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option disabled>Select</option>
                            <option v-for="option in coverageLengthArray" :value="option" v-text="option"></option>
                          </select>
                          <div v-if="firstStepErrors.coverage_length" class="text-red-500"
                            v-text="firstStepErrors.coverage_length[0]">
                          </div>
                        </div>



                        <div>
                          <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">Premium
                            Frequency<span class="text-red-400">*</span></label>
                          <select v-model="form.premium_frequency" id="countries"
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
                          <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">{{
                            form.premium_frequency != 'Select' ? form.premium_frequency : '' }} Premium Amount<span
                              class="text-red-400">*</span></label>
                          <input v-model="form.premium_amount" onkeyup="this.value=this.value.replace(/[^0-9]/g,'')"
                            type="text" id="text"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="$ 0.00" required />
                          <div v-if="firstStepErrors.premium_amount" class="text-red-500"
                            v-text="firstStepErrors.premium_amount[0]"></div>
                        </div>


                        <div>
                          <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">Annual Premium
                            Volume<span class="text-red-400">*</span></label>
                          <input v-model="form.premium_volumn" onkeyup="this.value=this.value.replace(/[^0-9]/g,'')"
                            type="text" id="text"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="$ 0.00" required />
                          <div v-if="firstStepErrors.premium_volumn" class="text-red-500"
                            v-text="firstStepErrors.premium_volumn[0]"></div>
                        </div>

                        <div>
                          <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">Do you have an
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
                        </div>

                        <div>
                          <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">Carrier Writing
                            Number<span class="text-red-400">*</span></label>
                          <input v-model="form.carrier_writing_number" type="text" id="carrier_writing_number"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="" required />
                          <div v-if="firstStepErrors.carrier_writing_number" class="text-red-500"
                            v-text="firstStepErrors.carrier_writing_number[0]"></div>
                        </div>

                        <div>
                          <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">Was this app
                            from a
                            lead?<span class="text-red-400">*</span></label>
                          <select v-model="form.this_app_from_lead" id="countries"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option disabled>Select</option>
                            <option value="YES">YES</option>
                            <option value="NO">NO</option>
                          </select>
                          <div v-if="firstStepErrors.this_app_from_lead" class="text-red-500"
                            v-text="firstStepErrors.this_app_from_lead[0]"></div>
                        </div>

                        <div>
                          <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">Source of the
                            lead<span class="text-red-400">*</span></label>
                          <input v-model="form.source_of_lead" type="text" id="source_of_lead"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="" required />
                          <div v-if="firstStepErrors.source_of_lead" class="text-red-500"
                            v-text="firstStepErrors.source_of_lead[0]"></div>
                        </div>
                      </div>

                      <h1 style="background-color: #134576;" class="my-5	text-center rounded-md py-2 text-white">
                        Application Type
                      </h1>
                      <div class="grid xl:grid-cols-3 lg:grid-cols-2 md:grid-cols-1 sm:grid-cols-1 gap-x-8">
                        <div>
                          <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">Enter Your FULL
                            EF
                            Number. EXAMPLE: EF123456<span class="text-red-400">*</span></label>
                          <input v-model="form.ef_number" type="text" id="ef_number"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="" required />
                          <div v-if="firstStepErrors.ef_number" class="text-red-500"
                            v-text="firstStepErrors.ef_number[0]">
                          </div>
                        </div>

                        <div>
                          <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">Select Your
                            Upline
                            Manager<span class="text-red-400">*</span></label>
                          <select v-model="form.upline_manager" id="countries"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option disabled>Select</option>
                            <option v-for="uplineManager in uplineManagerArray" :value="uplineManager.text">
                              {{ uplineManager.text }}
                            </option>
                          </select>

                          <div v-if="firstStepErrors.upline_manager" class="text-red-500"
                            v-text="firstStepErrors.upline_manager[0]"></div>
                        </div>

                        <div>
                          <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">Was this a
                            split
                            sale?<span class="text-red-400">*</span></label>
                          <select v-model="form.split_sale" id="countries"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option disabled>Select</option>
                            <option value="YES">YES</option>
                            <option value="NO">NO</option>
                          </select>
                          <div v-if="firstStepErrors.split_sale" class="text-red-500"
                            v-text="firstStepErrors.split_sale[0]">
                          </div>
                        </div>

                        <div>
                          <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">Choose Type of
                            Split
                            Sale<span class="text-red-400">*</span></label>
                          <select v-model="form.split_sale_type" id="countries"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option disabled>Select</option>
                            <option value="Transfer Program">Transfer Program</option>
                            <option value="Callback Lead">Callback Lead</option>
                            <option value="Internet Leads">Internet Leads</option>
                            <option value="Opt Leads">Opt Leads</option>
                            <option value="Referral">Referral</option>
                          </select>
                          <div v-if="firstStepErrors.split_sale_type" class="text-red-500"
                            v-text="firstStepErrors.split_sale_type[0]"></div>
                        </div>

                        <div>
                          <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">Split Agent
                            Email<span class="text-red-400">*</span></label>
                          <input v-model="form.split_agent_email" type="text" id="Email"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="" required />
                          <div v-if="firstStepErrors.split_agent_email" class="text-red-500"
                            v-text="firstStepErrors.split_agent_email[0]">
                          </div>
                        </div>
                        <div>
                          <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">Was this
                            appointment
                            virtual or face-to-face?<span class="text-red-400">*</span></label>
                          <select v-model="form.appointment_type" id="countries"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option disabled>Select</option>
                            <option value="Virtual">Virtual</option>
                            <option value="Face to face">Face to face</option>
                          </select>
                          <div v-if="firstStepErrors.appointment_type" class="text-red-500"
                            v-text="firstStepErrors.appointment_type[0]"></div>
                        </div>

                        <div>
                          <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">What Is the
                            Policy
                            Draft Date?<span class="text-red-400">*</span></label>
                          <VueDatePicker v-model="form.policy_draft_date" format="dd-MMM-yyyy" :maxDate="maxDate"
                            auto-apply>
                          </VueDatePicker>
                          <div v-if="firstStepErrors.policy_draft_date" class="text-red-500"
                            v-text="firstStepErrors.policy_draft_date[0]"></div>
                        </div>



                        <div>
                          <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">MI<span
                              class="text-red-400">*</span></label>
                          <input v-model="form.mi" type="text" id="mi"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="" required />
                          <div v-if="firstStepErrors.mi" class="text-red-500" v-text="firstStepErrors.mi[0]"></div>
                        </div>

                        <div>
                          <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">Annual Target
                            Premium<span class="text-red-400">*</span></label>
                          <input v-model="form.annual_target_premium"
                            onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" type="text" id="text"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="$ 0.00" required />
                          <div v-if="firstStepErrors.annual_target_premium" class="text-red-500"
                            v-text="firstStepErrors.annual_target_premium[0]"></div>
                        </div>

                        <div>
                          <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">Annual Planned
                            Premium<span class="text-red-400">*</span></label>
                          <input v-model="form.annual_planned_premium"
                            onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" type="text" id="text"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="$ 0.00" required />
                          <div v-if="firstStepErrors.annual_planned_premium" class="text-red-500"
                            v-text="firstStepErrors.annual_planned_premium[0]"></div>
                        </div>

                        <div>
                          <label for="EFNumber"
                            class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">Annual
                            Excess Premium<span class="text-red-400">*</span></label>
                          <input v-model="form.annual_excess_premium"
                            onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" type="text" id="text"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="$ 0.00" required />
                          <div v-if="firstStepErrors.annual_excess_premium" class="text-red-500"
                            v-text="firstStepErrors.annual_excess_premium[0]"></div>
                        </div>

                        <div>
                          <label for="EFNumber"
                            class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">Initial
                            Investment Amount<span class="text-red-400">*</span></label>

                          <input v-model="form.intial_investment_amount"
                            onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" type="text" id="text"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="$ 0.00" required />
                          <div v-if="firstStepErrors.intial_investment_amount" class="text-red-500"
                            v-text="firstStepErrors.intial_investment_amount[0]"></div>
                        </div>


                        <div>

                          <label for="EFNumber"
                            class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">Did
                            another agent refer this application to you?<span class="text-red-400">*</span></label>

                          <select v-model="form.refer_another_agent" id="countries"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option disabled>Select</option>
                            <option value="YES">YES</option>
                            <option value="No">No</option>
                          </select>
                          <div v-if="firstStepErrors.refer_another_agent" class="text-red-500"
                            v-text="firstStepErrors.refer_another_agent[0]"></div>
                        </div>

                        <div>

                          <label for="EFNumber"
                            class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">Is
                            this
                            AN SDIC?<span class="text-red-400">*</span></label>

                          <select v-model="form.this_an_sdic"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option disabled>Select</option>
                            <option value="YES">YES</option>
                            <option value="No">No</option>
                          </select>
                          <div v-if="firstStepErrors.this_an_sdic" class="text-red-500"
                            v-text="firstStepErrors.this_an_sdic[0]">
                          </div>
                        </div>


                        <div>

                          <label for="EFNumber"
                            class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">Will
                            there be a recurring premium?<span class="text-red-400">*</span></label>

                          <select v-model="form.recurring_premium"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option disabled>Select</option>
                            <option value="YES">YES</option>
                            <option value="No">No</option>
                          </select>
                          <div v-if="firstStepErrors.recurring_premium" class="text-red-500"
                            v-text="firstStepErrors.recurring_premium[0]"></div>
                        </div>



                        <div>
                          <label for="EFNumber"
                            class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">Date of
                            Birth<span class="text-red-400">*</span></label>

                          <VueDatePicker v-model="form.dob" format="dd-MMM-yyyy" :maxDate="maxDate" auto-apply>
                          </VueDatePicker>
                          <div v-if="firstStepErrors.dob" class="text-red-500" v-text="firstStepErrors.dob[0]"></div>
                        </div>
                        <div>
                          <label for="EFNumber"
                            class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">Gender<span
                              class="text-red-400">*</span></label>

                          <select v-model="form.gender" id="countries"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option disabled>Select</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                          </select>
                          <div v-if="firstStepErrors.gender" class="text-red-500" v-text="firstStepErrors.gender[0]">
                          </div>
                        </div>
                      </div>






                      <h1 style="background-color: #134576;" class="my-5	text-center rounded-md py-2 text-white">
                        Client Information
                      </h1>

                      <div class="grid xl:grid-cols-3 lg:grid-cols-2 md:grid-cols-1 sm:grid-cols-1 gap-x-8">

                        <div>
                          <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">First Name<span
                              class="text-red-400">*</span></label>
                          <input v-model="form.first_name" type="text" id="first_name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="" required />
                          <div v-if="firstStepErrors.first_name" class="text-red-500"
                            v-text="firstStepErrors.first_name[0]">
                          </div>
                        </div>

                        <div>
                          <label class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">Last Name<span
                              class="text-red-400">*</span></label>
                          <input v-model="form.last_name" type="text" id="last_name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="" required />
                          <div v-if="firstStepErrors.last_name" class="text-red-500"
                            v-text="firstStepErrors.last_name[0]">
                          </div>
                        </div>

                        <div>
                          <label for="EFNumber"
                            class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">Street
                            Address 1<span class="text-red-400">*</span></label>
                          <input v-model="form.client_street_address_1" type="text" id="client_street_address_1"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="" required />
                          <div v-if="firstStepErrors.client_street_address_1" class="text-red-500"
                            v-text="firstStepErrors.client_street_address_1[0]"></div>
                        </div>

                        <div>
                          <label for="EFNumber"
                            class="block mb-2 mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">Street
                            Address 2<span class="text-red-400">*</span></label>
                          <input v-model="form.client_street_address_2" type="text" id="client_street_address_2"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="" required />
                          <div v-if="firstStepErrors.client_street_address_2" class="text-red-500"
                            v-text="firstStepErrors.client_street_address_2[0]"></div>
                        </div>

                        <div>
                          <label for="EFNumber"
                            class="block mb-2 mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">City<span
                              class="text-red-400">*</span></label>
                          <input v-model="form.client_city" type="text" id="client_city"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="" required />
                          <div v-if="firstStepErrors.client_city" class="text-red-500"
                            v-text="firstStepErrors.client_city[0]">
                          </div>
                        </div>

                        <div>

                          <label for="EFNumber"
                            class="block mb-2 mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">State<span
                              class="text-red-400">*</span></label>
                          <select v-model="form.client_state" @change="StateChange(state)" id="countries"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option disabled>Select</option>
                            <option v-for="state in states" :value="state.id">{{ state.full_name }}</option>
                          </select>
                          <div v-if="firstStepErrors.client_state" class="text-red-500"
                            v-text="firstStepErrors.client_state[0]">
                          </div>
                        </div>
                        <div>

                          <label for=""
                            class="block mb-2 mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">Zip-code<span
                              class="text-red-400">*</span></label>
                          <input v-model="form.client_zipcode"
                            @input="enforceFiveDigitInput(form.client_zipcode, 'client_zipcode')" type="text"
                            id="client_zipcode"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="" required />
                          <div v-if="firstStepErrors.client_zipcode" class="text-red-500"
                            v-text="firstStepErrors.client_zipcode[0]"></div>
                        </div>
                        <div>

                          <label for="EFNumber"
                            class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">Client
                            Phone Number<span class="text-red-400">*</span></label>

                          <input v-model="form.client_phone_no" type="text" maxLength="15"
                            onkeyup="this.value=this.value.replace(/[^0-9]/g,'')"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="" required />
                          <div v-if="firstStepErrors.client_phone_no" class="text-red-500"
                            v-text="firstStepErrors.client_phone_no[0]"></div>
                        </div>

                        <div>

                          <label for="EFNumber"
                            class="block mt-5 text-sm mb-2 font-medium text-gray-900 dark:text-white">Client
                            Email<span class="text-red-400">*</span></label>
                          <input v-model="form.client_email" type="text" id="client_email"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="" required />
                          <div v-if="firstStepErrors.client_email" class="text-red-500"
                            v-text="firstStepErrors.client_email[0]">
                          </div>
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
                      <button v-if="step === 2" :class="{ 'opacity-25': isLoading === true }"
                        :disabled="isLoading == true" @click="SaveBussinessData()"
                        class="button-custom px-3 py-2 rounded-md flex items-center" href="#">
                        <global-spinner :spinner="isLoading" /> Confirm
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
  </AuthenticatedLayout>
</template>
