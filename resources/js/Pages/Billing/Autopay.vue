<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import BillingNav from "@/Components/BillingNav.vue";
import AuthenticatedButton from "@/Components/AuthenticatedButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { ref, onMounted } from "vue";
import { Head, router, usePage } from "@inertiajs/vue3";

import { createToaster } from "@meforma/vue-toaster";

let page = usePage();

let toaster = createToaster({
  position: "top-right",
});

if (page.props.flash.message) {
  toaster.success(page.props.flash.message);
}

let props = defineProps({
  cards: {
    type: Array,
  },
  setting: {
    type: Object,
  },
});


let settings = ref({
  enabled: false,
  threshold: 500,
  amount: 100,
  card_id: null,
});






let chosenCard = ref(null);

let saveChanges = () => {
  router.visit("/billing/autopay", {
    method: "post",
    data: {
      enabled: settings.value.enabled ? 1 : 0,
      threshold: settings.value.threshold,
      amount: settings.value.amount,
      card_id: chosenCard.value.id,
    },
  });
};

let chooseCard = (card) => {
  chosenCard.value = card;
};


if (props.setting) {
  settings.value = {
    enabled: (props.setting.enabled === 1) ? true : false,
    threshold: props.setting.threshold,
    amount: props.setting.amount,
    card_id: props.setting.card_id,
  }

  chosenCard.value = props.cards.filter(card => {
    return card.id === props.setting.card_id;
  })[0];
}

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

    <div class="sm:py-10">
      <div class="mx-auto max-w-7xl">
        <div class="sm:pl-10 max-w-6xl">
          <BillingNav></BillingNav>
        </div>

        <div>
          <section class="mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 sm:rounded-lg">
              <h2 id="headline" class="text-4xl text-custom-sky font-bold mb-6">
                Add Autopay
              </h2>
              <hr class="mb-8" />
            
              <div v-if="cards.length" class="max-w-lg">
                <label
                  class="relative inline-flex items-center mb-4 cursor-pointer"
                >
                  <input
                    type="checkbox"
                    value=""
                    class="sr-only peer"
                    :checked="settings.enabled"
                    v-model="settings.enabled"
                  />
                  <div
                    class="w-11 h-6 bg-custom-blue rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"
                  ></div>
                  <span
                    class="ml-3 text-sm font-medium text-gray-300"
                    >Enable</span
                  >
                </label>

                <div class="text-gray-300">When the balance is below:</div>

                <div>
                  <div class="flex mt-2">
                    <span
                      class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md"
                    >
                      $
                    </span>
                    <TextInput
                      type="number"
                      id="threshold"
                      class="rounded-none rounded-r-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5"
                      placeholder="100.00"
                      v-model="settings.threshold"
                    />
                  </div>
                </div>

                <div class="text-gray-300 mt-2">Add:</div>

                <div>
                  <div class="flex mt-2">
                    <span
                      class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md"
                    >
                      $
                    </span>
                    <TextInput
                      type="number"
                      id="amount"
                      class="rounded-none rounded-r-lg border focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5 border-gray-400 placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                      placeholder="50.00"
                      v-model="settings.amount"
                    />
                  </div>
                </div>

                <div class="text-gray-300 mt-2">
                  to the account using the following card:
                </div>

                <div
                  :class="{
                    'flex items-center px-2 py-4 mt-4 rounded-lg shadow hover:bg-gray-300 hover:font-medium mb-2 cursor-pointer select-none': true,
                    'border border-custom-indigo bg-custom-blue text-blue-600 shadow-2xl font-bold':
                      chosenCard && chosenCard.id === card.id,
                    'border border-gray-500 text-gray-500':
                      !chosenCard || chosenCard.id !== card.id,
                  }"
                  v-for="card in cards"
                  :key="card.id"
                  @click.prevent="chooseCard(card)"
                >
                  <div
                    class="ml-2 text-sm flex items-center"
                  >
                    <span class="uppercase mr-4">{{ card.type }}</span> **** ****
                    **** {{ card.last4 }}
                  </div>
                </div>

                <AuthenticatedButton
                  @click.prevent="saveChanges()"
                  type="button"
                >
                  Save Changes
                </AuthenticatedButton>
              </div>

              <div v-else>
                <p class="text-gray-400 mt-6 mb-8">You haven't added any cards yet.</p>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
  

  