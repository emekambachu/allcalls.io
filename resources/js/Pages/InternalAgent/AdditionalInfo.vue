
<style scoped>
#signature {
    border: solid 1px lightgray;
    padding: 10px;
    border-radius: 10px;

}

#signature canvas {
    padding: 5px;

}

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

.container {
    width: 70%;
}

.child-singnature {
    width: 60%;
}

.child-singnature-date {
    width: 30% !important;
    margin-top: 113px;
}

.instructions-main {
    width: 70%;
}

@media (min-width: 200px) and (max-width: 1024px) {
    .container {
        width: 100% !important;
    }

    .instructions-main {
        width: 100%;
    }

    .child-singnature {
        width: 100% !important;
    }

    .child-singnature-date {
        width: 100% !important;
        margin-top: 0px;
    }
}
</style>
<template>
    <h1 style="background-color: #134576;" class="mb-4	text-center rounded-md py-2 text-white">
        Additional Information
    </h1>
    <div>

        <div class="grid lg:grid-cols-2 mb-2  md:grid-cols-2 sm:grid-cols-1 gap-4">
            <div>
                <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 ">Resident
                    Country {{ form.resident_country }}<span class="text-red-500 ">*</span></label>
                <select :disabled="page.auth.role === 'admin'" v-model="form.resident_country" id="countries"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                    <option v-for="country in countries" :value="country.code">{{ country.name }} </option>
                </select>
                <!-- <input type="text" v-model="form.resident_country" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white"> -->
                <div v-if="firstStepErrors.resident_country" class="text-red-500"
                    v-text="firstStepErrors.resident_country[0]"></div>
            </div>
            <div class=" mt-4 lg:ml-4 sm:ml-0">
                <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 ">Do you own
                    your home?<span class="text-red-500 ">*</span></label>
                <div class="flex">
                    <div class="flex items-center mr-4">

                        <input :disabled="page.auth.role === 'admin'" id="default-radio-1-1"
                            v-model="form.resident_your_home" type="radio" value="YES"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="default-radio-1-1"
                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">YES</label>
                    </div>
                    <div class="flex items-center">
                        <input :disabled="page.auth.role === 'admin'" id="default-radio-2-2"
                            v-model="form.resident_your_home" type="radio" value="NO"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="default-radio-2-2"
                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">NO</label>
                    </div>
                </div>
                <div v-if="firstStepErrors.resident_your_home" class="text-red-500"
                    v-text="firstStepErrors.resident_your_home[0]"></div>
            </div>



        </div>
        <div class="grid lg:grid-cols-3 mb-2  md:grid-cols-2 sm:grid-cols-1 gap-4">
            <div>
                <label for="last_name" class="block mb-2 text-sm font-black text-gray-900 ">City of
                    Birth<span class="text-red-500 ">*</span></label>
                <input :disabled="page.auth.role === 'admin'" type="text" v-model="form.resident_city" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                <div v-if="firstStepErrors.resident_city" class="text-red-500" v-text="firstStepErrors.resident_city[0]">
                </div>
            </div>
            <div>
                <label for="last_name" class="block mb-2 text-sm font-black text-gray-900 "> State of
                    Birth<span class="text-red-500 ">*</span></label>
                <select :disabled="page.auth.role === 'admin'" v-model="form.resident_state" id="countries"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                    <option>Choose </option>
                    <option v-for="state in states" :value="state.id">{{ state.full_name }} </option>
                </select>
                <div v-if="firstStepErrors.resident_state" class="text-red-500" v-text="firstStepErrors.resident_state[0]">
                </div>
            </div>

            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 ">Maiden
                    Name</label>
                <input :disabled="page.auth.role === 'admin'" type="text" v-model="form.resident_maiden_name"
                    id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                <div v-if="firstStepErrors.resident_maiden_name" class="text-red-500"
                    v-text="firstStepErrors.resident_maiden_name[0]"></div>
            </div>
        </div>
        <!-- <div class="mx-auto mb-5 instructions-main">
            <hr class="w-100 h-1 my-4 bg-gray-600 border-0 rounded dark:bg-gray-700">
            <p class="text-center">
                By signing below, l hereby authorize the Company to initiate credit entries and, if
                necessary, adjustments for credit entries in error to the checking and/or savings account
                indicated on this form. This authority is to remain in full effect until the Company has
                received written notice from me for its termination. I understand that this authorization
                is subject to the terms of any agent or representative contract, commission agreement,
                or loan agreement that I may have now, or in the future, with the Company.

            </p>
        </div> -->


        <div class="px-5 pb-6">
            <div class="flex justify-between flex-wrap">
                <div class="mt-4">

                    <button type="button" @click.prevent="ChangeTabBack" class="button-custom-back px-3 py-2 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                        </svg>
                        Back
                    </button>
                </div>
                <div class="mt-4">
                    <button type="button" :class="{ 'opacity-25': isLoading }" :disabled="isLoading" @click="ChangeTab"
                        class="button-custom px-3 py-2 rounded-md">
                        <global-spinner :spinner="isLoading" /> Next Step
                    </button>
                </div>

            </div>
        </div>
    </div>
