<script setup>
import BillingNav from "@/Components/BillingNav.vue";
import TextInput from "@/Components/TextInput.vue";
import AuthenticatedButton from "@/Components/AuthenticatedButton.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import { ref, reactive, onMounted } from "vue";
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
  toaster.success(page.props.flash.message, {
    duration: false,
  });
}

let selectedCardId = ref("0");
let cardForm = reactive({
  amount: 100,
  number: "",
  month: "",
  year: "",
  cvv: "",
  address: "",
  city: "",
  state: "",
  zip: "",
});

let selectCard = (cardId) => {
  console.log("clicked", cardId);
  selectedCardId.value = Number(cardId);
};
let addFunds = () => {
  if (cardForm.amount && cardForm.amount != Math.round(cardForm.amount)) {
    alert("Only whole dollar amounts allowed.");
    return;
  }

  let cardId = selectedCardId.value;

  if (cardId === '0') {
    router.visit("/billing/funds-with-card", {
      method: "post",
      data: cardForm,
    });

    return;
  }

  // if (cardId === 0 && props.cards.length) {
  //   cardId = props.cards[0].id;
  // }

  router.visit("/billing/funds", {
    method: "post",
    data: {
      amount: cardForm.amount,
      cardId: cardId,
    },
  });
};

onMounted(() => {
});
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
        <div v-if="cards.length">
          <section class="mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 sm:rounded-lg" style="padding-top: 0">
              <!-- <h2 class="text-xl">Choose from your cards</h2> -->
              <!-- <div
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
              </div> -->

              <label class="block mb-2 text-sm font-medium text-gray-700"
                >Select your card</label
              >

              <select
                v-model="selectedCardId"
                class="select-custom"
              >
                <option
                  v-for="card in cards"
                  :key="card.id"
                  :value="card.id"
                  :class="{
                    'border-custom-indigo bg-custom-blue text-blue-600 shadow-2xl font-bold':
                      selectedCardId === card.id,
                    'border-gray-500 text-gray-500': selectedCardId !== card.id,
                  }"
                >
                  {{ card.type }} **** **** **** {{ card.last4 }}
                </option>
                <option value="0">Add a new card</option>
              </select>

              <!-- <h2 class="text-xl my-4">Or add a new card:</h2> -->

              <form
                v-if="selectedCardId === '0'"
                @submit.prevent=""
                class="my-12"
                autocomplete="on"
              >

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
                      class="select-custom"
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
                      class="select-custom"
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
                      class="select-custom"
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
              </form>

              <div class="mt-4 max-w-lg">
                <label
                  for="amount"
                  class="block mb-2 text-sm font-medium text-gray-500"
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
                    placeholder="500"
                    v-model="cardForm.amount"
                    step="1"
                    min="0"
                    class="rounded-none rounded-r-lg border focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 border-gray-400 placeholder-gray-400 "
                  />
                </div>
              </div>

              <p class="text-gray-700 text-xs max-w-lg mt-4">
                By clicking the "Add Funds" button below I authorize AllCalls
                LLC to charge my card and agree to be billed for ${{
                  cardForm.amount !== '' ? cardForm.amount : 0
                }}. This is a one-time purchase. Funds will be added to your
                account immediately. Your credit card will be billed as
                "AllCalls.io" on your billing statement.
              </p>

              <AuthenticatedButton
                type="button"
                class="mt-3"
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
  

  