<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

Echo.private('User.Status.Online.1')
    .listen('OrderShipmentStatusUpdated', (e) => {
        console.log(e.order.name);
    });
</script>

<template>
    <Head title="Pusher Test" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Pusher Test</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">Pusher Test!</div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
