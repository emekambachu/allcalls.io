<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import BillingNav from "@/Components/BillingNav.vue";
import { ref } from 'vue';

defineProps({
  cards: {
    type: Array,
  },
});

let settings = ref({
  enabled: false,
  threshold: 500,
  amount: 100,
});

let chosenCard = ref(null);

// let saveChanges = () => {
//   router.visit('/billing/autopay', {
//     method: 'post',
//     data: settings.value,
//   });
// }

let chooseCard = (card) => {
  chosenCard.value = card;
};
</script>

<template>
  <Head title="Cards" />

  <AuthenticatedLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
      >
        Billing
      </h2>
    </template>

    <div class="py-12">
      <div class="px-4 mx-auto max-w-4xl">
        <div class="border-b border-gray-200 dark:border-gray-700">
          <BillingNav></BillingNav>
        </div>

        <div>
          <section class="bg-white dark:bg-gray-900 mt-14 mx-20">
            <div class="mt-4">
              <div class="flex items-center justify-between">
                <h2
                  class="mb-8 text-xl font-bold text-gray-900 dark:text-white"
                >
                  Autopay Settings
                </h2>
              </div>
            </div>


            <label class="relative inline-flex items-center mb-4 cursor-pointer">
              <input type="checkbox" value="" class="sr-only peer" :checked="settings.enabled">
              <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
              <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">Enabled</span>
            </label>


            <div class="dark:text-gray-300">When the balance is below:</div>

            <div>
              <div class="flex mt-2">
                <span
                  class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600"
                >
                  $
                </span>
                <input
                  type="number"
                  id="threshold"
                  class="rounded-none rounded-r-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  placeholder="100.00"
                  v-model="settings.threshold"
                />
              </div>
            </div>

            <div class="dark:text-gray-300 mt-2">Add:</div>

            <div>
              <div class="flex mt-2">
                <span
                  class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600"
                >
                  $
                </span>
                <input
                  type="number"
                  id="amount"
                  class="rounded-none rounded-r-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  placeholder="50.00"
                  v-model="settings.amount"
                />
              </div>
            </div>

            <div class="dark:text-gray-300 mt-2">
              to the account using the following card:
            </div>

            <div
              :class="{
                'flex items-center px-2 py-4 mt-4 bg-white border-2 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 mb-2 cursor-pointer select-none border-gray-200 dark:border-gray-700': true,
              }"
              v-for="card in cards"
              :key="card.id"
              @click.prevent="chooseCard(card)"
            >
              <div
                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300 flex items-center"
              >
                <span class="uppercase mr-4">{{ card.type }}</span> **** ****
                **** {{ card.last4 }}
              </div>
            </div>


            <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 mt-2">Save Changes</button>
          </section>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
  

  