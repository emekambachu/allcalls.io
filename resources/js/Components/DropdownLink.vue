<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        required: true,
    },
    disabledNavLink:Boolean,
});
</script>

<template>
    <Link
        :href="href"
        :class="[{'disabledNavLink': disabledNavLink }]"
        class="block w-full px-4 py-2 text-left text-sm leading-5 text-custom-gray hover:text-gray-100 focus:outline-none transition duration-150 ease-in-out bg-custom-blue"
    >
        <slot />
    </Link>
</template>
