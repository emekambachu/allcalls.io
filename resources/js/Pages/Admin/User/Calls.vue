<script setup>
import { ref, reactive, defineEmits, onMounted, watch } from "vue";
import TextInput from "@/Components/TextInput.vue";
import { router } from "@inertiajs/vue3";
import ClientDetailsModal from "@/Components/ClientDetailsModal.vue";
import Modal from "@/Components/Modal.vue";
let props = defineProps({
  showCallsModal: {
    type: Boolean,
  },

  detailType: {
    type: String,
  },
  user: Object,
});
let states = ref(null)
let calls = ref([]);
onMounted(() => {
  Fetehcalls(props.user.id);
});
let slidingLoader = ref(false);
let Fetehcalls = async (id) => {
  slidingLoader.value = true;
  try {
    const response = await axios.get(`/admin/customer/calls/${id}`);
    const data = response.data; // Assuming your API response provides relevant data
    // console.log('what is data', data.calls);
    calls.value = data.calls;
    slidingLoader.value = false;
    states.value = data.states
  } catch (error) {
    console.error(error);
  }
};
let fetchcallsBypage = async (page) => {
  if (page) {
    slidingLoader.value = true;
    let url = new URL(page);
    try {
      const response = await axios.get(
        `/admin/customer/calls/${props.user.id}${url.search}`
      );
      const data = response.data; // Assuming your API response provides relevant data
      // console.log('what is data', data.calls);
      calls.value = data.calls;
      slidingLoader.value = false;
    } catch (error) {
      console.error(error);
    }
  }
};

let formatMoney = (amount) => {
  return parseFloat(amount)
    .toFixed(2)
    .replace(/\d(?=(\d{3})+\.)/g, "$&,");
};
let showModal = ref(false);
let callDetail = ref(null);

let openClientModal = (call) => {
  callDetail.value = call;
  showModal.value = true;
};
</script>

<template>
  <animation-slider :slidingLoader="slidingLoader" />
  <section v-if="calls.data?.length && slidingLoader == false" class="p-3">
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
                <th scope="col" class="px-4 py-3">AMOUNT SPENT</th>
                <th scope="col" class="px-4 py-3">CALL TYPE</th>
                <th scope="col" class="px-4 py-3">CALLER ID</th>
                <th scope="col" class="px-4 py-3">URL</th>
                <th scope="col" class="px-4 py-3 text-end">Actions</th>
              </tr>
            </thead>
            <tbody>

              <tr v-for="call in calls.data" :key="call.id" class="border-b border-gray-500">
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
                  <a v-if="call.recording_url" target="_blank" :href="call.recording_url" class="flex"><svg
                      xmlns="http://www.w3.org/2000/svg" height="1.5em" class="pr-1" viewBox="0 0 512 512">
                      <path
                        d="M0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm256-96a96 96 0 1 1 0 192 96 96 0 1 1 0-192zm0 224a128 128 0 1 0 0-256 128 128 0 1 0 0 256zm0-96a32 32 0 1 0 0-64 32 32 0 1 0 0 64z" />
                    </svg>Open Recording
                  </a>
                  <span v-else>_</span>
                </td>
                <td class="text-gray-700 px-4 py-3 flex items-center justify-end">
                  <button v-if="call.get_client" @click="openClientModal(call)"
                    class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none"
                    type="button">
                    View Client
                  </button>
                  <button v-else class="text-center" type="button">-</button>
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
              <li v-for="(link, index) in calls.links" :key="link.label" :class="{ disabled: link.url === null }">
                <a href="#" @click.prevent="fetchcallsBypage(link.url)" :class="[
                  'flex items-center justify-center px-4 h-10 border border-gray-300',
                  link.active
                    ? 'text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-700'
                    : 'leading-tight text-gray-500 bg-white hover:bg-gray-100 hover:text-gray-700',
                  {
                    'rounded-l-lg': index === 0,
                    'rounded-r-lg': index === calls.links.length - 1,
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

  <section v-else-if="slidingLoader == false" class="p-3">
    <p class="text-center text-gray-600">No Calls yet.</p>
  </section>
  <Modal :show="showModal" @close="showModal = false">
    <ClientDetailsModal :showModal="showModal" :callDetail="callDetail" :states="states" @close="showModal = false">
    </ClientDetailsModal>
  </Modal>
</template>
