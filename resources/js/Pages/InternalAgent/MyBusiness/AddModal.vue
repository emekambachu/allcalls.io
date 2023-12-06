<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed } from "vue";
import GuestTextInput from "@/Components/GuestTextInput.vue";
import GuestInputLabel from "@/Components/GuestInputLabel.vue";
let emits = defineEmits()
let props = defineProps({
    addBusinessModal: Boolean,
    firstStepErrors: Object,
});
let loading = ref(false)
let showConfirmationWindow = ref(false)
let index = ref(0)
let step = ref(8)

let typeA = [31, 32, 33, 34, 35, 36, 37, 38, 39, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 40, 19, 20, 21, 22, 23, 41, 29, 30];
let typeB = [31, 32, 33, 34, 35, 36, 37, 38, 39, 0, 2, 14, 6, 7, 8, 9, 10, 15, 16, 40, 19, 20, 21, 22, 23, 41, 29, 30];
let typeC = [31, 32, 33, 34, 35, 36, 37, 38, 39, 0, 1, 2, 17, 3, 4, 5, 6, 7, 8, 9, 10, 15, 16, 40, 19, 20, 21, 22, 23, 41, 29, 30];
let typeD = [31, 32, 33, 34, 35, 36, 37, 38, 39, 0, 1, 2, 11, 12, 13, 6, 7, 8, 9, 10, 15, 16, 40, 19, 20, 21, 22, 23, 41, 29, 30];
let typeE = [31, 32, 33, 34, 35, 36, 37, 38, 39, 0, 1, 2, 11, 12, 13, 6, 7, 8, 9, 10, 40, 19, 31, 20, 21, 22, 23, 41, 29, 30];
let typeF = [31, 32, 33, 34, 35, 36, 37, 38, 39, 0, 2, 14, 18, 6, 7, 8, 9, 10, 15, 16, 40, 19, 31, 20, 21, 22, 23, 41, 29, 30];

