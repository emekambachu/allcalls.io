<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed } from "vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
let page = usePage();
let props = defineProps({
    userData: Object,
});
let LegalInformation = ref([
    {
        id: 1,
        name: 'convicted_checkbox_1',
        heading: 'Have you ever been charged or convicted of, or pled guilty or no contest to, any felony, misdemeanor, federal/state insurance and/or securities or investments regulations or statutes?',
        question: '1',
    },
    {
        id: 2,
        name: 'convicted_checkbox_1a',
        heading: 'Have you ever been convicted of, or pled guilty or no contest to, any felony?',
        question: '1A',
    },
    {
        id: 3,
        name: 'convicted_checkbox_1b',
        heading: 'Have you ever been convicted of, or pled guilty or no contest to, any misdemeanor?',
        question: '1B',
    },
    {
        id: 4,
        name: 'convicted_checkbox_1c',
        heading: 'Have you ever been convicted of, or pled guilty or no contest to, a violation of federal or state securities or investment-related regulations?',
        question: '1C',
    },
    {
        id: 5,
        name: 'convicted_checkbox_1d',
        heading: 'Have you ever been convicted of, or pled guilty or no contest to, a violation of state insurance department regulation or statute?',
        question: '1D',
    },
    {
        id: 6,
        name: 'convicted_checkbox_1e',
        heading: 'Has any foreign government, court, regulatory agency, or exchange ever entered an order against you related to income investments or fraud?',
        question: '1E',
    },
    {
        id: 7,
        name: 'convicted_checkbox_1f',
        heading: 'Have you ever been charged with a felony?',
        question: '1F',
    },
    {
        id: 8,
        name: 'convicted_checkbox_1g',
        heading: 'Have you ever been charged with a misdemeanor?',
        question: '1G',
    },
    {
        id: 9,
        name: 'convicted_checkbox_1h',
        heading: 'Have you ever been on probation?',
        question: '1H',
    },
    {
        id: 10,
        name: 'lawsuit_checkbox_2',
        heading: 'Have you ever been, or are you currently being investigated, have any pending indictments, lawsuits, or have you ever been in a lawsuit with an insurance company?',
        question: '2',
    },
    {
        id: 11,
        name: 'lawsuit_checkbox_2a',
        heading: 'Are you currently under investigation by any legal or regulatory agency?',
        question: '2A'
    },
    {
        id: 12,
        name: 'lawsuit_checkbox_2b',
        heading: 'Have you been under investigation by any insurance company?',
        question: '2B',
    },
    {
        id: 13,
        name: 'lawsuit_checkbox_2c',
        heading: 'Have you ever been, or are you currently involved in any pending indictments, lawsuits, civil judgements or other legal proceedings (civil or criminal) (you may omit family court)?',
        question: '2C'
    },
    {
        id: 14,
        name: 'lawsuit_checkbox_2d',
        heading: 'Have you ever been named as a defendant or co-defendant in a lawsuit, or have you ever sued or been sued by an insurance company?',
        question: '2D',
    },
    {
        id: 15,
        name: 'alleged_engaged_checkbox_3',
        heading: 'Have you ever been alleged to have engaged in any fraud?',
        question: '3',
    },
    {
        id: 16,
        name: 'found_engaged_checkbox_4',
        heading: 'Have you ever been found to have engaged in any fraud?',
        question: '4',
    },
    {
        id: 17,
        name: 'terminate_contract_checkbox_5',
        heading: 'Has any insurance or financial services company or broker-dealer terminated your contract or appointment or permitted you to resign for reason other than lack of sales?',
        question: '5'
    },
    {
        id: 18,
        name: 'terminate_contract_checkbox_5a',
        heading: 'Were you fired because you were accused of violating insurance or investment-related statutes, regulations, rules, or industry standards of conduct?',
        question: '5A',
    },
    {
        id: 19,
        name: 'terminate_contract_checkbox_5b',
        heading: 'Were you fired because you were accused of fraud or the wrongful taking of property?',
        question: '5B',
    },
    {
        id: 20,
        name: 'terminate_contract_checkbox_5c',
        heading: 'Failure to supervise in connection with insurance or investment-related statutes, regulations, rules, or industry standards of conduct?',
        question: '5C',
    },
    {
        id: 21,
        name: 'cancel_insu_cause_checkbox_6',
        heading: 'Have you ever had an appointment with any insurance company denied or terminated for cause? (If you have been reported to Vector One, answer YES) ',
        question: '6',
    },
    {
        id: 22,
        name: 'insurer_checkbox_7',
        heading: 'Does any insurer, insured, or other person claim any commission chargeback or other indebtedness from you as a result of any insurance transactions or business? (If you have been reported to Vector One, answer YES)',
        question: '7',
    },
    {
        id: 23,
        name: 'lawsuit_checkbox_8',
        heading: 'Has any lawsuit or claim ever been made against you, your surety company, or errors and omissions insurer, arising out of your sales or practices, or have you been refused surety bonding or E&O coverage?',
        question: '8'
    },
    {
        id: 24,
        name: 'lawsuit_checkbox_8a',
        heading: 'Has a bonding or surety company ever denied, paid on, or revoked a bond for you? 8B. Has any Errors & Omissions company ever denied, paid claims on, or cancelled your coverage?',
        question: '8A'
    },
    {
        id: 25,
        name: 'lawsuit_checkbox_8b',
        heading: 'Has any Errors & Ommisions company ever denied, paid claims on, or cancelled your coverage?',
        question: '8b'
    },
    {
        id: 26,
        name: 'license_denied_checkbox_9',
        heading: 'Have you ever had an insurance or securities license denied, suspended, cancelled, or revoked?',
        question: '9'
    },
    {
        id: 27,
        name: 'regulatory_checkbox_10',
        heading: 'Has any state or federal regulatory body found you to have been a cause of an investment or insurance-related business having its authorization to do business denied, suspended, revoked, or restricted?',
        question: '10'
    },
    {
        id: 28,
        name: 'regulatory_revoked_checkbox_11',
        heading: 'Has any state or federal regulatory agency revoked or suspended your license as an attorney, accountant, or federal contractor?',
        question: '11'
    },
    {
        id: 29,
        name: 'regulatory_found_checkbox_12',
        heading: 'Has any state or federal regulatory agency found you to have made a false statement or omission or been dishonest, unfair, or unethical?',
        question: '12'
    },
    {
        id: 30,
        name: 'interr_licensing_checkbox_13',
        heading: 'Have you ever had any interruptions in licensing?',
        question: '13'
    },
    {
        id: 31,
        name: 'self_regularity_checkbox_14',
        heading: 'Has any state, federal, or self-regulatory agency filed a complaint against you, fined, sanctioned, censured, penalized, or otherwise disciplined you for a violation of their regulations or state or federal statuses?',
        question: '14'
    },
    {
        id: 32,
        name: 'self_regularity_checkbox_14a',
        heading: 'Has any regulatory body ever sanctioned, censured, penalized, or otherwise disciplined you?',
        question: '14A'
    },
    {
        id: 33,
        name: 'self_regularity_checkbox_14b',
        heading: 'Has any state, federal, or self-regulatory agency filed a complaint against you, fined, or sanctioned you?',
        question: '14B'
    },
    {
        id: 34,
        name: 'self_regularity_checkbox_14c',
        heading: 'Have you ever been the subject of a consumer-initiated complaint?',
        question: '14C'
    },
    {
        id: 35,
        name: 'bankruptcy_checkbox_15',
        heading: 'Have you personally, or any insurance or securities brokerage firm with whom you have been associated, filed a bankruptcy petition, or declared bankruptcy? 15A. Have you personally filed a bankruptcy petition or declared bankruptcy?',
        question: '15'
    },
    {
        id: 36,
        name: 'bankruptcy_checkbox_15a',
        heading: 'Have you personally, or any insurance or securities brokerage firm with whom you have been associated, filed a bankruptcy petition, or declared bankruptcy? 15A. Have you personally filed a bankruptcy petition or declared bankruptcy?',
        question: '15a'
    },
    {
        id: 37,
        name: 'bankruptcy_checkbox_15b',
        heading: 'Has any insurance or securities brokerage firm, with whom you have been associated, filed a bankruptcy petition, or been declared bankrupt, either during your association with them or within 5 years after termination of such an association?',
        question: '15B'
    },
    {
        id: 38,
        name: 'bankruptcy_checkbox_15c',
        heading: 'Is the bankruptcy pending?',
        question: '15C'
    },
    {
        id: 39,
        name: 'liens_against_checkbox_16',
        heading: 'Are there any unsatisfied judgements or liens against you?',
        question: '16'
    },
    {
        id: 40,
        name: 'connected_checkbox_17',
        heading: 'Are you connected in any way with a bank, savings & loan association, or other lending or financial institution?',
        question: '17'
    },
    {
        id: 41,
        name: 'aliases_checkbox_18',
        heading: 'Have you ever used any other names or aliases?',
        question: '18'
    },
    {
        id: 42,
        name: 'unresolved_matter_checkbox_19',
        heading: 'Do you have any unresolved matters pending with the Internal Revenue Service, or other taxing authority?',
        question: '19'
    },


])
let form = ref({

})
// if (page.props.auth.role === 'admin' && props.userData.internal_agent_contract) {
props.userData.internal_agent_contract.legal_question.forEach((question) => {
    const matchingLegalInfo = LegalInformation.value.find((info) => info.name === question.name);
    if (matchingLegalInfo) {
        form.value[matchingLegalInfo.name] = question.value;
        form.value[matchingLegalInfo.name + '_text'] = question.description
    }
});
// }
</script>
<template>
    <div class="container mx-auto p-5">
        <h1 style="background-color: #134576;" class="mb-4 mt-4	text-center rounded-md py-2 text-white">
            Basic Information
        </h1>
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 bg-white rounded-lg shadow-md gap-4">
            <div class="bg-white  p-4">
                <p class="text-gray-600"><strong>Last Name:</strong> {{ userData.internal_agent_contract.last_name }}</p>
                <p class="text-gray-600"><strong>First Name:</strong> {{ userData.internal_agent_contract.first_name }}</p>
                <p class="text-gray-600"><strong>Middle Name:</strong> {{ userData.internal_agent_contract.middle_name }}
                </p>
                <p class="text-gray-600"><strong>Social Security Number (SSN):</strong> {{
                    userData.internal_agent_contract.ssn }}</p>
                <p class="text-gray-600"><strong>Gender:</strong> {{ userData.internal_agent_contract.gender }}</p>
                <p class="text-gray-600"><strong>Date of Birth:</strong> {{ userData.internal_agent_contract.dob }}</p>
                <p class="text-gray-600"><strong>Cell Phone: </strong> {{ userData.internal_agent_contract.cell_phone }}</p>
                <p class="text-gray-600"><strong>Home Phone: </strong> {{ userData.internal_agent_contract.home_phone }}</p>
                <!-- Add more data fields as needed -->
            </div>
            <div class="bg-white  p-4">

                <p class="text-gray-600"><strong>Fax: </strong> {{ userData.internal_agent_contract.fax }}</p>
                <p class="text-gray-600"><strong>Email: </strong> {{ userData.internal_agent_contract.email }}</p>
                <p class="text-gray-600"><strong>Married Status: </strong> {{
                    userData.internal_agent_contract.marital_status }}</p>
                <p class="text-gray-600"><strong>Drivers License: </strong> {{
                    userData.internal_agent_contract.driver_license_no }}</p>
                <p class="text-gray-600"><strong>Driver Licence State: </strong> {{
                    userData.internal_agent_contract.driver_license_state }}</p>
                <p class="text-gray-600"><strong>Current Address (Residence): </strong> {{
                    userData.internal_agent_contract.address }}</p>
                <p class="text-gray-600"><strong>City: </strong> {{ userData.internal_agent_contract.city }}</p>
                <p class="text-gray-600"><strong>State: </strong> {{ userData.internal_agent_contract.state }}</p>
            </div>
            <div class="bg-white  p-4">
                <p class="text-gray-600"><strong>Zip Code: </strong> {{ userData.internal_agent_contract.zip }}</p>
                <p class="text-gray-600"><strong>Move-In Date: </strong> {{ userData.internal_agent_contract.move_in_date }}
                </p>
                <p class="text-gray-600"><strong>Mailing Address (If Diffrent From Residence): </strong> {{
                    userData.internal_agent_contract.move_in_address }}</p>
                <p class="text-gray-600"><strong>City: </strong> {{ userData.internal_agent_contract.move_in_city }}</p>
                <p class="text-gray-600"><strong>State: </strong> {{ userData.internal_agent_contract.move_in_state }}</p>
                <p class="text-gray-600"><strong>Zip Code: </strong> {{ userData.internal_agent_contract.move_in_zip }}</p>
            </div>
        </div>
        <hr>
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 bg-white rounded-lg shadow-md gap-4">
            <div class="bg-white  p-4">
                <p class="text-gray-600"><strong>Resident Insurance License: </strong> {{
                    userData.internal_agent_contract.resident_insu_license_no }}</p>
            </div>
            <div class="bg-white  p-4">
                <p class="text-gray-600"><strong>Resident Insurance License State: </strong> {{
                    userData.internal_agent_contract.resident_insu_license_state }}</p>
            </div>
            <div class="bg-white  p-4">
                <p class="text-gray-600"><strong>Doing Business As: </strong> {{
                    userData.internal_agent_contract.doing_business_as }}</p>
            </div>
        </div>
        <hr>
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 bg-white rounded-lg shadow-md gap-4">
            <!-- Repeat this card for each record -->
            <div class="bg-white  p-4">
                <p class="text-gray-600"><strong>Business Name: </strong> {{ userData.internal_agent_contract.business_name
                }}</p>
                <p class="text-gray-600"><strong>Tax ID: </strong> {{ userData.internal_agent_contract.business_tax_id }}
                </p>
                <p class="text-gray-600"><strong>Principle Agent Name: </strong> {{
                    userData.internal_agent_contract.business_agent_name }}</p>
                <p class="text-gray-600"><strong>Priciple Agent Title: </strong> {{
                    userData.internal_agent_contract.business_agent_title }}</p>
                <p class="text-gray-600"><strong>Business Insurance Licence: </strong> {{
                    userData.internal_agent_contract.business_insu_license_no }}</p>
                <p class="text-gray-600"><strong>Cell Fax: </strong> {{ userData.internal_agent_contract.business_office_fax
                }}</p>
            </div>
            <div class="bg-white  p-4">
                <p class="text-gray-600"><strong>Office Phone: </strong> {{
                    userData.internal_agent_contract.business_office_phone }}</p>
                <p class="text-gray-600"><strong>Email: </strong> {{ userData.internal_agent_contract.business_email }}</p>
                <p class="text-gray-600"><strong>Website: </strong> {{ userData.internal_agent_contract.business_website }}
                </p>
                <p class="text-gray-600"><strong>Business Address: </strong> {{
                    userData.internal_agent_contract.business_address }}</p>
                <p class="text-gray-600"><strong>City: </strong> {{ userData.internal_agent_contract.business_city }}</p>
                <p class="text-gray-600"><strong>State: </strong> {{ userData.internal_agent_contract.business_state }}</p>
            </div>
            <div class="bg-white  p-4">
                <p class="text-gray-600"><strong>Zip Code: </strong> {{ userData.internal_agent_contract.business_zip }}</p>
                <p class="text-gray-600"><strong>Move-In Date: </strong> {{
                    userData.internal_agent_contract.business_move_in_date }}</p>
                <p class="text-gray-600"><strong>Company Type: </strong> {{
                    userData.internal_agent_contract.business_company_type }}</p>
            </div>
        </div>
        <hr>
        <h1 style="background-color: #134576;" class="mb-4 mt-4	text-center rounded-md py-2 text-white">
            Legal Questions
        </h1>
        <div style="width:100%;" class="flex justify-around">
            <p> <strong>For contracting and appointment requests, please answer the following questions. If
                    you answer YES to any question, you must provide documentation including a full, detailed explanation
                    and
                    specific dates. Please answer every question including subquestions for clarity.</strong></p>
        </div>
        <div class=" bg-white rounded-lg shadow-md gap-4 mt-4">
            <div v-for="(information, index) in LegalInformation" :key="information.id">
                <div style="width: 100%;" class="flex justify-around  pl-6 pr-2">
                    <div style="width: 100%;">
                        <p>
                            <strong>
                                <span style="font-size: 18px;" class="mr-2">{{ information.question }}.</span>{{
                                    information.heading
                                }}<span class="text-red-500 ml-1">*</span>
                            </strong>
                        </p>

                        <div class="flex mt-4">
                            <div class="flex items-center mr-4">
                                <input :id="'default-radio-' + information.id + '-yes'" v-model="form[information.name]"
                                    value="YES" disabled type="radio" :name="'question-' + information.id"
                                    :checked="form[information.name] === 'YES'"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                                <label class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">YES</label>
                            </div>
                            <div class="flex items-center">
                                <input :id="'default-radio-' + information.id + '-no'" v-model="form[information.name]"
                                    value="NO" disabled type="radio" :name="'question-' + information.id"
                                    :checked="form[information.name] === 'NO'"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                                <label class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">NO</label>
                            </div>

                        </div>


                        <input type="text" disabled v-show="form[information.name] === 'YES'"
                            v-model="form[information.name + '_text']"
                            class="bg-gray-50 mt-5 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

                    </div>
                </div>

                <hr class="my-4">
            </div>
        </div>
        <h1 style="background-color: #134576;" class="mb-4	text-center rounded-md py-2 text-white">
            Your Address History for the Past 7 Years
        </h1>
        <div  class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 bg-white rounded-lg shadow-md gap-4">
            <div v-for="address in userData.internal_agent_contract.addresses" class="bg-white  p-4">
                <p class="text-gray-600"><strong>Home Address:</strong> {{ address.address }}</p>
                <p class="text-gray-600"><strong>City:</strong> {{ userData.internal_agent_contract.first_name }}</p>
                <p class="text-gray-600"><strong>State:</strong> {{ userData.internal_agent_contract.middle_name }}
                </p>
                <p class="text-gray-600"><strong>Zip Code:</strong> {{
                    userData.internal_agent_contract.ssn }}</p>
                <p class="text-gray-600"><strong>Move-In Date:</strong> {{ userData.internal_agent_contract.gender }}</p>
                <p class="text-gray-600"><strong>Move-Out Date:</strong> {{ userData.internal_agent_contract.dob }}</p>
                <!-- Add more data fields as needed -->
            </div>
        </div>
    </div>
</template>