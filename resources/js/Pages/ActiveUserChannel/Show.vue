<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head } from "@inertiajs/vue3";
import { ref } from 'vue';
import { AxiosError } from "axios";
import axios from "axios";

Echo.join(`active-users`)
    .here((users) => {
        console.log('The total active users');
        console.log(users);
    })
    .joining((user) => {
        console.log(user.name);
    })
    .leaving((user) => {
        console.log(user.name);
    })
    .error((error) => {
        console.error(error);
    });

let status = ref('Waiting');

let updateStatus = async () => {
    try {
        const response = await axios.patch('/api/active-users', {
            status: status.value
        });

        // Handle successful response
        console.log('Status updated:', response.data);
    } catch (error) {
        // Handle error
        console.error('Error updating status:', error);
    }
};
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Active Users</h2>
        </template>
        <div class="py-14">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="px-4 sm:px-8 sm:rounded-lg">
                    <div class="text-4xl text-custom-sky font-bold mb-6">Active User Channel</div>
                    <hr class="mb-4" />

                    <div class="mb-4">
                        <select class="select-custom" v-model="status">
                            <option value="Waiting">Listening</option>
                            <option value="Ringing">Ringing</option>
                            <option value="Talking">Talking</option>
                        </select>
                    </div>

                    <PrimaryButton @click.prevent="updateStatus">Update</PrimaryButton>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
