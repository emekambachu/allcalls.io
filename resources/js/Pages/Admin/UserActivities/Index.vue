<script setup>
import { ref, onMounted } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { toaster } from "@/helper.js";
import { Popover, PopoverButton, PopoverPanel } from "@headlessui/vue";
import Modal from "@/Components/Modal.vue";

let page = usePage();
if (page.props.flash.message) {
  toaster("success", page.props.flash.message);
}
let props = defineProps(["userActivities"]);

console.log(props);

let paginate = (url) => {
  router.visit(url);
};

let clearAll = () => {
  router.visit("/admin/user-activities/clear-all", { method: "DELETE" });
};

let abbreviateString = (theString) => {
  return theString.length > 12 ? theString.substring(0, 12) + '...' : theString;
};

</script>

<style scoped>
/* Keep existing styles or adjust as needed for the User Activities page. */
</style>

<template>
  <Head title="User Activities" />
  <AuthenticatedLayout>
    <div class="pt-14 flex justify-between px-16">
      <div>
        <div class="text-4xl text-custom-sky font-bold mb-6">User Activities</div>
      </div>

      <div class="flex items-center">
        <button
          @click.prevent="clearAll"
          class="button-custom-back px-3 py-2 rounded-md mr-2"
        >
          Clear All
        </button>
      </div>
    </div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
      <div class="px-4 sm:px-8 sm:rounded-lg">
        <hr class="mb-4" />
      </div>
    </div>

    <section v-if="userActivities.data.length" class="p-3">
      <div class="mx-auto max-w-screen-xl sm:px-12">
        <div class="relative sm:rounded-lg overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-400">
              <thead class="text-xs text-gray-300 uppercase bg-sky-900">
                <tr>
                  <th scope="col" class="px-4 py-3 whitespace-nowrap">ID</th>
                  <th scope="col" class="px-4 py-3 whitespace-nowrap">Action</th>
                  <th scope="col" class="px-4 py-3 whitespace-nowrap">Name</th>
                  <th scope="col" class="px-4 py-3 whitespace-nowrap">Platform</th>
                  <th scope="col" class="px-4 py-3 whitespace-nowrap">IP Address</th>
                  <th scope="col" class="px-4 py-3 whitespace-nowrap">User Agent</th>
                  <th scope="col" class="px-4 py-3 whitespace-nowrap">Data</th>
                  <th scope="col" class="px-4 py-3 whitespace-nowrap">Created At</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="activity in userActivities.data"
                  :key="activity.id"
                  class="border-b border-gray-500"
                >
                  <td class="text-gray-600 px-4 py-3 whitespace-nowrap">{{ activity.id }}</td>
                  <td class="text-gray-600 px-4 py-3 whitespace-nowrap">{{ activity.action }}</td>
                  <td class="text-gray-600 px-4 py-3 whitespace-nowrap">
                    {{ activity.user.first_name + " " + activity.user.last_name }}
                  </td>
                  <td class="text-gray-600 px-4 py-3 whitespace-nowrap">{{ activity.platform }}</td>
                  <td class="text-gray-600 px-4 py-3">
                    <Popover class="relative">
                      <PopoverButton class="whitespace-nowrap" title="Click to expand">{{ abbreviateString(activity.ip_address) }}</PopoverButton>

                      <PopoverPanel class="absolute z-10 top-6 whitespace-normal">
                        <div class="shadow bg-white rounded p-3">
                          {{ activity.ip_address }}
                        </div>
                      </PopoverPanel>
                    </Popover>
                  </td>
                  <td class="text-gray-600 px-4 py-3 whitespace-nowrap">
                    <Popover class="relative">
                      <PopoverButton class="whitespace-nowrap" title="Click to expand">{{ abbreviateString(activity.user_agent) }}</PopoverButton>

                      <PopoverPanel class="absolute z-10 top-6 whitespace-normal">
                        <div class="shadow bg-white rounded p-3">
                          {{ activity.user_agent }}
                        </div>
                      </PopoverPanel>
                    </Popover>
                  </td>
                  <td class="text-gray-600 px-4 py-3 whitespace-nowrap">
                    <pre
                      style="width: 200px; overflow-x: scroll; word-wrap: break-word"
                      class="p-2 bg-gray-200 text-gray-800 rounded"
                      >{{ activity.data }}</pre
                    >
                  </td>
                  <td class="text-gray-600 px-4 py-3 whitespace-nowrap">{{ activity.created_at }}</td>
                </tr>
              </tbody>
            </table>
            <!-- Pagination and other components can go here -->
          </div>
        </div>

        <nav class="flex justify-between my-4" v-if="userActivities.links">
          <div v-if="userActivities">
            <span class="text-sm text-gray-700">
              Showing
              <span class="font-semibold text-gray-900">{{ userActivities.from }}</span>
              to
              <span class="font-semibold text-gray-900">{{ userActivities.to }}</span> of
              <span class="font-semibold text-gray-900">{{ userActivities.total }}</span>
              Entries
            </span>
          </div>

          <ul class="inline-flex -space-x-px text-base h-10">
            <li
              v-for="(link, index) in userActivities.links"
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
                    'rounded-r-lg': index === userActivities.links.length - 1,
                  },
                ]"
                v-html="link.label"
              ></a>
            </li>
          </ul>
        </nav>
      </div>
    </section>


    <Modal :show="true" :closeable="true">
      <div>Data here</div>
    </Modal>
  </AuthenticatedLayout>
</template>
