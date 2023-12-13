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


let paginate = (url) => {
  router.visit(url);
};
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
                                    <th scope="col" class="px-4 py-3" style="min-width:240px">IP ADDRESS</th>
                                    <th scope="col" class="px-4 py-3" style="min-width:240px">DEVICES DETAILS</th>
                                    <th scope="col" class="px-4 py-3" style="min-width:240px">USER AGENT</th>
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
                                    <td class="text-gray-700 px-4 py-3">{{ (activity.ip_address) ? activity.ip_address : 'N/A' }}</td>
                                    <td class="text-gray-700 px-4 py-3">{{ (activity.devices_details) ? activity.devices_details : 'N/A' }}</td>
                                    <td class="text-gray-700 px-4 py-3">{{ (activity.user_agent) ? activity.user_agent : 'N/A' }}</td>
                                    <td class="text-gray-700 px-4 py-3">{{ formatDate(moment(moment(activity.created_at).utc().format("YYYY-MM-DD HH:mm:ss")).tz(timezone).format("YYYY-MM-DD HH:mm:ss")) }}</td>
                                    <td class="text-gray-700 px-4 py-3">{{ !(activity.logout_time)? '': formatDate(moment(moment(activity.logout_time).utc().format("YYYY-MM-DD HH:mm:ss")).tz(timezone).format("YYYY-MM-DD HH:mm:ss")) }} </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
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
                            <li v-for="(link, index) in activities.links" :key="link.label"
                            :class="{ disabled: link.url === null }">
                            <a href="#" @click.prevent="paginate(link.url)" :class="[
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
        </section>

        <section v-else class="p-3">
            <p class="text-center text-gray-700">No activities yet.</p>
        </section>

    </AuthenticatedLayout>
</template>
