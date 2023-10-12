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
import { faTurkishLira } from "@fortawesome/free-solid-svg-icons";


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
const isLoading = ref(false);
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
let firstStepErrors = ref({});



let ChangeTab = () => {
    if(contractStep.value === 4 && page.props.auth.role === 'internal-agent'){
        router.visit('contract-steps')
    }else{
        contractStep.value += 1
    }
    
    var element = document.getElementById("modal_main_id");
    element.scrollIntoView();
}
let ChangeTabBack = () => {
    contractStep.value -= 1
}
let contactDetailData = ref(null)
let individual_business = ref(false)
let updateFormData = (val) => {
    isLoading.value = true
    contactDetailData.value = val.form
    individual_business.value = val.individual_business
    submit(1)
}

let legalFormDataStep1 = (data) => {
    isLoading.value = true
    legalFormData1.value = data
    submit(2)
}

let legalFormDataStep2 = (data) => {
    isLoading.value = true
    legalFormData2.value = data
    submit(3)
}

let AddressHistoryData = ref(null)
let AddressHistoryfun = (val) => {
    isLoading.value = true
    AddressHistoryData.value = val
    submit(4)
}

let additionalInfoD = ref(null)
let additionalInformation = (val) => {
    isLoading.value = true
    accompanying_sign.value = val.accompanying_sign
    additionalInfoD.value = val.form
    submit(5)
}

let uploadAmlPdf = ref(null)
let uploadPdfAml = (val) => {
    isLoading.value = true
    uploadAmlPdf.value = val.selectedFile
    form.value.aml_course = val.aml_course
    submit(6)
}

let uploadOmmisionPdf = ref(null)
let uploadPdfOmmision = (val) => {
    isLoading.value = true
    uploadOmmisionPdf.value = val.selectedFile
    form.value.omissions_insurance = val.omissions_insurance
    submit(7)
}

let uploadLicensePdf = ref(null)
let uploadLicense = (val) => {
    isLoading.value = true
    uploadLicensePdf.value = val
    submit(8)
}

let uploadBankingInfoPdf = ref(null)
let uploadBankingInfo = (val) => {
    isLoading.value = true
    uploadBankingInfoPdf.value = val
    submit(9)
}
let previewContract = () => {
    StepsModal.value = false
    contractModal.value = true
}

let errorHandle = (data, route) => {
    // console.log('data', data);
    if (data < 5) {
        ChangeTab()
    } else if (data < 9) {
        if (data === 5) {
            // console.log('route', route);
            router.visit(route)
            // axios.get(route)
            // .then((res)=>{
            //     console.log('res', res);
            // })
            // step.value = 2
        } else if (data === 6) {
            step.value = 3
        } else if (data === 7) {
            step.value = 4
        } else if (data === 8) {
            step.value = 5
        }
        contractStep.value = 0
    } else if (data === 9) {
        router.visit('contract-steps')
    }
}
if (props.userData?.internal_agent_contract) {
    if (props.userData.contract_step <= 5) {
        step.value = 1
        contractStep.value = props.userData.contract_step
    } else if (props.userData.contract_step <= 9) {
        contractStep.value = 0
        if (props.userData.contract_step === 6) {
            step.value = 2
        } else if (props.userData.contract_step === 7) {
            step.value = 3
        } else if (props.userData.contract_step === 8) {
            step.value = 4
        } else if (props.userData.contract_step === 9) {
            step.value = 5
        }
    } else if (props.userData.contract_step === 10) {
        StepsModal.value = false
        contractModal.value = true
    }
}