let form = ref({
    full_name: '',
    agentEmail: '',
    EFNumber: '',
    uplineManager: 'Select',
    wasThisASplitSale: 'Select',
    typeOfSplitSale: 'Select',
    insuranceCompany: 'AETNA/CVS',
    productName: 'Select',
    applicationDate: '',
    coverageAmount: 0,
    coverageLength: '',
    premiumFrequency: '',
    premiumAmount: 0,
    annualPremiumVolume: 0,
    doYouHaveAnEquisWritingNumberWithThisCarrier: '',
    carrierWritingNumber: '',
    wasThisAppFromALead: '',
    sourceOfTheLead: '',
    wasThisAppointmentVirtualOrFaceToFace: '',
    whatIsThePolicyDraftDate: '',
    firstName: '',
    MI: '',
    lastName: '',
    dateOfBirth: '',
    gender: '',
    streetLine1: '',
    streetLine2: '',
    city: '',
    state: '',
    zipcode: '',
    phoneNumber: '',
    client_email: '',


    splitAgentEmail: '',
    annualTargetPremium: 0,
    annualPlannedPremium: 0,
    annualExcessPremium: 0,
    initialInvestmentAmount: 0,
    didAnotherAgentReferThisApplicationToYou: '',
    referringAgentEFNumber: '',
    isThisAnSDIC: '',
    willThereBeARecurringPremium: '',
    agentName: '',
    choose: '',

});
let companies = ref({
    "AETNA/CVS": {
        "Individual Whole Life": {
            "questions": typeA
        }
    },
    "AIG": {
        "GIWL": {
            "questions": typeA
        },
        "Power 10 Protector": {
            "questions": typeB
        },
        "Power 7 Protector": {
            "questions": typeB
        },
        "SimpliNow Legacy": {
            "questions": typeA
        },
    },
    "American Amicable": {
        "Easy Term": {
            "questions": typeA
        },
        "Express UL": {
            "questions": typeA
        },
        "Family Choice": {
            "questions": typeA
        },
        "Home Protector": {
            "questions": typeA
        },
        "OBA": {
            "questions": typeA
        },
        "Security Protector": {
            "questions": typeA
        },
        "Senior Choice": {
            "questions": typeA
        },
        "Survivor Protector": {
            "questions": typeA
        },
        "Term Made Simple": {
            "questions": typeA
        },
    },
    "American Equity": {
        "AssetShield 10 with Enhancements": {
            "questions": typeB
        },
        "AssetShield 5 with Enhancements": {
            "questions": typeB
        },
        "AssetShield 7 with Enhancements": {
            "questions": typeB
        },
        "Bonus Gold": {
            "questions": typeB
        },
        "CA AssetShield 5": {
            "questions": typeB
        },
        "CA AssetShield 9": {
            "questions": typeB
        },
        "CA Destinations 9": {
            "questions": typeB
        },
        "CA IncomeShield 7": {
            "questions": typeB
        },
        "CA IncomeShield 9 With LIBR": {
            "questions": typeB
        },
        "CA IncomeShield 9 Without LIBR": {
            "questions": typeB
        },
        "EstateShield 10": {
            "questions": typeB
        },
        "FL Retirement Gold": {
            "questions": typeB
        },
        "FlexShield 10": {
            "questions": typeB
        },
        "Guarantee 6": {
            "questions": typeB
        },
        "Guarantee Series (5,6,7)": {
            "questions": typeB
        },
        "GuaranteeShield 3": {
            "questions": typeB
        },
        "GuaranteeShield 5": {
            "questions": typeB
        },
        "Immediate Annuity": {
            "questions": typeB
        },
        "IncomeSheild 10 With LIBR": {
            "questions": typeB
        },
        "IncomeShield 10 Without LIBR": {
            "questions": typeB
        },
        "IncomeShield 7": {
            "questions": typeB
        },
        "Retirement Gold": {
            "questions": typeB
        },
    },
    "Americo": {
        "Advantage Whole Life": {
            "questions": typeA
        },
        "Continuation": {
            "questions": typeA
        },
        "Eagle Guaranteed Series": {
            "questions": typeA
        },
        "Eagle Premier Series": {
            "questions": typeA
        },
        "Payment Protector Continuation": {
            "questions": typeA
        },
        "Term /ADB": {
            "questions": typeA
        },
        "Term 100": {
            "questions": typeA
        },
        "Term 125": {
            "questions": typeA
        },
        "Term CBO-100": {
            "questions": typeA
        },
        "Term CBO-50": {
            "questions": typeA
        },
        "Term Payment Protector": {
            "questions": typeA
        },
    },
    "Ameritas": {
        "Access Whole Life": {
            "questions": typeC
        },
        "Access Whole Life Simplified": {
            "questions": typeC
        },
        "Compass Flexible Premium": {
            "questions": typeB
        },
        "Excel Essential": {
            "questions": typeD
        },
        "FLX Living Benefits IUL": {
            "questions": typeD
        },
        "Growth 10 Pay Whole Life": {
            "questions": typeC
        },
        "Growth Index UL": {
            "questions": typeC
        },
        "Growth Whole Life": {
            "questions": typeC
        },
        "Value Plus Index UL": {
            "questions": typeC
        },
        "Value Plus Survivor IUL": {
            "questions": typeC
        },
        "Value Plus Term": {
            "questions": typeA
        },
        "Value Plus UL": {
            "questions": typeC
        },
        "Value Plus Whole Life": {
            "questions": typeC
        },
        "Value Plus Whole Life Under 25K": {
            "questions": typeC
        },
    },
    "Assurant Life": {
        "Level Benefit Whole Life": {
            "questions": typeA
        },
        "Modified Benefit Whole Life": {
            "questions": typeA
        },
    },
    "Athene": {
        "Agility 10": {
            "questions": typeB
        },
        "Agility 7": {
            "questions": typeB
        },
        "Ascent Series": {
            "questions": typeB
        },
        "Max Rate 3": {
            "questions": typeB
        },
        "Max Rate 5": {
            "questions": typeB
        },
        "Max Rate 7": {
            "questions": typeB
        },
        "Performance Elite 10": {
            "questions": typeB
        },
        "Performance Elite 15": {
            "questions": typeB
        },
        "Performance Elite 7": {
            "questions": typeB
        },
        "Single Premium Immediate": {
            "questions": typeB
        },
    },
    "CFG": {
        "Dignified Choice Series": {
            "questions": typeA
        },
        "SafeShield Simplified Issue": {
            "questions": typeA
        },
    },
    "Columbus Life": {
        "Advantage Single Premium Fixed Indexed": {
            "questions": typeB
        },
        "Expedition Survivorship IUL": {
            "questions": typeD
        },
        "Explorer Plus UL": {
            "questions": typeE
        },
        "Indexed Explorer Plus UL": {
            "questions": typeD
        },
        "Nautical Term": {
            "questions": typeA
        },
        "Voyager UL": {
            "questions": typeA
        },
    },
    "EquiTrust": {
        "Certainty Select": {
            "questions": typeB
        },
        "ChoiceFour": {
            "questions": typeB
        },
        "Confidence Income Annuity": {
            "questions": typeB
        },
        "MarketForce Bonus Index": {
            "questions": typeB
        },
        "MarketMax Index": {
            "questions": typeB
        },
        "MarketPower Bonus Index": {
            "questions": typeB
        },
        "MarketSeven Index": {
            "questions": typeB
        },
        "MarketTen Bonus Index": {
            "questions": typeB
        },
        "MarketValue Index": {
            "questions": typeB
        },
    },
    "Ethos": {
        "Ethos Final Expense": {
            "questions": typeA
        },
        "Ethos Term Life Prime (LGA)": {
            "questions": typeC
        },
        "Ethos Term Life Select (LGA-Elevated)": {
            "questions": typeC
        },
        "Ethos Term Life Spectrum (Ameritas)": {
            "questions": typeC
        },
        "TruStage Advantage Whole Life": {
            "questions": typeC
        },
        "TruStage Simplified Issue Term Life": {
            "questions": typeC
        },
        "TrueStage Guaranteed Acceptance Whole Life": {
            "questions": typeA
        },
    },
    "Fidelity & Guaranty Life": {
        "Accelerator Plus 10": {
            "questions": typeB
        },
        "Accelerator Plus 14": {
            "questions": typeB
        },
        "Accumulator Plus 10": {
            "questions": typeB
        },
        "Accumulator Plus 7": {
            "questions": typeB
        },
        "Dynamic Accumulator": {
            "questions": typeB
        },
        "Everlast": {
            "questions": typeD
        },
        "ExecuDex": {
            "questions": typeD
        },
        "Flex Accumulator": {
            "questions": typeB
        },
        "Guarantee-Platinum Series": {
            "questions": typeB
        },
        "Immediate Income": {
            "questions": typeB
        },
        "Pathsetter": {
            "questions": typeD
        },
        "Performance Pro": {
            "questions": typeB
        },
        "Power Accumulator 10": {
            "questions": typeA
        },
        "Power Accumulator 7": {
            "questions": typeB
        },
        "Prosperity Elite": {
            "questions": typeB
        },
        "Retirement Pro": {
            "questions": typeB
        },
        "Safe Income Plus": {
            "questions": typeB
        },
    },
    "Foresters": {
        "Advantage Plus Fully Underwritten": {
            "questions": typeC
        },
        "Advantage Plus Simplified Issue": {
            "questions": typeC
        },
        "BrightFuture Children's Whole Life": {
            "questions": typeA
        },
        "PlanRight Basic": {
            "questions": typeA
        },
        "PlanRight Preferred": {
            "questions": typeA
        },
        "PlanRight Standard": {
            "questions": typeA
        },
        "Prepared": {
            "questions": typeA
        },
        "Smart UL Fully Underwritten": {
            "questions": typeA
        },
        "Smart UL Simplified Issue": {
            "questions": typeA
        },
        "Strong Foundation Simplified": {
            "questions": typeA
        },
        "Your Legacy": {
            "questions": typeA
        },
        "Your Term Medical": {
            "questions": typeA
        },
    },
    "Gerber": {
        "Accident Protection": {
            "questions": typeA
        },
        "Gerber Life College Plan": {
            "questions": typeB
        },
        "Grow Up Plan": {
            "questions": typeA
        },
        "Grow Up Plan *Advance Payment*": {
            "questions": typeA
        },
        "Guaranteed Issue Whole Life": {
            "questions": typeA
        },
        "Simplified Senior Life": {
            "questions": typeA
        },
        "Whole Life Age 18-70": {
            "questions": typeA
        },
    },
    "GILICO": {
        "Choice 4": {
            "questions": typeB
        },
        "Flex 10": {
            "questions": typeB
        },
        "Flex 12": {
            "questions": typeB
        },
        "Flex 7": {
            "questions": typeB
        },
        "FlexPlus 10": {
            "questions": typeB
        },
        "FlexPlus 5": {
            "questions": typeB
        },
        "FlexPlus 7": {
            "questions": typeB
        },
        "GILICO Form 1046": {
            "questions": typeB
        },
        "GILICO Form 1049": {
            "questions": typeB
        },
        "Guaranty 4": {
            "questions": typeB
        },
        "Guaranty 6": {
            "questions": typeB
        },
        "Guaranty 8": {
            "questions": typeB
        },
        "Guaranty Growth Builder": {
            "questions": typeB
        },
        "Guaranty Growth Plus": {
            "questions": typeB
        },
        "Guaranty Immediate Annuity": {
            "questions": typeB
        },
        "Guaranty Rate Lock": {
            "questions": typeB
        },
        "SPDA": {
            "questions": typeB
        },
        "SPDA Deposits": {
            "questions": typeB
        },
        "Security Plus": {
            "questions": typeB
        },
        "Ultra Choice": {
            "questions": typeB
        },
        "Ultra Flex 10": {
            "questions": typeB
        },
        "Ultra Flex 7": {
            "questions": typeB
        },
        "WealthChoice": {
            "questions": typeB
        },
    },
    "Government Personnel Mutual": {
        "20 Pay Whole Life": {
            "questions": typeA
        },
        "Equity Protector": {
            "questions": typeA
        },
        "Estate Builder": {
            "questions": typeA
        },
        "Estate Builder for Kids": {
            "questions": typeA
        },
        "Final Expense": {
            "questions": typeA
        },
        "Life Alliance Term": {
            "questions": typeA
        },
        "Life Alliance UL": {
            "questions": typeA
        },
        "Traditional Whole Life": {
            "questions": typeA
        },
    },
    "Great Western": {
        "Day One/Assurance Plus": {
            "questions": typeA
        },
        "Graded Benefit Plan": {
            "questions": typeA
        },
        "Guaranteed Assurance": {
            "questions": typeA
        },
        "The Great Assurance": {
            "questions": typeA
        },
    },
    "HMA": {
        "HMA 10000": {
            "questions": typeA
        },
        "HMA 15000": {
            "questions": typeA
        },
        "HMA 20000": {
            "questions": typeA
        },
        "HMA 2500": {
            "questions": typeA
        },
        "HMA 25000": {
            "questions": typeA
        },
        "HMA 30000": {
            "questions": typeA
        },
        "HMA 40000": {
            "questions": typeA
        },
        "HMA 5000": {
            "questions": typeA
        },
        "HMA 50000": {
            "questions": typeA
        },
        "HMA 60000": {
            "questions": typeA
        },
        "HMA 7500": {
            "questions": typeA
        },
    },
    "John Hancock": {
        "Simple Term": {
            "questions": typeA
        },
    },
    "Lafayette Life": {
        "10 Pay Life WL": {
            "questions": typeC
        },
        "Contender WL": {
            "questions": typeC
        },
        "Continental Term Series": {
            "questions": typeA
        },
        "Group Marquis® Fixed Indexed Annuities": {
            "questions": typeB
        },
        "Heritage WL": {
            "questions": typeC
        },
        "Horizon Single Premium Immediate Annuity": {
            "questions": typeB
        },
        "Liberty WL": {
            "questions": typeC
        },
        "Marquis® Centennial Deferred Fixed Indexed Annuity": {
            "questions": typeB
        },
        "Marquis® Single Premium Fixed Indexed Annuity": {
            "questions": typeB
        },
        "Patriot WL": {
            "questions": typeC
        },
        "Protector Simplified WL": {
            "questions": typeA
        },
        "Sentinel WL": {
            "questions": typeC
        },
    },
    "Mutual of Omaha": {
        "Accum UL Fully Underwritten": {
            "questions": typeA
        },
        "Children's Whole Life": {
            "questions": typeA
        },
        "Critical Advantage Cancer": {
            "questions": typeA
        },
        "Critical Advantage Critical": {
            "questions": typeA
        },
        "Critical Advantage Heart Attack & Stroke": {
            "questions": typeA
        },
        "DI Mutual Income Solutions": {
            "questions": typeA
        },
        "Guaranteed Advantage": {
            "questions": typeA
        },
        "Income Advantage IUL": {
            "questions": typeA
        },
        "Indexed UL Express": {
            "questions": typeA
        },
        "Life Protection Advantage IUL": {
            "questions": typeA
        },
        "Living Promise Graded": {
            "questions": typeA
        },
        "Living Promise Level": {
            "questions": typeA
        },
        "Long-Term Care": {
            "questions": typeA
        },
        "Medicare Supplement": {
            "questions": typeA
        },
        "Medicare Supplement Basic": {
            "questions": typeA
        },
        "Medicare Supplement Extended Basic": {
            "questions": typeA
        },
        "Medicare Supplement High Deductible Basic": {
            "questions": typeA
        },
        "Medicare Supplement Plan A": {
            "questions": typeA
        },
        "Medicare Supplement Plan B": {
            "questions": typeA
        },
        "Medicare Supplement Plan C": {
            "questions": typeA
        },
        "Medicare Supplement Plan D": {
            "questions": typeA
        },
        "Medicare Supplement Plan F": {
            "questions": typeA
        },
        "Medicare Supplement Plan G": {
            "questions": typeA
        },
        "Medicare Supplement Plan M": {
            "questions": typeA
        },
        "Medicare Supplement Plan N": {
            "questions": typeA
        },
        "Mutual Dental Preferred": {
            "questions": typeA
        },
        "Mutual Dental Protection": {
            "questions": typeA
        },
        "Term Life Answers Fully Underwritten": {
            "questions": typeA
        },
        "Term Life Express Simplified Issue": {
            "questions": typeA
        },
    },
    "Nassau Re": {
        "Phoenix Personal Income": {
            "questions": typeB
        },
        "Remembrance Life": {
            "questions": typeA
        },
        "Safe Harbor Express": {
            "questions": typeA
        },
        "Safe Harbor Term": {
            "questions": typeA
        },
        "Simplicity IUL Simplified": {
            "questions": typeA
        },
    },
    "NLG": {
        "BasicSecure": {
            "questions": typeA
        },
        "FIT Certain Income": {
            "questions": typeB
        },
        "FIT Focus Growth": {
            "questions": typeB
        },
        "FIT Focus Income": {
            "questions": typeB
        },
        "FIT Horizon Growth": {
            "questions": typeB
        },
        "FIT Horizon Income": {
            "questions": typeB
        },
        "FIT Rewards Growth Select Income": {
            "questions": typeF
        },
        "FIT Secure Growth": {
            "questions": typeF
        },
        "FIT Select Income": {
            "questions": typeF
        },
        "Flexlife (2019)": {
            "questions": typeD
        },
        "LSW Term": {
            "questions": typeA
        },
        "PeakLife (2019)": {
            "questions": typeD
        },
        "SummitLife IUL": {
            "questions": typeD
        },
        "SurvivorLife": {
            "questions": typeD
        },
        "Total Secure": {
            "questions": typeA
        },
    },
    "North American Co": {
        "Charter Plus 10": {
            "questions": typeB
        },
        "Charter Plus 14": {
            "questions": typeB
        },
        "Guarantee Choice 10": {
            "questions": typeB
        },
        "Guarantee Choice 3": {
            "questions": typeB
        },
        "Guarantee Choice 5": {
            "questions": typeB
        },
        "Guarantee Choice 7": {
            "questions": typeB
        },
        "NA Income": {
            "questions": typeB
        },
        "NAC Benefit Solutions 10": {
            "questions": typeB
        },
        "NAC Income Choice 10": {
            "questions": typeB
        },
        "NAC Versa Choice 10": {
            "questions": typeB
        },
        "Performance Choice 8": {
            "questions": typeB
        },
        "Prime Path Pro 10": {
            "questions": typeB
        },
        "Prime Path Pro 12": {
            "questions": typeB
        },
    },
    "Occidental": {
        "EZ Term": {
            "questions": typeA
        },
        "Express UL": {
            "questions": typeA
        },
        "Family Choice": {
            "questions": typeA
        },
        "Home Protector": {
            "questions": typeA
        },
        "OBA": {
            "questions": typeA
        },
        "Security Protector": {
            "questions": typeA
        },
        "Sr. Choice": {
            "questions": typeA
        },
        "Survivor Protector": {
            "questions": typeA
        },
        "Term Made Simple": {
            "questions": typeA
        },
    },
    "Oxford": {
        "Assurance Final Expense": {
            "questions": typeA
        },
        "Assurance One Single Premium": {
            "questions": typeA
        },
        "Prosperity Select Single Premium": {
            "questions": typeA
        },
    },
    "Pet HMA": {
        "PHMA 10000": {
            "questions": typeA
        },
        "PHMA 15000": {
            "questions": typeA
        },
        "PHMA 20000": {
            "questions": typeA
        },
        "PHMA 2500": {
            "questions": typeA
        },
        "PHMA 5000": {
            "questions": typeA
        },
        "PHMA 7500": {
            "questions": typeA
        },
    },
    "Prosperity": {
        "Family Freedom Term": {
            "questions": typeA
        },
        "New Vista Final Expense": {
            "questions": typeA
        },
        "PrimeTerm": {
            "questions": typeA
        },
    },
    "Royal Neighbors": {
        "Graded Death Benefit": {
            "questions": typeA
        },
        "JETerm Fully Underwritten": {
            "questions": typeA
        },
        "JETerm Simplified Issue": {
            "questions": typeA
        },
        "Simplified Issue": {
            "questions": typeA
        },
    },
    "Security Benefit": {
        "Strategic Growth": {
            "questions": typeA
        },
        "Strategic Growth Plus": {
            "questions": typeA
        },
        "Total Value": {
            "questions": typeA
        },
    },
    "Sentinel Security Life": {
        "Accumulation Protector Plus": {
            "questions": typeB
        },
        "Guaranteed Income Annuity": {
            "questions": typeB
        },
        "Medicare Supplement / Select": {
            "questions": typeA
        },
        "Personal Choice Annuity": {
            "questions": typeB
        },
        "Personal Choice Plus 5 Year Index": {
            "questions": typeB
        },
        "Retirement Plus Multiplier": {
            "questions": typeB
        },
        "Summit Bonus Index": {
            "questions": typeB
        },
    },
    "SILAC": {
        "Teton Bonus Series": {
            "questions": typeF
        },
    },
    "Tier One Aflac": {},
    "TransAmerica": {
        "Accumulation UL": {
            "questions": typeA
        },
        "Final Expense Solutions": {
            "questions": typeA
        },
        "Financial Foundation": {
            "questions": typeA
        },
        "Lifetime Whole Life": {
            "questions": typeA
        },
        "Trendsetter LB": {
            "questions": typeA
        },
        "Trendsetter Super": {
            "questions": typeA
        },
    },
})
let currentQuestion = ref({
    text: 'Agent Full Name',
    heading: 'Agent Information',
    type: 'text',
    propertyName: 'agentName',
    validationMethod: 'validateRequiredField',
    required: true,
})

