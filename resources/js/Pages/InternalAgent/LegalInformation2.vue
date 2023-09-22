<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
const props = defineProps({
    contractStep: String,
});
let LegalInformation = ref([
    {   id:23,
        checbox:'convicted_checkbox_8',
        heading: 'Has any lawsuit or claim ever been made against you, your surety company, or errors and omissions insurer, arising out of your sales or practices, or have you been refused surety bonding or E&O coverage?',
        question: '8'
    },
    {
        id:24,
        checbox:'convicted_checkbox_8a',
        heading: 'Has a bonding or surety company ever denied, paid on, or revoked a bond for you? 8B. Has any Errors & Omissions company ever denied, paid claims on, or cancelled your coverage?',
        question: '8A'
    },
    {
        id:25,
        checbox:'convicted_checkbox_9',
        heading: 'Have you ever had an insurance or securities license denied, suspended, cancelled, or revoked?',
        question: '9'
    },
    {
        id:26,
        checbox:'convicted_checkbox_10',
        heading: 'Has any state or federal regulatory body found you to have been a cause of an investment or insurance-related business having its authorization to do business denied, suspended, revoked, or restricted?',
        question: '10'
    },
    {
        id:27,
        checbox:'convicted_checkbox_11',
        heading: 'Has any state or federal regulatory agency revoked or suspended your license as an attorney, accountant, or federal contractor?',
        question: '11'
    },
    {
        id:28,
        checbox:'convicted_checkbox_12',
        heading: 'Has any state or federal regulatory agency found you to have made a false statement or omission or been dishonest, unfair, or unethical?',
        question: '12'
    },
    {
        id:29,
        checbox:'convicted_checkbox_13',
        heading: 'Have you ever had any interruptions in licensing?',
        question: '13'
    },
    {
        id:30,
        checbox:'convicted_checkbox_14',
        heading: 'Has any state, federal, or self-regulatory agency filed a complaint against you, fined, sanctioned, censured, penalized, or otherwise disciplined you for a violation of their regulations or state or federal statuses?',
        question: '14'
    },
    {
        id:31,
        checbox:'convicted_checkbox_14a',
        heading: 'Has any regulatory body ever sanctioned, censured, penalized, or otherwise disciplined you?',
        question: '14A'
    },
    {
        id:32,
        checbox:'convicted_checkbox_14b',
        heading: 'Has any state, federal, or self-regulatory agency filed a complaint against you, fined, or sanctioned you?',
        question: '14B'
    },
    {
        id:33,
        checbox:'convicted_checkbox_14c',
        heading: 'Have you ever been the subject of a consumer-initiated complaint?',
        question: '14C'
    },
    {
        id:34,
        checbox:'convicted_checkbox_15',
        heading: 'Have you personally, or any insurance or securities brokerage firm with whom you have been associated, filed a bankruptcy petition, or declared bankruptcy? 15A. Have you personally filed a bankruptcy petition or declared bankruptcy?',
        question: '15'
    },
    {
        id:35,
        checbox:'convicted_checkbox_15b',
        heading: 'Has any insurance or securities brokerage firm, with whom you have been associated, filed a bankruptcy petition, or been declared bankrupt, either during your association with them or within 5 years after termination of such an association?',
        question: '15B'
    },
    {
        id:36,
        checbox:'convicted_checkbox_15c',
        heading: 'Is the bankruptcy pending?',
        question: '15C'
    },
    {
        id:37,
        checbox:'convicted_checkbox_16',
        heading: 'Are there any unsatisfied judgements or liens against you?',
        question: '16'
    },
    {
        id:38,
        checbox:'convicted_checkbox_17',
        heading: 'Are you connected in any way with a bank, savings & loan association, or other lending or financial institution?',
        question: '17'
    },
    {
        id:39,
        checbox:'convicted_checkbox_18',
        heading: 'Have you ever used any other names or aliases?',
        question: '18'
    },
    {
        id:40,
        checbox:'convicted_checkbox_19',
        heading: 'Do you have any unresolved matters pending with the Internal Revenue Service, or other taxing authority?',
        question: '19'
    },
    
    

])
let page2form  = ref({
    
})
const emits = defineEmits();
watch(page2form.value, (newForm, oldForm) => {
    emits("updateFormData", newForm);
});
</script>
<template>
    <div style="width:100%;" class="flex justify-around">
        <p > <strong>For contracting and appointment requests, please answer the following questions. If you answer YES to any question, you must provide documentation including a full, detailed explanation and specific dates. Please answer every question including subquestions for clarity.</strong></p>
    </div>

    <hr class="w-100 h-1 my-4 bg-gray-600 border-0 rounded dark:bg-gray-700">

    <div v-for="(information, index) in LegalInformation" :key="information.id">
        <div style="width: 100%;" class="flex justify-around">
            <div style="width: 90%;">
                <p>
                    <strong>
                        <span style="font-size: 18px;" class="mr-2">{{ information.question }}.</span>{{ information.heading
                        }}
                    </strong>
                </p>

                <div class="flex mt-4">
                    <div class="flex items-center mr-4">
                        <input :id="'default-radio-' + information.id + '-yes'" v-model="page2form[information.checbox]"
                            value="YES" type="radio" :name="'question-' + information.id"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                        <label :for="'default-radio-' + information.id + '-yes'"
                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">YES</label>
                    </div>
                    <div class="flex items-center">
                        <input :id="'default-radio-' + information.id + '-no'" v-model="page2form[information.checbox]"
                            value="NO" type="radio" :name="'question-' + information.id"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                        <label :for="'default-radio-' + information.id + '-no'"
                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">NO</label>
                    </div>
                </div>
            </div>
        </div>
        <hr class="w-100 h-1 my-4 bg-gray-600 border-0 rounded dark:bg-gray-700">
    </div>

    <div style="width:100%;" class="flex justify-around">
        <p > <strong>I attest that the information I have provided is true to the best of my knowledge. I acknowledge that if any information changes, I will notify my agency office within 5 days of such change. Further, I understand that my agency may contact me when I need to answer carrier-specific questions.</strong></p>
    </div>
    
</template>