<script setup>
import { onMounted, ref } from "vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { toaster } from "@/helper.js";
import AddModal from "@/Pages/InternalAgent/MyBusiness/AddModal.vue";
import ViewDetailCom from "@/Pages/InternalAgent/MyBusiness/ViewDetail.vue";
import Modal from "@/Components/Modal.vue";

import { endOfMonth, endOfYear, startOfMonth, subDays, startOfYear, subMonths, startOfWeek, endOfWeek, subWeeks, startOfQuarter, endOfQuarter, subQuarters } from 'date-fns';

const presetDates = ref([
    { label: 'Today', value: [new Date(), new Date()] },
    {
        label: 'Today (Slot)',
        value: [new Date(), new Date()],
        slot: 'preset-date-range-button'
    },
    { label: 'This month', value: [startOfMonth(new Date()), endOfMonth(new Date())] },
    {
        label: 'Last month',
        value: [startOfMonth(subMonths(new Date(), 1)), endOfMonth(subMonths(new Date(), 1))],
    },
    { label: 'This year', value: [startOfYear(new Date()), endOfYear(new Date())] },
    {
        label: 'Last 7 Days',
        value: [subDays(new Date(), 6), new Date()],
    },
    {
        label: 'Last 14 Days',
        value: [subDays(new Date(), 13), new Date()],
    },
    {
        label: 'Last 30 Days',
        value: [subDays(new Date(), 29), new Date()],
    },
    {
        label: 'This Week',
        value: [startOfWeek(new Date()), endOfWeek(new Date())],
    },
    {
        label: 'Last Week',
        value: [startOfWeek(subWeeks(new Date(), 1)), endOfWeek(subWeeks(new Date(), 1))],
    },


]);



let { agentInvites, states, requestData , agents } = defineProps({
    businesses: {
        required: true,
        type: Array,
    },
    states: Array,
    requestData: Array,
    agents:Array,
});


let slidingLoader = ref(false)



let page = usePage();
if (page.props.flash.message) {
    toaster("success", page.props.flash.message);
}
onMounted(() => {

});

let dateRange = ref(null)
if (requestData.from && requestData.to) {
    // Convert date strings to Date objects
    const fromDate = new Date(requestData.from);
    const toDate = new Date(requestData.to);
    // Set the date range initially
    dateRange.value = [fromDate, toDate];
}
let filterBusiness = () => {

    if (dateRange.value) {
        const from = new Date(dateRange.value[0]);
        const to = new Date(dateRange.value[1]);
        // Extract month, date, and year components
        const fromMonth = from.getMonth() + 1; // Add 1 because months are zero-based
        const fromDate = from.getDate();
        const fromYear = from.getFullYear();

        const toMonth = to.getMonth() + 1;
        const toDate = to.getDate();
        const toYear = to.getFullYear();

        // Format the components as desired (e.g., as "MM-DD-YYYY")
        const formattedFrom = `${fromMonth}/${fromDate}/${fromYear}`;
        const formattedTo = `${toMonth}/${toDate}/${toYear}`;
        const queryParams = {
            from: formattedFrom,
            to: formattedTo,
        };
        router.visit(page.url, {
            data: queryParams,
        });
    }



}

// get Agent invites by pagination start
let fetchagentInvites = (page) => {
    let url = new URL(page);
    // Ensure the protocol is https
    if (url.protocol !== "https:") {
        url.protocol = "https:";
    }

    // Get the https URL as a string
    let httpsPage = url.toString();

    router.visit(httpsPage, { method: "get" });

};
// get Agent invites by pagination end
let viewDetailModal = ref(false)
let addBusinessModal = ref(false)
let businessData = ref(null)
let ViewDetail = (business_data) => {
    let selectedState = states.find(state => state.id === business_data.client_state)
    if (selectedState) {
        business_data.client_state_name = selectedState.full_name;
    }
    businessData.value = business_data
    viewDetailModal.value = true
}

