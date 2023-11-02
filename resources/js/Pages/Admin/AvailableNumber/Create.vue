<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed } from "vue";
import Multiselect from "@vueform/multiselect";
import InputError from "@/Components/InputError.vue";
import { router, usePage } from "@inertiajs/vue3";
import GuestTextInput from "@/Components/GuestTextInput.vue";
import GuestInputLabel from "@/Components/GuestInputLabel.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { toaster } from "@/helper.js";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import axios from "axios";

let page = usePage();
if (page.props.flash.message) {
  toaster("success", page.props.flash.message);
}
let props = defineProps({
  availableNumberModal: {
    type: Boolean,
  },
  currentPage: Number,
  callTypes: Array,
  states: Array,
  user: Array,
});

let firstStepErrors = ref({});
let uiEmailValidation = ref({
  isValid: false,
});
let form = useForm({
  phone: "",
  user_id: "",
  from: "",
  call_type_id: "",
});

// Initialize the validation variable
let isFormValid = ref(true);
let step = ref(0);
const isLoading = ref(false);
let selectedTypes = ref([]);

let stateOptions = computed(() => {
  return props.states.map((state) => {
    return {
      value: state.id,
      label: state.full_name,
    };
  });
});
watch(
  () => [form.phone, form.user_id, form.from, form.call_type_id],
  async ([phone, user_id, from, call_type_id]) => {
    if (phone === "" || user_id === "" || from === "" || call_type_id === "") {
      isFormValid.value = true;
    } else {
      isFormValid.value = false;
    }
  }
);

let saveChanges = () => {
  isLoading.value = true;

  return axios
    .post(`/admin/available-number/store`, form)
    .then((response) => {
      toaster("success", response.data.message);
      router.visit(`/admin/available-number?page=${props.currentPage}`);
      isLoading.value = false;
    })
    .catch((error) => {
      if (error.response.status === 400) {
        uiEmailValidation.value.isValid = false;
        firstStepErrors.value = error.response.data.errors;
        if (
          firstStepErrors.value.user ||
          firstStepErrors.value.phone ||
          firstStepErrors.value.from ||
          firstStepErrors.value.call_type_id
        ) {
          step.value = 0;
        } else if (firstStepErrors.value.typesWithStates) {
          step.value = 1;
        }

        isLoading.value = false;
      }
    });
};
let emit = defineEmits(["close"]);
let close = () => {
  form.reset();
  step.value = 0;
  emit("close");
  firstStepErrors.value = {};
  isLoading.value = false;
};
let goBack = () => {
  step.value -= 1;
};
</script>
<style scoped>
.active\:bg-gray-900:active {
  color: white;
}

.hover\:drop-shadow-2xl:hover {
  background-color: white;
  color: black;
}

.button-custom {
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  font-weight: 600;
  border-width: 1px;
  align-items: center;
  display: inline-flex;
  border-color: rgb(107 114 128 / var(--tw-border-opacity));
  background-color: #03243d;
  color: #3cfa7a;
  cursor: pointer;
}

.button-custom:hover {
  transition-duration: 150ms;
  background-color: white;
  color: black;
}

.button-custom-back {
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  font-weight: 600;
  border-width: 1px;
  align-items: center;
  display: inline-flex;
  border-color: rgb(107 114 128 / var(--tw-border-opacity));
}

.button-custom-back:hover {
  background-color: #03243d;
  color: #3cfa7a;
  transition-duration: 150ms;
}

.blurred-overlay {
  backdrop-filter: blur(10px);
  /* Adjust the blur intensity as needed */
  background-color: rgba(0, 0, 0, 0.6);
  /* Adjust the background color and opacity as needed */
}
</style>
<template>
  <div>
    <!-- <Transition
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
          tabindex="-1"
          class="flex items-center justify-center fixed inset-0 z-50 w-full h-full overflow-x-hidden overflow-y-auto max-h-full mx-4 sm:mx-0"
        >
          <div class="fixed inset-0 bg-black opacity-60"></div> -->
    <!-- This is the overlay -->

    <div class="relative w-full max-w-4xl max-h-full mx-auto">
      <div class="relative bg-white border border-gray-300 rounded-lg shadow-lg">
        <div class="flex items-start justify-between p-4 border-b border-gray-300 rounded-t">
          <h3 class="text-xl font-small text-gray-700">Add Available Number</h3>
          <button @click="close" type="button"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center"
            data-modal-hide="defaultModal">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
        </div>

        <div class="p-6">
          <div>
            <div>
              <GuestInputLabel for="phone" value="Enter Phone Number" />

              <input type="number" id="phone"
                class="bg-custom-blue text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 text-black outline-none border-none mt-1 block"
                v-model="form.phone" minlength="2" required>

              <div v-if="firstStepErrors.phone" class="text-red-500" v-text="firstStepErrors.phone[0]"></div>
            </div>

            <div class="flex justify-end mt-6">
              <PrimaryButton type="button" @click="saveChanges" :class="{ 'opacity-25': areAllArraysEmpty || isLoading }"
                :disabled="isLoading">
                <global-spinner :spinner="isLoading" /> Submit
              </PrimaryButton>

              <PrimaryButton @click.prevent="close" type="button" class="ml-3">
                Close
              </PrimaryButton>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- </div>
      </Transition> -->
  </div>
</template>
