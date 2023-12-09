<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed } from "vue";
import PreviewInfo from "@/Pages/InternalAgent/MyBusiness/PreviewInfo.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";


let emits = defineEmits();
let props = defineProps({
  viewDetailModal: Boolean,
  businessData: Object,
  
});
let close = () => {
  emits("close");
};
</script>
<template>
  <!-- This is the overlay -->
  <AuthenticatedLayout>
    <Transition name="modal" enter-active-class="transition ease-out  duration-300 transform"
      enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
      enter-to-class="opacity-100 translate-y-0 sm:scale-100"
      leave-active-class="transition ease-in duration-200 transform"
      leave-from-class="opacity-100 translate-y-0 sm:scale-100"
      leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

      <div id="defaultModal" v-show="viewDetailModal" tabindex="-1"
        class="flex  items-center justify-center fixed inset-0 z-50 w-full h-full overflow-x-hidden overflow-y-auto max-h-full mx-4 sm:mx-0">

        <div class="fixed inset-0 bg-black opacity-90 blurred-overlay"></div>
        <div style="width:80%;height:90%;" class="relative  ">
          <div class="relative bg-white rounded-lg shadow-lg transition-all">
            <div class="flex justify-end">
              <button @click="close" type="button"
                class="text-gray-400 bg-transparent mr-2 mt-2 hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center"
                data-modal-hide="defaultModal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                  viewBox="0 0 14 14">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Close modal</span>
              </button>
            </div>
            <div class="px-12 py-2">
              <PreviewInfo :form="businessData" :heading="'Business Information'" />
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </AuthenticatedLayout>
</template>