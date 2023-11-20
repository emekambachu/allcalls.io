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
let addAgentLevel = () => {
    addAgentLevelModal.value = true
    modal_type.value = 1
}
let levelData = ref(null)
let editAgentLevel = (level, page) => {
    addAgentLevelModal.value = true
    modal_type.value = 2
    levelData.value = level
}
let fetchlevels = (page) => {
    // Create URL object from page
    let url = new URL(page);
    // Ensure the protocol is https
    if (url.protocol !== "https:") {
        url.protocol = "https:";
    }
    // Get the https URL as a string
    let httpsPage = url.toString();
    router.visit(httpsPage, { method: "get" });
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
let actionToDeleteUser = () => {
  isLoading.value = true
  axios.delete(`/admin/internal-agent-level/destroy/${confirmMessage.value.level_id}`)
    .then((res) => {
      deleteUserModal.value = false
      toaster("success", res.data.message)
      router.visit('/admin/customers')
    }).catch((error) => {
      isLoading.value = false
      toaster("error", error.message)
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
                <PrimaryButton @click="addAgentLevel()">Add Level</PrimaryButton>
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
                                    <th scope="col" class="px-4 py-3">ID</th>
                                    <th scope="col" class="px-4 py-3"> Name</th>
                                    <th scope="col" class="px-4 py-3 text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="level in levels.data" :key="level.id" class="border-b border-gray-500">
                                    <td class="text-gray-600 px-4 py-3">{{ level.id }}</td>
                                    <td class="text-gray-600 px-4 py-3">{{ level.name }}</td>
                                    <td class="text-gray-700 px-4 py-3 flex items-center justify-end">
                                        <button @click="deleteUser(level.id)">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-red-600 mr-1">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                        <!-- <button title="Edit Agent" @click="editAgentLevel(level , levels.current_page)"
                                            class="inline-flex items-center mx-2 p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none"
                                            type="button">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                            </svg>
                                        </button> -->

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="p-4">
                            <nav class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4"
                                aria-label="Table navigation">
                                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                                    Showing
                                    <span class="font-semibold text-custom-blue">{{
                                        levels.current_page
                                    }}</span>
                                    of
                                    <span class="font-semibold text-custom-blue">{{
                                        levels.last_page
                                    }}</span>
                                </span>
                                <ul class="inline-flex items-stretch -space-x-px cursor-pointer">
                                    <li>
                                        <a v-if="levels.prev_page_url" @click="fetchlevels(levels.prev_page_url)"
                                            class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-custom-white rounded-l-lg hover:bg-sky-950 hover:shadow-2xl hover:text-white">
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
                                            class="flex items-center justify-center text-sm py-2 px-3 leading-tight font-extrabold text-gray-500 bg-custom-white shadow-2xl hover:bg-sky-950 hover:shadow-2xl hover:text-white">{{
                                                levels.current_page }}
                                        </a>
                                    </li>

                                    <li>
                                        <a v-if="levels.next_page_url" @click="fetchlevels(levels.next_page_url)"
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
                </div>
            </div>
        </section>

        <section v-else class="p-3">
            <p class="text-center text-gray-600">No Level yet.</p>
        </section>
        <Modal :show="addAgentLevelModal" @close="addAgentLevelModal = false">
            <LevelModal :addAgentLevelModal="addAgentLevelModal" :levelData="levelData" @close="addAgentLevelModal = false"
                :modal_type="modal_type"></LevelModal>
        </Modal>
        <DeleteModal :isLoading="isLoading" @actionToDeleteUser="actionToDeleteUser" :deleteUserModal="deleteUserModal"
            @close="deleteUserModal = false" :confirmMessage="confirmMessage" />

    </AuthenticatedLayout>
</template>


