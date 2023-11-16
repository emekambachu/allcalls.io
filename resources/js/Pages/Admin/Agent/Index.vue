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
import ViewPdfindex from "@/Pages/Admin/Agent/ViewPdfindex.vue";
import ProgressView from "@/Pages/Admin/Agent/ProgressView.vue";
import ApproveConfirm from "@/Pages/Admin/Agent/ApproveConfirm.vue";
import axios from "axios";
import { rule } from "postcss";
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
  statuses: Array,
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
let viewModalpdf = ref(false)
let progressModal = ref(false)
let viewPdfData = (agent) => {
  userData.value = agent
  viewModalpdf.value = true
}
let progressFun = (agent) => {
  userData.value = agent
  progressModal.value = true
}
let showModalConfirm = ref(false)
let ApproveAgentVal = ref(null)
let isLoading = ref(false)
let ApproveAgent = (agent) => {

  ApproveAgentVal.value = agent
  showModalConfirm.value = true;
}
let onApprove = () => {
  // Logic for approval
  isLoading.value = true
  axios.get(`/admin/approved-internal-agent/${ApproveAgentVal.value.id}`)
    .then((res) => {
      router.visit('/admin/agents')
      toaster("success", res.data.message);
      showModalConfirm.value = false;
    })
    .catch((error) => {
      toaster("error", error.message);
      showModalConfirm.value = false;
      isLoading.value = false
    });
}
let onCancel = () => {
  showModalConfirm.value = false;
}
let updateProgress = (data) => {
  isLoading.value = true
  axios.post('/admin/progress-internal-agent', {
    id:data.user_id,
    progress:data.progress
  }).then((res)=>{
    progressModal.value = false
    router.visit('/admin/agents')
    toaster("success", res.data.message);
  }).catch((error)=>{
    isLoading.value = false
    toaster("error", error.response.data.errors);
  })

}
let dateFormat = (data) => {
    if (data) {
        let date = new Date(data)
        const day = date.getDate().toString().padStart(2, "0"); // Add leading zero if needed
        const month = (date.getMonth() + 1).toString().padStart(2, "0"); // Month is zero-based, so add 1
        const year = date.getFullYear();
        // Create the formatted date string
        return `${month}/${day}/${year}`;
    }

}
</script>
<style scoped>
.modal {
  display: flex;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  justify-content: center;
  align-items: center;

}

.modal-content {
  background-color: #fff;
  padding: 20px;
  border-radius: 4px;
}

