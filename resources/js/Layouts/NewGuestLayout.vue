<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';

</script>

<template>
    <div class="min-h-screen flex flex-col items-center lg:block">
        <div class="lg:hidden block py-8">
            <Link href="/">
                <ApplicationLogo class="w-fit" />
            </Link>
        </div>

        <div
            class="w-[80%] sm:max-w-xl lg:w-full  m-6 px-6 py-4 bg-white drop-shadow-2xl overflow-hidden sm:rounded-lg lg:absolute lg:top-60 lg:right-10 z-10"
        >
            <slot />
        </div>
        
        <div class="bg-custom-blue w-full px-6 py-6 md:px-20 md:pb-20 mt-6 md:mt-0 relative">
            <div class="hidden lg:block py-20">
                <Link href="/">
                    <ApplicationLogo class="w-fit" />
                </Link>
            </div>

            <div class="w-full text-center lg:text-left lg:max-w-sm xl:max-w-xl mt-10 lg:mt-0">
                <slot name="titles" />
            </div>
        </div>

        <div class="bg-white w-full md:max-w-md xl:max-w-3xl text-center lg:text-left px-6 py-10 md:px-20 md:py-20">
            <slot name="subtitles" />
        </div>

    </div>
</template>


<style scoped>

.newGuestLayout {
    background-image: url('/img/background.png');
}

</style>
