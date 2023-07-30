<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { reactive } from "vue";
import { createToaster } from "@meforma/vue-toaster";
import BillingNav from '@/Components/BillingNav.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedButton from '@/Components/AuthenticatedButton.vue';

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
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Billing
      </h2>
    </template>

    <div class="sm:py-10">
      <div class="mx-auto max-w-7xl">
        <div class="sm:pl-10 max-w-6xl">
          <BillingNav></BillingNav>
        </div>

        <div>
          <section class=" mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 sm:rounded-lg">
              <!-- <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16"> -->
              <!-- <hr> -->
              <h2 id="headline" class="text-4xl text-custom-sky font-bold mb-6">
                Add a payment method
              </h2>
              <hr class="mb-8" />

              <form @submit.prevent="addNewCard()" class="mb-12">
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6 mb-6">
                  <div class="sm:col-span-2">
                    <label for="number" class="block mb-2 text-sm font-medium text-gray-300 ">Card
                      number</label>
                    <TextInput type="text" name="number" id="number" class="w-full" placeholder="XXXXXXXXXXXXXXXX"
                      required v-model="cardForm.number" maxlength="16" minlength="16" />
                  </div>
                  <div class="w-full">
                    <label for="month" class="block mb-2 text-sm font-medium text-gray-300 ">Expiration
                      month</label>
                    <TextInput type="number" name="month" id="month" class="w-full" placeholder="MM" required min="1"
                      max="12" v-model="cardForm.month" />
                  </div>
                  <div class="w-full">
                    <label for="year" class="block mb-2 text-sm font-medium text-gray-300 ">Expiration
                      year</label>
                    <TextInput type="number" name="year" id="year" class="w-full" placeholder="YYYY" required min="2023"
                      max="2030" v-model="cardForm.year" />
                  </div>
                  <div class="w-full">
                    <label for="cvv" class="block mb-2 text-sm font-medium text-gray-300 ">CVV</label>
                    <TextInput type="password" name="cvv" id="cvv" class="w-full" placeholder="XXX" required
                      pattern="[0-9]{3}" v-model="cardForm.cvv" />
                  </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                  <div class="w-full">
                    <label for="street" class="block mb-2 text-sm font-medium text-gray-300 ">Street
                      address</label>
                    <TextInput type="text" name="street" id="street" class="w-full" placeholder="123 Main St" required
                      v-model="cardForm.address" />
                  </div>
                  <div class="w-full">
                    <label for="city" class="block mb-2 text-sm font-medium text-gray-300 ">City</label>
                    <TextInput type="text" name="city" id="city" class="w-full" placeholder="New York" required
                      v-model="cardForm.city" />
                  </div>
                  <div class="w-full">
                    <label for="state" class="block mb-2 text-sm font-medium text-gray-300 ">State</label>
                    <TextInput type="text" name="state" id="state" class="w-full" placeholder="NY" required
                      v-model="cardForm.state" />
                  </div>
                  <div class="w-full">
                    <label for="zip" class="block mb-2 text-sm font-medium text-gray-300 ">ZIP code</label>
                    <TextInput type="text" name="zip" id="zip" class="w-full" placeholder="10001" required
                      v-model="cardForm.zip" />
                  </div>
                </div>

                <AuthenticatedButton id="button" type="submit" class="mt-4">
                  Add credit card
                </AuthenticatedButton>
              </form>

              <div class="text-4xl text-custom-sky font-bold mb-6">Added Payment Methods</div>
              <hr class="mb-8"/>

              <div class="max-w-2xl">
                <div class="flex items-center justify-between">
                  <h2 class="mb-4 text-xl font-bold text-gray-300 ">
                    Your Cards
                  </h2>
                </div>
                <div class="flow-root">
                  <ul role="list" class="divide-y divide-gray-200">
                    <li class="px-8 py-4 sm:px-12 rounded-lg sm:py-8 bg-custom-white mb-4" v-for="card in cards" :key="card.id">
                      <div class="flex items-center space-x-4">
                        <div class="flex-1 min-w-0">
                          <p class="text-sm font-medium text-custom-blue truncate ">
                            Visa ending in {{ card.last4 }}
                          </p>
                          <p class="text-sm text-custom-blue truncate">
                            Expiry date: {{ card.expiryDate }}
                          </p>
                        </div>
                        <div class="inline-flex items-center text-base font-semibold">
                          <button type="button"
                            class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg px-3 py-2 text-xs mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
                            @click.prevent="removeCard(card.id)">
                            Remove Card
                          </button>
                        </div>
                      </div>
                    </li>
                  </ul>

                  <div v-if="!cards.length">

                    <p class="text-gray-300">You haven't added any cards yet.</p>

                  </div>
                </div>
              </div>

            </div>
          </section>
        </div>

        <div>

        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
