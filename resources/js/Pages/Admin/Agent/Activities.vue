<script setup>
import { ref, reactive, defineEmits, onMounted, watch } from "vue";
import TextInput from "@/Components/TextInput.vue";
import { router } from "@inertiajs/vue3"
import { Popover, PopoverButton, PopoverPanel } from "@headlessui/vue";

let props = defineProps({

  user: Object,
});
let activities = ref([]);

onMounted(() => {
  Fetehactivities(props.user.id);
})
let slidingLoader = ref(false)
let Fetehactivities = async (id) => {
  slidingLoader.value = true
  try {
    const response = await axios.get(`/admin/agent/activities/${id}`);
    const data = response.data; // Assuming your API response provides relevant data
    activities.value = data.activities
    slidingLoader.value = false
  } catch (error) {
    console.error(error);
  }
};
let fetchactivitiesBypage = async (page) => {
  slidingLoader.value = true
  let url = new URL(page);
  try {
    const response = await axios.get(`/admin/agent/activities/${props.user.id}${url.search}`);
    const data = response.data; // Assuming your API response provides relevant data
    activities.value = data.activities
    slidingLoader.value = false
  } catch (error) {
    console.error(error);
  }
};
let abbreviateString = (theString) => {
  return theString.length > 12 ? theString.substring(0, 12) + "..." : theString;
};
</script>

<template>
  <animation-slider :slidingLoader="slidingLoader" />
  <section v-if="activities.data?.length && slidingLoader == false" class="p-3">
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
              <tr v-for="activity in activities.data" :key="activity.id" class="border-b border-gray-500">
                <td class="text-gray-600 px-4 py-3 whitespace-nowrap">
                  {{ activity.id }}
                </td>
                <td class="text-gray-600 px-4 py-3 whitespace-nowrap">
                  {{ activity.action }}
                </td>
                <td class="text-gray-600 px-4 py-3 whitespace-nowrap">
                  {{ activity.user.first_name + " " + activity.user.last_name }}
                </td>
                <td class="text-gray-600 px-4 py-3 whitespace-nowrap">
                  {{ activity.platform }}
                </td>
                <td class="text-gray-600 px-4 py-3">
                  <Popover class="relative">
                    <PopoverButton class="whitespace-nowrap" title="Click to expand">{{
                      abbreviateString(activity.ip_address)
                    }}</PopoverButton>

                    <PopoverPanel class="absolute z-10 top-6 whitespace-normal">
                      <div class="shadow bg-white rounded p-3">
                        {{ activity.ip_address }}
                      </div>
                    </PopoverPanel>
                  </Popover>
                </td>
                <td class="text-gray-600 px-4 py-3 whitespace-nowrap">
                  <Popover class="relative">
                    <PopoverButton class="whitespace-nowrap" title="Click to expand">{{
                      abbreviateString(activity.user_agent)
                    }}</PopoverButton>

                    <PopoverPanel class="absolute z-10 top-6 whitespace-normal">
                      <div class="shadow bg-white rounded p-3">
                        {{ activity.user_agent }}
                      </div>
                    </PopoverPanel>
                  </Popover>
                </td>
                <td class="text-gray-600 px-4 py-3 whitespace-nowrap">
                  <pre style="width: 200px; overflow-x: scroll; word-wrap: break-word" @click.prevent="
                    selectedActivity = activity;
                  showDataModal = true;
                  " class="p-2 bg-gray-200 text-gray-800 rounded">{{ activity.data }}</pre>
                </td>
                <td class="text-gray-600 px-4 py-3 whitespace-nowrap">
                  {{ activity.created_at }}
                </td>
              </tr>
            </tbody>
          </table>
          <div class="p-4">
            <nav class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4"
              aria-label="Table navigation">
              <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                Showing
                <span class="font-semibold text-custom-blue">{{
                  activities.current_page
                }}</span>
                of
                <span class="font-semibold text-custom-blue">{{
                  activities.last_page
                }}</span>
              </span>
              <ul class="inline-flex items-stretch -space-x-px cursor-pointer">
                <li>
                  <a v-if="activities.prev_page_url" @click="fetchactivitiesBypage(activities.prev_page_url)"
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
                      activities.current_page }}
                  </a>
                </li>

                <li>
                  <a v-if="activities.next_page_url" @click="fetchactivitiesBypage(activities.next_page_url)"
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
      </div>
    </div>
  </section>

  <section v-else-if="slidingLoader == false" class="p-3">
    <p class="text-center text-gray-600">No Activities yet.</p>
  </section>
</template>