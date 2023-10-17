<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Edit from "@//Pages/Admin/User/Edit.vue";
import { Head, router, usePage, useForm } from "@inertiajs/vue3";
import ClientDetailsModal from "@/Components/ClientDetailsModal.vue";
import { ref } from "vue";
import { toaster } from "@/helper.js";
import Modal from "@/Components/Modal.vue";
import SearchFilter from "@/Components/SearchFilter.vue";
import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";
let page = usePage();
if (page.props.flash.message) {
  toaster("success", page.props.flash.message);
}

let props = defineProps({
  calls: {
    type: Object,
  },
});

console.log("calls:");
console.log(props.calls);

let slidingLoader = ref(false);

let fetchCalls = (page) => {
  // Create URL object from page
  let url = new URL(page);

  // Ensure the protocol is https
  if (url.protocol !== "https:") {
    url.protocol = "https:";
  }

  // Get the https URL as a string
  let httpsPage = url.toString();

  router.visit(httpsPage, { method: "get" });
};

let showModal = ref(false);
let callDetail = ref(null);

let openClientModal = (call) => {
  callDetail.value = call;
  showModal.value = true;
};
</script>

<template>
  <Head title="Client Information" />
  <AuthenticatedLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
      >
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
    <section class="p-3">
      <div class="mx-auto max-w-screen-xl sm:px-12">
        <div class="relative sm:rounded-lg overflow-hidden">
          <div class="overflow-x-auto">
            <table
              class="w-full text-sm text-left text-gray-400 table-responsive"
            >
              <thead class="text-xs text-gray-300 uppercase bg-sky-900">
                <tr>
                  <th scope="col" class="px-4 py-3 whitespace-no-wrap">ID</th>
                  <th
                    scope="col"
                    class="px-4 py-3 whitespace-no-wrap"
                    style="min-width: 150px"
                  >
                    Name
                  </th>
                  <th
                    scope="col"
                    class="px-4 py-3 whitespace-no-wrap"
                    style="min-width: 150px"
                  >
                    Role
                  </th>
                  <th scope="col" class="px-4 py-3 whitespace-no-wrap">
                    RING DURATION
                  </th>
                  <th scope="col" class="px-4 py-3 whitespace-no-wrap">
                    CONNECTED DURATION
                  </th>
                  <th
                    scope="col"
                    class="px-4 py-3 whitespace-no-wrap"
                    style="min-width: 175px"
                  >
                    CALL TAKEN
                  </th>
                  <th scope="col" class="px-4 py-3 whitespace-no-wrap">
                    AMOUNT SPENT
                  </th>
                  <th
                    scope="col"
                    class="px-4 py-3 whitespace-no-wrap"
                    style="min-width: 130px"
                  >
                    VERTICAL
                  </th>
                  <th
                    scope="col"
                    class="px-4 py-3 whitespace-no-wrap"
                    style="min-width: 100px"
                  >
                    CALLER ID
                  </th>
                  <th
                    scope="col"
                    class="px-4 py-3 whitespace-no-wrap"
                    style="min-width: 160px"
                  >
                    URL
                  </th>
                  <th
                    scope="col"
                    class="px-4 py-3 whitespace-no-wrap text-end"
                    style="min-width: 110px"
                  >
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="call in calls.data"
                  :key="call.id"
                  class="border-b border-gray-500"
                >
                  <td class="text-gray-600 px-4 py-3">{{ call.id }}</td>
                  <td class="text-gray-600 px-4 py-3">
                    {{ call.user.first_name }} {{ call.user.last_name }}
                  </td>
                  <td class="text-gray-600 px-4 py-3">
                    <span
                      v-if="call.role === 'Internal Agent'"
                      class="bg-indigo-100 text-indigo-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded"
                      >Internal Agent</span
                    >
                    <span
                      v-else
                      class="bg-purple-100 text-purple-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded"
                      >Regular User</span
                    >
                  </td>

                  <td class="text-gray-600 px-4 py-3">
                    {{
                      String(Math.floor(call.ringing_duration / 60)).padStart(
                        2,
                        "0"
                      ) +
                      ":" +
                      String(call.ringing_duration % 60).padStart(2, "0")
                    }}
                  </td>
                  <td class="text-gray-600 px-4 py-3">
                    {{
                      String(
                        Math.floor(call.call_duration_in_seconds / 60)
                      ).padStart(2, "0") +
                      ":" +
                      String(call.call_duration_in_seconds % 60).padStart(
                        2,
                        "0"
                      )
                    }}
                  </td>

                  <th class="text-gray-600 px-4 py-3">{{ call.call_taken }}</th>
                  <td class="text-gray-600 px-4 py-3">
                    ${{ call.amount_spent }}
                  </td>
                  <td class="text-gray-600 px-4 py-3">
                    {{ call.call_type.type }}
                  </td>
                  <td class="text-gray-600 px-4 py-3">{{ call.from }}</td>

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
                        <path
                          d="M0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm256-96a96 96 0 1 1 0 192 96 96 0 1 1 0-192zm0 224a128 128 0 1 0 0-256 128 128 0 1 0 0 256zm0-96a32 32 0 1 0 0-64 32 32 0 1 0 0 64z"
                        /></svg
                      >Open Recording
                    </a>
                    <span v-else>_</span>
                  </td>
                  <td class="text-gray-700 px-4 py-3">

                    <Menu as="div" class="relative inline-block text-left">
                      <div>
                        <MenuButton
                          class="inline-flex justify-center rounded-md px-4 py-2 relative"
                          style="z-index: -1;"
                        >
                          <svg
                            class="w-6 h-6 text-gray-800"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor"
                            viewBox="0 0 4 15"
                          >
                            <path
                              d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"
                            />
                          </svg>
                        </MenuButton>
                      </div>

                      <transition
                        enter-active-class="transition duration-100 ease-out"
                        enter-from-class="transform scale-95 opacity-0"
                        enter-to-class="transform scale-100 opacity-100"
                        leave-active-class="transition duration-75 ease-in"
                        leave-from-class="transform scale-100 opacity-100"
                        leave-to-class="transform scale-95 opacity-0"
                      >
                        <MenuItems
                          class="absolute right-0 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                        >
                          <div class="px-1 py-1">
                            <MenuItem v-slot="{ active }">
                              <button
                                :class="[
                                  active
                                    ? 'bg-violet-500 text-white'
                                    : 'text-gray-900',
                                  'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                ]"
                              >
                                <EditIcon
                                  :active="active"
                                  class="mr-2 h-5 w-5 text-violet-400"
                                  aria-hidden="true"
                                />
                                Edit
                              </button>
                            </MenuItem>
                            <MenuItem v-slot="{ active }">
                              <button
                                :class="[
                                  active
                                    ? 'bg-violet-500 text-white'
                                    : 'text-gray-900',
                                  'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                ]"
                              >
                                <DuplicateIcon
                                  :active="active"
                                  class="mr-2 h-5 w-5 text-violet-400"
                                  aria-hidden="true"
                                />
                                Duplicate
                              </button>
                            </MenuItem>
                          </div>
                          <div class="px-1 py-1">
                            <MenuItem v-slot="{ active }">
                              <button
                                :class="[
                                  active
                                    ? 'bg-violet-500 text-white'
                                    : 'text-gray-900',
                                  'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                ]"
                              >
                                <ArchiveIcon
                                  :active="active"
                                  class="mr-2 h-5 w-5 text-violet-400"
                                  aria-hidden="true"
                                />
                                Archive
                              </button>
                            </MenuItem>
                            <MenuItem v-slot="{ active }">
                              <button
                                :class="[
                                  active
                                    ? 'bg-violet-500 text-white'
                                    : 'text-gray-900',
                                  'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                ]"
                              >
                                <MoveIcon
                                  :active="active"
                                  class="mr-2 h-5 w-5 text-violet-400"
                                  aria-hidden="true"
                                />
                                Move
                              </button>
                            </MenuItem>
                          </div>

                          <div class="px-1 py-1">
                            <MenuItem v-slot="{ active }">
                              <button
                                :class="[
                                  active
                                    ? 'bg-violet-500 text-white'
                                    : 'text-gray-900',
                                  'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                ]"
                              >
                                <DeleteIcon
                                  :active="active"
                                  class="mr-2 h-5 w-5 text-violet-400"
                                  aria-hidden="true"
                                />
                                Delete
                              </button>
                            </MenuItem>
                          </div>
                        </MenuItems>
                      </transition>
                    </Menu>

                    <!-- <div
                      class="text-gray-900 hover:text-gray-800 cursor-pointer"
                      v-if="call.get_client"
                      @click="openClientModal(call)"
                    >
                      View Client
                    </div> -->
                  </td>
                </tr>
              </tbody>
            </table>
            <div class="p-4">
              <nav
                class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4"
                aria-label="Table navigation"
              >
                <span
                  class="text-sm font-normal text-gray-500 dark:text-gray-400"
                >
                  Showing
                  <span class="font-semibold text-custom-blue">{{
                    calls.current_page
                  }}</span>
                  of
                  <span class="font-semibold text-custom-blue">{{
                    calls.last_page
                  }}</span>
                </span>
                <ul
                  class="inline-flex items-stretch -space-x-px cursor-pointer"
                >
                  <li>
                    <a
                      v-if="calls.prev_page_url"
                      @click="fetchCalls(calls.prev_page_url)"
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
                      >{{ calls.current_page }}
                    </a>
                  </li>

                  <li>
                    <a
                      v-if="calls.next_page_url"
                      @click="fetchCalls(calls.next_page_url)"
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
}
</style>