let questions = ref([
    {
        text: 'Application Date',
        type: 'date',
        propertyName: 'applicationDate',
        validationMethod: 'validateRequiredField',
        heading: 'Policy Information',
        required: true,
    },
    {
        text: 'Coverage Amount',
        type: 'currency',
        propertyName: 'coverageAmount',
        validationMethod: 'validatePositiveNumberField',
        heading: 'Policy Information',
        required: true,
    },
    {
        text: 'Coverage Length',
        heading: 'Policy Information',
        type: 'select',
        options: [
            'N/A',
            '3 Years',
            '5 Years',
            '5 Years w/ 5 Year Guaranty',
            '5 Years w/ ROP',
            '7 Years',
            '10 Years',
            '15 Years',
            '15 Years w/ ROP',
            '20 Years',
            '20 Years w/ 5 Year Guaranty',
            '20 Years w/ ROP',
            '25 Years',
            '25 Years w/ ROP',
            '30 Years',
            '30 Years w/ 5 Year Guaranty',
            '30 Years w/ ROP',
            'Issue Ages',
        ],
        propertyName: 'coverageLength',
        validationMethod: 'validateRequiredField',
        required: true,
    },

    {
        text: 'Premium Frequency',
        heading: 'Policy Information',
        type: 'select',
        options: [
            'Monthly',
            'Quarterly',
            'Semi-Annual',
            'Annual',
        ],
        propertyName: 'premiumFrequency',
        validationMethod: 'validateRequiredField',
        required: true,
    },

    {
        text: 'Premium Amount',
        textMethod: 'getAnnualPremiumVolumeText',
        heading: 'Policy Information',
        type: 'currency',
        propertyName: 'premiumAmount',
        validationMethod: 'validatePositiveNumberField',
        required: true,
    },
    {
        text: 'Annual Premium Volume',
        heading: 'Policy Information',
        type: 'currency',
        propertyName: 'annualPremiumVolume',
        validationMethod: 'validatePositiveNumberField',
        required: true,
    },
    {
        text: 'Do you have an Equis writing number with this carrier?',
        heading: 'Policy Information',
        type: 'select',
        options: ['Yes', 'No'],
        propertyName: 'doYouHaveAnEquisWritingNumberWithThisCarrier',
        validationMethod: 'validateOptionalField',
        required: false,
    },
    {
        text: 'Carrier Writing Number',
        heading: 'Policy Information',
        type: 'text',
        propertyName: 'carrierWritingNumber',
        validationMethod: 'validateOptionalField',
        required: false,
    },
    {
        text: 'Was this app from a lead?',
        heading: 'Policy Information',
        type: 'select',
        options: ['Yes', 'No'],
        propertyName: 'wasThisAppFromALead',
        validationMethod: 'validateRequiredField',
        required: true,
    },
    {
        text: 'Source of the lead',
        heading: 'Policy Information',
        type: 'select',
        options: [
            'Transfer Program',
            'Callback Lead',
            'Internet Leads',
            'Opt Leads',
            'Referral',
        ],
        propertyName: 'sourceOfTheLead',
        validationMethod: 'validateRequiredField',
        required: true,
    },
    {
        text: 'Was this appointment virtual or face-to-face?',
        heading: 'Policy Information',
        type: 'select',
        options: ['Virtual', 'Face to face'],
        propertyName: 'wasThisAppointmentVirtualOrFaceToFace',
        validationMethod: 'validateRequiredField',
        required: true,
    },
    {
        text: 'Annual Target Premium',
        heading: 'Policy Information',
        type: 'currency',
        propertyName: 'annualTargetPremium',
        validationMethod: 'validatePositiveNumberField',
        required: true,
    },
    {
        text: 'Annual Planned Premium',
        heading: 'Policy Information',
        type: 'currency',
        propertyName: 'annualPlannedPremium',
        validationMethod: 'validatePositiveNumberField',
        required: true,
    },
    {
        text: 'Annual Excess Premium',
        heading: 'Policy Information',
        type: 'currency',
        propertyName: 'annualExcessPremium',
        validationMethod: 'validatePositiveNumberField',
        required: true,
    },
    {
        text: 'Initial Investment Amount',
        heading: 'Policy Information',
        type: 'currency',
        propertyName: 'initialInvestmentAmount',
        validationMethod: 'validatePositiveNumberField',
        required: true,
    },
    {
        text: 'Did another agent refer this application to you?',
        heading: 'Policy Information',
        type: 'select',
        options: [
            'Yes', 'No'
        ],
        propertyName: 'didAnotherAgentReferThisApplicationToYou',
        validationMethod: 'validateRequiredField',
        required: true,
    },
    {
        text: 'referringAgentEFNumber',
        heading: 'Policy Information',
        type: 'text',
        propertyName: 'referringAgentEFNumber',
        validationMethod: 'validateRequiredField',
        required: true,
    },
    {
        text: 'Is this AN SDIC?',
        heading: 'Policy Information',
        type: 'select',
        options: ['Yes', 'No'],
        propertyName: 'isThisAnSDIC',
        validationMethod: 'validateRequiredField',
        required: true,
    },
    {
        text: 'Will there be a recurring premium?',
        heading: 'Policy Information',
        type: 'select',
        options: ['Yes', 'No'],
        propertyName: 'willThereBeARecurringPremium',
        validationMethod: 'validateRequiredField',
        required: true,
    },
    {
        text: 'First Name',
        heading: 'Client Information',
        type: 'text',
        propertyName: 'firstName',
        validationMethod: 'validateRequiredField',
        required: true,
    },
    {
        text: 'MI',
        heading: 'Client Information',
        type: 'text',
        propertyName: 'MI',
        validationMethod: 'validateOptionalField',
        required: false,
    },
    {
        text: 'Last name',
        heading: 'Client Information',
        type: 'text',
        propertyName: 'lastName',
        validationMethod: 'validateRequiredField',
        required: true,
    },
    {
        text: 'Date of Birth',
        heading: 'Client Information',
        heading: 'Client Information',
        type: 'date',
        propertyName: 'dateOfBirth',
        validationMethod: 'validateRequiredField',
        required: true,
    },
    {
        text: 'Gender',
        heading: 'Client Information',
        type: 'select',
        options: ['Male', 'Female', 'Other'],
        propertyName: 'gender',
        validationMethod: 'validateRequiredField',
        required: true,
    },
    {
        text: 'Street Address 1',
        heading: 'Client Contact Information',
        type: 'text',
        propertyName: 'streetLine1',
        validationMethod: 'validateRequiredField',
        required: true,
    },
    {
        text: 'Street Address 2',
        heading: 'Client Contact Information',
        type: 'text',
        propertyName: 'streetLine2',
        validationMethod: 'validateOptionalField',
        required: false,
    },
    {
        text: 'City',
        heading: 'Client Contact Information',
        type: 'text',
        propertyName: 'city',
        validationMethod: 'validateRequiredField',
        required: true,
    },
    {
        text: 'State',
        heading: 'Client Contact Information',
        type: 'select',
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
            "Wyoming"
        ],
        propertyName: 'state',
        validationMethod: 'validateRequiredField',
        required: true,
    },
    {
        text: 'Zip-code',
        heading: 'Client Contact Information',
        type: 'text',
        propertyName: 'zipcode',
        validationMethod: 'validateZipCodeField',
        required: true,
    },
    {
        text: 'Client Phone Number',
        heading: 'Client Contact Information',
        type: 'text',
        propertyName: 'phoneNumber',
        validationMethod: 'validatePhoneNumberField',
        required: true,
    },
    {
        text: 'Client Email',
        heading: 'Client Contact Information',
        type: 'text',
        propertyName: 'email',
        validationMethod: 'validateEmailField',
        required: true,
    },

    {
        text: 'Agent Full Name',
        heading: 'Agent Information',
        type: 'text',
        propertyName: 'agentName',
        validationMethod: 'validateRequiredField',
        required: true,
    },

    {
        text: 'Agent Email',
        heading: 'Agent Information',
        type: 'text',
        propertyName: 'agentEmail',
        validationMethod: 'validateEmailField',
        required: true,
    },

    {
        text: 'Enter Your FULL EF Number. EXAMPLE: EF123456',
        heading: 'Agent Information',
        type: 'text',
        propertyName: 'EFNumber',
        validationMethod: 'validateRequiredField',
        required: true,
    },

    {
        text: 'Select Your Upline Manager',
        heading: 'Agent Information',
        type: 'select',
        options: [
            'Aurora Financial - Vincent Hall ',
            'Imperial Group - Ruben Basurto',
            '1st Choice Agency - Paul & Lauren Vassallo',
            'Advisor Guild - David Richter',
            'Peace Financial - Timothy Charpentier',
            'Straus Agency - Cynthia Straus'
        ],
        propertyName: 'uplineManager',
        validationMethod: 'validateRequiredField',
        required: true,
    },

    {
        text: 'Was this a split sale?',
        heading: 'Agent Information',
        type: 'select',
        options: [
            'Yes', 'No'
        ],
        propertyName: 'wasThisASplitSale',
        validationMethod: 'validateRequiredField',
        required: true,
    },

    {
        text: 'Choose Type of Split Sale',
        heading: 'Agent Information',
        type: 'select',
        options: [
            'Transfer Program',
            'Retirement Specialist',
            'Other',
        ],
        propertyName: 'choose',
        validationMethod: 'validateRequiredField',
        required: true,
    },

    {
        text: 'Split Agent Email',
        heading: 'Agent Information',
        type: 'text',
        propertyName: 'splitAgentEmail',
        validationMethod: 'validateEmailField',
        required: true,
    },

    {
        text: 'Insurance Company',
        heading: 'Policy Information',
        type: 'dynamicSelect',
        propertyName: 'insuranceCompany',
        dynamicOptionsMethod: 'getInsuranceCompanyOptions',
        validationMethod: 'validateRequiredField',
        required: true,
    },

    {
        text: 'Product Name',
        heading: 'Policy Information',
        type: 'dynamicSelect',
        propertyName: 'productName',
        dynamicOptionsMethod: 'getProductNameOptions',
        validationMethod: 'validateRequiredField',
        required: true,
    },

    {
        text: 'What Is the Policy Draft Date?',
        heading: 'Policy Information',
        type: 'date',
        propertyName: 'whatIsThePolicyDraftDate',
        validationMethod: 'validateRequiredField',
        required: true,
    },

    {
        text: 'Address Fields',
        text: 'Client Information',
        type: 'collection',
        questions: [
            {
                text: 'Street Address 1',
                heading: 'Client Contact Information',
                type: 'text',
                propertyName: 'streetLine1',
                validationMethod: 'validateStreetAddress',
                required: true,
            },
            {
                text: 'Street Address 2',
                heading: 'Client Contact Information',
                type: 'text',
                propertyName: 'streetLine2',
                validationMethod: 'validateOptionalField',
                required: false,
            },
            {
                text: 'City',
                heading: 'Client Contact Information',
                type: 'text',
                propertyName: 'city',
                validationMethod: 'validateCity',
                required: true,
            },
            {
                text: 'State',
                heading: 'Client Contact Information',
                type: 'select',
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
                    "Wyoming"
                ],
                propertyName: 'state',
                validationMethod: 'validateState',
                required: true,
            },
            {
                text: 'Zip-code',
                heading: 'Client Contact Information',
                type: 'text',
                propertyName: 'zipcode',
                validationMethod: 'validateZipCodeField',
                required: true,
            },
        ],
    },
])
let uplineManagerArray = ref([
    { text: 'Aurora Financial - Vincent Hall' },
    { text: 'Imperial Group - Ruben Basurto' },
    { text: '1st Choice Agency - Paul & Lauren Vassallo' },
    { text: 'Advisor Guild - David Richter' },
    { text: 'Peace Financial - Timothy Charpentier' },
    { text: 'Straus Agency - Cynthia Straus' }
])

