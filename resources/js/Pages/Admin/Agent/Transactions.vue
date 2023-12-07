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
    const response = await axios.get(`/admin/agent/transactions/${id}`);
    const data = response.data; // Assuming your API response provides relevant data
    transactions.value = data.transactions
    slidingLoader.value = false
  } catch (error) {
    console.error(error);
  }
};
let fetchTransactionsBypage = async (page) => {
  slidingLoader.value = true
  let url = new URL(page);
  try {
    const response = await axios.get(`/admin/agent/transactions/${props.user.id}${url.search}`);
    const data = response.data; // Assuming your API response provides relevant data
    transactions.value = data.transactions
    slidingLoader.value = false
  } catch (error) {
    console.error(error);
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
                <!-- <th scope="col" class="px-4 py-3">Card NO.</th> -->
                <!-- <th scope="col" class="px-4 py-3">City</th>
                <th scope="col" class="px-4 py-3">State</th>
                <th scope="col" class="px-4 py-3">Zip</th> -->
                <th scope="col" class="px-4 py-3">Label</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="transaction in transactions.data" :key="transaction.id" class="border-b border-gray-500">

                <td class="text-gray-600 px-4 py-3">{{ transaction.id }}</td>
                <td class="text-gray-600 px-4 py-3 font-bold">${{ transaction.amount }}</td>
                <td class="text-gray-600 px-4 py-3">{{ transaction.sign }}</td>
                <!-- <td class="text-gray-600 px-4 py-3">{{ transaction.card?.number }}</td> -->
                <!-- <td class="text-gray-600 px-4 py-3">{{ transaction.card?.city }}</td>
                <td class="text-gray-600 px-4 py-3">{{ transaction.card?.state }}</td>
                <td class="text-gray-600 px-4 py-3">{{ transaction.card?.zip }}</td> -->
                <td class="text-gray-600 px-4 py-3">{{ transaction.label ? transaction.label : '-' }}</td>
              </tr>
            </tbody>
          </table>
          <div class="p-4">
            <nav class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4"
              aria-label="Table navigation">
              <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                Showing
                <span class="font-semibold text-custom-blue">{{
                  transactions.current_page
                }}</span>
                of
                <span class="font-semibold text-custom-blue">{{
                  transactions.last_page
                }}</span>
              </span>
              <ul class="inline-flex items-stretch -space-x-px cursor-pointer">
                <li>
                  <a v-if="transactions.prev_page_url" @click="fetchTransactionsBypage(transactions.prev_page_url)"
                    class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-custom-white rounded-l-lg hover:bg-sky-950 hover:shadow-2xl hover:text-white">
                    <span class="sr-only">Previous</span>
                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20"
                      xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd"
                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                        clip-rule="evenodd" />
                    </svg>
                  </a>
                </li>

                <li>
                  <a
                    class="flex items-center justify-center text-sm py-2 px-3 leading-tight font-extrabold text-gray-500 bg-custom-white shadow-2xl hover:bg-sky-950 hover:shadow-2xl hover:text-white">{{
                      transactions.current_page }}
                  </a>
                </li>

                <li>
                  <a v-if="transactions.next_page_url" @click="fetchTransactionsBypage(transactions.next_page_url)"
                    class="flex items-center justify-center h-full py-1.5 px-3 leading-tight text-gray-500 bg-custom-white rounded-r-lg hover:bg-sky-950 hover:shadow-2xl hover:text-white">
                    <span class="sr-only">Next</span>
                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20"
                      xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                    </svg>
                  </a>
                </li>
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div v-else-if="slidingLoader == false" class="p-3">
    <p class="text-center text-gray-600">No Transactions yet.</p>
  </div>
</template>