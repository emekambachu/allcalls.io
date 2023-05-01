<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";

const page = usePage()

const toaster = createToaster({
  position: 'top-right',
});

if (page.props.flash.message) {
  toaster.success(page.props.flash.message);
}

defineProps({
  transactions: {
    type: Array,
  },
});

let deleteTransaction = (transactionId) => {
  router.visit(`/transactions/${transactionId}`, {
    method: 'delete',
  });
};
</script>

<template>
  <Head title="Transactions" />
  <AuthenticatedLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
      >
        Transactions
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="relative overflow-x-auto">
          <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <div class="relative overflow-x-auto" v-if="transactions.length">
              <table
                class="w-full text-sm text-left text-gray-500 dark:text-gray-400"
              >
                <thead
                  class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                >
                  <tr>
                    <th scope="col" class="px-6 py-3">ID</th>
                    <th scope="col" class="px-6 py-3">Amount</th>
                    <th scope="col" class="px-6 py-3">Date & Time</th>
                    <th scope="col" class="px-6 py-3">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="transaction in transactions"
                    :key="transaction.id"
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                  >
                    <th
                      scope="row"
                      class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                      v-text="transaction.id"
                    ></th>
                    <td class="px-6 py-4">
                      <span v-if="transaction.sign === 1" class="text-green-500">
                        +${{ transaction.amount.toFixed(2) }}
                      </span>
                      <span v-if="transaction.sign === 0" class="text-red-500">
                        -${{ transaction.amount.toFixed(2) }}
                      </span>
                    </td>
                    <td class="px-6 py-4" v-text="new Date(transaction.created_at).toLocaleString()"></td>
                    <td class="px-6 py-4">
                      <a
                        href="#"
                        class="px-3 py-2 text-xs font-medium text-center text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 rounded-lg mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
                        @click.prevent="deleteTransaction(transaction.id)"
                        >Delete Transaction</a
                      >
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="text-center text-gray-900 dark:text-gray-400" v-else>
              You haven't made any transactions yet.
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
