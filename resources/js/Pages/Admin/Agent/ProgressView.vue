<script setup>
import { ref, reactive, defineEmits, computed, onMounted, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { toaster } from "@/helper.js";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
let page = usePage();
if (page.props.flash.message) {
    toaster("success", page.props.flash.message);
}
let props = defineProps({
    progressModal: {
        type: Boolean,
    },
    statuses: Array,
    userData: Object,
    isLoading:Boolean,
});

let emit = defineEmits(["close"]);

let close = () => {
    ProgressError.value = false
    emit("close");
};

let form = ref({
    status: 'Select Progress'
});
let ProgressError = ref(false)
let updateProgress = () => {
    if (form.value.status != 'Select Progress') {
        ProgressError.value = false
        emit("updateProgress", { progress: form.value.status, user_id: props.userData.id });
    } else {
        ProgressError.value = true
    }

}
if (props.userData.progress != null) {
    form.value.status = props.userData.progress
}
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
                <h3 class="text-xl font-small text-gray-700">Progress</h3>
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
            <div class="bg-white p-4  m-5  ">
                <label for="roles" class="block mb-2 text-sm font-medium text-gray-700">Select Progress</label>
                <select v-model="form.status" id='roles' name="roles" class="select-custom" required>
                    <option disabled="" value="Select Progress">{{ form.status }}</option>
                    <option v-for="status in statuses" :value="status">{{ status }}</option>
                </select>
                <div v-if="ProgressError" class="text-red-500">Please Select the value. </div>
            </div>
            <div class="flex justify-end p-5">
                <PrimaryButton type="submit" :class="{ 'opacity-25':  isLoading }" :disabled=" isLoading" @click.prevent="updateProgress">
                    <global-spinner :spinner="isLoading" /> Update
                </PrimaryButton>
                <SecondaryButton @click.prevent="close" type="button" class="ml-3">
                    Cancel
                </SecondaryButton>
            </div>

        </div>
    </div>
</template>
