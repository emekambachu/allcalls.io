<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Edit from "@//Pages/Admin/User/Edit.vue";
import Create from "@//Pages/Admin/Agent/Create.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import { toaster } from "@/helper.js";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from "@/Components/Modal.vue";
import SearchFilter from "@/Components/SearchFilter.vue";
import ContractSteps from '@/Pages/InternalAgent/ContractSteps.vue'
let page = usePage();
if (page.props.flash.message) {
  toaster("success", page.props.flash.message);
}

let props = defineProps({
  agents: {
    type: Object,
  },

  callTypes: Array,
  states: Array,
  requestData: {
    type: Array,
  },
  callTypes: Array,
  states: Array,
});
let contractModal = ref(false)
let formData = ref({
  name: props.requestData.name,
  email: props.requestData.email,
  phone: props.requestData.phone,
  first_six_card_no: props.requestData.first_six_card_no,
  last_four_card_no: props.requestData.last_four_card_no,
});

let fetchAgents = (page) => {
  // Create URL object from page
  let url = new URL(page);

  if (formData.value.name !== undefined && formData.value.name !== null) {
    url.searchParams.set("name", formData.value.name);
  }
  if (formData.value.email !== undefined && formData.value.email !== null) {
    url.searchParams.set("email", formData.value.email);
  }
  if (formData.value.phone !== undefined && formData.value.phone !== null) {
    url.searchParams.set("phone", formData.value.phone);
  }
  if (formData.value.card_no !== undefined && formData.value.card_no !== null) {
    url.searchParams.set("card_no", formData.value.card_no);
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

let openAgentModal = (user, page) => {
  userDetail.value = user;
  currentPage.value = page;
  showModal.value = true;
};
let userData = ({})
let openContractModal = (agent) => {
  // console.log('agent',agent);
  userData.value = agent
  contractModal.value = true
}
let agentModal = ref(false);
let addAgentModal = (page) => {
  agentModal.value = true;
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
</script>

<template>
  <Head title="Agent Information" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Agents
      </h2>
    </template>

    <div class="pt-14 flex justify-between px-16">
      <!-- <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="px-4 sm:px-8 sm:rounded-lg">
                    <div class="text-4xl text-custom-sky font-bold mb-6">Agents</div>
                    <hr class="mb-4" />
                </div>
            </div> -->
      <div>
        <div class="text-4xl text-custom-sky font-bold mb-6">Agents</div>
      </div>
      <div>
        <PrimaryButton @click="addAgentModal(agents.current_page)">Add New</PrimaryButton>
      </div>
    </div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
      <div class="px-4 sm:px-8 sm:rounded-lg">
        <hr class="mb-4" />
      </div>
    </div>
    <SearchFilter :route="page.url" :requestData="requestData" />
    <section v-if="agents.data.length" class="p-3">
      <div class="mx-auto max-w-screen-xl sm:px-12">
        <div class="relative sm:rounded-lg overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-400">
              <thead class="text-xs text-gray-300 uppercase bg-sky-900">
                <tr>
                  <th scope="col" class="px-4 py-3">ID</th>
                  <th scope="col" class="px-4 py-3">First Name</th>
                  <th scope="col" class="px-4 py-3">Last Name</th>
                  <th scope="col" class="px-4 py-3">Email</th>
                  <th scope="col" class="px-4 py-3">Balance</th>
                  <th scope="col" class="px-4 py-3">Phone</th>
                  <th scope="col" class="px-4 py-3 text-end">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="agent in agents.data" :key="agent.id" class="border-b border-gray-500">
                  <td class="text-gray-600 px-4 py-3">{{ agent.id }}</td>
                  <td class="text-gray-600 px-4 py-3">{{ agent.first_name }}</td>
                  <td class="text-gray-600 px-4 py-3">{{ agent.last_name }}</td>
                  <th class="text-gray-600 px-4 py-3">{{ agent.email }}</th>
                  <td class="text-gray-600 px-4 py-3">
                    ${{ formatMoney(agent.balance) }}
                  </td>
                  <td class="text-gray-600 px-4 py-3">{{ agent.phone }}</td>
                  <td class="text-gray-700 px-4 py-3 flex items-center justify-end">
                    <a title="View Agent" :href="route('admin.agent.detail', agent.id)"><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                          d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      </svg>
                    </a>

                    <button title="Edit Agent" @click="openAgentModal(agent, agents.current_page)"
                      class="inline-flex items-center mx-2 p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none"
                      type="button">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                          d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                      </svg>
                    </button>
                    <button title="Onboarding info" v-show="agent.internal_agent_contract"
                      @click="openContractModal(agent)"> <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                          d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                      </svg>

                    </button>
                    <button title="View PDF">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5 ">
                        <path stroke-linecap="round" stroke-linejoin="round"
                          d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                      </svg>
                    </button>
                  </td>
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
                    agents.current_page
                  }}</span>
                  of
                  <span class="font-semibold text-custom-blue">{{
                    agents.last_page
                  }}</span>
                </span>
                <ul class="inline-flex items-stretch -space-x-px cursor-pointer">
                  <li>
                    <a v-if="agents.prev_page_url" @click="fetchAgents(agents.prev_page_url)"
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
                        agents.current_page }}
                    </a>
                  </li>

                  <li>
                    <a v-if="agents.next_page_url" @click="fetchAgents(agents.next_page_url)"
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
      <p class="text-center text-gray-600">No clients yet.</p>
    </section>
    <ContractSteps v-if="contractModal" @close="contractModal = false" :states="states" :userData="userData" />

    <Modal :show="showModal" @close="showModal = false">
      <Edit :showModal="showModal" :userDetail="userDetail" :currentPage="currentPage" @close="showModal = false"
        :callTypes="callTypes" :states="states" :route="'/admin/agent'"></Edit>
    </Modal>
    <Modal :show="agentModal" @close="agentModal = false">
      <Create :agentModal="agentModal" :currentPage="currentPage" :callTypes="callTypes" :states="states"
        @close="agentModal = false"></Create>
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
}</style>
