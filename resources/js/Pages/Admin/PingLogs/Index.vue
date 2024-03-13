<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import { toaster } from "@/helper.js";

let page = usePage();
if (page.props.flash.message) {
    toaster("success", page.props.flash.message);
}

let props = defineProps({
    pingLogs: {
        type: Object,
    },
});

let paginate = (url) => {
    router.visit(url);
};


let currentPage = ref(null);

let formatTime = (duration) => {
    const minutes = Math.floor(duration / 60);
    const seconds = Math.floor(duration % 60);
    return `${minutes.toString().padStart(2, "0")}:${seconds.toString().padStart(2, "0")}`;
};

let formatDate = (inputDate) => {
    // Split the input date by the hyphen ("-") to get year, month, and day
    const [year, month, day] = inputDate.split("-");

    // Rearrange the components in the "mm-dd-yyyy" format
    const formattedDate = `${month}-${day}-${year}`;

    return formattedDate;
};

let capitalizeAndReplaceUnderscore = (str) => {
    // Replace underscores with spaces
    let result = str.replace(/_/g, " ");

    // Capitalize the first letter of each word
    result = result.replace(/\b(\w)/g, (match) => match.toUpperCase());

    return result;
};
</script>

<template>
    <Head title="Ping Logs" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Ping Logs
            </h2>
        </template>

        <div class="pt-14 flex justify-between px-16">
            <div>
                <div class="text-4xl text-custom-sky font-bold mb-6">Ping Logs</div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="px-4 sm:px-8 sm:rounded-lg">
                <hr class="mb-4" />
            </div>
        </div>
        <section v-if="pingLogs.data.length" class="p-3">
            <div class="sm:px-12">
                <div class="relative sm:rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-400">
                            <thead class="text-xs text-gray-300 uppercase bg-sky-900">
                            <tr>
                                <th scope="col" class="px-4 py-3">ID</th>
                                <th scope="col" class="px-4 py-3">Request</th>
                                <th scope="col" class="px-4 py-3">Response</th>
                                <th scope="col" class="px-4 py-3">Status</th>
                                <th scope="col" class="px-4 py-3">Created At</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="pingLog in pingLogs.data" :key="pingLog.id"
                                class="border-b border-gray-500">
                                <td class="text-gray-600 px-4 py-3">{{ pingLog.id }}</td>
                                <td class="text-gray-600 px-4 py-3">
                                    {{ pingLog.request }}
                                </td>
                                <td class="text-gray-600 px-4 py-3">
                                    {{ pingLog.response }}
                                </td>
                                <td class="text-gray-600 px-4 py-3">

                                    <span v-if="pingLog.status == 'success'" class="bg-green-600 text-white text-xs px-2 py-1 rounded-2xl">{{pingLog.status}}</span>
                                    <span v-else class="bg-red-600 text-white text-xs px-2 py-1 rounded-2xl">{{pingLog.status }}</span>
                                </td>

                                <td class="text-gray-600 px-4 py-3">
                                    {{ pingLog.created_at }}
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <nav class="flex justify-between my-4" v-if="pingLogs.links">
                            <div v-if="pingLogs">
                                    <span class="text-sm text-gray-700">
                                      Showing
                                      <span class="font-semibold text-gray-900">{{ pingLogs.from }}</span>
                                      to
                                      <span class="font-semibold text-gray-900">{{ pingLogs.to }}</span> of
                                      <span class="font-semibold text-gray-900">{{ pingLogs.total }}</span>
                                      Entries
                                    </span>
                            </div>

                            <ul class="inline-flex -space-x-px text-base h-10">
                                <li v-for="(link, index) in pingLogs.links" :key="link.label" :class="{ disabled: link.url === null }">
                                    <a href="#" @click.prevent="paginate(link.url)" :class="[
                    'flex items-center justify-center px-4 h-10 border border-gray-300',
                    link.active
                      ? 'text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-700'
                      : 'leading-tight text-gray-500 bg-white hover:bg-gray-100 hover:text-gray-700',
                    {
                      'rounded-l-lg': index === 0,
                      'rounded-r-lg': index === pingLogs.links.length - 1,
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
            <p class="text-center text-gray-600">No ping logs yet.</p>
        </section>

    </AuthenticatedLayout>
</template>

<style src="@vueform/multiselect/themes/default.css"></style>
<style>
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type="number"] {
    -moz-appearance: textfield;
}

.multiselect {
    color: black !important;
    border: none;
    border-radius: 10px;
}

.multiselect-wrapper {
    background-color: #d7d7d7;
    border-radius: 5px;
}

.box-shadow {
    padding: 20px;
    width: 97%;
    margin: auto;
    box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
}
</style>
