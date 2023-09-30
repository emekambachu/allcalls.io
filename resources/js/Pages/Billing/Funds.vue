<script setup>
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { ref, onMounted, computed } from 'vue';
import InputError from "@/Components/InputError.vue";
import { toaster } from '@/helper.js';

let { cards } = defineProps({
    cards: {
        type: Array,
        required: true,
    }
});
console.log(cards);

let page = usePage();

let amount = ref('50');
let name = ref('');
let number = ref('');
let expiry = ref('');
let cvv = ref('');
let year = ref('');
let month = ref('');

let address = ref('');
let city = ref('');
let state = ref('');
let zip = ref('');
let card = ref('0');


let isLoading = ref(false);


onMounted(() => {
    // make option for year dropdown
    var i, currentYear, startYear, endYear, newOption, dropdownYear;
    dropdownYear = document.getElementById("year");
    currentYear = (new Date()).getFullYear();
    startYear = currentYear - 0;
    endYear = currentYear + 10;

    for (i=startYear;i<=endYear;i++) {
      newOption = document.createElement("option");
      newOption.value = i;
      newOption.label = i;
      dropdownYear.appendChild(newOption);
    }
    showSuccessNotificationIfAvailable();
    selectDefaultCardIfAvailable();
});

let selectDefaultCardIfAvailable = () => {
    // Search for the default = 1 card:
    for (let i = 0; i < cards.length; i++) {
        if (cards[i].default === 1) {
            card.value = cards[i].id
        }
    }
}

let showSuccessNotificationIfAvailable = () => {
    if (page.props.flash.message) {
        toaster('success', page.props.flash.message)
    }

    if (page.props.flash.bonus) {
        toaster('success', page.props.flash.bonus)
    }
};

