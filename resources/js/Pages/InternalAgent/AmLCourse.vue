<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed } from "vue";
import UploadPdfFile from "@/Components/UploadPdfFile.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
let emits = defineEmits();
let props = defineProps({
    firstStepErrors: Object,
    userData: Object,
    isLoading: Boolean,
});
let page = usePage();

const selectedFile = ref(null)
const selectedFileName = ref(""); // To store the selected file name
let amlUrl = ref(null)
let form = ref({
    aml_course: false,
});
if (page.props.auth.role === 'admin') {
    form.value.aml_course = true
}
if (props.userData.internal_agent_contract && props.userData.internal_agent_contract.aml_course) {
    if (props.userData.internal_agent_contract.aml_course.aml_course === 1) {
        selectedFileName.value = props.userData.internal_agent_contract.aml_course.name
        form.value.aml_course = true
        amlUrl.value = props.userData.internal_agent_contract.aml_course.url
    }

}
let amlCourseRead = ref(true)
watch(form.value, (newVal, oldVal) => {
    if (newVal.aml_course == true) {
        amlCourseRead.value = false
    } else {
        amlCourseRead.value = true
    }
})

let ChangeTab = () => {
    for (const key in props.firstStepErrors) {
        if (props.firstStepErrors.hasOwnProperty(key)) {
            props.firstStepErrors[key] = [];
        }
    }

    if (!selectedFile.value && page.props.auth.role === 'internal-agent' && !props.userData.internal_agent_contract.aml_course) {
        props.firstStepErrors.uploadAmlPdf = [`The AML course certificate is required.`];
    } else {
        if (page.props.auth.role === 'internal-agent') {
            emits("uploadPdfAml", { selectedFile: selectedFile.value, aml_course: form.value.aml_course });
        } else {
            emits("changeTab");
        }


    }
}

let goBack = () => {
    emits("goback");
}

const fileError = ref(false);


const handleFileChange = (event) => {
    const files = event.target.files;
    selectedFile.value = null
    if (files.length === 1 && files[0].type === "application/pdf") {
        // Handle the single PDF file here
        fileError.value = false; // Reset the error message
        selectedFileName.value = files[0].name; // Set the selected file name
        selectedFile.value = files[0]
        if (props.firstStepErrors.aml_course) {
            props.firstStepErrors.aml_course = null
        }
    } else if (files.length > 1) {
        // Display an error message for multiple files
        fileError.value = true;
        selectedFileName.value = ""; // Clear the selected file name
        props.firstStepErrors.aml_course = [];
        selectedFile.value = null
    } else {
        // Display an error message for invalid file type
        fileError.value = true;
        selectedFileName.value = ""; // Clear the selected file name
        props.firstStepErrors.aml_course = [];
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
        if (props.firstStepErrors.aml_course) {
            props.firstStepErrors.aml_course = null
        }
    } else if (files.length > 1) {
        // Display an error message for multiple files
        fileError.value = true;
        selectedFileName.value = ""; // Clear the selected file name
        props.firstStepErrors.aml_course = [];
        selectedFile.value = null
    } else {
        // Display an error message for invalid file type or no files dropped
        fileError.value = true;
        selectedFileName.value = ""; // Clear the selected file name
        props.firstStepErrors.aml_course = [];
        selectedFile.value = null
    }
};

const fileErrorMessage = ref("Please select a single PDF file.");

</script>
<template>
    <h1 style="background-color: #134576;" class="mb-4	text-center rounded-md py-2 text-white">
        AML Course
    </h1>

    <div v-show="page.props.auth.role === 'internal-agent'" class="bg-blue-50 py-10 px-6 rounded-lg shadow-md">
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
    <div v-show="page.props.auth.role === 'admin'" class="bg-blue-50 py-10 px-6 rounded-lg shadow-md">
        <div>
            <a target="_blank" :href="amlUrl" :disabled="!amlUrl" :class="{ 'opacity-25': !amlUrl }">
                <strong class="text-blue-600 mr-1 hover:underline">Click Here </strong>
            </a> Preview / Download AML course
        </div>
    </div>

    <div class="mt-5">
        <div v-show="page.props.auth.role === 'internal-agent'" class="flex items-center justify-center w-full">
            <label for="dropzone-file-aml"
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
                    <p class="text-xs text-gray-500 dark:text-gray-400">PDF files only<span class="text-red-500 ">*</span>
                    </p>
                </div>
                <input id="dropzone-file-aml" type="file" class="hidden" @change="handleFileChange" accept=".pdf" />
            </label>
        </div>
        <div v-if="firstStepErrors.uploadAmlPdf" class="text-red-500 mt-1" v-text="firstStepErrors.uploadAmlPdf[0]"></div>
        <p v-if="fileError" class="text-red-500 mt-4">{{ fileErrorMessage }}</p>
        <!-- Display the selected file name with styling -->
        <div v-if="selectedFileName && page.props.auth.role === 'internal-agent'" class="text-green-500 mt-4">
            Selected File: {{ selectedFileName }}
        </div>
    </div>


    <div class="flex justify-between my-5">
        <div></div>
        <div>
            <input id="link-checkbox" v-model="form.aml_course" type="checkbox" value=""
                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="link-checkbox" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">I have
                completed
                the AML course.<span class="text-red-500 ">*</span></label>
            <div v-if="firstStepErrors.aml_course" class="text-red-500 mt-1" v-text="firstStepErrors.aml_course[0]">
            </div>

        </div>

    </div>

    <!-- <div v-if="firstStepErrors.aml_course" class="text-red-500" v-text="firstStepErrors.aml_course[0]"></div> -->
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
                <button type="button" :class="{ 'opacity-25': form.aml_course === false || isLoading }"
                    :disabled="form.aml_course === false || isLoading" @click.prevent="ChangeTab"
                    class="button-custom px-3 py-2 rounded-md">
                    <global-spinner :spinner="isLoading" /> Next Step
                </button>

            </div>
        </div>
    </div>
</template>