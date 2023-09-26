<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
let props = defineProps({
    firstStepErrors: Object,
});
let maxDate = ref(new Date)
let form = ref({
    first_name: null,
    last_name: null,
    middle_name: null,
    ssn: null,
    gender: null,
    dob: null,
    martial_status: null,
    cell_phone: null,
    home_phone: null,
    fax: null,
    email: null,
    driver_license_no: null,
    driver_license_state: null,
    address: null,
    city_state: null,
    zip: null,
    move_in_date: null,
    move_in_address: null,
    move_in_city_state: null,
    move_in_zip: null,
    resident_insu_license_no: null,
    resident_insu_license_state: null,
    doing_business_as: null,

    business_name: null,
    business_tax_id: null,
    business_agent_name: null,
    business_agent_title: null,
    business_company_type: null,
    business_insu_license_no: null,
    business_office_fax: null,
    business_office_phone: null,
    business_email: null,
    business_website: null,
    business_address: null,
    business_city_state:'test',
    business_zip: null,
    business_move_in_date: null,
})
const emits = defineEmits();
watch(form.value, (newForm, oldForm) => {
    emits("updateFormData", newForm);
});
onMounted(()=>{
    emits("updateFormData", form.value);
})
let ChangeTab = () => {
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
        "business_city_state", "business_zip", "business_move_in_date"
    ]
    const requiredFields = [
        "first_name", "last_name", "middle_name", "ssn", "gender", "dob", "martial_status",
        "cell_phone", "email", "driver_license_no", "driver_license_state",
        "address", "city_state", "zip", "move_in_date", "resident_insu_license_no", "resident_insu_license_state",
    ];
    const hasBusinessValue = businessInputs.some(fieldName => {
        const value = form.value[fieldName];
        return value !== null && value !== "Choose" && value !== '';
    });
    const mergedFields = hasBusinessValue ? [...requiredFields, ...businessInputs] : requiredFields;

    mergedFields.forEach(fieldName => {
        if (form.value[fieldName] === null || form.value[fieldName] === "" || form.value[fieldName] === "Choose") {
            props.firstStepErrors[fieldName] = [`The ${fieldName.replace(/_/g, ' ')} field is required.`];
        }
    });
    // Check if there are any errors
    const hasErrors = Object.values(props.firstStepErrors).some(errors => errors.length > 0);
    if (!hasErrors) {
        emits("changeTab");
    } else {
        var element = document.getElementById("modal_main_id");
        element.scrollIntoView();
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
                <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Last
                    Name<span class="text-red-500">*</span></label>
                <input type="text" v-model="form.last_name" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <div v-if="firstStepErrors.last_name" class="text-red-500" v-text="firstStepErrors.last_name[0]"></div>
            </div>
            <div>
                <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">First
                    Name<span class="text-red-500">*</span></label>
                <input type="text" v-model="form.first_name" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <div v-if="firstStepErrors.first_name" class="text-red-500" v-text="firstStepErrors.first_name[0]"></div>
            </div>
            <div>
                <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Middle
                    Name<span class="text-red-500">*</span></label>
                <input type="text" v-model="form.middle_name" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <div v-if="firstStepErrors.middle_name" class="text-red-500" v-text="firstStepErrors.middle_name[0]"></div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 mb-2  md:grid-cols-2 sm:grid-cols-1 gap-4">
            <div>
                <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Social
                    Security Number (SSN)<span class="text-red-500">*</span></label>
                <input type="number" v-model="form.ssn" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <div v-if="firstStepErrors.ssn" class="text-red-500" v-text="firstStepErrors.ssn[0]"></div>
            </div>
            <div>
                <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Gender<span
                        class="text-red-500">*</span></label>
                <select v-model="form.gender" id="countries"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value=null>Choose </option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
                <div v-if="firstStepErrors.gender" class="text-red-500" v-text="firstStepErrors.gender[0]"></div>
            </div>
            <div>
                <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date of
                    Birth<span class="text-red-500">*</span></label>
                <VueDatePicker v-model="form.dob" format="dd-MMM-yyyy" :maxDate="maxDate"></VueDatePicker>
                <div v-if="firstStepErrors.dob" class="text-red-500" v-text="firstStepErrors.dob[0]"></div>
            </div>

        </div>

        <div class="grid lg:grid-cols-3 mb-2  md:grid-cols-2 sm:grid-cols-1 gap-4">
            <div>
                <label for="last_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Cell
                    Phone<span class="text-red-500">*</span></label>
                <input type="number" v-model="form.cell_phone" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <div v-if="firstStepErrors.cell_phone" class="text-red-500" v-text="firstStepErrors.cell_phone[0]"></div>
            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Home
                    Phone</label>
                <input type="number" v-model="form.home_phone" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <div v-if="firstStepErrors.home_phone" class="text-red-500" v-text="firstStepErrors.home_phone[0]"></div>
            </div>
            <div>
                <label for="middle_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Fax</label>
                <input type="number" v-model="form.fax" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <div v-if="firstStepErrors.fax" class="text-red-500" v-text="firstStepErrors.fax[0]"></div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 mb-2  md:grid-cols-1 sm:grid-cols-1 gap-4">
            <div>
                <label for="middle_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Email<span
                        class="text-red-500">*</span></label>
                <input type="text" v-model="form.email" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <div v-if="firstStepErrors.email" class="text-red-500" v-text="firstStepErrors.email[0]"></div>
            </div>

            <div>
                <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Married
                    Status<span class="text-red-500">*</span></label>
                <select v-model="form.martial_status" id="countries"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value=null>Choose </option>
                    <option value="married">Married</option>
                    <option value="unmarried">UnMarried</option>
                </select>
                <div v-if="firstStepErrors.martial_status" class="text-red-500" v-text="firstStepErrors.martial_status[0]">
                </div>
            </div>

        </div>

        <div class="grid lg:grid-cols-3 mb-2  md:grid-cols-2 sm:grid-cols-1 gap-4">
            <div>
                <label for="last_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Drivers
                    License#<span class="text-red-500">*</span></label>
                <input type="number" v-model="form.driver_license_no" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <div v-if="firstStepErrors.driver_license_no" class="text-red-500"
                    v-text="firstStepErrors.driver_license_no[0]"></div>
            </div>

            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Driver Licence
                    State<span class="text-red-500">*</span></label>
                <input type="text" v-model="form.driver_license_state" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <div v-if="firstStepErrors.driver_license_state" class="text-red-500"
                    v-text="firstStepErrors.driver_license_state[0]"></div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 mb-2  md:grid-cols-2 sm:grid-cols-1 gap-4">
            <div>
                <label for="last_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Current Address
                    (Residence)<span class="text-red-500">*</span></label>
                <div>
                    <input type="text" v-model="form.address" id="default-input"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <span style="font-size: 14px;">Include Apt/Unit #</span>
                </div>
                <div v-if="firstStepErrors.address" class="text-red-500" v-text="firstStepErrors.address[0]"></div>
            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">City,
                    State<span class="text-red-500">*</span></label>
                <input type="text" v-model="form.city_state" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <div v-if="firstStepErrors.city_state" class="text-red-500" v-text="firstStepErrors.city_state[0]"></div>
            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Zip Code<span
                        class="text-red-500">*</span></label>
                <input type="number" v-model="form.zip" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <div v-if="firstStepErrors.zip" class="text-red-500" v-text="firstStepErrors.zip[0]"></div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 mb-2 md:grid-cols-2 sm:grid-cols-1 mt-3 gap-4">
            <div>
                <label for="middle_name" class="block mb-2   text-sm font-black text-gray-900 dark:text-white">Move-In
                    Date<span class="text-red-500">*</span></label>
                <VueDatePicker v-model="form.move_in_date" format="dd-MMM-yyyy" :maxDate="maxDate"></VueDatePicker>
                <div v-if="firstStepErrors.move_in_date" class="text-red-500" v-text="firstStepErrors.move_in_date[0]">
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 mb-2  md:grid-cols-2 sm:grid-cols-1 gap-4">
            <div>
                <label for="last_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Mailing Address
                    (If Diffrent From Residence)</label>
                <div>
                    <input type="text" v-model="form.move_in_address" id="default-input"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <span style="font-size: 14px;">Include Apt/Unit #</span>
                </div>
                <div v-if="firstStepErrors.move_in_address" class="text-red-500"
                    v-text="firstStepErrors.move_in_address[0]"></div>
            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">City,
                    State</label>
                <input type="text" v-model="form.move_in_city_state" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <div v-if="firstStepErrors.move_in_city_state" class="text-red-500"
                    v-text="firstStepErrors.move_in_city_state[0]"></div>
            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Zip Code</label>
                <input type="number" v-model="form.move_in_zip" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <div v-if="firstStepErrors.move_in_zip" class="text-red-500" v-text="firstStepErrors.move_in_zip[0]"></div>
            </div>
        </div>
        <hr class="w-100 h-1 my-4 bg-gray-600 border-0 rounded dark:bg-gray-700">

        <div class="grid lg:grid-cols-3 mb-2  md:grid-cols-2 sm:grid-cols-1 gap-4">
            <div>
                <label for="last_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Resident
                    Insurance License #<span class="text-red-500">*</span>
                </label>
                <input type="text" v-model="form.resident_insu_license_no" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <div v-if="firstStepErrors.resident_insu_license_no" class="text-red-500"
                    v-text="firstStepErrors.resident_insu_license_no[0]"></div>

            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Resident
                    Insurance License
                    State<span class="text-red-500">*</span></label>
                <input type="text" v-model="form.resident_insu_license_state" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <div v-if="firstStepErrors.resident_insu_license_state" class="text-red-500"
                    v-text="firstStepErrors.resident_insu_license_state[0]"></div>
            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Doing Business
                    As</label>
                <div>
                    <input type="text" v-model="form.doing_business_as" id="default-input"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <span style="font-size: 14px;">Individual / Business Entity (Requires Licence)</span>
                </div>
                <div v-if="firstStepErrors.doing_business_as" class="text-red-500"
                    v-text="firstStepErrors.doing_business_as[0]"></div>
            </div>
        </div>
        <hr class="w-100 h-1 my-4 bg-gray-600 border-0 rounded dark:bg-gray-700">
        <div class="flex justify-center">
            <p style="width: 70%;font-size: 14px;" class="text-center  py-2">Complete this section ONLY if
                requesting to be contracted as business entity
                <br>
                <span>*A copy of your business entity,s resident
                    insurance licence your resident individual licence, and articles of incorporation must be submitted with
                    your completed
                    contracted pocket.</span>
            </p>
        </div>


        <div class="grid lg:grid-cols-4 mb-2 mt-2  md:grid-cols-2 sm:grid-cols-1 gap-4">
            <div>
                <label for="last_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Business
                    Name</label>
                <div>

                </div>
                <input type="text" v-model="form.business_name" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <div v-if="firstStepErrors.business_name" class="text-red-500" v-text="firstStepErrors.business_name[0]">
                </div>

            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Tax ID</label>
                <input type="number" v-model="form.business_tax_id" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <div v-if="firstStepErrors.business_tax_id" class="text-red-500"
                    v-text="firstStepErrors.business_tax_id[0]"></div>
            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Principle Agent
                    Name</label>
                <input type="text" v-model="form.business_agent_name" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <div v-if="firstStepErrors.business_agent_name" class="text-red-500"
                    v-text="firstStepErrors.business_agent_name[0]"></div>
            </div>
            <div>
                <label for="last_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Priciple Agent
                    Title
                </label>
                <input type="text" v-model="form.business_agent_title" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <div v-if="firstStepErrors.business_agent_title" class="text-red-500"
                    v-text="firstStepErrors.business_agent_title[0]"></div>
            </div>
        </div>

        <div class="grid lg:grid-cols-4 mb-2 mt-2  md:grid-cols-2 sm:grid-cols-1 gap-4">
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Business
                    Insurance Licence #</label>
                <input type="text" v-model="form.business_insu_license_no" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <div v-if="firstStepErrors.business_insu_license_no" class="text-red-500"
                    v-text="firstStepErrors.business_insu_license_no[0]"></div>

            </div>
            <div>
                <label for="last_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Cell Fax</label>
                <input type="number" v-model="form.business_office_fax" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <div v-if="firstStepErrors.business_office_fax" class="text-red-500"
                    v-text="firstStepErrors.business_office_fax[0]"></div>
            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Office
                    Phone</label>
                <input type="number" v-model="form.business_office_phone" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <div v-if="firstStepErrors.business_office_phone" class="text-red-500"
                    v-text="firstStepErrors.business_office_phone[0]"></div>
            </div>
            <div>
                <label for="middle_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Email</label>
                <input type="text" v-model="form.business_email" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <div v-if="firstStepErrors.business_email" class="text-red-500" v-text="firstStepErrors.business_email[0]">
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-4 mb-2 mt-2  md:grid-cols-2 sm:grid-cols-1 gap-4">
            <div>
                <label for="middle_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Website</label>
                <input type="text" v-model="form.business_website" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <div v-if="firstStepErrors.business_website" class="text-red-500"
                    v-text="firstStepErrors.business_website[0]"></div>
            </div>
            <div>
                <label for="last_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Business
                    Address
                </label>
                <div>
                    <input type="text" v-model="form.business_address" id="default-input"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <span style="font-size: 14px;">Include Apt/Unit #</span>
                </div>
                <div v-if="firstStepErrors.business_address" class="text-red-500"
                    v-text="firstStepErrors.business_address[0]"></div>
            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">City,
                    State</label>
                <input type="text" v-model="form.business_city_state" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <div v-if="firstStepErrors.business_city_state" class="text-red-500"
                    v-text="firstStepErrors.business_city_state[0]"></div>
            </div>
            <div>
                <label for="first_name" class="block mb-0 text-sm font-black text-gray-900 dark:text-white">Zip Code</label>
                <input type="number" v-model="form.business_zip" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <div v-if="firstStepErrors.business_zip" class="text-red-500" v-text="firstStepErrors.business_zip[0]">
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-4 mb-2 md:grid-cols-1 sm:grid-cols-1  gap-4">
            <div>
                <label for="middle_name" class="block mb-2   text-sm font-black text-gray-900 dark:text-white">Move-In
                    Date</label>
                <VueDatePicker v-model="form.business_move_in_date" format="dd-MMM-yyyy" :maxDate="maxDate"></VueDatePicker>
                <div v-if="firstStepErrors.business_move_in_date" class="text-red-500"
                    v-text="firstStepErrors.business_move_in_date[0]"></div>
            </div>
            <div>

                <div>
                    <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Company
                        Type</label>
                    <select v-model="form.business_company_type" id="countries"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value=null>Choose </option>
                        <option value="corporation">Corporation</option>
                        <option value="parternership">Parternership</option>
                        <option value="LLP">LLP</option>
                        <option value="LLC">LLC</option>
                    </select>
                    <div v-if="firstStepErrors.business_company_type" class="text-red-500"
                        v-text="firstStepErrors.business_company_type[0]"></div>
                </div>
            </div>
            <div>
            </div>
        </div>
    </div>
    <div class="px-5 pb-6">
        <div class="flex justify-end flex-wrap">
            <div class="mt-4">
                <button type="button" @click="ChangeTab" class="button-custom px-3 py-2 rounded-md">
                    Next
                </button>
            </div>
        </div>
    </div>
</template>