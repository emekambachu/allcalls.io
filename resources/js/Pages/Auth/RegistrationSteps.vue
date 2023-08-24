<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import GuestInputLabel from "@/Components/GuestInputLabel.vue";
import Register from "@/Pages/Auth/Register.vue";
import StepsModalView from "@/Pages/Auth/StepsModal.vue";
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

const props = defineProps({
    spendData: Array,
    callData: Array,
    totalCalls: Number,
    totalAmountSpent: Number,
    averageCallDuration: Number,
    callTypes: Array,
    states: Array,
});
let stateOptions = computed(() => {
    return props.states.map((state) => {
        return {
            value: state.id,
            label: state.name,
        };
    });
});

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

let selectedTypes = ref([]);

let selectedTypesWithStates = ref({
    "Auto Insurance": [],
    "Final Expense": [],
    "U65 Health": [],
    ACA: [],
    "Medicare/Medicaid": [],
    "Debt Relief": [],
});
let onTypeUpdate = (event) => {
    if (event.target.checked) {
        selectedTypes.value.push(Number(event.target.value));
    } else {
        selectedTypes.value.splice(
            selectedTypes.value.indexOf(Number(event.target.value)),
            1
        );
    }
};
console.log('call types here', props.callTypes);
let form = useForm({
    consent: false,
    typesWithStates: props.callTypes.reduce((acc, obj) => {
        acc[obj.id] = [];
        return acc;
    }, {}),
    bids: props.callTypes.map((type) => {
        return { call_type_id: type.id, amount: 20 };
    }),
});
let spendChartData = reactive({
    labels: props.spendData.map((item) => item.date),
    datasets: [
        {
            label: "Amount Spent (Last 7 Days)",
            data: props.spendData.map((item) => item.sum),
            backgroundColor: "rgba(75, 192, 192, 0.2)",
            borderColor: "rgba(75, 192, 192, 1)",
            borderWidth: 1,
        },
    ],
});

let callChartData = reactive({
    labels: props.callData.map((item) => item.date),
    datasets: [
        {
            label: "Clients per Day (Last 7 Days)",
            data: props.callData.map((item) => item.count),
            backgroundColor: "rgba(153, 102, 255, 0.2)",
            borderColor: "rgba(153, 102, 255, 1)",
            borderWidth: 1,
        },
    ],
});

let chartOptions = reactive({
    responsive: true,
    scales: {
        y: {
            beginAtZero: true,
        },
    },
});

let spendChartOptions = reactive({
    responsive: true,
    scales: {
        y: {
            beginAtZero: true,
            ticks: {
                // Include a dollar sign and commas in the ticks
                callback: function (value) {
                    return "$" + value.toLocaleString();
                },
            },
        },
    },
});

let formatTime = (duration) => {
    const minutes = Math.floor(duration / 60);
    const seconds = Math.floor(duration % 60);
    return `${minutes.toString().padStart(2, "0")}:${seconds
        .toString()
        .padStart(2, "0")}`;
};

let formatMoney = (amount) => {
    return parseFloat(amount)
        .toFixed(2)
        .replace(/\d(?=(\d{3})+\.)/g, "$&,");
};

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
    <AuthenticatedLayout >
            <StepsModalView 
                :StepsModal="StepsModal"
                :callTypes="callTypes"
                :states="states"
                @close="StepsModal = false"
        ></StepsModalView>
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
    padding: 50px;
    width: 90%;
    margin: auto;
    box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
}
</style>
