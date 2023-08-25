<script setup>
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
<<<<<<< HEAD
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

=======
import { Head, router, usePage } from "@inertiajs/vue3";
import { ref, onMounted, computed } from 'vue';
>>>>>>> 8619a8332ddaa025cf06f98a81057cf872ac1757
import InputError from "@/Components/InputError.vue";
import { createToaster } from "@meforma/vue-toaster";

<<<<<<< HEAD
import { toaster }   from '@/helper.js';
let page = usePage();
if (page.props.flash.message) {
  toaster('success', page.props.flash.message)
}

=======
let page = usePage();
let stripeAPIKey = 'pk_test_51JUMhZF43egAbbxbdvc4FIRiALFxHyYECIknypspzMqjYBQ47Kvt8TBY3g44gfhIQHJLPQT4GMwcqlqN1KwKPsbc00UfQoy1mu';
let stripe = Stripe(stripeAPIKey);
let cardElement = stripe.elements().create('card');
let amount = ref('50');
let name = ref('');
let cardContainer = ref(null);
let isLoading = ref(false);
let toaster = createToaster({
    position: "top-right",
});
>>>>>>> 8619a8332ddaa025cf06f98a81057cf872ac1757

onMounted(() => {
    cardElement.mount(cardContainer.value);
});

<<<<<<< HEAD
=======
if (page.props.flash.message) {
    toaster.success(page.props.flash.message);
}
>>>>>>> 8619a8332ddaa025cf06f98a81057cf872ac1757

let addFunds = async () => {
    isLoading.value = true;

    const { paymentMethod, error } = await stripe.createPaymentMethod(
        'card', cardElement, {
        billing_details: { name: 'John Doe' }
    }
    );

    if (error) {
        // Display "error.message" to the user...
        console.log('Error occured:')
        console.log(error.message);
        isLoading.value = false;
        return;
    }

    // The card has been verified successfully...
    console.log('Card verified.');
    console.log(paymentMethod.id)
    router.visit('/billing/funds', {
        method: 'POST',
        data: {
            amount: amount.value,
            paymentMethodId: paymentMethod.id,
        }
    })
    return;
}

let creditCardFee = computed(() => {
    let fee = Number(amount.value) * 0.03;
    return String(fee.toFixed(2));
});

let total = computed(() => {
    let fee = Number(amount.value) * 0.03;

    return String((Number(amount.value) + fee).toFixed(2));
});
</script>

<template>
    <Head title="Funds" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-300 leading-tight">Billing</h2>
        </template>

        <div class="sm:py-10">
            <div class="mx-8 px-12 py-4 sm:p-8 sm:rounded-lg" style="padding-bottom: 0">
                <h2 id="headline" class="text-4xl text-custom-sky font-small mb-6" style="padding-bottom: 0">
                    Add Funds
                </h2>
                <hr class="mb-8" />
            </div>
            <form class="mx-auto max-w-7xl">
                <div>
                    <section class="mx-auto sm:px-6 lg:px-8 space-y-6">
                        <div class="p-4 sm:p-8 sm:rounded-lg" style="padding-top: 0">

                            <form @submit.prevent="" class="my-12" autocomplete="on">
                                <div class="mb-4 flex justify-center">
                                    <img class="h-8 mr-3 border border-gray-300 shadow rounded" src="/img/visa.svg"
                                        alt="VISA" />
                                    <img class="h-8 mr-3 border border-gray-300 shadow rounded" src="/img/mastercard.svg"
                                        alt="MASTERCARD" />
                                    <img class="h-8 mr-3 border border-gray-300 shadow rounded" src="/img/amex.svg"
                                        alt="AMERICAN EXPRESS" />
                                    <img class="h-8 mr-3 border border-gray-300 shadow rounded" src="/img/discover.svg"
                                        alt="DISCOVER" />
                                </div>

                                <div class="mb-4">
                                    <label for="name" class="block mb-2 text-sm font-medium text-gray-500">Full Name</label>
                                    <TextInput v-model="name" id="name" placeholder="John Doe" />
                                </div>

                                <div class="mb-4">
                                    <label class="block mb-2 text-sm font-medium text-gray-500">Card Details</label>

                                    <div class="p-3 rounded" style="background-color: #E8F0FE;">
                                        <div ref="cardContainer" id="card-element"></div>
                                    </div>
                                </div>


                                <div class="mt-4">
                                    <label for="amount" class="block mb-2 text-sm font-medium text-gray-500">Amount</label>
                                    <div class="flex mt-2">
                                        <span
                                            class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md">
                                            $
                                        </span>

                                        <TextInput type="number" id="amount" placeholder="500" v-model="amount" step="1"
                                            min="0"
                                            class="rounded-none rounded-r-lg border focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 border-gray-400 placeholder-gray-400" />
                                        <InputError class="mt-2" :message="$page.props.errors.amount" />
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-6">
                                    <div class="text-gray-700 text-xs mt-4">
                                        <p class="mb-2">Charge will include card processing fee of 3%</p>
                                        <p>
                                            By clicking the "Add Funds" button below I authorize AllCalls LLC to
                                            charge my card and agree to be billed for ${{
                                                amount !== "" ? amount : 0
                                            }}. This is a one-time purchase. Funds will be added to your account
                                            immediately. Your credit card will be billed as "AllCalls.io" on your
                                            billing statement.
                                        </p>
                                    </div>
                                    <div>
                                        <div class="space-y-2 text-gray-700 text-sm mt-4">
                                            <div class="flex justify-between">
                                                <span class="whitespace-nowrap text-gray-800 font-bold">Sub Total:</span>
                                                <span v-if="amount">${{ amount }}</span>
                                            </div>

                                            <div class="flex justify-between">
                                                <span class="whitespace-nowrap text-gray-800 font-bold">Credit Card
                                                    Processing
                                                    Fee:</span>
                                                <span>${{ creditCardFee }}</span>
                                            </div>

                                            <hr class="mb-8" />

                                            <div class="flex justify-between">
                                                <span class="whitespace-nowrap text-gray-800 font-bold">Total:</span>
                                                <span>${{ total }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-end mt-6">
                                    <PrimaryButton type="button" class="mt-3 flex items-center" @click.prevent="addFunds" :disabled="isLoading">
                                        <GlobalSpinner :spinner="isLoading" class="mr-2" /> Add funds
                                    </PrimaryButton>
                                </div>
                            </form>


                        </div>
                    </section>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
