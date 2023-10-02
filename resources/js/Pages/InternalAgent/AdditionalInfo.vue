<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed } from "vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { format } from 'date-fns';
let maxDate = ref(new Date)
let props = defineProps({
    firstStepErrors: Object,
    states: Array,
    userData: Object,
});
let page = usePage();
console.log('userData address additional info', props.userData);
const emits = defineEmits();
let countries = ref([{
    'name': 'United States of America',
    'code': "USA"
}])
let form = ref({
    resident_country: 'USA',
    resident_your_home: null,
    resident_city: null,
    resident_state: 'Choose',
    resident_maiden_name: null,
    aml_provider: null,
    training_completion_date: null,
    limra_password: null,

})
if (page.props.auth.role === 'admin' && props.userData.internal_agent_contract) {
    form.value = props.userData.internal_agent_contract.additional_info
}
watch(form.value, (newForm, oldForm) => {
    emits("additionalInfoData", newForm);
});
let ChangeTab = () => {
    for (const key in props.firstStepErrors) {
        if (props.firstStepErrors.hasOwnProperty(key)) {
            props.firstStepErrors[key] = [];
        }
    }
    // Define an array of field names that are required
    const requiredFields = [
        "resident_country", "resident_your_home", "resident_city", 'resident_state',
    ];
    if (page.props.auth.role === 'internal-agent') {
        requiredFields.forEach(fieldName => {
            if (form.value[fieldName] === null || form.value[fieldName] === "" || form.value[fieldName] === "Choose") {
                props.firstStepErrors[fieldName] = [`This  field is required.`];
            }
        });
    }
    // Check if there are any errors
    const hasErrors = Object.values(props.firstStepErrors).some(errors => errors.length > 0);
    if (!hasErrors) {
        emits("changeTab");
    } else {
        var element = document.getElementById("modal_main_id");
        element.scrollIntoView();
    }
}
let ChangeTabBack = () => {
    emits("goback");
}
</script>
<style scoped>
.input-custom {
    background-color: #d3e4eb;
    border: solid 2px #a7d4ea;
    height: 35px;
}

.small-input-custom {
    width: 35px;
    margin-right: 5px;
}

.flex-container {
    display: flex;
}

