<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref,onMounted } from "vue";
import moment from 'moment-timezone'
defineProps({
    activities: {
        type: Object,
    },
});


let formatDate = date => {
    if (!date) {
        return '';
    }

    const dateObj = new Date(date);

    const formattedDate = dateObj.toLocaleDateString("en-US", {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "numeric",
        minute: "numeric",
    });

    return formattedDate;
}

let fetchActivities = page => {
    // Create URL object from page
    let url = new URL(page);

    // Ensure the protocol is https
    if (url.protocol !== 'https:') {
        url.protocol = 'https:';
    }

    // Get the https URL as a string
    let httpsPage = url.toString();

    router.visit(httpsPage, { method: 'get', });
}
let timezone = "UTC";
onMounted(() => {
  timezone = document.querySelector("meta[name='user-timezone']").getAttribute('content');
});
</script>

<template>
    <Head title="Activities" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Activities
            </h2>
        </template>

        <div class="pt-14">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="px-4 sm:px-8  sm:rounded-lg">
                    <div class="text-4xl text-custom-sky font-bold mb-6">Activities</div>
                    <hr class="mb-4">
                </div>
            </div>
        </div>

        <section v-if="activities.data.length" class="p-3 ">
            <div class="mx-auto max-w-screen-xl sm:px-12">
                <div class="relative sm:rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-400 table-responsive">
                            <thead class="text-xs text-gray-300 uppercase bg-sky-900">
                                <tr>
                                    <th scope="col" class="px-4 py-3">FULL NAME</th>
                                    <th scope="col" class="px-4 py-3">EMAIL</th>
                                    <th scope="col" class="px-4 py-3" style="min-width:240px">LAST ACTIVITY AT</th>
                                    <th scope="col" class="px-4 py-3" style="min-width:240px">SIGNED IN AT</th>
                                    <th scope="col" class="px-4 py-3" style="min-width:240px">LOGOUT AT</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="activity in activities.data" :key="activity.id" class="border-b border-gray-500">
                                    <th scope="row"
                                        class="px-4 py-3 font-medium text-custom-blue font-semibold whitespace-nowrap">{{
                                            activity.user.first_name }} {{ activity.user.last_name }}</th>
                                    <td class="text-gray-700 px-4 py-3">
                                        {{ activity.user.email }}
                                        
                                    </td>
                                    <td class="text-gray-700 px-4 py-3">{{ formatDate(moment(moment(activity.last_activity_at).utc().format("YYYY-MM-DD HH:mm:ss")).tz(timezone).format("YYYY-MM-DD HH:mm:ss")) }}</td>
                                    <td class="text-gray-700 px-4 py-3">{{ formatDate(moment(moment(activity.created_at).utc().format("YYYY-MM-DD HH:mm:ss")).tz(timezone).format("YYYY-MM-DD HH:mm:ss")) }}</td>
                                    <td class="text-gray-700 px-4 py-3">{{ !(activity.logout_time)? '': formatDate(moment(moment(activity.logout_time).utc().format("YYYY-MM-DD HH:mm:ss")).tz(timezone).format("YYYY-MM-DD HH:mm:ss")) }} </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <nav class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4"
                        aria-label="Table navigation">
                        <span class="text-sm font-normal text-gray-500">
                            Showing
                            <span class="font-semibold text-custom-blue">{{ activities.current_page }}</span>
                            of
                            <span class="font-semibold text-custom-blue">{{ activities.last_page }}</span>
                        </span>
                        <ul class="inline-flex items-stretch -space-x-px cursor-pointer">
                            <li>
                                <a 
                                    v-if="activities.prev_page_url"
                                    @click="fetchActivities(activities.prev_page_url)"
                                    class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-custom-white rounded-l-lg hover:bg-sky-950 hover:shadow-2xl hover:text-white "
                                    >
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
                                    class="flex items-center justify-center text-sm py-2 px-3 leading-tight font-extrabold text-gray-500 bg-custom-white shadow-2xl hover:bg-sky-950 hover:shadow-2xl hover:text-white">{{ activities.current_page }}
                                </a>
                            </li>


                            <li>
                                <a 
                                    v-if="activities.next_page_url"
                                    @click="fetchActivities(activities.next_page_url)"
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
        </section>

        <section v-else class="p-3">
            <p class="text-center text-gray-700">No activities yet.</p>
        </section>

    </AuthenticatedLayout>
</template>
