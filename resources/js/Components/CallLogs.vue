<script setup>
import { onMounted } from "vue";
import axios from "axios";

let { callId } = defineProps(["callId"]);
onMounted(() => {
  axios
    .get(`/admin/web-api/${callId}/call-logs`)
    .then((response) => {
      console.log(response.data);
    })
    .catch((error) => {
      console.log(error);
    });
});
</script>

<template>
  <div class="bg-white p-2">
    <h1>Call Logs for Call#{{ callId }}</h1>
  </div>
</template>
