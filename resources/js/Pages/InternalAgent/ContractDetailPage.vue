<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed } from "vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
let page = usePage();
let props = defineProps({
    previewData: Object,
    userData:Object,
});
console.log('previewData', props.previewData);
console.log('userData', props.userData.internal_agent_contract);
let LegalInformation = ref([        
    {
        id: 43,
        name: 'convicted_checkbox_1',
        description: 'convicted_checkbox_1_text',
        heading: 'Have you ever been charged or convicted of, or pled guilty or no contest to, any felony, misdemeanor, federal/state insurance and/or securities or investments regulations or statutes?',
        question: '1',
    },
    {
        id: 44,
        name: 'convicted_checkbox_1a',
        description: 'convicted_checkbox_1a_text',
        heading: 'Have you ever been convicted of, or pled guilty or no contest to, any felony?',
        question: '1A',
    },
    {
        id: 45,
        name: 'convicted_checkbox_1b',
        description: 'convicted_checkbox_1b_text',
        heading: 'Have you ever been convicted of, or pled guilty or no contest to, any misdemeanor?',
        question: '1B',
    },
    {
        id: 46,
        name: 'convicted_checkbox_1c',
        description: 'convicted_checkbox_1c_text',
        heading: 'Have you ever been convicted of, or pled guilty or no contest to, a violation of federal or state securities or investment-related regulations?',
        question: '1C',
    },
    {
        id: 47,
        name: 'convicted_checkbox_1d',
        description: 'convicted_checkbox_1d_text',
        heading: 'Have you ever been convicted of, or pled guilty or no contest to, a violation of state insurance department regulation or statute?',
        question: '1D',
    },
    {
        id: 48,
        name: 'convicted_checkbox_1e',
        description: 'convicted_checkbox_1e_text',
        heading: 'Has any foreign government, court, regulatory agency, or exchange ever entered an order against you related to income investments or fraud?',
        question: '1E',
    },
    {
        id: 49,
        name: 'convicted_checkbox_1f',
        description: 'convicted_checkbox_1f_text',
        heading: 'Have you ever been charged with a felony?',
        question: '1F',
    },
    {
        id: 50,
        name: 'convicted_checkbox_1g',
        description: 'convicted_checkbox_1g_text',
        heading: 'Have you ever been charged with a misdemeanor?',
        question: '1G',
    },
    {
        id: 51,
        name: 'convicted_checkbox_1h',
        description: 'convicted_checkbox_1h_text',
        heading: 'Have you ever been on probation?',
        question: '1H',
    },
    {
        id: 52,
        name: 'lawsuit_checkbox_2',
        description: 'lawsuit_checkbox_2_text',
        heading: 'Have you ever been, or are you currently being investigated, have any pending indictments, lawsuits, or have you ever been in a lawsuit with an insurance company?',
        question: '2',
    },
    {
        id: 53,
        name: 'lawsuit_checkbox_2a',
        description: 'lawsuit_checkbox_2a_text',
        heading: 'Are you currently under investigation by any legal or regulatory agency?',
        question: '2A'
    },
    {
        id: 54,
        name: 'lawsuit_checkbox_2b',
        description: 'lawsuit_checkbox_2b_text',
        heading: 'Have you been under investigation by any insurance company?',
        question: '2B',
    },
    {
        id: 55,
        name: 'lawsuit_checkbox_2c',
        description: 'lawsuit_checkbox_2c_text',
        heading: 'Have you ever been, or are you currently involved in any pending indictments, lawsuits, civil judgements or other legal proceedings (civil or criminal) (you may omit family court)?',
        question: '2C'
    },
    {
        id: 56,
        name: 'lawsuit_checkbox_2d',
        description: 'lawsuit_checkbox_2d_text',
        heading: 'Have you ever been named as a defendant or co-defendant in a lawsuit, or have you ever sued or been sued by an insurance company?',
        question: '2D',
    },
    {
        id: 57,
        name: 'alleged_engaged_checkbox_3',
        description: 'alleged_engaged_checkbox_3_text',
        heading: 'Have you ever been alleged to have engaged in any fraud?',
        question: '3',
    },
    {
        id: 58,
        name: 'found_engaged_checkbox_4',
        description: 'found_engaged_checkbox_4_text',
        heading: 'Have you ever been found to have engaged in any fraud?',
        question: '4',
    },
    {
        id: 59,
        name: 'terminate_contract_checkbox_5',
        description: 'terminate_contract_checkbox_5_text',
        heading: 'Has any insurance or financial services company or broker-dealer terminated your contract or appointment or permitted you to resign for reason other than lack of sales?',
        question: '5'
    },
    {
        id: 60,
        name: 'terminate_contract_checkbox_5a',
        description: 'terminate_contract_checkbox_5a_text',
        heading: 'Were you fired because you were accused of violating insurance or investment-related statutes, regulations, rules, or industry standards of conduct?',
        question: '5A',
    },
    {
        id: 61,
        name: 'terminate_contract_checkbox_5b',
        description: 'terminate_contract_checkbox_5b_text',
        heading: 'Were you fired because you were accused of fraud or the wrongful taking of property?',
        question: '5B',
    },
    {
        id: 62,
        name: 'terminate_contract_checkbox_5c',
        description: 'terminate_contract_checkbox_5c_text',
        heading: 'Failure to supervise in connection with insurance or investment-related statutes, regulations, rules, or industry standards of conduct?',
        question: '5C',
    },
    {
        id: 63,
        name: 'cancel_insu_cause_checkbox_6',
        description: 'cancel_insu_cause_checkbox_6_text',
        heading: 'Have you ever had an appointment with any insurance company denied or terminated for cause? (If you have been reported to Vector One, answer YES) ',
        question: '6',
    },
    {
        id: 64,
        name: 'insurer_checkbox_7',
        description: 'insurer_checkbox_7_text',
        heading: 'Does any insurer, insured, or other person claim any commission chargeback or other indebtedness from you as a result of any insurance transactions or business? (If you have been reported to Vector One, answer YES)',
        question: '7',
    },
    {
        id: 65,
        name: 'lawsuit_checkbox_8',
        description: 'lawsuit_checkbox_8_text',
        heading: 'Has any lawsuit or claim ever been made against you, your surety company, or errors and omissions insurer, arising out of your sales or practices, or have you been refused surety bonding or E&O coverage?',
        question: '8'
    },
    {
        id: 66,
        name: 'lawsuit_checkbox_8a',
        description: 'lawsuit_checkbox_8a_text',
        heading: 'Has a bonding or surety company ever denied, paid on, or revoked a bond for you? 8B. Has any Errors & Omissions company ever denied, paid claims on, or cancelled your coverage?',
        question: '8A'
    },
    {
        id: 67,
        name: 'lawsuit_checkbox_8b',
        description: 'lawsuit_checkbox_8b_text',
        heading: 'Has any Errors & Ommisions company ever denied, paid claims on, or cancelled your coverage?',
        question: '8b'
    },
    {
        id: 68,
        name: 'license_denied_checkbox_9',
        description: 'license_denied_checkbox_9_text',
        heading: 'Have you ever had an insurance or securities license denied, suspended, cancelled, or revoked?',
        question: '9'
    },
    {
        id: 69,
        name: 'regulatory_checkbox_10',
        description: 'regulatory_checkbox_10_text',
        heading: 'Has any state or federal regulatory body found you to have been a cause of an investment or insurance-related business having its authorization to do business denied, suspended, revoked, or restricted?',
        question: '10'
    },
    {
        id: 70,
        name: 'regulatory_revoked_checkbox_11',
        description: 'regulatory_revoked_checkbox_11_text',
        heading: 'Has any state or federal regulatory agency revoked or suspended your license as an attorney, accountant, or federal contractor?',
        question: '11'
    },
    {
        id: 71,
        name: 'regulatory_found_checkbox_12',
        description: 'regulatory_found_checkbox_12_text',
        heading: 'Has any state or federal regulatory agency found you to have made a false statement or omission or been dishonest, unfair, or unethical?',
        question: '12'
    },
    {
        id: 72,
        name: 'interr_licensing_checkbox_13',
        description: 'interr_licensing_checkbox_13_text',
        heading: 'Have you ever had any interruptions in licensing?',
        question: '13'
    },
    {
        id: 73,
        name: 'self_regularity_checkbox_14',
        description: 'self_regularity_checkbox_14_text',
        heading: 'Has any state, federal, or self-regulatory agency filed a complaint against you, fined, sanctioned, censured, penalized, or otherwise disciplined you for a violation of their regulations or state or federal statuses?',
        question: '14'
    },
    {
        id: 74,
        name: 'self_regularity_checkbox_14a',
        description: 'self_regularity_checkbox_14a_text',
        heading: 'Has any regulatory body ever sanctioned, censured, penalized, or otherwise disciplined you?',
        question: '14A'
    },
    {
        id: 75,
        name: 'self_regularity_checkbox_14b',
        description: 'self_regularity_checkbox_14b_text',
        heading: 'Has any state, federal, or self-regulatory agency filed a complaint against you, fined, or sanctioned you?',
        question: '14B'
    },
    {
        id: 76,
        name: 'self_regularity_checkbox_14c',
        description: 'self_regularity_checkbox_14c_text',
        heading: 'Have you ever been the subject of a consumer-initiated complaint?',
        question: '14C'
    },
    {
        id: 77,
        name: 'bankruptcy_checkbox_15',
        description: 'bankruptcy_checkbox_15_text',
        heading: 'Have you personally, or any insurance or securities brokerage firm with whom you have been associated, filed a bankruptcy petition, or declared bankruptcy? 15A. Have you personally filed a bankruptcy petition or declared bankruptcy?',
        question: '15'
    },
    {
        id: 78,
        name: 'bankruptcy_checkbox_15a',
        description: 'bankruptcy_checkbox_15a_text',
        heading: 'Have you personally, or any insurance or securities brokerage firm with whom you have been associated, filed a bankruptcy petition, or declared bankruptcy? 15A. Have you personally filed a bankruptcy petition or declared bankruptcy?',
        question: '15a'
    },
    {
        id: 79,
        name: 'bankruptcy_checkbox_15b',
        description: 'bankruptcy_checkbox_15b_text',
        heading: 'Has any insurance or securities brokerage firm, with whom you have been associated, filed a bankruptcy petition, or been declared bankrupt, either during your association with them or within 5 years after termination of such an association?',
        question: '15B'
    },
    {
        id: 80,
        name: 'bankruptcy_checkbox_15c',
        description: 'bankruptcy_checkbox_15c_text',
        heading: 'Is the bankruptcy pending?',
        question: '15C'
    },
    {
        id: 81,
        name: 'liens_against_checkbox_16',
        description: 'liens_against_checkbox_16_text',
        heading: 'Are there any unsatisfied judgements or liens against you?',
        question: '16'
    },
    {
        id: 82,
        name: 'connected_checkbox_17',
        description: 'connected_checkbox_17_text',
        heading: 'Are you connected in any way with a bank, savings & loan association, or other lending or financial institution?',
        question: '17'
    },
    {
        id: 83,
        name: 'aliases_checkbox_18',
        description: 'aliases_checkbox_18_text',
        heading: 'Have you ever used any other names or aliases?',
        question: '18'
    },
    {
        id: 84,
        name: 'unresolved_matter_checkbox_19',
        description: 'unresolved_matter_checkbox_19_text',
        heading: 'Do you have any unresolved matters pending with the Internal Revenue Service, or other taxing authority?',
        question: '19'
    },
    {
        id: 85,
        name: 'contract_commission_checkbox_20',
        description: 'contract_commission_checkbox_20_text',
        heading: 'Have you SIGNED CONTRACTS or BEEN PAID COMMISSIONS with any insurance carriers in the last 6 months?',
        question: '20'
    },

]);

