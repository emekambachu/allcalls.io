<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed } from "vue";
import { Head, Link, router, useForm, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { toaster } from "@/helper.js";
import { basicTrainingSteps } from "@/constants.js";

let props = defineProps({
    // basicTrainingModal: Boolean,
    unlocked: String,
});
let basicTrainingModal = ref(true)
let page = usePage();
let emits = defineEmits()
let close = () => {
    emits('close')
}
let isLoading = ref(false)
let done = () => {
    if (page.props.auth.user.basic_training !== 1) {
        isLoading.value = true
        axios.get(`/training-complete/${page.props.auth.user.id}`)
            .then((response) => {
                isLoading.value = false
                toaster("success", response.data.message);
                emits('close')
                router.visit('/take-calls')
            }).catch((error) => {
                console.log('errir', error);
                isLoading.value = false
            })
    } else {
        emits('close')
        toaster("success", 'Your Training Completed Successfully.');
    }
    return
    axios.get(`/training-complete/${page.props.auth.user.id}`)
        .then((response) => {
            toaster("success", response.data.message);
            router.visit('/take-calls')
        }).catch((error) => {
            console.log('errir', error);
        })
    // step.value = 1
}
let downloadPDF = async (pdfPath) => {
    const fileName = pdfPath.substring(pdfPath.lastIndexOf('/') + 1);
    const link = document.createElement('a');
    link.href = pdfPath;
    link.download = fileName;
    link.click();
}
let step = ref(1)
let NextStep = () => {
    step.value += 1;
};
let goBack = () => {
    step.value -= 1;
};
let updateItemsToShow = () => {
    // Update the number of items to show based on screen width
    const screenWidth = window.innerWidth;

    if (screenWidth >= 1024) {
        this.itemsToShow = 3; // Large screens
    } else if (screenWidth >= 768) {
        this.itemsToShow = 2; // Medium screens
    } else {
        this.itemsToShow = 1; // Small screens
    }
}
let downloadPdf = (url) => {
  window.open(url, '_blank');
}
</script>

<style scoped>
.modal-width {
  width: 70%;
}
.video-player {
  width: 100%;
  max-height: 400px;
}
@media only screen and (min-width: 320px) and (max-width: 768px) {
  .modal-width {
    width: 90%;
  }
  .video-player {
    width: 100%;
    height: 100%;
  }
  .heading-modal {
    margin: 10px;
  }
}

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

.success-checkmark {
    width: 120px;
    /* Increase the width */
    height: 170px;
    /* Increase the height */
    margin: 0 auto;

    .check-icon {
        width: 120px;
        /* Increase the width */
        height: 120px;
        /* Increase the height */
        position: relative;
        border-radius: 50%;
        box-sizing: content-box;
        border: 6px solid #4CAF50;
        /* Adjust the border thickness */

        &::before {
            top: 4px;
            left: -3px;
            width: 45px;
            /* Increase the width */
            transform-origin: 100% 50%;
            border-radius: 100px 0 0 100px;
        }

        &::after {
            top: 0;
            left: 45px;
            /* Increase the left position */
            width: 90px;
            /* Increase the width */
            transform-origin: 0 50%;
            border-radius: 0 100px 100px 0;
            animation: rotate-circle 4.25s ease-in;
        }

        &::before,
        &::after {
            content: '';
            height: 150px;
            /* Increase the height */
            position: absolute;
            background: #FFFFFF;
            transform: rotate(-45deg);
        }

        .icon-line {
            height: 7.5px;
            /* Increase the height */
            background-color: #4CAF50;
            display: block;
            border-radius: 3px;
            position: absolute;
            z-index: 10;

            &.line-tip {
                top: 69px;
                /* Increase the top position */
                left: 21px;
                /* Increase the left position */
                width: 37.5px;
                /* Increase the width */
                transform: rotate(45deg);
                animation: icon-line-tip 0.75s;
            }

            &.line-long {
                top: 57px;
                /* Increase the top position */
                right: 12px;
                /* Increase the right position */
                width: 70.5px;
                /* Increase the width */
                transform: rotate(-45deg);
                animation: icon-line-long 0.75s;
            }
        }

        .icon-circle {
            top: -6px;
            /* Increase the top position */
            left: -6px;
            /* Increase the left position */
            z-index: 10;
            width: 120px;
            /* Increase the width */
            height: 120px;
            /* Increase the height */
            border-radius: 50%;
            position: absolute;
            box-sizing: content-box;
            border: 6px solid rgba(76, 175, 80, .5);
            /* Adjust the border thickness */
        }

        .icon-fix {
            top: 12px;
            /* Increase the top position */
            width: 7.5px;
            /* Increase the width */
            left: 39px;
            /* Increase the left position */
            z-index: 1;
            height: 127.5px;
            /* Increase the height */
            position: absolute;
            transform: rotate(-45deg);
            background-color: #FFFFFF;
        }
    }
}

@keyframes rotate-circle {
    0% {
        transform: rotate(-45deg);
    }

    5% {
        transform: rotate(-45deg);
    }

    12% {
        transform: rotate(-405deg);
    }

    100% {
        transform: rotate(-405deg);
    }
}

@keyframes icon-line-tip {
    0% {
        width: 0;
        left: 1px;
        top: 19px;
    }

    54% {
        width: 0;
        left: 1px;
        top: 19px;
    }

    70% {
        width: 50px;
        left: -8px;
        top: 37px;
    }

    84% {
        width: 17px;
        left: 21px;
        top: 48px;
    }

    100% {
        width: 25px;
        left: 14px;
        top: 45px;
    }
}

@keyframes icon-line-long {
    0% {
        width: 0;
        right: 46px;
        top: 54px;
    }

    65% {
        width: 0;
        right: 46px;
        top: 54px;
    }

    84% {
        width: 55px;
        right: 0px;
        top: 35px;
    }

    100% {
        width: 47px;
        right: 8px;
        top: 38px;
    }
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
            <div id="defaultModal" v-if="basicTrainingModal" tabindex="-1"
                class="flex items-center justify-center fixed inset-0 z-50 w-full h-full overflow-x-hidden overflow-y-auto max-h-full mx-4 sm:mx-0">
                <div class="fixed inset-0 bg-black opacity-90 blurred-overlay"></div>

                <!-- This is the overlay -->
                <div class="relative w-full py-10 modal-width  max-h-full mx-auto">
                    <div class="relative bg-white rounded-lg shadow-lg transition-all">
                        <div class="flex justify-between ">
                            <div class="px-12 py-2 mt-2">
                                <h1 v-for="(basicTraning, index) in basicTrainingSteps" :key="basicTraning.id"
                                    class=" text-gray-800 text-xl font-bold"> <span
                                        v-show="basicTraning.id == step && step < 12"> Basic
                                        Training - {{ basicTraning.title }} ( {{ step }}/11 )</span> </h1>
                            </div>
                            <button v-if="unlocked" @click="close" type="button"
                                class="text-gray-400 bg-transparent mr-2 mt-2 hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center"
                                data-modal-hide="defaultModal">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                            <div v-else class="flex justify-end">
                                <Link :href="route('logout')" method="post" as="button"
                                    class="underline text-sm text-gray-600 mr-5 mt-2  dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                Logout </Link>

                            </div>

                        </div>

                        <div v-for="(basicTraning, index) in basicTrainingSteps" :key="basicTraning.id">
                            <div v-if="basicTraning.id == step && step < 11" class="px-12 pt-3 ">
                                <!-- <iframe style="width:100%;" height="400" :src="basicTraning.video_url"
                                    title="YouTube video player" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen></iframe> -->
                                <video class="video-player"  controls>
                                    <source :src="basicTraning.video_url">
                                </video>
                                <br>
                                <div>
                                    <button
                                        :target="basicTraning.pdf ? '_blank' : ''"
                                        :disabled="!basicTraning.pdf"
                                        :class="{ 'opacity-50': !basicTraning.pdf }"
                                        @click="downloadPdf(basicTraning.pdf)"
                                        class="px-3 py-2 text-sm cursor-pointer font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300"
                                        >
                                        Download {{ basicTraning.title }} PDF
                                    </button>
                                    <!-- <a @click="downloadPDF(basicTraning.pdf)"
                                        class="font-medium  text-blue-600 cursor-pointer  dark:text-blue-500 hover:underline">Download
                                        pdf
                                    </a>
                                    <a @click="downloadPDF(basicTraning.pdf)" title="Download pdf"
                                        class="inline-flex  cursor-pointer ml-2 items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-blue-600">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                        </svg>
                                    </a> -->
                                </div>
                            </div>
                            <div    v-if="basicTraning.id == step && step == 11" class="px-12 pt-3 flex justify-center">
                                <div class="success-checkmark">
                                    <div class="check-icon">
                                        <span class="icon-line line-tip"></span>
                                        <span class="icon-line line-long"></span>
                                        <div class="icon-circle"></div>
                                        <div class="icon-fix"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="px-12  pb-5">
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
                                        <button v-if="step < 10" type="button" :class="{ 'opacity-25': isLoading }"
                                            :disabled="isLoading" @click.prevent="NextStep"
                                            class="button-custom px-3 py-2 rounded-md">
                                            <global-spinner :spinner="isLoading" /> Next Step
                                        </button>
                                        <button v-if="step == 10 && !unlocked" type="button"
                                            :class="{ 'opacity-25': isLoading }" :disabled="isLoading"
                                            @click.prevent="NextStep" class="button-custom px-3 py-2 rounded-md">
                                            <global-spinner :spinner="isLoading" /> Finish
                                        </button>
                                        <button v-if="step == 10 && unlocked" type="button"
                                            :class="{ 'opacity-25': isLoading }" :disabled="isLoading"
                                            @click.prevent="NextStep" class="button-custom px-3 py-2 rounded-md">
                                            <global-spinner :spinner="isLoading" /> Finish
                                        </button>

                                        <button v-if="step == 11 && !unlocked" type="button"
                                            :class="{ 'opacity-25': isLoading }" :disabled="isLoading" @click.prevent="done"
                                            class="button-custom px-3 py-2 rounded-md">
                                            <global-spinner :spinner="isLoading" /> done
                                        </button>
                                        <button v-if="step == 11 && unlocked" type="button"
                                            :class="{ 'opacity-25': isLoading }" :disabled="isLoading" @click.prevent="done"
                                            class="button-custom px-3 py-2 rounded-md">
                                            <global-spinner :spinner="isLoading" /> done
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
