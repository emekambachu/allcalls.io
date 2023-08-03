<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({});
</script>

<template>
  <Head title="Support" />
  <AuthenticatedLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
      >
        Support
      </h2>
    </template>

    <div class="pt-14">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="px-4 sm:px-8 sm:rounded-lg">
          <div class="text-4xl text-custom-sky font-bold mb-6">Support</div>
          <hr class="mb-4" />
        </div>
      </div>
    </div>

    <div
      class="px-16 mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8"
    >
      <div class="flex flex-col bg-custom-darksky rounded p-4 text-center">
        <div class="mb-6">
          <h2 class="text-xl text-white font-extrabold">Search our Help Center</h2>
          <p class="text-gray-300">Find helpful how-to's and commonly asked questions.</p>
        </div>
        <div class="mt-auto">
          <PrimaryButton>Visit Help Center</PrimaryButton>
        </div>
      </div>

      <div class="flex flex-col bg-custom-darksky rounded p-4 text-center">
        <div class="mb-6">
          <h2 class="text-xl text-white font-extrabold">Live Calls User Guide</h2>
          <p class="text-gray-300">A detailed guide of the Live Calls page.</p>
        </div>
        <div class="mt-auto">
          <PrimaryButton>Download</PrimaryButton>
        </div>
      </div>

      <div class="flex flex-col bg-custom-darksky rounded p-4 text-center">
        <div class="mb-6">
          <h2 class="text-xl text-white font-extrabold">Open a ticket</h2>
          <p class="text-gray-300">Submit a ticket for a question or issue you are having.</p>
        </div>
        <div class="mt-auto">
          <PrimaryButton>Submit Ticket</PrimaryButton>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
