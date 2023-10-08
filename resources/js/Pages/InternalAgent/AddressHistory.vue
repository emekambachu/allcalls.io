<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed } from "vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
let maxDate = ref(new Date)
maxDate.value.setHours(23, 59, 59, 999);
const emits = defineEmits();
let props = defineProps({
    states: Array,
    userData: Object,
    isLoading: Boolean,
});
let page = usePage();
let addres_history = ref([
    {
        address: 'history_address1',
    },
    {
        address: 'history_address2',
    },
    {
        address: 'history_address3',
    },
    {
        address: 'history_address4',
    },
    {
        address: 'history_address5',
    },
    {
        address: 'history_address6',
    },

    {
        address: 'history_address7',
    },
])
let form = ref({
    history_address1: { id: 1, state: "Choose", zip_code: '', address: '', city: '', move_in_date: null, move_out_date: null, },
    history_address2: { id: 2, state: "Choose", zip_code: '', address: '', city: '', move_in_date: null, move_out_date: null, },
    history_address3: { id: 3, state: "Choose", zip_code: '', address: '', city: '', move_in_date: null, move_out_date: null, },
    history_address4: { id: 4, state: "Choose", zip_code: '', address: '', city: '', move_in_date: null, move_out_date: null, },
    history_address5: { id: 5, state: "Choose", zip_code: '', address: '', city: '', move_in_date: null, move_out_date: null, },
    history_address6: { id: 6, state: "Choose", zip_code: '', address: '', city: '', move_in_date: null, move_out_date: null, },
    history_address7: { id: 7, state: "Choose", zip_code: '', address: '', city: '', move_in_date: null, move_out_date: null, },
})
if (props.userData.internal_agent_contract && props.userData.internal_agent_contract.addresses) {
    props.userData.internal_agent_contract.addresses.forEach((address, index) => {
        // Check if the index is within the range of your form addresses
        if (index < addres_history.value.length) {
            const formKey = `history_address${index + 1}`;
            form.value[formKey].address = address.address;
            // Set other properties such as city, state, zip_code as needed
            form.value[formKey].city = address.city;
            form.value[formKey].state = address.state;
            form.value[formKey].zip_code = address.zip;
            form.value[formKey].state = address.state;
            form.value[formKey].move_in_date = address.move_in_date;
            form.value[formKey].move_out_date = address.move_out_date;

            // Add more properties as needed
        }
    });
}

let hasValidationErrors = ref({});

const ChangeTab = () => {
    hasValidationErrors.value = {};
    let isValid = true; // Initialize a flag to check if all elements are valid
    if (page.props.auth.role === 'internal-agent') {
        for (const history of addres_history.value) {
            const formData = form.value[history.address];
            console.log('formData', formData);

            // Check if any field is filled and if it's a string
            if (
                (typeof formData.address === 'string' && formData.address.trim() !== '') ||
                (typeof formData.city === 'string' && formData.city.trim() !== '') ||
                (typeof formData.zip_code === 'string' && formData.zip_code.trim() !== '') ||
                formData.state !== 'Choose'
            ) {
                // If any field is filled, check if all fields are filled for the current address
                if (
                    (typeof formData.address !== 'string' || formData.address.trim() === '') ||
                    (typeof formData.city !== 'string' || formData.city.trim() === '') ||
                    (typeof formData.zip_code !== 'string' || formData.zip_code.trim() === '') ||
                    formData.state === 'Choose'
                ) {
                    hasValidationErrors.value[history.address] = {
                        address: !formData.address || typeof formData.address !== 'string',
                        city: !formData.city || typeof formData.city !== 'string',
                        state: formData.state === 'Choose',
                        zip_code: !formData.zip_code || typeof formData.zip_code !== 'string',
                    };
                    isValid = false; // Set isValid to false if there's a validation error
                }
            }
        }
    }
    // If isValid is still true, it means there are no validation errors
    if (isValid) {
        if (page.props.auth.role === 'internal-agent') {
            emits("addRessHistory", form.value);
        } else {
            emits("changeTab");
        }
    } else {
        var element = document.getElementById("modal_main_id");
        element.scrollIntoView();
    }
}


