<script setup>
import { ref, reactive, defineEmits, onMounted, watch } from "vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import ClientModal from "@/Components/ClientModal.vue";
import Modal from "@/Components/Modal.vue";
let props = defineProps({
  user: Object,
  url:String,
});

let emit = defineEmits();

console.log('what is url ', props.url);
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
};

let formatMoney = (amount) => {
  return parseFloat(amount)
    .toFixed(2)
    .replace(/\d(?=(\d{3})+\.)/g, "$&,");
};
let showModal = ref(false);
let ClientDetail = ref(null);
let openclientModal = (Client) => {
    console.log('what is detail', Client);
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
                    <span v-if="client.status == 'not_sold'"
                      class="bg-red-600 text-white text-xs px-2 py-1 rounded-2xl">Not Sold</span>
                    <span v-else-if="client.status == 'sold'"
                      class="bg-green-600 text-white text-xs px-2 py-1 rounded-2xl">Sold</span>
                    <span v-else-if="client.status"
                      class="bg-yellow-600 text-white text-xs px-2 py-1 rounded-2xl">{{ client.status  }}</span>
                      <span v-else>-</span>
                  </td>
                  <td class="text-gray-700 px-4 py-3 flex items-center justify-end">
                    <button  @click="openclientModal(client)"
                      class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none"
                      type="button">
                      View client
                    </button>
                  </td>
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
                  clients.current_page
                }}</span>
                of
                <span class="font-semibold text-custom-blue">{{ clients.last_page }}</span>
              </span>
              <ul class="inline-flex items-stretch -space-x-px cursor-pointer">
                <li>
                  <a
                    v-if="clients.prev_page_url"
                    @click="fetchclientsBypage(clients.prev_page_url)"
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
                    >{{ clients.current_page }}
                  </a>
                </li>

                <li>
                  <a
                    v-if="clients.next_page_url"
                    @click="fetchclientsBypage(clients.next_page_url)"
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

  <section v-else-if="slidingLoader == false" class="p-3">
    <p class="text-center text-gray-600">No clients yet.</p>
  </section>
  <Modal :show="showModal" @close="showModal = false">
      <ClientModal :showModal="showModal" :ClientDetail="ClientDetail" :states="states" @close="showModal = false" ></ClientModal>
    </Modal>
</template>
