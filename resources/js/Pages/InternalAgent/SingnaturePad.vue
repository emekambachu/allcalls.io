<template>
    <div class="container mx-auto p-5 flex justify-between">

        <!-- Left Side (Signature) -->
        <div class="" style="width: 70%;">
            <!-- Signature Box -->
            <div class=" mb-10 ">
                <div>Signature:</div>
                <!-- Signature Pad Component -->

                <!-- Undo Button (if needed) -->
                <VueSignaturePad id="signature" ref="signaturePad" :options="options" />
                <button @click="undo" class=" button-custom mt-2 px-2 py-2 rounded-md">
                    Undo
                </button>
                <p v-if="sigError" class="text-red-500 mt-2">{{ sigError }}</p>
            </div>
        </div>


        <!-- Right Side (Date) -->
        <div class="w-30 " style="margin-top: 73px;">
            <div class="mb-2"><strong>Date:</strong> <span class="mx-2">{{ dateFormat(date) }}</span></div>
            <!-- Underscore -->
            <div style="width: 200px;" class="border-b border-black "></div>
        </div>

    </div>
    <div class="flex mb-5 justify-between">
        <button @click="editContract()"
        class="button-custom-back px-3 py-2 rounded-md">
           Edit
        </button>
        <button type="button" :class="{ 'opacity-25': isLoading }" :disabled="isLoading" @click="save"
            class="button-custom  px-3 py-2 rounded-md">
            <global-spinner :spinner="isLoading" /> Submit
        </button>

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
    },
    methods: {
        undo() {
            this.$refs.signaturePad.undoSignature();
        },

        changeFun(val) {
            console.log('what is change', val);
        },
        dateFormat(date) {
            const day = date.getDate().toString().padStart(2, "0"); // Add leading zero if needed
            const month = (date.getMonth() + 1).toString().padStart(2, "0"); // Month is zero-based, so add 1
            const year = date.getFullYear();

            // Create the formatted date string
            return `${day}/${month}/${year}`;
        },
        editContract(){
            this.$emit("editContract");
        },
        async save() {
            const { isEmpty, data } = this.$refs.signaturePad.saveSignature();
            this.sigError = ''

            if (!isEmpty) {
                this.$emit("signature", data);
                // axios
                //     .post("/internal-agent/registration-signature", {
                //         signatue:data
                //     }, {
                //         headers: {
                //             'Content-Type': 'multipart/form-data' // Set the content type to multipart/form-data
                //         }
                //     }).then((response) => {
                //         console.log('response', response);
                //         this.isLoading = false
                //     })
                //     .catch((error) => {
                //         this.isLoading = false
                //         if (error.response) {
                //             if (error.response.status === 400) {
                //                console.log('erros', error.response);
                //             } else {
                //                 console.log("Other errors", error.response.data);
                //             }
                //         } else if (error.request) {
                //             console.log("No response received", error.request);
                //         } else {
                //             console.log("Error", error.message);
                //         }
                //     });
            } else {
                this.sigError = 'Please provide a signature.';
            }


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
    height: 60px !important;
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
  