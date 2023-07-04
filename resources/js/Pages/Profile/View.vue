<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AuthenticatedButton from '@/Components/AuthenticatedButton.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, toRef } from 'vue';

defineProps({
    user: {
        type: Object,
    },
});

let formatDate = date => {

    const dateObj = new Date(date);

    const formattedDate = dateObj.toLocaleDateString("en-US", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });

    return formattedDate;
}
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Profile</h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8  sm:rounded-lg">
                    <div class="text-4xl text-custom-sky font-bold mb-6">My Profile</div>
                    <hr class="mb-8">

                    <div class="flex flex-col items-start sm:flex-row sm:items-center sm:space-x-8 mb-4 lg:mb-10 relative">
                        <img class="w-30 h-30 mb-6 rounded-full" src="/img/profile-picture.jpg" alt="">
                        <div class="font-medium text-white">
                            <div class="text-4xl text-custom-white">{{ user.first_name }} {{ user.last_name }}</div>
                            <div class="text-lg text-gray-400">Joined on {{ formatDate(user.created_at)
                            }}</div>
                            <div class="flex items-center gap-2">
                                <!-- <div class="text-md text-gray-500">Balance: </div> -->
                                <div class="text-2xl text-custom-green">${{ user.balance }}</div>
                            </div>
                        </div>
                        <Link href="/profile/edit" class="border border-gray-400 ease-in cursor-pointer bg-white bg-opacity-5 hover:shadow-2xl hover:bg-white hover:text-custom-blue hover:bg-opacity-80 
                                    rounded px-3 py-3 font-bold text-md text-custom-white absolute right-0">Edit
                            Profile</Link>
                        </div>
                        <hr class="sm:hidden mb-10">

                    <div class="text-4xl text-custom-sky font-bold mb-6">Personal Information</div>
                    <hr class="mb-4">

                    <div class="grid grid-cols-2 gap-10 mb-12">
                        <div class="flex flex-col space-y-2 h-full overflow-auto">
                            <div class="text-sm text-gray-400 font-bold">First Name</div>
                            <div class="text-md sm:text-xl text-custom-white font-bold">{{ user.first_name }}</div>
                        </div>
                        <div class="flex flex-col space-y-2 h-full overflow-auto">
                            <div class="text-sm text-gray-400 font-bold">Last Name</div>
                            <div class="text-md sm:text-xl text-custom-white font-bold">{{ user.last_name }}</div>
                        </div>
                        <div class="flex flex-col space-y-2 h-full overflow-auto">
                            <div class="text-sm text-gray-400 font-bold">Email Address</div>
                            <div class="text-md sm:text-xl text-custom-white font-bold flex-grow">{{ user.email }}</div>
                        </div>
                        <div class="flex flex-col space-y-2 h-full overflow-auto">
                            <div class="text-sm text-gray-400 font-bold">Phone</div>
                            <div class="text-md sm:text-xl text-custom-white font-bold">{{ user.phone }}</div>
                        </div>
                    </div>


                    <div class="text-4xl text-custom-sky font-bold mb-6">Licences</div>
                    <hr class="mb-4">

                </div>

                <div class="flex p-4 sm:p-8 sm:rounded-lg">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
