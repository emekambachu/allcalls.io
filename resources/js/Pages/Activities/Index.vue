<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";

defineProps({
  activities: {
    type: Array,
  },
});
</script>

<template>
  <Head title="Usage Activity" />
  <AuthenticatedLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
      >
        Activities
      </h2>
    </template>

    <!-- <template> -->
    
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3 bg-gray-50 dark:bg-gray-800">
                    ID
                </th>
                <th scope="col" class="px-6 py-3">
                    EMAIL
                </th>
                <th scope="col" class="px-6 py-3 bg-gray-50 dark:bg-gray-800">
                    LAST ACTIVITY
                </th>
                <th scope="col" class="px-6 py-3">
                    SIGNED AT
                </th>
                <th scope="col" class="px-6 py-3 bg-gray-50 dark:bg-gray-800">
                    LOGOUT TIME
                </th>
            </tr>
        </thead>
        <tbody>

            <tr v-for="activity in activities" :key="activity.id">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50 dark:text-white dark:bg-gray-800">
                    {{ activity.id }}
                </th>
                <td class="px-6 py-4">
                    {{ activity.user.email }}
                </td>
                <td class="px-6 py-4 bg-gray-50 dark:bg-gray-800">
                    {{ activity.last_activity_at }}
                </td>
                <td class="px-6 py-4">
                    {{ activity.created_at }}
                </td>
                <td class="px-6 py-4 bg-gray-50 dark:bg-gray-800">
                    {{ activity.logout_time }}
                </td>
            </tr>
        </tbody>
    </table>
</div>


<!-- </template> -->



  </AuthenticatedLayout>
</template>
