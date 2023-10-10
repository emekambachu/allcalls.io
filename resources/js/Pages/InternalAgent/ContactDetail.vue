<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed } from "vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
let page = usePage();
let props = defineProps({
    firstStepErrors: Object,
    userData: Object,
    states: Array,
    isLoading:Boolean,
});
let individual_business = ref(false)

// console.log('user dagta', props.userData.internal_agent_contract);
let maxDate = ref(new Date)
maxDate.value.setHours(23, 59, 59, 999);
let form = ref({
    first_name: null,
    last_name: null,
    middle_name: null,
    ssn: null,
    gender: 'Choose',
    dob: null,
    marital_status: 'Choose',
    cell_phone: null,
    home_phone: null,
    fax: null,
    email: null,
    driver_license_no: null,
    driver_license_state: 'Choose',
    address: null,
    city: null,
    state: "Choose",
    zip: null,
    move_in_date: null,

    move_in_address: null,
    move_in_city: null,
    move_in_state: 'Choose',
    move_in_zip: null,

    resident_insu_license_no: null,
    resident_insu_license_state: 'Choose',
    doing_business_as: null,

    business_name: null,
    business_tax_id: null,
    business_agent_name: null,
    business_agent_title: null,
    business_company_type: 'Choose',
    business_insu_license_no: null,
    business_office_fax: null,
    business_office_phone: null,
    business_email: null,
    business_website: null,
    business_address: null,
    business_city: null,
    business_state: 'Choose',
    business_zip: null,
    business_move_in_date: null,
})
onMounted(() => {
    if (props.userData?.internal_agent_contract) {
        form.value = props.userData.internal_agent_contract
        if(props.userData.internal_agent_contract.business_name != null){
            individual_business.value = true
        }
    } else {
        form.value.first_name = props.userData.first_name
        form.value.last_name = props.userData.last_name
        form.value.email = props.userData.email
        form.value.cell_phone = props.userData.phone
    }

})
const emits = defineEmits();
// watch(form.value, (newForm, oldForm) => {
//     emits("updateFormData", newForm);
// });
watch(individual_business, (newVal) => {
    if (newVal === false && page.props.auth.role != 'admin') {
            form.value.business_name = null,
            form.value.business_tax_id = null,
            form.value.business_agent_name = null,
            form.value.business_agent_title = null,
            form.value.business_company_type = 'Choose',
            form.value.business_insu_license_no = null,
            form.value.business_office_fax = null,
            form.value.business_office_phone = null,
            form.value.business_email = null,
            form.value.business_website = null,
            form.value.business_address = null,
            form.value.business_city = null,
            form.value.business_state = null,
            form.value.business_zip = null,
            form.value.business_move_in_date = null
    }
})
let ChangeTab = () => {
    if (page.props.auth.role === 'admin') {
        emits("changeTab");
    } else {
        for (const key in props.firstStepErrors) {
            if (props.firstStepErrors.hasOwnProperty(key)) {
                props.firstStepErrors[key] = [];
            }
        }
        // Define an array of field names that are required
        const businessInputs = [
            "business_name", "business_tax_id", "business_agent_name", "business_agent_title",
            "business_company_type", "business_insu_license_no", "business_office_fax",
            "business_office_phone", "business_email", "business_website", "business_address",
            "business_city", "business_zip", "business_move_in_date", 'business_state'
        ]
        const requiredFields = [
            "first_name", "last_name", "middle_name", "ssn", "gender", "dob", "marital_status",
            "cell_phone", "email", "driver_license_no", "driver_license_state",
            "address", "city", 'state', "zip", "move_in_date", "resident_insu_license_no", "resident_insu_license_state",
        ];
        // console.log('form', form.value);
        let hasBusinessValue = individual_business && businessInputs.some(fieldName => {
            const value = form.value[fieldName];
            return value !== null && value !== "Choose" && value !== '';
        });
        if (!hasBusinessValue && individual_business.value) {
            hasBusinessValue = true
        }
        const mergedFields = hasBusinessValue ? [...requiredFields, ...businessInputs] : requiredFields;

        mergedFields.forEach(fieldName => {
            if (page.props.auth.role === 'internal-agent') {
                if (form.value[fieldName] === null || form.value[fieldName] === "" || form.value[fieldName] === "Choose") {
                    props.firstStepErrors[fieldName] = [`This  field is required.`];
                }
            }

        });
        // Check if there are any errors
        const hasErrors = Object.values(props.firstStepErrors).some(errors => errors.length > 0);
        if (!hasErrors) {
            emits("updateFormData", { form: form.value, individual_business: individual_business.value });
        } else {
            var element = document.getElementById("modal_main_id");
            element.scrollIntoView();
        }
    }

}
let enforceFiveDigitInput = (fieldName, val) => {
    if (form.value[fieldName]) {
        form.value[fieldName] = form.value[fieldName].toString().replace(/[^0-9]/g, '');
        if (form.value[fieldName].length > 5) {
            form.value[fieldName] = form.value[fieldName].slice(0, 5);
        }
    }
}
</script>
<style scoped></style>
<template>
    <h1 style="background-color: #134576;" class="mb-4	text-center rounded-md py-2 text-white">
        New Producer Information
    </h1>
    <div>


        <div class="grid lg:grid-cols-3 mb-2  md:grid-cols-2 sm:grid-cols-1 gap-4">
            <div>
                <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 ">Last
                    Name<span class="text-red-500">*</span></label>
                <input type="text" v-model="form.last_name" id="default-input"
                    :disabled="page.props.auth.role === 'admin'"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                <div v-if="firstStepErrors.last_name" class="text-red-500" v-text="firstStepErrors.last_name[0]"></div>
            </div>
            <div>
                <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 ">First
                    Name<span class="text-red-500">*</span></label>
                <input type="text" v-model="form.first_name" id="default-input"
                    :disabled="page.props.auth.role === 'admin'"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                <div v-if="firstStepErrors.first_name" class="text-red-500" v-text="firstStepErrors.first_name[0]"></div>
            </div>
            <div>
                <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 ">Middle
                    Name<span class="text-red-500">*</span></label>
                <input type="text" v-model="form.middle_name" id="default-input"
                    :disabled="page.props.auth.role === 'admin'"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                <div v-if="firstStepErrors.middle_name" class="text-red-500" v-text="firstStepErrors.middle_name[0]"></div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 mb-2  md:grid-cols-2 sm:grid-cols-1 gap-4">
            <div>
                <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 ">Social
                    Security Number (SSN)<span class="text-red-500">*</span></label>
                <input type="number" v-model="form.ssn" id="default-input"
                    :disabled="page.props.auth.role === 'admin'"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                <div v-if="firstStepErrors.ssn" class="text-red-500" v-text="firstStepErrors.ssn[0]"></div>
            </div>
            <div>
                <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 ">Gender<span
                        class="text-red-500">*</span></label>
                <select v-model="form.gender" id="countries"
                    :disabled="page.props.auth.role === 'admin'"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                    <option>Choose </option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
                <div v-if="firstStepErrors.gender" class="text-red-500" v-text="firstStepErrors.gender[0]"></div>
            </div>
            <div>
                <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 ">Date of
                    Birth<span class="text-red-500">*</span></label>
                <VueDatePicker :disabled="page.props.auth.role === 'admin'"  v-model="form.dob" format="dd-MMM-yyyy" :maxDate="maxDate" auto-apply></VueDatePicker>
                <div v-if="firstStepErrors.dob" class="text-red-500" v-text="firstStepErrors.dob[0]"></div>
            </div>

        </div>

        <div class="grid lg:grid-cols-3 mb-2 md:grid-cols-2 sm:grid-cols-1 gap-4">
            <div>
                <label for="last_name" class="block mb-2 text-sm font-black text-gray-900 ">Cell
                    Phone#<span class="text-red-500">*</span></label>
                <input type="text" maxLength="10" v-model="form.cell_phone" id="default-input"
                    :disabled="page.props.auth.role === 'admin'"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                <div v-if="firstStepErrors.cell_phone" class="text-red-500" v-text="firstStepErrors.cell_phone[0]"></div>
            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 ">Home
                    Phone#</label>
                <input type="text" v-model="form.home_phone" maxLength="10" id="default-input"
                    :disabled="page.props.auth.role === 'admin'"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                <div v-if="firstStepErrors.home_phone" class="text-red-500" v-text="firstStepErrors.home_phone[0]"></div>
            </div>
            <div>
                <label for="middle_name" class="block mb-2 text-sm font-black text-gray-900 ">Fax#</label>
                <input type="text" maxLength="15" v-model="form.fax" id="default-input"
                    :disabled="page.props.auth.role === 'admin'"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                <div v-if="firstStepErrors.fax" class="text-red-500" v-text="firstStepErrors.fax[0]"></div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 mb-2  md:grid-cols-1 sm:grid-cols-1 gap-4">
            <div>
                <label for="middle_name" class="block mb-2 text-sm font-black text-gray-900 ">Email<span
                        class="text-red-500">*</span></label>
                <input type="email" v-model="form.email" id="default-input"
                    :disabled="page.props.auth.role === 'admin'"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                <div v-if="firstStepErrors.email" class="text-red-500" v-text="firstStepErrors.email[0]"></div>
            </div>

            <div>
                <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 ">Married
                    Status<span class="text-red-500">*</span></label>
                <select v-model="form.marital_status" id="countries"
                    :disabled="page.props.auth.role === 'admin'"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                    <option>Choose </option>
                    <option value="married">Married</option>
                    <option value="unmarried">UnMarried</option>
                </select>
                <div v-if="firstStepErrors.marital_status" class="text-red-500" v-text="firstStepErrors.marital_status[0]">
                </div>
            </div>
            <div>
                <label for="last_name" class="block mb-2 text-sm font-black text-gray-900 ">Drivers
                    License#<span class="text-red-500">*</span></label>
                <input type="text" v-model="form.driver_license_no" id="default-input"
                    :disabled="page.props.auth.role === 'admin'"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                <div v-if="firstStepErrors.driver_license_no" class="text-red-500"
                    v-text="firstStepErrors.driver_license_no[0]"></div>
            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 ">Driver Licence
                    State<span class="text-red-500">*</span></label>

                <select v-model="form.driver_license_state" id="countries"
                    :disabled="page.props.auth.role === 'admin'"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                    <option>Choose </option>
                    <option v-for="state in states" :value="state.id">{{ state.full_name }} </option>
                </select>
                <div v-if="firstStepErrors.driver_license_state" class="text-red-500"
                    v-text="firstStepErrors.driver_license_state[0]"></div>
            </div>
            <div>
                <label for="last_name" class="block mb-2 text-sm font-black text-gray-900 ">Current Address
                    (Residence)<span class="text-red-500">*</span></label>
                <div>
                    <input type="text" v-model="form.address" id="default-input"
                        :disabled="page.props.auth.role === 'admin'"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                    <span style="font-size: 14px;">Include Apt/Unit #</span>
                </div>
                <div v-if="firstStepErrors.address" class="text-red-500" v-text="firstStepErrors.address[0]"></div>
            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 ">City<span
                        class="text-red-500">*</span></label>
                <input type="text" v-model="form.city" id="default-input"
                    :disabled="page.props.auth.role === 'admin'"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                <div v-if="firstStepErrors.city" class="text-red-500" v-text="firstStepErrors.city[0]"></div>
            </div>

            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 ">
                    State<span class="text-red-500">*</span></label>
                <select v-model="form.state" id="countries"
                    :disabled="page.props.auth.role === 'admin'"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                    <option>Choose </option>
                    <option v-for="state in states" :value="state.id">{{ state.full_name }} </option>
                </select>
                <div v-if="firstStepErrors.state" class="text-red-500" v-text="firstStepErrors.state[0]"></div>
            </div>

            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 ">Zip Code<span
                        class="text-red-500">*</span></label>
                <input type="number" @input="enforceFiveDigitInput('zip')" v-model="form.zip" id="default-input"
                    :disabled="page.props.auth.role === 'admin'"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                <div v-if="firstStepErrors.zip" class="text-red-500" v-text="firstStepErrors.zip[0]"></div>
            </div>
            <div>
                <label for="middle_name" class="block mb-2   text-sm font-black text-gray-900 ">Move-In
                    Date<span class="text-red-500">*</span></label>
                <VueDatePicker :disabled="page.props.auth.role === 'admin'" v-model="form.move_in_date" format="dd-MMM-yyyy" :maxDate="maxDate" auto-apply>
                </VueDatePicker>
                <div v-if="firstStepErrors.move_in_date" class="text-red-500" v-text="firstStepErrors.move_in_date[0]">
                </div>
            </div>
        </div>
        <div class="grid lg:grid-cols-3 mb-2  md:grid-cols-2 sm:grid-cols-1 gap-4">
            <div>
                <label for="last_name" class="block mb-2 text-sm font-black text-gray-900 ">Mailing Address
                    (If Diffrent From Residence)</label>
                <div>
                    <input type="text" v-model="form.move_in_address" id="default-input"
                        :disabled="page.props.auth.role === 'admin'"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                    <span style="font-size: 14px;">Include Apt/Unit #</span>
                </div>
                <div v-if="firstStepErrors.move_in_address" class="text-red-500"
                    v-text="firstStepErrors.move_in_address[0]"></div>
            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 ">City</label>
                <input type="text" v-model="form.move_in_city" id="default-input"
                    :disabled="page.props.auth.role === 'admin'"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                <div v-if="firstStepErrors.move_in_city" class="text-red-500" v-text="firstStepErrors.move_in_city[0]">
                </div>
            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 ">
                    State</label>
                <select v-model="form.move_in_state" id="countries"
                    :disabled="page.props.auth.role === 'admin'"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                    <option>Choose </option>
                    <option v-for="state in states" :value="state.id">{{ state.full_name }} </option>
                </select>
                <div v-if="firstStepErrors.move_in_state" class="text-red-500" v-text="firstStepErrors.move_in_state[0]">
                </div>
            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 ">Zip Code</label>
                <input type="number" @input="enforceFiveDigitInput('move_in_zip')" v-model="form.move_in_zip" maxLength="5"
                    id="default-input"
                    :disabled="page.props.auth.role === 'admin'"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                <div v-if="firstStepErrors.move_in_zip" class="text-red-500" v-text="firstStepErrors.move_in_zip[0]"></div>
            </div>
        </div>
        <hr class="w-100 h-1 my-4 bg-gray-600 border-0 rounded dark:bg-gray-700">

        <div class="grid lg:grid-cols-3 mb-2  md:grid-cols-2 sm:grid-cols-1 gap-4">
            <div>
                <label for="last_name" class="block mb-2 text-sm font-black text-gray-900 ">Resident
                    Insurance License #<span class="text-red-500">*</span>
                </label>
                <input type="text" v-model="form.resident_insu_license_no" id="default-input"
                    :disabled="page.props.auth.role === 'admin'"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                <div v-if="firstStepErrors.resident_insu_license_no" class="text-red-500"
                    v-text="firstStepErrors.resident_insu_license_no[0]"></div>

            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 ">Resident
                    Insurance License
                    State<span class="text-red-500">*</span></label>
                <select v-model="form.resident_insu_license_state" id="countries"
                    :disabled="page.props.auth.role === 'admin'"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                    <option>Choose </option>
                    <option v-for="state in states" :value="state.id">{{ state.full_name }} </option>
                </select>
                <!-- <input type="text" v-model="form.resident_insu_license_state" id="default-input"
                    :disabled="page.props.auth.role === 'admin'"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white"> -->
                <div v-if="firstStepErrors.resident_insu_license_state" class="text-red-500"
                    v-text="firstStepErrors.resident_insu_license_state[0]"></div>
            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 ">Doing Business
                    As</label>
                <div>
                    <input type="text" v-model="form.doing_business_as" id="default-input"
                        :disabled="page.props.auth.role === 'admin'"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                    <span style="font-size: 14px;">Individual / Business Entity (Requires Licence)</span>
                </div>
                <div v-if="firstStepErrors.doing_business_as" class="text-red-500"
                    v-text="firstStepErrors.doing_business_as[0]"></div>
            </div>
        </div>
        <hr class="w-100 h-1 my-4 bg-gray-600 border-0 rounded dark:bg-gray-700">

        <div>
            <p>Will you be contracting with us as an individual or as a business?</p>
            <div class="flex flex-wrap">
                <div class="flex items-center my-4 mr-5 ">
                    <input id="default-radio-1" v-model="individual_business" type="radio" :value="true"
                        name="default-radio"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="default-radio-1"
                        class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Business</label>
                </div>
                <div class="flex items-center my-4 ">
                    <input id="default-radio-2" v-model="individual_business" type="radio" :value="false"
                        name="default-radio"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="default-radio-2"
                        class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Individual</label>
                </div>
            </div>
        </div>




        <div v-show="individual_business">

            <div class="flex justify-center">
                <p style="width: 70%;font-size: 14px;" class="text-center  py-2">Complete this section ONLY if
                    requesting to be contracted as business entity
                    <br>
                    <span>*A copy of your business entity,s resident
                        insurance licence your resident individual licence, and articles of incorporation must be submitted
                        with
                        your completed
                        contracted pocket.</span>
                </p>
            </div>

            <div class="grid lg:grid-cols-4 mb-2 mt-2  md:grid-cols-2 sm:grid-cols-1 gap-4">
                <div>
                    <label for="last_name" class="block mb-2 text-sm font-black text-gray-900 ">Business
                        Name<span class="text-red-500">*</span></label>
                    <div>

                    </div>
                    <input type="text" v-model="form.business_name" id="default-input"
                        :disabled="page.props.auth.role === 'admin'"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                    <div v-if="firstStepErrors.business_name" class="text-red-500"
                        v-text="firstStepErrors.business_name[0]">
                    </div>

                </div>
                <div>
                    <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 ">Tax
                        ID<span class="text-red-500">*</span></label>
                    <input type="number" v-model="form.business_tax_id" id="default-input"
                        :disabled="page.props.auth.role === 'admin'"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                    <div v-if="firstStepErrors.business_tax_id" class="text-red-500"
                        v-text="firstStepErrors.business_tax_id[0]"></div>
                </div>
                <div>
                    <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 ">Principle
                        Agent
                        Name<span class="text-red-500">*</span></label>
                    <input type="text" v-model="form.business_agent_name" id="default-input"
                        :disabled="page.props.auth.role === 'admin'"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                    <div v-if="firstStepErrors.business_agent_name" class="text-red-500"
                        v-text="firstStepErrors.business_agent_name[0]"></div>
                </div>
                <div>
                    <label for="last_name" class="block mb-2 text-sm font-black text-gray-900 ">Priciple
                        Agent
                        Title<span class="text-red-500">*</span>
                    </label>
                    <input type="text" v-model="form.business_agent_title" id="default-input"
                        :disabled="page.props.auth.role === 'admin'"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                    <div v-if="firstStepErrors.business_agent_title" class="text-red-500"
                        v-text="firstStepErrors.business_agent_title[0]"></div>
                </div>
            </div>

            <div class="grid lg:grid-cols-4 mb-2 mt-2  md:grid-cols-2 sm:grid-cols-1 gap-4">
                <div>
                    <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 ">Business
                        Insurance Licence #<span class="text-red-500">*</span></label>
                    <input type="text" v-model="form.business_insu_license_no" id="default-input"
                        :disabled="page.props.auth.role === 'admin'"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                    <div v-if="firstStepErrors.business_insu_license_no" class="text-red-500"
                        v-text="firstStepErrors.business_insu_license_no[0]"></div>

                </div>
                <div>
                    <label for="last_name" class="block mb-2 text-sm font-black text-gray-900 ">Cell
                        Fax<span class="text-red-500">*</span></label>
                    <input type="text" maxLength="15" v-model="form.business_office_fax" id="default-input"
                        :disabled="page.props.auth.role === 'admin'"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                    <div v-if="firstStepErrors.business_office_fax" class="text-red-500"
                        v-text="firstStepErrors.business_office_fax[0]"></div>
                </div>
                <div>
                    <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 ">Office
                        Phone<span class="text-red-500">*</span></label>
                    <input type="text" maxLength="10" v-model="form.business_office_phone" id="default-input"
                        :disabled="page.props.auth.role === 'admin'"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                    <div v-if="firstStepErrors.business_office_phone" class="text-red-500"
                        v-text="firstStepErrors.business_office_phone[0]"></div>
                </div>
                <div>
                    <label for="middle_name" class="block mb-2 text-sm font-black text-gray-900 ">Email<span
                            class="text-red-500">*</span></label>
                    <input type="email" v-model="form.business_email" id="default-input"
                        :disabled="page.props.auth.role === 'admin'"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                    <div v-if="firstStepErrors.business_email" class="text-red-500"
                        v-text="firstStepErrors.business_email[0]">
                    </div>
                </div>
            </div>

            <div class="grid lg:grid-cols-4 mb-2 mt-2  md:grid-cols-2 sm:grid-cols-1 gap-4">
                <div>
                    <label for="middle_name"
                        class="block mb-2 text-sm font-black text-gray-900 ">Website<span
                            class="text-red-500">*</span></label>
                    <input type="url" v-model="form.business_website" id="default-input"
                        :disabled="page.props.auth.role === 'admin'"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                    <div v-if="firstStepErrors.business_website" class="text-red-500"
                        v-text="firstStepErrors.business_website[0]"></div>
                </div>
                <div>
                    <label for="last_name" class="block mb-2 text-sm font-black text-gray-900 ">Business
                        Address<span class="text-red-500">*</span>
                    </label>
                    <div>
                        <input type="text" v-model="form.business_address" id="default-input"
                            :disabled="page.props.auth.role === 'admin'"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                        <span style="font-size: 14px;">Include Apt/Unit #<span class="text-red-500">*</span></span>
                    </div>
                    <div v-if="firstStepErrors.business_address" class="text-red-500"
                        v-text="firstStepErrors.business_address[0]"></div>
                </div>
                <div>
                    <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 ">City<span
                            class="text-red-500">*</span></label>
                    <input type="text" v-model="form.business_city" id="default-input"
                        :disabled="page.props.auth.role === 'admin'"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                    <div v-if="firstStepErrors.business_city" class="text-red-500"
                        v-text="firstStepErrors.business_city[0]"></div>
                </div>
                <div>
                    <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 ">
                        State<span class="text-red-500">*</span></label>
                    <select v-model="form.business_state" id="countries"
                        :disabled="page.props.auth.role === 'admin'"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                        <option>Choose </option>
                        <option v-for="state in states" :value="state.id">{{ state.full_name }} </option>
                    </select>
                    <div v-if="firstStepErrors.business_state" class="text-red-500"
                        v-text="firstStepErrors.business_state[0]"></div>
                </div>
                <div>
                    <label for="first_name" class="block mb-0 text-sm font-black text-gray-900 ">Zip
                        Code<span class="text-red-500">*</span></label>
                    <input type="number" @input="enforceFiveDigitInput('business_zip')" v-model="form.business_zip"
                        id="default-input"
                        :disabled="page.props.auth.role === 'admin'"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                    <div v-if="firstStepErrors.business_zip" class="text-red-500" v-text="firstStepErrors.business_zip[0]">
                    </div>
                </div>
                <div>
                    <label for="middle_name" class="block mb-2   text-sm font-black text-gray-900 ">Move-In
                        Date<span class="text-red-500">*</span></label>
                    <VueDatePicker :disabled="page.props.auth.role === 'admin'" v-model="form.business_move_in_date" format="dd-MMM-yyyy" :maxDate="maxDate" auto-apply>
                    </VueDatePicker>
                    <div v-if="firstStepErrors.business_move_in_date" class="text-red-500"
                        v-text="firstStepErrors.business_move_in_date[0]"></div>
                </div>
                <div>

                    <div>
                        <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 ">Company
                            Type<span class="text-red-500">*</span></label>
                        <select v-model="form.business_company_type" id="countries"
                            :disabled="page.props.auth.role === 'admin'"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                            <option>Choose </option>
                            <option value="corporation">Corporation</option>
                            <option value="parternership">Parternership</option>
                            <option value="LLP">LLP</option>
                            <option value="LLC">LLC</option>
                        </select>
                        <div v-if="firstStepErrors.business_company_type" class="text-red-500"
                            v-text="firstStepErrors.business_company_type[0]"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="px-5 pb-6">
        <div class="flex justify-end flex-wrap">
            <div class="mt-4">
                <button type="button" :class="{ 'opacity-25': isLoading }" :disabled="isLoading" @click="ChangeTab"
                    class="button-custom px-3 py-2 rounded-md">
                    <global-spinner :spinner="isLoading" /> Next
                </button>
            </div>
        </div>
    </div>
</template>