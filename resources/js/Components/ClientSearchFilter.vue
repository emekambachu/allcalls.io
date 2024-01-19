<script setup>
import { ref, reactive, defineEmits, onMounted, computed, watch } from "vue";
import TextInput from "@/Components/TextInput.vue";
import { router } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { toaster } from "@/helper.js";
import { Popover, PopoverButton, PopoverPanel } from "@headlessui/vue";
import { faL } from "@fortawesome/free-solid-svg-icons";
import axios from "axios";

let { route, allClients, requestData } = defineProps({
    route: String,
    allClients: Array,
    requestData: Array,
});
let form = ref({});
let users = ref([])
if (allClients) {
    users.value = allClients
}
const userPopoverRef = ref(null);
let paymentSubmitedByLabel = ref(null)
let filterUserBy = ref('email');
if (requestData) {
    const keys = Object.keys(requestData);
    keys.forEach(key => {
        filterUserBy.value = key
    })
}
let clientsLoader = ref(false)
let userFilterTerm = ref('');
let offset = ref(0)
let limit = ref(2)
let hasMoreRecords = ref(true)

let getClient = async () => {
    clientsLoader.value = true
    await axios.post(route, {
        [filterUserBy.value]: userFilterTerm.value,
        // 'offset': offset.value,
        // 'limit': limit.value,

    }).then((response) => {
        console.log('response', response);
        users.value = response.data.allClients
        clientsLoader.value = false
    }).catch((error) => {
        clientsLoader.value = false
    })
}
let loadMoreRecords = async  () => {
    clientsLoader.value = true
    await axios.post(route, {
        [filterUserBy.value]: userFilterTerm.value,
        'offset': offset.value,
        'limit': limit.value,

    }).then((response) => {
        console.log('response', response);
        users.value = response.data.allClients
        clientsLoader.value = false
    }).catch((error) => {
        clientsLoader.value = false
    })
}
watch(
    () => userFilterTerm.value,
    (newVal, oldVal) => {
        if (newVal || newVal === '') {
            getClient()
        }
    }
);
onMounted(() => {
})
let filteredUsers = computed(() => {
    // Convert the filter term to lowercase for case-insensitive comparison
    let filterTerm = userFilterTerm.value.toLowerCase();
    // Filter the users array based on email
    console.log('users', users.value);
    return users.value.filter(user => {
        // Check if the user's email contains the filter term
        // Assuming each user object has an 'email' property
        if (filterUserBy.value === 'phone') {
            return user.phone.toLowerCase().includes(filterTerm);
        } else if (filterUserBy.value === 'email') {
            return user.email.toLowerCase().includes(filterTerm);
        } else if (filterUserBy.value === 'name') {
            return (
                user.first_name.toLowerCase().includes(filterTerm) ||
                user.last_name.toLowerCase().includes(filterTerm)
            )
        }
    });
});
if (requestData.phone) {
    paymentSubmitedByLabel.value = requestData.phone
}
if (requestData.email) {
    paymentSubmitedByLabel.value = requestData.email
}
if (requestData.name) {
    paymentSubmitedByLabel.value = requestData.name
}
let selectUser = (user) => {
    if (filterUserBy.value == 'phone') {
        paymentSubmitedByLabel.value = user.phone

    } else if (filterUserBy.value == 'email') {
        paymentSubmitedByLabel.value = user.email
    } else if (filterUserBy.value == 'name') {
        paymentSubmitedByLabel.value = user.first_name + " " + user.last_name
    }

    userPopoverRef.value = false
}


let firstStepErrors = ref({})
let isLoading = ref(false)
let isLoadingReset = ref(false)
let fetchData = (e) => {
    firstStepErrors.value.paymentSubmitedByLabel = ['']
    if (!paymentSubmitedByLabel.value) {
        firstStepErrors.value.paymentSubmitedByLabel = ['Please select the filter.']
        return
    }

    isLoading.value = true
    try {
        router.visit(`${route}?${filterUserBy.value}=${paymentSubmitedByLabel.value}`)
        isLoading.value = false
    } catch (error) {
        console.log('error', error);
    }
}
let ClearFilter = () => {

    isLoadingReset.value = true
    try {
        const baseUrl = route.split('?')[0];
        router.visit(baseUrl)
        isLoadingReset.value = false
    } catch (error) {

    }
}

