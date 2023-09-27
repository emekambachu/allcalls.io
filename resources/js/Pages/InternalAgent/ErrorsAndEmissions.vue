<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed } from "vue";
import UploadPdfFile from "@/Components/UploadPdfFile.vue";
let emits = defineEmits()
let props = defineProps({
    firstStepErrors: Object,
});
let form = ref({
    omissions_insurance: false,
});
let omissionsInsurance = ref(true)
const selectedFile = ref(null)
watch(form.value, (newVal, oldVal) => {
    if (newVal.omissions_insurance == true) {
        omissionsInsurance.value = false
    } else {
        omissionsInsurance.value = true
    }

})
let ChangeTab = () => {
    for (const key in props.firstStepErrors) {
        if (props.firstStepErrors.hasOwnProperty(key)) {
            props.firstStepErrors[key] = [];
        }
    }
   
    if(!selectedFile.value){
        props.firstStepErrors.omissions_insurance = [`The Omissions Insurances certificate is required.`];
    }else{
        emits("uploadPdfOmmision" , selectedFile.value);
        emits("changeTab");
    }
}

const fileError = ref(false);
const selectedFileName = ref(""); // To store the selected file name

const handleFileChange = (event) => {
    const files = event.target.files;
    selectedFile.value = null
    if (files.length === 1 && files[0].type === "application/pdf") {
        // Handle the single PDF file here
        fileError.value = false; // Reset the error message
        selectedFileName.value = files[0].name; // Set the selected file name
        selectedFile.value = files[0]
        if(props.firstStepErrors.omissions_insurance){
            props.firstStepErrors.omissions_insurance = null
        }
    } else if (files.length > 1) {
        // Display an error message for multiple files
        fileError.value = true;
        selectedFileName.value = ""; // Clear the selected file name
        props.firstStepErrors.omissions_insurance = [];
        selectedFile.value = null
    } else {
        // Display an error message for invalid file type
        fileError.value = true;
        selectedFileName.value = ""; // Clear the selected file name
        props.firstStepErrors.omissions_insurance = [];
        selectedFile.value = null
    }
};

const handleDrop = (event) => {
    event.preventDefault();
    const files = event.dataTransfer.files;
    if (files.length === 1 && files[0].type === "application/pdf") {
        // Handle the dropped single PDF file here
        fileError.value = false; // Reset the error message
        selectedFileName.value = files[0].name; // Set the selected file name
        selectedFile.value = files[0]
        if(props.firstStepErrors.omissions_insurance){
            props.firstStepErrors.omissions_insurance = null
        }
    } else if (files.length > 1) {
        // Display an error message for multiple files
        fileError.value = true;
        selectedFileName.value = ""; // Clear the selected file name
        props.firstStepErrors.omissions_insurance = [];
        selectedFile.value = null
    } else {
        // Display an error message for invalid file type or no files dropped
        fileError.value = true;
        selectedFileName.value = ""; // Clear the selected file name
        props.firstStepErrors.omissions_insurance = [];
        selectedFile.value = null
    }
};

const fileErrorMessage = ref("Please select a single PDF file.");

let goBack = () => {
    emits("goback");
}
</script>
<template>
    <h1 style="background-color: #134576;" class="mb-4	text-center rounded-md py-2 text-white">
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
    <div class="mt-5">
        <div class="flex items-center justify-center w-full">
            <label for="dropzone-file-ommision"
                class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
                @dragover.prevent @drop="handleDrop">
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                    <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                    </svg>
                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                        <span class="font-semibold">Click to upload</span> or drag and drop
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">PDF files only<span class="text-red-500 ">*</span></p>
                </div>
                <input id="dropzone-file-ommision" type="file" class="hidden" @change="handleFileChange" accept=".pdf"  />
            </label>
        </div>
        <p v-if="fileError" class="text-red-500 mt-4">{{ fileErrorMessage }}</p>
        <!-- Display the selected file name with styling -->
        <div v-if="selectedFileName" class="text-green-500 mt-4">
            Selected File: {{ selectedFileName }}
        </div>
    </div>
    <div class="flex justify-between my-5">
        <div></div>
        <div>
            <input id="link-omissions_insurance" v-model="form.omissions_insurance" type="checkbox" value=""
                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="link-omissions_insurance" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Errors
                and
                Omissions Insurances.<span class="text-red-500 ">*</span></label>
        </div>
    </div>
    
    <div v-if="firstStepErrors.omissions_insurance" class="text-red-500" v-text="firstStepErrors.omissions_insurance[0]">
    </div>
    <div class="px-5 pb-6">
        <div class="flex justify-between flex-wrap">
            <div class="mt-4">

                <button type="button" @click.prevent="goBack" class="button-custom-back px-3 py-2 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                    </svg>
                    Back Step
                </button>
            </div>
            <div class="mt-4">
                <button type="button" :class="{ 'opacity-25': omissionsInsurance }" :disabled="omissionsInsurance"
                    @click.prevent="ChangeTab" class="button-custom px-3 py-2 rounded-md">
                    Next Step
                </button>

            </div>
        </div>
    </div>
</template>