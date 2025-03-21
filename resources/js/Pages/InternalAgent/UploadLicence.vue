
<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed } from "vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
let props = defineProps({
    firstStepErrors: Object,
    userData: Object,
    isLoading: Boolean,
});
const selectedFileName = ref(""); // To store the selected file name
let page = usePage();
let residentUrl = ref(null)

if (props.userData.internal_agent_contract && props.userData.internal_agent_contract.resident_license) {
    selectedFileName.value = props.userData.internal_agent_contract.resident_license.name
    residentUrl.value = props.userData.internal_agent_contract.resident_license.url
}
const fileError = ref(false);
const selectedFile = ref(null)
const handleFileChange = (event) => {
    const files = event.target.files;
    selectedFile.value = null
    if (files.length === 1) {
        // Handle the single PDF file here
        fileError.value = false; // Reset the error message
        selectedFileName.value = files[0].name; // Set the selected file name
        selectedFile.value = files[0]
        if (props.firstStepErrors.residentLicensePdf) {
            props.firstStepErrors.residentLicensePdf = null
        }
    } else if (files.length > 1) {
        // Display an error message for multiple files
        fileError.value = true;
        selectedFileName.value = ""; // Clear the selected file name
        props.firstStepErrors.residentLicensePdf = [];
        selectedFile.value = null
    } else {
        // Display an error message for invalid file type
        fileError.value = true;
        selectedFileName.value = ""; // Clear the selected file name
        props.firstStepErrors.residentLicensePdf = [];
        selectedFile.value = null
    }
};

const handleDrop = (event) => {
    event.preventDefault();
    const files = event.dataTransfer.files;
    if (files.length === 1) {
        // Handle the dropped single PDF file here
        fileError.value = false; // Reset the error message
        selectedFileName.value = files[0].name; // Set the selected file name
        selectedFile.value = files[0]
        if (props.firstStepErrors.residentLicensePdf) {
            props.firstStepErrors.residentLicensePdf = null
        }
    } else if (files.length > 1) {
        // Display an error message for multiple files
        fileError.value = true;
        selectedFileName.value = ""; // Clear the selected file name
        props.firstStepErrors.residentLicensePdf = [];
        selectedFile.value = null
    } else {
        fileError.value = true;
        selectedFileName.value = ""; // Clear the selected file name
        props.firstStepErrors.residentLicensePdf = [];
        selectedFile.value = null
    }
};

const fileErrorMessage = ref("Please select a single file.");
const emits = defineEmits();
let ChangeTab = () => {
    for (const key in props.firstStepErrors) {
        if (props.firstStepErrors.hasOwnProperty(key)) {
            props.firstStepErrors[key] = [];
        }
    }
    if (!selectedFile.value && page.props.auth.role === 'internal-agent' && !props.userData.internal_agent_contract.resident_license) {
        fileError.value = false;
        props.firstStepErrors.residentLicensePdf = [`The Resident License field is required.`];
    } else {
        if (page.props.auth.role === 'internal-agent') {
            emits("uploadLicense", selectedFile.value);
        } else {
            emits("changeTab");
        }
    }
}
let ChangeTabBack = () => {
    emits("goback");
}
</script>
<template>
    <div>
        <h1 style="background-color: #134576;" class="mb-4 text-center rounded-md py-2 text-white">
            Upload Resident License PDF
        </h1>
        <div v-show="page.props.auth.role === 'internal-agent'" class="flex items-center p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-700 dark:text-blue-400"
            role="alert">
            <svg class="flex-shrink-0 inline w-4 h-4 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
            <div class="dark:text-gray-100">
                <span class="font-medium "></span> We will need a copy of your Resident License To Provide the Carriers. Please Upload a Copy Below.
                <br>
                If you do not have a copy, you can obtain one at by creating an account at <strong><a target="_blank" class="underline font-bold" href="https://www.sircon.com/">Sircon.com</a></strong>  and following their instructions. Once obtained, please return and upload the pdf copy here.
            </div>
        </div>
        <div v-show="page.props.auth.role === 'admin'" class="bg-blue-50 py-10 px-6 rounded-lg shadow-md">
            <div>
                <a target="_blank" :href="residentUrl" :disabled="!residentUrl" :class="{ 'opacity-25': !residentUrl }">
                    <strong class="text-blue-600 mr-1 hover:underline">Click here
                    </strong>
                </a>Preview / Download Resident License.
            </div>
        </div>
        <div v-show="page.props.auth.role === 'internal-agent'" class="flex items-center justify-center w-full">
            <label for="dropzone-file"
                class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
                @dragover.prevent @drop="handleDrop">
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                    <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                    </svg>
                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                        <span class="font-semibold">Click to upload</span> or drag and drop <span class="text-red-500 ">*</span>
                    </p>
                </div>
                <input id="dropzone-file" type="file" class="hidden" @change="handleFileChange"/>
            </label>
        </div>
        <div v-if="firstStepErrors.residentLicensePdf" class="text-red-500 mt-1"
            v-text="firstStepErrors.residentLicensePdf[0]"></div>
        <p v-if="fileError" class="text-red-500 mt-4">{{ fileErrorMessage }}</p>
        <!-- Display the selected file name with styling -->
        <div v-if="selectedFileName && page.props.auth.role === 'internal-agent'" class="text-green-500 mt-4">
            Selected File: {{ selectedFileName }}
        </div>

    </div>
    <div class="px-5 pb-6">
        <div class="flex justify-between flex-wrap">
            <div class="mt-4">

                <button type="button" @click.prevent="ChangeTabBack" class="button-custom-back px-3 py-2 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                    </svg>
                    Back Step
                </button>
            </div>
            <div class="mt-4">
                <button type="button" :class="{ 'opacity-25': isLoading }" :disabled="isLoading" @click="ChangeTab"
                    class="button-custom px-3 py-2 rounded-md">
                    <global-spinner :spinner="isLoading" /> Next Step
                </button>

            </div>
        </div>
    </div>
</template>

