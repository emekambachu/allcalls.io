<script setup>
import { onMounted, ref } from "vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { toaster } from "@/helper.js";
import AddModal from "@/Pages/InternalAgent/MyBusiness/AddModal.vue";


let { agentInvites, states } = defineProps({
    businesses: {
        required: true,
        type: Array,
    },
    states: Array,
});
let firstStepErrors = ref({})
let slidingLoader = ref(false)

let page = usePage();
if (page.props.flash.message) {
    toaster("success", page.props.flash.message);
}
onMounted(() => {

});
let addBusinessModal = ref(false)

let addBusiness = () => {
    addBusinessModal.value = true
}
// get Agent invites by pagination start
let fetchagentInvites = (page) => {
    alert('please set the pagination')
    return
    slidingLoader.value = true
    const urlParams = new URLSearchParams(new URL(page).search);
    const pageValue = urlParams.get('page');
    axios.get(`/internal-agent/get-agent-invites?page=${pageValue}`)
        .then((response) => {

        }).catch((error) => {
            console.log('error agent', error);
            slidingLoader.value = false
        })

};
// get Agent invites by pagination end
</script>
<style scoped></style>
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
                                My Business
                            </h1>
                        </div>
                        <div>
                            <PrimaryButton @click="addBusiness()">Report Application</PrimaryButton>
                        </div>
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
                                    <th scope="col" style="min-width:145px;" class="px-4 py-3">Agent Full Name</th>
                                    <th scope="col" style="min-width:145px;" class="px-4 py-3">Agent Email</th>
                                    <th scope="col" style="min-width:100px;" class="px-4 py-3">EF Number</th>
                                    <th scope="col" style="min-width:140px;" class="px-4 py-3">Upline Manager</th>
                                    <th scope="col" style="min-width:100px;" class="px-4 py-3">split sale</th>
                                    <th scope="col" style="min-width:130px;" class="px-4 py-3">split sale Type</th>
                                    <th scope="col" class="px-4 py-3">split Agent Email</th>
                                    <th scope="col" style="min-width:166px;" class="px-4 py-3">Insurance Company</th>
                                    <th scope="col" class="px-4 py-3">Product Name</th>
                                    <th scope="col" class="px-4 py-3">Application Date</th>
                                    <th scope="col" class="px-4 py-3">Coverage Amount</th>
                                    <th scope="col" class="px-4 py-3">Coverage Length</th>
                                    <th scope="col" class="px-4 py-3">Premium Frequency</th>
                                    <th scope="col" class="px-4 py-3">Premium Amount</th>
                                    <th scope="col" class="px-4 py-3">Annual Premium Volume</th>
                                    <th scope="col" class="px-4 py-3">Equis writing number</th>
                                    <th scope="col" class="px-4 py-3">Carrier Writing Number</th>
                                    <th scope="col" class="px-4 py-3">App from a lead</th>
                                    <th scope="col" class="px-4 py-3">Source of the lead</th>
                                    <th scope="col" class="px-4 py-3">Appointment Type</th>
                                    <th scope="col" class="px-4 py-3">Policy Draft Date</th>
                                    <th scope="col" class="px-4 py-3">First Name</th>
                                    <th scope="col" class="px-4 py-3">MI</th>
                                    <th scope="col" class="px-4 py-3">Last Name</th>
                                    <th scope="col" class="px-4 py-3">Date of Birth</th>
                                    <th scope="col" class="px-4 py-3">Gender</th>
                                    <th scope="col" class="px-4 py-3">Street Address 1</th>
                                    <th scope="col" class="px-4 py-3">Street Address 2</th>
                                    <th scope="col" class="px-4 py-3">City</th>
                                    <th scope="col" class="px-4 py-3">State</th>
                                    <th scope="col" class="px-4 py-3">Zip-code</th>
                                    <th scope="col" class="px-4 py-3">Client Phone Number</th>
                                    <th scope="col" class="px-4 py-3">Client Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-gray-500" v-for="(businesse, index) in businesses.data"
                                    :key="businesse.id">
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse.id"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.agent_full_name"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse.agent_email"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.ef_number"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.upline_manager"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.split_sale"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.split_sale_type"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.split_agent_email"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.insurance_company"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.product_name"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.application_date"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.coverage_amount"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.coverage_length"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.premium_frequency"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.quarterly_premium_amount"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.annually_premium_amount"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.equis_writing_number_carrier">
                                    </td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.carrier_writing_number"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.this_app_from_lead"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.source_of_lead"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.appointment_type"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.policy_draft_date"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.first_name"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.mi"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.last_name"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.dob"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.gender"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.client_street_address_1"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.client_street_address_2"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.city"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.client_state"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.client_zipcode"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.client_phone_no"></td>
                                    <td class="text-gray-600 px-4 py-3" v-text="businesse?.client_email"></td>
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
                        No invites yet.
                    </div>
                </div>
            </div>
        </div>
        <AddModal v-if="addBusinessModal" :states="states" :addBusinessModal="addBusinessModal"
            @close="addBusinessModal = false" :firstStepErrors="firstStepErrors" :reIniteAgent="reIniteAgent" />
    </AuthenticatedLayout>
</template>
