<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
let maxDate = ref(new Date)
let form = ref({
    first_name: null,
    last_name: null,
    middle_name: null,
    ssn: null,
    gender: 'Choose',
    dob: null,
    martial_status: 'Choose',
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
    business_company_type: 'Choose',
    business_insu_license_no: null,
    business_office_fax: null,
    business_office_phone: null,
    business_email: null,
    business_website: null,
    business_address: null,
    business_city_state: null,
    business_zip: null,
    business_move_in_date: null,
})
const emits = defineEmits();
watch(form.value, (newForm, oldForm) => {
    emits("updateFormData", newForm);
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


        <div class="grid lg:grid-cols-3 mb-2  md:grid-cols-2 sm:grid-cols-1 gap-4">
            <div>
                <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Last
                    Name</label>
                <input type="text" v-model="form.last_name" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>
            <div>
                <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">First
                    Name</label>
                <input type="text" v-model="form.first_name" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>
            <div>
                <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Middle
                    Name</label>
                <input type="text" v-model="form.middle_name" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>
        </div>

        <div class="grid lg:grid-cols-3 mb-2  md:grid-cols-2 sm:grid-cols-1 gap-4">
            <div>
                <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Social
                    Security Number (SSN)</label>
                <input type="text" v-model="form.ssn" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>
            <div>
                <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Gender</label>
                <select v-model="form.gender" id="countries"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option selected>Choose </option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div>
                <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date of
                    Birth</label>
                <VueDatePicker v-model="form.dob" format="dd-MMM-yyyy" :maxDate="maxDate"></VueDatePicker>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 mb-2  md:grid-cols-2 sm:grid-cols-1 gap-4">
            <div>
                <label for="last_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Cell
                    Phone</label>
                <input type="text" v-model="form.cell_phone" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Home
                    Phone</label>
                <input type="text" v-model="form.home_phone" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>
            <div>
                <label for="middle_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Fax</label>
                <input type="text" v-model="form.fax" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>
        </div>

        <div class="grid lg:grid-cols-3 mb-2  md:grid-cols-1 sm:grid-cols-1 gap-4">
            <div>
                <label for="middle_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Email</label>
                <input type="text" v-model="form.email" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>

            <div>
                <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Married
                    Status</label>
                <select v-model="form.martial_status" id="countries"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option selected>Choose </option>
                    <option value="married">Married</option>
                    <option value="unmarried">UnMarried</option>
                </select>
            </div>

        </div>

        <div class="grid lg:grid-cols-3 mb-2  md:grid-cols-2 sm:grid-cols-1 gap-4">
            <div>
                <label for="last_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Drivers
                    License#</label>
                <input type="text" v-model="form.driver_license_no" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Driver Licence
                    State</label>
                <input type="text" v-model="form.driver_license_state" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>
        </div>

        <div class="grid lg:grid-cols-3 mb-2  md:grid-cols-2 sm:grid-cols-1 gap-4">
            <div>
                <label for="last_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Current Address
                    (Residence)</label>
                <div>
                    <input type="text" v-model="form.address" id="default-input"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <span style="font-size: 14px;">Include Apt/Unit #</span>
                </div>
            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">City,
                    State</label>
                <input type="text" v-model="form.city_state" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Zip Code</label>
                <input type="text" v-model="form.zip" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>
        </div>

        <div class="grid lg:grid-cols-3 mb-2 md:grid-cols-2 sm:grid-cols-1 mt-3 gap-4">
            <div>
                <label for="middle_name" class="block mb-2   text-sm font-black text-gray-900 dark:text-white">Move-In
                    Date</label>
                <VueDatePicker v-model="form.move_in_date" format="dd-MMM-yyyy" :maxDate="maxDate"></VueDatePicker>
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
            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">City,
                    State</label>
                <input type="text" v-model="form.move_in_city_state" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Zip Code</label>
                <input type="text" v-model="form.move_in_zip" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>
        </div>
        <hr class="w-100 h-1 my-4 bg-gray-600 border-0 rounded dark:bg-gray-700">

        <div class="grid lg:grid-cols-3 mb-2  md:grid-cols-2 sm:grid-cols-1 gap-4">
            <div>
                <label for="last_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Resident
                    Insurance License #
                </label>
                <input type="text" v-model="form.resident_insu_license_no" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Resident
                    Insurance License
                    State</label>
                <input type="text" v-model="form.resident_insu_license_state" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Doing Business
                    As</label>
                <div>
                    <input type="text" v-model="form.doing_business_as" id="default-input"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <span style="font-size: 14px;">Individual / Business Entity (Requires Licence)</span>
                </div>

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
                <label for="last_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Business Name
                </label>
                <div>

                </div>
                <input type="text" v-model="form.business_name" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Tax ID</label>
                <input type="text" v-model="form.business_tax_id" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Principle Agent
                    Name</label>
                <input type="text" v-model="form.business_agent_name" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>
            <div>
                <label for="last_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Priciple Agent
                    Title
                </label>
                <input type="text" v-model="form.business_agent_title" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>
        </div>

        <div class="grid lg:grid-cols-4 mb-2 mt-2  md:grid-cols-2 sm:grid-cols-1 gap-4">
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Business
                    Insurance Licence #</label>
                <input type="text" v-model="form.business_insu_license_no" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

            </div>
            <div>
                <label for="last_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Cell Fax</label>
                <input type="text" v-model="form.business_office_fax" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Office
                    Phone</label>
                <input type="text" v-model="form.business_office_phone" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>
            <div>
                <label for="middle_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Email</label>
                <input type="text" v-model="form.business_email" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>
        </div>

        <div class="grid lg:grid-cols-4 mb-2 mt-2  md:grid-cols-2 sm:grid-cols-1 gap-4">
            <div>
                <label for="middle_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Website</label>
                <input type="text" v-model="form.business_website" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>
            <div>
                <label for="last_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Business Address
                </label>
                <div>
                    <input type="text" v-model="form.business_address" id="default-input"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <span style="font-size: 14px;">Include Apt/Unit #</span>
                </div>
            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">City,
                    State</label>
                <input type="text" v-model="form.business_city_state" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>
            <div>
                <label for="first_name" class="block mb-0 text-sm font-black text-gray-900 dark:text-white">Zip Code</label>
                <input type="text" v-model="form.business_zip" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>
        </div>

        <div class="grid lg:grid-cols-4 mb-2 md:grid-cols-1 sm:grid-cols-1  gap-4">
            <div>
                <label for="middle_name" class="block mb-2   text-sm font-black text-gray-900 dark:text-white">Move-In
                    Date</label>
                <VueDatePicker v-model="form.business_move_in_date" format="dd-MMM-yyyy" :maxDate="maxDate"></VueDatePicker>
            </div>
            <div>

                <div>
                    <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Company
                        Type</label>
                    <select v-model="form.business_company_type" id="countries"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected>Choose </option>
                        <option value="corporation">Corporation</option>
                        <option value="parternership">Parternership</option>
                        <option value="LLP">LLP</option>
                        <option value="LLC">LLC</option>
                    </select>
                </div>
            </div>
            <div>
            </div>
        </div>
    </div>
</template>