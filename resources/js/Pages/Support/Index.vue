<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({});

let openLiveChat = () => {
  let iframe = document.querySelector(".widget-visible iframe");
  let iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
  let iframeButton = iframeDoc.querySelector("button");

  iframeButton.click();
};
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
          <h2 class="text-xl text-white font-extrabold">
            Search our Help Center
          </h2>
          <p class="text-gray-300">
            Find helpful how-to's and commonly asked questions.
          </p>
        </div>
        <div class="mt-auto">
          <PrimaryButton>Visit Help Center</PrimaryButton>
        </div>
      </div>

      <div class="flex flex-col bg-custom-darksky rounded p-4 text-center">
        <div class="mb-6">
          <h2 class="text-xl text-white font-extrabold">
            Live Calls User Guide
          </h2>
          <p class="text-gray-300">A detailed guide of the Live Calls page.</p>
        </div>
        <div class="mt-auto">
          <PrimaryButton>Download</PrimaryButton>
        </div>
      </div>

      <div class="flex flex-col bg-custom-darksky rounded p-4 text-center">
        <div class="mb-6">
          <h2 class="text-xl text-white font-extrabold">Live-chat support</h2>
          <p class="text-gray-300">
            You can chat with our support staff if you have any issues or
            questions.
          </p>
        </div>
        <div class="mt-auto">
          <PrimaryButton @click="openLiveChat">Open Live Chat</PrimaryButton>
        </div>
      </div>
    </div>

    <div class="pt-14">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="px-4 sm:px-8 sm:rounded-lg">
          <div class="text-4xl text-custom-sky font-bold mb-6">
            Contact Information
          </div>
          <hr class="mb-4" />
        </div>
      </div>
    </div>

    <div class="px-16 text-gray-700 mb-8">
      <div class="font-bold text-gray-700 mb-2 text-lg">AllCalls LLC</div>
      <div>1309 COFFEEN AVE STE 11246<br>SHERIDAN, WY 82801, USA</div>
      <div>
        <strong class="text-gray-600">Toll Free Customer Service Phone:</strong>
        (855) 815-0382
      </div>
      <div>
        <strong class="text-gray-600">Support Hours:</strong> 24/7 Monday -
        Sunday
      </div>

      <div class="font-bold text-gray-700 mt-4 text-lg mb-2">Returns</div>
      <p class="mb-3">
        If you need to return your order please contact customer service to get
        an RMA number that you will need to include in the package you send back
        to us. All returns should be sent to this address:
      </p>

      <p class="mb-1">1309 COFFEEN AVE STE 11246<br>SHERIDAN, WY 82801, USA</p>
      <p><strong class="text-gray-500">Email:</strong> support@allcalls.io</p>
    </div>
  </AuthenticatedLayout>
</template>
