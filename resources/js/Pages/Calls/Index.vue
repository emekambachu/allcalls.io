<script setup>
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ClientDetailsModal from "@/Components/ClientDetailsModal.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import { toaster } from "@/helper.js";
import Modal from "@/Components/Modal.vue";
import moment from "moment-timezone";
import PrimaryButton from "@/Components/PrimaryButton.vue";
let page = usePage();
if (page.props.flash.message) {
  toaster("success", page.props.flash.message);
}
let timezone = "UTC";
onMounted(() => {
  timezone = document.querySelector("meta[name='user-timezone']").getAttribute("content");
});

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
  states: Array,
});

let paginate = (url) => {
  router.visit(url);
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

let disposition = ref('Select a disposition')
let showDispositionModal = ref(false)
let call_id = ref(null)
let colIndex = ref(null)
let addDisposition = (call , index) => {
  firstStepErrors.value.disposition = [``];
  showDispositionModal.value = true
  call_id.value = call.id
  colIndex.value = index
  if(call?.get_client?.status){
    disposition.value = call?.get_client?.status
  }else{
    disposition.value = 'Select a disposition'
  }
}
let firstStepErrors = ref({})
let disposition_loader = ref(false)
let saveChanges = () => {
  firstStepErrors.value.disposition = [``];
  disposition_loader.value = true
  if(!disposition.value || disposition.value == 'Select a disposition' ){
    console.log('error');
    firstStepErrors.value.disposition = [`The disposition is required.`];
    disposition_loader.value = false
    console.log('firstStepErrors.value',firstStepErrors.value);
    return
  }
  axios.post('/internal-agent/calls/disposition',{
    disposition:disposition.value,
    call_id:call_id.value
  })
  .then((res)=>{
    console.log('res',res);
    toaster("success", res.data.message);
    // router.visit('/admin/calls')
    disposition_loader.value = false
    showDispositionModal.value = false
    if(props.calls.data[colIndex.value].get_client){
      props.calls.data[colIndex.value].get_client.status = res.data.call?.client.status;
    }
    // console.log('props.calls[colIndex.value]',props.calls[colIndex.value]);
    // console.log('loadedCalls.value[colIndex.value]',loadedCalls.value[colIndex.value].disposition);
  }).catch((error)=>{
    if(error.response.status == 400){
      toaster("error", error.response.data.errors);
    }else{
      toaster("error", error.message);
    }
    disposition_loader.value = false
  })
}
</script>
<template>
  <Head title="Client Information" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Calls
      </h2>
    </template>

    <div class="pt-14">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="px-4 sm:px-8 sm:rounded-lg">
          <div class="text-4xl text-custom-sky font-bold mb-6">Calls</div>
          <hr class="mb-4" />
        </div>
      </div>
    </div>

    <Modal
          :show="showDispositionModal"
          @close="showDispositionModal = false"
          :closeable="true"
      >
          <div class="bg-gray-100 py-4 px-6 text-gray-900">
              <div class="flex justify-between mb-6">
                  <h3 class="text-2xl font-bold">Update  Disposition</h3>
                  <span class="cursor-pointer" @click.prevent="showDispositionModal = false">&#x2715</span>
              </div>

              <div class="mb-3">
                  <label class="block mb-2 text-sm font-medium text-gray-900">Disposition:</label>

                  <select v-model="disposition" class="select-custom border border-gray-200">
                      <!-- <option v-for="(filter, index) in filters" :key="index" :value="filter.name">{{ filter.label }}</option> -->
                      <option disabled value="Select a disposition">Select a disposition</option>
                      <option value="Sale - Simplified Issue">Sale - Simplified Issue</option>
                      <option value="Sale - Guaranteed Issue">Sale - Guaranteed Issue</option>
                      <option value="Follow Up Needed">Follow Up Needed</option>
                      <option value="Quoted - Not Interested">Quoted - Not Interested</option>
                      <option value="Not Interested">Not Interested</option>
                      <option value="Transfer Handoff Too Long">Transfer Handoff Too Long</option>
                      <option value="Client Hung Up">Client Hung Up</option>
                      <option value="No Income">No Income</option>
                      <option value="Wrong State">Wrong State</option>
                      <option value="Not Qualified Age">Not Qualified Age</option>
                      <option value="Not Qualified Nursing Home">Not Qualified Nursing Home</option>
                      <option value="Not Qualified Memory Issues">Not Qualified Memory Issues</option>
                      <option value="Language Barrier">Language Barrier</option>
                      <option value="Do Not Call">Do Not Call</option>
                      <option value="Dead Air/No Response">Dead Air/No Response</option>
                      <option value="Thought Was Free">Thought Was Free</option>
                  </select>
                  <div v-if="firstStepErrors.disposition" class="text-red-500 mt-1" v-text="firstStepErrors.disposition[0]"></div>

              </div>


              <div class="flex items-center justify-end mt-4">
                  <PrimaryButton 
                    :disabled=" disposition_loader" class="mr-2" @click.prevent="saveChanges"><global-spinner :spinner="disposition_loader" /> Save Changes</PrimaryButton>

                  <button
                      class="inline-flex items-center px-4 py-3 border rounded-md font-semibold text-md uppercase tracking-widest transition ease-in-out duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 hover:bg-white hover:text-custom-blue"
                      :class="{
              'border-transparent text-gray-900 bg-gray-100 hover:drop-shadow-2xl ': true,
            }"
                      :disabled="disabled"
                      @click.prevent="showDispositionModal = false"
                  >
                      Cancel
                  </button>
              </div>
          </div>
    </Modal>

    <div
      class="mx-auto px-4 sm:px-8 md:px-16 py-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"
    >
      <div class="max-w-sm p-6 bg-custom-darksky rounded-lg shadow overflow-auto">
        <p class="mb-1 text-sm text-gray-300">Total Calls</p>
        <h2 class="mb-2 text-2xl sm:text-3xl lg:text-4xl font-bold text-white">
          {{ totalCalls }}
        </h2>
      </div>
      <div class="max-w-sm p-6 bg-custom-darksky rounded-lg shadow overflow-auto">
        <p class="mb-1 text-sm text-gray-300">Total Spent</p>
        <h2 class="mb-2 text-2xl sm:text-3xl lg:text-4xl font-bold text-white">
          ${{ formatMoney(totalAmountSpent) }}
        </h2>
      </div>
      <div class="max-w-sm p-6 bg-custom-darksky rounded-lg shadow overflow-auto">
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
            <table class="w-full text-sm text-left text-gray-400 table-responsive">
              <thead class="text-xs text-gray-300 uppercase bg-sky-900">
                <tr>
                  <th scope="col" class="px-4 py-3">ID</th>
                  <th scope="col" class="px-4 py-3">HANG UP BY</th>
                  <th scope="col" class="px-4 py-3">CALL DURATION</th>
                  <th scope="col" class="px-4 py-3" style="min-width: 175px">
                    CALL TAKEN
                  </th>
                  <th scope="col" class="px-4 py-3">AMOUNT SPENT</th>
                  <th scope="col" class="px-4 py-3" style="min-width: 130px">
                    CALL TYPE
                  </th>
                  <th scope="col" class="px-4 py-3" style="min-width: 100px">
                    CALLER ID
                  </th>
                  <th scope="col" class="px-4 py-3" style="min-width: 100px">
                    DISPOSITION
                  </th>
                  <th scope="col" class="px-4 py-3" style="min-width: 175px">URL</th>
                  <th  scope="col" class="px-4 py-3 text-end" style="min-width: 150px;">
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(call , index) in calls.data"
                  :key="call.id"
                  class="border-b border-gray-500"
                >
                  <td class="text-gray-600 px-4 py-3">{{ call.id }}</td>
                  <td class="text-gray-600 px-4 py-3">{{ call.hung_up_by }}</td>
                  <td class="text-gray-600 px-4 py-3">
                    {{
                      String(Math.floor(call.call_duration_in_seconds / 60)).padStart(
                        2,
                        "0"
                      ) +
                      ":" +
                      String(call.call_duration_in_seconds % 60).padStart(2, "0")
                    }}
                  </td>
                  <th class="text-gray-600 px-4 py-3">{{ call.call_taken }}</th>
                  <td class="text-gray-600 px-4 py-3">{{ call.amount_spent }}</td>
                  <td class="text-gray-600 px-4 py-3">{{ call.call_type.type }}</td>
                  <td class="text-gray-600 px-4 py-3">{{ call.from }}</td>
                  <td class="text-gray-600 px-4 py-3">
                    {{ call?.get_client?.status }}
                    <!-- <span v-if="call.get_client">{{
                      call.get_client.status
                    }}</span>
                    <span v-else>N/A</span> -->
                  </td>
                  <td class="text-gray-600 px-4 py-3">
                    <a
                      v-if="call.recording_url"
                      target="_blank"
                      :href="call.recording_url"
                      class="flex"
                      ><svg
                        xmlns="http://www.w3.org/2000/svg"
                        height="1.5em"
                        class="pr-1"
                        viewBox="0 0 512 512"
                      >
                        <!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                        <path
                          d="M0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm256-96a96 96 0 1 1 0 192 96 96 0 1 1 0-192zm0 224a128 128 0 1 0 0-256 128 128 0 1 0 0 256zm0-96a32 32 0 1 0 0-64 32 32 0 1 0 0 64z"
                        /></svg
                      >Open Recording
                    </a>
                    <span v-else>_</span>
                  </td>
                  <td class="text-gray-700 px-4 py-3 flex items-center justify-end">
                    <button
                      v-if="call.get_client && call.get_client.unlocked"
                      @click="openClientModal(call)"
                      class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none"
                      type="button"
                    >
                      View Client
                    </button>
                    <button v-else class="text-center" type="button">-</button>
                    <button >

                      <svg @click.prevent="addDisposition(call , index)" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 cursor-pointer text-gray-800 hover:text-gray-900 ml-3">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                    </button>
                   
                  </td>
                </tr>
              </tbody>
            </table>

            <nav class="flex justify-between my-4" v-if="calls.links">
              <div v-if="calls">
                <span class="text-sm text-gray-700">
                  Showing
                  <span class="font-semibold text-gray-900">{{ calls.from }}</span>
                  to
                  <span class="font-semibold text-gray-900">{{ calls.to }}</span> of
                  <span class="font-semibold text-gray-900">{{ calls.total }}</span>
                  Entries
                </span>
              </div>

              <ul class="inline-flex -space-x-px text-base h-10">
                <li
                  v-for="(link, index) in calls.links"
                  :key="link.label"
                  :class="{ disabled: link.url === null }"
                >
                  <a
                    href="#"
                    @click.prevent="paginate(link.url)"
                    :class="[
                      'flex items-center justify-center px-4 h-10 border border-gray-300',
                      link.active
                        ? 'text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-700'
                        : 'leading-tight text-gray-500 bg-white hover:bg-gray-100 hover:text-gray-700',
                      {
                        'rounded-l-lg': index === 0,
                        'rounded-r-lg': index === calls.links.length - 1,
                      },
                    ]"
                    v-html="link.label"
                  ></a>
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
      <ClientDetailsModal
        :showModal="showModal"
        :callDetail="callDetail"
        :states="states"
        @close="showModal = false"
      ></ClientDetailsModal>
    </Modal>
  </AuthenticatedLayout>
</template>
