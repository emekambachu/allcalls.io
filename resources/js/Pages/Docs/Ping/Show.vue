<script setup>
import Layout from "@/Pages/Docs/Layout.vue";
import { Head } from "@inertiajs/vue3";
</script>
<template>
  <Head title="/api/ping" />
  <Layout>
    <!-- Main Content -->
    <div class="ml-80 mt-5">
      <h1 class="mb-3 text-2xl font-semibold">/api/ping Endpoint</h1>
      <p class="mb-5 text-gray-600">
        This API endpoint determines whether a call should be initiated based on
        the given callerId.
      </p>

      <!-- Endpoint Details -->
      <div class="mb-5 rounded bg-white p-5 shadow-lg">
        <h2 class="text-lg font-semibold">Endpoint:</h2>
        <p><strong>URL:</strong> <code>https://allcalls.io/api/ping</code></p>
        <p>
          <strong>Supported Methods:</strong> <code>GET</code>,
          <code>POST</code>
        </p>
      </div>

      <!-- Request Details -->
      <div class="mb-5 rounded bg-white p-5 shadow-lg">
        <h2 class="text-lg font-semibold">Request:</h2>
        <p><strong>Parameters:</strong></p>
        <ul class="list-disc pl-5 mb-5">
          <li>
            <strong>callerId</strong> (required): A 10 or 11-digit phone number.
            If it's 11 digits, it should start with '1'.
          </li>
        </ul>
      </div>

      <!-- Successful Response Details -->
      <div class="mb-5 rounded bg-white p-5 shadow-lg">
        <h2 class="text-lg font-semibold">Successful Response:</h2>
        <p><strong>200 OK</strong></p>
        <div class="rounded bg-gray-100 p-3 mb-3">
          <pre class="text-sm">
  {
      "downline": 1
  }</pre
          >
        </div>
        <p>OR</p>
        <div class="rounded bg-gray-100 p-3 mb-3">
          <pre class="text-sm">
  {
      "downline": 0
  }</pre
          >
        </div>
        <p>
          <strong>downline</strong>: This can be either <code>1</code> or
          <code>0</code>. A value of <code>1</code> means "send a call", while a
          value of <code>0</code> means "don't send a call".
        </p>
      </div>
    </div>
  </Layout>
</template>