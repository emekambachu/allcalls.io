<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import { toaster } from "@/helper.js";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from "@/Components/Modal.vue";
import LevelModal from "@//Pages/Admin/AgentLevel/LevelModal.vue";
import { Portal } from "@headlessui/vue";
import DeleteModal from "@/Components/DeleteModal.vue";

let page = usePage();
if (page.props.flash.message) {
    toaster("success", page.props.flash.message);
}

let props = defineProps({
    levels: Array,
});
let currentPage = ref(null);
let modal_type = ref(null)
let addAgentLevelModal = ref(false)
let addAgentLevel = (page) => {
    currentPage.value = page;
    addAgentLevelModal.value = true
    modal_type.value = 1
}
let levelData = ref(null)
let editAgentLevel = (level, page) => {
    addAgentLevelModal.value = true
    modal_type.value = 2
    currentPage.value = page;
    levelData.value = level
}

let paginate = (url) => {
    router.visit(url);
};
let deleteUserModal = ref(false)
let confirmMessage = ref(null)
let isLoading = ref(false)
let deleteUser = (level_id) => {
    confirmMessage.value = {
        heading: 'Delete Level',
        confirm: 'Are you sure you want to delete this level?',
        level_id: level_id
    }
    deleteUserModal.value = true
};
let actionToDeleteLevel = () => {
    isLoading.value = true
    axios.delete(`/admin/internal-agent-level/destroy/${confirmMessage.value.level_id}`)
        .then((res) => {
            deleteUserModal.value = false
            toaster("success", res.data.message)
            if (props.levels.current_page === 1) {
                router.visit('/admin/internal-agent-levels')
            } else {
                router.visit(`/admin/internal-agent-levels?page=${props.levels.current_page}`);
            }


        }).catch((error) => {
            if (error.response.status === 400) {
                toaster("error", error.response.data.message);
                deleteUserModal.value = false
                isLoading.value = false
            } else {
                toaster("error", error.message);
            }
        })
}
</script>

<template>
    <Head title="Agent Information" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Agents Level
            </h2>
        </template>

        <div class="pt-14 flex justify-between flex-wrap px-16 ">

            <div>
                <div class="text-4xl text-custom-sky font-bold mb-6">Agents Levels</div>
            </div>
            <div class="">
                <PrimaryButton @click="addAgentLevel(levels.current_page)">Add Level</PrimaryButton>
            </div>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="px-4 sm:px-8 sm:rounded-lg">
                <hr class="mb-4" />
            </div>
        </div>
        <section v-if="levels.data.length" class="p-3">
            <div class="mx-auto max-w-screen-xl sm:px-12">
                <div class="relative sm:rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-400">
                            <thead class="text-xs text-gray-300 uppercase bg-sky-900">
                                <tr>
                                    <th scope="col" class="px-4 py-3">Order</th>
                                    <th scope="col" class="px-4 py-3"> Name</th>
                                    <th scope="col" class="px-4 py-3 text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="level in levels.data" :key="level.id" class="border-b border-gray-500">
                                    <td class="text-gray-600 px-4 py-3">{{ level.order }}</td>
                                    <td class="text-gray-600 px-4 py-3">{{ level.name }}</td>
                                    <td class="text-gray-700 px-4 py-3 flex items-center justify-end">
                                        <button @click="editAgentLevel(level, levels.current_page)">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4  mr-2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                            </svg>

                                        </button>
                                        <button @click="deleteUser(level.id)">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-red-600 mr-1">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <nav class="flex justify-between my-4" v-if="levels.links">
                            <div v-if="levels">
                                <span class="text-sm text-gray-700">
                                    Showing
                                    <span class="font-semibold text-gray-900">{{ levels.from }}</span>
                                    to
                                    <span class="font-semibold text-gray-900">{{ levels.to }}</span> of
                                    <span class="font-semibold text-gray-900">{{ levels.total }}</span>
                                    Entries
                                </span>
                            </div>

                            <ul class="inline-flex -space-x-px text-base h-10">
                                <li v-for="(link, index) in levels.links" :key="link.label"
                                    :class="{ disabled: link.url === null }">
                                    <a href="#" @click.prevent="paginate(link.url)" :class="[
                                        'flex items-center justify-center px-4 h-10 border border-gray-300',
                                        link.active
                                            ? 'text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-700'
                                            : 'leading-tight text-gray-500 bg-white hover:bg-gray-100 hover:text-gray-700',
                                        {
                                            'rounded-l-lg': index === 0,
                                            'rounded-r-lg': index === levels.links.length - 1,
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

        <section v-else class="p-3">
            <p class="text-center text-gray-600">No Level yet.</p>
        </section>
        <Modal :show="addAgentLevelModal" @close="addAgentLevelModal = false">
            <LevelModal :addAgentLevelModal="addAgentLevelModal" :currentPage="currentPage" :levelData="levelData"
                @close="addAgentLevelModal = false" :modal_type="modal_type"></LevelModal>
        </Modal>
        <DeleteModal :isLoading="isLoading" @actionToDeleteUser="actionToDeleteLevel" :deleteUserModal="deleteUserModal"
            @close="deleteUserModal = false" :confirmMessage="confirmMessage" />

    </AuthenticatedLayout>
</template>


