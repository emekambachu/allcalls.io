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
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <template v-for="onlineUser in onlineUsers" :key="onlineUser.id">
                            <div class="rounded-lg shadow-lg border p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-semibold">
                                        {{ onlineUser.user.first_name }} {{ onlineUser.user.last_name }}
                                    </span>
                                    <span :class="getStatusBadge(onlineUser.user.call_status)" class="text-white text-xs px-2 py-1 rounded">
                                        {{ onlineUser.user.call_status }}
                                    </span>
                                </div>
                                <div class="text-sm mt-2">
                                    <div><strong>Email:</strong> {{ onlineUser.user.email }}</div>
                                    <div><strong>Insurance Type:</strong> {{ onlineUser.call_type.type }}</div>
                                    <div><strong>User ID:</strong> {{ onlineUser.user.id }}</div>
                                </div>
                            </div>
                        </template>
                    </div>
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
                    return "bg-green-700";
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
