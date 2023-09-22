  
<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const fileError = ref(false);
const selectedFileName = ref(""); // To store the selected file name
const selectedFile = ref()
const handleFileChange = (event) => {
    const files = event.target.files;
    if (files.length === 1 && files[0].type === "application/pdf") {
        fileError.value = false; // Reset the error message
        selectedFileName.value = files[0].name; // Set the selected file name
        selectedFile.value = files[0]
    } else if (files.length > 1) {
        fileError.value = true;
        selectedFileName.value = ""; // Clear the selected file name
    } else {
        fileError.value = true;
        selectedFileName.value = ""; // Clear the selected file name
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
        // console.log('handleDrop', selectedFile.value);
    } else if (files.length > 1) {
        // Display an error message for multiple files
        fileError.value = true;
        selectedFileName.value = ""; // Clear the selected file name
    } else {
        // Display an error message for invalid file type or no files dropped
        fileError.value = true;
        selectedFileName.value = ""; // Clear the selected file name
    }
};

const fileErrorMessage = ref("Please select a single PDF file.");
const emits = defineEmits();
watch(selectedFile, (newForm, oldForm) => {
    emits("uploadBankingInfo", newForm);
});
</script>
<template>
    <div>
        <h1 style="background-color: #134576;" class="mb-4 text-center rounded-md py-2 text-white">
            Banking Information
        </h1>
        <div class="flex items-center justify-center w-full">
            <label for="dropzone-file-bank-info"
                class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
                @dragover.prevent @drop="handleDrop">
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                    <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                        <!-- SVG icon here -->
                    </svg>
                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                        <span class="font-semibold">Click to upload</span> or drag and drop
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">PDF files only</p>
                </div>
                <input id="dropzone-file-bank-info" type="file" class="hidden" @change="handleFileChange" accept=".pdf" multiple />
            </label>
        </div>
        <p v-if="fileError" class="text-red-500">{{ fileErrorMessage }}</p>
        <!-- Display the selected file name with styling -->
        <div v-if="selectedFileName" class="text-green-500 mt-4">
            Selected File: {{ selectedFileName }}
        </div>
    </div>
</template>

  