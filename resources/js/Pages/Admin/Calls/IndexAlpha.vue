<script setup>
import { ref, onMounted, watch, computed } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import InfinityTable from "@/Components/InfinityTable.vue";
import { toaster } from "@/helper.js";
import { useInfinityTable } from "@/Composables/InfinityTable.js";
import {
  Menu,
  MenuButton,
  MenuItems,
  MenuItem,
  Popover,
  PopoverButton,
  PopoverPanel,
} from "@headlessui/vue";

let page = usePage();
let initialUrl = page.url;
if (page.props.flash.message) {
  toaster("success", page.props.flash.message);
}
let props = defineProps({
  calls: {
    type: Object,
    required: true,
  },
  totalCalls: {
    type: Number,
    required: true,
  },
  totalRevenue: {
    type: Number,
    required: true,
  },
  callsGroupedByUser: {
    type: Object,
    required: true,
  },
});

let columns = ref([
  {
    label: "ID",
    columnMethod: "getIdColumn",
    visible: true,
    sortable: true,
    sortingMethod(a, b) {
      let valueA = a.id; // Assuming 'id' is the property name in your call object
      let valueB = b.id;

      if (sortDirection.value === "asc") {
        return valueA - valueB;
      } else {
        return valueB - valueA;
      }
    },
    render(call) {
      return call.id;
    },
  },
  {
    label: "Call Date",
    columnMethod: "getCallTakenColumn",
    visible: true,
    sortable: true,
    render(call) {
      return call.call_taken;
    },
    sortingMethod: (a, b) => {
      const extractDateTime = (call) => {
        const dateTimePattern = /\((.*?)\)/; // Regex to extract string in parentheses
        const match = call.call_taken.match(dateTimePattern);
        return match
          ? new Date(match[1].replace(/(\d{2})\/(\d{2})\/(\d{4})/, "$2/$1/$3"))
          : new Date(0); // Convert to MM/DD/YYYY format for Date parsing
      };

      let dateA = extractDateTime(a);
      let dateB = extractDateTime(b);

      if (sortDirection.value === "asc") {
        return dateA - dateB;
      } else {
        return dateB - dateA;
      }
    },
  },
  {
    label: "Agent Name",
    columnMethod: "getAgentNameColumn",
    visible: true,
    sortable: false,
    render(call) {
      return call.user.first_name + " " + call.user.last_name;
    },
  },
  {
    label: "Role",
    columnMethod: "getRoleColumn",
    visible: false,
    sortable: false,
    render(call) {
      return call.user.role;
    },
  },
  {
    label: "Connected Duration",
    columnMethod: "getConnectedDurationColumn",
    visible: false,
    sortable: true,
    sortingMethod: (a, b) => {
      let durationA = a.call_duration_in_seconds;
      let durationB = b.call_duration_in_seconds;

      if (sortDirection.value === "asc") {
        return durationA - durationB;
      } else {
        return durationB - durationA;
      }
    },
    render(call) {
      return (
        String(Math.floor(call.call_duration_in_seconds / 60)).padStart(2, "0") +
        ":" +
        String(call.call_duration_in_seconds % 60).padStart(2, "0")
      );
    },
  },
  {
    label: "Revenue",
    columnMethod: "getRevenueColumn",
    visible: true,
    sortable: true,
    render(call) {
      return "$" + call.amount_spent;
    },
    sortingMethod: (a, b) => {
      // Convert the string with dollar sign to a float number
      const getNumericValue = (call) => parseFloat(call.amount_spent);

      let valueA = getNumericValue(a);
      let valueB = getNumericValue(b);

      if (sortDirection.value === "asc") {
        return valueA - valueB;
      } else {
        return valueB - valueA;
      }
    },
  },
  {
    label: "Vertical",
    columnMethod: "getVerticalColumn",
    visible: true,
    sortable: false,
    render(call) {
      return call.call_type.type;
    },
  },
  {
    label: "CallerID",
    columnMethod: "getCallerIdColumn",
    visible: true,
    sortable: false,
    render(call) {
      return call.from;
    },
  },
]);

let filters = ref([
  {
    label: "Paid Calls",
    checked: false,
    filter(calls) {
      return calls.filter((call) => call.amount_spent > 0);
    },
  },
  {
    label: "Unpaid Calls",
    checked: false,
    filter(calls) {
      console.log("Unpaid Calls Filter");
      console.log(calls.filter((call) => Number(call.amount_spent) === 0));

      return calls.filter((call) => Number(call.amount_spent) == 0);
    },
  },
]);

const {
  loadedItems,
  sortColumn,
  sortDirection,
  performSorting,
  sortByColumn,
  renderColumn,
  filteredItems,
  loadMore,
} = useInfinityTable(props.calls, initialUrl, columns, filters);

let callsGroupedByUser = ref(props.callsGroupedByUser);

// Columns for the grouped calls table
let groupedColumns = ref([
  { label: "Agent Name", render: (userData) => userData.agentName, visible: true },
  { label: "Total Calls", render: (userData) => userData.totalCalls, visible: true },
  { label: "Paid Calls", render: (userData) => userData.paidCalls, visible: true },
  {
    label: "Unpaid Calls",
    render: (userData) => userData.unpaidCalls,
    visible: true,
  },
  { label: "Revenue Earned", render: (userData) => `$${userData.revenueEarned}`, visible: true },
  {
    label: "Revenue Per Call",
    render: (userData) => `$${userData.revenuePerCall}`,
    visible: true,
  },
  { label: "Total Call Length", render: (userData) => userData.totalCallLength, visible: true },
  {
    label: "Average Call Length",
    render: (userData) => `${userData.averageCallLength} mins`,
    visible: true,
  },
  // Add more columns as needed
]);

// Filters for the grouped calls table (if needed)
let groupedFilters = [];

const groupedCallsItems = {
  data: Object.values(props.callsGroupedByUser),
};

const groupedTableData = useInfinityTable(
  groupedCallsItems,
  initialUrl,
  groupedFilters,
  false
);

console.log('Grouped Table Data: ', groupedTableData.filteredItems);
console.log('Grouped Table Data Loaded Data: ', groupedTableData.loadedItems);

</script>

<template>
  <Head title="Calls" />
  <AuthenticatedLayout>
    <div class="pt-14 flex justify-between px-16">
      <div>
        <div class="text-4xl text-custom-sky font-bold mb-6">Calls</div>
      </div>

      <div class="flex items-center">
        <!-- <button class="button-custom-back px-3 py-2 rounded-md mr-2">Clear All</button> -->
      </div>
    </div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
      <div class="px-4 sm:px-8 sm:rounded-lg">
        <hr class="mb-4" />
      </div>
    </div>

    <!-- Grouped Calls Table -->
    <InfinityTable
      :columns="groupedColumns"
      :items="groupedTableData.filteredItems"
      :renderColumn="groupedTableData.renderColumn"
      :filters="groupedFilters"
      :totalItems="totalCalls"
    />

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
      <div class="px-4 sm:px-8 sm:rounded-lg">
        <hr class="mb-4" />
      </div>
    </div>

    <InfinityTable
      :columns="columns"
      :filters="filters"
      :sortColumn="sortColumn"
      :sortDirection="sortDirection"
      :items="filteredItems"
      :totalItems="totalCalls"
      :loadMore="loadMore"
      :renderColumn="renderColumn"
      :sortByColumn="sortByColumn"
    />
  </AuthenticatedLayout>
</template>