let ChangeTabBack = () => {
    emits("goback");
}
// watch(form.value, (newForm, oldForm) => {
//     emits("addRessHistory", newForm);
// });
let enforceFiveDigitInput = (fieldName, val) => {
    addres_history.value.forEach((history) => {
        let field = form.value[history.address][fieldName];
        if (field) {
            field = field.toString().replace(/[^0-9]/g, '');
            form.value[history.address][fieldName] = field
            if (field.length > 5) {
                field = field.slice(0, 5);
                form.value[history.address][fieldName] = field
            }
        }
    })
}
</script>
<template>
    <h1 style="background-color: #134576;" class="mb-4	text-center rounded-md py-2 text-white">
        Please Provide Your Address History for the Past 7 Years
    </h1>
    <div v-for="(history, index) in addres_history" :key="history.id">

        <div class="grid lg:grid-cols-3 mb-2  md:grid-cols-2 sm:grid-cols-1 gap-4">
            <div>
                <label for="last_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Home
                    Address</label>
                <div>
                    <input type="text" v-model="form[history.address].address" id="default-input"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <span style="font-size: 14px;">Include Apt/Unit #</span>
                </div>
                <div v-if="hasValidationErrors[history.address]">
                    <span v-if="hasValidationErrors[history.address].address" class="text-red-600">Home Address is
                        required</span>
                </div>
            </div>



            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">City
                </label>
                <input type="text" v-model="form[history.address].city" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <div v-if="hasValidationErrors[history.address]">
                    <span v-if="hasValidationErrors[history.address].city" class="text-red-600">City is
                        required</span>
                </div>
            </div>

            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">
                    State</label>
                <select v-model="form[history.address].state" id="countries"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option>Choose </option>
                    <option v-for="state in states" :value="state.id">{{ state.full_name }} </option>
                </select>
                <div v-if="hasValidationErrors[history.address]">
                    <span v-if="hasValidationErrors[history.address].state" class="text-red-600"> State is
                        required</span>
                </div>
            </div>

            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Zip Code</label>
                <input type="number" @input="enforceFiveDigitInput('zip_code')" v-model="form[history.address].zip_code"
                    id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <div v-if="hasValidationErrors[history.address]">
                    <span v-if="hasValidationErrors[history.address].zip_code" class="text-red-600">Zip Code is
                        required</span>
                </div>
            </div>

            <div>
                <label for="middle_name" class="block mb-2   text-sm font-black text-gray-900 dark:text-white">Move-In
                    Date</label>
                <VueDatePicker v-model="form[history.address].move_in_date" format="dd-MMM-yyyy" :maxDate="maxDate"
                    auto-apply>
                </VueDatePicker>
            </div>
            <div>
                <label for="middle_name" class="block mb-2   text-sm font-black text-gray-900 dark:text-white">Move-Out
                    Date</label>
                <VueDatePicker v-model="form[history.address].move_out_date" format="dd-MMM-yyyy" :maxDate="maxDate"
                    auto-apply>
                </VueDatePicker>
            </div>

        </div>



        <hr class="w-100 h-1 my-4 bg-gray-600 border-0 rounded dark:bg-gray-700">
    </div>
    <div class="px-5 pb-6">
        <div class="flex justify-between flex-wrap">
            <div class="mt-4">

                <button type="button" @click.prevent="ChangeTabBack" class="button-custom-back px-3 py-2 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                    </svg>
                    Back
                </button>
            </div>
            <div class="mt-4">
                <button type="button" :class="{ 'opacity-25': isLoading }" :disabled="isLoading" @click="ChangeTab"
                    class="button-custom px-3 py-2 rounded-md">
                    <global-spinner :spinner="isLoading" /> Next
                </button>

            </div>
        </div>
    </div>
</template>