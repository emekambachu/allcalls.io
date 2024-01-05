<script setup>
import { Link, Head } from "@inertiajs/vue3";
import Layout from "@/Pages/Docs/Layout.vue";

defineProps({
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
</script>

<template>
  <Head title="/api/agent-status" />
  <Layout :domain="domain">
    <!-- Main Content -->
    <div class="ml-80 mt-5">
      <h1 class="mb-3 text-2xl font-semibold">/api/agent-status Endpoint</h1>
      <p class="mb-5 text-gray-600">
        Detailed documentation for the `/api/agent-status` endpoint.
      </p>

      <!-- Endpoint Details -->
      <div class="mb-5 rounded bg-white p-5 shadow-lg">
        <h2 class="text-lg font-semibold">Endpoint:</h2>
        <p>
          <strong>URL:</strong>
          <code>https://allcalls.io/api/agent-status</code>
        </p>
        <p><strong>Methods:</strong> <code>GET</code>, <code>POST</code></p>
      </div>

      <!-- Description -->
      <div class="mb-5 rounded bg-white p-5 shadow-lg">
        <h2 class="text-lg font-semibold">Description:</h2>
        <p>
          This endpoint checks if an agent is available for a given phone
          number's area code and vertical.
        </p>
      </div>

      <!-- Request Details -->
      <div class="mb-5 rounded bg-white p-5 shadow-lg">
        <h2 class="text-lg font-semibold">Parameters:</h2>
        <ul class="list-disc pl-5 mb-5">
          <li>
            <strong>phone</strong>: (Optional) Phone number. Cannot be provided
            if <code>state</code> or <code>zip</code> is already provided.
          </li>
          <li>
            <strong>zip</strong>: (Optional) ZIP code. Cannot be provided if
            <code>phone</code> or <code>state</code> is already provided.
          </li>
          <li>
            <strong>state</strong>: (Optional) State code (2 characters) or full
            state name. Cannot be provided if <code>phone</code> or
            <code>zip</code> is already provided.
          </li>
          <li>
            <strong>vertical</strong>: (Required) Vertical. Valid values:
            <code>auto_insurance</code>, <code>final_expense</code>,
            <code>u65_health</code>, <code>aca</code>, <code>medicare</code>.
          </li>
          <li>
            <strong>affiliate_id</strong>: (Required) Identifier for the
            affiliate.
          </li>
          <li>
            <strong>api_key</strong>: (Required) API key associated with the
            affiliate.
          </li>
        </ul>
        <p>
          Note: One of <code>phone</code>, <code>zip</code>, or
          <code>state</code> is required for the request.
        </p>
      </div>

      <!-- Successful Response Details -->
      <div class="mb-5 rounded bg-white p-5 shadow-lg">
        <h2 class="text-lg font-semibold">Response:</h2>
        <p><strong>200 OK</strong></p>
        <div class="rounded bg-gray-100 p-3 mb-3">
          <pre class="text-sm">
{
    "online": true
}
            </pre
          >
        </div>
        <p>
          <strong>online</strong>: <code>true</code> if the agent is available,
          <code>false</code> otherwise.
        </p>
      </div>

      <!-- Example Details -->
      <div class="mb-5 rounded bg-white p-5 shadow-lg">
        <h2 class="text-lg font-semibold">Examples:</h2>
        <div class="mb-3">
          <p><strong>Request:</strong></p>
          <div class="rounded bg-gray-100 p-3">
            <pre class="text-sm">
GET https://allcalls.io/api/agent-status?phone=1234567890&vertical=auto_insurance&affiliate_id=YOUR_AFFILIATE_ID&api_key=YOUR_API_KEY
</pre
            >
          </div>
        </div>
        <div class="mb-3">
          <p><strong>Response:</strong></p>
          <div class="rounded bg-gray-100 p-3">
            <pre class="text-sm">
{
    "online": true
}
                </pre
            >
          </div>
        </div>
      </div>

      <!-- Notes -->
      <div class="rounded bg-white p-5 shadow-lg">
        <h2 class="text-lg font-semibold">Notes:</h2>
        <ul class="list-disc pl-5">
          <li>
            This API checks the availability of an agent based on the provided
            parameters.
          </li>
          <li>
            Affiliates using the API must provide valid credentials for
            authentication.
          </li>
        </ul>
      </div>
    </div>
  </Layout>
</template>