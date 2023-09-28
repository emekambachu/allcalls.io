<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref, reactive, defineEmits, onMounted, watch, computed } from "vue";
import InputError from "@/Components/InputError.vue";
import { router } from "@inertiajs/vue3";
import { Head, Link, useForm } from "@inertiajs/vue3";
import ContactDetail from "@/Pages/InternalAgent/ContactDetail.vue";
import Tabs from '@/Pages/InternalAgent/Tabs.vue'
import LegalInformation from '@/Pages/InternalAgent/LegalInformation.vue'
import LegalInformation2 from '@/Pages/InternalAgent/LegalInformation2.vue'
import AddressHistory from '@/Pages/InternalAgent/AddressHistory.vue'
import AdditionalInfo from '@/Pages/InternalAgent/AdditionalInfo.vue'
import UploadLicence from '@/Pages/InternalAgent/UploadLicence.vue'
import BankInformationUpload from '@/Pages/InternalAgent/BankInformationUpload.vue'
import AmLCourse from '@/Pages/InternalAgent/AmLCourse.vue'
import ErrorsAndEmissions from '@/Pages/InternalAgent/ErrorsAndEmissions.vue'


import { toaster } from "@/helper.js";
let props = defineProps({
    userData: Object,
    states: Array,
});
let StepsModal = ref(true)
let form = ref({
    aml_course: false,
    omissions_insurance: false
});

let step = ref(1);
let contractStep = ref(4);
let emit = defineEmits(["close"]);
let close = () => {
    emit("close");
};


let NextStep = () => {
    var element = document.getElementById("modal_main_id");
    element.scrollIntoView();
    step.value += 1;
    contractStep.value = 0
};
let goBack = () => {

    if (step.value === 2) {
        contractStep.value = 5
    }
    step.value -= 1;


};

let ChangeTab = () => {
    contractStep.value += 1
    var element = document.getElementById("modal_main_id");
    element.scrollIntoView();
}
let ChangeTabBack = () => {
    contractStep.value -= 1
}



const isLoading = ref(false);
let firstStepErrors = ref({});

let contactDetailData = ref(null)
let updateFormData = (val) => {
    contactDetailData.value = val
    // console.log('contactDetailData', contactDetailData.value);
}

// Use an object to store the legal form data
const legalFormData1 = ref(null);
const legalFormData2 = ref(null);
let uploadAmlPdf = ref(null)
let uploadOmmisionPdf = ref(null)
// Function to update the legal form data

const updateLegalFormData1 = (val) => {
    legalFormData1.value = val
};
const updateLegalFormData2 = (val) => {
    legalFormData2.value = val
};
let AddressHistoryData = ref(null)
let AddressHistoryfun = (val) => {
    AddressHistoryData.value = val
}
let additionalInfoD = ref(null)
let additionalInformation = (val) => {
    additionalInfoD.value = val
    // console.log('new values', additionalInfoD.value);
}
let uploadLicensePdf = ref(null)
let uploadLicense = (val) => {
    uploadLicensePdf.value = val
}
let uploadBankingInfoPdf = ref(null)
let uploadBankingInfo = (val) => {
    uploadBankingInfoPdf.value = val
}

let uploadPdfAml = (val) => {
    uploadAmlPdf.value = val
    // console.log('uploadPdfAml', val);
}
let uploadPdfOmmision = (val) => {
    uploadOmmisionPdf.value = val
    // console.log('parent uploadPdfOmmision', val);
}
let errorHandle = (data) => {
    const stepMapping = {
        1: { contractStep: data.step, step: 1 },
        2: { contractStep: data.step, step: 1 },
        3: { contractStep: data.step, step: 1 },
        4: { contractStep: 5, step: 1 },
        5: { contractStep: 6, step: 2 },
        6: { contractStep: 6, step: 3 },
        7: { contractStep: 6, step: 4 },
        8: { contractStep: 6, step: 5 },
    };

    if (stepMapping.hasOwnProperty(data.step)) {
        const { contractStep: newContractStep, step: newStep } = stepMapping[data.step];
        contractStep.value = newContractStep;
        step.value = newStep;
    }

}



