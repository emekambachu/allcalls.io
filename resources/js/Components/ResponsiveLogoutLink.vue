<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

let logout = () => {
  axios.post("/web-api/logout").then((response) => {
    window.location.href = "/";
  });
};
</script>

<template>
  <button
    @click.prevent="logout"
    class="block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-300 hover:text-gray-400 hover:bg-cyan-950 hover:border-cyan-800 focus:outline-none focus:text-custom-white focus:bg-sky-950 focus:border-custom-blue transition duration-150 ease-in-out"
  >
    Logout
  </button>
</template>
