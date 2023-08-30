<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { router } from '@inertiajs/vue3';
import Echo from "laravel-echo";
import { onMounted } from "vue";

let props = defineProps({
    activeUsers: {
        type: Array,
    }
});

onMounted(() => {
    console.log('Mounted attaching event listeners');
    console.log(window.Echo);
    Echo.private('active-user-list-updated').listen('ActiveUserListUpdated', e => {
        console.log('active-user-list-updated fired');
        console.log(e);
    });
});


console.log(props.activeUsers);
let refreshPage = () => {
    router.visit('/admin/active-users');
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-2xl font-semibold">Active Users</h2>
        </template>
        <div class="py-14">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="px-4 sm:px-8 sm:rounded-lg">
                    <div class="text-4xl text-custom-sky font-bold mb-6">Active Users</div>
                    <hr class="mb-4" />
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <template v-for="user in activeUsers" :key="user.id">
                            <div class="rounded-lg shadow-lg border p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-semibold">{{ user.firstName }} {{ user.lastName }}</span>
                                    <span :class="getStatusBadge(user.status)" class="text-white text-xs px-2 py-1 rounded">
                                        {{ user.status }}
                                    </span>
                                </div>
                                <div class="text-sm mt-2">
                                    <div><strong>User ID:</strong> {{ user.id }}</div>
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
                case "Listening for calls":
                    return "bg-green-400";
                case "Ringing":
                    return "bg-yellow-400";
                case "On-going call":
                    return "bg-red-400";
                default:
                    return "bg-gray-400";
            }
        },
    },
};
</script>

<style scoped></style>
