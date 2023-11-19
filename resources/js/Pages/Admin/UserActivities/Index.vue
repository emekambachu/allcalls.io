<script setup>
import { ref, onMounted } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

let props = defineProps(['userActivities']);

console.log(props);
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
        <button class="button-custom-back px-3 py-2 rounded-md mr-2">Clear All</button>
      </div>
    </div>

    <section v-if="userActivities.length" class="p-3">
      <div class="mx-auto max-w-screen-xl sm:px-12">
        <div class="relative sm:rounded-lg overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-400">
              <thead class="text-xs text-gray-300 uppercase bg-sky-900">
                <tr>
                  <th scope="col" class="px-4 py-3">ID</th>
                  <th scope="col" class="px-4 py-3">Action</th>
                  <th scope="col" class="px-4 py-3">Name</th>
                  <th scope="col" class="px-4 py-3">Platform</th>
                  <th scope="col" class="px-4 py-3">Created At</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="activity in userActivities"
                  :key="activity.id"
                  class="border-b border-gray-500"
                >
                  <td class="text-gray-600 px-4 py-3">{{ activity.id }}</td>
                  <td class="text-gray-600 px-4 py-3">{{ activity.action }}</td>
                  <td class="text-gray-600 px-4 py-3">{{ activity.user.first_name + ' ' + activity.user.last_name }}</td>
                  <td class="text-gray-600 px-4 py-3">{{ activity.platform }}</td>
                  <td class="text-gray-600 px-4 py-3">{{ activity.created_at }}</td>
                </tr>
              </tbody>
            </table>
            <!-- Pagination and other components can go here -->
          </div>
        </div>
      </div>
    </section>
  </AuthenticatedLayout>
</template>
