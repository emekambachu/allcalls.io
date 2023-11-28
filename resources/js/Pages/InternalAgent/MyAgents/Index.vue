<script setup>
import { onMounted, ref } from "vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InvitesModal from "@/Pages/InternalAgent/MyAgents/InvitesModal.vue";
import { toaster } from "@/helper.js";
import axios from "axios";

let { agents, baseUrl } = defineProps({
  agents: {
    required: true,
    type: Array,
  },
  agentLevels:Array,
  baseUrl: {
    required: true,
    type: String,
  },
});

let invitesModal = ref(false)
let reIniteAgent = ref(false)
let ReiniteAgentVal = ref(null)

let page = usePage();
if (page.props.flash.message) {
  toaster("success", page.props.flash.message);
}
let add_agent_button = ref(true)
if(page.props.auth.user.upline_id && page.props.auth.user.level_id){
  add_agent_button.value = false
}
console.log('user', page.props.auth.user);
onMounted(() => {
  copyAgentInvitationLinkIfAvailable();
});

let copyAgentInvitationLinkIfAvailable = () => {
  if (page.props.flash.agentInvitationLink) {
    navigator.clipboard
      .writeText(page.props.flash.agentInvitationLink)
      .then(function () {
        toaster("success", "Invitation link copied to clipboard.");
      })
      .catch(function (err) {
        console.error("Could not copy text to clipboard: ", err);
      });
  }
};
let generateInvite = () => {
  invitesModal.value = true
  reIniteAgent.value = false
};
let isLoading = ref(false)
let firstStepErrors = ref({})
let inviteAgent = (data) => {
  isLoading.value = true
  axios.post("/internal-agent/agent-invites", data).then((res) => {
    invitesModal.value = false
    toaster("success", res.data.message)
    router.visit("/internal-agent/agent-agency")
    isLoading.value = false
  }).catch((error) => {
    console.log('error', error);
    isLoading.value = false
    if (error.response.status === 400) {
      firstStepErrors.value = error.response.data.errors;
    }else{
      firstStepErrors.value = error
    }
    
  })
}
let deleteInvite = (agentInvite) => {
  if (window.confirm("Are you sure you want to delete this invite?")) {
    axios.delete(`/internal-agent/agent-invites/${agentInvite.id}`)
      .then((res) => {
        toaster("success", res.data.message)
        router.visit('/internal-agent/agent-agency')
      }).catch((error) => {
        toaster("error", error.message)
      })
    // router.visit(`/internal-agent/agent-invites/${agentInvite.id}`, {
    //   method: "DELETE",
    // })
  } else {
    console.log("Deletion cancelled");
  }
};
let fetchagents = (page) => {
  let url = new URL(page);
  if (url.protocol !== "https:") {
    url.protocol = "https:";
  }
  let httpsPage = url.toString();
  router.visit(httpsPage, { method: "get" });
};

let reInviteAgent = (agentInvite) => {
  ReiniteAgentVal.value = agentInvite
  reIniteAgent.value = true
  invitesModal.value = true
}
let ReInviteAgentFun = () => {
  isLoading.value = true
  axios.get(`/internal-agent/reinvite-agent/${ReiniteAgentVal.value.id}`)
    .then((res) => {
      toaster("success", res.data.message)
      isLoading.value = false
      invitesModal.value = false
      router.visit('/internal-agent/agent-invites')
    }).catch((error) => {
      toaster("error", error.response.data.message)
      isLoading.value = false
    })
}
let formatMoney = (amount) => {
  return parseFloat(amount)
    .toFixed(2)
    .replace(/\d(?=(\d{3})+\.)/g, "$&,");
};
let dateFormat = (data) => {
  if (data) {
    let date = new Date(data);
    const day = date.getDate().toString().padStart(2, "0"); // Add leading zero if needed
    const month = (date.getMonth() + 1).toString().padStart(2, "0"); // Month is zero-based, so add 1
    const year = date.getFullYear();
    // Create the formatted date string
    return `${month}/${day}/${year}`;
  }
};
</script>

