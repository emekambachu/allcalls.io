<script setup>
import { reactive } from 'vue';

let policies = reactive([
    { id: 1, insuranceCompany: 'State Farm', apv: 100000 },
    { id: 2, insuranceCompany: 'Allstate', apv: 200000 },
    { id: 3, insuranceCompany: 'Progressive', apv: 300000 },
]);
</script>
<template>
  <div>
    <!-- Introduction -->
    <div class="mb-8">
      <h2 class="text-xl font-semibold text-gray-900">Manage Attached Policies</h2>
      <p class="mt-2 text-gray-600">
        Below is a list of policies currently attached to this client. You can review
        these existing policies to ensure accuracy and completeness. To attach additional
        policies, please use the policy attachment feature provided in the CRM.
      </p>
    </div>

    <!-- Policies table -->
    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
      <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
          <tr>
            <th scope="col" class="py-3 px-6">ID</th>
            <th scope="col" class="py-3 px-6">Insurance Company</th>
            <th scope="col" class="py-3 px-6">APV</th>
            <th scope="col" class="py-3 px-6">Actions</th>
          </tr>
        </thead>
        <tbody>
          <!-- Iterate over each policy -->
          <tr v-for="policy in policies" class="bg-white border-b hover:bg-gray-50" :key="policy.id">
            <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
              {{ policy.id }}
            </th>
            <td class="py-4 px-6">{{ policy.insuranceCompany }}</td>
            <td class="py-4 px-6">{{ policy.apv }}</td>
            <td class="py-4 px-6">
              <button
                class="text-red-500 hover:text-red-700"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-5 w-5"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                  />
                </svg>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
