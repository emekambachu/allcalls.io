<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref, reactive, defineEmits, onMounted, watch, computed } from "vue";
import InputError from "@/Components/InputError.vue";
import { Head, Link, useForm, usePage, router } from "@inertiajs/vue3";
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
import ContractDetailPage from '@/Pages/InternalAgent/ContractDetailPage.vue'
import SingnaturePad from '@/Pages/InternalAgent/SingnaturePad.vue'

import { toaster } from "@/helper.js";
import { triggerRef } from "vue";


let page = usePage();
if (page.props.flash.message) {
    toaster("success", page.props.flash.message);
}
let props = defineProps({
    userData: Object,
    states: Array,
});

// console.log('userData', props.userData.value);
let StepsModal = ref(true)
let contractModal = ref(false)

let form = ref({
    aml_course: false,
    omissions_insurance: false
});

let step = ref(1);
let contractStep = ref(1);
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
let legalFormData1 = ref(null);
let legalFormData2 = ref(null);
let accompanying_sign = ref(null);
let ChangeTab = (data) => {
    if (data && contractStep.value === 2) {
        legalFormData1.value = data
    }
    if (data && contractStep.value === 3) {
        accompanying_sign.value = data.accompanying_sign
        legalFormData2.value = data.form
    }
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
}

// Use an object to store the legal form data

