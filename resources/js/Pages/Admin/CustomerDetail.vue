<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { Bar } from "vue-chartjs";


const props = defineProps({
  role: String,
  transactions: Array,
  user: Object,
});
</script>

<template>
  <Head title="Dashboard" />
  <AuthenticatedLayout  :role="role">
  

    <div class="pt-14">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="px-4 sm:px-8 sm:rounded-lg">
          <div class="text-4xl text-custom-sky font-bold mb-6">
              {{ user.first_name }} {{ user.last_name }}</div>
          <hr class="mb-4" />
        </div>
      </div>
    </div>

    

    <section v-if="transactions.data.length" class="p-3">
      <div class="mx-auto max-w-screen-xl sm:px-12">
        <div class="relative sm:rounded-lg overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-400">
              <thead class="text-xs text-gray-300 uppercase bg-sky-900">
                <tr>
                  <th scope="col" class="px-4 py-3">#</th>
                  <th scope="col" class="px-4 py-3">HANG UP BY</th>
                  <th scope="col" class="px-4 py-3">CALL DURATION</th>
                  <th scope="col" class="px-4 py-3">CALL TAKEN</th>
                  <th scope="col" class="px-4 py-3">AMMOUNT SPENT</th>
                  <th scope="col" class="px-4 py-3">CALL TYPE</th>
                  <th scope="col" class="px-4 py-3 text-end">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(transaction, index) in transactions.data"
                  :key="transaction.id"
                  class="border-b border-gray-500"
                >
                <th class="text-gray-600 px-4 py-3">{{ index + 1 }}</th>
                {{transaction  }}
                  <!-- <td class="text-gray-600 px-4 py-3">{{ call.id }}</td>
                  <td class="text-gray-600 px-4 py-3">{{ call.hung_up_by }}</td>
                  <td class="text-gray-600 px-4 py-3">
                    {{
                    String(
                      Math.floor(call.call_duration_in_seconds / 60)
                    ).padStart(2, "0") +
                    ":" +
                    String(
                      call.call_duration_in_seconds % 60
                    ).padStart(2, "0")
                  }}
                  </td>
              
                  <th class="text-gray-600 px-4 py-3">{{ call.call_taken }}</th>
                  <td class="text-gray-600 px-4 py-3">{{ call.amount_spent }}</td>
                  <td class="text-gray-600 px-4 py-3">{{ call.call_type.type }}</td>
                  <td 
                    class="text-gray-700 px-4 py-3 flex items-center justify-end"
                  >

                    <button v-if="call.get_client"
                      @click="openClientModal(call)"
                      class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none"
                      type="button"
                    >
                      View Client
                    </button>
                    <button v-else class="text-center"
                      type="button">-</button>
                  </td> -->
                </tr>
              </tbody>
            </table>
            <div class="p-4">
              <nav
                class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4"
                aria-label="Table navigation"
              >
                <span
                  class="text-sm font-normal text-gray-500 dark:text-gray-400"
                >
                  Showing
                  <span class="font-semibold text-custom-blue">{{
                    transactions.current_page
                  }}</span>
                  of
                  <span class="font-semibold text-custom-blue">{{
                    transactions.last_page
                  }}</span>
                </span>
                <ul
                  class="inline-flex items-stretch -space-x-px cursor-pointer"
                >
                  <li>
                    <a
                      v-if="transactions.prev_page_url"
                      @click="fetchClients(transactions.prev_page_url)"
                      class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-custom-white rounded-l-lg hover:bg-sky-950 hover:shadow-2xl hover:text-white"
                    >
                      <span class="sr-only">Previous</span>
                      <svg
                        class="w-5 h-5"
                        aria-hidden="true"
                        fill="currentColor"
                        viewbox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <path
                          fill-rule="evenodd"
                          d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                          clip-rule="evenodd"
                        />
                      </svg>
                    </a>
                  </li>

                  <li>
                    <a
                      class="flex items-center justify-center text-sm py-2 px-3 leading-tight font-extrabold text-gray-500 bg-custom-white shadow-2xl hover:bg-sky-950 hover:shadow-2xl hover:text-white"
                      >{{ transactions.current_page }}
                    </a>
                  </li>

                  <li>
                    <a
                      v-if="transactions.next_page_url"
                      @click="fetchClients(transactions.next_page_url)"
                      class="flex items-center justify-center h-full py-1.5 px-3 leading-tight text-gray-500 bg-custom-white rounded-r-lg hover:bg-sky-950 hover:shadow-2xl hover:text-white"
                    >
                      <span class="sr-only">Next</span>
                      <svg
                        class="w-5 h-5"
                        aria-hidden="true"
                        fill="currentColor"
                        viewbox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <path
                          fill-rule="evenodd"
                          d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                          clip-rule="evenodd"
                        />
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
      <p class="text-center text-gray-600">No clients yet.</p>
    </section>
  </AuthenticatedLayout>
</template>
