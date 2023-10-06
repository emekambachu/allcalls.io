<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed } from "vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
const props = defineProps({
    contractStep: String,
    firstStepErrors: Object,
    userData: Object,
});
let page = usePage();

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


])
let form = ref({

})
if (page.props.auth.role === 'admin' && props.userData.internal_agent_contract) {
    props.userData.internal_agent_contract.legal_question.forEach((question) => {
        const matchingLegalInfo = LegalInformation.value.find((info) => info.name === question.name);
        if (matchingLegalInfo) {
            form.value[matchingLegalInfo.name] = question.value;
            form.value[matchingLegalInfo.name + '_text'] = question.description
        }
    });
}


const emits = defineEmits();
// watch(form.value, (newForm, oldForm) => {
//     emits("updateFormData", newForm);
// });

let ChangeTab = () => {
    for (const key in props.firstStepErrors) {
        if (props.firstStepErrors.hasOwnProperty(key)) {
            props.firstStepErrors[key] = [];
        }
    }

    // Define an array of field names that are required
    if (page.props.auth.role === 'internal-agent') {
        for (const information of LegalInformation.value) {
            let checboxValue = form.value[information.name]
            if (!form.value[information.name]) {
                props.firstStepErrors[information.name] = [`This field is required.`];
            } else if (checboxValue === "YES") {
                if (!form.value[information.name + '_text']) {
                    props.firstStepErrors[information.name] = [`This field is required.`];
                }
            }
        }
    }
    // Check if there are any errors

    const hasErrors = Object.values(props.firstStepErrors).some(errors => errors.length > 0);
    if (!hasErrors) {
        emits("changeTab", form.value);
    } else {
        var element = document.getElementById("modal_main_id");
        element.scrollIntoView();
    }
}
let ChangeTabBack = () => {
    emits("goback");
}
</script>
<template>
    <h1 style="background-color: #134576;" class="mb-4	text-center rounded-md py-2 text-white">
        Legal Questions
    </h1>
    <div style="width:100%;" class="flex justify-around">
        <p> <strong>For contracting and appointment requests, please answer the following questions. If
                you answer YES to any question, you must provide documentation including a full, detailed explanation and
                specific dates. Please answer every question including subquestions for clarity.</strong></p>
    </div>

    <hr class="w-100 h-1 my-4 bg-gray-600 border-0 rounded dark:bg-gray-700">

    <div v-for="(information, index) in LegalInformation" :key="information.id">
        <div style="width: 100%;" class="flex justify-around">
            <div style="width: 90%;">
                <p>
                    <strong>
                        <span style="font-size: 18px;" class="mr-2">{{ information.question }}.</span>{{ information.heading
                        }}<span class="text-red-500 ml-1">*</span>
                    </strong>
                </p>

                <div class="flex mt-4">
                    <div class="flex items-center mr-4">
                        <input :id="'default-radio-' + information.id + '-yes'" v-model="form[information.name]" value="YES"
                            type="radio" :name="'question-' + information.id" :checked="form[information.name] === 'YES'"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                        <label :for="'default-radio-' + information.id + '-yes'"
                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">YES</label>
                    </div>
                    <div class="flex items-center">
                        <input :id="'default-radio-' + information.id + '-no'" v-model="form[information.name]" value="NO"
                            type="radio" :name="'question-' + information.id" :checked="form[information.name] === 'NO'"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                        <label :for="'default-radio-' + information.id + '-no'"
                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">NO</label>
                    </div>

                </div>


                <input type="text" v-show="form[information.name] === 'YES'" v-model="form[information.name + '_text']"
                    class="bg-gray-50 mt-5 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <div v-if="firstStepErrors[information.name]" class="text-red-500 mt-3"
                    v-text="firstStepErrors[information.name][0]"></div>
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
                <button type="button" @click="ChangeTab" class="button-custom px-3 py-2 rounded-md">
                    Next
                </button>

            </div>
        </div>
    </div>
</template>