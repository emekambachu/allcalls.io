<script setup>
import { onMounted } from "vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { toaster } from "@/helper.js";


let { agentInvites, baseUrl } = defineProps({
    agentInvites: {
        required: true,
        type: Array
    },
    baseUrl: {
        required: true,
        type: String
    },
});

let page = usePage();

onMounted(() => {
    copyAgentInvitationLinkIfAvailable();
});

let copyAgentInvitationLinkIfAvailable = () => {
    if (page.props.flash.agentInvitationLink) {
        navigator.clipboard.writeText(page.props.flash.agentInvitationLink).then(function () {
            toaster('success', 'Invitation link copied to clipboard.')
        }).catch(function (err) {
            console.error('Could not copy text to clipboard: ', err);
        });
    }
}
let generateInvite = () => {
    router.visit('/admin/agent-invites', {
        method: 'POST',
    });
};

let deleteInvite = agentInvite => {
    if (window.confirm("Are you sure you want to delete this invite?")) {
        router.visit(`/admin/agent-invites/${agentInvite.id}`, {
            method: 'DELETE'
        });
    } else {
        console.log("Deletion cancelled");
    }
};

</script>

<template>
    <Head title="Agent Invites" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Agent Invites
            </h2>
        </template>


        <div class="py-14">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 rounded-lg bg-white">
                    <div class="flex justify-between">
                        <div>
                            <h1 class="text-4xl text-custom-sky font-bold mb-6">Agent Invites</h1>
                        </div>
                        <div>
                            <PrimaryButton @click="generateInvite">Add New</PrimaryButton>
                        </div>
                    </div>
                    <hr class="mb-4" />
                    <table v-if="agentInvites.length" class="w-full text-sm text-left text-gray-400">
                        <thead class="text-xs text-gray-300 uppercase bg-sky-900">
                            <tr>
                                <th scope="col" class="px-4 py-3">ID</th>
                                <th scope="col" class="px-4 py-3">URL</th>
                                <th scope="col" class="px-4 py-3">Status</th>
                                <th scope="col" class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-500" v-for="agentInvite in agentInvites" :key="agentInvite.id">
                                <td class="text-gray-600 px-4 py-3" v-text="agentInvite.id"></td>
                                <td class="text-gray-600 px-4 py-3">
                                    <a class="text-blue-500 hover:text-blue-700 hover:underline"
                                        target="_blank"
                                        :href="`${baseUrl}/register?agentToken=${agentInvite.token}`"
                                        v-text="`${baseUrl}/register?agentToken=${agentInvite.token}`"></a>
                                </td>
                                <td class="text-gray-600 px-4 py-3">
                                    <span v-if="agentInvite.used">
                                        Used
                                    </span>
                                    <span v-else>
                                        Not Used
                                    </span>
                                </td>
                                <td class="text-gray-600 px-4 py-3">
                                    <button @click.prevent="deleteInvite(agentInvite)" class="text-white bg-red-500 px-2 py-1 rounded-2xl">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div v-else class="text-center py-4 text-gray-800">
                        No invites yet.
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
