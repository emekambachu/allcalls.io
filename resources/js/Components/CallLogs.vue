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
  const userAgent = device.device_type;

  // Regular expressions for major browser user-agent strings
  const browserRegex = {
    'Chrome': /Chrome\/([\d.]+)/,
    'Safari': /Version\/([\d.]+) Safari/,
    'Firefox': /Firefox\/([\d.]+)/,
    'Edge': /Edg(e|\/)([\d.]+)/,
    'Opera': /OPR\/([\d.]+)/,
    'IE': /Trident\/.*rv:([\d.]+)/ // For Internet Explorer 11
  };

  // Check against each browser regex
  for (const [name, regex] of Object.entries(browserRegex)) {
    const match = userAgent.match(regex);
    if (match) {
      return `${name} ${match[1]}`; // Return the browser name and version number
    }
  }

  // If no known browser is matched, return the full user-agent string
  return userAgent;
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
