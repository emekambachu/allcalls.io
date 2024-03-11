<script setup>
import { onMounted, ref, watch, defineEmits } from "vue";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
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
let emit = defineEmits();
const props = defineProps({
  dateRange: Array,
});
if (props.dateRange) {
  dateRange.value = props.dateRange;
}
watch(dateRange, (newVal) => {
  if (newVal) {
    emit("changeDate", dateRange.value);
  }
});
</script>
<template>
  <VueDatePicker
    v-model="dateRange"
    :range="true"
    :preset-dates="presetDates"
    placeholder="Picker date range"
    format="MMM-dd-yyyy"
    :multi-calendars="{ solo: true }"
    auto-apply
  />
</template>
