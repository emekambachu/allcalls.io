<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed } from "vue";
import { Head, Link, router, useForm, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
let emits = defineEmits();
let { videoDataModal, videoData } = defineProps({
  videoDataModal: Boolean,
  videoData: Object,
});
let close = () => {
  emits("close");
};
let downloadPDF = async (pdfPath) => {
  const fileName = pdfPath.substring(pdfPath.lastIndexOf("/") + 1);
  const link = document.createElement("a");
  link.href = pdfPath;
  link.download = fileName;
  link.click();
};
</script>
<style scoped>
.modal-width {
  width: 80%;
  height: 100%;
}
.video-player {
  width: 100%;
  height: 400px;
}
@media only screen and (min-width: 320px) and (max-width: 768px) {
  .modal-width {
    width: 90%;
  }
  .video-player {
    width: 100%;
    height: 100%;
  }
  .heading-modal {
    margin: 10px;
  }
}
</style>
<template>
  <AuthenticatedLayout>
    <Transition
      name="modal"
      enter-active-class="transition ease-out duration-300 transform"
      enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
      enter-to-class="opacity-100 translate-y-0 sm:scale-100"
      leave-active-class="transition ease-in duration-200 transform"
      leave-from-class="opacity-100 translate-y-0 sm:scale-100"
      leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    >
      <div
        id="defaultModal"
        v-if="videoDataModal"
        tabindex="-1"
        class="flex items-center justify-center fixed inset-0 z-50 w-full h-full overflow-x-hidden overflow-y-auto max-h-full lg:mx-4 sm:mx-2"
      >
        <div class="fixed inset-0 bg-black opacity-60 blurred-overlay"></div>

        <!-- This is the overlay -->
        <div class="relative w-full lg:py-10 modal-width max-h-full mx-auto">
          <div class="relative bg-white rounded-lg shadow-lg transition-all">
            <div class="flex justify-between">
              <div class="lg:px-12 sm:px-5 heading-modal py-2 mt-2">
                <h1 class="text-gray-800 text-xl font-bold">
                  <span> {{ videoData.id }}) {{ videoData.title }} </span>
                </h1>
              </div>
              <button
                @click="close"
                type="button"
                class="text-gray-400 bg-transparent mr-2 mt-2 hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center"
                data-modal-hide="defaultModal"
              >
                <svg
                  class="w-3 h-3"
                  aria-hidden="true"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 14 14"
                >
                  <path
                    stroke="currentColor"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"
                  />
                </svg>
                <span class="sr-only">Close modal</span>
              </button>
            </div>
            <div class="px-8 pt-3 pb-10">
              <video class="video-player" controls autoplay>
                <source :src="videoData.video_url" />
              </video>
              <br />
              <div>
                <a
                  target="_blank"
                  :href="videoData.pdf"
                  class="px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300"
                >
                Download {{videoData.title  }} PDF
                </a>

                <!-- <a target="_blank" :href="videoData.pdf" 
                                    class="font-medium  text-blue-600  cursor-pointer dark:text-blue-500 hover:underline">Download
                                    pdf
                                </a>
                                <a  target="_blank" :href="videoData.pdf"  title="Download pdf"
                                    class="inline-flex cursor-pointer  ml-2 items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-blue-600">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                    </svg>
                                </a> -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </AuthenticatedLayout>
</template>