const isValidEmail = (email) => {
    // Regular expression for validating an Email address
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
};

let restoreError = () => {
    props.firstStepErrors.full_name = ''
    props.firstStepErrors.agentEmail = ''
    props.firstStepErrors.EFNumber = ''
    props.firstStepErrors.uplineManager = '';
    props.firstStepErrors.wasThisASplitSale = '';
    props.firstStepErrors.typeOfSplitSale = '';
    props.firstStepErrors.insuranceCompany = '';
    props.firstStepErrors.productName = '';
    props.firstStepErrors.applicationDate = '';
    props.firstStepErrors.coverageAmount = '';
    props.firstStepErrors.coverageLength = '';
    props.firstStepErrors.premiumFrequency = '';
    props.firstStepErrors.premiumAmount = '';
    props.firstStepErrors.annualPremiumVolume = '';
    props.firstStepErrors.doYouHaveAnEquisWritingNumberWithThisCarrier = '';
    props.firstStepErrors.carrierWritingNumber = '';
    props.firstStepErrors.wasThisAppFromALead = '';
    props.firstStepErrors.sourceOfTheLead = '';
    props.firstStepErrors.wasThisAppointmentVirtualOrFaceToFace = '';
    props.firstStepErrors.whatIsThePolicyDraftDate = '';
    props.firstStepErrors.firstName = '';
    props.firstStepErrors.MI = '';
    props.firstStepErrors.lastName = '';
    props.firstStepErrors.dateOfBirth = '';
    props.firstStepErrors.gender = '';
    props.firstStepErrors.streetLine1 = '';
    props.firstStepErrors.streetLine2 = '';
    props.firstStepErrors.city = '';
    props.firstStepErrors.state = '';
    props.firstStepErrors.zipcode = '';
    props.firstStepErrors.phoneNumber = '';
    props.firstStepErrors.client_email = '';
}
// validation steps  star
let validationMethod = (step_input) => {
    restoreError()
    // Check for required fields in step 1
    if (step_input === 1) {
        if (!form.value.full_name.trim()) {
            props.firstStepErrors.full_name = ['This field is required'];
        }

        // Add similar checks for other fields in step 1
    } else if (step_input === 2) {
        // Check for required fields in step 2
        if (!form.value.agentEmail.trim()) {
            props.firstStepErrors.agentEmail = ['This field is required'];
        } else if (!isValidEmail(form.value.agentEmail)) {
            props.firstStepErrors.agentEmail = ['Invalid email format'];
        }

        // Add similar checks for other fields in step 2
    } else if (step_input === 3) {
        if (!form.value.EFNumber.trim()) {
            props.firstStepErrors.EFNumber = ['This field is required'];
        }
    } else if (step_input === 4) {
        if (!form.value.uplineManager.trim() || form.value.uplineManager === 'Select') {
            props.firstStepErrors.uplineManager = ['This field is required'];
        }
    } else if (step_input === 5) {
        if (!form.value.wasThisASplitSale.trim() || form.value.wasThisASplitSale === 'Select') {
            props.firstStepErrors.wasThisASplitSale = ['This field is required'];
        }
    } else if (step_input === 6) {
        if (!form.value.typeOfSplitSale.trim() || form.value.typeOfSplitSale === 'Select') {
            props.firstStepErrors.typeOfSplitSale = ['This field is required'];
        }
    } else if (step_input === 7) {
        if (!form.value.insuranceCompany.trim() || form.value.insuranceCompany === 'Select') {
            props.firstStepErrors.insuranceCompany = ['This field is required'];
        }
    } else if (step_input === 8) {
        if (!form.value.productName.trim() || form.value.productName === 'Select') {
            props.firstStepErrors.productName = ['This field is required'];
        }
    } else if (step_input === 9) {
        if (!form.value.applicationDate.trim()) {
            props.firstStepErrors.applicationDate = ['This field is required'];
        }
    } else if (step_input === 10) {
        if (!form.value.coverageAmount.trim()) {
            props.firstStepErrors.coverageAmount = ['This field is required'];
        }
    } else if (step_input === 11) {
        if (!form.value.coverageLength.trim()) {
            props.firstStepErrors.coverageLength = ['This field is required'];
        }
    } else if (step_input === 12) {
        if (!form.value.premiumFrequency.trim()) {
            props.firstStepErrors.premiumFrequency = ['This field is required'];
        }
    } else if (step_input === 13) {
        if (!form.value.premiumAmount.trim()) {
            props.firstStepErrors.premiumAmount = ['This field is required'];
        }
    } else if (step_input === 14) {
        if (!form.value.annualPremiumVolume.trim()) {
            props.firstStepErrors.annualPremiumVolume = ['This field is required'];
        }
    } else if (step_input === 15) {
        if (!form.value.doYouHaveAnEquisWritingNumberWithThisCarrier.trim()) {
            props.firstStepErrors.doYouHaveAnEquisWritingNumberWithThisCarrier = ['This field is required'];
        }
    } else if (step_input === 16) {
        if (!form.value.carrierWritingNumber.trim()) {
            props.firstStepErrors.carrierWritingNumber = ['This field is required'];
        }
    } else if (step_input === 17) {
        if (!form.value.wasThisAppFromALead.trim()) {
            props.firstStepErrors.wasThisAppFromALead = ['This field is required'];
        }
    } else if (step_input === 18) {
        if (!form.value.sourceOfTheLead.trim()) {
            props.firstStepErrors.sourceOfTheLead = ['This field is required'];
        }
    } else if (step_input === 19) {
        if (!form.value.wasThisAppointmentVirtualOrFaceToFace.trim()) {
            props.firstStepErrors.wasThisAppointmentVirtualOrFaceToFace = ['This field is required'];
        }
    } else if (step_input === 20) {
        if (!form.value.whatIsThePolicyDraftDate.trim()) {
            props.firstStepErrors.whatIsThePolicyDraftDate = ['This field is required'];
        }
    } else if (step_input === 21) {
        if (!form.value.firstName.trim()) {
            props.firstStepErrors.firstName = ['This field is required'];
        }
    } else if (step_input === 22) {
        if (!form.value.MI.trim()) {
            props.firstStepErrors.MI = ['This field is required'];
        }
    } else if (step_input === 23) {
        if (!form.value.lastName.trim()) {
            props.firstStepErrors.lastName = ['This field is required'];
        }
    } else if (step_input === 24) {
        if (!form.value.dateOfBirth.trim()) {
            props.firstStepErrors.dateOfBirth = ['This field is required'];
        }
    } else if (step_input === 25) {
        if (!form.value.gender.trim()) {
            props.firstStepErrors.gender = ['This field is required'];
        }
    } else if (step_input === 26) {
        if (!form.value.streetLine1.trim()) {
            props.firstStepErrors.streetLine1 = ['This field is required'];
        }
    } else if (step_input === 27) {
        if (!form.value.streetLine2.trim()) {
            props.firstStepErrors.streetLine2 = ['This field is required'];
        }
    } else if (step_input === 28) {
        if (!form.value.city.trim()) {
            props.firstStepErrors.city = ['This field is required'];
        }
    } else if (step_input === 29) {
        if (!form.value.state.trim()) {
            props.firstStepErrors.state = ['This field is required'];
        }
    } else if (step_input === 30) {
        if (!form.value.zipcode.trim()) {
            props.firstStepErrors.zipcode = ['This field is required'];
        }
    } else if (step_input === 31) {
        if (!form.value.phoneNumber.trim()) {
            props.firstStepErrors.phoneNumber = ['This field is required'];
        }
    } else if (step_input === 32) {
        if (!form.value.client_email.trim()) {
            props.firstStepErrors.client_email = ['This field is required'];
        }
    }
}
// validation steps end

