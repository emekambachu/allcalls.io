<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed } from "vue";
import { Head, Link, router, useForm, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import BasicTraining from "@/Pages/InternalAgent/Traning/BasicTraining.vue";
let props = defineProps({

});
let page = usePage();

let StepsModal = ref(true)

let basicTrainingModal = ref(true)
// if (page.props.auth.user.basic_training === 1) {
//     basicTrainingModal.value = false
//     router.visit("/training")
// }
let basisTraning = () => {
    StepsModal.value = false
    basicTrainingModal.value = true
}
let closeBasicTraining = () => {
    // StepsModal.value = true
    basicTrainingModal.value = false
}
</script>
<style scoped>
.active\:bg-gray-900:active {
    color: white;
}

.hover\:drop-shadow-2xl:hover {
    background-color: white;
    color: black;
}

.blurred-overlay {
    backdrop-filter: blur(10px);
    /* Adjust the blur intensity as needed */
    background-color: rgba(0, 0, 0, 0.6);
    /* Adjust the background color and opacity as needed */
}
</style>
<template>
    <AuthenticatedLayout>
        <Transition name="modal" enter-active-class="transition ease-out duration-300 transform"
            enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to-class="opacity-100 translate-y-0 sm:scale-100"
            leave-active-class="transition ease-in duration-200 transform"
            leave-from-class="opacity-100 translate-y-0 sm:scale-100"
            leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <div id="defaultModal" v-if="StepsModal" tabindex="-1"
                class="flex items-center justify-center fixed inset-0 z-50 w-full h-full overflow-x-hidden overflow-y-auto max-h-full mx-4 sm:mx-0">
                <div class="fixed inset-0 bg-black opacity-90 blurred-overlay"></div>

                <!-- This is the overlay -->
                <div class="relative w-full max-w-xl max-h-full mx-auto">
                    <div class="relative bg-white rounded-lg shadow-lg transition-all">
                        <div class="flex justify-end">
                            <Link :href="route('logout')" method="post" as="button"
                                class="underline text-sm text-gray-600 mr-5 mt-5  dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            Logout </Link>

                        </div>
                        <div class="px-12 py-2">
                            <h1 class=" text-gray-800 text-3xl font-bold">Welcome to AllCalls.io!</h1>
                        </div>
                        <div class="px-12 pt-3 pb-10">
                            <p class="mb-5">
                                Thank you for completing onboarding. Our contracting team will reach out to you within the
                                next
                                24 - 48 hours.
                            </p>

                          

                            <br>


                            <!-- <button @click="basisTraning" class="font-medium">- <span
                                    class="text-blue-600 dark:text-blue-500 hover:underline">Schedule
                                    Training</span></button>
                            <br> -->
                            <!-- <a href="#" class="font-medium"> - <span class="text-blue-600 dark:text-blue-500 hover:underline">Intermediate Training</span></a>
                            <br>
                            <a href="#" class="font-medium"> - <span class="text-blue-600 dark:text-blue-500 hover:underline">Advanced Training</span></a> -->


                        </div>
                    </div>
                </div>
            </div>

        </Transition>
    </AuthenticatedLayout>
    <BasicTraining v-if="basicTrainingModal" @close="closeBasicTraining" :basicTrainingModal="basicTrainingModal" />
</template>
