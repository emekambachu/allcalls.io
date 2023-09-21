<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
let maxDate = ref(new Date)
let addres_history = ref([
    {
        address: 'history_address1',
    },
    {
        address: 'history_address2',
    },
    {
        address: 'history_address3',
    },
    {
        address: 'history_address4',
    },
    {
        address: 'history_address5',
    },
    {
        address: 'history_address6',
    },

    {
        address: 'history_address7',
    },
])
let form = useForm({
    history_address1: {},
    history_address2: {},
    history_address3: {},
    history_address4: {},
    history_address5: {},
    history_address6: {},
    history_address7: {},
})
const emits = defineEmits();
watch(form, (newForm, oldForm) => {
    emits("addRessHistory", newForm);
});
</script>
<style scoped>
.input-custom {
    background-color: #d3e4eb;
    border: solid 2px #a7d4ea;
    height: 35px;
}

.small-input-custom {
    width: 35px;
    margin-right: 5px;
}

.flex-container {
    display: flex;
}

.flex-item {
    text-align: center;
    padding: 10px;
    border: 1px solid #000;
}
</style>
<template>
    <div v-for="(history, index) in addres_history" :key="history.id">

        <div class="grid lg:grid-cols-3 mb-2  md:grid-cols-2 sm:grid-cols-1 gap-4">
            <div>
                <label for="last_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Home Address</label>
                <div>
                    <input type="text" v-model="form[history.address].address" id="default-input"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <span style="font-size: 14px;">Include Apt/Unit #</span>
                </div>
            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">City,
                    State</label>
                <input type="text" v-model="form[history.address].city" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-black text-gray-900 dark:text-white">Zip Code</label>
                <input type="text" v-model="form[history.address].zip_code" id="default-input"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>
        </div>

        <div class="grid lg:grid-cols-3 mb-2 md:grid-cols-2 sm:grid-cols-1 mt-3 gap-4">
            <div>
                <label for="middle_name" class="block mb-2   text-sm font-black text-gray-900 dark:text-white">Move-In
                    Date</label>
                <VueDatePicker v-model="form[history.address].move_in_date" format="dd-MMM-yyyy" :maxDate="maxDate">
                </VueDatePicker>
            </div>
            <div>
                <label for="middle_name" class="block mb-2   text-sm font-black text-gray-900 dark:text-white">Move-Out
                    Date</label>
                <VueDatePicker v-model="form[history.address].move_out_date" format="dd-MMM-yyyy" :maxDate="maxDate">
                </VueDatePicker>
            </div>
        </div>
        <hr class="w-100 h-1 my-4 bg-gray-600 border-0 rounded dark:bg-gray-700">
    </div>
</template>