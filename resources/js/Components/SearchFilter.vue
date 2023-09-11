<script setup>
import TextInput from "@/Components/TextInput.vue";
import { Link, useForm, usePage, router } from "@inertiajs/vue3";
import { ref, reactive, computed, watch, defineEmits, onMounted } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
let props = defineProps({
    route: String,
    requestData: Array,
});

let formData = ref({
    name: props.requestData.name || '',
    email: props.requestData.email || '',
    phone: props.requestData.phone || '',
    first_six_card_no: props.requestData.first_six_card_no || '',
    last_four_card_no: props.requestData.last_four_card_no || '',
});
let isLoading = ref(false)
let isLoadingReset = ref(false)
let fetchData = (e) => {

    isLoading.value = true
    try {
        const queryParams = {
            name: formData.value.name,
            email: formData.value.email,
            phone: formData.value.phone,
            first_six_card_no: formData.value.first_six_card_no,
            last_four_card_no: formData.value.last_four_card_no,
        };
        router.visit(props.route, {
            data: queryParams
        })
        isLoading.value = false
    } catch (error) {

    }
}
let ClearFilter = () => {

    isLoadingReset.value = true
    try {
        const baseUrl = props.route.split('?')[0];
        router.visit(baseUrl)
        isLoadingReset.value = false
    } catch (error) {

    }
}
const emit = defineEmits();
onMounted(() => {
    // emit('update-form-data', {
    //     name: formData.value.name,
    //     email: formData.value.email,
    //     phone: formData.value.phone,
    //     first_six_card_no: formData.value.first_six_card_no,
    //     last_four_card_no: formData.value.last_four_card_no,
    // });
})
</script>
<style scoped>
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
</style>
<template>
    <div class="grid grid-cols-4 gap-4 px-12 mb-3 mx-3">

        <div>
            <InputLabel for="name" value="name" />

            <TextInput id="name" type="text" placeholder="Name" class="mt-1 block w-full" v-model="formData.name" required
                autofocus />

        </div>
        <div>
            <InputLabel for="email" value="email" />

            <TextInput id="email" type="text" placeholder="Email" class="mt-1 block w-full" v-model="formData.email"
                required />

        </div>
        <div>
            <InputLabel for="phone" value="phone" />

            <TextInput id="phone" type="number" placeholder="Phone Number" class="mt-1 block w-full"
                v-model="formData.phone" required />

        </div>
        <div>
            <InputLabel for="card" value="card" />

            <TextInput id="card" type="number" placeholder="First 6 digit  of  card number" class="mt-1 block w-full"
                v-model="formData.first_six_card_no"   required />

        </div>
        <div>
            <InputLabel for="card" value="card" />

            <TextInput id="card" type="number" placeholder="Last 4 digit of  card number" class="mt-1 block w-full"
                v-model="formData.last_four_card_no"  required />

        </div>
        <div>
            <PrimaryButton type="button" class="ml-2" @click.prevent="fetchData">
                <global-spinner :spinner="isLoading" /> Filter
            </PrimaryButton>
            <button @click.prevent="ClearFilter()" type="button" class="button-custom-back px-4 py-3 rounded-md ml-2">
                <global-spinner :spinner="isLoadingReset" /> Reset
            </button>
        </div>

    </div>
</template>