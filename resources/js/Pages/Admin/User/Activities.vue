<script setup>
import { ref, reactive, defineEmits, onMounted, watch } from "vue";
import TextInput from "@/Components/TextInput.vue";
import { router } from "@inertiajs/vue3"
import { Popover, PopoverButton, PopoverPanel } from "@headlessui/vue";

let props = defineProps({

  user: Object,
});
const formatCustomDate = (date) => {
  const userDate = new Date(date);
  const options = {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: 'numeric',
    minute: 'numeric',
    hour12: true,
  };
  return userDate.toLocaleString('en-US', options);
};
let activities = ref([]);
onMounted(() => {
  Fetehactivities(props.user.id);
})
let slidingLoader = ref(false)
let Fetehactivities = async (id) => {
  slidingLoader.value = true
  try {
    const response = await axios.get(`/admin/customer/activities/${id}`);
    const data = response.data; // Assuming your API response provides relevant data
    activities.value = data.activities
    slidingLoader.value = false
  } catch (error) {
    console.error(error);
  }
};
let fetchactivitiesBypage = async (page) => {
  if (page) {
    slidingLoader.value = true
    let url = new URL(page);
    try {
      const response = await axios.get(`/admin/customer/activities/${props.user.id}${url.search}`);
      const data = response.data; // Assuming your API response provides relevant data
      activities.value = data.activities
      slidingLoader.value = false
    } catch (error) {
      console.error(error);
    }
  }
};
let abbreviateString = (theString) => {
  return theString.length > 12 ? theString.substring(0, 12) + "..." : theString;
};
let clearAll = async () => {
  let text = `Are you sure you want to clear all activity of this user?`;
  if (confirm(text) == true) {
    slidingLoader.value = true
    try {
      const response = await axios.delete(`/admin/customer/activities/${props.user.id}`);
      const data = response.data; // Assuming your API response provides relevant data
      activities.value = data.activities
      slidingLoader.value = false
    } catch (error) {
      console.error(error);
    }
  } else {
    console.log('cancel');
  }
  return

};
</script>

<template>
  <animation-slider :slidingLoader="slidingLoader" />
  <section v-if="activities.data?.length && slidingLoader == false" class="p-3">
    <div class="flex items-center justify-end mb-3 sm:px-12">
      <!-- <button @click.prevent="clearAll" class="button-custom-back px-3 py-2 rounded-md mr-2">
        Clear All
      </button> -->
    </div>
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
              <!-- <tr v-for="activity in activities.data" :key="activity.id" class="border-b border-gray-500">
                <th scope="row" class="px-4 py-3 font-medium text-custom-blue font-semibold whitespace-nowrap">{{
                  activity.user.first_name }} {{ activity.user.last_name }}</th>
                <td class="text-gray-700 px-4 py-3">
                  {{ activity.user.email }}

                </td>
                <td class="text-gray-700 px-4 py-3">{{ formatCustomDate(activity.last_activity_at) }} </td>
                <td class="text-gray-700 px-4 py-3">{{ (activity.ip_address) ? activity.ip_address : 'N/A' }}</td>
                <td class="text-gray-700 px-4 py-3">{{ (activity.devices_details) ? activity.devices_details : 'N/A' }}
                </td>
                <td class="text-gray-700 px-4 py-3">{{ (activity.user_agent) ? activity.user_agent : 'N/A' }}</td>
                <td class="text-gray-700 px-4 py-3">{{ formatCustomDate(activity.created_at) }}</td>
                <td class="text-gray-700 px-4 py-3">{{ !(activity.logout_time) ? '' :
                  formatCustomDate(activity.logout_time)
                }} </td>
              </tr> -->
            </tbody>
          </table>

          <nav class="flex justify-between my-4" v-if="activities.links">
            <div v-if="activities">
              <span class="text-sm text-gray-700">
                Showing
                <span class="font-semibold text-gray-900">{{ activities.from }}</span>
                to
                <span class="font-semibold text-gray-900">{{ activities.to }}</span> of
                <span class="font-semibold text-gray-900">{{ activities.total }}</span>
                Entries
              </span>
            </div>

            <ul class="inline-flex -space-x-px text-base h-10">
              <li v-for="(link, index) in activities.links" :key="link.label" :class="{ disabled: link.url === null }">
                <a href="#" @click.prevent="fetchactivitiesBypage(link.url)" :class="[
                  'flex items-center justify-center px-4 h-10 border border-gray-300',
                  link.active
                    ? 'text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-700'
                    : 'leading-tight text-gray-500 bg-white hover:bg-gray-100 hover:text-gray-700',
                  {
                    'rounded-l-lg': index === 0,
                    'rounded-r-lg': index === activities.links.length - 1,
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
    <p class="text-center text-gray-600">No Activities yet.</p>
  </section>
</template>