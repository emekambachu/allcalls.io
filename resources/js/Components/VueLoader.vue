<script setup>
let props = defineProps({
    slidingLoader: Boolean,

});
</script>
<style scoped>
.loader-overlay {
    background-color: rgba(0, 0, 0, 0.3);
    position: fixed;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
<template>
    <div v-if="slidingLoader" class="fixed top-0 left-0 w-full h-full flex items-center justify-center z-50">
        <div class="loader-overlay">
            <vue-loaders name="ball-spin-fade-loader" color="black" scale="1"></vue-loaders>
        </div>
    </div>
</template>
