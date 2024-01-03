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
      <h2 class="font-small text-xl text-gray-800 leading-tight">
        Promotion Guidelines
      </h2>
    </template>

    <div>
        <img style="max-width: 100%; transform: scale(1.01);" src="/img/promotion-guidelines/External 1.png">
    </div>
  </AuthenticatedLayout>
</template>