</script>

<template>
    <div
        class="mx-auto px-4 sm:px-8 md:px-16 grid sm:grid-cols-1 xl:grid-cols-2 lg:grid-cols-3 md:grid-cols-2 gap-2 text-gray-700 mb-5">
        <div>
            <Popover ref="userPopoverRef" class="relative mr-2 block w-full">
                <PopoverButton class="h-full w-full flex">
                    <button type="button"
                        class="text-left h-full w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        Filter By: {{ paymentSubmitedByLabel }}
                    </button>

                </PopoverButton>
                <PopoverPanel class="absolute z-10" style="width: 100%">
                    <div class="border border-gray-100 p-3 shadow bg-white mt-2 rounded-md">
                        <ul
                            class="items-center w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg sm:flex">
                            <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                                <div class="flex items-center ps-3">
                                    <input id="horizontal-list-radio-email" type="radio" value="email" name="list-radio"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2"
                                        v-model="filterUserBy" />
                                    <label for="horizontal-list-radio-email"
                                        class="w-full py-3 ms-2 text-sm font-medium text-gray-900">Filter by email</label>
                                </div>
                            </li>
                            <li class="w-full">
                                <div class="flex items-center ps-3">
                                    <input id="horizontal-list-radio-phone" type="radio" value="phone" name="list-radio"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2"
                                        v-model="filterUserBy" />
                                    <label for="horizontal-list-radio-phone"
                                        class="w-full py-3 ms-2 text-sm font-medium text-gray-900">Filter by phone
                                    </label>
                                </div>
                            </li>
                            <li class="w-full">
                                <div class="flex items-center ps-3">
                                    <input id="horizontal-list-radio-name" type="radio" value="name" name="list-radio"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2"
                                        v-model="filterUserBy" />
                                    <label for="horizontal-list-radio-name"
                                        class="w-full py-3 ms-2 text-sm font-medium text-gray-900">Filter by Name</label>
                                </div>
                            </li>

                        </ul>

                        <div class="mt-2">
                            <input v-model="userFilterTerm" :placeholder="`Filter by ${filterUserBy}`"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" />
                        </div>

                        <div style="max-height: 300px; overflow-y: scroll;" class="mt-3">
                            <ul class="max-w-md divide-y divide-gray-200">
                                <global-spinner class="text-center" :spinner="clientsLoader" />
                                <li v-if="!clientsLoader" v-for="user in users" :key="user.id">
                                    <div @click.prevent="selectUser(user)"
                                        class="cursor-pointer flex items-center space-x-4 rtl:space-x-reverse hover:bg-gray-50 p-2">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ user.first_name }} {{ user.last_name }}
                                            </p>
                                            <p class="text-sm text-gray-500 truncate">{{ user.email }} - {{ user.phone }}
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <!-- <div>
                            <button class="text-blue-600 mt-4" @click="loadMoreRecords">See More</button>
                        </div> -->
                    </div>
                </PopoverPanel>
            </Popover>
            <div v-if="firstStepErrors.paymentSubmitedByLabel" class="text-red-500 mt-1"
                v-text="firstStepErrors.paymentSubmitedByLabel[0]"></div>

        </div>
        <div>

            <PrimaryButton type="button" class="ml-2" @click.prevent="fetchData">
                <global-spinner :spinner="isLoading" /> Filter
            </PrimaryButton>
            <button @click.prevent="ClearFilter()" type="button" class="button-custom-back px-4 py-3 rounded-md ml-2">
                <global-spinner :spinner="isLoadingReset" /> Reset
            </button>
        </div>
    </div>
</template>
<style scoped>
.spnClassLocked {
    color: #fb4040;
}

.button-custom-back {
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    font-weight: 600;
    border-width: 1px;
    align-items: center;
    display: inline-flex;
    border-color: rgb(107 114 128 / var(--tw-border-opacity));
}

.button-custom-back:hover {
    background-color: #03243d;
    color: #3cfa7a;
    transition-duration: 150ms;
}
</style>