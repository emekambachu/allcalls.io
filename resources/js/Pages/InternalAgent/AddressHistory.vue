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
    Current_address_data:Object
});
console.log('userData', props.userData);
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
    history_address1: { id: 1, state: "Choose", zip_code: '', address: '', city: '', move_in_date: '', move_out_date: '', },
    history_address2: { id: 2, state: "Choose", zip_code: '', address: '', city: '', move_in_date: '', move_out_date: '', },
    history_address3: { id: 3, state: "Choose", zip_code: '', address: '', city: '', move_in_date: '', move_out_date: '', },
    history_address4: { id: 4, state: "Choose", zip_code: '', address: '', city: '', move_in_date: '', move_out_date: '', },
    history_address5: { id: 5, state: "Choose", zip_code: '', address: '', city: '', move_in_date: '', move_out_date: '', },
    history_address6: { id: 6, state: "Choose", zip_code: '', address: '', city: '', move_in_date: '', move_out_date: '', },
    history_address7: { id: 7, state: "Choose", zip_code: '', address: '', city: '', move_in_date: '', move_out_date: '', },
})
    if(props.userData.internal_agent_contract){
        form.value.history_address1.address = props.userData.internal_agent_contract.address
        form.value.history_address1.city = props.userData.internal_agent_contract.city
        form.value.history_address1.state = props.userData.internal_agent_contract.state
        form.value.history_address1.zip_code = props.userData.internal_agent_contract.zip
        form.value.history_address1.move_in_date = props.userData.internal_agent_contract.move_in_date
        form.value.history_address1.move_out_date = new Date()
    }
    console.log('Current_address_data', props.Current_address_data);

if (props.userData.internal_agent_contract && props.userData.internal_agent_contract.addresses) {
    props.userData.internal_agent_contract.addresses.forEach((address, index) => {
        // Check if the index is within the range of your form addresses
        // console.log('address',address);
        if (index < addres_history.value.length) {
            const formKey = `history_address${index + 1}`;
            console.log('formKey',formKey);
            if(formKey !== 'history_address1'){
                form.value[formKey].address = address.address;
                // Set other properties such as city, state, zip_code as needed
                form.value[formKey].city = address.city;
                form.value[formKey].state = address.state;
                form.value[formKey].zip_code = address.zip;
                form.value[formKey].state = address.state;
                form.value[formKey].move_in_date = address.move_in_date;
                form.value[formKey].move_out_date = address.move_out_date;
            }
            
            // Add more properties as needed
        }
    });
}

let hasValidationErrors = ref({});

let pageTop = () => {
    var element = document.getElementById("modal_main_id");
    element.scrollIntoView();
}
let dateFormat = (data) => {
    if (data) {
        let date = new Date(data)
        const day = date.getDate().toString().padStart(2, "0"); // Add leading zero if needed
        const month = (date.getMonth() + 1).toString().padStart(2, "0"); // Month is zero-based, so add 1
        const year = date.getFullYear();
        // Create the formatted date string
        return `${day}/${month}/${year}`;
    }

}
let getYearDifference = (date1, date2) => {
    const date1Parts = date1.split('/');
    const date2Parts = date2.split('/');
    const d1 = new Date(+date1Parts[2], date1Parts[0] - 1, +date1Parts[1]);
    const d2 = new Date(+date2Parts[2], date2Parts[0] - 1, +date2Parts[1]);
    const diffTime = Math.abs(d2 - d1);
    const diffYears = diffTime / (1000 * 60 * 60 * 24 * 365);
    return diffYears;
}
let yearError = ref(false)
const ChangeTab = () => {
    if (page.props.auth.role === 'admin') {
        emits("changeTab");
    } else {
        hasValidationErrors.value = {};
        let isValid = true; // Initialize a flag to check if all elements are valid
        if (page.props.auth.role === 'internal-agent') {
            for (const history of addres_history.value) {
                const formData = form.value[history.address];
                // Check if any field is filled and if it's a string
                if (
                    (typeof formData.address === 'string' && formData.address.trim() !== '') ||
                    (typeof formData.city === 'string' && formData.city.trim() !== '') ||
                    (typeof formData.zip_code === 'string' && formData.zip_code.trim() !== '') ||
                    formData.state !== 'Choose' ||
                    formData.move_in_date !== '' ||
                    formData.move_out_date !== ''
                ) {
                    // If any field is filled, check if all fields are filled for the current address
                    if (
                        (typeof formData.address !== 'string' || formData.address.trim() === '') ||
                        (typeof formData.city !== 'string' || formData.city.trim() === '') ||
                        (typeof formData.zip_code !== 'string' || formData.zip_code.trim() === '') ||
                        formData.state === 'Choose' ||
                        (!formData.move_in_date || !formData.move_out_date)
                    ) {
                        hasValidationErrors.value[history.address] = {
                            address: !formData.address || typeof formData.address !== 'string',
                            city: !formData.city || typeof formData.city !== 'string',
                            state: formData.state === 'Choose',
                            zip_code: !formData.zip_code || typeof formData.zip_code !== 'string',
                            move_in_date: !formData.move_in_date,
                            move_out_date: !formData.move_out_date,
                        };
                        isValid = false; // Set isValid to false if there's a validation error
                    }
                }

            }
        }
        let accumulatedYears = 0;
        // let addresdCount = 0;
        for (const history of addres_history.value) {
            const formData = form.value[history.address];
            if (formData.move_in_date && formData.move_out_date) {
                const differenceInYears = getYearDifference(dateFormat(formData.move_in_date), dateFormat(formData.move_out_date))
                accumulatedYears += differenceInYears;
                // addresdCount++
            }

        }
        if (isValid && accumulatedYears < 7) {
            yearError.value = true
            const firstAddress = addres_history.value[0];
            const firstFormData = form.value[firstAddress.address];
            const isFirstAddressEmpty =
                !firstFormData.address ||
                !firstFormData.city ||
                firstFormData.state === "Choose" ||
                !firstFormData.zip_code ||
                !firstFormData.move_in_date ||
                !firstFormData.move_out_date;
            if (isFirstAddressEmpty) {
                hasValidationErrors.value[firstAddress.address] = {
                    address: !firstFormData.address || typeof firstFormData.address !== 'string',
                    city: !firstFormData.city || typeof firstFormData.city !== 'string',
                    state: firstFormData.state === 'Choose',
                    zip_code: !firstFormData.zip_code || typeof firstFormData.zip_code !== 'string',
                    move_in_date: !firstFormData.move_in_date,
                    move_out_date: !firstFormData.move_out_date,
                    year_error: !firstFormData.year_error,
                };
            }
            isValid = false;
            setTimeout(() => {
                yearError.value = false
            }, 10000);
        }
        if (isValid) {
            if (page.props.auth.role === 'internal-agent') {
                yearError.value = false
                emits("addRessHistory", form.value);
            } else {
                emits("changeTab");
            }
        } else {
            pageTop()
        }
    }
}

