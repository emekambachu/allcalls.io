<style>
.spnClassLocked{
    color:#fb4040;
}
</style>
<script setup>
import { ref, reactive, defineEmits, onMounted } from "vue";
import TextInput from "@/Components/TextInput.vue";
import { router } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
let props = defineProps({
    showModal: {
        type: Boolean,
    },

    ClientDetail: {
        type: Object,
    },
    states: Array,
});
let editScreen = ref(false);
let emit = defineEmits(["close"]);

let form = ref({});
let close = () => {
    editScreen.value = false;
    emit("close");
    form.value = {};
    props.ClientDetail = {};
};


let isLoading = ref(false);
let saveChanges = () => {
    isLoading.value = true;
    router.visit(`/clients/${form.id}`, {
        method: "PATCH",
        data: form,
    });
    isLoading.value = false;
};

let openEdit = () => {
    editScreen.value = true;
    form = JSON.parse(JSON.stringify(props.ClientDetail));
};
</script>

<template>
    <div v-if="!editScreen" class="relative w-full max-w-4xl max-h-full mx-auto">
        <div class="relative bg-white rounded-lg shadow-lg">
            <div class="flex items-start justify-between p-4 border-b rounded-t border-gray-600">
                <h3 class="text-xl font-small text-custom-blue">Client Details</h3>

                <button @click="close" type="button"
                    class="text-gray-500 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center"
                    data-modal-hide="defaultModal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-6">
                <div v-if="ClientDetail">
                    <span class="spnClassLocked" v-if = "ClientDetail.unlocked == 1">Client is locked</span>
                    <div class="flex justify-between items-center" v-if = "ClientDetail.unlocked != 1">
                        <h4 class="text-2xl font-small text-custom-sky mb-2">Personal Details</h4>

                        <PrimaryButton @click="openEdit"> Edit Client </PrimaryButton>
                    </div>

                    <div class="grid sm:grid-cols-1 md:grid-cols-2 gap-2 text-gray-700 mb-10" >
                        
                        <div>
                            <strong class="text-lg">First Name: </strong>
                            {{ ClientDetail.first_name }}
                        </div>
                        <div>
                            <strong class="text-lg">Last Name: </strong>
                            {{ ClientDetail.last_name }}
                        </div>
                        <div v-if = "ClientDetail.unlocked != 1">
                            <strong class="text-lg">Date of Birth: </strong>
                            {{ ClientDetail.dob || "N/A" }}
                        </div>
                        <div v-if = "ClientDetail.unlocked != 1"> 
                            <strong class="text-lg">Status: </strong>
                            <span v-if="ClientDetail.status == 'not_sold'"
                                class="bg-red-600 text-white text-xs px-2 py-1 rounded-2xl">Not Sold</span>
                            <span v-else-if="ClientDetail.status == 'sold'"
                                class="bg-green-600 text-white text-xs px-2 py-1 rounded-2xl">Sold</span>
                            <span v-else-if="ClientDetail.status"
                                class="bg-yellow-600 text-white text-xs px-2 py-1 rounded-2xl">{{ ClientDetail.status
                                }}</span>
                            <span v-else>-</span>
                        </div>
                    </div>

                    <h4 class="text-2xl font-small text-custom-sky mb-2">Contact Information</h4>
                    <div class="grid sm:grid-cols-1 md:grid-cols-2 gap-2 text-gray-700 mb-10">
                        <div v-if = "ClientDetail.unlocked != 1">
                            <strong class="text-lg">Phone: </strong>
                            {{ ClientDetail.phone }}
                        </div>
                        <div v-if = "ClientDetail.unlocked != 1">
                            <strong class="text-lg">Email: </strong>
                            {{ ClientDetail.email || "N/A" }}
                        </div>
                        <div v-if = "ClientDetail.unlocked != 1">
                            <strong class="text-lg">Address: </strong>
                            {{ ClientDetail.address || "N/A" }}
                        </div>
                        <div>
                            <strong class="text-lg">State: </strong>
                            {{ ClientDetail.state || "N/A" }}
                        </div>
                        <div v-if = "ClientDetail.unlocked != 1">
                            <strong class="text-lg">Zip Code: </strong>
                            {{ ClientDetail.zipCode || "N/A" }}
                        </div>
                    </div>


                </div>

                <div class="flex justify-end">
                    <PrimaryButton @click="close"> Close </PrimaryButton>
                </div>
            </div>
        </div>
    </div>

    <div v-else class="relative w-full max-w-4xl max-h-full mx-auto">
        <div class="relative bg-white border border-gray-300 rounded-lg shadow-lg">
            <div class="flex items-start justify-between p-4 border-b border-gray-300 rounded-t">
                <h3 class="text-xl font-small text-gray-700">Edit Client Details</h3>
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
            <form v-if="form" class="p-6">
                <h4 class="text-2xl font-small text-gray-700 mb-2">Personal Details</h4>
                <div class="grid sm:grid-cols-1 md:grid-cols-2 gap-2 text-gray-700 mb-10">
                    <div>
                        <label class="text-lg">First Name:</label>
                        <TextInput type="text" name="first_name" id="first_name" class="w-full" placeholder="John" required
                            v-model="form.first_name" />
                    </div>
                    <div>
                        <label class="text-lg">Last Name:</label>
                        <TextInput type="text" name="last_name" id="last_name" class="w-full" placeholder="Doe" required
                            v-model="form.last_name" />
                    </div>
                    <div>
                        <label class="text-lg">Date of Birth:</label>
                        <TextInput type="text" name="dob" id="dob" class="w-full" required v-model="form.dob" />
                    </div>
                </div>

                <h4 class="text-2xl font-small text-custom-sky mb-2">Contact Information</h4>
                <div class="grid sm:grid-cols-1 md:grid-cols-2 gap-2 text-gray-700 mb-10">
                    <div>
                        <label class="text-lg">Phone:</label>
                        <TextInput type="text" name="phone" id="phone" class="w-full" required v-model="form.phone" />
                    </div>
                    <div>
                        <label class="text-lg">Email:</label>
                        <TextInput type="email" name="email" id="email" class="w-full" placeholder="john@example.com"
                            required v-model="form.email" />
                    </div>
                    <div>
                        <label class="text-lg">Address:</label>
                        <TextInput type="text" name="address" id="address" class="w-full" placeholder="10001" required
                            v-model="form.address" />
                    </div>
                    <div class="w-full">
                        <label for="state" class="block mb-2 text-sm font-medium text-gray-700">State</label>
                        <select name="state" id="state" class="select-custom" v-model="form.state" required>
                            <option v-for="(state, index) in states" :key="index" :value="state.full_name">{{
                                state.full_name }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-lg">Zip Code:</label>
                        <TextInput type="text" name="zip" id="zip" class="w-full" placeholder="10001" required
                            v-model="form.zipCode" />
                    </div>
                </div>

                <h4 class="text-2xl font-small text-custom-sky mb-2">Other Information</h4>
                <div class="grid sm:grid-cols-1 md:grid-cols-2 gap-2 text-gray-700 mb-10">
                    <div>
                        <label class="text-lg">Status:</label>
                        <select name="status" id="status" class="select-custom" v-model="form.status" required>
                            <option selected="" disabled="" value="">Select status</option>
                            <option value="not_sold">Not Sold</option>
                            <option value="sold">Sold</option>
                            <option value="follow_up_needed">Follow Up Needed</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex justify-end mt-6">
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
