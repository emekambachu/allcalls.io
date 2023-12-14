<script setup>
import { ref, reactive, defineEmits, onMounted, watch } from "vue";
import TextInput from "@/Components/TextInput.vue";
import { router } from "@inertiajs/vue3"
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
                <th scope="col" class="px-4 py-3">FULL NAME</th>
                <th scope="col" class="px-4 py-3">EMAIL</th>
                <th scope="col" class="px-4 py-3" style="min-width:240px">LAST ACTIVITY AT</th>
                <th scope="col" class="px-4 py-3" style="min-width:240px">IP ADDRESS</th>
                <th scope="col" class="px-4 py-3" style="min-width:240px">DEVICES DETAILS</th>
                <th scope="col" class="px-4 py-3" style="min-width:240px">USER AGENT</th>
                <th scope="col" class="px-4 py-3" style="min-width:240px">SIGNED IN AT</th>
                <th scope="col" class="px-4 py-3" style="min-width:240px">LOGOUT AT</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="activity in activities.data" :key="activity.id" class="border-b border-gray-500">
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
              </tr>
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