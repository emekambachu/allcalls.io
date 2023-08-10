<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
</script>

<template>
  <div class="border-t border-gray-800 text-white w-full mt-10 py-6">
    <div>
      <div class="mb-1">
        <h2 class="text-lg text-custom-green text-center">Important Links</h2>
      </div>
      <div class="flex justify-center items-center mb-6">
        <a class="mr-2 text-xs hover:text-gray-300" href="/terms.php">Terms of Service</a>
        <span class="mr-2">|</span>
        <a class="text-xs hover:text-gray-300" href="/privacy.php"> Privacy Policy</a>
      </div>
      <div class="mb-1">
        <h2 class="text-lg text-custom-green text-center">Contact Information</h2>
      </div>
      <div class="flex flex-col items-center text-xs">
        <p class="mb-1">12924 Pierce Street, Pacoima CA 91331, USA</p>
        <p>support@allcalls.io</p>
      </div>
    </div>
  </div>
</template>
  