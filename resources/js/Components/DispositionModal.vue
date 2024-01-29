<script setup>
import { ref } from "vue";
import axios from "axios";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import GlobalSpinner from "@/Components/GlobalSpinner.vue";
import { toaster } from "@/helper.js";
import { router } from "@inertiajs/vue3";

let emit = defineEmits(['close']);
let { client, callTypeId } = defineProps(["client", "callTypeId"]);

console.log('Disposition Modal CallTypeId', callTypeId);


let latestClientDisposition = ref("");
let dispositionUpdating = ref(false);
let updateLatestClientDisposition = () => {
  dispositionUpdating.value = true;
  axios
    .post(`/web-api/clients/${client.id}/disposition`, {
      status: latestClientDisposition.value,
    })
    .then((response) => {
      dispositionUpdating.value = false;
      emit('close');
      //   showUpdateDispositionForLastClient.value = false;
      
      console.log("Turn them back on for whatever vertical they turned off for.");
      turnOnForCalls();

      toaster(
        "success",
        `Disposition updated for ${client.first_name} ${client.last_name}.`
      );

      if (response.data.status.startsWith("Sale")) {
        router.visit(`/internal-agent/my-business?clientId=${response.data.clientId}`);
      }
    })
    .catch((error) => {
      //   dispositionUpdating.value = false;
      emit('close');
      turnOnForCalls();
      console.log("Error updating client disposition:");
      console.log(error);
    });
};
</script>
<template>
  <div class="bg-white p-6 rounded-lg shadow">
    <div
      class="mb-4 flex flex-col md:flex-row rounded-lg bg-blue-100 p-4 text-blue-900"
      role="alert"
    >
      <svg
        class="mr-4 h-6 w-6 flex-shrink-0 text-blue-500"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
        aria-hidden="true"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
        />
      </svg>
      <div class="flex-1">
        <h3 class="text-lg font-semibold">Attention Required</h3>
        <p class="mt-1 text-sm">
          You've just completed a call. Please update the client's disposition to ensure
          accurate tracking and follow-up actions.
        </p>
        <h4 class="mt-4 text-lg font-bold">Client Information</h4>
        <p class="mt-1 text-sm">
          <span class="font-bold">Name: </span>
          <span>{{ client.first_name + " " + client.last_name }}</span>
        </p>
      </div>
    </div>

    <div class="mb-6">
      <label for="clientDisposition" class="block mb-2 text-sm font-medium text-gray-900"
        >Client Disposition:</label
      >
      <select
        id="clientDisposition"
        class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
        v-model="latestClientDisposition"
      >
        <option value="">Select a disposition</option>
        <option value="Sale - Simplified Issue">Sale - Simplified Issue</option>
        <option value="Sale - Guaranteed Issue">Sale - Guaranteed Issue</option>
        <option value="Follow Up Needed">Follow Up Needed</option>
        <option value="Quoted - Not Interested">Quoted - Not Interested</option>
        <option value="Not Interested">Not Interested</option>
        <option value="Transfer Handoff Too Long">Transfer Handoff Too Long</option>
        <option value="Client Hung Up">Client Hung Up</option>
        <option value="No Income">No Income</option>
        <option value="Wrong State">Wrong State</option>
        <option value="Not Qualified Age">Not Qualified Age</option>
        <option value="Not Qualified Nursing Home">Not Qualified Nursing Home</option>
        <option value="Not Qualified Memory Issues">Not Qualified Memory Issues</option>
        <option value="Language Barrier">Language Barrier</option>
        <option value="Do Not Call">Do Not Call</option>
      </select>
    </div>

    <div class="flex justify-end">
      <PrimaryButton
        :disabled="dispositionUpdating"
        @click.prevent="updateLatestClientDisposition"
      >
        <GlobalSpinner :spinner="dispositionUpdating" />
        Save Disposition
      </PrimaryButton>
    </div>
  </div>
</template>
