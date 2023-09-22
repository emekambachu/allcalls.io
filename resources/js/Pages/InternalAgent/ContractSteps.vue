<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import GuestInputLabel from "@/Components/GuestInputLabel.vue";
import Register from "@/Pages/Auth/Register.vue";
import ContractModal from "@/Pages/InternalAgent/ContractModal.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import Multiselect from "@vueform/multiselect";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import { Bar } from "vue-chartjs";
import axios from "axios";
import { router } from "@inertiajs/vue3"
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
} from "chart.js";
import { ref, reactive, computed } from "vue";
import { Carousel, Slide, Pagination, Navigation } from "vue3-carousel";
ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale
);

let step = ref(1);
let firstStepErrors = ref({});
let StepsModal = ref(true)

let insuranceTypes = ref([
    "Auto Insurance",
    "Final Expense",
    "U65 Health",
    "ACA",
    "Medicare/Medicaid",
    "Debt Relief",
]);




let errors = ref(null);
const consentError = ref(false)
const consentErro = ref('')
let submit = () => {
    return axios
        .post("/store-registration-steps", form)
        .then((response) => {
            consentError.value = false
            router.visit('/dashboard')
        })
        .catch((error) => {
            if (error.response) {
                errors.value = error.response.data.errors;
                firstStepErrors.value = error.response.data.errors;
                consentErro.value = error.response.data.consent;
                consentError.value = true
                step.value = 2;
                if (error.response.status === 400) {
                    step.value = 2;
                    firstStepErrors.value = error.response.data.errors;
                    consentErro.value = error.response.data.consent;
                    consentError.value = true
                } else {
                    console.log("Other errors", error.response.data);
                }
            } else if (error.request) {
                console.log("No response received", error.request);
            } else {
                console.log("Error", error.message);
            }
        });
};
let goBack = () => {
    step.value = 1;
};
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <ContractModal :StepsModal="StepsModal" :callTypes="callTypes" :states="states" @close="StepsModal = false">
        </ContractModal>
    </AuthenticatedLayout>
</template>

<style src="@vueform/multiselect/themes/default.css"></style>
<style>
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type="number"] {
    -moz-appearance: textfield;
}

.multiselect {
    color: black !important;
    border: none;
    border-radius: 10px;
}

.multiselect-wrapper {
    background-color: #d7d7d7;
    border-radius: 5px;
}

.box-shadow {
    padding: 20px;
    width: 97%;
    margin: auto;
    box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
}
</style>
