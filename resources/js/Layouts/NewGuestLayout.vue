<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
  <div class="min-h-screen flex flex-col items-center lg:block">
    <div class="lg:hidden block py-4 flex flex-col items-center gap-1 w-full bg-custom-blue mb-0">
      <Link href="/" class="mb-2">
        <ApplicationLogo :black="true" class="w-fit" />
      </Link>
    </div>
    <div
      class="bg-custom-blue sm:block lg:hidden  w-full px-6 py-6 md:px-20 md:pb-20  md:mt-0 relative"
    >
      <div class="hidden lg:block pt-4 pb-14 xl:py-20">
        <Link href="/">
          <ApplicationLogo class="w-fit" />
        </Link>
      </div>

      <div
        class="w-full  text-center lg:text-left lg:max-w-sm xl:max-w-xl mt-10 lg:mt-0"
      >
        <slot name="titles" />
      </div>
    </div>
    <div class="lg:hidden mt-5 flex flex-col items-center gap-1">
      <!-- <div
        class="px-10 text-center text-4xl font-black text-sky-900 tracking-tighter font-extrabold"
      >
        Start Receiving Live Calls Now!
      </div>
      <div
        class="text-md text-custom-blue font-semibold text-center px-10 mb-6"
      >
        No risk, no contracts, and no long-term commitment. Cancel anytime,
        hassle-free.
      </div> -->
      <slot name="smallStepRegister"  />
      <slot name="smallStepOneLoading" />
    </div>

    <div
      class="w-[80%] sm:max-w-xl lg:w-full lg:absolute lg:top-60 lg:right-10 z-10"
    >
      <div class="hidden lg:block flex flex-col items-center gap-1">
        <slot name="loadingText" />
        <slot name="largeStepOneLoading" />
      </div>

      <div
        class="bg-white m-3 px-6 py-4 bg-white drop-shadow-2xl overflow-hidden sm:rounded-lg"
      >
        <slot />
      </div>
    </div>

    <div
      class="bg-custom-blue hidden lg:block w-full px-6 py-6 md:px-20 md:pb-20 mt-6 md:mt-0 relative"
    >
      <div class="hidden lg:block pt-4 pb-14 xl:py-20">
        <Link href="/">
          <ApplicationLogo class="w-fit" />
        </Link>
      </div>

      <div
        class="w-full  text-center lg:text-left lg:max-w-sm xl:max-w-xl mt-10 lg:mt-0"
      >
        <slot name="titles" />
      </div>
    </div>

    <div
      class="bg-white w-full md:max-w-md xl:max-w-3xl text-center lg:text-left px-6 py-10 md:px-20 md:py-20"
    >
      <slot name="subtitles" />
    </div>
  </div>
</template>


<style scoped>
.newGuestLayout {
  background-image: url("/img/background.png");
}
</style>