#alert-additional-content-3 {
  width: 30%;
}
</style>
<template>
  <Head title="Agent Information" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Agents
      </h2>
    </template>

    <div class="pt-14 flex justify-between px-16">

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
                  <th scope="col" class="px-4 py-3">Sign Up Date</th>
                  <th scope="col" class="px-4 py-3">Progress</th>
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
                  <td class="text-gray-600 px-4 py-3"> <div class="flex"><span class="mr-1" v-if="agent.phone_code">{{ agent.phone_code}}</span> <span>{{ agent.phone }}</span> </div> </td>
                  <th class="text-gray-600 px-4 py-3">{{ dateFormat(agent.created_at)  }}</th>

                  <td class="text-gray-600 px-4 py-3">{{ agent.progress ? agent.progress : "-" }}</td>
                  <td class="text-gray-700 px-4 py-3 flex items-center justify-end">
                    <a title="View Agent" :href="route('admin.agent.detail', agent.id)"><svg
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5">
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
                    <button class="mr-2" title="Onboarding info"
                      v-show="agent.internal_agent_contract && agent.legacy_key === 1 "
                      @click="openContractModal(agent)"> <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                      </svg>
                    </button>

                    <button class="ml-2" @click="viewPdfData(agent)"
                      v-show="agent.internal_agent_contract && agent.legacy_key === 1 "
                      title="Contracting">
                      <svg fill="#000000" class="w-4 h-4 " version="1.1" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 237.783 237.783" xmlns:xlink="http://www.w3.org/1999/xlink"
                        enable-background="new 0 0 237.783 237.783">
                        <g>
                          <path
                            d="m42.735,50.071h96.959c3.313,0 6,2.687 6,6s-2.687,6-6,6h-96.959c-3.313,0-6-2.687-6-6s2.686-6 6-6zm0,25.934h96.959c3.313,0 6,2.687 6,6s-2.687,6-6,6h-96.959c-3.313,0-6-2.687-6-6s2.686-6 6-6zm0,25.935h96.959c3.313,0 6,2.687 6,6s-2.687,6-6,6h-96.959c-3.313,0-6-2.687-6-6s2.686-6 6-6zm0,25.935h96.959c3.313,0 6,2.687 6,6s-2.687,6-6,6h-96.959c-3.313,0-6-2.687-6-6s2.686-6 6-6z" />
                          <path
                            d="m42.735,62.071h96.959c3.313,0 6-2.687 6-6s-2.687-6-6-6h-96.959c-3.313,0-6,2.687-6,6s2.686,6 6,6z" />
                          <path
                            d="m42.735,88.005h96.959c3.313,0 6-2.687 6-6s-2.687-6-6-6h-96.959c-3.313,0-6,2.687-6,6s2.686,6 6,6z" />
                          <path
                            d="m42.735,113.94h96.959c3.313,0 6-2.687 6-6s-2.687-6-6-6h-96.959c-3.313,0-6,2.687-6,6s2.686,6 6,6z" />
                          <path
                            d="m42.735,139.875h96.959c3.313,0 6-2.687 6-6s-2.687-6-6-6h-96.959c-3.313,0-6,2.687-6,6s2.686,6 6,6z" />
                          <path
                            d="m237.783,98.361c0-1.591-0.632-3.117-1.757-4.243l-16.356-16.355c-1.125-1.125-2.651-1.757-4.243-1.757s-3.117,0.632-4.243,1.757l-28.756,28.756v-88.117c0-3.313-2.686-6-6-6h-170.428c-3.314,0-6,2.687-6,6v200.979c0,3.313 2.686,6 6,6h170.429c3.314,0 6-2.687 6-6v-63.18l53.597-53.597c1.125-1.125 1.757-2.651 1.757-4.243zm-225.783,115.02v-188.979h158.429v94.117l-35.291,35.291h-92.403c-3.313,0-6,2.687-6,6s2.687,6 6,6h80.403l-1.033,1.033c-0.777,0.777-1.326,1.753-1.586,2.821l-4.157,17.05h-25.148c-3.313,0-6,2.687-6,6s2.687,6 6,6c0,0 29.714,0 29.86,0 0.473,0 0.95-0.056 1.421-0.171l21.629-5.273c1.068-0.26 2.044-0.809 2.821-1.586l23.482-23.482v45.181h-158.427zm127.649-31.374l-10.408,2.538 2.538-10.408 83.648-83.648 7.871,7.871-83.649,83.647z" />
                        </g>
                      </svg>
                    </button>
                    <button class="ml-2" @click="agent.is_locked !== 0 ? ApproveAgent(agent) : null"
                      v-show="agent.internal_agent_contract && agent.legacy_key === 1"
                      :title="agent.is_locked === 0 ? 'Approved' : 'Approve Agent'">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" :class="{ 'text-green-400': agent.is_locked === 0 }" class="w-5 h-5 ">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                      </svg>
                    </button>
                    <button class="ml-2" @click="progressFun(agent)"
                      title="Progress"
                      >
                      <svg version="1.0" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512.000000 512.000000" preserveAspectRatio="xMidYMid meet" class="w-5 h-5 ">

                        <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)" fill="#000000"
                          stroke="none">
                          <path d="M2320 5109 c-146 -15 -259 -36 -293 -57 -74 -42 -119 -141 -103 -225
                              8 -45 50 -108 89 -134 51 -35 103 -42 198 -26 127 20 436 28 570 14 645 -68
                              1213 -419 1579 -976 74 -113 172 -319 219 -460 77 -231 111 -440 111 -687 0
                              -573 -218 -1098 -624 -1504 -351 -351 -795 -564 -1285 -615 -134 -14 -443 -6
                              -570 14 -130 21 -200 -3 -255 -87 -49 -76 -47 -167 7 -238 43 -56 80 -76 173
                              -92 225 -40 551 -45 779 -12 463 67 912 263 1260 551 514 424 831 996 927
                              1670 17 118 17 512 0 630 -70 491 -250 916 -544 1284 -142 179 -376 395 -560
                              519 -325 219 -690 360 -1083 418 -124 18 -468 26 -595 13z" />
                          <path d="M1338 4799 c-81 -19 -340 -199 -503 -350 -175 -161 -372 -397 -430
                            -513 -55 -110 -13 -230 99 -288 31 -16 53 -19 102 -16 83 5 129 37 194 133
                            152 225 363 429 613 592 143 94 174 130 184 211 12 102 -52 198 -150 227 -56
                            17 -57 17 -109 4z" />
                          <path d="M3538 3394 c-26 -11 -218 -208 -704 -722 -368 -389 -671 -707 -674
                              -707 -3 0 -128 129 -278 287 -257 271 -276 288 -325 303 -105 31 -204 -11
                              -252 -109 -31 -64 -32 -124 -2 -186 10 -19 158 -183 330 -365 285 -302 318
                              -333 380 -364 61 -31 78 -35 147 -35 69 0 86 4 147 35 64 32 106 74 784 791
                              394 417 721 770 728 784 65 142 -38 305 -191 303 -29 0 -69 -7 -90 -15z" />
                          <path d="M222 3300 c-56 -13 -110 -58 -136 -114 -44 -91 -86 -400 -86 -626 0
                              -226 42 -535 86 -626 64 -134 240 -161 341 -51 61 66 70 118 44 249 -31 158
                              -41 258 -41 428 0 170 9 268 42 432 18 95 19 113 7 155 -18 67 -78 129 -142
                              148 -55 16 -66 17 -115 5z" />
                          <path d="M505 1473 c-113 -59 -155 -180 -100 -289 58 -116 255 -352 430 -513
                              172 -159 423 -331 510 -351 93 -21 187 27 231 117 23 45 26 62 21 109 -9 87
                              -38 121 -184 217 -220 144 -425 334 -558 517 -33 45 -73 101 -90 123 -42 56
                              -104 87 -174 87 -31 -1 -69 -8 -86 -17z" />
                        </g>
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

    <Modal :show="viewModalpdf" @close="viewModalpdf = false">
      <ViewPdfindex @close="viewModalpdf = false" :userData="userData.value" />
    </Modal>

    <Modal :show="progressModal" @close="progressModal = false">
      <ProgressView @close="progressModal = false" :statuses="statuses" :isLoading="isLoading" @updateProgress="updateProgress" :userData="userData.value"  />
    </Modal>

    <Modal :show="showModal" @close="showModal = false">
      <Edit :showModal="showModal" :userDetail="userDetail" :currentPage="currentPage" @close="showModal = false"
        :callTypes="callTypes" :states="states" :route="'/admin/agent'"></Edit>
    </Modal>

    <Modal :show="agentModal" @close="agentModal = false">
      <Create :agentModal="agentModal" :currentPage="currentPage" :callTypes="callTypes" :states="states"
        @close="agentModal = false"></Create>
    </Modal>

    <ApproveConfirm @close="showModalConfirm = false" :showModalConfirm="showModalConfirm" @onApprove="onApprove"
      :isLoading="isLoading" @onCancel="onCancel" />
  </AuthenticatedLayout>
</template>

<style src="@vueform/multiselect/themes/default.css"></style>
<style >
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
