<script setup>
import { ref, reactive, defineEmits, computed, onMounted, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { toaster } from "@/helper.js";
import PrimaryButton from "@/Components/PrimaryButton.vue";
let page = usePage();
if (page.props.flash.message) {
    toaster("success", page.props.flash.message);
}
let props = defineProps({
    viewModalpdf: {
        type: Boolean,
    },
    userData: Object,
});
console.log('userData', props.userData);
let bankingInfotUrl = ref(null)
// if (props.userData.internal_agent_contract && props.userData.internal_agent_contract.banking_info) {
//     bankingInfotUrl.value = props.userData.internal_agent_contract.banking_info.url
// }
let emit = defineEmits(["close"]);

let close = () => {
    emit("close");
};

let form = ref({

});

// form = JSON.parse(JSON.stringify(props.userDetail));
let getFormattedText = (name) => {
    const parts = name.split('_');
    return parts[parts.length - 1].toUpperCase();
}


let formatData = (val) => {
    const withSpaces = val.replace(/_/g, ' ');

    // Capitalize the first letter of each word
    const formatted = withSpaces
        .split(' ')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');

    return formatted;

}
</script>

<template>
    <!-- This is the overlay -->

    <div class="relative w-full max-w-4xl max-h-full mx-auto">
        <div class="relative bg-white border border-gray-300 rounded-lg shadow-lg">
            <div class="flex items-start justify-between p-4 border-b border-gray-300 rounded-t">
                <h3 class="text-xl font-small text-gray-700">Contracting</h3>
                <button @click="close" type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center"
                    data-modal-hide="defaultModal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>


            <div class="bg-white p-4 shadow-lg rounded-lg m-5  ">
                <div class="bg-white  m-5 flex items-center">
                    <div class=" text-lg font-semibold mr-4">
                        1. Full Contract
                    </div>
                    <div>
                        <a target="_blank" :href="userData.internal_agent_contract?.get_contract_sign?.sign_url">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                class="w-6 h-6 text-blue-600">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                        </a>
                    </div>

                </div>
                <!-- <div class="bg-white  m-5 flex items-center">
                    <div class=" text-lg font-semibold mr-4">
                        2. Signature Authorization
                    </div>
                    <div>
                        <a :href="route('admin.agent.signature.authorization.pdf', userData.id)">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                class="w-6 h-6 text-blue-600">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                        </a>
                    </div>
                </div> -->

                
                <div class="bg-white  m-5 flex items-center">
                    <div class=" text-lg font-semibold mr-4">
                        2. Driver License
                    </div>
                    <div>
                        <a target="_blank" :href="userData.internal_agent_contract?.driver_license?.url"
                            :disabled="!userData.internal_agent_contract?.driver_license?.url"
                            :class="{ 'opacity-25': !userData.internal_agent_contract?.driver_license?.url }">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                class="w-6 h-6 text-blue-600">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="bg-white  m-5 flex items-center">
                    <div class=" text-lg font-semibold mr-4">
                        3. AML Course
                    </div>
                    <div>
                        <a target="_blank" :href="userData.internal_agent_contract?.aml_course?.url"
                            :disabled="!userData.internal_agent_contract?.aml_course?.url"
                            :class="{ 'opacity-25': !userData.internal_agent_contract?.aml_course?.url }">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                class="w-6 h-6 text-blue-600">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="bg-white  m-5 flex items-center">
                    <div class=" text-lg font-semibold mr-4">
                        4. Errors and Omissions Insurances
                    </div>
                    <div>
                        <a target="_blank" :href="userData.internal_agent_contract?.error_and_emission?.url"
                            :disabled="!userData.internal_agent_contract?.error_and_emission?.url"
                            :class="{ 'opacity-25': !userData.internal_agent_contract?.error_and_emission?.url }">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                class="w-6 h-6 text-blue-600">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="bg-white  m-5 flex items-center">
                    <div class=" text-lg font-semibold mr-4">
                        5. Resident License
                    </div>
                    <div>
                        <a target="_blank" :href="userData.internal_agent_contract?.resident_license?.url"
                            :disabled="!userData.internal_agent_contract?.resident_license?.url"
                            :class="{ 'opacity-25': !userData.internal_agent_contract?.resident_license?.url }">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                class="w-6 h-6 text-blue-600">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="bg-white  m-5 flex items-center">
                    <div class=" text-lg font-semibold mr-4">
                        6. Banking Information
                    </div>
                    <div>
                        <a target="_blank" :href="userData.internal_agent_contract?.banking_info?.url"
                            :disabled="!userData.internal_agent_contract?.banking_info?.url"
                            :class="{ 'opacity-25': !userData.internal_agent_contract?.banking_info?.url }">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                class="w-6 h-6 text-blue-600">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                        </a>
                    </div>
                </div>
           
                <div class="text-center my-5">
                    <div class="flex items-center justify-center my-3">
                        <div class="flex-1 h-0.5 bg-black mr-3"></div>
                        <div class="text-lg font-bold">Legal Questions Explanation</div>
                        <div class="flex-1 h-0.5 bg-black ml-3"></div>
                    </div>
                </div>

                <div v-for="question in userData.internal_agent_contract.legal_question" class="bg-white  m-5 ">
                    <div>
                        <div v-if="question.value === 'YES'" class=" text-lg font-semibold mr-4 flex">
                            <span class="mr-5">{{ getFormattedText(question.name) }}. Explantion</span>
                            <a :href="route('admin.agent.legal.question.pdf', [question.id, userData.id, getFormattedText(question.name)])"
                                :disabled="!userData.internal_agent_contract?.banking_info?.url"
                                :class="{ 'opacity-25': !userData.internal_agent_contract?.banking_info?.url }">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" class="w-6 h-6 text-blue-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                            </a>
                        </div>

                        <!-- <div>
                            <a target="_blank" :href="userData.internal_agent_contract?.banking_info?.url"
                                :disabled="!userData.internal_agent_contract?.banking_info?.url"
                                :class="{ 'opacity-25': !userData.internal_agent_contract?.banking_info?.url }">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" class="w-6 h-6 text-blue-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                            </a>
                        </div> -->
                    </div>

                </div>
            </div>



            <div class="flex justify-end p-5">
                <PrimaryButton @click.prevent="close" type="button" class="ml-3">
                    Close
                </PrimaryButton>
            </div>

        </div>
    </div>
</template>
