<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";

let props = defineProps({
  transactions: {
    type: Object,
  },
  isInternalAgent: Boolean,
});

let formatDate = (date) => {
  if (!date) {
    return "";
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
};

let formatMoney = (amount) => {
  return parseFloat(amount)
    .toFixed(2)
    .replace(/\d(?=(\d{3})+\.)/g, "$&,");
};
let paginate = (url) => {
  router.visit(url);
};
</script>

<template>
  <Head title="Transactions" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Transactions</h2>
    </template>

    <div class="pt-14">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="px-4 sm:px-8 sm:rounded-lg">
          <div class="text-4xl text-custom-sky font-bold mb-6">Transactions</div>
          <hr class="mb-4" />
        </div>
      </div>
    </div>

    <section v-if="transactions.data.length" class="p-3">
      <div class="mx-auto max-w-screen-xl sm:px-12">
        <div class="relative sm:rounded-lg overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-400 table-responsive">
              <thead class="text-xs text-gray-300 uppercase bg-sky-900">
                <tr>
                  <th scope="col" class="px-4 py-3">ID</th>
                  <th scope="col" class="px-4 py-3">Amount</th>
                  <th scope="col" class="px-4 py-3" style="min-width:240px">Date & Time</th>
                  <th scope="col" class="px-4 py-3">Label</th>
                  <th scope="col" class="px-4 py-3">Direction</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="transaction in transactions.data"
                  :key="transaction.id"
                  class="border-b border-gray-500"
                >
                  <th
                    scope="row"
                    class="px-4 py-3 font-medium text-gray-700 whitespace-nowrap"
                  >
                    {{ transaction.id }}
                  </th>
                  <td class="text-gray-600 px-4 py-3">
                    {{ transaction.sign ? "" : "-" }} $
                    {{ formatMoney(transaction.amount) }}
                  </td>
                  <td class="text-gray-600 px-4 py-3">
                    {{ transaction.created_at }}
                  </td>
                  <td class="text-gray-600 px-4 py-3">{{ transaction.label ? transaction.label : '-' }}</td>
                  <td class="text-gray-300 px-4 py-3">
                    <div v-if="isInternalAgent && transaction.bonus">
                      <span
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800"
                      >
                        Bonus
                      </span>
                    </div>

                    <div v-else>
                      <span
                        v-if="transaction.sign"
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800"
                      >
                        Deposited
                      </span>

                      <span
                        v-else
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800"
                      >
                        Deducted
                      </span>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            <nav class="flex justify-between my-4" v-if="transactions.links">
              <div v-if="transactions">
                <span class="text-sm text-gray-700">
                  Showing
                  <span class="font-semibold text-gray-900">{{ transactions.from }}</span>
                  to
                  <span class="font-semibold text-gray-900">{{ transactions.to }}</span> of
                  <span class="font-semibold text-gray-900">{{ transactions.total }}</span>
                  Entries
                </span>
              </div>

              <ul class="inline-flex -space-x-px text-base h-10">
                <li v-for="(link, index) in transactions.links" :key="link.label"
                  :class="{ disabled: link.url === null }">
                  <a href="#" @click.prevent="paginate(link.url)" :class="[
                    'flex items-center justify-center px-4 h-10 border border-gray-300',
                    link.active
                      ? 'text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-700'
                      : 'leading-tight text-gray-500 bg-white hover:bg-gray-100 hover:text-gray-700',
                    {
                      'rounded-l-lg': index === 0,
                      'rounded-r-lg': index === transactions.links.length - 1,
                    },
                  ]" v-html="link.label"></a>
                </li>
              </ul>
            </nav>
            <br>
            
          </div>
        </div>
      </div>
    </section>

    <section v-else class="p-3">
      <p class="text-center text-gray-600">No transactions yet.</p>
    </section>
  </AuthenticatedLayout>
</template>
