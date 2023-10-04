<script setup>
import { ref, reactive, defineEmits, computed, onMounted, watch } from "vue";
import TextInput from "@/Components/TextInput.vue";
import { router, usePage } from "@inertiajs/vue3";
import GuestTextInput from "@/Components/GuestTextInput.vue";
import GuestInputLabel from "@/Components/GuestInputLabel.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { toaster } from "@/helper.js";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import axios from "axios";
import { fromJSON } from "postcss";
let page = usePage();
if (page.props.flash.message) {
    toaster("success", page.props.flash.message);
}
let props = defineProps({
    legalModal: {
        type: Boolean,
    },
    legalQuestions: Array,
    userDetail: {
        type: Object,
    },
    currentPage: Number,
    slidingLoader:Boolean,
});
let emit = defineEmits(["close"]);
let originalClient = props.userDetail;

let close = () => {
    emit("close");
    firstStepErrors.value = {};
};
let firstStepErrors = ref({});
let uiEmailValidation = ref({
    isValid: false,
});
let balanceChange = ref(false);
let form = useForm({
    first_name: "",
    last_name: "",
    email: "",
    password: "",
    phone: "",
    balance: 0,
    comment: "",
});

// form = JSON.parse(JSON.stringify(props.userDetail));

const isSecondFormVisible = ref(false);
// Check if balance change
let changeBalance = (newBalance) => {
    return newBalance != props.userDetail.balance;
};
let validateEmail = (email) => {
    return /\S+@\S+\.\S+/.test(email); // Simple regex for email validation
};

const isLoading = ref(false);
let formatData = (val) => {
    const withSpaces = val.replace(/_/g, ' ');

    // Capitalize the first letter of each word
    const formatted = withSpaces
        .split(' ')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');

    return formatted;

}
</script>

<template>
    <!-- This is the overlay -->
    
    <div class="relative w-full max-w-4xl max-h-full mx-auto">
        <div class="relative bg-white border border-gray-300 rounded-lg shadow-lg">
            <div class="flex items-start justify-between p-4 border-b border-gray-300 rounded-t">
                <h3 class="text-xl font-small text-gray-700">Legal Questions</h3>
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
            <animation-slider class="mt-3" :slidingLoader="slidingLoader" />
            <div v-show="!slidingLoader" class="mb-5">
                <div v-for="question in legalQuestions" class="flex justify-between">
                    <div class="px-5 py-2">{{ formatData(question.name) }}</div>
                    <button class="px-5 py-2">download pdf</button>
                </div>
            </div>
            <div class="flex justify-end p-5">
                <PrimaryButton @click.prevent="close" type="button" class="ml-3">
                    Close
                </PrimaryButton>
            </div>

        </div>
    </div>
</template>
