<script setup>
import BillingNav from '@/Components/BillingNav.vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import { ref } from 'vue';
let props = defineProps({
  cards: {
    type: Array
  }
});

import { createToaster } from "@meforma/vue-toaster";

const page = usePage()

const toaster = createToaster({
  position: 'top-right',
});

if (page.props.flash.message) {
  toaster.success(page.props.flash.message);
}

let amount = ref(100);
let selectedCardId = ref(0);
let selectCard = cardId => {
  console.log('clicked', cardId);
  selectedCardId.value = Number(cardId);
}

let addFunds = () => {
  let cardId = selectedCardId.value;

  if (cardId === 0 && props.cards.length){
    cardId = props.cards[0].id
  }


  router.visit("/billing/funds", {
    method: "post",
    data: {
      amount: amount.value,
      cardId: cardId,
  }
  });
};
</script>

<template>
  <Head title="Funds" />

  <AuthenticatedLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-300 leading-tight"
      >
        Billing
      </h2>
    </template>

    <div class="py-12">
      <form class="px-4 mx-auto max-w-4xl">
        <div class="border-b border-gray-200 dark:border-gray-700">
          <BillingNav></BillingNav>
        </div>

        <div v-if="cards.length" class="py-4 px-4 mx-auto max-w-2xl lg:py-16">
          <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">
            Add funds
          </h2>

          <div
            :class="{
              'flex items-center px-2 py-4 bg-white border-2 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 mb-2 cursor-pointer select-none': true,
              'border-blue-900 dark:border-blue-900': selectedCardId === card.id,
              'border-gray-200 dark:border-gray-700': selectedCardId !== card.id,
            }"
            v-for="card in cards"
            :key="card.id"
            @click.prevent="selectCard(card.id)"
          >
            <div
              class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300 flex items-center"
            >
              <span class="uppercase mr-4">{{ card.type }}</span> **** **** **** {{ card.last4 }}
            </div>
          </div>

          <div class="mt-4">
            <label
              for="amount"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >Amount</label
            >
            <div class="relative mb-6">
              <div
                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none dark:text-white"
              >
                $
              </div>
              <input
                type="number"
                id="amount"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-8 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="500"
                v-model="amount"
                :disabled="selectedCardId === 0"
              />
            </div>
          </div>

          <button
            type="button"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
            @click.prevent="addFunds"
          >
            Add funds
          </button>
        </div>

        <div v-else class="text-center">
          <p class="dark:text-gray-400 mt-6 mb-8">You haven't added any cards yet.</p>
          <Link
            href="/billing/cards"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
          >
            Add credit card
        </Link>        
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>
  

  