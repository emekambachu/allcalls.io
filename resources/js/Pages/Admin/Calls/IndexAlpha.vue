<script setup>
import { ref, onMounted, watch, computed } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import InfinityTable from "@/Components/InfinityTable.vue";
import { toaster } from "@/helper.js";
import useInfinityTable from "@/Composables/useInfinityTable.js";
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





const {
    loadedItems,
    sortColumn,
    sortDirection,
    performSorting,
    sortByColumn,
    renderColumn,
    filteredItems,
    loadMore
} = useInfinityTable(props, initialUrl, columns, filters);

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

let filteredCalls = computed(() => {
  let calls = loadedCalls.value;

  filters.value.forEach((filter) => {
    if (filter.checked) {
      calls = filter.filter(calls);
    }
  });

  return calls;
});










let callsGroupedByUser = ref(props.callsGroupedByUser);

// Convert the object into an array of [userId, calls] pairs
let groupedCallsArray = computed(() => {
  return Object.entries(callsGroupedByUser.value);
});


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

    <InfinityTable
        :columns="columns"
        :filters="filters"
        :sortColumn="sortColumn"
        :sortDirection="sortDirection"
        :items="filteredCalls"
        :totalItems="totalCalls"
        :loadMore="loadMore"
        :renderColumn="renderColumn"
        :sortByColumn="sortByColumn"
     />
  </AuthenticatedLayout>
</template>
