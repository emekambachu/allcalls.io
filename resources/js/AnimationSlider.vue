<script setup>
let props = defineProps({
    slidingLoader: Boolean,

});
</script>
<template>
    <div v-if="slidingLoader" class="animate-pulse flex space-x-4 px-12">
        <div class="flex-1 space-y-6 py-1">
            <div class="h-7 bg-slate-200 rounded"></div>
            <div class="h-7 bg-slate-200 rounded"></div>
            <div class="h-7 bg-slate-200 rounded"></div>
            <div class="h-7 bg-slate-200 rounded"></div>
            <div class="h-7 bg-slate-200 rounded"></div>
        </div>
    </div>
</template>