let ChangeProducName = () => {
   form.value.productName = 'Select' 
}
let getInsuranceCompanyOptions = () =>  {
            return Object.keys(companies.value);
}
let getProductNameOptions = () =>  {
            return Object.keys(companies.value[form.value.insuranceCompany]);
        }
const Next = (data) => {
    validationMethod(data);
    // Only proceed to the next step if there are no errors
    if (!Object.values(props.firstStepErrors).flat().some(error => error)) {
        step.value += 1;
    }
};
let Previous = (data) => {
    step.value -= 1;
}
let close = () => {
    emits('close')
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
    <Transition name="modal" enter-active-class="transition ease-out duration-300 transform"
        enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        enter-to-class="opacity-100 translate-y-0 sm:scale-100"
        leave-active-class="transition ease-in duration-200 transform"
        leave-from-class="opacity-100 translate-y-0 sm:scale-100"
        leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
        <div id="defaultModal" v-if="addBusinessModal" tabindex="-1"
            class="flex items-center justify-center fixed inset-0 z-50 w-full h-full overflow-x-hidden overflow-y-auto max-h-full mx-4 sm:mx-0">
            <div class="fixed inset-0 bg-black opacity-90 blurred-overlay"></div>

            <!-- This is the overlay -->
            <div class="relative w-full max-w-xl max-h-full mx-auto">
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
                        <h1 class="text-gray-800 text-2xl font-bold">Invite Agent</h1>
                        <br>
                        <div class="mb-3">
                            <form @submit.prevent="" class="question-card-list">
                                <div class="question-card animate__animated" style="position: relative;">

                                    <div v-show="step == 1">
                                        <label for="full name"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Full
                                            Name <span class="text-red-400">*</span></label>
                                        <input v-model="form.full_name" type="text" id="full name"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Full Name" required>
                                        <div v-if="firstStepErrors.full_name" class="text-red-500"
                                            v-text="firstStepErrors.full_name[0]"></div>
                                    </div>

                                    <div v-show="step == 2">
                                        <label for="Email"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email<span
                                                class="text-red-400">*</span></label>
                                        <input v-model="form.agentEmail" type="text" id="Email"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Email" required>
                                        <div v-if="firstStepErrors.agentEmail" class="text-red-500"
                                            v-text="firstStepErrors.agentEmail[0]"></div>
                                    </div>
                                    <div v-show="step == 3">
                                        <label for="EFNumber"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Enter Your
                                            FULL EF Number. EXAMPLE: EF123456<span class="text-red-400">*</span></label>
                                        <input v-model="form.EFNumber" type="text" id="EFNumber"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="EFNumber" required>
                                        <div v-if="firstStepErrors.EFNumber" class="text-red-500"
                                            v-text="firstStepErrors.EFNumber[0]"></div>
                                    </div>



                                    <div v-show="step == 4">
                                        <label for="countries"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select Your
                                            Upline Manager</label>
                                        <select v-model="form.uplineManager" id="countries"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option disabled>Select</option>
                                            <option v-for="uplineManager in uplineManagerArray" :value="uplineManager.text">{{ uplineManager.text }}</option>
                                        </select>

                                        <div v-if="firstStepErrors.uplineManager" class="text-red-500"
                                            v-text="firstStepErrors.uplineManager[0]"></div>
                                    </div>
                                    <div v-show="step == 5">
                                        <label for="countries"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Was this a split sale?</label>
                                        <select v-model="form.wasThisASplitSale" id="countries"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option disabled>Select</option>
                                            <option  value="YES">YES</option>
                                            <option  value="NO">NO</option>
                                        </select>
                                        <div v-if="firstStepErrors.wasThisASplitSale" class="text-red-500"
                                            v-text="firstStepErrors.wasThisASplitSale[0]"></div>
                                    </div>
                                    <div v-show="step == 6">
                                        <label for="countries"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Choose Type of Split Sale</label>
                                        <select v-model="form.typeOfSplitSale" id="countries"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option disabled>Select</option>
                                            <option  value="Transfer Program">Transfer Program</option>
                                            <option  value="Callback Lead">Callback Lead</option>
                                            <option  value="Internet Leads">Internet Leads</option>
                                            <option  value="Opt Leads">Opt Leads</option>
                                            <option  value="Referral">Referral</option>
                                        </select>
                                        <div v-if="firstStepErrors.typeOfSplitSale" class="text-red-500"
                                            v-text="firstStepErrors.typeOfSplitSale[0]"></div>
                                    </div>
                                    <div v-show="step == 7">
                                        <label for="countries"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Was this a split sale?</label>
                                        <select v-model="form.insuranceCompany" id="countries" @change="ChangeProducName()"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option disabled>Select</option>
                                            <option v-for="option in getInsuranceCompanyOptions()" v-text="option"></option>
                                        </select>
                                        <div v-if="firstStepErrors.insuranceCompany" class="text-red-500"
                                            v-text="firstStepErrors.insuranceCompany[0]"></div>
                                    </div>
                                    <div v-show="step == 8">
                                        <label for="countries"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Was this a split sale?</label>
                                        <select v-model="form.productName" id="countries"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option disabled>Select</option>
                                            <option v-for="option in getProductNameOptions()" :value="option" v-text="option"></option>
                                        </select>
                                        <div v-if="firstStepErrors.productName" class="text-red-500"
                                            v-text="firstStepErrors.productName[0]"></div>
                                    </div>
                                    <div v-show="step == 9">
                                        <label for="countries"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select Your
                                            Upline Manager</label>
                                        <select v-model="form.uplineManager" id="countries"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option disabled>Select</option>
                                            <option v-for="uplineManager in uplineManagerArray" :value="uplineManager.text">{{ uplineManager.text }}</option>
                                        </select>

                                        <div v-if="firstStepErrors.uplineManager" class="text-red-500"
                                            v-text="firstStepErrors.uplineManager[0]"></div>
                                        <label for="EFNumber"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Enter Your
                                            FULL EF Number. EXAMPLE: EF123456<span class="text-red-400">*</span></label>
                                        <input v-model="form.EFNumber" type="text" id="EFNumber"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="EFNumber" required>
                                        <div v-if="firstStepErrors.EFNumber" class="text-red-500"
                                            v-text="firstStepErrors.EFNumber[0]"></div>
                                    </div>
                                    <div v-show="step == 10">
                                        <label for="EFNumber"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Enter Your
                                            FULL EF Number. EXAMPLE: EF123456<span class="text-red-400">*</span></label>
                                        <input v-model="form.EFNumber" type="text" id="EFNumber"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="EFNumber" required>
                                        <div v-if="firstStepErrors.EFNumber" class="text-red-500"
                                            v-text="firstStepErrors.EFNumber[0]"></div>
                                    </div>
                                    <div v-show="step == 11">
                                        <label for="EFNumber"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Enter Your
                                            FULL EF Number. EXAMPLE: EF123456<span class="text-red-400">*</span></label>
                                        <input v-model="form.EFNumber" type="text" id="EFNumber"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="EFNumber" required>
                                        <div v-if="firstStepErrors.EFNumber" class="text-red-500"
                                            v-text="firstStepErrors.EFNumber[0]"></div>
                                    </div>
                                    <div v-show="step == 12">
                                        <label for="EFNumber"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Enter Your
                                            FULL EF Number. EXAMPLE: EF123456<span class="text-red-400">*</span></label>
                                        <input v-model="form.EFNumber" type="text" id="EFNumber"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="EFNumber" required>
                                        <div v-if="firstStepErrors.EFNumber" class="text-red-500"
                                            v-text="firstStepErrors.EFNumber[0]"></div>
                                    </div>
                                    <div v-show="step == 13">
                                        <label for="EFNumber"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Enter Your
                                            FULL EF Number. EXAMPLE: EF123456<span class="text-red-400">*</span></label>
                                        <input v-model="form.EFNumber" type="text" id="EFNumber"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="EFNumber" required>
                                        <div v-if="firstStepErrors.EFNumber" class="text-red-500"
                                            v-text="firstStepErrors.EFNumber[0]"></div>
                                    </div>
                                    <div v-show="step == 14">
                                        <label for="EFNumber"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Enter Your
                                            FULL EF Number. EXAMPLE: EF123456<span class="text-red-400">*</span></label>
                                        <input v-model="form.EFNumber" type="text" id="EFNumber"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="EFNumber" required>
                                        <div v-if="firstStepErrors.EFNumber" class="text-red-500"
                                            v-text="firstStepErrors.EFNumber[0]"></div>
                                    </div>
                                    <div v-show="step == 15">
                                        <label for="EFNumber"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Enter Your
                                            FULL EF Number. EXAMPLE: EF123456<span class="text-red-400">*</span></label>
                                        <input v-model="form.EFNumber" type="text" id="EFNumber"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="EFNumber" required>
                                        <div v-if="firstStepErrors.EFNumber" class="text-red-500"
                                            v-text="firstStepErrors.EFNumber[0]"></div>
                                    </div>
                                    <div v-show="step == 16">
                                        <label for="EFNumber"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Enter Your
                                            FULL EF Number. EXAMPLE: EF123456<span class="text-red-400">*</span></label>
                                        <input v-model="form.EFNumber" type="text" id="EFNumber"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="EFNumber" required>
                                        <div v-if="firstStepErrors.EFNumber" class="text-red-500"
                                            v-text="firstStepErrors.EFNumber[0]"></div>
                                    </div>
                                    <div v-show="step == 17">
                                        <label for="EFNumber"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Enter Your
                                            FULL EF Number. EXAMPLE: EF123456<span class="text-red-400">*</span></label>
                                        <input v-model="form.EFNumber" type="text" id="EFNumber"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="EFNumber" required>
                                        <div v-if="firstStepErrors.EFNumber" class="text-red-500"
                                            v-text="firstStepErrors.EFNumber[0]"></div>
                                    </div>
                                    <div v-show="step == 18">
                                        <label for="EFNumber"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Enter Your
                                            FULL EF Number. EXAMPLE: EF123456<span class="text-red-400">*</span></label>
                                        <input v-model="form.EFNumber" type="text" id="EFNumber"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="EFNumber" required>
                                        <div v-if="firstStepErrors.EFNumber" class="text-red-500"
                                            v-text="firstStepErrors.EFNumber[0]"></div>
                                    </div>
                                    <div v-show="step == 19">
                                        <label for="EFNumber"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Enter Your
                                            FULL EF Number. EXAMPLE: EF123456<span class="text-red-400">*</span></label>
                                        <input v-model="form.EFNumber" type="text" id="EFNumber"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="EFNumber" required>
                                        <div v-if="firstStepErrors.EFNumber" class="text-red-500"
                                            v-text="firstStepErrors.EFNumber[0]"></div>
                                    </div>
                                    <div v-show="step == 20">
                                        <label for="EFNumber"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Enter Your
                                            FULL EF Number. EXAMPLE: EF123456<span class="text-red-400">*</span></label>
                                        <input v-model="form.EFNumber" type="text" id="EFNumber"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="EFNumber" required>
                                        <div v-if="firstStepErrors.EFNumber" class="text-red-500"
                                            v-text="firstStepErrors.EFNumber[0]"></div>
                                    </div>
                                    <div v-show="step == 21">
                                        <label for="EFNumber"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Enter Your
                                            FULL EF Number. EXAMPLE: EF123456<span class="text-red-400">*</span></label>
                                        <input v-model="form.EFNumber" type="text" id="EFNumber"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="EFNumber" required>
                                        <div v-if="firstStepErrors.EFNumber" class="text-red-500"
                                            v-text="firstStepErrors.EFNumber[0]"></div>
                                    </div>
                                    <div v-show="step == 22">
                                        <label for="EFNumber"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Enter Your
                                            FULL EF Number. EXAMPLE: EF123456<span class="text-red-400">*</span></label>
                                        <input v-model="form.EFNumber" type="text" id="EFNumber"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="EFNumber" required>
                                        <div v-if="firstStepErrors.EFNumber" class="text-red-500"
                                            v-text="firstStepErrors.EFNumber[0]"></div>
                                    </div>
                                    <div v-show="step == 23">
                                        <label for="EFNumber"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Enter Your
                                            FULL EF Number. EXAMPLE: EF123456<span class="text-red-400">*</span></label>
                                        <input v-model="form.EFNumber" type="text" id="EFNumber"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="EFNumber" required>
                                        <div v-if="firstStepErrors.EFNumber" class="text-red-500"
                                            v-text="firstStepErrors.EFNumber[0]"></div>
                                    </div>
                                    <div v-show="step == 24">
                                        <label for="EFNumber"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Enter Your
                                            FULL EF Number. EXAMPLE: EF123456<span class="text-red-400">*</span></label>
                                        <input v-model="form.EFNumber" type="text" id="EFNumber"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="EFNumber" required>
                                        <div v-if="firstStepErrors.EFNumber" class="text-red-500"
                                            v-text="firstStepErrors.EFNumber[0]"></div>
                                    </div>
                                    <div v-show="step == 25">
                                        <label for="EFNumber"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Enter Your
                                            FULL EF Number. EXAMPLE: EF123456<span class="text-red-400">*</span></label>
                                        <input v-model="form.EFNumber" type="text" id="EFNumber"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="EFNumber" required>
                                        <div v-if="firstStepErrors.EFNumber" class="text-red-500"
                                            v-text="firstStepErrors.EFNumber[0]"></div>
                                    </div>
                                    <div v-show="step == 26">
                                        <label for="EFNumber"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Enter Your
                                            FULL EF Number. EXAMPLE: EF123456<span class="text-red-400">*</span></label>
                                        <input v-model="form.EFNumber" type="text" id="EFNumber"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="EFNumber" required>
                                        <div v-if="firstStepErrors.EFNumber" class="text-red-500"
                                            v-text="firstStepErrors.EFNumber[0]"></div>
                                    </div>
                                    <div v-show="step == 27">
                                        <label for="EFNumber"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Enter Your
                                            FULL EF Number. EXAMPLE: EF123456<span class="text-red-400">*</span></label>
                                        <input v-model="form.EFNumber" type="text" id="EFNumber"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="EFNumber" required>
                                        <div v-if="firstStepErrors.EFNumber" class="text-red-500"
                                            v-text="firstStepErrors.EFNumber[0]"></div>
                                    </div>
                                    <div v-show="step == 28">
                                        <label for="EFNumber"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Enter Your
                                            FULL EF Number. EXAMPLE: EF123456<span class="text-red-400">*</span></label>
                                        <input v-model="form.EFNumber" type="text" id="EFNumber"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="EFNumber" required>
                                        <div v-if="firstStepErrors.EFNumber" class="text-red-500"
                                            v-text="firstStepErrors.EFNumber[0]"></div>
                                    </div>
                                    <div v-show="step == 29">
                                        <label for="EFNumber"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Enter Your
                                            FULL EF Number. EXAMPLE: EF123456<span class="text-red-400">*</span></label>
                                        <input v-model="form.EFNumber" type="text" id="EFNumber"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="EFNumber" required>
                                        <div v-if="firstStepErrors.EFNumber" class="text-red-500"
                                            v-text="firstStepErrors.EFNumber[0]"></div>
                                    </div>
                                    <div v-show="step == 30">
                                        <label for="EFNumber"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Enter Your
                                            FULL EF Number. EXAMPLE: EF123456<span class="text-red-400">*</span></label>
                                        <input v-model="form.EFNumber" type="text" id="EFNumber"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="EFNumber" required>
                                        <div v-if="firstStepErrors.EFNumber" class="text-red-500"
                                            v-text="firstStepErrors.EFNumber[0]"></div>
                                    </div>
                                    <div v-show="step == 31">
                                        <label for="EFNumber"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Enter Your
                                            FULL EF Number. EXAMPLE: EF123456<span class="text-red-400">*</span></label>
                                        <input v-model="form.EFNumber" type="text" id="EFNumber"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="EFNumber" required>
                                        <div v-if="firstStepErrors.EFNumber" class="text-red-500"
                                            v-text="firstStepErrors.EFNumber[0]"></div>
                                    </div>
                                    <div v-show="step == 32">
                                        <label for="EFNumber"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Enter Your
                                            FULL EF Number. EXAMPLE: EF123456<span class="text-red-400">*</span></label>
                                        <input v-model="form.EFNumber" type="text" id="EFNumber"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="EFNumber" required>
                                        <div v-if="firstStepErrors.EFNumber" class="text-red-500"
                                            v-text="firstStepErrors.EFNumber[0]"></div>
                                    </div>



                                    <div class=" flex justify-between mt-5">
                                        <a @click.prevent="Previous(step)"
                                            class="button-custom-back px-3 py-2 rounded-md flex items-center" href="#">

                                            Previous
                                        </a>
                                        <a @click.prevent="Next(step)"
                                            class="button-custom px-3 py-2 rounded-md flex items-center" href="#">
                                            Next

                                        </a>
                                    </div>


                                </div>
                            </form>



                            <!-- <label for="Email" class="block mb-2 text-sm font-black text-gray-900 ">Email<span
                                    class="text-red-500">*</span></label>
                            <input type="email" id="default-input"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                            <div v-if="firstStepErrors.email" class="text-red-500" v-text="firstStepErrors.email[0]"></div> -->
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </Transition>
</template>
