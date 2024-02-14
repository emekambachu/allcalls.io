<script setup>
import { ref, reactive, defineEmits, computed, onMounted, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { toaster } from "@/helper.js";
import PrimaryButton from "@/Components/PrimaryButton.vue";
let page = usePage();
if (page.props.flash.message) {
    toaster("success", page.props.flash.message);
}
let props = defineProps({
    newAgentTrainingModal: {
        type: Boolean,
    },
});

let emit = defineEmits(["close"]);

let close = () => {
    emit("close");
};
let onLowBalanceModalClick = () => {
  window.open("https://calendly.com/insurancecareers/new-agent-call-review", "_blank");
  emit("close");
};
</script>

<template>
    <Transition name="modal" enter-active-class="transition ease-out  duration-300 transform"
            enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to-class="opacity-100 translate-y-0 sm:scale-100"
            leave-active-class="transition ease-in duration-200 transform"
            leave-from-class="opacity-100 translate-y-0 sm:scale-100"
            leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

            <div id="defaultModal"  tabindex="-1"
                class="flex items-center justify-center fixed inset-0 z-50 w-full h-full overflow-x-hidden overflow-y-auto max-h-full mx-4 sm:mx-0">

                <div class="fixed inset-0 bg-black opacity-90 blurred-overlay"></div>
                <!-- This is the overlay -->
                <div style="width: 50%;" class="relative w-full py-10  max-h-full mx-auto" id="modal_main_id">
                    <div class="relative bg-white rounded-lg shadow-lg ">
                        <div class="flex justify-end">
                            <Link  :href="route('logout')" method="post"
                                as="button"
                                class="underline text-sm text-gray-600 mr-5 mt-5  dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            Logout </Link>
                            <button  @click="close" type="button"
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
                        <div class="bg-white py-6   ">
                           
                                <h4 class="mb-4 mx-5 text-lg">
                                    Congratulations on completing your training videos! Your next step awaits – schedule your agent review call now. Don't miss out, take the next step today!
                                </h4>
                                <div class="flex justify-between mx-5">
                                    <PrimaryButton  @click.prevent="onLowBalanceModalClick">Schedule Live Training</PrimaryButton>
                                    <PrimaryButton  @click.prevent="close">Close</PrimaryButton>
                                </div>
                          
                        </div>
                      
                    </div>
                </div>
            </div>

        </Transition>
</template>
