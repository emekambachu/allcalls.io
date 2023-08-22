<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import GuestInputLabel from "@/Components/GuestInputLabel.vue";
import Register from "@/Pages/Auth/Register.vue";
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
       

        <div class="pt-14">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="px-4 sm:px-8 sm:rounded-lg">
                    <div class="text-4xl text-custom-sky font-bold mb-6">Steps</div>
                </div>
            </div>
        </div>
        <form @submit.prevent="submit" class="pb-20">
            <div v-if="step === 1" class="px-20 box-shadow">
                <h6 class="text-black text-2xl font-extrabold text-center my-4">
                    How It Works
                </h6>

                <Carousel :items-to-show="1">
                    <Slide :key="1">
                        <div class="px-12">
                            <p class="text-gray-700 text-lg text-left leading-relaxed">
                                Our dynamic bidding system allows you to set a maximum bid for
                                each type of call you're interested in as you configure your
                                account.
                            </p>
                        </div>
                    </Slide>

                    <Slide :key="2">
                        <div class="px-12">
                            <p class="text-gray-700 text-lg text-left leading-relaxed">
                                Each call type has a base bid of $20, but you can bid higher to
                                increase your chances of securing the call. The user with the
                                highest bid wins the call but pays only $1 more than the second
                                highest bid.
                            </p>
                        </div>
                    </Slide>
                    <Slide :key="3">
                        <div class="px-12">
                            <p class="text-gray-700 text-lg text-left leading-relaxed">
                                To start, select your desired call types and indicate your
                                maximum bid for each. Don't forget to select the states where
                                you're licensed to operate. Remember, banks will see your bid
                                amounts as they set the prices they allow for payment
                                processing, so bid wisely!
                            </p>

                            <div class="text-center mt-4 mb-2">
                                <PrimaryButton type="button" @click.prevent="step = 2">Configure Call Types</PrimaryButton>
                            </div>
                        </div>
                    </Slide>

                    <template #addons>
                        <navigation />
                        <pagination />
                    </template>
                </Carousel>
            </div>
            <div v-if="step === 2" class="px-20 box-shadow">
                <div class="mt-4">
                    <GuestInputLabel class="mb-3" for="insurance_type"
                        value="What types of calls do you want to receive?" />

                    <div v-for="(callType, index) in callTypes" :key="callType.id" class="mb-4">
                        <input :id="`insurance_type_${callType.id}`" type="checkbox"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                            @change="onTypeUpdate" :value="callType.id" />

                        <label class="ml-2 text-md font-medium text-custom-indigo" :for="`insurance_type_${callType.id}`"
                            v-text="callType.type"></label>

                        <div v-if="selectedTypes.includes(callType.id)" class="pt-2 mb-8">
                            <div>
                                <label class="ml-2 text-xs font-medium">Your Bid</label>
                                <div class="relative mb-2">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                                        $
                                    </div>
                                    <input type="number" min="20"
                                        class="bg-custom-gray border-none focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm pl-8 w-full"
                                        placeholder="20.00" v-model="form.bids[index].amount" />
                                </div>
                            </div>
                            <label class="ml-2 text-xs font-medium">States you're licensed in:</label>
                            <Multiselect :options="stateOptions" v-model="form.typesWithStates[callType.id]"
                                track-by="value" label="label" mode="tags" :close-on-select="false"
                                placeholder="Select a state">
                            </Multiselect>
                        </div>
                    </div>

                    <InputError class="mt-2" :message="form.errors.insurance_type" />
                   <div v-if="firstStepErrors">
                        <div
                            v-if="firstStepErrors.typesWithStates"
                            class="text-red-500"
                            v-text="firstStepErrors.typesWithStates[0]"
                        ></div>
                   </div>
                   
                </div>

                <div class="flex mt-10">
                    <input id="checked-checkbox" type="checkbox" v-model="form.consent"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                    <label for="checked-checkbox" class="ml-2 text-xs font-medium text-gray-900 dark:text-gray-400">
                        By clicking Continue, I agree to email marketing, the Terms and
                        Conditions (which include mandatory arbitration), Privacy Policy,
                        and site visit recordation by Trusted Form and Jornaya. I provide my
                        express written consent and electronic signature to receive
                        monitored or recorded phone sales calls and text messages from
                        AllCalls.io regarding products and services including Medicare
                        Supplement, Medicare Advantage, and Prescription Drug Plans on the
                        landline or mobile number I provided even if I am on a federal or
                        State do not call registry. I confirm that the phone number set
                        forth above is accurate and I am the regular user of the phone. I
                        understand these calls may be generated using an automated dialing
                        system and may contain pre-recorded and artificial voice messages
                        and that consenting is not required to receive a quote or speak with
                        an agent and that I can revoke my consent at any time by any
                        reasonable means. To receive a quote without providing consent,
                        please call (866) 523-1718. For SMS message campaigns: Text STOP to
                        stop and HELP for help. Msg and data rates may apply. Periodic
                        messages; max. 30/month.
                    </label>
                </div>
                <p class="p-5" v-if="consentError == true" style="color: red;">{{ consentErro }}</p>

                <div class="flex justify-between items-center">
                    <div class="mt-4">
                        <a href="#" @click.prevent="goBack"
                            class="border border-gray-500 inline-flex items-center px-3 py-2 bg-white rounded-md font-semibold text-md text-custom-blue uppercase tracking-widest hover:bg-custom-blue hover:drop-shadow-2xl hover:text-custom-green hover:ring-2 hover:ring-custom-sky focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-4 h-4 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                            </svg>

                            Back</a>
                    </div>

                    <div class="flex items-center justify-end mt-4 mb-10">
                       

                        <PrimaryButton type="button" @click.prevent="submit" class="ml-4"
                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Register

                        </PrimaryButton>
                    </div>
                </div>
            </div>
        </form>
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
/* 
.carousel__item {
  min-height: 200px;
  width: 100%;
  background-color: var(--vc-clr-primary);
  color: var(--vc-clr-white);
  font-size: 20px;
  border-radius: 8px;
  display: flex;
  justify-content: center;
  align-items: center;
}

.carousel__slide {
  padding: 10px;
}

.carousel__prev,
.carousel__next {
  box-sizing: content-box;
  border: 5px solid white;
} */
</style>
