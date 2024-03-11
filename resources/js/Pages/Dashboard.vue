<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
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
import { reactive, ref, watch, onMounted } from "vue";

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

const props = defineProps({
  spendData: Array,
  callData: Array,
  totalCalls: Number,
  totalAmountSpent: Number,
  averageCallDuration: Number,
  callDiffInPercentage: Number,
  totalAmountSpentPercentage: Number,
  averageCallDurationPercentage: Number,
  from: String,
  to: String,
});
import {
  endOfDay,
  startOfDay,
  subDays,
  startOfWeek,
  endOfWeek,
  subWeeks,
  startOfMonth,
  endOfMonth,
  startOfQuarter,
  endOfQuarter,
  subQuarters,
  startOfYear,
  endOfYear,
  subMonths,
} from "date-fns";

const presetDates = ref([
  { label: "Today", value: [startOfDay(new Date()), endOfDay(new Date())] },
  {
    label: "Yesterday",
    value: [startOfDay(subDays(new Date(), 1)), endOfDay(subDays(new Date(), 1))],
  },
  { label: "This Week", value: [startOfWeek(new Date()), endOfWeek(new Date())] },
  {
    label: "Last Week",
    value: [startOfWeek(subWeeks(new Date(), 1)), endOfWeek(subWeeks(new Date(), 1))],
  },
  { label: "Last 7 Days", value: [subDays(new Date(), 6), endOfDay(new Date())] },
  { label: "Last 14 Days", value: [subDays(new Date(), 13), endOfDay(new Date())] },
  { label: "Last 30 Days", value: [subDays(new Date(), 29), endOfDay(new Date())] },
  { label: "This Month", value: [startOfMonth(new Date()), endOfMonth(new Date())] },
  {
    label: "Last Month",
    value: [startOfMonth(subMonths(new Date(), 1)), endOfMonth(subMonths(new Date(), 1))],
  },
  { label: "Last 90 Days", value: [subDays(new Date(), 89), endOfDay(new Date())] },
  { label: "Last 6 Months", value: [subMonths(new Date(), 5), endOfDay(new Date())] },
  { label: "This Year", value: [startOfYear(new Date()), endOfYear(new Date())] },
]);

let dateRange = ref([]);
watch(dateRange, (newVal) => {
  if (newVal) {
    fetechDashboard();
  }
});
const formatDate = (date) => {
  return date.toLocaleString("en-US", {
    weekday: "short",
    month: "short",
    day: "2-digit",
    year: "numeric",
    hour: "2-digit",
    minute: "2-digit",
    second: "2-digit",
    timeZoneName: "short",
  });
};

const calculateLast7Days = () => {
  const toDate = new Date(); // Today's date
  const fromDate = new Date();
  fromDate.setDate(toDate.getDate() - 6); // Subtract 7 days from today

  return {
    from: formatDate(fromDate),
    to: formatDate(toDate),
  };
};

const watchFromTo = () => {
  if (props.from && props.to) {
    const fromDate = new Date(props.from);
    const toDate = new Date(props.to);
    dateRange.value.push(formatDate(fromDate), formatDate(toDate));
  } else {
    // Calculate last 7 days if props.from and props.to are not set
    const last7Days = calculateLast7Days();
    dateRange.value.push(last7Days.from, last7Days.to);
  }
};

onMounted(() => {
  watchFromTo();
});