let uploadAmlPdf = ref(null)
let uploadOmmisionPdf = ref(null)
// Function to update the legal form data



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
    uploadAmlPdf.value = val.selectedFile
    form.value.aml_course = val.aml_course
}
let uploadPdfOmmision = (val) => {
    uploadOmmisionPdf.value = val.selectedFile
    form.value.omissions_insurance = val.omissions_insurance
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






let previewData = ref(null)



let previewContract = () => {
    const requestData = {};

    const filteredAddressHistory = {};
    for (const key in AddressHistoryData.value) {
        if (key !== 'id' && AddressHistoryData.value[key].state !== 'Choose' && AddressHistoryData.value[key].address.trim() !== '') {
            filteredAddressHistory[key] = AddressHistoryData.value[key];
        }
    }

    // console.log('AddressHistoryData.value', AddressHistoryData.value);
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
    requestData.accompanying_sign = accompanying_sign.value


    for (const key in requestData) {
        if (requestData.hasOwnProperty(key) && requestData[key] === 'Choose') {
            requestData[key] = null
        }
    }
    previewData.value = requestData
    StepsModal.value = false
    contractModal.value = true

}
let signature_authorization = ref(null)
let signaturePreview = (val) => {
    isLoading.value = true
    signature_authorization.value = val
    submit()
}
let submit = () => {

    const requestData = {};

    const filteredAddressHistory = {};
    for (const key in AddressHistoryData.value) {
        if (key !== 'id' && AddressHistoryData.value[key].state !== 'Choose' && AddressHistoryData.value[key].address.trim() !== '') {
            filteredAddressHistory[key] = AddressHistoryData.value[key];
        }
    }

    // console.log('AddressHistoryData.value', AddressHistoryData.value);
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
    requestData.accompanying_sign = accompanying_sign.value
    requestData.signature_authorization = signature_authorization.value


    for (const key in requestData) {
        if (requestData.hasOwnProperty(key) && requestData[key] === 'Choose') {
            requestData[key] = null
        }
    }


    isLoading.value = true;

    return axios
        .post("/internal-agent/registration-steps", requestData, {
            headers: {
                'Content-Type': 'multipart/form-data' // Set the content type to multipart/form-data
            }
        })
        .then((response) => {
            // props.userData.value = {}
            // // console.log('what is res', response.data.contractData);
            // StepsModal.value = false
            // contractModal.value = true
            toaster("success", response.data.message);
            router.visit("/dashboard");
            isLoading.value = false;
        })
        .catch((error) => {
            isLoading.value = false;
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
let editContract = () => {
    contractModal.value = false
    StepsModal.value = true
    step.value = 1
    contractStep.value = 1
}

</script>
<style >
.container {
    width: "100%";
    padding: 8px 16px;
}

.buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
    margin-top: 8px;
}

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
                        <div class="flex justify-end">
                            <Link v-show="$page.props.auth.role != 'admin'" :href="route('logout')" method="post"
                                as="button"
                                class="underline text-sm text-gray-600 mr-5 mt-5  dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            Log Out</Link>
                            <button v-show="$page.props.auth.role === 'admin'" @click="close" type="button"
                                class="text-gray-400 bg-transparent mr-2 mt-2 hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center"
                                data-modal-hide="defaultModal">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>

                        <div class="px-12 py-2">

                            <Tabs :step="step" />

                            <div v-show="contractStep === 1" class="">

                                <ContactDetail @updateFormData="updateFormData" :firstStepErrors="firstStepErrors"
                                    @changeTab="ChangeTab" :states="states"
                                    :userData="$page.props.auth.role === 'admin' ? userData.value : userData" />
                            </div>
                            <div v-show="contractStep === 2">
                                <LegalInformation  :firstStepErrors="firstStepErrors"
                                    @changeTab="ChangeTab" @goback="ChangeTabBack()"
                                    :userData="$page.props.auth.role === 'admin' ? userData.value : userData" />
                            </div>
                            <div v-show="contractStep === 3">
                                <LegalInformation2  :firstStepErrors="firstStepErrors"
                                    @changeTab="ChangeTab" :page="$page.props" @goback="ChangeTabBack()"
                                    :userData="$page.props.auth.role === 'admin' ? userData.value : userData" />
                            </div>
                            <div v-show="contractStep === 4">
                                <AddressHistory @addRessHistory="AddressHistoryfun" @changeTab="ChangeTab()"
                                    @goback="ChangeTabBack()" :states="states"
                                    :userData="$page.props.auth.role === 'admin' ? userData.value : userData" />
                            </div>
                            <div v-show="contractStep === 5">
                                <AdditionalInfo @additionalInfoData="additionalInformation"
                                    :firstStepErrors="firstStepErrors" :states="states" @changeTab="NextStep()"
                                    @goback="ChangeTabBack()"
                                    :userData="$page.props.auth.role === 'admin' ? userData.value : userData" />
                            </div>
                            <div v-show="step === 2" class="pt-6">
                                <AmLCourse :firstStepErrors="firstStepErrors" @uploadPdfAml="uploadPdfAml"
                                    @changeTab="NextStep()" @goback="goBack()"
                                    :userData="$page.props.auth.role === 'admin' ? userData.value : userData" />
                            </div>

                            <div v-show="step === 3" class="pt-6">
                                <ErrorsAndEmissions :firstStepErrors="firstStepErrors"
                                    @uploadPdfOmmision="uploadPdfOmmision" @changeTab="NextStep()" @goback="goBack()"
                                    :userData="$page.props.auth.role === 'admin' ? userData.value : userData" />
                            </div>

                            <div v-show="step === 4">
                                <UploadLicence @uploadLicense="uploadLicense" :firstStepErrors="firstStepErrors"
                                    @changeTab="NextStep()" @goback="goBack()"
                                    :userData="$page.props.auth.role === 'admin' ? userData.value : userData" />
                            </div>
                            <div v-show="step === 5">
                                <BankInformationUpload @uploadBankingInfo="uploadBankingInfo"
                                    :firstStepErrors="firstStepErrors" @submit="previewContract" :isLoading="isLoading"
                                    @goback="goBack()"
                                    :userData="$page.props.auth.role === 'admin' ? userData.value : userData" />
                            </div>
                            <button @click="previewContract">show Contract</button>
                            <!-- <vueSignature /> -->
                        </div>
                    </div>
                </div>
            </div>

        </Transition>
        <Transition name="modal" enter-active-class="transition ease-out  duration-300 transform"
            enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to-class="opacity-100 translate-y-0 sm:scale-100"
            leave-active-class="transition ease-in duration-200 transform"
            leave-from-class="opacity-100 translate-y-0 sm:scale-100"
            leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

            <div id="defaultModal" v-if="contractModal" tabindex="-1"
                class="flex items-center justify-center fixed inset-0 z-50 w-full h-full overflow-x-hidden overflow-y-auto max-h-full mx-4 sm:mx-0">

                <div class="fixed inset-0 bg-black opacity-90 blurred-overlay"></div>
                <!-- This is the overlay -->
                <div style="width: 75%;" class="relative w-full py-10  max-h-full mx-auto" id="modal_main_id">
                    <div class="relative bg-white rounded-lg shadow-lg ">
                        <div class="flex justify-end">
                            <Link v-show="$page.props.auth.role != 'admin'" :href="route('logout')" method="post"
                                as="button"
                                class="underline text-sm text-gray-600 mr-5 mt-5  dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            Log Out</Link>
                            
                            <button v-show="$page.props.auth.role === 'admin'" @click="close" type="button"
                                class="text-gray-400 bg-transparent mr-2 mt-2 hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center"
                                data-modal-hide="defaultModal">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>

                        <div class="px-12 py-2">
                            <ContractDetailPage :previewData="previewData" />
                            <SingnaturePad :userData="userData" @editContract="editContract"  :isLoading="isLoading" @signature="signaturePreview" />
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </AuthenticatedLayout>
</template>


