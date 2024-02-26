<script setup>
import { computed , ref } from 'vue';
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
    // disabledNavLinkTraining:Boolean,
});
const classes = computed(() =>
    props.active
        ? 'inline-flex items-center p-2 border-indigo-400 font-semibold text-lg leading-27 leading-none text-custom-green dark:hover:bg-gray-700 rounded-lg transition duration-150 ease-in-out'
        : 'inline-flex items-center p-2 border-transparent text-lg font-medium leading-5 text-custom-white hover:text-custom-green hover:border-gray-300 dark:hover:bg-gray-700 rounded-lg focus:outline-none focus:text-custom-sky dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out'
);

</script>

<template>
    <Link :href="href" :class="[classes, {'disabledNavLink': disabledNavLink } ]"    >
        <slot  />

    </Link>
</template>
<style>
.disabledNavLink{
    pointer-events: none;

};
</style>
