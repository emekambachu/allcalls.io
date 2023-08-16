<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { reactive } from "vue";
import { createToaster } from "@meforma/vue-toaster";
import BillingNav from "@/Components/BillingNav.vue";
import TextInput from "@/Components/TextInput.vue";
import AuthenticatedButton from "@/Components/AuthenticatedButton.vue";

const page = usePage();

const toaster = createToaster({
  position: "top-right",
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
    method: "delete",
  });
};

let getCardType = (cardNumber) => {
  console.log(cardNumber);

  if (typeof cardNumber !== "string") {
    return "Unknown";
  }

  if (cardNumber.startsWith("4")) {
    return "visa";
  } else if (
    /^5[1-5]/.test(cardNumber) ||
    /^2[2-7][1-9][0-9]/.test(cardNumber)
  ) {
    return "mastercard";
  } else if (/^3[47]/.test(cardNumber)) {
    return "amex";
  } else if (/^6(?:011|5)/.test(cardNumber)) {
    return "discover";
  } else {
    return "Unknown";
  }
};

let setAsDefault = (cardId) => {
  router.visit(`/billing/cards/default/${cardId}`, {
    method: "PATCH",
  });
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

    <div class="sm:py-10">
      <div class="mx-auto max-w-7xl">
        <div>
          <section class="mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 sm:rounded-lg">
              <div class="text-4xl text-custom-sky font-bold mb-6">
                Saved Cards
              </div>
              <hr class="mb-8" />

              <div class="max-w-md">
                <div class="flow-root">
                  <ul>
                    <li
                      class="p-3 rounded-lg border-2 border-gray-200 hover:border-gray-300 mb-6 flex items-center relative"
                      v-for="card in cards"
                      :key="card.id"
                    >
                      <span
                        v-if="card.default"
                        class="bg-blue-100 tracking-wide uppercase text-blue-500 text-xs font-bold mr-2 px-2 py-0.5 rounded-md border-2 border-blue-300 absolute -top-4 -right-8"
                        style="font-size:10px;"
                        >Default</span
                      >

                      <img
                        class="h-8 max-h-full mr-4 bg-gray-200 border border-gray-300 rounded shadow"
                        :src="`/img/${card.cardType}.svg`"
                        :alt="card.cardType"
                      />
                      <div class="w-full flex justify-between items-center">
                        <div>
                          <h3
                            class="text-xs text-gray-800 font-bold tracking-wide"
                          >
                            {{
                              card.cardType.charAt(0).toUpperCase() +
                              card.cardType.slice(1)
                            }}
                            xxxx {{ card.last4 }}
                          </h3>
                          <p class="text-xs text-gray-700 tracking-wide">
                            Expires on {{ card.expiryDate }}
                          </p>
                        </div>
                        <div class="flex items-center">
                          <button
                            type="button"
                            class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded px-2 py-2 text-xs flex items-center mr-2"
                            @click.prevent="setAsDefault(card.id)"
                            v-if="!card.default"
                          >
                            Set As Default
                          </button>
                          <button
                            type="button"
                            class="text-white bg-red-700 hover:bg-red-800 font-medium rounded px-2 py-2 text-xs flex items-center"
                            @click.prevent="removeCard(card.id)"
                          >
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              fill="none"
                              viewBox="0 0 24 24"
                              stroke-width="2"
                              stroke="currentColor"
                              class="w-4 h-4 mr-1"
                            >
                              <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                              />
                            </svg>
                            Delete
                          </button>
                        </div>
                      </div>
                    </li>
                  </ul>

                  <div v-if="!cards.length">
                    <p class="text-gray-600">
                      You haven't added any cards yet.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>

        <div></div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
