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
      <div
        class="mx-8 px-12 py-4 sm:p-8 sm:rounded-lg"
        style="padding-bottom: 0"
      >
        <h2
          id="headline"
          class="text-4xl text-custom-sky font-bold mb-6"
          style="padding-bottom: 0"
        >
          Add Funds
        </h2>
        <hr class="mb-8" />
      </div>
      <form class="mx-auto max-w-7xl">
        <!-- <div class="sm:pl-10 max-w-6xl">
          <BillingNav></BillingNav>
        </div> -->

        <div v-if="cards.length">
          <section class="mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 sm:rounded-lg" style="padding-top: 0">
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
                <div class="flex mt-2 bg-custom-blue">
                  <span
                    class="inline-flex items-center px-3 text-sm text-white bg-custom-blue border-r-0 rounded-l-md"
                  >
                    $
                  </span>

                  <input
                    type="number"
                    id="amount"
                    placeholder="500"
                    v-model="amount"
                    :disabled="selectedCardId === 0"
                    class="bg-custom-blue text-sm rounded-lg focus:ring-transparent focus:border-blue-500 block w-full p-2.5 text-white outline-none border-none"
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
  

  