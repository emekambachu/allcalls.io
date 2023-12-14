<script setup>
import { ref, reactive, defineEmits, onMounted, watch } from "vue";
import TextInput from "@/Components/TextInput.vue";
import { router } from "@inertiajs/vue3"
import axios from "axios";
let props = defineProps({
  user: Object,
});

let transactions = ref([]);
onMounted(() => {
  FetehTransactions(props.user.id);
})

let slidingLoader = ref(false)
let FetehTransactions = async (id) => {
  slidingLoader.value = true
  try {
    const response = await axios.get(`/admin/customer/transactions/${id}`);
    const data = response.data; // Assuming your API response provides relevant data
    transactions.value = data.transactions
    slidingLoader.value = false
  } catch (error) {
    console.error(error);
  }
};
let fetchTransactionsBypage = async (page) => {
  if (page) {
    slidingLoader.value = true
    let url = new URL(page);
    try {
      const response = await axios.get(`/admin/customer/transactions/${props.user.id}${url.search}`);
      const data = response.data; // Assuming your API response provides relevant data
      transactions.value = data.transactions
      slidingLoader.value = false
    } catch (error) {
      console.error(error);
    }
  }
};
</script>

<template>
  <animation-slider :slidingLoader="slidingLoader" />
  <div v-if="transactions.data?.length && slidingLoader == false" class="p-3">
    <div class="mx-auto max-w-screen-xl sm:px-12">
      <div class="relative sm:rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-sm text-left text-gray-400">
            <thead class="text-xs text-gray-300 uppercase bg-sky-900">
              <tr>
                <th scope="col" class="px-4 py-3">ID</th>
                <th scope="col" class="px-4 py-3">Amount</th>
                <th scope="col" class="px-4 py-3">Sign</th>
                <th scope="col" class="px-4 py-3">Card NO.</th>
                <th scope="col" class="px-4 py-3">City</th>
                <th scope="col" class="px-4 py-3">State</th>
                <th scope="col" class="px-4 py-3">Zip</th>
                <th scope="col" class="px-4 py-3">Label</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="transaction in transactions.data" :key="transaction.id" class="border-b border-gray-500">

                <td class="text-gray-600 px-4 py-3">{{ transaction.id }}</td>
                <td class="text-gray-600 px-4 py-3">{{ transaction.amount }}</td>
                <td class="text-gray-600 px-4 py-3">{{ transaction.sign }}</td>
                <td class="text-gray-600 px-4 py-3">{{ transaction.card?.number }}</td>
                <td class="text-gray-600 px-4 py-3">{{ transaction.card?.city }}</td>
                <td class="text-gray-600 px-4 py-3">{{ transaction.card?.state }}</td>
                <td class="text-gray-600 px-4 py-3">{{ transaction.card?.zip }}</td>
                <td class="text-gray-600 px-4 py-3">{{ transaction.label ? transaction.label : '-' }}</td>
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
              <li v-for="(link, index) in transactions.links" :key="link.label" :class="{ disabled: link.url === null }">
                <a href="#" @click.prevent="fetchTransactionsBypage(link.url)" :class="[
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
  </div>

  <div v-else-if="slidingLoader == false" class="p-3">
    <p class="text-center text-gray-600">No Transactions yet.</p>
  </div>
</template>