<script setup>
import BillingNav from "@/Components/BillingNav.vue";
import TextInput from "@/Components/TextInput.vue";
import AuthenticatedButton from "@/Components/AuthenticatedButton.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
let props = defineProps({
  cards: {
    type: Array,
  },
});

import { createToaster } from "@meforma/vue-toaster";

const page = usePage();

const toaster = createToaster({
  position: "top-right",
});

if (page.props.flash.message) {
  toaster.success(page.props.flash.message);
}

let amount = ref(100);
let selectedCardId = ref(0);
let selectCard = (cardId) => {
  console.log("clicked", cardId);
  selectedCardId.value = Number(cardId);
};

let addFunds = () => {
  let cardId = selectedCardId.value;

  if (cardId === 0 && props.cards.length) {
    cardId = props.cards[0].id;
  }

  router.visit("/billing/funds", {
    method: "post",
    data: {
      amount: amount.value,
      cardId: cardId,
    },
  });
};
</script>

<template>
  <Head title="Funds" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-300 leading-tight">Billing</h2>
    </template>

    <div class="sm:py-10">
      <form class="mx-auto max-w-7xl">
        <div class="sm:pl-10 max-w-6xl">
          <BillingNav></BillingNav>
        </div>

        <div v-if="cards.length">
          <section class="mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 sm:rounded-lg">
              <h2 class="text-4xl text-custom-sky font-bold mb-6">Add funds</h2>
              <hr class="mb-8" />

              <div
                :class="{
                  'max-w-lg flex items-center px-2 py-4 mt-4 rounded-lg shadow hover:bg-gray-300 hover:font-medium mb-2 cursor-pointer select-none': true,
                  'border border-custom-indigo bg-custom-blue text-blue-600 shadow-2xl font-bold':
                    selectedCardId === card.id,
                  'border border-gray-500 text-gray-500':
                    selectedCardId !== card.id,
                }"
                v-for="card in cards"
                :key="card.id"
                @click.prevent="selectCard(card.id)"
              >
                <div class="ml-2 text-sm font-medium flex items-center">
                  <span class="uppercase mr-4">{{ card.type }}</span> **** ****
                  **** {{ card.last4 }}
                </div>
              </div>
              <div class="mt-4 max-w-lg">
                <label
                  for="amount"
                  class="block mb-2 text-sm font-medium text-custom-white"
                  >Amount</label
                >
                <div class="flex mt-2">
                  <span
                    class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md"
                  >
                    $
                  </span>
                  <TextInput
                    type="number"
                    id="amount"
                    class="rounded-none rounded-r-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5"
                    placeholder="500"
                    v-model="amount"
                    :disabled="selectedCardId === 0"
                  />
                </div>
              </div>

              <AuthenticatedButton
                type="button"
                class=""
                @click.prevent="addFunds"
              >
                Add funds
              </AuthenticatedButton>
            </div>
          </section>
        </div>

        <div v-else class="text-center sm:px-6 lg:px-8">
          <p class="text-gray-300 mt-6 mb-8">
            You haven't added any cards yet.
          </p>
          <AuthenticatedButton class="">
            <Link href="/billing/cards"> Add credit card </Link>
          </AuthenticatedButton>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>
  

  