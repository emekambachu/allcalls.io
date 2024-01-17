<script setup>
import { ref, reactive, defineEmits, onMounted, computed } from "vue";
import TextInput from "@/Components/TextInput.vue";
import { router } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { toaster } from "@/helper.js";
import { Popover, PopoverButton, PopoverPanel  } from "@headlessui/vue";

let { route, users } = defineProps({
    route: String,
    users: Array,
});
let form = ref({});
const userPopoverRef = ref(null);
let filterUserBy = ref('email');
let userFilterTerm = ref('');
let filteredUsers = computed(() => {
    // Convert the filter term to lowercase for case-insensitive comparison
    let filterTerm = userFilterTerm.value.toLowerCase();
    // Filter the users array based on email
    return users.filter(user => {
        // Check if the user's email contains the filter term
        // Assuming each user object has an 'email' property
        if (filterUserBy.value === 'phone') {
            return user.phone.toLowerCase().includes(filterTerm);
        }
        return user.email.toLowerCase().includes(filterTerm);
    });
});
let selectUser = (user) => {
    console.log('user', user);
    console.log('filterUserBy', filterUserBy.value);
    
    console.log('userPopoverRef.value', userPopoverRef);
    userPopoverRef.value.doClose();
}

</script>

<template>
    <div
        class="mx-auto px-4 sm:px-8 md:px-16 grid sm:grid-cols-1 xl:grid-cols-2 lg:grid-cols-3 md:grid-cols-2 gap-2 text-gray-700 mb-5">
        <Popover ref="userPopoverRef" class="relative mr-2 block w-full">
            <PopoverButton class="h-full w-full flex">
                <button type="button"
                    class="text-left h-full w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    Payment Submitted By {{ paymentSubmitedByLabel }}
                </button>
            </PopoverButton>

            <PopoverPanel  class="absolute z-10" style="width: 100%">
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
                                    number</label>
                            </div>
                        </li>

                    </ul>

                    <div class="mt-2">
                        <input v-model="userFilterTerm" :placeholder="`Filter by ${filterUserBy}`"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" />
                    </div>

                    <div style="max-height: 300px; overflow-y: scroll;" class="mt-3">
                        <ul class="max-w-md divide-y divide-gray-200">
                            <li v-for="user in filteredUsers" :key="user.id">
                                <div @click.prevent="selectUser(user)"
                                    class="cursor-pointer flex items-center space-x-4 rtl:space-x-reverse hover:bg-gray-50 p-2">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ user.first_name }} {{ user.last_name }}
                                        </p>
                                        <p class="text-sm text-gray-500 truncate">{{ user.email }} - {{ user.phone }}</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </PopoverPanel>
        </Popover>
    </div>
</template>
<style>
.spnClassLocked {
    color: #fb4040;
}
</style>