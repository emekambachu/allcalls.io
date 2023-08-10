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

let getCardType = cardNumber => {

  console.log(cardNumber);

    if (typeof cardNumber !== 'string') {
        return 'Unknown';
    }

    if (cardNumber.startsWith('4')) {
        return 'visa';
    } else if (/^5[1-5]/.test(cardNumber) || /^2[2-7][1-9][0-9]/.test(cardNumber)) {
        return 'mastercard';
    } else if (/^3[47]/.test(cardNumber)) {
        return 'amex';
    } else if (/^6(?:011|5)/.test(cardNumber)) {
        return 'discover';
    } else {
        return 'Unknown';
    }
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
        <div>
          <section class="mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 sm:rounded-lg">
              <h2 id="headline" class="text-4xl text-custom-sky font-bold mb-6">
                Add a new card
              </h2>
              <hr class="mb-8" />

              <div class="flex justify-center">
                <img
                  class="h-8 mr-3 border border-gray-300 shadow rounded"
                  src="/img/visa.svg"
                  alt="VISA"
                />
                <img
                  class="h-8 mr-3 border border-gray-300 shadow rounded"
                  src="/img/mastercard.svg"
                  alt="MASTERCARD"
                />
                <img
                  class="h-8 mr-3 border border-gray-300 shadow rounded"
                  src="/img/amex.svg"
                  alt="AMERICAN EXPRESS"
                />
                <img
                  class="h-8 mr-3 border border-gray-300 shadow rounded"
                  src="/img/discover.svg"
                  alt="DISCOVER"
                />
              </div>

              <form
                @submit.prevent="addNewCard()"
                class="mb-12"
                autocomplete="on"
              >
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6 mb-6">
                  <div class="sm:col-span-2">
                    <label
                      for="number"
                      class="block mb-2 text-sm font-medium text-gray-700"
                      >Card number</label
                    >
                    <TextInput
                      type="text"
                      name="number"
                      id="number"
                      class="w-full"
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
                      class="block mb-2 text-sm font-medium text-gray-700"
                      >Expiration month</label
                    >
                    <select
                      name="month"
                      id="month"
                      class="bg-custom-blue text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 text-white outline-none border-none"
                      v-model="cardForm.month"
                      required
                    >
                      <option selected="" disabled="" value="">
                        Select Month
                      </option>
                      <option value="01">January</option>
                      <option value="02">February</option>
                      <option value="03">March</option>
                      <option value="04">April</option>
                      <option value="05">May</option>
                      <option value="06">June</option>
                      <option value="07">July</option>
                      <option value="08">August</option>
                      <option value="09">September</option>
                      <option value="10">October</option>
                      <option value="11">November</option>
                      <option value="12">December</option>
                    </select>
                  </div>

                  <div class="w-full">
                    <label
                      for="year"
                      class="block mb-2 text-sm font-medium text-gray-700"
                      >Expiration year</label
                    >
                    <select
                      name="year"
                      id="year"
                      class="bg-custom-blue text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 text-white outline-none border-none"
                      v-model="cardForm.year"
                      required
                    >
                      <option selected="" disabled="" value="">
                        Select Year
                      </option>
                      <option value="2023">2023</option>
                      <option value="2024">2024</option>
                      <option value="2025">2025</option>
                      <option value="2026">2026</option>
                      <option value="2027">2027</option>
                      <option value="2028">2028</option>
                      <option value="2029">2029</option>
                      <option value="2030">2030</option>
                      <option value="2031">2031</option>
                      <option value="2032">2032</option>
                      <option value="2033">2033</option>
                    </select>
                  </div>

                  <div class="w-full">
                    <label
                      for="cvv"
                      class="block mb-2 text-sm font-medium text-gray-700"
                      >CVV</label
                    >
                    <TextInput
                      type="password"
                      name="cvv"
                      id="cvv"
                      class="w-full"
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
                      class="block mb-2 text-sm font-medium text-gray-700"
                      >Street address</label
                    >
                    <TextInput
                      type="text"
                      name="street"
                      id="street"
                      class="w-full"
                      placeholder="123 Main St"
                      required
                      v-model="cardForm.address"
                    />
                  </div>
                  <div class="w-full">
                    <label
                      for="city"
                      class="block mb-2 text-sm font-medium text-gray-700"
                      >City</label
                    >
                    <TextInput
                      type="text"
                      name="city"
                      id="city"
                      class="w-full"
                      placeholder="New York"
                      required
                      v-model="cardForm.city"
                    />
                  </div>
                  <div class="w-full">
                    <label
                      for="state"
                      class="block mb-2 text-sm font-medium text-gray-700"
                      >State</label
                    >
                    <select
                      name="state"
                      id="state"
                      class="bg-custom-blue text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 text-white outline-none border-none"
                      v-model="cardForm.state"
                      required
                    >
                      <option selected="" disabled="" value="">
                        Select State
                      </option>
                      <option value="AL">Alabama</option>
                      <option value="AK">Alaska</option>
                      <option value="AZ">Arizona</option>
                      <option value="AR">Arkansas</option>
                      <option value="CA">California</option>
                      <option value="CO">Colorado</option>
                      <option value="CT">Connecticut</option>
                      <option value="DE">Delaware</option>
                      <option value="FL">Florida</option>
                      <option value="GA">Georgia</option>
                      <option value="HI">Hawaii</option>
                      <option value="ID">Idaho</option>
                      <option value="IL">Illinois</option>
                      <option value="IN">Indiana</option>
                      <option value="IA">Iowa</option>
                      <option value="KS">Kansas</option>
                      <option value="KY">Kentucky</option>
                      <option value="LA">Louisiana</option>
                      <option value="ME">Maine</option>
                      <option value="MD">Maryland</option>
                      <option value="MA">Massachusetts</option>
                      <option class="text-black" value="MI">Michigan</option>
                      <option class="text-black" value="MN">Minnesota</option>
                      <option class="text-black" value="MS">Mississippi</option>
                      <option class="text-black" value="MO">Missouri</option>
                      <option class="text-black" value="MT">Montana</option>
                      <option class="text-black" value="NE">Nebraska</option>
                      <option class="text-black" value="NV">Nevada</option>
                      <option class="text-black" value="NH">
                        New Hampshire
                      </option>
                      <option class="text-black" value="NJ">New Jersey</option>
                      <option class="text-black" value="NM">New Mexico</option>
                      <option class="text-black" value="NY">New York</option>
                      <option class="text-black" value="NC">
                        North Carolina
                      </option>
                      <option class="text-black" value="ND">
                        North Dakota
                      </option>
                      <option class="text-black" value="OH">Ohio</option>
                      <option class="text-black" value="OK">Oklahoma</option>
                      <option class="text-black" value="OR">Oregon</option>
                      <option class="text-black" value="PA">
                        Pennsylvania
                      </option>
                      <option class="text-black" value="RI">
                        Rhode Island
                      </option>
                      <option class="text-black" value="SC">
                        South Carolina
                      </option>
                      <option class="text-black" value="SD">
                        South Dakota
                      </option>
                      <option class="text-black" value="TN">Tennessee</option>
                      <option class="text-black" value="TX">Texas</option>
                      <option class="text-black" value="UT">Utah</option>
                      <option class="text-black" value="VT">Vermont</option>
                      <option class="text-black" value="VA">Virginia</option>
                      <option class="text-black" value="WA">Washington</option>
                      <option class="text-black" value="WV">
                        West Virginia
                      </option>
                      <option class="text-black" value="WI">Wisconsin</option>
                      <option class="text-black" value="WY">Wyoming</option>
                    </select>
                  </div>

                  <div class="w-full">
                    <label
                      for="zip"
                      class="block mb-2 text-sm font-medium text-gray-700"
                      >ZIP code</label
                    >
                    <TextInput
                      type="text"
                      name="zip"
                      id="zip"
                      class="w-full"
                      placeholder="10001"
                      required
                      v-model="cardForm.zip"
                    />
                  </div>
                </div>

                <AuthenticatedButton id="button" type="submit" class="mt-4">
                  Add credit card
                </AuthenticatedButton>
              </form>

              <div class="text-4xl text-custom-sky font-bold mb-6">
                Saved Cards
              </div>
              <hr class="mb-8" />

              <div class="max-w-md">
                <div class="flow-root">
                  <ul>
                    <li
                      class="p-3 rounded-lg border-2 border-gray-200 hover:border-gray-300 mb-4 flex items-center"
                      v-for="card in cards"
                      :key="card.id"
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
                            {{ card.cardType.charAt(0).toUpperCase() + card.cardType.slice(1) }} xxxx {{ card.last4 }}
                          </h3>
                          <p class="text-xs text-gray-700 tracking-wide">
                            Expires on {{ card.expiryDate }}
                          </p>
                        </div>
                        <div>
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
