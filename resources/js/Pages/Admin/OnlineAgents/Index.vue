<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { router } from '@inertiajs/vue3';
import { onMounted } from "vue";
import { Head } from "@inertiajs/vue3";

const props = defineProps({
    onlineUsers: {
        type: Array,
    }
});

const refreshPage = () => {
    router.visit('/admin/active-users');
};

onMounted(() => {
    window.Echo.channel('active-user-events')
        .listen('ActiveUserListUpdated', () => {
            refreshPage();
        })
        .listen('ActiveUserStatusUpdated', () => {
            refreshPage();
        });
});
</script>

<template>
    <Head title="Online Agents" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-2xl font-semibold">Online Agents</h2>
        </template>
        <div class="py-14">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 rounded-lg bg-white shadow">
                    <h3 class="text-4xl text-custom-sky font-bold mb-6">Online Agents</h3>
                    <hr class="mb-4" />
                    <!-- The Table Header -->
                    <table class="w-full text-sm text-left text-gray-400">
                        <thead class="text-xs text-gray-300 uppercase bg-sky-900">
                            <tr>
                                <th scope="col" class="px-4 py-3">User ID</th>
                                <th scope="col" class="px-4 py-3">First Name</th>
                                <th scope="col" class="px-4 py-3">Last Name</th>
                                <th scope="col" class="px-4 py-3">Email</th>
                                <th scope="col" class="px-4 py-3">Call Status</th>
                                <th scope="col" class="px-4 py-3">Listening For</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- The Table Body -->
                            <tr v-for="onlineUser in onlineUsers" :key="onlineUser.id" class="border-b border-gray-500">
                                <td class="text-gray-600 px-4 py-3">{{ onlineUser.user.id }}</td>
                                <td class="text-gray-600 px-4 py-3">{{ onlineUser.user.first_name }}</td>
                                <td class="text-gray-600 px-4 py-3">{{ onlineUser.user.last_name }}</td>
                                <td class="text-gray-600 px-4 py-3">{{ onlineUser.user.email }}</td>
                                <td class="text-gray-600 px-4 py-3">
                                    <span
                                        :class="`${getStatusBadge(onlineUser.user.call_status)} text-white text-xs px-2 py-1 rounded-2xl`">
                                        {{ onlineUser.user.call_status }}
                                    </span>
                                </td>
                                <td class="text-gray-600 px-4 py-3">{{ onlineUser.call_type.type }}</td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
export default {
    methods: {
        getStatusBadge(status) {
            switch (status) {
                case "Waiting":
                    return "bg-green-600";
                case "Ringing":
                    return "bg-yellow-400";
                case "Talking":
                    return "bg-red-400";
                default:
                    return "bg-gray-400";
            }
        },
    },
};
</script>

<style scoped>
/* Add your custom styles here */
</style>
