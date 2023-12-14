<script setup>
import { ref, reactive, defineEmits, onMounted, watch } from "vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import ClientModal from "@/Components/ClientModal.vue";
import Modal from "@/Components/Modal.vue";
let props = defineProps({
  user: Object,
  url: String,
});

let emit = defineEmits();

let clients = ref([]);
onMounted(() => {
  Fetehclients(props.user.id);
});
let slidingLoader = ref(false);
let Fetehclients = async (id) => {
  slidingLoader.value = true;
  try {
    const response = await axios.get(`${props.url}/${id}`);
    const data = response.data; // Assuming your API response provides relevant data
    // console.log('what is data', data.clients);
    clients.value = data.clients;
    slidingLoader.value = false;
  } catch (error) {
    console.error(error);
  }
};
let fetchclientsBypage = async (page) => {
  if (page) {
    slidingLoader.value = true;
    let url = new URL(page);
    try {
      const response = await axios.get(
        `${props.url}/${props.user.id}${url.search}`
      );
      const data = response.data; // Assuming your API response provides relevant data
      // console.log('what is data', data.clients);
      clients.value = data.clients;
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
let ClientDetail = ref(null);
let openclientModal = (Client) => {
  ClientDetail.value = Client;
  showModal.value = true;
};
</script>

<template>
  <animation-slider :slidingLoader="slidingLoader" />
  <section v-if="clients.data?.length && slidingLoader == false" class="p-3">
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
                <th scope="col" class="px-4 py-3">Status</th>
                <th scope="col" class="px-4 py-3 text-end">Actions</th>
              </tr>
            </thead>
            <tbody>

              <tr v-for="client in clients.data" :key="client.id" class="border-b border-gray-500">
                <td class="text-gray-600 px-4 py-3">{{ client.id }}</td>
                <td class="text-gray-600 px-4 py-3">{{ client.first_name }}</td>
                <td class="text-gray-600 px-4 py-3">{{ client.last_name }}</td>
                <th class="text-gray-600 px-4 py-3">{{ client.email }}</th>
                <td class="text-gray-600 px-4 py-3">
                  <span v-if="client.status == 'not_sold'" class="bg-red-600 text-white text-xs px-2 py-1 rounded-2xl">Not
                    Sold</span>
                  <span v-else-if="client.status == 'sold'"
                    class="bg-green-600 text-white text-xs px-2 py-1 rounded-2xl">Sold</span>
                  <span v-else-if="client.status" class="bg-yellow-600 text-white text-xs px-2 py-1 rounded-2xl">{{
                    client.status }}</span>
                  <span v-else>-</span>
                </td>
                <td class="text-gray-700 px-4 py-3 flex items-center justify-end">
                  <button @click="openclientModal(client)"
                    class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none"
                    type="button">
                    View client
                  </button>
                </td>
              </tr>
            </tbody>
          </table>

          <nav class="flex justify-between my-4" v-if="clients.links">
            <div v-if="clients">
              <span class="text-sm text-gray-700">
                Showing
                <span class="font-semibold text-gray-900">{{ clients.from }}</span>
                to
                <span class="font-semibold text-gray-900">{{ clients.to }}</span> of
                <span class="font-semibold text-gray-900">{{ clients.total }}</span>
                Entries
              </span>
            </div>

            <ul class="inline-flex -space-x-px text-base h-10">
              <li v-for="(link, index) in clients.links" :key="link.label" :class="{ disabled: link.url === null }">
                <a href="#" @click.prevent="fetchclientsBypage(link.url)" :class="[
                  'flex items-center justify-center px-4 h-10 border border-gray-300',
                  link.active
                    ? 'text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-700'
                    : 'leading-tight text-gray-500 bg-white hover:bg-gray-100 hover:text-gray-700',
                  {
                    'rounded-l-lg': index === 0,
                    'rounded-r-lg': index === clients.links.length - 1,
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
    <p class="text-center text-gray-600">No clients yet.</p>
  </section>
  <Modal :show="showModal" @close="showModal = false">
    <ClientModal :showModal="showModal" :ClientDetail="ClientDetail" :states="states" @close="showModal = false">
    </ClientModal>
  </Modal>
</template>
