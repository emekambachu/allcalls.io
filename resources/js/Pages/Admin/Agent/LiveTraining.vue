<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed, onBeforeMount, onUnmounted, inject } from "vue";
import { router, usePage } from "@inertiajs/vue3";

import { toaster } from "@/helper.js";
import PrimaryButton from "@/Components/PrimaryButton.vue";
let page = usePage();
if (page.props.flash.message) {
    toaster("success", page.props.flash.message);
}
const countryList = inject('countryList');
let props = defineProps({
    userDetail: {
        type: Object,
    },
    currentPage: Number,
    route: String,

});
let emit = defineEmits(["close"]);
let close = () => {
    emit("close");
};
let form = ref({
    agent_access_status: props.userDetail.agent_access_status ? props.userDetail.agent_access_status :  'Select Status',
    agent_id: props.userDetail.id
})
let firstStepErrors = ref({})
let isLoading = ref(false)
let saveChanges = async () => {
    isLoading.value = true
    firstStepErrors.value.agent_access_status = ''
    if (!form.value.agent_access_status || form.value.agent_access_status === "Select Status") {
        firstStepErrors.value.agent_access_status = [`This  field is required.`];
        isLoading.value = false
    }
    const hasErrors = Object.values(firstStepErrors.value).some(
        (errors) => errors.length > 0
    );
    if (!hasErrors) {
        await axios.post("/admin/update-training-status", form.value)
            .then((response) => {
                console.log('res', response);
                isLoading.value = false
                toaster('success', response.data.message)
                emit('updateUserData', response.data.user)
            }).catch((error) => {
                console.log('error', error);
                toaster('error', error.message)
                isLoading.value = false
            })
    }

}

</script>
<style>
.chemical-formula {
    display: inline-block;
    padding: 5px 10px;
    background-color: #0b4c73;
    color: white;
    font-size: 16px;
    border-radius: 50px;
    border: none;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    outline: none;
}

.active-tab {
    border-bottom: 2px solid #0b4c73;

}

.slide-enter-active,
.slide-leave-active {
    /* transition: all 0.2s ease; */
}

.slide-enter,
.slide-leave-to {
    opacity: 0;
    transform: translateX(-20px);
}

.drop_down_main {
    background: #d7d7d7;
    height: 39.5px;
    margin-top: 5px;
    border-radius: 5px 0px 0px 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0px 5px;
}



.country-select-container {
    display: flex;
}

.country-code,
.country-dropdown {
    margin-right: 10px;
}
</style>
<template>
    <!-- This is the overlay -->

    <div class="relative w-full max-w-4xl max-h-full mx-auto">
        <div class="relative bg-white border border-gray-300 rounded-lg shadow-lg">
            <div class="flex items-start justify-between p-4 border-b border-gray-300 rounded-t">
                <h3 class="text-xl font-small text-gray-700">Edit Training Status</h3>
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

            <form @submit.prevent="saveChanges" class="p-6">


                <div>
                    <div>
                        <div class="mb-4">
                            <div class="mb-4 rounded-lg bg-blue-50 p-4 text-sm text-blue-800" role="alert">When set to live, $200 will be added to the agent's account.</div>

                            <label for="roles" class="block mb-2 text-sm font-medium text-gray-700">Select Role</label>
                            <select v-model="form.agent_access_status" id='agent_access_status' name="agent_access_status"
                                class="select-custom" required>
                                <option disabled value="Select Status">Select Status</option>
                                <option value="Training">Training</option>
                                <option value="Live">Live</option>
                            </select>
                        </div>
                        <div v-if="firstStepErrors">
                            <div v-if="firstStepErrors.agent_access_status" class="text-red-500"
                                v-text="firstStepErrors.agent_access_status[0]"></div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-6">
                    <PrimaryButton type="submit" :disabled="isLoading" @click.prevent="saveChanges">
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
