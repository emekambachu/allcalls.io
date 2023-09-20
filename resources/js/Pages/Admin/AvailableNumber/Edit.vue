<script setup>
import {
  ref,
  reactive,
  defineEmits,
  onMounted,
  watch,
  computed,
  onBeforeMount,
} from "vue";
import TextInput from "@/Components/TextInput.vue";
import Multiselect from "@vueform/multiselect";
import InputError from "@/Components/InputError.vue";
import SelectInput from "@/Components/SelectInput";
import { router, usePage } from "@inertiajs/vue3";
import GuestTextInput from "@/Components/GuestTextInput.vue";
import GuestInputLabel from "@/Components/GuestInputLabel.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { toaster } from "@/helper.js";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import axios from "axios";
let page = usePage();
if (page.props.flash.message) {
  toaster("success", page.props.flash.message);
}
let props = defineProps({
  showModal: {
    type: Boolean,
  },
  user: Array,
  availableNumber: {
    type: Object,
  },
  currentPage: Number,
  callTypes: Array,

  route: String,
});
let emit = defineEmits(["close"]);
let availableNumber = props.availableNumber;

let form = useForm({
  phone: "",
  user_id: "",
  from: "",
  call_type_id: "",
});

const isLoading = ref(false);
let saveChanges = () => {
  isLoading.value = true;

  return axios
    .post(`${props.route}/${props.availableNumber.id}`, form)
    .then((response) => {
      toaster("success", response.data.message);
      if (props.currentPage != 1) {
        router.visit(`${props.route}s?page=${props.currentPage}`);
      } else {
        router.visit(`${props.route}s`);
      }
      isLoading.value = false;
      balanceChange.value = false;
    })
    .catch((error) => {
      //   console.log(error);
      toaster("error", "Something went wrong");
    });
};
</script>
<style>
.chemical-formula {
  display: inline-block;
  padding: 5px 10px;
  background-color: #0b4c73;
  color: white;
  font-size: 16px;
  border-radius: 50px;
  border: none;
  cursor: pointer;
  text-align: center;
  text-decoration: none;
  outline: none;
}

.active-tab {
  border-bottom: 2px solid #0b4c73;
}

.slide-enter-active,
.slide-leave-active {
  /* transition: all 0.2s ease; */
}

.slide-enter,
.slide-leave-to {
  opacity: 0;
  transform: translateX(-20px);
}
</style>
<template>
  <!-- This is the overlay -->
  <div class="relative w-full max-w-4xl max-h-full mx-auto">
    <div class="relative bg-white border border-gray-300 rounded-lg shadow-lg">
      <div
        class="flex items-start justify-between p-4 border-b border-gray-300 rounded-t"
      >
        <h3 class="text-xl font-small text-gray-700">Add Available Number</h3>
        <button
          @click="close"
          type="button"
          class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center"
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

      <div class="p-6">
        <div>
          <div>
            <GuestInputLabel for="phone" value="Enter Phone Number" />

            <GuestTextInput
              id="phone"
              type="number"
              class="mt-1 block w-full"
              v-model="form.phone"
              minlength="2"
              required
            />
            <!-- <InputError class="mt-2" :message="form.errors.phone" /> -->
            <div
              v-if="firstStepErrors.phone"
              class="text-red-500"
              v-text="firstStepErrors.phone[0]"
            ></div>
          </div>
          <div class="pt-4">
            <GuestInputLabel for="user_id" value="User" />

            <SelectInput
              id="user_id"
              class="mt-1 block w-full"
              v-model="form.user_id"
              minlength="2"
              required
            >
              <option v-for="use in user" :value="use.id">
                {{ use.first_name }}
              </option>
            </SelectInput>
            <!-- <InputError class="mt-2" :message="form.errors.user_id" /> -->
            <div
              v-if="firstStepErrors.user_id"
              class="text-red-500"
              v-text="firstStepErrors.user_id[0]"
            ></div>
          </div>
          <div class="pt-4">
            <GuestInputLabel for="from" value="Enter from Number" />

            <GuestTextInput
              id="from"
              type="number"
              class="mt-1 block w-full"
              v-model="form.from"
              minlength="2"
              required
            />
            <!-- <InputError class="mt-2" :message="form.errors.from" /> -->
            <div
              v-if="firstStepErrors.from"
              class="text-red-500"
              v-text="firstStepErrors.from[0]"
            ></div>
          </div>

          <div class="pt-4">
            <GuestInputLabel for="call_type_id" value="Call Type" />

            <SelectInput
              id="call_type_id"
              class="mt-1 block w-full"
              v-model="form.call_type_id"
              minlength="2"
              required
            >
              <option v-for="call_type in callTypes" :value="call_type.id">
                {{ call_type.type }}
              </option>
            </SelectInput>
            <!-- <InputError class="mt-2" :message="form.errors.call_type_id" /> -->
            <div
              v-if="firstStepErrors.call_type_id"
              class="text-red-500"
              v-text="firstStepErrors.call_type_id[0]"
            ></div>
          </div>
          <div class="flex justify-end mt-6">
            <PrimaryButton
              type="button"
              @click="saveChanges"
              :class="{ 'opacity-25': areAllArraysEmpty || isLoading }"
              :disabled="isLoading"
            >
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
</template>
