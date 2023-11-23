<script setup>
import { ref, onMounted } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { toaster } from "@/helper.js";
import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";

let page = usePage();
if (page.props.flash.message) {
  toaster("success", page.props.flash.message);
}
let props = defineProps(["calls"]);

console.log(props);

let paginate = (url) => {
  router.visit(url);
};
</script>

<style scoped>
/* Keep existing styles or adjust as needed for the User Activities page. */
</style>

<template>
  <Head title="Calls" />
  <AuthenticatedLayout>
    <div class="pt-14 flex justify-between px-16">
      <div>
        <div class="text-4xl text-custom-sky font-bold mb-6">Calls</div>
      </div>

      <div class="flex items-center">
        <!-- <button class="button-custom-back px-3 py-2 rounded-md mr-2">Clear All</button> -->
      </div>
    </div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
      <div class="px-4 sm:px-8 sm:rounded-lg">
        <hr class="mb-4" />
      </div>
    </div>

    <section class="py-3 sm:py-5">
      <div class="px-4 mx-auto max-w-screen-2xl lg:px-12">
        <div class="relative overflow-hidden bg-white sm:rounded-lg">
          <div
            class="flex flex-col px-4 py-3 space-y-3 lg:flex-row lg:items-center lg:justify-between lg:space-y-0 lg:space-x-4"
          >
            <div class="flex items-center flex-1 space-x-4">
              <h5>
                <span class="text-gray-500">All Calls:</span>
                <span class="">123456</span>
              </h5>
              <h5>
                <span class="text-gray-500">Total Revenue:</span>
                <span class="">$88.4k</span>
              </h5>
            </div>
            <div
              class="flex flex-col flex-shrink-0 space-y-3 md:flex-row md:items-center lg:justify-end md:space-y-0 md:space-x-3"
            >
              <button
                type="button"
                class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 focus:outline-none"
              >
                <svg
                  class="h-3.5 w-3.5 mr-2"
                  fill="currentColor"
                  viewbox="0 0 20 20"
                  xmlns="http://www.w3.org/2000/svg"
                  aria-hidden="true"
                >
                  <path
                    clip-rule="evenodd"
                    fill-rule="evenodd"
                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                  />
                </svg>
                Add new product
              </button>
              <button
                type="button"
                class="flex items-center justify-center flex-shrink-0 px-3 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200"
              >
                <svg
                  class="w-4 h-4 mr-2"
                  xmlns="http://www.w3.org/2000/svg"
                  aria-hidden="true"
                  fill="none"
                  viewbox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"
                  />
                </svg>
                Update stocks 1/250
              </button>

              <Menu as="div" class="relative inline-block text-left">
                <div>
                  <MenuButton
                    class="flex items-center justify-center flex-shrink-0 px-3 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200"
                  >
                    <svg
                      class="w-4 h-4 mr-2"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewbox="0 0 24 24"
                      stroke-width="2"
                      stroke="currentColor"
                      aria-hidden="true"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"
                      />
                    </svg>
                    Visible Columns
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
                    class="absolute mt-2 w-56 origin-top-right rounded-md bg-white shadow border border-gray-100"
                  >
                    <div class="px-1 py-1">
                      <MenuItem>
                        <span class="text-gray-900 block px-4 py-2 text-sm">
                          Hello, world
                        </span>
                      </MenuItem>
                    </div>
                  </MenuItems>
                </transition>
              </Menu>
            </div>
          </div>
          <div v-if="calls.data.length" class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
              <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                  <th scope="col" class="px-4 py-3 whitespace-nowrap">ID</th>
                  <th scope="col" class="px-4 py-3 whitespace-nowrap">Call Date</th>
                  <th scope="col" class="px-4 py-3 whitespace-nowrap">Agent Name</th>
                  <th scope="col" class="px-4 py-3 whitespace-nowrap">Role</th>
                  <th scope="col" class="px-4 py-3 whitespace-nowrap">
                    Connected Duration
                  </th>
                  <th scope="col" class="px-4 py-3 whitespace-nowrap">Revenue</th>
                  <th scope="col" class="px-4 py-3 whitespace-nowrap">Vertical</th>
                  <th scope="col" class="px-4 py-3 whitespace-nowrap">Caller ID</th>
                  <th scope="col" class="px-4 py-3 whitespace-nowrap">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  class="border-b hover:bg-gray-100"
                  v-for="call in calls.data"
                  :key="call.id"
                >
                  <td
                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                  >
                    {{ call.id }}
                  </td>
                  <td
                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                  >
                    {{ call.call_taken }}
                  </td>
                  <td
                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                  >
                    {{ call.user.first_name }} {{ call.user.last_name }}
                  </td>

                  <td class="px-4 py-2">
                    <span
                      class="whitespace-nowrap bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded"
                    >
                      <span v-if="call.role === 'Internal Agent'">Internal Agent</span>
                      <span v-else>Regular User</span>
                    </span>
                  </td>

                  <td
                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                  >
                    {{
                      String(Math.floor(call.call_duration_in_seconds / 60)).padStart(
                        2,
                        "0"
                      ) +
                      ":" +
                      String(call.call_duration_in_seconds % 60).padStart(2, "0")
                    }}
                  </td>
                  <td
                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                  >
                    ${{ call.amount_spent }}
                  </td>
                  <td
                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                  >
                    {{ call.call_type.type }}
                  </td>
                  <td
                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                  >
                    {{ call.from }}
                  </td>
                  <td
                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                  >
                    <Menu as="div" class="relative inline-block text-left">
                      <div>
                        <MenuButton
                          class="inline-flex justify-center rounded-md px-4 py-2 relative"
                          style="z-index: 1"
                        >
                          <svg
                            class="w-4 h-4 text-gray-800 dark:text-white"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 17 4"
                          >
                            <path
                              stroke="currentColor"
                              stroke-linecap="round"
                              stroke-width="2"
                              d="M2.49 2h.01m6 0h.01m5.99 0h.01"
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
                          style="z-index: 10"
                        >
                          <div class="px-1 py-1">
                            <MenuItem v-slot="{ active }">
                              <button
                                :class="[
                                  active ? 'bg-sky-900 text-white' : 'text-gray-900',
                                  'group flex w-full items-center rounded-md py-2 px-2 text-xs',
                                ]"
                              >
                                Open Client Details
                              </button>
                            </MenuItem>
                            <MenuItem v-slot="{ active }">
                              <button
                                :class="[
                                  active ? 'bg-sky-900 text-white' : 'text-gray-900',
                                  'group flex w-full items-center rounded-md py-2 px-2 text-xs',
                                ]"
                              >
                                Open User Details
                              </button>
                            </MenuItem>
                            <MenuItem v-slot="{ active }">
                              <button
                                :class="[
                                  active ? 'bg-sky-900 text-white' : 'text-gray-900',
                                  'group flex w-full items-center rounded-md py-2 px-2 text-xs',
                                ]"
                              >
                                Open Call Details
                              </button>
                            </MenuItem>
                          </div>
                        </MenuItems>
                      </transition>
                    </Menu>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div v-else class="text-sm text-center">No calls found.</div>
        </div>
      </div>
    </section>
  </AuthenticatedLayout>
</template>
