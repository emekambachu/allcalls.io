<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, reactive, onMounted } from "vue";
// import { useShepherd } from 'vue-shepherd';

const headline = ref(null);

// const tour = useShepherd({
//   useModalOverlay: true
// });
  
onMounted(() =>  {
  // tour.addStep({
  //   attachTo: { element: headline.value, on: 'top' },
  //   text: 'Test'
  // });

  // tour.start();
});


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
          <ul
            class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400"
          >
            <li class="mr-2">
              <Link
                href="/billing/funds"
                class="inline-flex items-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group"
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
              <a
                href="/billing/cards"
                class="inline-flex p-4 items-center text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500 group"
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
              </a>
            </li>
          </ul>
        </div>

        <div>
          <section class="bg-white dark:bg-gray-900">
            <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
              <h2 ref="headline" class="mb-4 text-xl font-bold text-gray-900 dark:text-white">
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
  

  