<script setup>
import { Link, Head } from "@inertiajs/vue3";
import { ref, computed } from "vue";

let { domain, brandName } = defineProps({
  domain: {
    type: String,
    required: false,
    default: "https://allcalls.io",
  },
  brandName: {
    type: String,
    required: false,
    default: "AllCalls.io",
  },
});



let baseUrlForDocs = computed(() => {

  if (domain === 'https://allcalls.io') {
    return '/'
  }

  // remove the protocol and other stuff and only take the domain:
  let domainPassed = domain.replace(/(^\w+:|^)\/\//, '');
  return `/${domainPassed}/`;
});


</script>

<template>
  <div class="min-h-screen bg-gray-100 p-8">
    <!-- Header Section -->
    <div class="mb-8 flex items-center justify-between">
      <div class="flex items-center space-x-4">
        <div>
          <p class="text-lg font-semibold" v-text="brandName"></p>
          <div class="flex items-center space-x-2">
            <p class="text-sm text-gray-500">API Documentation</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Sidebar -->
    <div class="float-left mt-5 w-64">
      <div class="mb-4">
        <p class="mb-1 text-sm font-semibold">ENDPOINTS</p>
        <ul class="space-y-2">
          <li class="cursor-pointer">
            <Link
              :class="{
                'text-gray-500': !route().current('docs.ping.show'),
                'text-blue-500': route().current('docs.ping.show'),
              }"
              :href="`${baseUrlForDocs}docs/api/ping`"
              >/api/ping</Link
            >
          </li>
          <li class="cursor-pointer">
            <Link
              :href="`${baseUrlForDocs}docs/api/agent-status`"
              :class="{
                'text-gray-500': !route().current('docs.agent-status.show'),
                'text-blue-500': route().current('docs.agent-status.show'),
              }"
              >/api/agent-status</Link
            >
          </li>
          <li class="cursor-pointer">
            <Link
              :href="`${baseUrlForDocs}docs/api/agent-status-price`"
              :class="{
                'text-gray-500': !route().current('docs.agent-status-price.show'),
                'text-blue-500': route().current('docs.agent-status-price.show'),
              }"
              >/api/agent-status-price</Link
            >
          </li>
        </ul>
      </div>

      <!-- ... add more sections in sidebar -->
    </div>

    <!-- Main Content Placeholder (specific for each endpoint documentation) -->
    <slot></slot>
  </div>
</template>
