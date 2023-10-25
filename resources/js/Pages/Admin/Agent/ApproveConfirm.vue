<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed } from "vue";
let emits = defineEmits()
let props = defineProps({
    showModalConfirm: Boolean,
    isLoading:Boolean,
});
let onCancel  = () => {
    emits('onCancel')
}
let onApprove  = () => {
    emits('onApprove')
}
let close = () => {
    emits('close')
}
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
</style>
<template>
    <Transition name="modal" enter-active-class="transition ease-out duration-300 transform"
        enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        enter-to-class="opacity-100 translate-y-0 sm:scale-100"
        leave-active-class="transition ease-in duration-200 transform"
        leave-from-class="opacity-100 translate-y-0 sm:scale-100"
        leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
        <div id="defaultModal" v-if="showModalConfirm" tabindex="-1"
            class="flex items-center justify-center fixed inset-0 z-50 w-full h-full overflow-x-hidden overflow-y-auto max-h-full mx-4 sm:mx-0">
            <div class="fixed inset-0 bg-black opacity-90 blurred-overlay"></div>

            <!-- This is the overlay -->
            <div class="relative w-full max-w-xl max-h-full mx-auto">
                <div class="relative bg-white rounded-lg shadow-lg transition-all">
                    <div class="flex justify-end">
                        <button @click="close" type="button"
                            class="text-gray-400 bg-transparent mr-2 mt-2 hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center"
                            data-modal-hide="defaultModal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="px-12 py-2">
                        <h1 class="text-gray-800 text-2xl font-bold">Internal Agent Approval.</h1>
                        <div class="mt-2 mb-2 text-sm">
                            Do you want to approve this agent?
                        </div>
                        <div class=" mb-3">
                            <div class="flex justify-end">
                                <div class="mt-4">
                                    <button @click="onCancel" class="button-custom-back mr-3 px-3 py-2 rounded-md">
                                        Cancel</button>
                                </div>
                                <div class="mt-4">
                                    <button type="button" @click="onApprove"
                                        :class="{ 'opacity-25': isLoading }"
                                        :disabled="isLoading"
                                        class="button-custom px-3 py-2 rounded-md flex items-center">
                                        <global-spinner :spinner="isLoading" />  Approve
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
