<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed } from "vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
let props = defineProps({
    basicTraningModal: Boolean
});
let page = usePage();
let emits = defineEmits()
let close = () => {
    emits('close')
}
let downloadPDF = async (pdfUrl) => {


    try {
        // Fetch the PDF file as a blob
        const response = await fetch(pdfUrl);
        const blob = await response.blob();

        // Create a Blob URL
        const blobUrl = URL.createObjectURL(blob);

        // Create a link element
        const link = document.createElement('a');
        link.href = blobUrl;

        // Specify the download attribute and filename
        link.download = 'dummy.pdf';

        // Append the link to the body
        document.body.appendChild(link);

        // Trigger a click on the link to start the download
        link.click();

        // Remove the link and revoke the Blob URL
        document.body.removeChild(link);
        URL.revokeObjectURL(blobUrl);
    } catch (error) {
        console.error('Error downloading PDF:', error);
    }
}
let step = ref(1)
let NextStep = () => {
    step.value += 1;

};
let basicTraningSteps = ref([
    {
        id: 1,
        video_url: 'https://www.youtube.com/embed/D0UnqGm_miA?si=Kp0oum7iLcBPDeti',
        pdf: 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
        title:'Finishing Your Contracting',
    },
    {
        id: 2,
        video_url: 'https://www.youtube.com/embed/9xwazD5SyVg?si=43Krn57-XI3P9cVg',
        pdf: 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
        title:'Understanding Final Expense',
    },
    {
        id: 3,
        video_url: 'https://www.youtube.com/embed/D0UnqGm_miA?si=Kp0oum7iLcBPDeti',
        pdf: 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
        title:'Understanding The Process',
    },
    {
        id: 4,
        video_url: 'https://www.youtube.com/embed/9xwazD5SyVg?si=43Krn57-XI3P9cVg',
        pdf: 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
        title:' Final Expense Products',
    },
    {
        id: 5,
        video_url: 'https://www.youtube.com/embed/9xwazD5SyVg?si=43Krn57-XI3P9cVg',
        pdf: 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
        title:'Taking Calls',
    },

    {
        id: 6,
        video_url: 'https://www.youtube.com/embed/D0UnqGm_miA?si=Kp0oum7iLcBPDeti',
        pdf: 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
        title:'My Script',
    },
    {
        id: 7,
        video_url: 'https://www.youtube.com/embed/9xwazD5SyVg?si=43Krn57-XI3P9cVg',
        pdf: 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
        title:'Underwriting Toolkit',
    },
    {
        id: 8,
        video_url: 'https://www.youtube.com/embed/D0UnqGm_miA?si=Kp0oum7iLcBPDeti',
        pdf: 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
        title:'Completing The Request For Coverage',
    },
    {
        id: 9,
        video_url: 'https://www.youtube.com/embed/9xwazD5SyVg?si=43Krn57-XI3P9cVg',
        pdf: 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
        title:'Live Call Examples',
    },
    {
        id: 10,
        video_url: 'https://www.youtube.com/embed/D0UnqGm_miA?si=Kp0oum7iLcBPDeti',
        pdf: 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
        title:'Reporting Your Business',
    },
    {
        id: 11,
        video_url: 'https://www.youtube.com/embed/9xwazD5SyVg?si=43Krn57-XI3P9cVg',
        pdf: 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
        title:'Set Yourself Up for Success',
    },
    
])

let goBack = () => {
    step.value -= 1;
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

.blurred-overlay {
    backdrop-filter: blur(10px);
    /* Adjust the blur intensity as needed */
    background-color: rgba(0, 0, 0, 0.6);
    /* Adjust the background color and opacity as needed */
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
</style>
<template>
    <AuthenticatedLayout>
        <Transition name="modal" enter-active-class="transition ease-out duration-300 transform"
            enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to-class="opacity-100 translate-y-0 sm:scale-100"
            leave-active-class="transition ease-in duration-200 transform"
            leave-from-class="opacity-100 translate-y-0 sm:scale-100"
            leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <div id="defaultModal" v-if="basicTraningModal" tabindex="-1"
                class="flex items-center justify-center fixed inset-0 z-50 w-full h-full overflow-x-hidden overflow-y-auto max-h-full mx-4 sm:mx-0">
                <div class="fixed inset-0 bg-black opacity-90 blurred-overlay"></div>

                <!-- This is the overlay -->
                <div style="width: 60%;" class="relative w-full py-10  max-h-full mx-auto">
                    <div class="relative bg-white rounded-lg shadow-lg transition-all">
                        <div class="flex justify-end ">
                            <div class="px-12 py-2 mt-2">
                                <h1 v-for="(basicTraning, index) in basicTraningSteps" :key="basicTraning.id" class=" text-gray-800 text-xl font-bold"> <span v-show="basicTraning.id == step"> Basic Training - {{basicTraning.title}}  ( {{ step }}/11 )</span> </h1>
                            </div>
                            <button @click="close" type="button"
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
                        <div v-for="(basicTraning, index) in basicTraningSteps" :key="basicTraning.id">
                            <div v-if="basicTraning.id == step"  class="px-12 pt-3 ">
                                <iframe style="width:100%;" height="400"
                                    :src="basicTraning.video_url"
                                    title="YouTube video player" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen></iframe>
                                <br>
                                <div>
                                    <a target="_blank"
                                        :href="basicTraning.pdf"
                                        class="font-medium  text-blue-600 dark:text-blue-500 hover:underline">Download pdf
                                    </a>
                                    <a target="_blank"
                                        :href="basicTraning.pdf"
                                        title="Download pdf"
                                        class="inline-flex  ml-2 items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-blue-600">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>


                       

                        <div class="px-12  pb-7">
                            <div class="">
                                <div class="flex justify-between flex-wrap">
                                    <div class="mt-4">

                                        <button v-if="step > 1" type="button" @click.prevent="goBack"
                                            class="button-custom-back px-3 py-2 rounded-md">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                                            </svg>
                                            Back Step
                                        </button>
                                    </div>
                                    <div class="mt-4">
                                        <button v-if="step < 11" type="button" :class="{ 'opacity-25': isLoading }" :disabled="isLoading"
                                            @click.prevent="NextStep" class="button-custom px-3 py-2 rounded-md">
                                            <global-spinner :spinner="isLoading" /> Next Step
                                        </button>
                                        <button v-if="step == 11" type="button" :class="{ 'opacity-25': isLoading }" :disabled="isLoading"
                                            @click.prevent="close" class="button-custom px-3 py-2 rounded-md">
                                            <global-spinner :spinner="isLoading" /> Done
                                        </button>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </Transition>
    </AuthenticatedLayout>
</template>
