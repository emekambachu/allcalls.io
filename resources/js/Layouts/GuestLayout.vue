<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-custom-indigo flex flex-col sm:justify-center items-center pt-6 sm:pt-0 ">
        <Link href="/">
        <ApplicationLogo class="fill-current text-gray-500" />
        </Link>
        <div class="w-full sm:max-w-3xl mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            <slot />
        </div>
    </div>
</template>
