
<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed } from "vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
let props = defineProps({
    firstStepErrors: Object,
    userData: Object,
    isLoading: Boolean,
});
let page = usePage();
const selectedFileName = ref(""); // To store the selected file name
let bankingInfotUrl = ref(null)
if (props.userData.internal_agent_contract && props.userData.internal_agent_contract.banking_info) {
    selectedFileName.value = props.userData.internal_agent_contract.banking_info.name
    bankingInfotUrl.value = props.userData.internal_agent_contract.banking_info.url
}

const fileError = ref(false);
const selectedFile = ref(null)
const handleFileChange = (event) => {
    const files = event.target.files;
    selectedFile.value = null
    if (files.length === 1) {
        fileError.value = false; // Reset the error message
        selectedFileName.value = files[0].name; // Set the selected file name
        selectedFile.value = files[0]
        if (props.firstStepErrors.bankingInfoPdf) {
            props.firstStepErrors.bankingInfoPdf = null
        }
    } else if (files.length > 1) {
        fileError.value = true;
        selectedFileName.value = ""; // Clear the selected file name
        props.firstStepErrors.bankingInfoPdf = [];
        selectedFile.value = null
    } else {
        fileError.value = true;
        selectedFileName.value = ""; // Clear the selected file name
        props.firstStepErrors.bankingInfoPdf = [];
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
        if (props.firstStepErrors.bankingInfoPdf) {
            props.firstStepErrors.bankingInfoPdf = null
        }
        // console.log('handleDrop', selectedFile.value);
    } else if (files.length > 1) {
        // Display an error message for multiple files
        fileError.value = true;
        selectedFileName.value = ""; // Clear the selected file name
        props.firstStepErrors.bankingInfoPdf = [];
        selectedFile.value = null
    } else {
        // Display an error message for invalid file type or no files dropped
        fileError.value = true;
        selectedFileName.value = ""; // Clear the selected file name
        props.firstStepErrors.bankingInfoPdf = [];
        selectedFile.value = null
    }
};
let dateFormat = (dateString) => {
    const dateObj = new Date(dateString);

    const day = dateObj.getDate().toString().padStart(2, "0");
    const month = dateObj.toLocaleString("en-US", { month: "short" }).toLowerCase();
    const year = dateObj.getFullYear();

    return `${day}-${month}-${year}`;
}
const fileErrorMessage = ref("Please select a single file.");
const emits = defineEmits();

let submit = () => {
    for (const key in props.firstStepErrors) {
        if (props.firstStepErrors.hasOwnProperty(key)) {
            props.firstStepErrors[key] = [];
        }
    }
    if (!selectedFile.value && page.props.auth.role === 'internal-agent' && !props.userData.internal_agent_contract.banking_info) {
        fileError.value = false;
        props.firstStepErrors.bankingInfoPdf = [`The Banking Information field is required.`];
    } else {
        if (page.props.auth.role === 'internal-agent') {
            emits("uploadBankingInfo", selectedFile.value);
        } else {
            emits("submit");
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
            Banking Information
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
                <span class="font-medium "></span> Please provide and upload banking information so our carriers know where to deposit your commissions.
                <br>
                Only certain forms of banking information are accepted, the most common form is a copy of a voided check. Please see the pdf below for other accepted examples.
            </div>
        </div>

        <div v-show="page.props.auth.role === 'internal-agent'" class="bg-gray-50 dark:bg-gray-700 py-7 px-6 mb-5 rounded-lg shadow-md">
            <div class="">
                <a target="_blank"
                    href="https://www.financialservicecareers.com/_files/ugd/0fb1f5_f3cad5300c3b4706ae1b5daf6fe16908.pdf">
                    <strong class="text-blue-600 mr-1 hover:underline">Download pdf guide</strong>
                </a> <span class="dark:text-gray-100">for banking information.</span> 
            </div>
        </div>
        <div v-show="page.props.auth.role === 'admin'" class="bg-blue-50  py-7 px-6 mb-5 rounded-lg shadow-md">
            <div class="">
                <a target="_blank" :href="bankingInfotUrl" :disabled="!bankingInfotUrl"
                    :class="{ 'opacity-25': !bankingInfotUrl }">
                    <strong class="text-blue-600 mr-1 hover:underline">Click here</strong>
                </a>Preview / Download banking information.
            </div>
        </div>

        <div v-show="page.props.auth.role === 'internal-agent'" class="flex items-center  justify-center mb-2 w-full">
            <label for="dropzone-file-bank-info"
                class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
                @dragover.prevent @drop="handleDrop">
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                    <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                    </svg>
                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                        <span class="font-semibold">Click to upload</span> or drag and drop<span class="text-red-500 ">*</span>
                    </p>
                </div>
                <input id="dropzone-file-bank-info" type="file" class="hidden" @change="handleFileChange"/>
            </label>
        </div>
        <div v-if="firstStepErrors.bankingInfoPdf" class="text-red-500 mt-1" v-text="firstStepErrors.bankingInfoPdf[0]">
        </div>
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
            <div v-show="$page.props.auth.role === 'internal-agent'" class="mt-4">
                <button type="button" :class="{ 'opacity-25': isLoading }" :disabled="isLoading" @click="submit"
                    class="button-custom px-3 py-2 rounded-md">
                    <global-spinner :spinner="isLoading" /> Next Step
                </button>

            </div>
        </div>
    </div>
</template>

