<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed } from "vue";
import GuestInputLabel from "@/Components/GuestInputLabel.vue";
import Multiselect from "@vueform/multiselect";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import { router } from "@inertiajs/vue3"
import { Head, Link, useForm } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
let props = defineProps({
    StepsModal: {
    type: Boolean,
  },
  
  callTypes: Array,
    states: Array,
});
let stateOptions = computed(() => {
    return props.states.map((state) => {
        return {
            value: state.id,
            label: state.name,
        };
    });
});
let form = useForm({
  first_name: "",
  last_name: "",
  email: "",
  password: "",
  phone: "",
  consent:  false,
  typesWithStates: props.callTypes.reduce((acc, obj) => {
        acc[obj.id] = [];
        return acc;
    }, {}),
    bids: props.callTypes.map((type) => {
        return { call_type_id: type.id, amount: 20 };
    }),
});


let step = ref(0);
let selectedTypes = ref([]);
let emit = defineEmits(["close"]);
let close = () => {

  emit("close");
  
};
let termsAndConditons = ref(true)
let onTypeUpdate = (event) => {
    if (event.target.checked) {
        selectedTypes.value.push(Number(event.target.value));
    } else {
        selectedTypes.value.splice(
            selectedTypes.value.indexOf(Number(event.target.value)),
            1
        );
        delete form.typesWithStates[Number(event.target.value)];
    }
};
let TermAndConditons = (event) => {
    if (event.target.checked) {
        termsAndConditons.value = false;
    } else {
        termsAndConditons.value = true;
    }
    
}
let NextStep = () => {
    step.value += 1;
}
let goBack = () => {
    step.value -= 1;
};

const areAllArraysEmpty = computed(() => {
    const arrays = Object.values(form.typesWithStates); // Get all arrays
    return arrays.every(array => array.length === 0); // Check if all arrays are empty
});
const consentError = ref(false)
const consentErro = ref('')
let submit = () => {
    
    return axios
        .post("/store-registration-steps", form)
        .then((response) => {
            consentError.value = false
            close()
            router.visit('/dashboard')
        })
        .catch((error) => {
            if (error.response) {
                firstStepErrors.value = error.response.data.errors;
                consentErro.value = error.response.data.consent;
                consentError.value = true
                if (error.response.status === 400) {
                    firstStepErrors.value = error.response.data.errors;
                    consentErro.value = error.response.data.consent;
                    consentError.value = true
                } else {
                    console.log("Other errors", error.response.data);
                }
            } else if (error.request) {
                console.log("No response received", error.request);
            } else {
                console.log("Error", error.message);
            }
        });
}
</script>
<style scoped>
.active\:bg-gray-900:active{
    color: white;
}
.hover\:drop-shadow-2xl:hover{
    background-color: white;
    color: black;
}


