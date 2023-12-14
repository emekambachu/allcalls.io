<script setup>
import { onMounted, ref } from "vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InvitesModal from "@/Pages/InternalAgent/Invites/InvitesModal.vue";
import { toaster } from "@/helper.js";
import AgentTree from "@/Components/AgentTree.vue";
import axios from "axios";

let { agentInvites, baseUrl , agents, agentLevels} = defineProps({
  agentInvites: {
    required: true,
    type: Array,
  },
  agentLevels:Array,
  baseUrl: {
    required: true,
    type: String,
  },
  agents:Array,
});

let agentInvitesData = ref(agentInvites)
let agentsData = ref(agents)
let invitesModal = ref(false)
let reIniteAgent = ref(false)
let ReiniteAgentVal = ref(null)
let slidingLoader = ref(false)
let page = usePage();
if (page.props.flash.message) {
  toaster("success", page.props.flash.message);
}
onMounted(() => {
  copyAgentInvitationLinkIfAvailable();
  GetagentInvites()
  Getagents()
});

// Get Agent invites api  start
let GetagentInvites = () => {
  axios.get('/internal-agent/get-agent-invites')
  .then((response)=>{
    agentInvitesData.value = response.data.agentInvites
  }).catch((error)=>{
    console.log('error', error);
  })
}
// Get Agent invites api end

// get Agent invites by pagination start
let fetchagentInvites = (page) => {
  slidingLoader.value = true
  console.log('page', page);
  const urlParams = new URLSearchParams(new URL(page).search);
  const pageValue = urlParams.get('page');
  axios.get(`/internal-agent/get-agent-invites?page=${pageValue}`)
  .then((response)=>{
    agentInvitesData.value = response.data.agentInvites
    slidingLoader.value = false
  }).catch((error)=>{
    console.log('error agent',error);
    slidingLoader.value = false
  })
  
};

// get Agent invites by pagination end

// get agents api start
let Getagents = () => {
  axios.get('/internal-agent/my-agent')
  .then((response)=>{
    console.log('response.data.agents',response.data.agents);
    agentsData.value = response.data.agents
  }).catch((error)=>{
    console.log('error agent',error);
  })
}
// get agents api end

// get agent by pagination start
let fetchagents = (page) => {
  slidingLoader.value = true
  const urlParams = new URLSearchParams(new URL(page).search);
  const pageValue = urlParams.get('page');
  axios.get(`/internal-agent/my-agent?page=${pageValue}`)
  .then((response)=>{
    agentsData.value = response.data.agents
    slidingLoader.value = false
  }).catch((error)=>{
    console.log('error agent',error);
    slidingLoader.value = false
  })
};
// get agent by pagination end

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

// invite agent modal start
let user_level = ref(null)
let generateInvite = () => {
  user_level.value = page.props.auth.user_level
  invitesModal.value = true
  reIniteAgent.value = false
};
// invite agent modal end

