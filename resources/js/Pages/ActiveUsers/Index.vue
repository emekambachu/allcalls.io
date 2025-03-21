<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { router } from '@inertiajs/vue3';
import { onMounted } from "vue";
import { Head } from "@inertiajs/vue3";

let props = defineProps({
    activeUsers: {
        type: Array,
    }
});

console.log('Active users: ');
console.log(props.activeUsers);

let refreshPage = () => {
    router.visit('/admin/active-users');
}

onMounted(() => {
    console.log('Registering event listener');
    window.Echo.channel('active-user-events')
        .listen('ActiveUserListUpdated', e => {
            console.log('ActiveUserListUpdated fired, refreshing...');
            refreshPage();
        })

        .listen('ActiveUserStatusUpdated', e => {
            console.log('ActiveUserStatusUpdated fired, refreshing...');
            refreshPage();
        });
});
</script>

<template>
    <Head title="Active Users" />

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
                        <template v-for="activeUser in activeUsers" :key="activeUser.id">
                            <div class="rounded-lg shadow-lg border p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-semibold">{{ activeUser.user.first_name }} {{ activeUser.user.last_name }}</span>
                                    <span :class="getStatusBadge(activeUser.status)" class="text-white text-xs px-2 py-1 rounded">
                                        {{ activeUser.status }}
                                    </span>
                                </div>
                                <div class="text-sm mt-2">
                                    <div><strong>User ID:</strong> {{ activeUser.user.id }}</div>
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
                    return "bg-green-400";
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

<style scoped></style>
