<template>
    <h1 style="background-color: #134576;" class="mb-4	text-center rounded-md py-2 text-white">
        Accompanying Documents
    </h1>
    <p class="px-4 py-2">
        <strong>I attest that the information I have provided is true to the best of my knowledge. I acknowledge that if
            any information changes, I will notify my agency office within 5 days of such change. Further, I
            understand that my agency may contact me when I need to answer carrier-specific questions.</strong>
    </p>
    <hr class="w-100 h-1 my-4 bg-gray-600 border-0 rounded dark:bg-gray-700">
    <p>By signing below, l hereby authorize the Company to initiate credit entries and, if
        necessary, adjustments for credit entries in error to the checking and/or savings account
        indicated on this form. This authority is to remain in full effect until the Company has
        received written notice from me for its termination. I understand that this authorization
        is subject to the terms of any agent or representative contract, commission agreement,
        or loan agreement that I may have now, or in the future, with the Company.</p>
    <br>

    <div class="w-full  flex justify-between p-4">
        <div class="w-3/5 pr-4 flex">
            <div>Signature:</div>
            <div class="border-b border-gray-400 w-full"></div>
        </div>
        <div class="w-2/5 pl-4 flex">
            <div>Date:</div>
            <div class="border-b border-gray-400 w-full"><span class="ml-5">{{ dateFormat(date) }}</span></div>
        </div>
    </div>
    <br>
    <!-- <hr class="w-100 h-1 my-4 bg-gray-600 border-0 rounded dark:bg-gray-700"> -->
    <h1 style="background-color: #134576;" class="mb-4	text-center rounded-md py-2 text-white">
        Signature Authorization
    </h1>
    <p class="p-4 ">
    <div class="mb-2"> <strong>PLEASE READ THIS AUTHORIZATION, SIGN IN THE BOX BELOW AND SUBMIT THIS
        FORM BY FOLLOWING THE INSTRUCTIONS PROVIDED ON THE COVER PAGE.</strong> </div>
    <hr class="w-100 h-1 my-4 bg-gray-600 border-0 rounded dark:bg-gray-700">
    I <span class="mx-2" style="border-bottom: 2px solid black;"><strong> {{ userData.first_name }} {{ userData.last_name }}
        </strong>, </span> hereby authorize AllCalls.io, LLC and its general agency customers (the "Authorized Parties")
    to affix or append a copy of my signature, as set forth below, to any and all required signature fields on forms
    and agreements of any insurance carrier (a "Carrier") designated by me through the SureLC software or
    through any other means, including without limitation, by e-mail or orally. The Authorized Parties shall be
    permitted to complete and submit all such forms and agreements on my behalf for the purpose of becoming
    authorized to sell Carrier insurance products. I hereby release, indemnify and hold harmless the Authorized
    Parties against any and all claims, demands, losses, damages, and causes of action, including expenses, costs
    and reasonable attorneys' fees which they may sustain or incur as a result of carrying out the authority
    granted hereunder.
    <div class="my-2 mb-5">
        By my signature below, I certify that the information I have submitted to the Authorized Parties is correct to
        the best of my knowledge and acknowledge that I have read and reviewed the forms and agreements which
        the Authorized Parties have been authorized to affix my signature. I agree to indemnify and hold any third
        party harmless from and against any and all claims, demands, losses, damages, and causes of action,
        including expenses, costs and reasonable attorneys' fees which such third party may incur as a result of its
        reliance on any form or agreement bearing my signature pursuant to this authorization.
    </div>
    </p>

    <!-- <div class="container mx-auto p-5 flex justify-between flex-wrap">
        <div class="" style="width: 70%;">
            <div class=" mb-10 ">
                <div>Signature:</div>

                <VueSignaturePad id="signature" ref="signaturePad" :options="options" />
                <button @click="undo" class=" button-custom mt-2 px-2 py-2 rounded-md">
                    Undo
                </button>
                <a v-if="docuSignAuthCode" :href="route('internal.agent.docusign.sign', 'signature_authorization')">Sign
                    with DocuSign</a>
                <div v-if="firstStepErrors.signature_authorization" class="text-red-500 mt-4"
                    v-text="firstStepErrors.signature_authorization[0]">
                </div>
                <p v-if="sigError" class="text-red-500 mt-2">{{ sigError }}</p>
            </div>
        </div>


        <div class="w-30 " style="margin-top: 132px;">
            <div class="mb-2"><strong>Date:</strong> <span class="mx-2">{{ dateFormat(date) }}</span></div>
            <div style="width: 200px;" class="border-b border-black "></div>
        </div>
    </div> -->
    <div class="w-full  flex justify-between p-4">
        <div class="w-3/5 pr-4 flex">
            <div>Signature:</div>
            <div class="border-b border-gray-400 w-full"></div>
        </div>
        <div class="w-2/5 pl-4 flex">
            <div>Date:</div>
            <div class="border-b border-gray-400 w-full"><span class="ml-5">{{ dateFormat(date) }}</span></div>
        </div>
    </div>
    <br>
    <h1 style="background-color: #134576;" class="mb-4	text-center rounded-md py-2 text-white">
        Contracting - Debit-Check Agent/Agency Authorization Form
    </h1>
    <p>
        <strong>I attest that the information I have provided is true to the best of my knowledge. I acknowledge that if any
            information changes, I will notify my agency office within 5 days of such change. Further, I understand that my
            agency may contact me when I need to answer carrier-specific questions.</strong>
    </p>
    <hr class="w-100 h-1 my-4 bg-gray-600 border-0 rounded dark:bg-gray-700">

    <p>
        By signing below, l hereby authorize the Company to initiate credit entries and, if necessary,
        adjustments for credit entries in error to the checking and/or savings account indicated on this
        form. This authority is to remain in full effect until the Company has received written notice from
        me for its termination. I understand that this authorization is subject to the terms of any agent or
        representative contract, commission agreement, or loan agreement that I may have now, or in the
        future, with the Company.</p>
    <div class="w-full  flex justify-between p-4">
        <div class="w-3/5 pr-4 flex">
            <div>Signature:</div>
            <div class="border-b border-gray-400 w-full"></div>
        </div>
        <div class="w-2/5 pl-4 flex">
            <div>Date:</div>
            <div class="border-b border-gray-400 w-full"><span class="ml-5">{{ dateFormat(date) }}</span></div>
        </div>
    </div>
    <br>
    <div class="flex mb-5 justify-between">
        <button @click="editContract()" class="button-custom-back px-3 py-2 rounded-md">
            Edit
        </button>
        <button v-if="!signatureAuthorizationSaved" type="button" :class="{ 'opacity-25': isLoading }" :disabled="isLoading"
            @click="save" class="button-custom  px-3 py-2 rounded-md">
            <global-spinner :spinner="isLoading" /> Prepare Document
        </button>
        <a @click="isLoading = true" v-if="docuSignAuthCode && signatureAuthorizationSaved"
            :href="route('internal.agent.docusign.sign', 'contract')" type="button"
            :class="{ 'opacity-25': !docuSignAuthCode }" :disabled="!docuSignAuthCode"
            class="button-custom  px-3 py-2 rounded-md">
            <global-spinner :spinner="isLoading" /> Sign Document
        </a>
        <div v-if="signatureAuthorizationMessage" class="text-green-500 mt-3 text-center">
            {{ signatureAuthorizationMessage }}
        </div>
    </div>
