<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed, onUnmounted, inject } from "vue";
import Multiselect from "@vueform/multiselect";
import InputError from "@/Components/InputError.vue";
import { router, usePage } from "@inertiajs/vue3";
import GuestTextInput from "@/Components/GuestTextInput.vue";
import GuestInputLabel from "@/Components/GuestInputLabel.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { toaster } from "@/helper.js";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import axios from "axios";

let page = usePage();
if (page.props.flash.message) {
    toaster("success", page.props.flash.message);
}
const countryList = inject('countryList');
let props = defineProps({
    addAgentLevelModal: {
        type: Boolean,
    },
    modal_type: String,
    levelData:Object,
});
let form = ref({
    name:'',
});
if(props.modal_type === 2){
form.value.name = props.levelData.name

}
let firstStepErrors = ref({});
let AddLevel = () => {
    axios.post('/admin/internal-agent-level/store', form.value).then((res)=>{
        console.log('res', res.data);
        toaster("success", res.data.message);
        close()
        router.visit('/admin/internal-agent-levels')
    }).catch((error)=>{
       if(error.response){
        firstStepErrors.value = error.response.data.errors

       }else{
        toaster("error", error.message);
       }
    })
};
let updateLevel = () => {
    
}
let emit = defineEmits(["close"]);
let close = () => {
  emit("close");
};



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
    <div>
        <!-- <Transition
        name="modal"
        enter-active-class="transition ease-out duration-300 transform"
        enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        enter-to-class="opacity-100 translate-y-0 sm:scale-100"
        leave-active-class="transition ease-in duration-200 transform"
        leave-from-class="opacity-100 translate-y-0 sm:scale-100"
        leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
      >
        <div
          id="defaultModal"
          tabindex="-1"
          class="flex items-center justify-center fixed inset-0 z-50 w-full h-full overflow-x-hidden overflow-y-auto max-h-full mx-4 sm:mx-0"
        >
          <div class="fixed inset-0 bg-black opacity-60"></div> -->
        <!-- This is the overlay -->

        <div class="relative w-full max-w-4xl max-h-full mx-auto">
            <div class="relative bg-white border border-gray-300 rounded-lg shadow-lg">
                <div class="flex items-start justify-between p-4 border-b border-gray-300 rounded-t">
                    <h3 class="text-xl font-small text-gray-700">Add Agent</h3>
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



                <div class="pt-6">
                    <div class="px-12">
                        <div class="mt-4">
                            <div>
                                <GuestInputLabel for="Name" value="Name" />
                                <GuestTextInput id="first_name" type="text" class="mt-1 block w-full" v-model="form.name"
                                    minlength="2" required pattern="[A-Za-z]{1,32}"
                                    />
                                <!-- <InputError class="mt-2" :message="form.errors.first_name" /> -->
                                <div v-if="firstStepErrors.name" class="text-red-500"
                                    v-text="firstStepErrors.name[0]"></div>
                            </div>

                            <div class="flex justify-end my-6">
                                <PrimaryButton v-if="modal_type == 1" type="button" @click="AddLevel" 
                                    :class="{ 'opacity-25': areAllArraysEmpty || isLoading }"
                                    :disabled="areAllArraysEmpty || isLoading">
                                    <global-spinner :spinner="isLoading" /> Add Level
                                </PrimaryButton>
                                <PrimaryButton v-else-if="modal_type == 2" type="button" @click="saveChanges" 
                                    :class="{ 'opacity-25': areAllArraysEmpty || isLoading }"
                                    :disabled="areAllArraysEmpty || isLoading">
                                    <global-spinner :spinner="isLoading" /> Update Level
                                </PrimaryButton>

                                <PrimaryButton @click.prevent="close" type="button" class="ml-3">
                                    Close
                                </PrimaryButton>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- </div>
      </Transition> -->
    </div>
</template>