let spendChartData = reactive({
  labels: props.spendData.map((item) => item.date),
  datasets: [
    {
      label: "Amount Spent",
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
      label: "Clients per Day",
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
  return `${minutes.toString().padStart(2, "0")}:${seconds.toString().padStart(2, "0")}`;
};

let formatMoney = (amount) => {
  return parseFloat(amount)
    .toFixed(2)
    .replace(/\d(?=(\d{3})+\.)/g, "$&,");
};

let fetechDashboard = async (val) => {
  try {
    // console.log('api call');
    const from = new Date(dateRange.value[0]);
    const to = new Date(dateRange.value[1]);

    // Extract month, date, and year components
    const fromMonth = from.getMonth() + 1; // Add 1 because months are zero-based
    const fromDate = from.getDate();
    const fromYear = from.getFullYear();

    const toMonth = to.getMonth() + 1;
    const toDate = to.getDate();
    const toYear = to.getFullYear();

    // Format the components as desired (e.g., as "MM-DD-YYYY")
    const formattedFrom = `${fromMonth}/${fromDate}/${fromYear}`;
    const formattedTo = `${toMonth}/${toDate}/${toYear}`;

    const queryParams = {
      from: formattedFrom,
      to: formattedTo,
    };

    router.visit("/dashboard", {
      data: queryParams,
    });
  } catch (error) {
    console.log(error);
  }
};
let formatNumberWith5DecimalPlaces = (number) => {
  if (Number.isInteger(number)) {
    return number.toString(); // Convert to string without decimal places for integers
  } else {
    return number.toFixed(3).replace(/\.?0+$/, ""); // Format with 5 decimal places and remove trailing zeros
  }
};
</script>

<template>
  <Head title="Dashboard" />
  <AuthenticatedLayout>
    <template #header>
      <h2>Dashboard</h2>
    </template>

    <div class="pt-14">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="px-4 sm:px-8 sm:rounded-lg">
          <div class="text-4xl text-custom-sky font-bold mb-6">Dashboard</div>
          <hr class="mb-4" />
        </div>
      </div>
    </div>
    <div class="px-16">
      <div class="mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6 mb-8">
        <VueDatePicker
          v-model="dateRange"
          range
          :preset-dates="presetDates"
          placeholder="Picker date range"
          format="MMM-dd-yyyy"
          :multi-calendars="{ solo: true }"
        />
      </div>
    </div>
    <div class="px-16">
      <div class="mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="max-w-sm p-6 bg-custom-darksky rounded-lg shadow relative">
          <p class="mb-1 text-sm text-gray-300">Total Calls</p>
          <h2 class="mb-2 text-2xl sm:text-3xl lg:text-4xl font-bold text-white">
            {{ totalCalls }}
          </h2>
          <button
            v-if="callDiffInPercentage && callDiffInPercentage > 0"
            class="absolute right-2 bottom-3 px-3 py-1 flex"
            style="background: #ecfef3; border-radius: 10px; color: #168054"
          >
            <svg
              class="w-3 h-3 mr-2 text-gray-800 dark:text-white"
              style="margin-top: 6px; color: #168054"
              aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 10 14"
            >
              <path
                stroke="currentColor"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M5 13V1m0 0L1 5m4-4 4 4"
              />
            </svg>
            {{ formatNumberWith5DecimalPlaces(Math.abs(callDiffInPercentage)) }}%
          </button>
          <button
            v-if="callDiffInPercentage <= 0"
            class="absolute right-2 bottom-3 px-3 py-1 flex"
            style="background: #fef4f3; border-radius: 10px; color: #ba3228"
          >
            <svg
              class="w-3 h-3 text-gray-800 mr-2 dark:text-white"
              style="margin-top: 6px; color: #ba3228"
              aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 10 14"
            >
              <path
                stroke="currentColor"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M5 1v12m0 0 4-4m-4 4L1 9"
              />
            </svg>
            {{ formatNumberWith5DecimalPlaces(Math.abs(callDiffInPercentage)) }}%
          </button>
        </div>
        <div
          class="max-w-sm p-6 bg-custom-darksky rounded-lg shadow overflow-auto relative"
        >
          <p class="mb-1 text-sm text-gray-300">Total Spent</p>
          <h2 class="mb-2 text-2xl sm:text-3xl lg:text-4xl font-bold text-white">
            ${{ formatMoney(totalAmountSpent) }}
          </h2>
          <button
            v-if="totalAmountSpentPercentage && totalAmountSpentPercentage > 0"
            class="absolute right-2 bottom-3 px-3 py-1 flex"
            style="background: #ecfef3; border-radius: 10px; color: #168054"
          >
            <svg
              class="w-3 h-3 mr-2 text-gray-800 dark:text-white"
              style="margin-top: 6px; color: #168054"
              aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 10 14"
            >
              <path
                stroke="currentColor"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M5 13V1m0 0L1 5m4-4 4 4"
              />
            </svg>
            {{ formatNumberWith5DecimalPlaces(Math.abs(totalAmountSpentPercentage)) }}%
          </button>
          <button
            v-if="totalAmountSpentPercentage && totalAmountSpentPercentage < 0"
            class="absolute right-2 bottom-3 px-3 py-1 flex"
            style="background: #fef4f3; border-radius: 10px; color: #ba3228"
          >
            <svg
              class="w-3 h-3 text-gray-800 mr-2 dark:text-white"
              style="margin-top: 6px; color: #ba3228"
              aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 10 14"
            >
              <path
                stroke="currentColor"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M5 1v12m0 0 4-4m-4 4L1 9"
              />
            </svg>
            {{ formatNumberWith5DecimalPlaces(Math.abs(totalAmountSpentPercentage)) }}%
          </button>
        </div>
        <div
          class="max-w-sm p-6 bg-custom-darksky rounded-lg shadow overflow-auto relative"
        >
          <p class="mb-1 text-sm text-gray-300">Average Call Duration</p>
          <h2 class="mb-2 text-2xl sm:text-3xl lg:text-4xl font-bold text-white">
            {{ formatTime(averageCallDuration) }}
          </h2>
          <button
            v-if="averageCallDurationPercentage && averageCallDurationPercentage > 0"
            class="absolute right-2 bottom-3 px-3 py-1 flex"
            style="background: #ecfef3; border-radius: 10px; color: #168054"
          >
            <svg
              class="w-3 h-3 mr-2 text-gray-800 dark:text-white"
              style="margin-top: 6px; color: #168054"
              aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 10 14"
            >
              <path
                stroke="currentColor"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M5 13V1m0 0L1 5m4-4 4 4"
              />
            </svg>
            {{ formatNumberWith5DecimalPlaces(Math.abs(averageCallDurationPercentage)) }}%
          </button>
          <button
            v-if="averageCallDurationPercentage && averageCallDurationPercentage < 0"
            class="absolute right-2 bottom-3 px-3 py-1 flex"
            style="background: #fef4f3; border-radius: 10px; color: #ba3228"
          >
            <svg
              class="w-3 h-3 text-gray-800 mr-2 dark:text-white"
              style="margin-top: 6px; color: #ba3228"
              aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 10 14"
            >
              <path
                stroke="currentColor"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M5 1v12m0 0 4-4m-4 4L1 9"
              />
            </svg>
            {{ formatNumberWith5DecimalPlaces(Math.abs(averageCallDurationPercentage)) }}%
          </button>
        </div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <Bar
            v-if="spendData.length"
            id="spend-chart-id"
            :options="spendChartOptions"
            :data="spendChartData"
          />

          <div v-else class="text-center py-10 text-gray-300 text-sm">
            <div class="py-10 bg-sky-950 rounded shadow-xl">
              No data to display for spenditure.
            </div>
          </div>
        </div>
        <div>
          <Bar
            v-if="callData.length"
            id="call-chart-id"
            :options="chartOptions"
            :data="callChartData"
          />

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
