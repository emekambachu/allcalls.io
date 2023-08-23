<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TransactionDetailModal from '@/Pages/Admin/User/TransactionDetailModal.vue'
import CallsDetailModal from '@/Pages/Admin/User/CallsDetailModal.vue'
import ActivitiesDetailModal from '@/Pages/Admin/User/ActivitiesDetailModal.vue'
import { Head } from "@inertiajs/vue3";
import { Bar } from "vue-chartjs";
import { ref } from "vue";

const props = defineProps({
  user: Object,
  callsCount: Number,
  activitiesCount: Number,
  transactionsCount: Number,
});
let showTransactionModal = ref(false);
let showCallsModal = ref(false);
let showActivitiesModal = ref(false);
let detailType = ref(null);

let openTransactionModal = (type) => {
  if (type == 'transactions') {
    detailType.value = type;
    showTransactionModal.value = true;
  } else if (type == 'calls') {
    detailType.value = type;
    showCallsModal.value = true;
  } else if (type == 'activities') {
    detailType.value = type;
    showActivitiesModal.value = true;
  }

};
</script>

<template>
  <Head title="Dashboard" />
  <AuthenticatedLayout>
    <div class="pt-14">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="px-4 sm:px-8 sm:rounded-lg">
          <div class="text-4xl text-custom-sky font-bold mb-6">
            {{ user.first_name }} {{ user.last_name }}</div>
          <hr class="mb-4" />
        </div>
      </div>
    </div>

    <div class="px-16 pt-14">
      <div class="mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div @click="openTransactionModal('transactions')"
          class="max-w-sm p-6 bg-custom-darksky cursor-pointer rounded-lg shadow overflow-auto">
          <p class="mb-1 text-sm text-gray-300">Total Transactions</p>
          <h2 class="mb-2 text-2xl sm:text-3xl lg:text-4xl font-bold text-white">
            {{ transactionsCount }}
          </h2>
        </div>
        <div @click="openTransactionModal('calls')"
          class="max-w-sm p-6 cursor-pointer bg-custom-darksky rounded-lg shadow overflow-auto">
          <p class="mb-1 text-sm text-gray-300">Total Calls</p>
          <h2 class="mb-2 text-2xl sm:text-3xl lg:text-4xl font-bold text-white">
            {{ callsCount }}
          </h2>
        </div>
        <div @click="openTransactionModal('activities')"
          class="max-w-sm cursor-pointer p-6 bg-custom-darksky rounded-lg shadow overflow-auto">
          <p class="mb-1 text-sm text-gray-300">
            Total Activities
          </p>
          <h2 class="mb-2 text-2xl sm:text-3xl lg:text-4xl font-bold text-white">
            {{ activitiesCount }}
          </h2>
        </div>
      </div>
    </div>



    <section class="px-16 pt-14">
      <div v-if="user">
      <div class="flex justify-between items-center">
        <h4 class="text-2xl font-semibold text-custom-sky mb-2">
          Personal Details
        </h4>
      </div>

      <div class="grid sm:grid-cols-1 md:grid-cols-2 gap-2 text-gray-700 mb-10">
        <div>
          <strong class="text-lg">First Name: </strong>
          {{ user.first_name }}
        </div>
        <div>
          <strong class="text-lg">Last Name: </strong>
          {{ user.last_name }}
        </div>
      </div>

      <h4 class="text-2xl font-semibold text-custom-sky mb-2">
        Contact Information
      </h4>
      <div class="grid sm:grid-cols-1 md:grid-cols-2 gap-2 text-gray-700 mb-10">
        <div>
          <strong class="text-lg">Phone: </strong>
          {{ user.phone }}
        </div>
        <div>
          <strong class="text-lg">Email: </strong>
          {{ user.email || "N/A" }}
        </div>
      </div>


      <h4 class="text-2xl font-semibold text-custom-sky mb-2">
        Other Information
      </h4>
      <div class="grid sm:grid-cols-1 md:grid-cols-2 gap-2 text-gray-700 mb-10">
        <div>
          <strong class="text-lg">Balance: </strong>
          {{ user.balance || "N/A" }}
        </div>

        <div>
          <strong class="text-lg">PM Type: </strong>
          {{ user.pm_type || "N/A" }}
        </div>

        <div>
          <strong class="text-lg">PM Last Four: </strong>
          {{ user.pm_last_four || "N/A" }}
        </div>

        <div>
          <strong class="text-lg">Trail Ends At: </strong>
          {{ user.trial_ends_at || "N/A" }}
        </div>

        <div>
          <strong class="text-lg">Balance: </strong>
          {{ user.balance || "N/A" }}
        </div>
      </div>

    </div>
    </section>

    <TransactionDetailModal :showTransactionModal="showTransactionModal" :detailType="detailType" :user="user"
      @close="showTransactionModal = false"></TransactionDetailModal>

    <CallsDetailModal :showCallsModal="showCallsModal" :detailType="detailType" :user="user"
      @close="showCallsModal = false"></CallsDetailModal>

    <ActivitiesDetailModal :showActivitiesModal="showActivitiesModal" :detailType="detailType" :user="user"
      @close="showActivitiesModal = false"></ActivitiesDetailModal>
  </AuthenticatedLayout>
</template>