.button-custom-back {
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    font-weight: 600;
    border-width: 1px;
    align-items: center;
    display: inline-flex;
    border-color: rgb(107 114 128 / var(--tw-border-opacity));
}
.button-custom-back:hover{
    background-color: #03243d;
    color: #3cfa7a;
    transition-duration: 150ms;
}
</style>
<template>
  <Transition
    name="modal"
    enter-active-class="transition ease-out duration-300 transform"
    enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    enter-to-class="opacity-100 translate-y-0 sm:scale-100"
    leave-active-class="transition ease-in duration-200 transform"
    leave-from-class="opacity-100 translate-y-0 sm:scale-100"
    leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
  >
    <div
      id="defaultModal"
      v-if="StepsModal"
      tabindex="-1"
      class="flex items-center justify-center fixed inset-0 z-50 w-full h-full overflow-x-hidden overflow-y-auto max-h-full mx-4 sm:mx-0"
    >
      <div class="fixed inset-0 bg-black opacity-60"></div>
      <!-- This is the overlay -->
      <div
        
        class="relative w-full max-w-5xl max-h-full mx-auto"
      >
        <div class="relative bg-white rounded-lg shadow-lg">
          <div
            class="flex items-start justify-between p-4 border-b rounded-t border-gray-600"
          >
            <h3 class="text-xl font-semibold text-custom-blue">
              Steps 
            </h3>
          </div>
          <div>
            <div v-if="step === 0" class="pt-6 ">
                <div   class="flex p-12 box-shadow">
                    <input id="checked-checkbox" type="checkbox" v-model="form.consent"
                    @change="TermAndConditons" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                    <label for="checked-checkbox" class="ml-2 text-xs font-medium text-gray-900 dark:text-gray-400">
                        By clicking Continue, I agree to email marketing, the Terms and
                        Conditions (which include mandatory arbitration), Privacy Policy,
                        and site visit recordation by Trusted Form and Jornaya. I provide my
                        express written consent and electronic signature to receive
                        monitored or recorded phone sales calls and text messages from
                        AllCalls.io regarding products and services including Medicare
                        Supplement, Medicare Advantage, and Prescription Drug Plans on the
                        landline or mobile number I provided even if I am on a federal or
                        State do not call registry. I confirm that the phone number set
                        forth above is accurate and I am the regular user of the phone. I
                        understand these calls may be generated using an automated dialing
                        system and may contain pre-recorded and artificial voice messages
                        and that consenting is not required to receive a quote or speak with
                        an agent and that I can revoke my consent at any time by any
                        reasonable means. To receive a quote without providing consent,
                        please call (866) 523-1718. For SMS message campaigns: Text STOP to
                        stop and HELP for help. Msg and data rates may apply. Periodic
                        messages; max. 30/month.
                    </label>
                </div>
            </div>
                <div v-if="step === 1" class="pt-6 ">
                    <div  class="p-12 box-shadow">
                        <p class="text-gray-700 text-lg text-left leading-relaxed">
                            Our dynamic bidding system allows you to set a maximum bid for
                            each type of call you're interested in as you configure your
                            account.
                        </p>
                    </div>
                </div>
                <div v-if="step === 2" class="pt-6 ">
                    <div  class="p-12 box-shadow">
                        <p class="text-gray-700 text-lg text-left leading-relaxed">
                            Each call type has a base bid of $20, but you can bid higher to
                            increase your chances of securing the call. The user with the
                            highest bid wins the call but pays only $1 more than the second
                            highest bid.
                        </p>
                    </div>
                </div>
                <div v-if="step === 3" class="pt-6 ">
                    <div  class="p-12 box-shadow ">
                        <p class="text-gray-700 text-lg text-left leading-relaxed">
                            To start, select your desired call types and indicate your
                            maximum bid for each. Don't forget to select the states where
                            you're licensed to operate. Remember, banks will see your bid
                            amounts as they set the prices they allow for payment
                            processing, so bid wisely!
                        </p>
                    </div>
                </div>

                <div v-if="step === 4" class="pt-6 ">
                    <div  class="px-20 box-shadow">
                        <div class="mt-4">
                            <GuestInputLabel class="mb-3" for="insurance_type"
                                value="What types of calls do you want to receive?" />

                            <div v-for="(callType, index) in callTypes" :key="callType.id" class="mb-4">
                                <input :id="`insurance_type_${callType.id}`" type="checkbox"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                    :checked="selectedTypes.includes(callType.id)"
                                    @change="onTypeUpdate" :value="callType.id" />

                                <label class="ml-2 text-md font-medium text-custom-indigo" :for="`insurance_type_${callType.id}`"
                                    v-text="callType.type"></label>

                                <div v-if="selectedTypes.includes(callType.id)" class="pt-2 mb-8">
                                    <div>
                                        <label class="ml-2 text-xs font-medium">Your Bid</label>
                                        <div class="relative mb-2">
                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                                                $
                                            </div>
                                            <input type="number" min="20"
                                                class="bg-custom-gray border-none focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm pl-8 w-full"
                                                placeholder="20.00" v-model="form.bids[index].amount" />
                                        </div>
                                    </div>
                                    <label class="ml-2 text-xs font-medium">States you're licensed in:</label>
                                    <Multiselect :options="stateOptions" v-model="form.typesWithStates[callType.id]"
                                        track-by="value" label="label" mode="tags" :close-on-select="false"
                                        placeholder="Select a state">
                                    </Multiselect>
                                </div>
                            </div>

                            <InputError class="mt-2" :message="form.errors.insurance_type" />
                        <div v-if="firstStepErrors">
                                <div
                                    v-if="firstStepErrors.typesWithStates"
                                    class="text-red-500"
                                    v-text="firstStepErrors.typesWithStates[0]"
                                ></div>
                        </div>
                        
                        </div>
                    </div>
                    <p class="p-5" v-if="consentError == true" style="color: red;">{{ consentErro }}</p>
                </div>
                
        
          <div class="p-6">
            <div class="flex justify-between">
                <div  class="mt-4">
                            <a v-if="step != 0" href="#" @click.prevent="goBack"
                            class="button-custom-back px-3 py-2 rounded-md ">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-4 h-4 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                            </svg>

                            Back</a>
                    </div>
                <PrimaryButton  v-if="step != 4"  type="button" @click="NextStep()"  :class="{ 'opacity-25':   termsAndConditons }" :disabled="termsAndConditons">Next</PrimaryButton>
                <PrimaryButton @click="submit" v-if="step === 4"  type="button"   :class="{ 'opacity-25': areAllArraysEmpty }" :disabled="areAllArraysEmpty">Register</PrimaryButton>
            </div>
          </div>
        </div>
        </div>
      </div>
    </div>
  </Transition>
</template>

