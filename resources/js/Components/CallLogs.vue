<script setup>
import { onMounted, ref } from "vue";
import axios from "axios";

let { callId } = defineProps(["callId"]);
const groupedDeviceLogs = ref({});

onMounted(() => {
  axios
    .get(`/admin/web-api/${callId}/call-logs`)
    .then((response) => {
      groupedDeviceLogs.value = response.data.groupedDeviceLogs;
    })
    .catch((error) => {
      console.error(error);
    });
});

function deviceName(device) {
  return device.device_type + " #" + device.id;
}

function formatDateTime(dateTime) {
  const options = {
    year: "numeric",
    month: "short",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  };
  return new Date(dateTime).toLocaleDateString("en-US", options);
}
</script>

<template>
  <div class="bg-white p-2">
    <h1>Call Logs for Call#{{ callId }}</h1>
    <div v-for="(deviceLogs, deviceId) in groupedDeviceLogs" :key="deviceId" class="p-4">
      <h3 class="mb-4">{{ deviceName(deviceLogs[0].device) }}</h3>
      <ol class="relative border-l border-gray-200">
        <li v-for="log in deviceLogs" :key="log.id" class="mb-10 ml-4">
          <div
            class="absolute -left-1.5 mt-1.5 h-3 w-3 rounded-full border border-white bg-gray-200"
          ></div>
          <time class="mb-1 text-sm font-normal leading-none text-gray-900"
            >{{ log.action }} ({{ formatDateTime(log.created_at) }})</time
          >
        </li>
      </ol>
    </div>
  </div>
</template>
