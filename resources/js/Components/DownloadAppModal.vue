<script setup>
import { ref, reactive, defineEmits, onMounted, watch } from "vue";
import TextInput from "@/Components/TextInput.vue";
import { router, usePage } from "@inertiajs/vue3"
import GuestTextInput from "@/Components/GuestTextInput.vue";
import GuestInputLabel from "@/Components/GuestInputLabel.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { toaster } from '@/helper.js';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import axios from "axios";
let page = usePage();
if (page.props.flash.message) {
    toaster('success', page.props.flash.message)
}
let props = defineProps({
    appDownloadModal: {
        type: Boolean,
    },

});
let emit = defineEmits(["close"]);

let close = () => {
    emit("close");
};
</script>
<style scoped>
.for-window {
    display: inline-flex;
    background: #0067b8;
    color: white;
    padding: 14px 28px;
    border-radius: 8px;
    margin-top: 11px;
}
</style>
<template>
    <Transition name="modal" enter-active-class="transition ease-out duration-300 transform"
        enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        enter-to-class="opacity-100 translate-y-0 sm:scale-100"
        leave-active-class="transition ease-in duration-200 transform"
        leave-from-class="opacity-100 translate-y-0 sm:scale-100"
        leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
        <div id="defaultModal" v-if="appDownloadModal" tabindex="-1"
            class="flex items-center justify-center fixed inset-0 z-50 w-full h-full overflow-x-hidden overflow-y-auto max-h-full mx-4 sm:mx-0">
            <div class="fixed inset-0 bg-black opacity-60"></div>
            <!-- This is the overlay -->
            <div class="relative w-full max-w-sm max-h-full mx-auto">
                <div class="relative bg-white border border-gray-300 rounded-lg shadow-lg">
                    <div class="flex items-start justify-between p-4 border-b border-gray-300 rounded-t">
                        <h3 class="text-xl font-small text-gray-700">
                            Download App
                        </h3>
                        <button @click="close" type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center"
                            data-modal-hide="defaultModal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="text-center mt-2">
                        <a href="#"><img style="max-width: 200px;margin: auto; " src="/img/google-store.png" /></a>
                        <a href="#"><img style="max-width: 200px;margin: auto; " src="/img/app-store.png" /></a>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>