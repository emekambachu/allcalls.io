<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { ref, onMounted } from "vue";

let props = defineProps(["showChart"]);

let guidelinesDesktopImg = ref(null);
let guidelinesMobileImg = ref(null);

onMounted(() => {
  if (props.showChart === "small") {
    guidelinesDesktopImg.value = "/img/promotion-guidelines/Internal 1.png";
    guidelinesMobileImg.value = "/img/promotion-guidelines/Internal 2.png";
  } else if (props.showChart === "large") {
    guidelinesDesktopImg.value = "/img/promotion-guidelines/External 1.png";
    guidelinesMobileImg.value = "/img/promotion-guidelines/External 2.png";
  }
});
</script>

<template>
  <Head title="Promotion Guidelines" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-small text-xl text-gray-800 leading-tight">Promotion Guidelines</h2>
    </template>

    <div v-if="guidelinesDesktopImg" class="hidden md:block">
      <img class="mx-auto" style="max-width: 100%; transform: scale(1.01)" :src="guidelinesDesktopImg" />
    </div>

    <div v-if="guidelinesMobileImg" class="block md:hidden">
      <img class="mx-auto" style="width: 100%; transform: scale(1.01)" :src="guidelinesMobileImg" />
    </div>
  </AuthenticatedLayout>
</template>
