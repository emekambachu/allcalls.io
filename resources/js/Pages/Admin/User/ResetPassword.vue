<script setup>
    import { ref, reactive, defineEmits, onMounted, watch, computed, onBeforeMount, onUnmounted, inject } from "vue";
    import TextInput from "@/Components/TextInput.vue";
    import Multiselect from "@vueform/multiselect";
    import InputError from "@/Components/InputError.vue";
    import { router, usePage } from "@inertiajs/vue3";
    import GuestTextInput from "@/Components/GuestTextInput.vue";
    import GuestInputLabel from "@/Components/GuestInputLabel.vue";
    import InputLabel from '@/Components/InputLabel.vue';
    import { Head, Link, useForm } from "@inertiajs/vue3";
    import { toaster } from "@/helper.js";
    import PrimaryButton from "@/Components/PrimaryButton.vue";
    import axios from "axios";
    let page = usePage();
    if (page.props.flash.message) {
        toaster("success", page.props.flash.message);
    }

    let props = defineProps({
        showModal: {
            type: Boolean,
        },

        userId:Number,

        currentPage: Number,
    });
    let emit = defineEmits(["close"]);

    let close = () => {
        emit("close");
    };

    let firstStepErrors = ref({});

    let uiEmailValidation = ref({
        isValid: false,
    });

    let balanceChange = ref(false);

    let form = useForm({});

    const selectedTypeIds = ref([]);

    let validatePassword = (password) => {
        firstStepErrors.value.password = [];
        firstStepErrors.value.password_confirmation = [];
        if (password !== form.password_confirmation) {
            isLoading.value = false
            firstStepErrors.value.password = ["Password doesn't match check again"];
            firstStepErrors.value.password_confirmation = ["Password doesn't match check again"];
            return false
        }else{
            return true
        }
    }

    const isLoading = ref(false);
    let saveChanges = () => {
        isLoading.value = true;
        if (validatePassword(form.password)) {
                    return axios
                        .post(`/admin/customers/reset-password/${props.userId}`, form)
                        .then((response) => {
                            toaster("success", response.data.message);
                            close();
                            if (props.currentPage != 1) {
                                router.visit(`/admin/customers?page=${props.currentPage}`);
                            } else {
                                router.visit(`/admin/customers`);
                            }
                            isLoading.value = false;
                            balanceChange.value = false;
                        })
                        .catch((error) => {
                            if (error.response.status === 400) {
                                firstStepErrors.value = error.response.data.errors;
                                isLoading.value = false;
                            } else {
                                toaster("error", "Something went wrong");
                            }
                        });

        } else {
            isLoading.value = false;
        }
    };

</script>
<template>
    <!-- This is the overlay -->

    <div class="relative w-full max-w-4xl max-h-full mx-auto">
        <div class="relative bg-white border border-gray-300 rounded-lg shadow-lg">
            <div class="flex items-start justify-between p-4 border-b border-gray-300 rounded-t">
                <h3 class="text-xl font-small text-gray-700">Reset Password</h3>
                <button @click="close" type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center"
                        data-modal-hide="defaultModal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <form @submit.prevent="saveChanges" class="p-6">
                <div>
                    <div class="mt-4">
                        <GuestInputLabel for="password" value="New Password*"/>

                        <GuestTextInput id="password" ref="passwordInput" v-model="form.password" type="password" class="mt-1 block w-full"
                                        autocomplete="new-password" />

                        <div v-if="firstStepErrors.password" class="text-red-500" v-text="firstStepErrors.password[0]"></div>
                    </div>

                    <div class="mt-4">
                        <GuestInputLabel for="password_confirmation" value="Confirm Password*" />

                        <GuestTextInput id="password_confirmation" v-model="form.password_confirmation" type="password"
                                        class="mt-1 block w-full" autocomplete="new-password" />

                        <div v-if="firstStepErrors.password_confirmation" class="text-red-500"
                             v-text="firstStepErrors.password_confirmation[0]"></div>

                    </div>
                </div>

                <div class="flex justify-end mt-10">
                    <PrimaryButton type="submit" @click.prevent="saveChanges">
                        <global-spinner :spinner="isLoading" /> Save Changes
                    </PrimaryButton>

                    <PrimaryButton @click.prevent="close" type="button" class="ml-3">
                        Close
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </div>
</template>
