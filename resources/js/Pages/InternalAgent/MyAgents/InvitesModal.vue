<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed } from "vue";
import GuestTextInput from "@/Components/GuestTextInput.vue";
import GuestInputLabel from "@/Components/GuestInputLabel.vue";
let emits = defineEmits()
let props = defineProps({
    invitesModal: Boolean,
    isLoading: Boolean,
    firstStepErrors: Object,
    reIniteAgent: Boolean,
    agentLevels: Array,
});
let form = ref({
    email: "",
    level: "-- Select an option --",
});
let validateEmail = (email) => {
    return /\S+@\S+\.\S+/.test(email); // Simple regex for email validation
};
let uiEmailValidation = ref({
    isValid: false,
});
let inviteAgent = () => {
    props.firstStepErrors.email = [``];
    props.firstStepErrors.level = [``];
    if (validateEmail(form.value.email)) {
        if(form.value.level && form.value.level !== "-- Select an option --"){
            emits('inviteAgent', form.value)
        }else{
            props.firstStepErrors.level = [`Please select the level.`];
        }
    } else {
        props.firstStepErrors.email = [`Please enter valid email address.`];
    }
}
let ReinviteAgent = () => {
    emits('ReinviteAgent')
}
let close = () => {
    emits('close')
}
</script>
<style scoped>
.active\:bg-gray-900:active {
    color: white;
}

.hover\:drop-shadow-2xl:hover {
    background-color: white;
    color: black;
}

.button-custom {
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    font-weight: 600;
    border-width: 1px;
    align-items: center;
    display: inline-flex;
    border-color: rgb(107 114 128 / var(--tw-border-opacity));
    background-color: #03243d;
    color: #3cfa7a;
    cursor: pointer;
}

.button-custom:hover {
    transition-duration: 150ms;
    background-color: white;
    color: black;
}

.button-custom-back {
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    font-weight: 600;
    border-width: 1px;
    align-items: center;
    display: inline-flex;
    border-color: rgb(107 114 128 / var(--tw-border-opacity));
}

.button-custom-back:hover {
    background-color: #03243d;
    color: #3cfa7a;
    transition-duration: 150ms;
}

.blurred-overlay {
    backdrop-filter: blur(10px);
    /* Adjust the blur intensity as needed */
    background-color: rgba(0, 0, 0, 0.6);
    /* Adjust the background color and opacity as needed */
}
</style>
<template>
    <Transition name="modal" enter-active-class="transition ease-out duration-300 transform"
        enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        enter-to-class="opacity-100 translate-y-0 sm:scale-100"
        leave-active-class="transition ease-in duration-200 transform"
        leave-from-class="opacity-100 translate-y-0 sm:scale-100"
        leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
        <div id="defaultModal" v-if="invitesModal" tabindex="-1"
            class="flex items-center justify-center fixed inset-0 z-50 w-full h-full overflow-x-hidden overflow-y-auto max-h-full mx-4 sm:mx-0">
            <div class="fixed inset-0 bg-black opacity-90 blurred-overlay"></div>

            <!-- This is the overlay -->
            <div class="relative w-full max-w-xl max-h-full mx-auto">
                <div class="relative bg-white rounded-lg shadow-lg transition-all">
                    <div class="flex justify-end">
                        <button @click="close" type="button"
                            class="text-gray-400 bg-transparent mr-2 mt-2 hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center"
                            data-modal-hide="defaultModal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div v-if="!reIniteAgent" class="px-12 py-2">
                        <h1 class="text-gray-800 text-2xl font-bold">Invite Agent</h1>
                        <br>
                        <!-- <div class="mt-4">
                            <GuestInputLabel for="email" value="Email" />

                            <GuestTextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                                pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}" />
                            <div v-if="uiEmailValidation.isValid" class="text-red-500">
                                Please enter valid email address.
                            </div>
                            <div v-if="firstStepErrors.email" class="text-red-500" v-text="firstStepErrors.email[0]"></div>
                        </div>

                        <br> -->


                        <div class="mb-3">
                            <label for="Email" class="block mb-2 text-sm font-black text-gray-900 ">Email<span
                                    class="text-red-500">*</span></label>
                            <input type="email" v-model="form.email" id="default-input"
                                
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                            <div v-if="firstStepErrors.email" class="text-red-500" v-text="firstStepErrors.email[0]"></div>
                            <!-- <div v-if="uiEmailValidation.isValid" class="text-red-500">
                                Please enter valid email address.
                            </div> -->
                        </div>
                        <!-- <div class="mb-3">
                            <label for="Upline ID" class="block mb-2 text-sm font-black text-gray-900 ">Upline ID<span
                                    class="text-red-500">*</span></label>
                            <input type="text"  v-model="form.upline_id" id="default-input"
                            
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                            <div v-if="firstStepErrors.upline_id" class="text-red-500" v-text="firstStepErrors.upline_id[0]"></div>
                            
                        </div> -->

                        <div>
                            <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 ">Agent Level<span
                                    class="text-red-500">*</span></label>
                            <select v-model="form.level" id="countries"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                                <option disabled selected>-- Select an option -- </option>
                                <option v-for="level in agentLevels" :value="level.id">{{ level.name }} </option>
                            </select>
                            <div v-if="firstStepErrors.level" class="text-red-500" v-text="firstStepErrors.level[0]">
                            </div>
                        </div>

                        <div class=" mb-3 mt-2">
                            <div class="flex justify-end">

                                <div class="mt-4">
                                    <button type="button" @click="inviteAgent" :class="{ 'opacity-25': isLoading }"
                                        :disabled="isLoading" class="button-custom px-3 py-2 rounded-md flex items-center">
                                        <global-spinner :spinner="isLoading" /> Invite
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="px-12 py-2">
                        <h1 class="text-gray-800 text-2xl font-bold">Reinvite Agent</h1>
                        <div class="mt-2 mb-2 text-sm">
                            Are you sure to reinvite this agent?
                        </div>
                        <div class=" mb-3">
                            <div class="flex justify-end">
                                <div class="mt-4">
                                    <button @click="close" class="button-custom-back mr-3 px-3 py-2 rounded-md">
                                        No</button>
                                </div>
                                <div class="mt-4">
                                    <button type="button" @click="ReinviteAgent" :class="{ 'opacity-25': isLoading }"
                                        :disabled="isLoading" class="button-custom px-3 py-2 rounded-md flex items-center">
                                        <global-spinner :spinner="isLoading" /> yes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </Transition>
</template>
