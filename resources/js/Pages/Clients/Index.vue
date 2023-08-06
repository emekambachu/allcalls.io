<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ClientDetailsModal from "@/Components/ClientDetailsModal.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref } from "vue";

let props = defineProps({
  clients: {
    type: Object,
  },

  totalCalls: {
    type: Number,
  },

  totalAmountSpent: {
    type: Number,
  },

  averageCallDuration: {
    type: Number,
  },
});

console.log(props.clients);

let fetchClients = (page) => {
  // Create URL object from page
  let url = new URL(page);

  // Ensure the protocol is https
  if (url.protocol !== "https:") {
    url.protocol = "https:";
  }

  // Get the https URL as a string
  let httpsPage = url.toString();

  router.visit(httpsPage, { method: "get" });
};

let showModal = ref(false);
let selectedClient = ref(null);

let openClientModal = (client) => {
  selectedClient.value = client;
  showModal.value = true;
};

let formatTime = (duration) => {
  const minutes = Math.floor(duration / 60);
  const seconds = Math.floor(duration % 60);
  return `${minutes.toString().padStart(2, "0")}:${seconds
    .toString()
    .padStart(2, "0")}`;
};

let formatMoney = (amount) => {
  return parseFloat(amount)
    .toFixed(2)
    .replace(/\d(?=(\d{3})+\.)/g, "$&,");
};

let capitalizeAndReplaceUnderscore = (str) => {
  return str
    .replace(/(?:^|\s|_)\S/g, (a) => a.toUpperCase())
    .replace(/_/g, " ");
};
</script>

<template>
  <Head title="Client Information" />
  <AuthenticatedLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
      >
        Clients
      </h2>
    </template>

    <div class="pt-14">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="px-4 sm:px-8 sm:rounded-lg">
          <div class="text-4xl text-custom-sky font-bold mb-6">Clients</div>
          <hr class="mb-4" />
        </div>
      </div>
    </div>

    <div
      class="mx-auto px-4 sm:px-8 md:px-16 py-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"
    >
      <div
        class="max-w-sm p-6 bg-custom-darksky rounded-lg shadow overflow-auto"
      >
        <p class="mb-1 text-sm text-gray-300">Total Calls</p>
        <h2 class="mb-2 text-2xl sm:text-3xl lg:text-4xl font-bold text-white">
          {{ totalCalls }}
        </h2>
      </div>
      <div
        class="max-w-sm p-6 bg-custom-darksky rounded-lg shadow overflow-auto"
      >
        <p class="mb-1 text-sm text-gray-300">Total Spent</p>
        <h2 class="mb-2 text-2xl sm:text-3xl lg:text-4xl font-bold text-white">
          ${{ formatMoney(totalAmountSpent) }}
        </h2>
      </div>
      <div
        class="max-w-sm p-6 bg-custom-darksky rounded-lg shadow overflow-auto"
      >
        <p class="mb-1 text-sm text-gray-300">Average Call Duration</p>
        <h2 class="mb-2 text-2xl sm:text-3xl lg:text-4xl font-bold text-white">
          {{ formatTime(averageCallDuration) }}
        </h2>
      </div>
    </div>

    <section v-if="clients.data.length" class="p-3">
      <div class="mx-auto max-w-screen-xl sm:px-12">
        <div class="relative sm:rounded-lg overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-400">
              <thead class="text-xs text-gray-300 uppercase bg-sky-900">
                <tr>
                  <th scope="col" class="px-4 py-3">ID</th>
                  <th scope="col" class="px-4 py-3">FULL NAME</th>
                  <th scope="col" class="px-4 py-3">EMAIL</th>
                  <th scope="col" class="px-4 py-3">PHONE</th>
                  <th scope="col" class="px-4 py-3">ZIP CODE</th>
                  <th scope="col" class="px-4 py-3">DOB</th>
                  <th scope="col" class="px-4 py-3">Status</th>
                  <th scope="col" class="px-4 py-3">
                    <span class="sr-only">Actions</span>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="client in clients.data"
                  :key="client.id"
                  class="border-b border-gray-500"
                >
                  <td class="text-gray-300 px-4 py-3">{{ client.id }}</td>
                  <th
                    scope="row"
                    class="px-4 py-3 font-medium text-custom-white whitespace-nowrap"
                  >
                    {{ client.first_name }} {{ client.last_name }}
                  </th>
                  <td class="text-gray-300 px-4 py-3">{{ client.email }}</td>
                  <td class="text-gray-300 px-4 py-3">{{ client.phone }}</td>
                  <td class="text-gray-300 px-4 py-3">{{ client.zipCode }}</td>
                  <td class="text-gray-300 px-4 py-3">{{ client.dob }}</td>
                  <td class="text-gray-300 px-4 py-3">
                    

                    <span
                      class="inline-flex items-center text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full"
                      :class="{
                        'bg-green-900 text-green-300': client.status === 'sold',
                        'bg-red-900 text-red-300': client.status === 'not_sold',
                        'bg-yellow-900 text-yellow-300': client.status === 'follow_up_needed',
                      }"
                    >
                      <span
                        class="w-2 h-2 mr-1 rounded-full"
                        :class="{
                          'bg-green-500': client.status === 'sold',
                          'bg-red-500': client.status === 'not_sold',
                          'bg-yellow-500': client.status === 'follow_up_needed',
                        }"
                      ></span>
                      {{ capitalizeAndReplaceUnderscore(client.status) }}
                    </span>
                  </td>
                  <td
                    class="text-gray-300 px-4 py-3 flex items-center justify-end"
                  >
                    <button
                      @click="openClientModal(client)"
                      class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                      type="button"
                    >
                      View Client
                    </button>
                  </td>
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
                  <span class="font-semibold text-white">{{
                    clients.current_page
                  }}</span>
                  of
                  <span class="font-semibold text-white">{{
                    clients.last_page
                  }}</span>
                </span>
                <ul
                  class="inline-flex items-stretch -space-x-px cursor-pointer"
                >
                  <li>
                    <a
                      v-if="clients.prev_page_url"
                      @click="fetchClients(clients.prev_page_url)"
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
                      >{{ clients.current_page }}
                    </a>
                  </li>

                  <li>
                    <a
                      v-if="clients.next_page_url"
                      @click="fetchClients(clients.next_page_url)"
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
      <p class="text-center text-gray-300">No clients yet.</p>
    </section>

    <ClientDetailsModal
      :showModal="showModal"
      :selectedClient="selectedClient"
      @close="showModal = false"
    ></ClientDetailsModal>

  </AuthenticatedLayout>
</template>
