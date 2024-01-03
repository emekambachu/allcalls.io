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
  <Head title="Promotion Guidelines" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-small text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Promotion Guidelines
      </h2>
    </template>

    <div class="px-2 py-4">
        <img style="max-width: 100%;" src="/img/promotion-guidelines/External 1.png">
    </div>
  </AuthenticatedLayout>
</template>
