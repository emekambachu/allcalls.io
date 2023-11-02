<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Edit from "@/Pages/Admin/User/Edit.vue";
import Create from "@/Pages/Admin/AvailableNumber/Create.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import { toaster } from "@/helper.js";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from "@/Components/Modal.vue";
// import SearchFilterAvailableNumber from "/Components/SearchFilterAvailableNumber.vue";

let page = usePage();
if (page.props.flash.message) {
  toaster("success", page.props.flash.message);
}

let props = defineProps({
  availableNumber: {
    type: Object,
  },

  callTypes: Array,
  states: Array,
  requestData: {
    type: Array,
  },
  callTypes: Array,
  states: Array,
  user: Array,
});
let formData = ref({
  phone: props.requestData.phone,
});

let fetchAvailableNumber = (page) => {
  // Create URL object from page
  let url = new URL(page);
  console.log(page);
  if (formData.value.phone !== undefined && formData.value.phone !== null) {
    url.searchParams.set("phone", formData.value.phone);
  }

  // Ensure the protocol is https
  if (url.protocol !== "https:") {
    url.protocol = "https:";
  }

  // Get the https URL as a string
  let httpsPage = url.toString();

  router.visit(httpsPage, { method: "get" });
};

let showModal = ref(false);
let userDetail = ref(null);
let currentPage = ref(null);

let openAvailableNumberModal = (availableNumber, page) => {
  availableNumber.value = availableNumber;
  currentPage.value = page;
  showModal.value = true;
};
let availableNumberModal = ref(false);
let addAvailableNumberModal = (page) => {
  availableNumberModal.value = true;
  currentPage.value = page;
};
let formatTime = (duration) => {
  const minutes = Math.floor(duration / 60);
  const seconds = Math.floor(duration % 60);
  return `${minutes.toString().padStart(2, "0")}:${seconds.toString().padStart(2, "0")}`;
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
};

let capitalizeAndReplaceUnderscore = (str) => {
  // Replace underscores with spaces
  let result = str.replace(/_/g, " ");

  // Capitalize the first letter of each word
  result = result.replace(/\b(\w)/g, (match) => match.toUpperCase());

  return result;
};

let mapCallType = (callTypeId) => {
  const callTypeMap = {
    1: 'Auto Insurance',
    2: 'Final Expense',
    3: 'U65 Health',
    4: 'ACA',
    5: 'Medicare/Medicaid'
  };

  // Convert callTypeId to Number in case it's passed as a string
  callTypeId = Number(callTypeId);

  // Return the corresponding value from the map
  return callTypeMap[callTypeId] || ''; // Default to '' if id is not found
};

</script>

<template>
  <Head title="availableNumber Information" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Available Number
      </h2>
    </template>

    <div class="pt-14 flex justify-between px-16">
      <!-- <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="px-4 sm:px-8 sm:rounded-lg">
                    <div class="text-4xl text-custom-sky font-bold mb-6">availableNumber</div>
                    <hr class="mb-4" />
                </div>
            </div> -->
      <div>
        <div class="text-4xl text-custom-sky font-bold mb-6">Available Number</div>
      </div>
      <div>
        <PrimaryButton @click="addAvailableNumberModal(availableNumber.current_page)"
          >Add New</PrimaryButton
        >
      </div>
    </div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
      <div class="px-4 sm:px-8 sm:rounded-lg">
        <hr class="mb-4" />
      </div>
    </div>
    <!-- <SearchFilterAvailableNumber :route="page.url" :requestData="requestData" /> -->
    <section v-if="availableNumber.data.length" class="p-3">
      <div class="mx-auto max-w-screen-xl sm:px-12">
        <div class="relative sm:rounded-lg overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-400">
              <thead class="text-xs text-gray-300 uppercase bg-sky-900">
                <tr>
                  <th scope="col" class="px-4 py-3">ID</th>
                  <th scope="col" class="px-4 py-3">Phone Number</th>
                  <th scope="col" class="px-4 py-3">User</th>
                  <th scope="col" class="px-4 py-3">Last Caller ID</th>
                  <th scope="col" class="px-4 py-3">Call Type</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="availableNumber in availableNumber.data"
                  :key="availableNumber.id"
                  class="border-b border-gray-500"
                >
                  <td class="text-gray-600 px-4 py-3">{{ availableNumber.id }}</td>
                  <td class="text-gray-600 px-4 py-3">
                    {{ availableNumber.phone }}
                  </td>
                  <td class="text-gray-600 px-4 py-3">
                    <a
                      v-if="availableNumber.user"
                      :href="'/admin/customer/detail/' + availableNumber.user.id"
                    >
                      {{ availableNumber.user.first_name }}
                    </a>
                  </td>
                  <th class="text-gray-600 px-4 py-3">
                    {{ availableNumber.from }}
                  </th>
                  <td class="text-gray-600 px-4 py-3">
                    {{ mapCallType(availableNumber.call_type_id) }}
                  </td>
                </tr>
              </tbody>
            </table>
            <div class="p-4">
              <nav
                class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4"
                aria-label="Table navigation"
              >
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                  Showing
                  <span class="font-semibold text-custom-blue">{{
                    availableNumber.current_page
                  }}</span>
                  of
                  <span class="font-semibold text-custom-blue">{{
                    availableNumber.last_page
                  }}</span>
                </span>
                <ul class="inline-flex items-stretch -space-x-px cursor-pointer">
                  <li>
                    <a
                      v-if="availableNumber.prev_page_url"
                      @click="fetchAvailableNumber(availableNumber.prev_page_url)"
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
                      >{{ availableNumber.current_page }}
                    </a>
                  </li>

                  <li>
                    <a
                      v-if="availableNumber.next_page_url"
                      @click="fetchAvailableNumber(availableNumber.next_page_url)"
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
      <p class="text-center text-gray-600">No available number yet.</p>
    </section>

    <!-- <Modal :show="showModal" @close="showModal = false">
      <Edit
        :showModal="showModal"
        :user="user"
        :currentPage="currentPage"
        @close="showModal = false"
        :callTypes="callTypes"
        :availableNumber="availableNumber"
        :route="'/admin/available-numbers'"
      ></Edit>
    </Modal> -->

    <Modal :show="availableNumberModal" @close="availableNumberModal = false">
      <Create
        :availableNumberModal="availableNumberModal"
        :currentPage="currentPage"
        :callTypes="callTypes"
        :states="states"
        :user="user"
        @close="availableNumberModal = false"
      ></Create>
    </Modal>
  </AuthenticatedLayout>
</template>

<style src="@vueform/multiselect/themes/default.css"></style>
<style>
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

input[type="number"] {
  -moz-appearance: textfield;
}

.multiselect {
  color: black !important;
  border: none;
  border-radius: 10px;
}

.multiselect-wrapper {
  background-color: #d7d7d7;
  border-radius: 5px;
}

.box-shadow {
  padding: 20px;
  width: 97%;
  margin: auto;
  box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
}
</style>