let ChangeTabBack = () => {
    emits("goback");
}

let enforceFiveDigitInput = (fieldName, key) => {
    // addres_history.value.forEach((history) => {
    //     let field = form.value[history.address][val];
    //     if (field) {
    //         form.value[history.address][val] = fieldName.replace(/[^0-9]/g, '');
    //         if (fieldName.length > 5) {
    //             form.value[history.address][val] = fieldName.slice(0, 5);
    //         }
    //     }
    // })
    let field = form.value[key].zip_code;
    if (field) {
        form.value[key].zip_code = field.replace(/[^0-9]/g, '').slice(0, 5);
    }
    
}
</script>
<template>
    <h1 style="background-color: #134576;" class="mb-4	text-center rounded-md py-2 text-white">
        Please Provide Your Address History for the Past 7 Years
    </h1>
    <div v-if="yearError">
        <div 
            class="bg-red-100 mb-4  border border-red-400 text-red-700 px-4 py-2 rounded relative" role="alert">
            <span class="block sm:inline">The combined duration of all residences must exceed 7 years.</span>
        </div>
        <hr class="w-100 h-1 my-4 bg-gray-600 border-0 rounded dark:bg-gray-700">
    </div>


    <div v-for="(history, index) in addres_history" :key="history.id">
        <div class="grid lg:grid-cols-3 mb-2  md:grid-cols-2 sm:grid-cols-1 gap-4">
            <div>
                <label for="last_name" class="block mb-2 text-sm font-black text-gray-900 " v-text="index === 0 ? 'Current Address' : 'Home Address' " ></label>
                <div>
                    <input :disabled="page.props.auth.role === 'admin'" type="text" v-model="form[history.address].address"
                        id="default-input"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                    <span style="font-size: 14px;">Include Apt/Unit #</span>
                </div>
                <div v-if="hasValidationErrors[history.address]">
                    <span v-if="hasValidationErrors[history.address].address" class="text-red-600">Home Address is
                        required</span>
                </div>
            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 ">City
                </label>
                <input :disabled="page.props.auth.role === 'admin'" type="text" v-model="form[history.address].city"
                    id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                <div v-if="hasValidationErrors[history.address]">
                    <span v-if="hasValidationErrors[history.address].city" class="text-red-600">City is
                        required</span>
                </div>
            </div>

            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 ">
                    State</label>
                <select :disabled="page.props.auth.role === 'admin'" v-model="form[history.address].state" id="countries"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                    <option>Choose </option>
                    <option v-for="state in states" :value="state.id">{{ state.full_name }} </option>
                </select>
                <div v-if="hasValidationErrors[history.address]">
                    <span v-if="hasValidationErrors[history.address].state" class="text-red-600"> State is
                        required</span>
                </div>
            </div>

            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 ">Zip Code</label>
                <input :disabled="page.props.auth.role === 'admin'" type="text" @input="enforceFiveDigitInput(form[history.address].zip_code, history.address)" 
                    v-model="form[history.address].zip_code" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                <div v-if="hasValidationErrors[history.address]">
                    <span v-if="hasValidationErrors[history.address].zip_code" class="text-red-600">Zip Code is
                        required</span>
                </div>
            </div>

            <div>
                <label for="middle_name" class="block mb-2   text-sm font-black text-gray-900 ">Move-In
                    Date</label>
                <VueDatePicker :disabled="page.props.auth.role === 'admin'" v-model="form[history.address].move_in_date"
                    format="dd-MMM-yyyy" :maxDate="maxDate" auto-apply>
                </VueDatePicker>
                <div v-if="hasValidationErrors[history.address]">
                    <span v-if="hasValidationErrors[history.address].move_in_date" class="text-red-600">This Field is
                        required</span>
                </div>
            </div>
            <div>
                <label for="middle_name" class="block mb-2   text-sm font-black text-gray-900 " v-text="'Move-Out-Date'">  </label>
                <VueDatePicker :disabled="page.props.auth.role === 'admin'" v-model="form[history.address].move_out_date"
                    format="dd-MMM-yyyy" :maxDate="maxDate" auto-apply>
                </VueDatePicker>
                <div v-if="hasValidationErrors[history.address]">
                    <span v-if="hasValidationErrors[history.address].move_out_date" class="text-red-600">This Field is
                        required</span>
                </div>
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