let signature_authorization = ref(null)
let signaturePreview = (val) => {
    isLoading.value = true
    signature_authorization.value = val
    submit(10)
}
let submit = (step) => {

    const requestData = {};
    if (step === 1) {
        Object.assign(requestData, contactDetailData.value);
        requestData.step = step;
        Object.assign(requestData, {
            individual_business: individual_business.value ? 1 : null, // Send 1 if true, 0 if false
        });

    } else if (step === 2) {
        Object.assign(requestData, legalFormData1.value);
        requestData.step = step;
    } else if (step === 3) {
        Object.assign(requestData, legalFormData2.value);
        requestData.step = step;
    } else if (step === 4) {
        const filteredAddressHistory = {};
        for (const key in AddressHistoryData.value) {
            if (key !== 'id' && AddressHistoryData.value[key].state !== 'Choose' && AddressHistoryData.value[key].address.trim() !== '') {
                filteredAddressHistory[key] = AddressHistoryData.value[key];
            }
        }
        Object.assign(requestData, filteredAddressHistory);
        requestData.step = step;
    } else if (step === 5) {
        requestData.accompanying_sign = accompanying_sign.value
        Object.assign(requestData, additionalInfoD.value);
        requestData.step = step;
    } else if (step === 6) {
        Object.assign(requestData, {
            aml_course: form.value.aml_course ? 1 : null, // Send 1 if true, 0 if false
        });
        requestData.uploadAmlPdf = uploadAmlPdf.value
        requestData.step = step;
    } else if (step === 7) {
        Object.assign(requestData, {
            omissions_insurance: form.value.omissions_insurance ? 1 : null, // Send 1 if true, 0 if false
        });
        requestData.uploadOmmisionPdf = uploadOmmisionPdf.value
        requestData.step = step;
    } else if (step === 8) {
        requestData.residentLicensePdf = uploadLicensePdf.value;
        requestData.step = step;
    } else if (step === 9) {
        requestData.bankingInfoPdf = uploadBankingInfoPdf.value;
        requestData.step = step;
    } else if (step === 10) {
        requestData.signature_authorization = signature_authorization.value
        requestData.step = step;
    }
    for (const key in requestData) {
        if (requestData.hasOwnProperty(key) && requestData[key] === 'Choose') {
            requestData[key] = null
        }
    }


    return axios
        .post("/internal-agent/registration-steps", requestData, {
            headers: {
                'Content-Type': 'multipart/form-data' // Set the content type to multipart/form-data
            }
        })
        .then((response) => {
            if (step === 10) {
                StepsModal.value = false
                contractModal.value = false
                toaster("success", response.data.message);
                router.visit("/dashboard");
            } else {
                errorHandle(step, response.data.route)
            }
            isLoading.value = false;
        })
        .catch((error) => {
            isLoading.value = false;
            if (error.response) {
                if (error.response.status === 400) {
                    // console.log(error.response.data.step);
                    firstStepErrors.value = error.response.data.errors;
                    isLoading.value = false;

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
                            Logout </Link>
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

                                <ContactDetail @updateFormData="updateFormData" @changeTab="ChangeTab()" :firstStepErrors="firstStepErrors"
                                    :states="states" :isLoading="isLoading"
                                    :userData="$page.props.auth.role === 'admin' ? userData.value : userData" />
                            </div>
                            <div v-show="contractStep === 2">
                                <LegalInformation :firstStepErrors="firstStepErrors" @changeTab="ChangeTab()"
                                    @legalFormDataStep1="legalFormDataStep1" @goback="ChangeTabBack()"
                                    :isLoading="isLoading"
                                    :userData="$page.props.auth.role === 'admin' ? userData.value : userData" />
                            </div>
                            <div v-show="contractStep === 3">
                                <LegalInformation2 :firstStepErrors="firstStepErrors" @changeTab="ChangeTab()"
                                    @legalFormDataStep2="legalFormDataStep2" :page="$page.props" @goback="ChangeTabBack()"
                                    :isLoading="isLoading"
                                    :userData="$page.props.auth.role === 'admin' ? userData.value : userData" />
                            </div>
                            <div v-show="contractStep === 4">
                                <AddressHistory @addRessHistory="AddressHistoryfun" @changeTab="ChangeTab()"
                                    @goback="ChangeTabBack()" :states="states" :isLoading="isLoading"
                                    :userData="$page.props.auth.role === 'admin' ? userData.value : userData" />
                            </div>
                            <div v-show="contractStep === 5">
                                <AdditionalInfo @additionalInfoData="additionalInformation"
                                    :firstStepErrors="firstStepErrors" :page="$page.props" :states="states"
                                    @changeTab="NextStep()" :isLoading="isLoading" @goback="ChangeTabBack()"
                                    :userData="$page.props.auth.role === 'admin' ? userData.value : userData" />
                            </div>
                            <div v-show="step === 2" class="pt-6">
                                <AmLCourse :firstStepErrors="firstStepErrors" :isLoading="isLoading"
                                    @uploadPdfAml="uploadPdfAml" @changeTab="NextStep()" @goback="goBack()"
                                    :userData="$page.props.auth.role === 'admin' ? userData.value : userData" />
                            </div>

                            <div v-show="step === 3" class="pt-6">
                                <ErrorsAndEmissions :firstStepErrors="firstStepErrors" :isLoading="isLoading"
                                    @uploadPdfOmmision="uploadPdfOmmision" @changeTab="NextStep()" @goback="goBack()"
                                    :userData="$page.props.auth.role === 'admin' ? userData.value : userData" />
                            </div>

                            <div v-show="step === 4">
                                <UploadLicence @uploadLicense="uploadLicense" :isLoading="isLoading"
                                    :firstStepErrors="firstStepErrors" @changeTab="NextStep()" @goback="goBack()"
                                    :userData="$page.props.auth.role === 'admin' ? userData.value : userData" />
                            </div>
                            <div v-show="step === 5">
                                <BankInformationUpload @uploadBankingInfo="uploadBankingInfo"
                                    :firstStepErrors="firstStepErrors" @submit="previewContract" :isLoading="isLoading"
                                    @goback="goBack()"
                                    :userData="$page.props.auth.role === 'admin' ? userData.value : userData" />
                            </div>
                            <!-- <button @click="previewContract">show Contract</button> -->
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
                            Logout</Link>

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
                            <ContractDetailPage  :userData="userData" />
                            <SingnaturePad :page="$page.props" :userData="userData" @editContract="editContract"
                                :firstStepErrors="firstStepErrors" :isLoading="isLoading" @signature="signaturePreview" />
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </AuthenticatedLayout>
</template>