let isLoading = ref(false)
let firstStepErrors = ref({})
// invite agent start
let inviteAgent = (data) => {
  isLoading.value = true
  axios.post("/internal-agent/agent-invites", data).then((response) => {
    invitesModal.value = false
    toaster("success", response.data.message)
    // router.visit("/internal-agent/agent-agency")
    agentInvitesData.value = response.data.agentInvites
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
// invite agent end

// delete invite start 
let deleteInvite = (agentInvite) => {
  if (window.confirm("Are you sure you want to delete this invite?")) {
    slidingLoader.value = true
    axios.delete(`/internal-agent/agent-invites/${agentInvite.id}`)
      .then((response) => {
        toaster("success", response.data.message)
        agentInvitesData.value = response.data.agentInvites
        slidingLoader.value = false
      }).catch((error) => {
        toaster("error", error.message)
      })
  } else {
    console.log("Deletion cancelled");
  }
};
// delete invite end 

// re-invite agent modal start
let reInviteAgent = (agentInvite) => {
  ReiniteAgentVal.value = agentInvite
  reIniteAgent.value = true
  invitesModal.value = true
}
// re-invite agent modal end

// re-invite agent start
let ReInviteAgentFun = () => {
  isLoading.value = true
  axios.get(`/internal-agent/reinvite-agent/${ReiniteAgentVal.value.id}`)
    .then((res) => {
      toaster("success", res.data.message)
      isLoading.value = false
      invitesModal.value = false
    }).catch((error) => {
      toaster("error", error.response.data.message)
      isLoading.value = false
    })
}
// re-invite agent en

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
let userData = ref({});
let agentTreeModal = ref(false)
let inviteAgentTree = (agent) => {
  agentTreeModal.value = true
  userData.value = agent;
}
</script>
<style scoped>
.button-custom-back {
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    font-weight: 600;
    border-width: 1px;
    align-items: center;
    display: inline-flex;
    border-color: rgb(107 114 128 / var(--tw-border-opacity));
}

.button-custom-back:hover {
    background-color: #03243d;
    color: #3cfa7a;
    transition-duration: 150ms;
}
</style>
<template>
  <Head title="Agent Invites" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
       Invites
      </h2>
    </template>
    <vue-loader :slidingLoader="slidingLoader" />
    <div class="pt-14">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 rounded-lg bg-white">
          <div class="flex justify-between">
            <div>
              <h1 class="text-4xl text-custom-sky font-bold mb-6">
               Invites
              </h1>
            </div>
            <div>
              <PrimaryButton :disabled="!$page.props.auth.user.upline_id && !$page.props.auth.user.level_id"  @click="generateInvite">Invite Agent</PrimaryButton>
            </div>
          </div>
          <hr class="mb-4" />
          <div v-if="agentInvitesData.data.length">
            <table class="w-full text-sm text-left text-gray-400">
              <thead class="text-xs text-gray-300 uppercase bg-sky-900">
                <tr>
                  <th scope="col" class="px-4 py-3">ID</th>
                  <th scope="col" class="px-4 py-3">Upline ID</th>
                  <th scope="col" class="px-4 py-3">Email</th>
                  <th scope="col" class="px-4 py-3">Level</th>
                  <th scope="col" class="px-4 py-3">URL</th>
                  <th scope="col" class="px-4 py-3">Status</th>
                  <th scope="col" class="px-4 py-3 text-center">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr class="border-b border-gray-500" v-for="(agentInvite, index) in agentInvitesData.data"
                  :key="agentInvite.id">
                  <td class="text-gray-600 px-4 py-3" v-text="agentInvite.id"></td>
                  <td class="text-gray-600 px-4 py-3" v-text="agentInvite?.upline_id"></td>
                  <td class="text-gray-600 px-4 py-3" v-text="agentInvite.email"></td>
                  <td class="text-gray-600 px-4 py-3" v-text="agentInvite?.get_agent_level?.name"></td>
                  <td class="text-gray-600 px-4 py-3">
                    <a class="text-blue-500 hover:text-blue-700 hover:underline" target="_blank" :href="agentInvite.url"
                      v-text="agentInvite.url
                        "></a>
                  </td>
                  <td class="text-gray-600 px-4 py-3">
                    <span v-if="agentInvite.used"
                      class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">
                      Used
                    </span>
                    <span v-else
                      class="bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">
                      Not Used
                    </span>
                  </td>
                  <td class="text-gray-600 px-4 py-3 flex justify-center">
                    <button @click.prevent="reInviteAgent(agentInvite)" title="Re-invite">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-liejoin="round"
                          d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                      </svg>
                    </button>
                    <button @click.prevent="deleteInvite(agentInvite)" title="delete" class="ml-2">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4 text-red-600 mr-1">
                        <path stroke-linecap="round" stroke-linejoin="round"
                          d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                      </svg>
                    </button>

                  </td>
                </tr>
              </tbody>
            </table>
            <nav class="flex justify-between my-4" v-if="agentInvitesData.links">
              <div v-if="agentInvitesData">
                <span class="text-sm text-gray-700">
                  Showing
                  <span class="font-semibold text-gray-900">{{ agentInvitesData.from }}</span>
                  to
                  <span class="font-semibold text-gray-900">{{ agentInvitesData.to }}</span> of
                  <span class="font-semibold text-gray-900">{{ agentInvitesData.total }}</span>
                  Entries
                </span>
              </div>

              <ul class="inline-flex -space-x-px text-base h-10">
                <li v-for="(link, index) in agentInvitesData.links" :key="link.label"
                  :class="{ disabled: link.url === null }">
                  <a href="#" @click.prevent="fetchagentInvites(link.url)" :class="[
                    'flex items-center justify-center px-4 h-10 border border-gray-300',
                    link.active
                      ? 'text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-700'
                      : 'leading-tight text-gray-500 bg-white hover:bg-gray-100 hover:text-gray-700',
                    {
                      'rounded-l-lg': index === 0,
                      'rounded-r-lg': index === agentInvitesData.links.length - 1,
                    },
                  ]" v-html="link.label"></a>
                </li>
              </ul>
            </nav>
            <br>
            
          </div>


          <div v-else class="text-center py-4 text-gray-800">
            No invites yet.
          </div>
        </div>
      </div>
    </div>


    <div class="pt-0 flex justify-between px-16">
        <div class="text-4xl text-custom-sky font-bold mb-6">My Agents</div>
        <button class="button-custom-back px-4 py-3 rounded-md ml-2 mb-2" @click="inviteAgentTree(page.props.auth.user)">View Complete Tree</button>     
    </div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
      <div class="px-4 sm:px-8 sm:rounded-lg">
        <hr class="mb-4" />
      </div>
    </div>


    <section v-if="agentsData.data.length" class="p-3">
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
                  <th scope="col" class="px-4 py-3" style="min-width: 100px;"  >Hierarchy</th>
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
                  v-for="agent in agentsData.data"
                  :key="agent.id"
                  class="border-b border-gray-500"
                >
                
                  <td class="text-gray-600 px-4 py-3">{{ agent.id }}</td>
                  <td class="text-gray-600 px-4 py-3">{{ agent.first_name }}</td>
                  <td class="text-gray-600 px-4 py-3">{{ agent.last_name }}</td>
                  <th class="text-gray-600 px-4 py-3">{{ agent.get_agent_level?.name }}</th>
                  <th class="text-gray-600 px-4 py-3">{{ agent.upline_id }}</th>
                  <th class="text-gray-600 px-4 py-3">
                    <a class="text-blue-500 cursor-pointer" v-if="agent.invitees_count > 0" title="View Invites" @click="inviteAgentTree(agent)">
                      View Tree
                    </a>
                  <span v-else>-</span>
                  </th>
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

            <nav class="flex justify-between my-4" v-if="agentsData.links">
              <div v-if="agentsData">
                <span class="text-sm text-gray-700">
                  Showing
                  <span class="font-semibold text-gray-900">{{ agentsData.from }}</span>
                  to
                  <span class="font-semibold text-gray-900">{{ agentsData.to }}</span> of
                  <span class="font-semibold text-gray-900">{{ agentsData.total }}</span>
                  Entries
                </span>
              </div>

              <ul class="inline-flex -space-x-px text-base h-10">
                <li v-for="(link, index) in agentsData.links" :key="link.label"
                  :class="{ disabled: link.url === null }">
                  <a href="#" @click.prevent="fetchagents(link.url)" :class="[
                    'flex items-center justify-center px-4 h-10 border border-gray-300',
                    link.active
                      ? 'text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-700'
                      : 'leading-tight text-gray-500 bg-white hover:bg-gray-100 hover:text-gray-700',
                    {
                      'rounded-l-lg': index === 0,
                      'rounded-r-lg': index === agentsData.links.length - 1,
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
      <p class="text-center text-gray-600">No Agents yet.</p>
    </section>

    <AgentTree v-if="agentTreeModal" :treeRoute="'/internal-agent/agent/tree/'"  :agentTreeModal="agentTreeModal" @close="agentTreeModal = false" :userData="userData" />

    <InvitesModal v-if="invitesModal" @close="invitesModal = false" :isLoading="isLoading" @inviteAgent="inviteAgent"
      :firstStepErrors="firstStepErrors" :user_level="user_level" :agentLevels="agentLevels" :invitesModal="invitesModal" @ReinviteAgent="ReInviteAgentFun"
      :reIniteAgent="reIniteAgent" />
  </AuthenticatedLayout></template>
