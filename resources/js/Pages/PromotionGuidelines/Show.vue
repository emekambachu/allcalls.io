<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { ref } from "vue";

let props = defineProps(["showChart"]);

let guidelinesImg = ref(null);

onMounted(() => {
  if (props.showChart === "small") {
    guidelinesImg.value = "/img/promotion-guidelines/Internal 1.png";
  } else if (props.showChart === "large") {
    guidelinesImg.value = "/img/promotion-guidelines/External 1.png";
  }
});
</script>

<template>
  <Head title="Promotion Guidelines" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-small text-xl text-gray-800 leading-tight">Promotion Guidelines</h2>
    </template>

    <div v-if="guidelinesImg">
      <img style="max-width: 100%; transform: scale(1.01)" :src="guidelinesImg" />
    </div>
  </AuthenticatedLayout>
</template>
