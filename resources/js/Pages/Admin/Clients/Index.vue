<script setup>
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ClientModal from "@/Components/ClientModal.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import { toaster } from "@/helper.js";
import Modal from "@/Components/Modal.vue";
import ClientSearchFilter from "@/Components/ClientSearchFilter.vue";
let page = usePage();
if (page.props.flash.message) {
  toaster("success", page.props.flash.message);
}

let props = defineProps({
  Clients: {
    type: Object,
  },

  totalClients: {
    type: Number,
  },
  states: Array,
  allClients: Array,
  requestData: Array,
});
console.log('props', props.Clients);

let paginate = (url) => {
  router.visit(url);
};

let showModal = ref(false);
let ClientDetail = ref(null);
let editScreen = ref(false);

let openClientModal = (Client) => {
  editScreen.value = false
  ClientDetail.value = Client;
  showModal.value = true;
};
let EditClientModal = (Client) => {
  ClientDetail.value = Client;
  showModal.value = true;
  editScreen.value = true
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
  <Head title="Client Information" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
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

    <div class="mx-auto px-4 sm:px-8 md:px-16 pb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div class="max-w-sm p-6 bg-custom-darksky rounded-lg shadow overflow-auto">
        <p class="mb-1 text-sm text-gray-300">Total Clients</p>
        <h2 class="mb-2 text-2xl sm:text-3xl lg:text-4xl font-bold text-white">
          {{ totalClients }}
        </h2>
      </div>
    </div>
    <div class="px-16">
      <hr class="mb-4" />
    </div>

    <ClientSearchFilter :allClients="allClients" :requestData="requestData" :route="'/admin/clients'" />

    <section v-if="Clients.data.length" class="p-3">
      <div class="mx-auto max-w-screen-xl sm:px-12">
        <div class="relative sm:rounded-lg overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-400">
              <thead class="text-xs text-gray-300 uppercase bg-sky-900">
                <tr>
                  <th scope="col" class="px-4 py-3">ID</th>
                  <th scope="col" class="px-4 py-3">First Name</th>
                  <th scope="col" class="px-4 py-3">Last Name</th>
                  <th scope="col" class="px-4 py-3">URL</th>
                  <th scope="col" class="px-4 py-3">Locked Status</th>
                  <th scope="col" class="px-4 py-3">Status</th>
                  <th scope="col" class="px-4 py-3 text-center">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="Client in Clients.data" :key="Client.id" class="border-b border-gray-500">
                  <td class="text-gray-600 px-4 py-3">{{ Client.id }}</td>
                  <td class="text-gray-600 px-4 py-3">{{ Client.first_name }}</td>
                  <td class="text-gray-600 px-4 py-3">{{ Client.last_name }}</td>
                  <td class="text-gray-600">
                    <a v-if="Client?.call?.recording_url" target="_blank" :href="Client?.call?.recording_url"
                      class="flex"><svg xmlns="http://www.w3.org/2000/svg" height="1.5em" class="pr-1"
                        viewBox="0 0 512 512">
                        <path
                          d="M0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm256-96a96 96 0 1 1 0 192 96 96 0 1 1 0-192zm0 224a128 128 0 1 0 0-256 128 128 0 1 0 0 256zm0-96a32 32 0 1 0 0-64 32 32 0 1 0 0 64z" />
                      </svg>Open Recording
                    </a>
                    <a class="text-center" v-else>_</a>
                  </td>
                  <td class="text-gray-600 px-4 py-3">
                    <div class="flex gap-2">
                      <div v-if="Client.unlocked"
                        class="relative grid items-center px-2 py-1 font-sans text-xs font-bold text-green-900 uppercase rounded-md select-none whitespace-nowrap bg-green-500/20">
                        <div class="absolute w-4 h-4 top-2/4 left-1 -translate-y-2/4">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                              d="M13.5 10.5V6.75a4.5 4.5 0 1 1 9 0v3.75M3.75 21.75h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H3.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                          </svg>
                        </div>
                        <span class="ml-5">Unlocked</span>
                      </div>
                      <div v-if="!Client.unlocked"
                        class="relative grid items-center px-2 py-1 font-sans text-xs font-bold text-red-900 uppercase rounded-md select-none whitespace-nowrap bg-red-500/20">
                        <div class="absolute w-4 h-4 top-2/4 left-1 -translate-y-2/4">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                              d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                          </svg>
                        </div>
                        <span class="ml-5">Locked</span>
                      </div>
                    </div>
                  </td>
                  <td class="text-gray-600 px-4 py-3">
                    <span v-if="['Sale - Simplified Issue', 'Sale - Guaranteed Issue'].includes(
                      Client.status
                    )
                      " class="bg-green-600 text-white text-xs px-2 py-1 rounded-2xl">{{ Client.status }}</span>
                    <span v-else-if="Client.status == 'Follow Up Needed'"
                      class="bg-yellow-600 text-white text-xs px-2 py-1 rounded-2xl">Follow Up Needed</span>
                    <span v-else-if="Client.status" class="bg-red-600 text-white text-xs px-2 py-1 rounded-2xl">{{
                      Client.status }}</span>
                    <span v-else>-</span>
                  </td>

                  <td class="text-gray-700 px-4 py-3 flex items-center justify-center">
                    <button title="View Client" @click="openClientModal(Client)"
                      class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none"
                      type="button">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                          d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                      </svg>

                    </button>
                    <button title="View Client" @click="EditClientModal(Client)"
                      class="inline-flex items-center ml-2 p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none"
                      type="button">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                          d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                      </svg>
                    </button>
                    <!-- <button v-else class="text-center" type="button">-</button> -->
                  </td>
                </tr>
              </tbody>
            </table>
            <nav class="flex justify-between my-4" v-if="Clients.links">
              <div v-if="Clients">
                <span class="text-sm text-gray-700">
                  Showing
                  <span class="font-semibold text-gray-900">{{ Clients.from }}</span>
                  to
                  <span class="font-semibold text-gray-900">{{ Clients.to }}</span> of
                  <span class="font-semibold text-gray-900">{{ Clients.total }}</span>
                  Entries
                </span>
              </div>

              <ul class="inline-flex -space-x-px text-base h-10">
                <li v-for="(link, index) in Clients.links" :key="link.label" :class="{ disabled: link.url === null }">
                  <a href="#" @click.prevent="paginate(link.url)" :class="[
                    'flex items-center justify-center px-4 h-10 border border-gray-300',
                    link.active
                      ? 'text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-700'
                      : 'leading-tight text-gray-500 bg-white hover:bg-gray-100 hover:text-gray-700',
                    {
                      'rounded-l-lg': index === 0,
                      'rounded-r-lg': index === Clients.links.length - 1,
                    },
                  ]" v-html="link.label"></a>
                </li>
              </ul>
            </nav>
            <br />
          </div>
        </div>
      </div>
    </section>

    <section v-else class="p-3">
      <p class="text-center text-gray-600">No clients yet.</p>
    </section>
    
    <Modal :show="showModal" @close="showModal = false">
      <ClientModal :showModal="showModal" :editScreen="editScreen" :route="'/admin/clients'" :ClientDetail="ClientDetail" :states="states"
        @close="showModal = false">
      </ClientModal>
    </Modal>
  </AuthenticatedLayout>
</template>