let addFunds = async () => {
    isLoading.value = true;

    // Extract month and year from the expiry.value
    // let [monthFromExpiry, yearFromExpiry] = expiry.value.split(' / ');
    let monthFromExpiry = month.value;
    let yearFromExpiry = year.value;

    let requestData;


    // If 'card' has a value of '0'
    if (card.value === '0') {
        requestData = {
            amount: amount.value,
            number: Number(number.value.trim().replace(/\s/g, '')),
            month: monthFromExpiry,
            year: yearFromExpiry,
            cvv: cvv.value,
            address: address.value,
            city: city.value,
            state: state.value,
            zip: zip.value,
        };
    } else {
        // Search for the card object using the id
        const selectedCard = cards.find(c => c.id === Number(card.value));

        if (selectedCard) {
            requestData = {
                amount: amount.value,
                cardId: selectedCard.id
            };
        } else {
            // Handle the error if the selected card is not found
            toaster('error', 'Selected card not found');
            isLoading.value = false;
            return;
        }
    }

    router.visit('/billing/funds', {
        method: 'POST',
        data: requestData,
    });

    return;
};



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
            <div class="mx-auto max-w-7xl">
                <div>
                    <section class="mx-auto sm:px-6 lg:px-8 space-y-6">
                        <div class="p-4 sm:p-8 sm:rounded-lg" style="padding-top: 0">

                            <div class="my-12">
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

                                <div class="mb-8">
                                    <label for="cards" class="block mb-2 text-sm font-medium text-gray-500">Card</label>
                                    <select class="select-custom" v-model="card">
                                        <option value="0">Add a new card</option>
                                        <option v-for="currentCard in cards" :key="currentCard.id" :value="currentCard.id"
                                            v-text="`${currentCard.type} ending in ${currentCard.last4}`"></option>
                                    </select>
                                </div>

                                <div v-if="card === '0'">


                                    <div class="mb-4">
                                        <label for="name" class="block mb-2 text-sm font-medium text-gray-500">Full
                                            Name</label>
                                        <TextInput v-model="name" id="name" placeholder="John Doe" />
                                        <InputError class="mt-2" :message="$page.props.errors.name" />
                                    </div>

                                    
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label for="address"
                                                class="block mb-2 text-sm font-medium text-gray-500">Card Number</label>
                                            <TextInput
                                                        class="text-sm border-transparent focus:border-transparent focus:ring-0 bg-transparent"
                                                        type="text" placeholder="4242 4242 4242 4242" name="cardNumber"
                                                        v-cardformat:formatCardNumber required
                                                        v-model="number" />    
                                            <InputError class="mt-2" :message="$page.props.errors.number" />
                                        </div>

                                        <div>
                                            <label for="month"
                                                class="block mb-2 text-sm font-medium text-gray-700">Select Expiration Month</label>
                                            <select v-model="month" name="month" id="month" class="select-custom" required>
                                                <option selected="" disabled="" value="">Select Month</option>
                                                <option value="01">01</option>
                                                <option value="02">02</option>
                                                <option value="03">03</option>
                                                <option value="04">04</option>
                                                <option value="05">05</option>
                                                <option value="06">06</option>
                                                <option value="07">07</option>
                                                <option value="08">08</option>
                                                <option value="09">09</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                            </select>
                                            <InputError class="mt-2" :message="$page.props.errors.month" />
                                        </div>
                                        <div>
                                            <label for="year"
                                                class="block mb-2 text-sm font-medium text-gray-700">Select Expiration Year</label>
                                            <select v-model="year" id='year' name="year"  class="select-custom" required>
                                                <option selected="" disabled="" value="">Select Year</option>
                                            </select>
                                            <InputError class="mt-2" :message="$page.props.errors.year" />
                                        </div>

                                        <div>
                                            <label for="zip" class="block mb-2 text-sm font-medium text-gray-500">CVV</label>
                                            <TextInput type="text" placeholder="0000" name="CVV" required pattern="\d{3,4}"
                                                        v-model="cvv" maxlength="4" v-cardformat:formatCardCVC />
                                            <InputError class="mt-2" :message="$page.props.errors.cvv" />
                                        </div>
                                    </div>


                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label for="address"
                                                class="block mb-2 text-sm font-medium text-gray-500">Address</label>
                                            <TextInput v-model="address" id="address" placeholder="123 Elm St, Apt 4"
                                                required />
                                            <InputError class="mt-2" :message="$page.props.errors.address" />
                                        </div>

                                        <div>
                                            <label for="city"
                                                class="block mb-2 text-sm font-medium text-gray-500">City</label>
                                            <TextInput v-model="city" id="city" placeholder="New York" required />
                                            <InputError class="mt-2" :message="$page.props.errors.city" />
                                        </div>

                                        <div>
                                            <label for="state"
                                                class="block mb-2 text-sm font-medium text-gray-700">State</label>
                                            <select v-model="state" name="state" id="state" class="select-custom" required>
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

                                        <div>
                                            <label for="zip" class="block mb-2 text-sm font-medium text-gray-500">ZIP
                                                Code</label>
                                            <TextInput v-model="zip" id="zip" placeholder="10001" required pattern="\d{5,6}"
                                                maxlength="6" />
                                            <InputError class="mt-2" :message="$page.props.errors.zip" />
                                        </div>
                                    </div>
                                </div>

                            </div>



                            <div class="mt-4">
                                <label for="amount" class="block mb-2 text-sm font-medium text-gray-500">Amount</label>
                                <div class="flex mt-2">
                                    <span
                                        class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md">
                                        $
                                    </span>

                                    <TextInput type="number" id="amount" placeholder="500" v-model="amount" step="1" min="0"
                                        class="rounded-none rounded-r-lg border focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 border-gray-400 placeholder-gray-400" />

                                </div>

                                <InputError class="mt-2" :message="$page.props.errors.amount" />
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
                                <PrimaryButton type="button" class="mt-3 flex items-center" @click.prevent="addFunds"
                                    :disabled="isLoading">
                                    <GlobalSpinner :spinner="isLoading" class="mr-2" /> Add funds
                                </PrimaryButton>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