let submit = () => {

    const requestData = {};

    const filteredAddressHistory = {};
    for (const key in AddressHistoryData.value) {
        if (key !== 'id' && AddressHistoryData.value[key].state !== 'Choose' && AddressHistoryData.value[key].address.trim() !== '') {
            filteredAddressHistory[key] = AddressHistoryData.value[key];
        }
    }

    console.log('AddressHistoryData.value', AddressHistoryData.value);
    Object.assign(requestData, {
        aml_course: form.value.aml_course ? 1 : null, // Send 1 if true, 0 if false
        omissions_insurance: form.value.omissions_insurance ? 1 : null, // Send 1 if true, 0 if false
    });
    Object.assign(requestData, contactDetailData.value);
    Object.assign(requestData, legalFormData1.value);
    Object.assign(requestData, legalFormData2.value);
    Object.assign(requestData, filteredAddressHistory);
    Object.assign(requestData, additionalInfoD.value);
    requestData.residentLicensePdf = uploadLicensePdf.value;
    requestData.bankingInfoPdf = uploadBankingInfoPdf.value;
    requestData.uploadAmlPdf = uploadAmlPdf.value
    requestData.uploadOmmisionPdf = uploadOmmisionPdf.value


    for (const key in requestData) {
        if (requestData.hasOwnProperty(key) && requestData[key] === 'Choose') {
            requestData[key] = null
        }
    }
    console.log(requestData);
    return
    isLoading.value = true;

    return axios
        .post("/internal-agent/registration-steps", requestData, {
            headers: {
                'Content-Type': 'multipart/form-data' // Set the content type to multipart/form-data
            }
        })
        .then((response) => {
            close();
            toaster("success", response.data.message);
            router.visit("/dashboard");
            isLoading.value = false;
        })
        .catch((error) => {
            if (error.response) {
                if (error.response.status === 400) {
                    // console.log(error.response.data.step);
                    firstStepErrors.value = error.response.data.errors;
                    isLoading.value = false;
                    errorHandle(error.response.data)
                } else {
                    console.log("Other errors", error.response.data);
                }
            } else if (error.request) {
                console.log("No response received", error.request);
            } else {
                console.log("Error", error.message);
            }
        });
};


