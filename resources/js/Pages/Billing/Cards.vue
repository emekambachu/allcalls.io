<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { reactive } from "vue";
import { createToaster } from "@meforma/vue-toaster";
import BillingNav from '@/Components/BillingNav.vue';

const page = usePage()

const toaster = createToaster({
  position: 'top-right',
});

if (page.props.flash.message) {
  toaster.success(page.props.flash.message);
}

let props = defineProps({
  cards: {
    type: Array,
  },
});

let cardForm = reactive({
  number: "",
  month: "",
  year: "",
  cvv: "",
  address: "",
  city: "",
  state: "",
  zip: "",
});

let addNewCard = () => {
  router.visit("/billing/cards", {
    method: "post",
    data: cardForm,
  });
};

let removeCard = (id) => {
  router.visit(`/billing/cards/${id}`, {
    method: 'delete',
  });
}

// onMounted(() => {
  // tippy('#step-one', {
  //   content: 'Step one',
  //   placement: 'bottom-start',
  //   trigger: 'manual',
  //   inertia: true,
  //   showOnCreate: true,
  //   allowHTML: true,
  // });
// });
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
          <section class="bg-white dark:bg-gray-900">
            <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">

              <h2 id="headline" class="mb-4 text-xl font-bold text-gray-900 dark:text-white">
                Add a credit card
              </h2>
              <form @submit.prevent="addNewCard()">
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6 mb-6">
                  <div class="sm:col-span-2">
                    <label
                      for="number"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >Card number</label
                    >
                    <input
                      type="text"
                      name="number"
                      id="number"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                      placeholder="XXXXXXXXXXXXXXXX"
                      required
                      v-model="cardForm.number"
                      maxlength="16"
                      minlength="16"
                    />
                  </div>
                  <div class="w-full">
                    <label
                      for="month"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >Expiration month</label
                    >
                    <input
                      type="number"
                      name="month"
                      id="month"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                      placeholder="MM"
                      required
                      min="1"
                      max="12"
                      v-model="cardForm.month"
                    />
                  </div>
                  <div class="w-full">
                    <label
                      for="year"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >Expiration year</label
                    >
                    <input
                      type="number"
                      name="year"
                      id="year"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                      placeholder="YYYY"
                      required
                      min="2023"
                      max="2030"
                      v-model="cardForm.year"
                    />
                  </div>
                  <div class="w-full">
                    <label
                      for="cvv"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >CVV</label
                    >
                    <input
                      type="password"
                      name="cvv"
                      id="cvv"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                      placeholder="XXX"
                      required
                      pattern="[0-9]{3}"
                      v-model="cardForm.cvv"
                    />
                  </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                  <div class="w-full">
                    <label
                      for="street"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >Street address</label
                    >
                    <input
                      type="text"
                      name="street"
                      id="street"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                      placeholder="123 Main St"
                      required
                      v-model="cardForm.address"
                    />
                  </div>
                  <div class="w-full">
                    <label
                      for="city"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >City</label
                    >
                    <input
                      type="text"
                      name="city"
                      id="city"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                      placeholder="New York"
                      required
                      v-model="cardForm.city"
                    />
                  </div>
                  <div class="w-full">
                    <label
                      for="state"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >State</label
                    >
                    <input
                      type="text"
                      name="state"
                      id="state"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                      placeholder="NY"
                      required
                      v-model="cardForm.state"
                    />
                  </div>
                  <div class="w-full">
                    <label
                      for="zip"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >ZIP code</label
                    >
                    <input
                      type="text"
                      name="zip"
                      id="zip"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                      placeholder="10001"
                      required
                      v-model="cardForm.zip"
                    />
                  </div>
                </div>

                <button
                  id="button"
                  type="submit"
                  class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 mt-6"
                >
                  Add credit card
                </button>
              </form>
            </div>
          </section>
        </div>

        <div>
          <div class="px-4 mx-auto max-w-2xl">
            <div class="flex items-center justify-between">
              <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">
                Your Cards
              </h2>
            </div>
            <div class="flow-root">
              <ul
                role="list"
                class="divide-y divide-gray-200 dark:divide-gray-700"
              >
                <li class="py-3 sm:py-4" v-for="card in cards" :key="card.id">
                  <div class="flex items-center space-x-4">
                    <div class="flex-1 min-w-0">
                      <p
                        class="text-sm font-medium text-gray-900 truncate dark:text-white"
                      >
                        Visa ending in {{ card.last4 }}
                      </p>
                      <p
                        class="text-sm text-gray-500 truncate dark:text-gray-400"
                      >
                        Expiry date: {{ card.expiryDate }}
                      </p>
                    </div>
                    <div
                      class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white"
                    >
                      <button
                        type="button"
                        class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg px-3 py-2 text-xs mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
                        @click.prevent="removeCard(card.id)"
                      >
                        Remove Card
                      </button>
                    </div>
                  </div>
                </li>
              </ul>

              <div v-if="! cards.length">

                <p class="dark:text-gray-400">You haven't added any cards yet.</p>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
