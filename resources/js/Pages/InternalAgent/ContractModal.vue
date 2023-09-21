<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed } from "vue";
import GuestInputLabel from "@/Components/GuestInputLabel.vue";
import Multiselect from "@vueform/multiselect";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import { router } from "@inertiajs/vue3";
import { Head, Link, useForm } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import StepTwo from "@/Pages/InternalAgent/StepTwo.vue";
import Tabs from '@/Pages/InternalAgent/Tabs.vue'
import LegalInformation from '@/Pages/InternalAgent/LegalInformation.vue'
import LegalInformation2 from '@/Pages/InternalAgent/LegalInformation2.vue'
import AddressHistory from '@/Pages/InternalAgent/AddressHistory.vue'
import AdditionalInfo from '@/Pages/InternalAgent/AdditionalInfo.vue'


import { toaster } from "@/helper.js";
let props = defineProps({
    StepsModal: {
        type: Boolean,
    },

    callTypes: Array,
    states: Array,
});

let form = useForm({
    aml_course:false,
});

let step = ref(1);
let contractStep = ref(1);
let ContactDetail = ref('Contact Details')
let selectedTypes = ref([]);
let emit = defineEmits(["close"]);
let close = () => {
    emit("close");
};
let termsAndConditons = ref(true);
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
let TermAndConditons = (event) => {
    if (event.target.checked) {
        termsAndConditons.value = false;
    } else {
        termsAndConditons.value = true;
    }
};
let amlCourseRead = ref(false)
watch(form, (newVal, oldVal) => {
    if(newVal.aml_course == true){
        amlCourseRead.value = false 
    }else{
        amlCourseRead.value = true 
    }
   
})
let NextStep = () => {
    step.value += 1;
    contractStep.value = 0
    if(step.value === 2 && form.aml_course === false){
        amlCourseRead.value = true
    }else{
        amlCourseRead.value = false
    }
};
let goBack = () => {
    if (step.value === 2) {
        contractStep.value = 5
    }
    step.value -= 1;
    if(step.value === 2 && form.aml_course === false){
        amlCourseRead.value = true
    }else{
        amlCourseRead.value = false
    }

};

let main_heading = ref('New Producer Information');
let ChangeTab = () => {
    contractStep.value += 1
    if (contractStep.value === 1) {
        main_heading.value = 'New Producer Information'
    } else if (contractStep.value === 2 || contractStep.value === 3) {
        main_heading.value = 'Legal Questions'
    } else if (contractStep.value === 4) {
        main_heading.value = 'Address History'
    } else if (contractStep.value === 5) {
        main_heading.value = 'Additional Information'
    }
}
let ChangeTabBack = () => {
    contractStep.value -= 1
    if (contractStep.value === 1) {
        main_heading.value = 'New Producer Information'
    } else if (contractStep.value === 2 || contractStep.value === 3) {
        main_heading.value = 'Legal Questions'
    } else if (contractStep.value === 4) {
        main_heading.value = 'Address History'
    } else if (contractStep.value === 5) {
        main_heading.value = 'Additional Information'
    }
}
if (contractStep.value === 1) {
    main_heading.value = 'New Producer Information'
} else if (contractStep.value === 2 || contractStep.value === 3) {
    main_heading.value = 'Legal Questions'
} else if (contractStep.value === 4) {
    main_heading.value = 'Address History'
} else if (contractStep.value === 5) {
    main_heading.value = 'Additional Information'
}


const isLoading = ref(false);
let firstStepErrors = ref({});

let contactDetail = ref()
let updateFormData = (val) => {
    contactDetail.value = val
}

// Use an object to store the legal form data
const legalFormData = ref({});

// Define a computed property to get the merged data
const mergedData = computed(() => {
  return {
    ...legalFormData.value,
  };
});

