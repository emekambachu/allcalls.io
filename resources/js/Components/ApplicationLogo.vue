<script setup>
defineProps({
  black: {
    default: false,
    type: Boolean
  }
});
</script>
<template>
  <div
    class="bg-clip-text text-4xl text-transparent bg-gradient-to-r from-custom-sky to-custom-green font-bold uppercase tracking-wider"
  >
    <!-- <img v-if="black" src="/img/new-logo-mixed.png" alt="AllCalls.io" style="max-width: 200px" /> -->
    <img v-if="black" src="/img/new-logo.png" alt="AllCalls.io" style="max-width: 200px" />
    <img v-else src="/img/new-logo.png" alt="AllCalls.io" style="max-width: 200px" />
  </div>
</template>
