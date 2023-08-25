<script async setup>
import BillingNav from "@/Components/BillingNav.vue";
import TextInput from "@/Components/TextInput.vue";
import AuthenticatedButton from "@/Components/AuthenticatedButton.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";

import { ref, reactive, onMounted, watchEffect, computed } from "vue";
let props = defineProps({
  cards: {
    type: Array,
  },
  intent: {
    type: Object,
  },
});

import { createToaster } from "@meforma/vue-toaster";
import InputError from "@/Components/InputError.vue";

const page = usePage();

const toaster = createToaster({
  position: "top-right",
});

// Stripe Intregation Constant
const token = ref(null);
let stripe = Stripe(
  "pk_test_51JUMhZF43egAbbxbdvc4FIRiALFxHyYECIknypspzMqjYBQ47Kvt8TBY3g44gfhIQHJLPQT4GMwcqlqN1KwKPsbc00UfQoy1mu"
);
const elements = stripe.elements();
let cardElement = elements.create("card");
let shouldMount = ref(false);

if (page.props.flash.message) {
  toaster.success(page.props.flash.message);
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
const isLoading = ref(false)
let addFunds = () => {
  isLoading.value = true
  if (cardForm.amount && cardForm.amount != Math.round(cardForm.amount)) {
    alert("Only whole dollar amounts allowed.");
    return;
  }

  let cardId = selectedCardId.value;

  if (cardId === "0") {

    router.visit("/billing/funds-with-card", {
      method: "post",
      data: cardForm,
    });
  

    // Temporay Close
    // router.visit("/billing/funds-with-card", {
    //   method: "post",
    //   data: cardForm,
    // });

    console.log(stripe);
    console.log(cardElement);
    stripe
      .confirmCardSetup(props.intent.client_secret, {
        payment_method: {
          card: cardElement,
          billing_details: "This is for testing purpose",
        },
      })
      .then(function (result) {
        if (result.error) {
          console.log(result);
        } else {
          // Stripe Intregration
          router.visit("/billing/funds-with-card", {
            method: "post",
            data: {
              amount: cardForm.amount,
              paymentMethod: result.setupIntent.payment_method,
            },
          });
        }
      });
      isLoading.value = false
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
  isLoading.value = false
};
onMounted(() => {
  // Stripe Element

  mountCardElement();
  if (Object.values(page.props.errors).length > 0) {
    selectedCardId.value = "0";
  } else {
    for (let i = 0; i < props.cards.length; i++) {
      if (props.cards[i].default) {
        selectedCardId.value = props.cards[i].id;
      }
    }
  }
});

function mountCardElement() {
  if (cardElement) {
    cardElement.mount("#payment-element");
  }
}

let creditCardFee = computed(() => {
  let fee = Number(cardForm.amount) * 0.03;
  return String(fee.toFixed(2));
});

let total = computed(() => {
  let fee = Number(cardForm.amount) * 0.03;

  return String((Number(cardForm.amount) + fee).toFixed(2));
});
// After flash message page reload
if (page.props.flash.message) {
  page.props.flash.message = null;
  setInterval(() => {
    location.reload();
  }, 2000);
}
</script>
<template>
  <Head title="Funds" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-300 leading-tight">Billing</h2>
    </template>

    <div class="sm:py-10">
      <div class="mx-8 px-12 py-4 sm:p-8 sm:rounded-lg" style="padding-bottom: 0">
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
        <div>
          <section class="mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 sm:rounded-lg" style="padding-top: 0">
              <!-- Temporary Close -->
              <!-- <label class="block mb-2 text-sm font-medium text-gray-700"
                >Select your card</label
              >

              <select v-model="selectedCardId" class="select-custom">
                <option value="0">Add a new card</option>
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
              </select> -->
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
                <!-- Stripe Intregation -->
                <div class="payment-element border-gray-300 shadow rounded p-3">
                  <div id="payment-element" class=""></div>
                  <!-- Stripe will create form elements here -->
                  <div id="card-errors" role="alert"></div>
                </div>

                <!-- Temporary Changes -->
                <!-- <div class="grid gap-4 sm:grid-cols-2 sm:gap- mt-6 mb-6">
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
                      minlength="15"
                    />

                    <InputError class="mt-2" :message="$page.props.errors.number" />
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
                      <option selected="" disabled="" value="">Select Month</option>
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
                    <InputError class="mt-2" :message="$page.props.errors.month" />
                  </div>

                  <div class="w-full">
                    <label for="year" class="block mb-2 text-sm font-medium text-gray-700"
                      >Expiration year</label
                    >
                    <select
                      name="year"
                      id="year"
                      class="select-custom"
                      v-model="cardForm.year"
                      required
                    >
                      <option selected="" disabled="" value="">Select Year</option>
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
                    <InputError class="mt-2" :message="$page.props.errors.year" />
                  </div>

                  <div class="w-full">
                    <label for="cvv" class="block mb-2 text-sm font-medium text-gray-700"
                      >CVV</label
                    >
                    <TextInput
                      type="password"
                      name="cvv"
                      id="cvv"
                      class="w-full"
                      placeholder="XXXX"
                      maxlength="4"
                      required
                      pattern="[0-9]{4}"
                      v-model="cardForm.cvv"
                    />
                    <InputError class="mt-2" :message="$page.props.errors.cvv" />
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
                    <InputError class="mt-2" :message="$page.props.errors.street" />
                  </div>
                  <div class="w-full">
                    <label for="city" class="block mb-2 text-sm font-medium text-gray-700"
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
                    <InputError class="mt-2" :message="$page.props.errors.city" />
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
                      <option selected="" disabled="" value="">Select State</option>
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
                      <option class="text-black" value="NH">New Hampshire</option>
                      <option class="text-black" value="NJ">New Jersey</option>
                      <option class="text-black" value="NM">New Mexico</option>
                      <option class="text-black" value="NY">New York</option>
                      <option class="text-black" value="NC">North Carolina</option>
                      <option class="text-black" value="ND">North Dakota</option>
                      <option class="text-black" value="OH">Ohio</option>
                      <option class="text-black" value="OK">Oklahoma</option>
                      <option class="text-black" value="OR">Oregon</option>
                      <option class="text-black" value="PA">Pennsylvania</option>
                      <option class="text-black" value="RI">Rhode Island</option>
                      <option class="text-black" value="SC">South Carolina</option>
                      <option class="text-black" value="SD">South Dakota</option>
                      <option class="text-black" value="TN">Tennessee</option>
                      <option class="text-black" value="TX">Texas</option>
                      <option class="text-black" value="UT">Utah</option>
                      <option class="text-black" value="VT">Vermont</option>
                      <option class="text-black" value="VA">Virginia</option>
                      <option class="text-black" value="WA">Washington</option>
                      <option class="text-black" value="WV">West Virginia</option>
                      <option class="text-black" value="WI">Wisconsin</option>
                      <option class="text-black" value="WY">Wyoming</option>
                    </select>
                    <InputError class="mt-2" :message="$page.props.errors.state" />
                  </div>

                  <div class="w-full">
                    <label for="zip" class="block mb-2 text-sm font-medium text-gray-700"
                      >ZIP code</label
                    >
                    <TextInput
                      type="text"
                      name="zip"
                      id="zip"
                      class="w-full"
                      placeholder="10001"
                      maxlength="6"
                      required
                      v-model="cardForm.zip"
                    />
                    <InputError class="mt-2" :message="$page.props.errors.zip" />
                  </div>
                </div> -->
              </form>

              <div class="mt-4">
                <label for="amount" class="block mb-2 text-sm font-medium text-gray-500"
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
                    class="rounded-none rounded-r-lg border focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 border-gray-400 placeholder-gray-400"
                  />
                  <InputError class="mt-2" :message="$page.props.errors.amount" />
                </div>
              </div>

              <div class="grid grid-cols-2 gap-6">
                <div class="text-gray-700 text-xs mt-4">
                  <p class="mb-2">Charge will include card processing fee of 3%</p>
                  <p>
                    By clicking the "Add Funds" button below I authorize AllCalls LLC to
                    charge my card and agree to be billed for ${{
                      cardForm.amount !== "" ? cardForm.amount : 0
                    }}. This is a one-time purchase. Funds will be added to your account
                    immediately. Your credit card will be billed as "AllCalls.io" on your
                    billing statement.
                  </p>
                </div>
                <div>
                  <div class="space-y-2 text-gray-700 text-sm mt-4">
                    <div class="flex justify-between">
                      <span class="whitespace-nowrap text-gray-800 font-bold"
                        >Sub Total:</span
                      >
                      <span v-if="cardForm.amount">${{ cardForm.amount }}</span>
                    </div>

                    <div class="flex justify-between">
                      <span class="whitespace-nowrap text-gray-800 font-bold"
                        >Credit Card Processing Fee:</span
                      >
                      <span>${{ creditCardFee }}</span>
                    </div>

                    <hr class="mb-8" />

                    <div class="flex justify-between">
                      <span class="whitespace-nowrap text-gray-800 font-bold"
                        >Total:</span
                      >
                      <span>${{ total }}</span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="flex justify-end mt-6">
                <AuthenticatedButton type="button" class="mt-3" @click.prevent="addFunds">
                  <global-spinner :spinner="isLoading" />  Add funds
                </AuthenticatedButton>
              </div>
            </div>
          </section>
        </div>

        <!-- <div v-else class="text-center sm:px-6 lg:px-8">
          <p class="text-gray-300 mt-6 mb-8">
            You haven't added any cards yet.
          </p>
          <AuthenticatedButton class="">
            <Link href="/billing/cards"> Add credit card </Link>
          </AuthenticatedButton>
        </div> -->
      </form>
    </div>
  </AuthenticatedLayout>
</template>
<style>
.payment-element {
  margin: auto;
  margin-top: 3rem;
  width: 25rem;
}

@media screen and (max-width: 992px) {
  .payment-element {
    margin-left: 0rem;
    margin-top: 3rem;
    width: 100%;
  }
}

/* On screens that are 600px or less, set the background color to olive */
@media screen and (max-width: 600px) {
  .payment-element {
    margin-left: 0rem;
    margin-top: 3rem;
    width: 100%;
  }
}
</style>
