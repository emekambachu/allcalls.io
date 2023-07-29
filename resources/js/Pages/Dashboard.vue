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
import { reactive } from "vue";

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
</script>

<template>
  <Head title="Dashboard" />
  <AuthenticatedLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
      >
        Dashboard
      </h2>
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
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <Bar
            id="spend-chart-id"
            :options="spendChartOptions"
            :data="spendChartData"
          />
        </div>
        <div>
          <Bar
            id="call-chart-id"
            :options="chartOptions"
            :data="callChartData"
          />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