</script>
<style >
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type=number] {
    -moz-appearance: textfield;
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

.iframe-cls {
    /* box-shadow: rgba(6, 24, 44, 0.4) 0px 0px 0px 2px, rgba(6, 24, 44, 0.65) 0px 4px 6px -1px, rgba(255, 255, 255, 0.08) 0px 1px 0px inset; */
    border: 5px solid black;
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

            <div id="defaultModal" v-show="StepsModal" tabindex="-1"
                class="flex items-center justify-center fixed inset-0 z-50 w-full h-full overflow-x-hidden overflow-y-auto max-h-full mx-4 sm:mx-0">

                <div class="fixed inset-0 bg-black opacity-90 blurred-overlay"></div>
                <!-- This is the overlay -->
                <div style="width: 75%;" class="relative w-full py-10  max-h-full mx-auto" id="modal_main_id">
                    <div class="relative bg-white rounded-lg shadow-lg ">
                        <div  class="flex justify-end">
                            <Link :href="route('logout')" method="post" as="button"
                                class="underline text-sm text-gray-600 mr-5 mt-5  dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            Log Out</Link>
                        </div>

                        <div class="px-12 py-2">

                            <Tabs :step="step" />

                            <div v-show="contractStep === 1" class="">
                                <ContactDetail @updateFormData="updateFormData" :firstStepErrors="firstStepErrors"
                                    @changeTab="ChangeTab()" :states="states" :userData="userData" />
                            </div>
                            <div v-show="contractStep === 2">
                                <LegalInformation @updateFormData="updateLegalFormData1" :firstStepErrors="firstStepErrors"
                                    @changeTab="ChangeTab()" @goback="ChangeTabBack()" />
                            </div>
                            <div v-show="contractStep === 3">
                                <LegalInformation2 @updateFormData="updateLegalFormData2" :firstStepErrors="firstStepErrors"
                                    @changeTab="ChangeTab()" @goback="ChangeTabBack()" />
                            </div>
                            <div v-show="contractStep === 4">
                                <AddressHistory @addRessHistory="AddressHistoryfun" @changeTab="ChangeTab()"
                                    @goback="ChangeTabBack()" :states="states" />
                            </div>
                            <div v-show="contractStep === 5">
                                <AdditionalInfo @additionalInfoData="additionalInformation"
                                    :firstStepErrors="firstStepErrors" :states="states" @changeTab="NextStep()"
                                    @goback="ChangeTabBack()" />
                            </div>
                            <div v-show="step === 2" class="pt-6">
                                <AmLCourse :firstStepErrors="firstStepErrors" @uploadPdfAml="uploadPdfAml"
                                    @changeTab="NextStep()" @goback="goBack()" />
                                <!-- <h1 style="background-color: #134576;" class="mb-4	text-center rounded-md py-2 text-white">
                                    AML Course
                                </h1>

                                <div class="bg-blue-50 py-10 px-6 rounded-lg shadow-md">
                                    <div class="mb-4">
                                        <a target="_blank"
                                            href="https://www.financialservicecareers.com/_files/ugd/0fb1f5_0a18cb8e43734547b1c42be4c1a0a52b.pdf">
                                            <strong class="text-blue-600 mr-1 hover:underline">Detailed PDF Guide</strong>
                                        </a>outlining the required steps within the AML
                                        course.
                                    </div>
                                    <div class="mb-4">
                                        <a target="_blank" href="https://secure.reged.com/Login/vu/VirtualUniversity/EQUIS">
                                            <strong class="text-blue-600 mr-1  hover:underline">Click Here</strong>
                                        </a> <span>for the registration and course completion</span>
                                    </div>
                                    <div class="text-gray-600">
                                        Please download PDF for course completion after completing the course.
                                    </div>
                                </div>
                                <div class="flex justify-between my-5">
                                    <div></div>
                                    <div>
                                        <input id="link-checkbox" v-model="form.aml_course" type="checkbox" value=""
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="link-checkbox"
                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">I have
                                            completed
                                            the AML course.<span class="text-red-500 ">*</span></label>
                                    </div>
                                </div>
                                <div v-if="firstStepErrors.aml_course" class="text-red-500"
                                    v-text="firstStepErrors.aml_course[0]"></div>
                                <div class="px-5 pb-6">
                                    <div class="flex justify-between flex-wrap">
                                        <div class="mt-4">

                                            <button type="button" @click.prevent="goBack"
                                                class="button-custom-back px-3 py-2 rounded-md">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                                                </svg>
                                                Back
                                            </button>
                                        </div>
                                        <div class="mt-4">
                                            <button type="button" :class="{ 'opacity-25': amlCourseRead }"
                                                :disabled="amlCourseRead" @click.prevent="NextStep"
                                                class="button-custom px-3 py-2 rounded-md">
                                                Next Step
                                            </button>

                                        </div>
                                    </div>
                                </div> -->
                            </div>

                            <div v-show="step === 3" class="pt-6">
                                <ErrorsAndEmissions :firstStepErrors="firstStepErrors"
                                    @uploadPdfOmmision="uploadPdfOmmision" @changeTab="NextStep()" @goback="goBack()" />
                                <!-- <h1 style="background-color: #134576;" class="mb-4	text-center rounded-md py-2 text-white">
                                    Errors and Omissions Insurances
                                </h1>
                                <div class="bg-blue-50 py-10 px-6 rounded-lg shadow-md">
                                    <div class="text-gray-600 mb-4">
                                        Complete the sign-up process and apply for Errors and Omissions Insurance.
                                    </div>
                                    <div class="mb-4">
                                        <a target="_blank"
                                            href="https://mga-eo.com/apply/nd/lh-eo?_ga=2.22742075.1083085069.1638818057-1601577075.1638818057">
                                            <strong class="text-blue-600 mr-1 hover:underline">MGA E&O Insurance Application
                                            </strong>
                                        </a>for registration and application.
                                    </div>
                                </div>
                                <div class="flex justify-between my-5">
                                    <div></div>
                                    <div>
                                        <input id="link-omissions_insurance" v-model="form.omissions_insurance"
                                            type="checkbox" value=""
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="link-omissions_insurance"
                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Errors and
                                            Omissions Insurances.<span class="text-red-500 ">*</span></label>
                                    </div>
                                </div>
                                <div v-if="firstStepErrors.omissions_insurance" class="text-red-500"
                                    v-text="firstStepErrors.omissions_insurance[0]"></div>
                                <div class="px-5 pb-6">
                                    <div class="flex justify-between flex-wrap">
                                        <div class="mt-4">

                                            <button type="button" @click.prevent="goBack"
                                                class="button-custom-back px-3 py-2 rounded-md">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                                                </svg>
                                                Back
                                            </button>
                                        </div>
                                        <div class="mt-4">
                                            <button type="button" :class="{ 'opacity-25': omissionsInsurance }"
                                                :disabled="omissionsInsurance" @click.prevent="NextStep"
                                                class="button-custom px-3 py-2 rounded-md">
                                                Next Step
                                            </button>

                                        </div>
                                    </div>
                                </div> -->
                            </div>

                            <div v-show="step === 4">
                                <UploadLicence @uploadLicense="uploadLicense" :firstStepErrors="firstStepErrors"
                                    @changeTab="NextStep()" @goback="goBack()" />
                            </div>
                            <div v-show="step === 5">
                                <BankInformationUpload @uploadBankingInfo="uploadBankingInfo"
                                    :firstStepErrors="firstStepErrors" @submit="submit()" @goback="goBack()" />
                            </div>



                            <!-- <div class="px-5 pb-6">
                                <div class="flex justify-between flex-wrap">
                                    <div class="mt-4">
                                        <a v-show="step > 1" href="#" @click.prevent="goBack"
                                            class="button-custom-back px-3 py-2 rounded-md">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                                            </svg>
                                            Step Back</a>
                                        <a v-show="step != 2 && contractStep != 6 && contractStep != 1 && contractStep != 0 && contractStep != 4"
                                            href="#" @click.prevent="ChangeTabBack"
                                            class="button-custom-back px-3 py-2 rounded-md">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                                            </svg>
                                            Back
                                        </a>
                                    </div>
                                    <div class="mt-4">
                                        <button :class="{ 'opacity-25': amlCourseRead }" :disabled="amlCourseRead"
                                            v-show="contractStep === 5 || step > 1 && step != 5 && step != 3" type="button"
                                            @click.prevent="NextStep" class="button-custom px-3 py-2 rounded-md">
                                            Next Step
                                        </button>
                                        <button :class="{ 'opacity-25': omissionsInsurance }" :disabled="omissionsInsurance"
                                            v-show="contractStep != 5 && step != 5 && step === 3" type="button"
                                            @click.prevent="NextStep" class="button-custom px-3 py-2 rounded-md">
                                            Next Step
                                        </button>
                                        <button v-show="contractStep != 5 && step === 1 && contractStep != 4" type="button"
                                            @click.prevent="ChangeTab" class="button-custom px-3 py-2 rounded-md">
                                            Next
                                        </button>
                                        <button @click="submit" type="button" v-show="step === 5"
                                            class="button-custom px-3 py-2 rounded-md"
                                            :class="{ 'opacity-25': areAllArraysEmpty || isLoading }"
                                            :disabled="areAllArraysEmpty || isLoading">
                                            <global-spinner :spinner="isLoading" /> Register
                                        </button>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </AuthenticatedLayout>
</template>
