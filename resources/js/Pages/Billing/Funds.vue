<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ref } from 'vue';
let props = defineProps({
  cards: {
    type: Array
  }
});

let amount = ref(100);
let selectedCardId = ref(0);
let selectCard = cardId => {
  console.log('clicked', cardId);
  selectedCardId.value = cardId
}

let addFunds = () => {
  router.visit("/billing/funds", {
    method: "post",
    data: {
      amount: amount.value,
      cardId: selectedCardId.value,
  }
  });
};
</script>

<template>
  <Head title="Funds" />

  <AuthenticatedLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
      >
        Billing
      </h2>
    </template>

    <div class="py-12">
      <form class="px-4 mx-auto max-w-4xl">
        <div class="border-b border-gray-200 dark:border-gray-700">
          <ul
            class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400"
          >
            <li class="mr-2">
              <Link
                href="/billing/funds"
                class="inline-flex p-4 items-center text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500 group"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  class="w-4 h-4 mr-2"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"
                  />
                </svg>

                <span>Funds</span>
              </Link>
            </li>
            <li class="mr-2">
              <Link
                href="/billing/cards"
                class="inline-flex items-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group"
                aria-current="page"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  class="w-4 h-4 mr-2"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"
                  />
                </svg>

                <span>Cards</span>
              </Link>
            </li>
          </ul>
        </div>

        <div v-if="cards.length" class="py-4 px-4 mx-auto max-w-2xl lg:py-16">
          <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">
            Add funds
          </h2>

          <div
            class="flex items-center px-2 py-4 bg-white border-2 border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 mb-2 cursor-pointer select-none"
            :class="{
              'dark:border-blue-900': selectedCardId === card.id,
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
  

  