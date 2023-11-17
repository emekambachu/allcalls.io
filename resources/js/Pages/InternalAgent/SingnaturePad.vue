<template>
    <br>
    <div class="flex mb-5 justify-between">
        <button @click="editContract()" class="button-custom-back px-3 py-2 rounded-md">
            Edit
        </button>
        <a @click="isLoading = true" 
            :href="route('internal.agent.docusign.sign', 'contract')" type="button"
            :class="{ 'opacity-25':  isLoading }" :disabled=" isLoading"
            class="button-custom  px-3 py-2 rounded-md">
            <global-spinner :spinner="isLoading" /> Sign Document
        </a>
        <!-- <button v-else :class="{ 'opacity-25':isLoading2 }" :disabled="isLoading2"  @click="ReloadPage" class="button-custom  px-3 py-2 rounded-md"><global-spinner :spinner="isLoading2" />Rload</button> -->

    </div>
</template>
  
<script>
export default {
    name: "App",
    data: () => ({
        options: {
            penColor: "black",
        },
        date: new Date(),
        sigError: '',
        isLoading2:false,
    }),
    props: {
        isLoading: Boolean,
        userData: Object,
        page: Object,
        firstStepErrors: Object,
        docuSignAuthCode: String,
    },
    methods: {
        ReloadPage(){
            this.isLoading2 = true
            location.reload();
        },
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
  