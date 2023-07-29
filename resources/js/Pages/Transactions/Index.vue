<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";

defineProps({
    transactions: {
        type: Object,
    },
});

let formatDate = date => {
    if (!date) {
        return '';
    }

    const dateObj = new Date(date);

    const formattedDate = dateObj.toLocaleDateString("en-US", {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "numeric",
        minute: "numeric",
    });

    return formattedDate;
}
</script>

<template>
    <Head title="Transactions" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Transactions
            </h2>
        </template>

        <div class="pt-14">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="px-4 sm:px-8  sm:rounded-lg">
                    <div class="text-4xl text-custom-sky font-bold mb-6">Transactions</div>
                    <hr class="mb-4">
                </div>
            </div>
        </div>

        <section v-if="transactions.length" class="p-3 ">
            <div class="mx-auto max-w-screen-xl sm:px-12">
                <div class="relative sm:rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-400 ">
                            <thead class="text-xs text-gray-300 uppercase bg-sky-900">
                                <tr>
                                    <th scope="col" class="px-4 py-3">ID</th>
                                    <th scope="col" class="px-4 py-3">Amount</th>
                                    <th scope="col" class="px-4 py-3">Date & Time</th>
                                    <th scope="col" class="px-4 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="transaction in transactions" :key="transaction.id" class="border-b border-gray-500">
                                    <th scope="row"
                                        class="px-4 py-3 font-medium text-custom-white whitespace-nowrap">{{ transaction.id }}</th>
                                    <td class="text-gray-300 px-4 py-3">{{ transaction.amount }}</td>
                                    <td class="text-gray-300 px-4 py-3">{{ formatDate(transaction.created_at) }}</td>
                                    <td class="text-gray-300 px-4 py-3 flex items-center justify-end">
                                        <button id="apple-imac-27-dropdown-button"
                                            data-dropdown-toggle="apple-imac-27-dropdown"
                                            class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                            type="button">
                                            <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                            </svg>
                                        </button>
                                        <!-- Add your dropdown content here -->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <section v-else class="p-3">
            <p class="text-center text-gray-300">No transactions yet.</p>
        </section>
    </AuthenticatedLayout>
</template>