let form = ref({

})
let date = new Date()
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
if (props.userData.internal_agent_contract.legal_question) {
    props.userData.internal_agent_contract.legal_question.forEach((question) => {
    const matchingLegalInfo = LegalInformation.value.find((info) => info.name === question.name);
    if (matchingLegalInfo) {
        form.value[matchingLegalInfo.name] = question.value;
        form.value[matchingLegalInfo.name + '_text'] = question.description;
    }
});


}

</script>
<template>
    <div class="container mx-auto p-5">
        <h1 style="background-color: #134576;" class="mb-4 mt-4	text-center rounded-md py-2 text-white">
            Basic Information
        </h1>
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 bg-white rounded-lg shadow-md gap-4">
            <div class="bg-white  p-4">
                <p class="text-gray-600"><strong>Last Name:</strong> {{ userData.internal_agent_contract?.last_name }}</p>
                <p class="text-gray-600"><strong>First Name:</strong> {{ userData.internal_agent_contract?.first_name }}</p>
                <p class="text-gray-600"><strong>Middle Name:</strong> {{ userData.internal_agent_contract?.middle_name }}
                </p>
                <p class="text-gray-600"><strong>Social Security Number (SSN):</strong> {{
                    userData.internal_agent_contract?.ssn }}</p>
                <p class="text-gray-600"><strong>Gender:</strong> {{ userData.internal_agent_contract?.gender }}</p>
                <p class="text-gray-600"><strong>Date of Birth:</strong> {{ dateFormat(userData.internal_agent_contract?.dob) }}</p>
                <p class="text-gray-600"><strong>Cell Phone: </strong> {{ userData.internal_agent_contract?.cell_phone }}</p>
                <p class="text-gray-600"><strong>Home Phone: </strong> {{ userData.internal_agent_contract?.home_phone }}</p>
                <!-- Add more data fields as needed -->
            </div>
            <div class="bg-white  p-4">

                <p class="text-gray-600"><strong>Fax: </strong> {{ userData.internal_agent_contract?.fax }}</p>
                <p class="text-gray-600"><strong>Email: </strong> {{ userData.internal_agent_contract?.email }}</p>
                <p class="text-gray-600"><strong>Married Status: </strong> {{
                    userData.internal_agent_contract?.marital_status }}</p>
                <p class="text-gray-600"><strong>Drivers License: </strong> {{
                    userData.internal_agent_contract?.driver_license_no }}</p>
                <p class="text-gray-600"><strong>Driver Licence State: </strong> {{
                    userData.internal_agent_contract?.driver_license_state }}</p>
                <p class="text-gray-600"><strong>Current Address (Residence): </strong> {{
                    userData.internal_agent_contract?.address }}</p>
                <p class="text-gray-600"><strong>City: </strong> {{ userData.internal_agent_contract?.city }}</p>
                <p class="text-gray-600"><strong>State: </strong> {{ userData.internal_agent_contract?.state }}</p>
            </div>
            <div class="bg-white  p-4">
                <p class="text-gray-600"><strong>Zip Code: </strong> {{ userData.internal_agent_contract?.zip }}</p>
                <p class="text-gray-600"><strong>Move-In Date: </strong> {{ dateFormat(userData.internal_agent_contract?.move_in_date) }}
                </p>
                <p class="text-gray-600"><strong>Mailing Address (If Diffrent From Residence): </strong> {{
                    userData.internal_agent_contract?.move_in_address }}</p>
                <p class="text-gray-600"><strong>City: </strong> {{ userData.internal_agent_contract?.move_in_city }}</p>
                <p class="text-gray-600"><strong>State: </strong> {{ userData.internal_agent_contract?.move_in_state }}</p>
                <p class="text-gray-600"><strong>Zip Code: </strong> {{ userData.internal_agent_contract?.move_in_zip }}</p>
            </div>
        </div>
        <hr>
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 bg-white rounded-lg shadow-md gap-4">
            <div class="bg-white  p-4">
                <p class="text-gray-600"><strong>Resident Insurance License: </strong> {{
                    userData.internal_agent_contract?.resident_insu_license_no }}</p>
            </div>
            <div class="bg-white  p-4">
                <p class="text-gray-600"><strong>Resident Insurance License State: </strong> {{
                    userData.internal_agent_contract?.resident_insu_license_state }}</p>
            </div>
            <div class="bg-white  p-4">
                <p class="text-gray-600"><strong>Doing Business As: </strong> {{
                    userData.internal_agent_contract?.doing_business_as }}</p>
            </div>
        </div>
        <hr>
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 bg-white rounded-lg shadow-md gap-4">
            <!-- Repeat this card for each record -->
            <div class="bg-white  p-4">
                <p class="text-gray-600"><strong>Business Name: </strong> {{ userData.internal_agent_contract?.business_name
                }}</p>
                <p class="text-gray-600"><strong>Tax ID: </strong> {{ userData.internal_agent_contract?.business_tax_id }}
                </p>
                <p class="text-gray-600"><strong>Principle Agent Name: </strong> {{
                    userData.internal_agent_contract?.business_agent_name }}</p>
                <p class="text-gray-600"><strong>Priciple Agent Title: </strong> {{
                    userData.internal_agent_contract?.business_agent_title }}</p>
                <p class="text-gray-600"><strong>Business Insurance Licence: </strong> {{
                    userData.internal_agent_contract?.business_insu_license_no }}</p>
                <p class="text-gray-600"><strong>Cell Fax: </strong> {{ userData.internal_agent_contract?.business_office_fax
                }}</p>
            </div>
            <div class="bg-white  p-4">
                <p class="text-gray-600"><strong>Office Phone: </strong> {{
                    userData.internal_agent_contract?.business_office_phone }}</p>
                <p class="text-gray-600"><strong>Email: </strong> {{ userData.internal_agent_contract?.business_email }}</p>
                <p class="text-gray-600"><strong>Website: </strong> {{ userData.internal_agent_contract?.business_website }}
                </p>
                <p class="text-gray-600"><strong>Business Address: </strong> {{
                    userData.internal_agent_contract?.business_address }}</p>
                <p class="text-gray-600"><strong>City: </strong> {{ userData.internal_agent_contract?.business_city }}</p>
                <p class="text-gray-600"><strong>State: </strong> {{ userData.internal_agent_contract?.business_state }}</p>
            </div>
            <div class="bg-white  p-4">
                <p class="text-gray-600"><strong>Zip Code: </strong> {{ userData.internal_agent_contract?.business_zip }}</p>
                <p class="text-gray-600"><strong>Move-In Date: </strong> {{
                    dateFormat(userData.internal_agent_contract?.business_move_in_date) }}</p>
                <p class="text-gray-600"><strong>Company Type: </strong> {{
                    userData.internal_agent_contract?.business_company_type }}</p>
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
        <div class=" flex bg-white rounded-lg  gap-4 mt-4 mb-4">
            <div style="padding: 10px; width: 30%; background: #ebe8e8;">
                <img class="mb-5" :src="userData.internal_agent_contract?.get_question_sign.sign_url" alt="signature" />
                <div> <strong class="mx-2">Date: </strong> {{ dateFormat(userData.internal_agent_contract?.get_question_sign.created_at) }}</div>
            </div>
        </div>
        <hr class="mb-5">

        <h1 style="background-color: #134576;" class="mb-4	text-center rounded-md py-2 text-white">
            Your Address History for the Past 7 Years
        </h1>
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 bg-white rounded-lg shadow-md gap-4">
            <div v-for="address in userData.internal_agent_contract?.addresses" class="bg-white  p-4">
                <p class="text-gray-600"><strong>Home Address:</strong> {{ address.address }}</p>
                <p class="text-gray-600"><strong>City:</strong> {{ address.city }}</p>
                <p class="text-gray-600"><strong>State:</strong> {{ address.state }}
                </p>
                <p class="text-gray-600"><strong>Zip Code:</strong> {{
                    address.zip_code }}</p>
                <p class="text-gray-600"><strong>Move-In Date:</strong> {{
                    dateFormat(address.move_in_date) }}</p>
                <p class="text-gray-600"><strong>Move-Out Date:</strong> {{
                    dateFormat(address.move_out_date) }}
                </p>
                <!-- Add more data fields as needed -->
            </div>
        </div>
        <h1 style="background-color: #134576;" class="mb-4 mt-4	text-center rounded-md py-2 text-white">
            Additional Information
        </h1>
        <div class="grid grid-cols-2 p-4 md:grid-cols-2 lg:grid-cols-3 bg-white rounded-lg shadow-md gap-4">
            <div class="bg-white  ">
                <p class="text-gray-600"><strong>Resident Country:</strong> {{
                    userData.internal_agent_contract?.additional_info.resident_country
                }}</p>
            </div>

            <div class="bg-white  ">
                <p class="text-gray-600"><strong>Do you own your home?:</strong> {{
                    userData.internal_agent_contract?.additional_info.resident_your_home }}</p>
            </div>
            <div class="bg-white  ">
                <p class="text-gray-600"><strong>City of Birth:</strong> {{
                    userData.internal_agent_contract?.additional_info.resident_city }}
                </p>
            </div>
            <div class="bg-white  ">
                <p class="text-gray-600"><strong>State of Birth:</strong> {{
                    userData.internal_agent_contract?.additional_info.resident_state }}</p>
            </div>
            <div class="bg-white  ">
                <p class="text-gray-600"><strong>Maiden Name:</strong> {{
                    userData.internal_agent_contract?.additional_info.resident_maiden_name }}</p>
            </div>
        </div>
    </div>
</template>
<script>
</script>