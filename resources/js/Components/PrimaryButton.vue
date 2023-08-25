<script setup>
let { disabled } = defineProps(['disabled'])
</script>

<template>
    <button
        class="inline-flex items-center px-4 py-3 border border-transparent rounded-md font-semibold text-md uppercase tracking-widest transition ease-in-out duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
        :class="{
            'bg-custom-blue text-custom-green hover:bg-white hover:drop-shadow-2xl hover:text-custom-blue hover:ring-2 hover:ring-custom-sky focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300': !disabled,
            'cursor-not-allowed bg-gray-400 text-gray-500': disabled
        }" :disabled="disabled">
        <slot />
    </button>
</template>