</template>
<script>
import { Head, Link, useForm, usePage, router } from "@inertiajs/vue3";
export default {
    props: {
        firstStepErrors: Object,
        states: Array,
        userData: Object,
        page: Object,
        isLoading: Boolean,
        docuSignAuthCode: {
            type: String,
            default: null
        },
        additional_info_saved: Boolean,
    },
    data() {
        return {
            maxDate: new Date(),
            date: new Date(),
            options: {
                penColor: "black",
            },
            form: {
                resident_country: 'US',
                resident_your_home: null,
                resident_city: null,
                resident_state: 'Choose',
                resident_maiden_name: null,
            },
            countries: [
                { "name": "Afghanistan", "code": "AF" },
                { "name": "land Islands", "code": "AX" },
                { "name": "Albania", "code": "AL" },
                { "name": "Algeria", "code": "DZ" },
                { "name": "American Samoa", "code": "AS" },
                { "name": "AndorrA", "code": "AD" },
                { "name": "Angola", "code": "AO" },
                { "name": "Anguilla", "code": "AI" },
                { "name": "Antarctica", "code": "AQ" },
                { "name": "Antigua and Barbuda", "code": "AG" },
                { "name": "Argentina", "code": "AR" },
                { "name": "Armenia", "code": "AM" },
                { "name": "Aruba", "code": "AW" },
                { "name": "Australia", "code": "AU" },
                { "name": "Austria", "code": "AT" },
                { "name": "Azerbaijan", "code": "AZ" },
                { "name": "Bahamas", "code": "BS" },
                { "name": "Bahrain", "code": "BH" },
                { "name": "Bangladesh", "code": "BD" },
                { "name": "Barbados", "code": "BB" },
                { "name": "Belarus", "code": "BY" },
                { "name": "Belgium", "code": "BE" },
                { "name": "Belize", "code": "BZ" },
                { "name": "Benin", "code": "BJ" },
                { "name": "Bermuda", "code": "BM" },
                { "name": "Bhutan", "code": "BT" },
                { "name": "Bolivia", "code": "BO" },
                { "name": "Bosnia and Herzegovina", "code": "BA" },
                { "name": "Botswana", "code": "BW" },
                { "name": "Bouvet Island", "code": "BV" },
                { "name": "Brazil", "code": "BR" },
                { "name": "British Indian Ocean Territory", "code": "IO" },
                { "name": "Brunei Darussalam", "code": "BN" },
                { "name": "Bulgaria", "code": "BG" },
                { "name": "Burkina Faso", "code": "BF" },
                { "name": "Burundi", "code": "BI" },
                { "name": "Cambodia", "code": "KH" },
                { "name": "Cameroon", "code": "CM" },
                { "name": "Canada", "code": "CA" },
                { "name": "Cape Verde", "code": "CV" },
                { "name": "Cayman Islands", "code": "KY" },
                { "name": "Central African Republic", "code": "CF" },
                { "name": "Chad", "code": "TD" },
                { "name": "Chile", "code": "CL" },
                { "name": "China", "code": "CN" },
                { "name": "Christmas Island", "code": "CX" },
                { "name": "Cocos (Keeling) Islands", "code": "CC" },
                { "name": "Colombia", "code": "CO" },
                { "name": "Comoros", "code": "KM" },
                { "name": "Congo", "code": "CG" },
                { "name": "Congo, The Democratic Republic of the", "code": "CD" },
                { "name": "Cook Islands", "code": "CK" },
                { "name": "Costa Rica", "code": "CR" },
                { "name": "Cote D'Ivoire", "code": "CI" },
                { "name": "Croatia", "code": "HR" },
                { "name": "Cuba", "code": "CU" },
                { "name": "Cyprus", "code": "CY" },
                { "name": "Czech Republic", "code": "CZ" },
                { "name": "Denmark", "code": "DK" },
                { "name": "Djibouti", "code": "DJ" },
                { "name": "Dominica", "code": "DM" },
                { "name": "Dominican Republic", "code": "DO" },
                { "name": "Ecuador", "code": "EC" },
                { "name": "Egypt", "code": "EG" },
                { "name": "El Salvador", "code": "SV" },
                { "name": "Equatorial Guinea", "code": "GQ" },
                { "name": "Eritrea", "code": "ER" },
                { "name": "Estonia", "code": "EE" },
                { "name": "Ethiopia", "code": "ET" },
                { "name": "Falkland Islands (Malvinas)", "code": "FK" },
                { "name": "Faroe Islands", "code": "FO" },
                { "name": "Fiji", "code": "FJ" },
                { "name": "Finland", "code": "FI" },
                { "name": "France", "code": "FR" },
                { "name": "French Guiana", "code": "GF" },
                { "name": "French Polynesia", "code": "PF" },
                { "name": "French Southern Territories", "code": "TF" },
                { "name": "Gabon", "code": "GA" },
                { "name": "Gambia", "code": "GM" },
                { "name": "Georgia", "code": "GE" },
                { "name": "Germany", "code": "DE" },
                { "name": "Ghana", "code": "GH" },
                { "name": "Gibraltar", "code": "GI" },
                { "name": "Greece", "code": "GR" },
                { "name": "Greenland", "code": "GL" },
                { "name": "Grenada", "code": "GD" },
                { "name": "Guadeloupe", "code": "GP" },
                { "name": "Guam", "code": "GU" },
                { "name": "Guatemala", "code": "GT" },
                { "name": "Guernsey", "code": "GG" },
                { "name": "Guinea", "code": "GN" },
                { "name": "Guinea-Bissau", "code": "GW" },
                { "name": "Guyana", "code": "GY" },
                { "name": "Haiti", "code": "HT" },
                { "name": "Heard Island and Mcdonald Islands", "code": "HM" },
                { "name": "Holy See (Vatican City State)", "code": "VA" },
                { "name": "Honduras", "code": "HN" },
                { "name": "Hong Kong", "code": "HK" },
                { "name": "Hungary", "code": "HU" },
                { "name": "Iceland", "code": "IS" },
                { "name": "India", "code": "IN" },
                { "name": "Indonesia", "code": "ID" },
                { "name": "Iran, Islamic Republic Of", "code": "IR" },
                { "name": "Iraq", "code": "IQ" },
                { "name": "Ireland", "code": "IE" },
                { "name": "Isle of Man", "code": "IM" },
                { "name": "Israel", "code": "IL" },
                { "name": "Italy", "code": "IT" },
                { "name": "Jamaica", "code": "JM" },
                { "name": "Japan", "code": "JP" },
                { "name": "Jersey", "code": "JE" },
                { "name": "Jordan", "code": "JO" },
                { "name": "Kazakhstan", "code": "KZ" },
                { "name": "Kenya", "code": "KE" },
                { "name": "Kiribati", "code": "KI" },
                { "name": "Korea, Democratic People'S Republic of", "code": "KP" },
                { "name": "Korea, Republic of", "code": "KR" },
                { "name": "Kuwait", "code": "KW" },
                { "name": "Kyrgyzstan", "code": "KG" },
                { "name": "Lao People'S Democratic Republic", "code": "LA" },
                { "name": "Latvia", "code": "LV" },
                { "name": "Lebanon", "code": "LB" },
                { "name": "Lesotho", "code": "LS" },
                { "name": "Liberia", "code": "LR" },
                { "name": "Libyan Arab Jamahiriya", "code": "LY" },
                { "name": "Liechtenstein", "code": "LI" },
                { "name": "Lithuania", "code": "LT" },
                { "name": "Luxembourg", "code": "LU" },
                { "name": "Macao", "code": "MO" },
                { "name": "Macedonia, The Former Yugoslav Republic of", "code": "MK" },
                { "name": "Madagascar", "code": "MG" },
                { "name": "Malawi", "code": "MW" },
                { "name": "Malaysia", "code": "MY" },
                { "name": "Maldives", "code": "MV" },
                { "name": "Mali", "code": "ML" },
                { "name": "Malta", "code": "MT" },
                { "name": "Marshall Islands", "code": "MH" },
                { "name": "Martinique", "code": "MQ" },
                { "name": "Mauritania", "code": "MR" },
                { "name": "Mauritius", "code": "MU" },
                { "name": "Mayotte", "code": "YT" },
                { "name": "Mexico", "code": "MX" },
                { "name": "Micronesia, Federated States of", "code": "FM" },
                { "name": "Moldova, Republic of", "code": "MD" },
                { "name": "Monaco", "code": "MC" },
                { "name": "Mongolia", "code": "MN" },
                { "name": "Montenegro", "code": "ME" },
                { "name": "Montserrat", "code": "MS" },
                { "name": "Morocco", "code": "MA" },
                { "name": "Mozambique", "code": "MZ" },
                { "name": "Myanmar", "code": "MM" },
                { "name": "Namibia", "code": "NA" },
                { "name": "Nauru", "code": "NR" },
                { "name": "Nepal", "code": "NP" },
                { "name": "Netherlands", "code": "NL" },
                { "name": "Netherlands Antilles", "code": "AN" },
                { "name": "New Caledonia", "code": "NC" },
                { "name": "New Zealand", "code": "NZ" },
                { "name": "Nicaragua", "code": "NI" },
                { "name": "Niger", "code": "NE" },
                { "name": "Nigeria", "code": "NG" },
                { "name": "Niue", "code": "NU" },
                { "name": "Norfolk Island", "code": "NF" },
                { "name": "Northern Mariana Islands", "code": "MP" },
                { "name": "Norway", "code": "NO" },
                { "name": "Oman", "code": "OM" },
                { "name": "Pakistan", "code": "PK" },
                { "name": "Palau", "code": "PW" },
                { "name": "Palestinian Territory, Occupied", "code": "PS" },
                { "name": "Panama", "code": "PA" },
                { "name": "Papua New Guinea", "code": "PG" },
                { "name": "Paraguay", "code": "PY" },
                { "name": "Peru", "code": "PE" },
                { "name": "Philippines", "code": "PH" },
                { "name": "Pitcairn", "code": "PN" },
                { "name": "Poland", "code": "PL" },
                { "name": "Portugal", "code": "PT" },
                { "name": "Puerto Rico", "code": "PR" },
                { "name": "Qatar", "code": "QA" },
                { "name": "Reunion", "code": "RE" },
                { "name": "Romania", "code": "RO" },
                { "name": "Russian Federation", "code": "RU" },
                { "name": "RWANDA", "code": "RW" },
                { "name": "Saint Helena", "code": "SH" },
                { "name": "Saint Kitts and Nevis", "code": "KN" },
                { "name": "Saint Lucia", "code": "LC" },
                { "name": "Saint Pierre and Miquelon", "code": "PM" },
                { "name": "Saint Vincent and the Grenadines", "code": "VC" },
                { "name": "Samoa", "code": "WS" },
                { "name": "San Marino", "code": "SM" },
                { "name": "Sao Tome and Principe", "code": "ST" },
                { "name": "Saudi Arabia", "code": "SA" },
                { "name": "Senegal", "code": "SN" },
                { "name": "Serbia", "code": "RS" },
                { "name": "Seychelles", "code": "SC" },
                { "name": "Sierra Leone", "code": "SL" },
                { "name": "Singapore", "code": "SG" },
                { "name": "Slovakia", "code": "SK" },
                { "name": "Slovenia", "code": "SI" },
                { "name": "Solomon Islands", "code": "SB" },
                { "name": "Somalia", "code": "SO" },
                { "name": "South Africa", "code": "ZA" },
                { "name": "South Georgia and the South Sandwich Islands", "code": "GS" },
                { "name": "Spain", "code": "ES" },
                { "name": "Sri Lanka", "code": "LK" },
                { "name": "Sudan", "code": "SD" },
                { "name": "Suriname", "code": "SR" },
                { "name": "Svalbard and Jan Mayen", "code": "SJ" },
                { "name": "Swaziland", "code": "SZ" },
                { "name": "Sweden", "code": "SE" },
                { "name": "Switzerland", "code": "CH" },
                { "name": "Syrian Arab Republic", "code": "SY" },
                { "name": "Taiwan, Province of China", "code": "TW" },
                { "name": "Tajikistan", "code": "TJ" },
                { "name": "Tanzania, United Republic of", "code": "TZ" },
                { "name": "Thailand", "code": "TH" },
                { "name": "Timor-Leste", "code": "TL" },
                { "name": "Togo", "code": "TG" },
                { "name": "Tokelau", "code": "TK" },
                { "name": "Tonga", "code": "TO" },
                { "name": "Trinidad and Tobago", "code": "TT" },
                { "name": "Tunisia", "code": "TN" },
                { "name": "Turkey", "code": "TR" },
                { "name": "Turkmenistan", "code": "TM" },
                { "name": "Turks and Caicos Islands", "code": "TC" },
                { "name": "Tuvalu", "code": "TV" },
                { "name": "Uganda", "code": "UG" },
                { "name": "Ukraine", "code": "UA" },
                { "name": "United Arab Emirates", "code": "AE" },
                { "name": "United Kingdom", "code": "GB" },
                { "name": "United States", "code": "US" },
                { "name": "United States Minor Outlying Islands", "code": "UM" },
                { "name": "Uruguay", "code": "UY" },
                { "name": "Uzbekistan", "code": "UZ" },
                { "name": "Vanuatu", "code": "VU" },
                { "name": "Venezuela", "code": "VE" },
                { "name": "Viet Nam", "code": "VN" },
                { "name": "Virgin Islands, British", "code": "VG" },
                { "name": "Virgin Islands, U.S.", "code": "VI" },
                { "name": "Wallis and Futuna", "code": "WF" },
                { "name": "Western Sahara", "code": "EH" },
                { "name": "Yemen", "code": "YE" },
                { "name": "Zambia", "code": "ZM" },
                { "name": "Zimbabwe", "code": "ZW" },
            ],
            sigError: null,
            signAture: null,
            isFormValid: false
        };
    },
    watch: {
        form: {
            handler(newVal, oldVal) {
                this.validateForm();
            },
            deep: true
        }
    },
    mounted() {
        if (this.userData.internal_agent_contract && this.userData.internal_agent_contract.additional_info) {
            this.form = this.userData.internal_agent_contract.additional_info
            this.signAture = this.userData.internal_agent_contract.get_question_sign
        }
        // if (this.page.auth.role === 'internal-agent') {
        //     const canvasElement = this.$refs.signature2Pad.$el.querySelector('canvas');
        //     canvasElement.width = 390; // Set the width you desire
        //     canvasElement.height = 100; // Set the height you desire
        // }
        // console.log('docuSignAuthCode', this.docuSignAuthCode);
    },

    methods: {
        validateForm() {
            this.isFormValid = (
                this.form.resident_country &&
                this.form.resident_your_home &&
                this.form.resident_city &&
                this.form.resident_state !== 'Choose'
            );
        },

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
                // Define an array of field names that are required
                const requiredFields = [
                    "resident_country", "resident_your_home", "resident_city", 'resident_state',
                ];
                if (this.page.auth.role === 'internal-agent') {
                    requiredFields.forEach(fieldName => {
                        if (this.form[fieldName] === null || this.form[fieldName] === "" || this.form[fieldName] === "Choose") {
                            this.firstStepErrors[fieldName] = [`This field is required.`];
                        }
                    });
                }
                // Check if there are any errors
                const hasErrors = Object.values(this.firstStepErrors).some(errors => errors.length > 0);
                if (this.page.auth.role === 'internal-agent') {
                    if (!hasErrors) {
                        this.$emit("additionalInfoData", this.form);
                        // :href="route('internal.agent.docusign.sign','accompanying_sign' )"
                        // const { isEmpty, data } = this.$refs.signature2Pad.saveSignature();
                        // if (!isEmpty || this.userData.internal_agent_contract.get_question_sign) {
                        //     this.sigError = null
                        //     this.$emit("additionalInfoData", { form: this.form, accompanying_sign: data });
                        //     // this.firstStepErrors = {}; // Clear the errors by assigning a new empty object
                        // } else {
                        //     this.sigError = 'Please provide a signature.';
                        // }
                    } else {
                        var element = document.getElementById("modal_main_id");
                        element.scrollIntoView();
                    }
                } else {
                    this.$emit("changeTab");
                }
            }

        },
        undo() {
            this.$refs.signature2Pad.undoSignature();
        },
        ChangeTabBack() {
            this.$emit("goback");
        },
    },
};
</script>