.flex-item {
    text-align: center;
    padding: 10px;
    border: 1px solid #000;
}
</style>
<template>
    <h1 style="background-color: #134576;" class="mb-4	text-center rounded-md py-2 text-white">
        Additional Information
    </h1>
    <div>

        <div class="grid lg:grid-cols-2 mb-2  md:grid-cols-2 sm:grid-cols-1 gap-4">
            <div>
                <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Resident
                    Country<span class="text-red-500 ">*</span></label>
                <select v-model="form.resident_country" id="countries"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option v-for="country in countries" :value="country.code">{{ country.name }} </option>
                </select>
                <!-- <input type="text" v-model="form.resident_country" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"> -->
                <div v-if="firstStepErrors.resident_country" class="text-red-500"
                    v-text="firstStepErrors.resident_country[0]"></div>
            </div>
            <div class=" mt-4 lg:ml-4 sm:ml-0">
                <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Do you own
                    your home?<span class="text-red-500 ">*</span></label>
                <div class="flex">
                    <div class="flex items-center mr-4">

                        <input id="default-radio-1-1" v-model="form.resident_your_home" type="radio" value="YES"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="default-radio-1-1"
                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">YES</label>
                    </div>
                    <div class="flex items-center">
                        <input id="default-radio-2-2" v-model="form.resident_your_home" type="radio" value="NO"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="default-radio-2-2"
                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">NO</label>
                    </div>
                </div>
                <div v-if="firstStepErrors.resident_your_home" class="text-red-500"
                    v-text="firstStepErrors.resident_your_home[0]"></div>
            </div>



        </div>
        <div class="grid lg:grid-cols-3 mb-2  md:grid-cols-2 sm:grid-cols-1 gap-4">
            <div>
                <label for="last_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">City of
                    Birth<span class="text-red-500 ">*</span></label>
                <input type="text" v-model="form.resident_city" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <div v-if="firstStepErrors.resident_city" class="text-red-500" v-text="firstStepErrors.resident_city[0]">
                </div>
            </div>
            <div>
                <label for="last_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white"> State of
                    Birth<span class="text-red-500 ">*</span></label>
                <select v-model="form.resident_state" id="countries"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option>Choose </option>
                    <option v-for="state in states" :value="state.id">{{ state.full_name }} </option>
                </select>
                <div v-if="firstStepErrors.resident_state" class="text-red-500" v-text="firstStepErrors.resident_state[0]">
                </div>
            </div>

            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Maiden
                    Name</label>
                <input type="text" v-model="form.resident_maiden_name" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <div v-if="firstStepErrors.resident_maiden_name" class="text-red-500"
                    v-text="firstStepErrors.resident_maiden_name[0]"></div>
            </div>
        </div>

        <!-- <h1 style="background-color: #134576;" class="mb-4 mt-4	text-center rounded-md py-2 text-white">
            Anti-Money Loundering
        </h1>
        <p>
            Anti-Money Laundering (AML) training is required by all of our carriers. Please provide the details of your most
            recent AML Training course below. If you have not completed an AML course with LIMRA or RegEd within the last
            year you will need to do so. Instructions for completing RegEd's free AML training are below.
        </p>
        <div class="grid lg:grid-cols-2 mb-2  md:grid-cols-2 sm:grid-cols-1 gap-4">

            <div class=" mt-4 lg:ml-4 sm:ml-0">
                <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">AML
                    Provider<span class="text-red-500 ">*</span></label>
                <div class="flex">
                    <div class="flex items-center mr-4">

                        <input id="default-radio-3" v-model="form.aml_provider" type="radio" value="RegEd"
                            name="aml_provider"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="default-radio-3"
                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">RegEd</label>
                    </div>
                    <div class="flex items-center lg:ml-20 md:ml-10 sm:-ml-0">
                        <input id="default-radio-4" v-model="form.aml_provider" type="radio" value="LIMRA"
                            name="aml_provider"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="default-radio-4"
                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">LIMRA</label>
                    </div>
                </div>
                <div v-if="firstStepErrors.aml_provider" class="text-red-500" v-text="firstStepErrors.aml_provider[0]">
                </div>
            </div>
            <div>
                <label for="middle_name" class="block mb-2   text-sm font-black text-gray-900 dark:text-white">Training
                    Completion Date<span class="text-red-500 ">*</span></label>
                <VueDatePicker v-model="form.training_completion_date" format="dd-MMM-yyyy" :maxDate="maxDate">
                </VueDatePicker>
                <div v-if="firstStepErrors.training_completion_date" class="text-red-500"
                    v-text="firstStepErrors.training_completion_date[0]"></div>
            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">If completed
                    through LIMRA, please provide your LIMRA password<span class="text-red-500 ">*</span></label>
                <input type="text" v-model="form.limra_password" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <div v-if="firstStepErrors.limra_password" class="text-red-500" v-text="firstStepErrors.limra_password[0]">
                </div>
            </div>
        </div>
        <div><strong>Steps for completing RegEd's Anti-Money Laundering Training:</strong></div>
        <div>- Go to https://secure.reged.com/Login/vu/Virtual University/EQUIS.</div>
        <div>- Register as a New User if you do not have an existing account with RegEd.</div>
        <div class="ml-2">If you do have an existing account, login with you email and password.</div>
        <div>- Once complete, attach a copy of your certificate of completion to this contracting packet.</div>

        <h1 style="background-color: #134576;" class="mb-4 mt-4	text-center rounded-md py-2 text-white">
            Errors & Omissions Insurance
        </h1>
        <p>
            If you currently hold Errors and Omissions insurance, please include a copy of your coverage certificate.
        </p>
        <p class="my-3">
            You are not required to hold E&O coverage to begin writing business, but it is highly recommended. Please
            reference the table below for information concerning which carriers will or will not appoint without E&O
            coverage:
        </p>
        <div class="grid lg:grid-cols-2 mb-2 text-center  md:grid-cols-2 sm:grid-cols-1 ">
            <div class="border py-2"> <strong> E&O NOT Required</strong></div>
            <div class="border py-2"><strong>Columbian Financial Group (CFG)</strong></div>
            <div class="border py-1">Columbian Financial Group (CFG)</div>
            <div class="border py-1">Americo</div>
            <div class="border py-1">Foresters (E&O Required for Smart UL Only)</div>
            <div class="border py-1"></div>
            <div class="border py-1">Government Personnel Mutual Life</div>
            <div class="border py-1"></div>
        </div>
        <p class="my-3">
            Equis Financial has a special group pricing arranged with 360 Coverage Pros. For less than $400 a year, you can
            be completely covered. For more information, visit: www.360coveragepros.com/equis/errors-and-omissions
        </p> -->
        <div class="px-5 pb-6">
            <div class="flex justify-between flex-wrap">
                <div class="mt-4">

                    <button type="button" @click.prevent="ChangeTabBack" class="button-custom-back px-3 py-2 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                        </svg>
                        Back
                    </button>
                </div>
                <div class="mt-4">
                    <button type="button" @click="ChangeTab" class="button-custom px-3 py-2 rounded-md">
                        Next Step
                    </button>

                </div>
            </div>
        </div>
    </div>
</template>