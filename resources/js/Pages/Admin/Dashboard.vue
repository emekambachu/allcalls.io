<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { Bar } from "vue-chartjs";
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
} from "chart.js";
import { ref, watch, reactive, computed } from "vue";
import axios from "axios";
import { router, usePage } from "@inertiajs/vue3"

// import { endOfMonth, endOfYear, startOfMonth, startOfYear, subMonths } from 'date-fns';
import { endOfMonth, endOfYear, startOfMonth, subDays, startOfYear, subMonths, startOfWeek, endOfWeek, subWeeks, startOfQuarter, endOfQuarter, subQuarters } from 'date-fns';

const presetDates = ref([
  { label: 'Today', value: [new Date(), new Date()] },
  {
    label: 'Today (Slot)',
    value: [new Date(), new Date()],
    slot: 'preset-date-range-button'
  },
  { label: 'This month', value: [startOfMonth(new Date()), endOfMonth(new Date())] },
  {
    label: 'Last month',
    value: [startOfMonth(subMonths(new Date(), 1)), endOfMonth(subMonths(new Date(), 1))],
  },
  { label: 'This year', value: [startOfYear(new Date()), endOfYear(new Date())] },
  {
    label: 'Last 7 Days',
    value: [subDays(new Date(), 6), new Date()],
  },
  {
    label: 'Last 14 Days',
    value: [subDays(new Date(), 13), new Date()],
  },
  {
    label: 'Last 30 Days',
    value: [subDays(new Date(), 29), new Date()],
  },
  {
    label: 'This Week',
    value: [startOfWeek(new Date()), endOfWeek(new Date())],
  },
  {
    label: 'Last Week',
    value: [startOfWeek(subWeeks(new Date(), 1)), endOfWeek(subWeeks(new Date(), 1))],
  },
  {
    label: 'This Quarter',
    value: [startOfQuarter(new Date()), endOfQuarter(new Date())],
  },
  {
    label: 'Last Quarter',
    value: [startOfQuarter(subQuarters(new Date(), 1)), endOfQuarter(subQuarters(new Date(), 1))],
  },
]);

ChartJS.register(
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale
);

const props = defineProps({
  spendData: Array,
  callData: Array,
  totalCalls: Number,
  totalAmountSpent: Number,
  averageCallDuration: Number,
  totalUserCount: Number,
  activeUsersCount: Number
});


let dateRange = ref([])
watch(dateRange, (newVal) => {
  if (newVal) {
    fetechDashboard();
  }
});
let spendChartData = reactive({
  labels: props.spendData.map((item) => item.date),
  datasets: [
    {
      label: "Amount Spent (Last 7 Days)",
      data: props.spendData.map((item) => item.sum),
      backgroundColor: "rgba(75, 192, 192, 0.2)",
      borderColor: "rgba(75, 192, 192, 1)",
      borderWidth: 1,
    },
  ],
});

let callChartData = reactive({
  labels: props.callData.map((item) => item.date),
  datasets: [
    {
      label: "Clients per Day (Last 7 Days)",
      data: props.callData.map((item) => item.count),
      backgroundColor: "rgba(153, 102, 255, 0.2)",
      borderColor: "rgba(153, 102, 255, 1)",
      borderWidth: 1,
    },
  ],
});

let chartOptions = reactive({
  responsive: true,
  scales: {
    y: {
      beginAtZero: true,
    },
  },
});

let spendChartOptions = reactive({
  responsive: true,
  scales: {
    y: {
      beginAtZero: true,
      ticks: {
        // Include a dollar sign and commas in the ticks
        callback: function (value) {
          return "$" + value.toLocaleString();
        },
      },
    },
  },
});

let formatTime = (duration) => {
  const minutes = Math.floor(duration / 60);
  const seconds = Math.floor(duration % 60);
  return `${minutes.toString().padStart(2, "0")}:${seconds
    .toString()
    .padStart(2, "0")}`;
};

let formatMoney = (amount) => {
  return parseFloat(amount)
    .toFixed(2)
    .replace(/\d(?=(\d{3})+\.)/g, "$&,");
};
let locale = ref({
  lang: 'fr', // or 'en', 'es', 'de',
  weekDays: ['L', 'M', 'M', 'J', 'V', 'S', 'D'], // you can surcharge weekDays too
})

let fetechDashboard = async (val) => {
  try {

    const queryParams = {
      from: dateRange.value[0],
      to: dateRange.value[1],
    };
   const response =  await axios.get('/admin/dashboard', {
      params: queryParams,
    });
    console.log(response);
  } catch (error) {
    console.log(error);
  }
}
</script>

<template>
  <Head title="Dashboard" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Dashboard
      </h2>
    </template>

    <div class="pt-14">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="px-4 sm:px-8 sm:rounded-lg">
          <div class="text-4xl text-custom-sky font-bold mb-6">Dashboard </div>
          <hr class="mb-4" />
        </div>
      </div>
    </div>


    <div class="px-16">
      <div class="mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6 mb-8">
        <VueDatePicker v-model="dateRange" range :preset-dates="presetDates" placeholder="Picker date range"
          format="dd-MMM-yyyy" :multi-calendars="{ solo: true }" />
      </div>
    </div>
    <div class="px-16">
      <div class="mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="max-w-sm p-6 bg-custom-darksky rounded-lg shadow overflow-auto">
          <p class="mb-1 text-sm text-gray-300">Total Users</p>
          <h2 class="mb-2 text-2xl sm:text-3xl lg:text-4xl font-bold text-white">
            {{ totalUserCount }}
          </h2>
        </div>
        <div class="max-w-sm p-6 bg-custom-darksky rounded-lg shadow overflow-auto">
          <p class="mb-1 text-sm text-gray-300">
            Active Users
          </p>
          <h2 class="mb-2 text-2xl sm:text-3xl lg:text-4xl font-bold text-white">
            {{ activeUsersCount }}
          </h2>
        </div>
        <div class="max-w-sm p-6 bg-custom-darksky rounded-lg shadow overflow-auto">
          <p class="mb-1 text-sm text-gray-300">Total Revenue</p>
          <h2 class="mb-2 text-2xl sm:text-3xl lg:text-4xl font-bold text-white">
            ${{ formatMoney(totalAmountSpent) }}
          </h2>
        </div>

      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <Bar v-if="spendData.length" id="spend-chart-id" :options="spendChartOptions" :data="spendChartData" />

          <div v-else class="text-center py-10 text-gray-300 text-sm">
            <div class="py-10 bg-sky-950 rounded shadow-xl">
              No data to display for spenditure.
            </div>
          </div>
        </div>
        <div>
          <Bar v-if="callData.length" id="call-chart-id" :options="chartOptions" :data="callChartData" />

          <div v-else class="text-center py-10 text-gray-300 text-sm">
            <div class="py-10 bg-sky-950 rounded shadow-xl">
              No data to display for calls.
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
