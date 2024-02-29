<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
</script>

<template>
  <div class="border-t border-gray-800 text-white w-full mt-20 py-6">
    <div>
      <div class="mb-1">
        <h2 class="text-lg text-custom-green text-center">Important Links</h2>
      </div>
      <div class="flex justify-center items-center mb-6">
        <a class="mr-2 text-xs hover:text-gray-300" href="/support">Support</a>
        <span class="mr-2">|</span>
        <a class="mr-2 text-xs hover:text-gray-300" href="/terms.php">Terms of Service</a>
        <span class="mr-2">|</span>
        <a class="text-xs hover:text-gray-300" href="/privacy.php"> Privacy Policy</a>
      </div>
      <div class="mb-1">
        <h2 class="text-lg text-custom-green text-center">Contact Information</h2>
      </div>
      <div class="flex flex-col items-center text-xs">
        <p class="mt-1 uppercase tracking-wider">AllCalls.io</p>
        <p>1309 COFFEEN AVE STE 11246<br>SHERIDAN, WY 82801, USA</p>
        <!-- <p>(855) 815-0382</p> -->
        <p>support@allcalls.io</p>
      </div>
    </div>
  </div>
</template>
