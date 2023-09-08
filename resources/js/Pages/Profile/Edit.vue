<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import AuthenticatedButton from "@/Components/AuthenticatedButton.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
let props = defineProps({
  mustVerifyEmail: {
    type: Boolean,
  },
  status: {
    type: String,
  },
  callTypes: {
    type: Array,
  },
  bids: {
    type: Array,
  },
  internalAgent: {
    type: Boolean,
  },
});

let bidsInput = ref(
  props.bids.map((bid) => {
    return { bid_id: bid.id, bid_amount: bid.amount, call_type_name: bid.call_type.type };
  })
);

let saveBids = () => {
  // Check if any bid amounts are below 20
  if (bidsInput.value.some((bid) => Number(bid.bid_amount) < 20)) {
    alert("The minimum amount for a bid is $20.");
    return; // Return early to prevent the router.visit from being called
  }

  // Continue to route if all bid amounts are 20 or greater
  router.visit("/bids", {
    method: "PATCH",
    data: {
      bids: bidsInput.value,
    },
  });
};
</script>

<template>
  <Head title="Profile" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Profile
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 sm:rounded-lg">
          <UpdateProfileInformationForm
            :must-verify-email="mustVerifyEmail"
            :status="status"
            :call-types="callTypes"
            class="max-w-xl"
          />
        </div>

        <div v-if="!internalAgent">
          <header class="pl-8">
            <h2 class="text-lg font-medium text-gray-700">Bids Setting</h2>

            <p class="mt-1 text-sm text-gray-400">
              You can set a default bid for each call type here.
            </p>
          </header>

          <div class="pl-8 mt-6">
            <div class="grid grid-cols-2 gap-10 mb-8">
              <div class="text-gray-700" v-for="bid in bidsInput" :key="bid.bid_id">
                <div>
                  <InputLabel
                    :for="`bid_${bid.bid_id}`"
                    :value="`${bid.call_type_name}`"
                  />

                  <TextInput
                    :id="`bid_${bid.bid_id}`"
                    type="number"
                    class="mt-1 block w-full"
                    v-model="bid.bid_amount"
                    required
                    autofocus
                  />
                </div>
              </div>
            </div>

            <div>
              <PrimaryButton @click="saveBids">Save</PrimaryButton>
            </div>
          </div>
        </div>

        <div class="p-4 sm:p-8 sm:rounded-lg">
          <UpdatePasswordForm class="max-w-xl" />
        </div>

        <div class="p-4 sm:p-8 sm:rounded-lg">
          <DeleteUserForm class="max-w-xl" />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style>
.multiselect {
  color: black !important;
  border: none;
  border-radius: 10px;
}

.multiselect-wrapper {
  background-color: #e8f0fe;
  border-radius: 5px;
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
</style>