</template>
  
<script>
import axios from 'axios';
export default {
    name: "App",
    data: () => ({
        options: {
            penColor: "black",
        },
        date: new Date(),
        sigError: '',
    }),
    props: {
        isLoading: Boolean,
        userData: Object,
        page: Object,
        firstStepErrors: Object,
        docuSignAuthCode: String,
        signatureAuthorizationMessage: String,
        signatureAuthorizationSaved: Boolean,
    },
    mounted() {
        // if (this.page.auth.role === 'internal-agent') {
        //     // console.log('i am running ');
        //     const canvasElement = this.$refs.signaturePad.$el.querySelector('canvas');
        //     canvasElement.width = 670; // Set the width you desire
        //     canvasElement.height = 100; // Set the height you desire
        // }
    },
    methods: {
        // undo() {
        //     this.$refs.signaturePad.undoSignature();
        // },
        dateFormat(date) {
            const day = date.getDate().toString().padStart(2, "0"); // Add leading zero if needed
            const month = (date.getMonth() + 1).toString().padStart(2, "0"); // Month is zero-based, so add 1
            const year = date.getFullYear();
            // Create the formatted date string
            return `${day}/${month}/${year}`;
        },
        editContract() {
            this.$emit("editContract");
        },
        async save() {
            this.$emit("signature");
            // const { isEmpty, data } = this.$refs.signaturePad.saveSignature();
            // this.sigError = ''
            // if (!isEmpty) {
            //     this.$emit("signature", data);
            // } else {
            //     this.sigError = 'Please provide a signature.';
            // }
        },

    },
};
</script>
  
<style scoped>
#signature {
    border: solid 1px lightgray;
    padding: 10px;
    border-radius: 10px;

}

#signature canvas {
    /* height: 60px !important; */
}

.container {
    width: "100%";

}

.buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
    margin-top: 8px;
}
</style>
  