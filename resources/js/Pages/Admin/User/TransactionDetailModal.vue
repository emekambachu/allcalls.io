<script setup>
import { ref, reactive, defineEmits, onMounted, watch } from "vue";
import TextInput from "@/Components/TextInput.vue";
import { router } from "@inertiajs/vue3"
import axios from "axios";
let props = defineProps({
  showTransactionModal: {
    type: Boolean,
  },
  user: Object,
  detailType: {
    type: String,
  },
});
let editScreen = ref(false);
let emit = defineEmits(["close"]);


let close = () => {

  emit("close");

};

let form = reactive(null);
let transactions = ref([]);

watch(() => props.showTransactionModal, async (newValue) => {
  if (newValue) {
    // Call APIs here and update the form data accordingly
    await FetehTransactions(props.user.id);
  }
});
let FetehTransactions = async (id) => {
  try {
    const response = await axios.get(`/admin/customer/transactions/${id}`);
    const data = response.data; // Assuming your API response provides relevant data
    console.log('what is data', data.transactions);
    transactions.value = data.transactions
  } catch (error) {
    console.error(error);
  }
};
let fetchTransactionsBypage = async (page) => {
  let url = new URL(page);
   try {
    const response = await axios.get(`/admin/customer/transactions/${props.user.id}${url.search}`);
    const data = response.data; // Assuming your API response provides relevant data
    console.log('what is data', data.transactions);
    transactions.value = data.transactions
  } catch (error) {
    console.error(error);
  }
};
</script>

<template>
  <Transition name="modal" enter-active-class="transition ease-out duration-300 transform"
    enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    enter-to-class="opacity-100 translate-y-0 sm:scale-100" leave-active-class="transition ease-in duration-200 transform"
    leave-from-class="opacity-100 translate-y-0 sm:scale-100"
    leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
    <div id="defaultModal" v-if="showTransactionModal" tabindex="-1"
      class="flex items-center justify-center fixed inset-0 z-50 w-full h-full overflow-x-hidden overflow-y-auto max-h-full mx-4 sm:mx-0">
      <div class="fixed inset-0 bg-black opacity-60"></div>
      <!-- This is the overlay -->

      <div class="relative w-full max-w-4xl max-h-full mx-auto">
        <div class="relative bg-white rounded-lg shadow-lg">
          <div class="flex items-start justify-between p-4 border-b rounded-t border-gray-600">
              <h1>Customer Transactions</h1>

            <button @click="close" type="button"
              class="text-gray-500 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center"
              data-modal-hide="defaultModal">
              <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
              </svg>
              <span class="sr-only">Close modal</span>
            </button>
          </div>
          <section v-if="transactions.data?.length" class="p-3">
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
                      </tr>
                    </tbody>
                  </table>
                  <div class="p-4">
                    <nav
                      class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4"
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
          </section>

          <section v-else class="p-3">
            <p class="text-center text-gray-600">No Transactions yet.</p>
          </section>
          <div class="p-6">

            <div class="flex justify-end">
              <button @click="close"
                class="border border-gray-400 ease-in cursor-pointer bg-white bg-opacity-5 hover:shadow-2xl hover:bg-white hover:text-custom-blue hover:bg-opacity-80 rounded px-3 py-3 font-bold text-md text-gray-500 transition">
                Close
              </button>
            </div>
          </div>
        </div>
      </div>


    </div>
  </Transition>
</template>