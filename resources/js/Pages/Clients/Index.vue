<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ClientDetailsModal from "@/Components/ClientDetailsModal.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import { createToaster } from "@meforma/vue-toaster";

let toaster = createToaster({
  position: "top-right",
});

let page = usePage();

if (page.props.flash.message) {
  toaster.success(page.props.flash.message);
}

let props = defineProps({
  calls: {
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

console.log(props.calls);

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
let callDetail = ref(null);

let openClientModal = (call) => {
  callDetail.value = call;
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

let formatDate = (inputDate) => {
  // Split the input date by the hyphen ("-") to get year, month, and day
  const [year, month, day] = inputDate.split("-");

  // Rearrange the components in the "mm-dd-yyyy" format
  const formattedDate = `${month}-${day}-${year}`;

  return formattedDate;
}


let capitalizeAndReplaceUnderscore = (str) => {
  // Replace underscores with spaces
  let result = str.replace(/_/g, ' ');

  // Capitalize the first letter of each word
  result = result.replace(/\b(\w)/g, (match) => match.toUpperCase());

  return result;
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

    <section v-if="calls.data.length" class="p-3">
      <div class="mx-auto max-w-screen-xl sm:px-12">
        <div class="relative sm:rounded-lg overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-400">
              <thead class="text-xs text-gray-300 uppercase bg-sky-900">
                <tr>
                  <th scope="col" class="px-4 py-3">ID</th>
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
                  v-for="call in calls.data"
                  :key="call.id"
                  class="border-b border-gray-500"
                >
                 
                  <td class="text-gray-600 px-4 py-3">{{ call.id }}</td>
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
                  <span class="font-semibold text-custom-blue">{{
                    calls.current_page
                  }}</span>
                  of
                  <span class="font-semibold text-custom-blue">{{
                    calls.last_page
                  }}</span>
                </span>
                <ul
                  class="inline-flex items-stretch -space-x-px cursor-pointer"
                >
                  <li>
                    <a
                      v-if="calls.prev_page_url"
                      @click="fetchClients(calls.prev_page_url)"
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
                      >{{ calls.current_page }}
                    </a>
                  </li>

                  <li>
                    <a
                      v-if="calls.next_page_url"
                      @click="fetchClients(calls.next_page_url)"
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

    <ClientDetailsModal
      :showModal="showModal"
      :callDetail="callDetail"
      @close="showModal = false"
    ></ClientDetailsModal>

  </AuthenticatedLayout>
</template>
