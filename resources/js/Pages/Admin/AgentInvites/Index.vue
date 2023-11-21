<script setup>
import { onMounted, ref } from "vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InvitesModal from "@/Pages/Admin/AgentInvites/InvitesModal.vue";
import { toaster } from "@/helper.js";
import axios from "axios";

let { agentInvites, baseUrl } = defineProps({
  agentInvites: {
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
  axios.post("/admin/agent-invites", data).then((res) => {
    invitesModal.value = false
    toaster("success", res.data.message)
    router.visit("/admin/agent-invites")
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
    axios.delete(`/admin/agent-invites/${agentInvite.id}`)
      .then((res) => {
        toaster("success", res.data.message)
        router.visit('/admin/agent-invites')
      }).catch((error) => {
        toaster("error", error.message)
      })
    // router.visit(`/admin/agent-invites/${agentInvite.id}`, {
    //   method: "DELETE",
    // })
  } else {
    console.log("Deletion cancelled");
  }
};
let fetchagentInvites = (page) => {
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
  axios.get(`/admin/reinvite-agent/${ReiniteAgentVal.value.id}`)
    .then((res) => {
      toaster("success", res.data.message)
      isLoading.value = false
      invitesModal.value = false
      router.visit('/admin/agent-invites')
    }).catch((error) => {
      toaster("error", error.response.data.message)
      isLoading.value = false
    })
}
</script>

<template>
  <Head title="Agent Invites" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Agent Invites
      </h2>
    </template>

    <div class="py-14">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 rounded-lg bg-white">
          <div class="flex justify-between">
            <div>
              <h1 class="text-4xl text-custom-sky font-bold mb-6">
                Agent Invites
              </h1>
            </div>
            <div>
              <PrimaryButton @click="generateInvite">Add New</PrimaryButton>
            </div>
          </div>
          <hr class="mb-4" />
          <div v-if="agentInvites.data.length">
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
                <tr class="border-b border-gray-500" v-for="(agentInvite, index) in agentInvites.data"
                  :key="agentInvite.id">
                  <td class="text-gray-600 px-4 py-3" v-text="index + 1"></td>
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
            <div class="p-4">
              <nav
                class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4"
                aria-label="Table navigation">
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                  Showing
                  <span class="font-semibold text-custom-blue">{{
                    agentInvites.current_page
                  }}</span>
                  of
                  <span class="font-semibold text-custom-blue">{{
                    agentInvites.last_page
                  }}</span>
                </span>
                <ul class="inline-flex items-stretch -space-x-px cursor-pointer">
                  <li>
                    <a v-if="agentInvites.prev_page_url" @click="fetchagentInvites(agentInvites.prev_page_url)"
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
                        agentInvites.current_page }}
                    </a>
                  </li>

                  <li>
                    <a v-if="agentInvites.next_page_url" @click="fetchagentInvites(agentInvites.next_page_url)"
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


          <div v-else class="text-center py-4 text-gray-800">
            No invites yet.
          </div>
        </div>
      </div>
    </div>
    <InvitesModal @close="invitesModal = false" :isLoading="isLoading" @inviteAgent="inviteAgent"
      :firstStepErrors="firstStepErrors" :agentLevels="agentLevels" :invitesModal="invitesModal" @ReinviteAgent="ReInviteAgentFun"
      :reIniteAgent="reIniteAgent" />
  </AuthenticatedLayout></template>
