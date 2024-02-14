<script setup>
let { callId } = defineProps(['callId'])


onMounted(() => {
    console.log('Call ID:', callId);
})
</script>

<template>
    <div class="bg-white p-2">
        <h1>Call Logs for Call#{{ callId }}</h1>
    </div>
</template>