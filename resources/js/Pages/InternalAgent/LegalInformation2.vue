
<template>
    <h1 style="background-color: #134576;" class="mb-4	text-center rounded-md py-2 text-white">
        Legal Questions
    </h1>
    <div style="width:100%;" class="flex justify-around">
        <p> <strong>For contracting and appointment requests, please answer the following questions. If you answer YES to
                any question, you must provide documentation including a full, detailed explanation and specific dates.
                Please answer every question including subquestions for clarity.</strong></p>
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
                        <input :disabled="page.auth.role === 'admin'" :id="'default-radio-' + information.id + '-yes'" v-model="form[information.name]" value="YES"
                            type="radio" :name="'question-' + information.id" :checked="form[information.name] === 'YES'"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                        <label :for="'default-radio-' + information.id + '-yes'"
                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">YES</label>
                    </div>
                    <div class="flex items-center">
                        <input :disabled="page.auth.role === 'admin'" :id="'default-radio-' + information.id + '-no'" v-model="form[information.name]" value="NO"
                            type="radio" :name="'question-' + information.id" :checked="form[information.name] === 'NO'"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                        <label :for="'default-radio-' + information.id + '-no'"
                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">NO</label>
                    </div>
                    <a target="_blank" :href="route('admin.agent.legal.question.pdf', [form[information.id],userData.id,information.question])" claass="text-blue-600  cursor-pointer" v-if="form[information.name] === 'YES' && page.auth.role === 'admin'&& userData.internal_agent_contract.legal_question" class="ml-5"><svg
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-blue-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                    </a>
                </div>
                <input :disabled="page.auth.role === 'admin'" type="text"
                    v-show="form[information.name] === 'YES' && information.name != 'contract_commission_checkbox_20'"
                    v-model="form[information.name + '_text']"
                    class="bg-gray-50 mt-5 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <div class="mt-5">
                    <Multiselect
                        :disabled="page.auth.role === 'admin'"
                        v-show="form[information.name] === 'YES' && information.name === 'contract_commission_checkbox_20'"
                        v-model="form[information.name + '_text']" :options="contract_commissions" track-by="value"
                        label="label" mode="tags" :close-on-select="false" placeholder="Choose">
                    </Multiselect>
                </div>
                <div v-if="firstStepErrors[information.name]" class="text-red-500 mt-3"
                    v-text="firstStepErrors[information.name][0]"></div>
            </div>
        </div>
        <hr class="w-100 h-1 my-4 bg-gray-600 border-0 rounded dark:bg-gray-700">
    </div>

    <div style="width:100%;" class="flex justify-around">
        <p> <strong>I attest that the information I have provided is true to the best of my knowledge. I acknowledge that if
                any information changes, I will notify my agency office within 5 days of such change. Further, I understand
                that my agency may contact me when I need to answer carrier-specific questions.</strong></p>
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
<script>
import Multiselect from "@vueform/multiselect";
export default {
    components: { Multiselect },
    name: "App",
    data: () => ({
        date: new Date(),
        isLoading: false,
        LegalInformation: [
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
            {
                id: 43,
                name: 'contract_commission_checkbox_20',
                heading: 'Have you SIGNED CONTRACTS or BEEN PAID COMMISSIONS with any insurance carriers in the last 6 months?',
                question: '20'
            },
        ],
        form: {
            contract_commission_checkbox_20_text: [],
        },
        accompanying_sign: null,
        contract_commissions: [
            'Aetna/Accendo',
            'Aig',
            'American Amicable',
            'Americo',
            'Ameritas',
            'Assurant',
            'Athene',
            'Columbian Financial Group',
            'Columbus Life',
            'Ethos',
            'Fidelity and Guaranty Life',
            'Foresters',
            'Gerber Life',
            'Government Personnel Mutual',
            'Great Western',
            'John Hancock',
            'Mutual Of Omaha',
            'National Life Group',
            'Occidental',
            'Prosperity Life',
            'Royal Neighbors',
            'TransAmerica'
        ]

    }),
    mounted() {
        if (this.userData.internal_agent_contract && this.userData.internal_agent_contract.legal_question) {
            this.userData.internal_agent_contract.legal_question.forEach((question) => {
                const matchingLegalInfo = this.LegalInformation.find((info) => info.name === question.name);
                if (matchingLegalInfo && question.name != 'contract_commission_checkbox_20') {
                    this.form[matchingLegalInfo.id] = question.id;
                    this.form[matchingLegalInfo.name] = question.value;
                    this.form[matchingLegalInfo.name + '_text'] = question.description
                } else if (question.name === 'contract_commission_checkbox_20') {
                    this.form[matchingLegalInfo.id] = question.id;
                    this.form[matchingLegalInfo.name] = question.value;
                    if (question.value === "YES") {
                        this.form[matchingLegalInfo.name + '_text'] = question.description.split(',');
                    }
                }
            });
        }
    },
    methods: {
        dateFormat(dateString) {
            const dateObj = new Date(dateString);

            const day = dateObj.getDate().toString().padStart(2, "0");
            const month = dateObj.toLocaleString("en-US", { month: "short" }).toLowerCase();
            const year = dateObj.getFullYear();

            return `${day}-${month}-${year}`;
        },

        ChangeTab() {
            if (this.page.auth.role === 'admin') {
                this.$emit("changeTab");
            } else {
                for (const key in this.firstStepErrors) {
                    if (this.firstStepErrors.hasOwnProperty(key)) {
                        this.firstStepErrors[key] = [];
                    }
                }

                if (this.page.auth.role === 'internal-agent') {
                    for (const information of this.LegalInformation) {
                        let checboxValue = this.form[information.name];
                        if (!this.form[information.name]) {
                            this.firstStepErrors[information.name] = [`This field is required.`];
                        } else if (checboxValue === "YES") {
                            if (!this.form[information.name + '_text']) {
                                this.firstStepErrors[information.name] = [`This field is required.`];
                            }
                            if (this.form[information.name + '_text'] === this.form.contract_commission_checkbox_20_text) {
                                if (this.form.contract_commission_checkbox_20_text.length === 0) {
                                    this.firstStepErrors[information.name] = [`This field is required.`];
                                }
                            }
                        }
                    }
                }

                const hasErrors = Object.values(this.firstStepErrors).some(errors => errors.length > 0);

                if (!hasErrors) {
                    if (this.page.auth.role === 'internal-agent') {
                        this.$emit("legalFormDataStep2", this.form);
                        // emits("legalFormDataStep2", this.form);
                        this.firstStepErrors = {}; // Clear the errors by assigning a new empty object

                    } else {
                        this.$emit("changeTab");
                    }


                } else {
                    var element = document.getElementById("modal_main_id");
                    element.scrollIntoView();
                }
            }

        },
        ChangeTabBack() {
            this.$emit("goback");
        },
    },
    props: {
        contractStep: String,
        firstStepErrors: Object,
        userData: Object,
        page: Object,
        isLoading: Boolean,
    },

};
</script>

<style src="@vueform/multiselect/themes/default.css"></style>
<style>
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type="number"] {
    -moz-appearance: textfield;
}

.multiselect {
    color: black !important;
    border: none;
    border-radius: 10px;
}

.multiselect-wrapper {
    background-color: #d7d7d7;
    border-radius: 5px;
}
</style>



