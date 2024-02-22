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



let paginate = (url) => {
  router.visit(url);
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


let releaseAllNumbers = () => {
  router.visit('/admin/available-number/release-all', {
    method: 'POST'
  });
}
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
      <div>
        <div class="text-4xl text-custom-sky font-bold mb-6">Available Number</div>
      </div>
      <div class="flex items-center">
        <button @click.prevent="releaseAllNumbers" class="button-custom-back px-3 py-2 rounded-md mr-2">Release All
          Numbers</button>
        <PrimaryButton @click="addAvailableNumberModal(availableNumber.current_page)">Add New</PrimaryButton>
      </div>
    </div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
      <div class="px-4 sm:px-8 sm:rounded-lg">
        <hr class="mb-4" />
      </div>
    </div>
    <!-- <SearchFilterAvailableNumber :route="page.url" :requestData="requestData" /> -->
    <section v-if="availableNumber.data.length" class="p-3">
      <div class="sm:px-12">
        <div class="relative sm:rounded-lg overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-400">
              <thead class="text-xs text-gray-300 uppercase bg-sky-900">
                <tr>
                  <th scope="col" class="px-4 py-3">ID</th>
                  <th scope="col" class="px-4 py-3">Phone Number</th>
                  <th scope="col" class="px-4 py-3">User</th>
                  <th scope="col" class="px-4 py-3">Last Client</th>
                  <th scope="col" class="px-4 py-3">Call Type</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="availableNumber in availableNumber.data" :key="availableNumber.id"
                  class="border-b border-gray-500">
                  <td class="text-gray-600 px-4 py-3">{{ availableNumber.id }}</td>
                  <td class="text-gray-600 px-4 py-3">
                    {{ availableNumber.phone }}
                  </td>
                  <td class="text-gray-600 px-4 py-3">
                    <a v-if="availableNumber.user" :href="'/admin/customer/detail/' + availableNumber.user.id">
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

            <nav class="flex justify-between my-4" v-if="availableNumber.links">
              <div v-if="availableNumber">
                <span class="text-sm text-gray-700">
                  Showing
                  <span class="font-semibold text-gray-900">{{ availableNumber.from }}</span>
                  to
                  <span class="font-semibold text-gray-900">{{ availableNumber.to }}</span> of
                  <span class="font-semibold text-gray-900">{{ availableNumber.total }}</span>
                  Entries
                </span>
              </div>

              <ul class="inline-flex -space-x-px text-base h-10">
                <li v-for="(link, index) in availableNumber.links" :key="link.label"
                  :class="{ disabled: link.url === null }">
                  <a href="#" @click.prevent="paginate(link.url)" :class="[
                    'flex items-center justify-center px-4 h-10 border border-gray-300',
                    link.active
                      ? 'text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-700'
                      : 'leading-tight text-gray-500 bg-white hover:bg-gray-100 hover:text-gray-700',
                    {
                      'rounded-l-lg': index === 0,
                      'rounded-r-lg': index === availableNumber.links.length - 1,
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
      <Create :availableNumberModal="availableNumberModal" :currentPage="currentPage" :callTypes="callTypes"
        :states="states" :user="user" @close="availableNumberModal = false"></Create>
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
