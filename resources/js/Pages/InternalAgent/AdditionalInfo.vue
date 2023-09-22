<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
let maxDate = ref(new Date)
const selectedDate = computed({
  get: () => form.training_completion_date,
  set: (newValue) => {
    // Set the selected date with the time portion set to midnight
    form.training_completion_date = new Date(newValue.getFullYear(), newValue.getMonth(), newValue.getDate());
  },
});
let form = ref({
    

})
const emits = defineEmits();
watch(form.value, (newForm, oldForm) => {
    emits("additionalInfoData", newForm);
});
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
    <div>

        <div class="grid lg:grid-cols-2 mb-2  md:grid-cols-2 sm:grid-cols-1 gap-4">
            <div>
                <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Resident
                    Country</label>
                <input type="text" v-model="form.resident_country" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>
            <div class=" mt-4 lg:ml-4 sm:ml-0">
                <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Do you own
                    your home?</label>
                <div class="flex">
                    <div class="flex items-center mr-4">

                        <input id="default-radio-1" v-model="form.resident_your_home" type="radio" value="YES" 
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="default-radio-1"
                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">YES</label>
                    </div>
                    <div class="flex items-center">
                        <input id="default-radio-2" v-model="form.resident_your_home" type="radio" value="NO" 
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="default-radio-2"
                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">NO</label>
                    </div>
                </div>
            </div>
            <div>
                <label for="last_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">City, state of
                    Birth</label>
                <input type="text" v-model="form.resident_city_state" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Maiden
                    Name</label>
                <input type="text" v-model="form.resident_maiden_name" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>

        </div>

        <h1 style="background-color: #134576;" class="mb-4 mt-4	text-center rounded-md py-2 text-white">
            Anti-Money Loundering
        </h1>
        <p>
            Anti-Money Laundering (AML) training is required by all of our carriers. Please provide the details of your most recent AML Training course below. If you have not completed an AML course with LIMRA or RegEd within the last year you will need to do so. Instructions for completing RegEd's free AML training are below.
        </p>
        <div class="grid lg:grid-cols-2 mb-2  md:grid-cols-2 sm:grid-cols-1 gap-4">
           
            <div class=" mt-4 lg:ml-4 sm:ml-0">
                <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">AML Provider</label>
                <div class="flex">
                    <div class="flex items-center mr-4">

                        <input id="default-radio-3" v-model="form.aml_provider" type="radio" value="RegEd" name="aml_provider"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="default-radio-3"
                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">RegEd</label>
                    </div>
                    <div class="flex items-center lg:ml-20 md:ml-10 sm:-ml-0">
                        <input id="default-radio-4" v-model="form.aml_provider" type="radio" value="LIMRA" name="aml_provider"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="default-radio-4"
                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">LIMRA</label>
                    </div>
                </div>
            </div>
            <div>
                <label for="middle_name" class="block mb-2   text-sm font-black text-gray-900 dark:text-white">Training Completion Date</label>
                <VueDatePicker v-model="selectedDate"  format="dd-MMM-yyyy" :maxDate="maxDate">
                </VueDatePicker>
            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">If completed through LIMRA, please provide your LIMRA password:</label>
                <input type="text" v-model="form.limra_password" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
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
            You are not required to hold E&O coverage to begin writing business, but it is highly recommended. Please reference the table below for information concerning which carriers will or will not appoint without E&O coverage:
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
            Equis Financial has a special group pricing arranged with 360 Coverage Pros. For less than $400 a year, you can be completely covered. For more information, visit: www.360coveragepros.com/equis/errors-and-omissions
        </p>
    </div>
</template>