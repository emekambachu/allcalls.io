<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed } from "vue";
import GuestTextInput from "@/Components/GuestTextInput.vue";
import GuestInputLabel from "@/Components/GuestInputLabel.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router, useForm, usePage } from "@inertiajs/vue3";

let emits = defineEmits()
let props = defineProps({

});
let invitesModal = ref(true)
let isLoading = ref(false)
let firstStepErrors = ref({})
let form = ref({
    email: "",
    level: "-- Select an option --",
});
console.log('');
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
        if (form.value.level && form.value.level !== "-- Select an option --") {
            emits('inviteAgent', form.value)
        } else {
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
    <AuthenticatedLayout>
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
                            <Link :href="route('logout')" method="post" as="button"
                                    class="underline text-sm text-gray-600 mr-5 mt-2  dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                Logout </Link>
                        </div>
                        <div class="px-12 py-2">
                            <h1 class="text-gray-800 text-2xl font-bold">Low Balance</h1>
                            <br>



                            <div class="mb-3">
                                <label for="Email" class="block mb-2 text-sm font-black text-gray-900 ">Email<span
                                        class="text-red-500">*</span></label>
                                <input type="email" v-model="form.email" id="default-input"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                                <div v-if="firstStepErrors.email" class="text-red-500" v-text="firstStepErrors.email[0]">
                                </div>
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
                                <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 ">Agent
                                    Level<span class="text-red-500">*</span></label>
                                <select v-model="form.level" id="countries"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white">
                                    <option disabled selected>-- Select an option -- </option>
                                    <option>hello </option>
                                </select>
                                <div v-if="firstStepErrors.level" class="text-red-500" v-text="firstStepErrors.level[0]">
                                </div>
                            </div>

                            <div class=" mb-3 mt-2">
                                <div class="flex justify-end">

                                    <div class="mt-4">
                                        <button type="button" @click="inviteAgent" :class="{ 'opacity-25': isLoading }"
                                            :disabled="isLoading"
                                            class="button-custom px-3 py-2 rounded-md flex items-center">
                                            <global-spinner :spinner="isLoading" /> Invite
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>
                </div>
            </div>
        </Transition>
    </AuthenticatedLayout>
</template>
