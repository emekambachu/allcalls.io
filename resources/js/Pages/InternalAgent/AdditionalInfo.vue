
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
                resident_country: 'USA',
                resident_your_home: null,
                resident_city: null,
                resident_state: 'Choose',
                resident_maiden_name: null,
            },
            countries: [
                { "name": "United States of America", "code": "USA" },
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