// Function to update the legal form data
const updateLegalFormData = (val) => {
  // Merge the new data with the existing data
  legalFormData.value = {
    ...legalFormData.value,
    ...val,
  };
  console.log('what merge data', mergedData.value);
};
let AddressHistoryData = ref()
let AddressHistoryfun = (val) => {
    AddressHistoryData.value = val
}
let additionalInfoD = ref()
let additionalInformation = (val) => {
    additionalInfoD.value = val
    console.log('new values', additionalInfoD.value);
}

let submit = () => {
    isLoading.value = true;

    return axios
        .post("/internal-agent/register-steps", {
            aml_course:form.aml_course,
            contactDetail:contactDetail.value,
            contact_detail:mergedData.value,
            AddressHistoryData:AddressHistoryData.value,
            additionalInfoD:additionalInfoD.value
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

.iframe-cls {
    /* box-shadow: rgba(6, 24, 44, 0.4) 0px 0px 0px 2px, rgba(6, 24, 44, 0.65) 0px 4px 6px -1px, rgba(255, 255, 255, 0.08) 0px 1px 0px inset; */
    border: 5px solid black;
}
</style>
<template>
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
            <div style="width: 75%;" class="relative w-full py-10  max-h-full mx-auto">
                <div class="relative bg-white rounded-lg shadow-lg ">
                    <div class="px-12 py-2">

                        <Tabs :step="step" />



                        <h1 v-show="step === 1" style="background-color: #134576;"
                            class="mb-4	text-center rounded-md py-2 text-white">
                            {{ main_heading }}
                        </h1>

                        <div v-show="contractStep === 1" class="">
                            <StepTwo @updateFormData="updateFormData" />
                        </div>
                        <div v-show="contractStep === 2">
                            <LegalInformation @updateFormData="updateLegalFormData" />
                        </div>
                        <div v-show="contractStep === 3">
                            <LegalInformation2 @updateFormData="updateLegalFormData" />
                        </div>
                        <div v-show="contractStep === 4">
                            <AddressHistory @addRessHistory="AddressHistoryfun" />
                        </div>
                        <div v-show="contractStep === 5">
                            <AdditionalInfo @additionalInfoData="additionalInformation" />
                        </div>
                        <div v-show="step === 2" class="pt-6">
                            <h1 style="background-color: #134576;" class="mb-4	text-center rounded-md py-2 text-white">
                                AML Course 
                            </h1>
                            <div class="iframe-cls">
                                <iframe 
                                    src="https://www.financialservicecareers.com/_files/ugd/0fb1f5_0a18cb8e43734547b1c42be4c1a0a52b.pdf"
                                    width="100%" height="500px" frameborder="0"></iframe>
                            </div>

                            <div class="flex justify-between my-5">
                                <div></div>
                                <div>
                                    <input id="link-checkbox" v-model="form.aml_course" type="checkbox" value=""
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="link-checkbox"
                                        class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">I have completed the AML course.</label>
                                </div>
                            </div>

                        </div>
                        <div v-show="step === 3" class="pt-6">
                            <h1 style="background-color: #134576;" class="mb-4	text-center rounded-md py-2 text-white">
                                Errors and Omissions Insurances
                            </h1>
                                    <iframe src="https://mga-eo.com/apply/nd/lh-eo?_ga=2.22742075.1083085069.1638818057-1601577075.1638818057" width="100%" height="500px" frameborder="0"></iframe>
                        </div>

                       

                        <div class="px-5 pb-6">
                            <div class="flex justify-between flex-wrap">
                                <div class="mt-4">
                                    <a v-show="step > 1 " href="#" @click.prevent="goBack"
                                        class="button-custom-back px-3 py-2 rounded-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                                        </svg>
                                        Step Back</a>
                                    <a v-show="step != 2 && contractStep != 6 && contractStep != 1 && contractStep != 0"
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
                                    <button :class="{ 'opacity-25': amlCourseRead  }" :disabled="amlCourseRead" v-show="contractStep === 5 || step > 1 && step != 5 " type="button" @click.prevent="NextStep"
                                        class="button-custom px-3 py-2 rounded-md">
                                        Next Step
                                    </button>
                                    <button v-show="contractStep != 5 && step === 1" type="button"
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>