let addBusiness = () => {
    businessData.value = null
    addBusinessModal.value = true
}
let EditBusiness = (business_data) => {
    addBusinessModal.value = true
    businessData.value = business_data
}
</script>
<style scoped>
/deep/ .dp__pointer {
    height: 42px;
}
</style>
<template>
    <Head title="Agent Invites" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                My Business
            </h2>
        </template>
        <vue-loader :slidingLoader="slidingLoader" />
        <div class="pt-14">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 rounded-lg bg-white">
                    <div class="flex justify-between">
                        <div>
                            <h1 class="text-4xl text-custom-sky font-bold mb-6">
                              <span v-if="$page.props.auth.role == 'admin'">Report</span> <span v-else>My</span>   Business
                            </h1>
                        </div>

                        <div >
                            <PrimaryButton @click="addBusiness()">Report Application </PrimaryButton>
                        </div>
                    </div>
                    <div class="flex mb-5">
                        <div style="width:40%;">
                            <VueDatePicker v-model="dateRange" range :preset-dates="presetDates"
                                placeholder="Picker date range" format="dd-MMM-yyyy" :multi-calendars="{ solo: true }"
                                auto-apply />
                        </div>
                        <PrimaryButton type="button" class="ml-4" @click.prevent="filterBusiness">
                            <global-spinner :spinner="isLoading" /> Filter
                        </PrimaryButton>
                    </div>
                    <hr class="mb-4" />
                    <div class="mx-auto max-w-screen-xl sm:px-12">
                        <div class="relative sm:rounded-lg overflow-hidden">
                        </div>
                    </div>
                    <div class="overflow-x-auto" v-if="businesses.data.length">
                        <table class="w-full text-sm text-left text-gray-400">
                            <thead class="text-xs text-gray-300 uppercase bg-sky-900">
                                <tr class="business-table-custom">
                                    <th scope="col" class="px-4 py-3">ID</th>
                                    <th v-if="$page.props.auth.role == 'admin'" scope="col" style="min-width: 150px;" class="px-4 py-3">Agent Name</th>
                                    <th scope="col" style="min-width: 150px;" class="px-4 py-3">Client Name</th>
                                    <th scope="col" style="min-width: 150px;" class="px-4 py-3">Application Date</th>
                                    <th scope="col" style="min-width: 150px;" class="px-4 py-3">Draft Date</th>
                                    <th scope="col" style="min-width: 150px;" class="px-4 py-3">Carrier</th>
                                    <th scope="col" style="min-width: 150px;" class="px-4 py-3">Product</th>
                                    <th scope="col" style="min-width: 150px;" class="px-4 py-3">APV (Annual Premium Volume)
                                    </th>
                                    <th scope="col" style="min-width: 150px;" class="px-4 py-3">Coverage Amount</th>
                                    
                                    <!-- <th scope="col" style="min-width: 150px;" class="px-4 py-3">Status</th> -->
                                    <th scope="col" style="min-width: 150px;" class="px-4 py-3">App Type</th>
                                    <th v-if="$page.props.auth.role == 'internal-agent'" scope="col" style="min-width: 150px;" class="px-4 py-3">Agent Name</th>
                                    <th scope="col" style="min-width: 100px;" class="px-4 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-gray-500" v-for="(businesse, index) in businesses.data"
                                    :key="businesse.id">
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse.id"></td>
                                    <td v-if="$page.props.auth.role == 'admin'" class="text-gray-600 px-4 py-3" v-text="businesse?.agent_full_name"></td>

                                    <td class="text-gray-600 px-4 py-3">{{ businesse.first_name }} {{ businesse.last_name }}
                                    </td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.application_date"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.policy_draft_date"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.insurance_company"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.product_name"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.premium_volumn"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.coverage_amount"></td>
                                    <!-- <td class="text-gray-600 px-4 py-3">status</td> -->
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.source_of_lead"></td>
                                    <td v-if="$page.props.auth.role == 'internal-agent'" class="text-gray-600 px-4 py-3" v-text="businesse?.agent_full_name"></td>
                                    <td class="text-gray-600 px-4 py-3">
                                        <button  @click="ViewDetail(businesse)">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </button>

                                        <button v-if="$page.props.auth.role === 'admin'" class="ml-3"  @click="EditBusiness(businesse)">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                            </svg>
                                        </button>

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
                                        businesses.current_page
                                    }}</span>
                                    of
                                    <span class="font-semibold text-custom-blue">{{
                                        businesses.last_page
                                    }}</span>
                                </span>
                                <ul class="inline-flex items-stretch -space-x-px cursor-pointer">
                                    <li>
                                        <a v-if="businesses.prev_page_url"
                                            @click="fetchagentInvites(businesses.prev_page_url)"
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
                                                businesses.current_page }}
                                        </a>
                                    </li>

                                    <li>
                                        <a v-if="businesses.next_page_url"
                                            @click="fetchagentInvites(businesses.next_page_url)"
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


                    <div v-else class="text-center py-4 text-gray-800">
                        No Business yet.
                    </div>
                </div>
            </div>
        </div>

        <AddModal v-if="addBusinessModal" :agents="agents" :states="states" :addBusinessModal="addBusinessModal"
            @close="addBusinessModal = false" :businessData="businessData" :reIniteAgent="reIniteAgent" />


        <ViewDetailCom v-if="viewDetailModal" :viewDetailModal="viewDetailModal" @close="viewDetailModal = false"
            :businessData="businessData" />


    </AuthenticatedLayout>
</template>
