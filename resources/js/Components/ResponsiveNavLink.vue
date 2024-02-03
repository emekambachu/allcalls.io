<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
    disabledNavLink:Boolean,
    disabledNavLinkTraining:Boolean,
});

const classes = computed(() =>
    props.active
        ? 'block w-full pl-3 pr-4 py-2 border-l-4 border-green-700 shadow-2xl text-left text-base font-extrabold text-custom-white bg-custom-blue focus:outline-none focus:text-indigo-800 focus:border-indigo-700 transition duration-150 ease-in-out'
        : 'block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-300 hover:text-gray-400 hover:bg-cyan-950 hover:border-cyan-800 focus:outline-none focus:text-custom-white focus:bg-sky-950 focus:border-custom-blue transition duration-150 ease-in-out'
);
</script>

<template>
    <Link :href="href" :class="[classes, {'disabledNavLink': disabledNavLink }, {'disabledNavLink': disabledNavLinkTraining }, ]" >
        <slot />
    </Link>
</template>
<style>
.disabledNavLink{
    pointer-events: none;
   
};
</style>