<template>
  <Head title="My Agent" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        My Agents
      </h2>
    </template>

    <div class="pt-14 flex justify-between px-16">
      <div>
        <div class="text-4xl text-custom-sky font-bold mb-6">My Agents</div>
      </div>
     
    </div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
      <div class="px-4 sm:px-8 sm:rounded-lg">
        <hr class="mb-4" />
      </div>
    </div>
    

    <section v-if="agents.data.length" class="p-3">
      <div class="mx-auto max-w-screen-xl sm:px-12">
        <div class="relative sm:rounded-lg overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-400">
              <thead class="text-xs text-gray-300 uppercase bg-sky-900">
                <tr>
                  <th scope="col" class="px-4 py-3">ID</th>
                  <th scope="col" style="min-width: 110px;" class="px-4 py-3">First Name</th>
                  <th scope="col" style="min-width: 110px;" class="px-4 py-3">Last Name</th>
                  <th scope="col" style="min-width: 110px;" class="px-4 py-3">Level</th>
                  <th scope="col" style="min-width: 110px;" class="px-4 py-3">Upline</th>
                  <th scope="col" class="px-4 py-3">Email</th>
                  <th scope="col" class="px-4 py-3">Balance</th>
                  <th scope="col" class="px-4 py-3">Phone</th>
                  <th scope="col" style="min-width: 115px;" class="px-4 py-3">Sign Up Date</th>
                  <th scope="col"  class="px-4 py-3">Progress</th>
                  <!-- <th scope="col" class="px-4 py-3 text-end">Actions</th> -->
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="agent in agents.data"
                  :key="agent.id"
                  class="border-b border-gray-500"
                >
                  <td class="text-gray-600 px-4 py-3">{{ agent.id }}</td>
                  <td class="text-gray-600 px-4 py-3">{{ agent.first_name }}</td>
                  <td class="text-gray-600 px-4 py-3">{{ agent.last_name }}</td>
                  <th class="text-gray-600 px-4 py-3">{{ agent.get_agent_level?.name }}</th>
                  <th class="text-gray-600 px-4 py-3">{{ agent.upline_id }}</th>
                  <th class="text-gray-600 px-4 py-3">{{ agent.email }}</th>
                  <td class="text-gray-600 px-4 py-3">
                    ${{ formatMoney(agent.balance) }}
                  </td>
                  <td class="text-gray-600 px-4 py-3">
                    <div class="flex">
                      <span class="mr-1" v-if="agent.phone_code">{{
                        agent.phone_code
                      }}</span>
                      <span>{{ agent.phone }}</span>
                    </div>
                  </td>
                  <th class="text-gray-600 px-4 py-3">
                    {{ dateFormat(agent.created_at) }}
                  </th>

                  <td class="text-gray-600 px-4 py-3">
                    {{ agent.progress ? agent.progress : "-" }}
                  </td>
                  <!-- <td class="text-gray-700 px-4 py-3 flex items-center justify-end">
                  </td> -->
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
                    agents.current_page
                  }}</span>
                  of
                  <span class="font-semibold text-custom-blue">{{
                    agents.last_page
                  }}</span>
                </span>
                <ul class="inline-flex items-stretch -space-x-px cursor-pointer">
                  <li>
                    <a
                      v-if="agents.prev_page_url"
                      @click="fetchAgents(agents.prev_page_url)"
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
                      >{{ agents.current_page }}
                    </a>
                  </li>

                  <li>
                    <a
                      v-if="agents.next_page_url"
                      @click="fetchAgents(agents.next_page_url)"
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
      <p class="text-center text-gray-600">No Agents yet.</p>
    </section>
    <InvitesModal @close="invitesModal = false" :isLoading="isLoading" @inviteAgent="inviteAgent"
      :firstStepErrors="firstStepErrors" :agentLevels="agentLevels" :invitesModal="invitesModal" @ReinviteAgent="ReInviteAgentFun"
      :reIniteAgent="reIniteAgent" />
  </AuthenticatedLayout></template>
