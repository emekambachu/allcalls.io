<script setup>
import { ref, onMounted, watch, computed } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { toaster } from "@/helper.js";
import GlobalSpinner from "@/Components/GlobalSpinner.vue";
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
if (page.props.flash.message) {
  toaster("success", page.props.flash.message);
}
let props = defineProps({
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
    name: "id",
    visible: true,
    sortable: true,
    render(call) {
      return call.id;
    },
  },
  {
    label: "Call Date",
    name: "call_taken",
    visible: true,
    render(call) {
      return call.call_taken;
    },
  },
  {
    label: "Role",
    name: "role",
    visible: false,
    sortable: false,
    render(call) {
      for (let i = 0; i < call.user.roles.length; i++) {
        if (call.user.roles[i].name === "internal-agent") {
          return "Internal Agent";
        }
      }

      return "External Agent";
    },
  },
  {
    label: "Connected Duration",
    name: "call_duration_in_seconds",
    visible: false,
    sortable: true,
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
    name: "amount_spent",
    visible: true,
    sortable: true,
    render(call) {
      return "$" + call.amount_spent;
    },
  },
  {
    label: "Vertical",
    name: "vertical",
    visible: true,
    sortable: false,
    render(call) {
      return call.call_type.type;
    },
  },
  {
    label: "CallerID",
    name: "from",
    visible: true,
    sortable: false,
    render(call) {
      return call.from;
    },
  },
]);

let performSorting = () => {
  console.log("Perform sorting now!!");
  console.log("Sort Column: ", sortColumn.value);
  console.log("Sort Direction", sortDirection.value);
};

let renderColumn = (column, call) => {
  return column.render(call);
};

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

let callsPaginator = ref(null);
let loadedCalls = ref([]);
let sortColumn = ref(null);
let sortDirection = ref("asc");
let loading = ref(false);
let currentPage = ref(1);

let fetchCalls = async (replace=false) => {
  let url = "/admin/web-api/calls?page=" + currentPage.value;

  loading.value = true;
  let response = await axios.get(url);

  if (replace) {
    loadedCalls.value = response.data.calls.data;
  } else {
    loadedCalls.value = [...loadedCalls.value, ...response.data.calls.data];
  }

  callsPaginator.value = response.data.calls;
  loading.value = false;
};

let loadMore = async () => {
  currentPage.value++;
  await fetchCalls();
};

onMounted(() => {
  fetchCalls();
});

let sortByColumn = async (column) => {
  console.log("Sort By Column: ", column.name);

  if (!column.sortable) return;

  sortColumn.value = column.name;
  sortDirection.value = "asc";

  await fetchCalls(true);
};
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
    <section v-if="false" class="py-3 sm:py-5">
      <div class="px-4 mx-auto max-w-screen-2xl lg:px-12">
        <div class="relative overflow-hidden bg-white sm:rounded-lg">
          <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
              <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr class="cursor-pointer">
                  <th scope="col" class="px-4 py-3 whitespace-nowrap">Agent Name</th>
                  <th scope="col" class="px-4 py-3 whitespace-nowrap">Total Calls</th>
                  <th scope="col" class="px-4 py-3 whitespace-nowrap">Paid Calls</th>
                  <th scope="col" class="px-4 py-3 whitespace-nowrap">Revenue Earned</th>
                  <th scope="col" class="px-4 py-3 whitespace-nowrap">
                    Revenue Per Call
                  </th>
                  <th scope="col" class="px-4 py-3 whitespace-nowrap">
                    Total Call Length
                  </th>
                  <th scope="col" class="px-4 py-3 whitespace-nowrap">
                    Average Call Length
                  </th>
                  <th scope="col" class="px-4 py-3 whitespace-nowrap">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(userData, userId) in callsGroupedByUser"
                  :key="userId"
                  class="border-b hover:bg-gray-100"
                >
                  <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">
                    {{ userData.agentName }}
                  </td>
                  <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">
                    {{ userData.totalCalls }}
                  </td>
                  <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">
                    {{ userData.paidCalls }}
                  </td>
                  <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">
                    ${{ userData.revenueEarned }}
                  </td>
                  <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">
                    ${{ userData.revenuePerCall.toFixed(2) }}
                  </td>
                  <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">
                    {{ userData.totalCallLength }}
                  </td>
                  <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">
                    {{ userData.averageCallLength.toFixed(2) }}
                  </td>
                  <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">
                    <!-- Actions column content -->
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>

    <section class="py-3 sm:py-5">
      <div class="px-4 mx-auto max-w-screen-2xl lg:px-12">
        <div class="relative overflow-hidden bg-white sm:rounded-lg">
          <div
            class="flex flex-col px-4 py-3 space-y-3 lg:flex-row lg:items-center lg:justify-between lg:space-y-0 lg:space-x-4"
          >
            <div class="flex items-center flex-1 space-x-4">
              <h5>
                <span class="text-gray-500">Total Calls: </span>
                <span class="">{{ totalCalls }}</span>
              </h5>
              <h5>
                <span class="text-gray-500">Total Revenue: </span>
                <span class="">${{ totalRevenue.toFixed(2) }}</span>
              </h5>
            </div>
            <div
              class="flex flex-col flex-shrink-0 space-y-3 md:flex-row md:items-center lg:justify-end md:space-y-0 md:space-x-3"
            >
              <div class="px-4 flex items-center">
                <Popover class="relative mr-2">
                  <PopoverButton>
                    <button
                      type="button"
                      class="flex items-center justify-center flex-shrink-0 px-3 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200"
                    >
                      Visible Columns
                    </button>
                  </PopoverButton>

                  <PopoverPanel class="absolute z-10 w-40 -left-20">
                    <div class="border border-gray-100 p-2 shadow bg-white mt-2">
                      <div
                        class="flex items-center mb-4"
                        v-for="(column, index) in columns"
                        :key="index"
                      >
                        <input
                          :id="`column-${index}`"
                          type="checkbox"
                          v-model="column.visible"
                          class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                        />
                        <label
                          :for="`column-${index}`"
                          class="ms-2 text-sm font-medium text-gray-900 select-none"
                          >{{ column.label }}</label
                        >
                      </div>
                    </div>
                  </PopoverPanel>
                </Popover>

                <Popover class="relative">
                  <PopoverButton>
                    <button
                      type="button"
                      class="flex items-center justify-center flex-shrink-0 px-3 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200"
                    >
                      Filters
                    </button>
                  </PopoverButton>

                  <PopoverPanel class="absolute z-10 w-40 -left-20">
                    <div class="border border-gray-100 p-2 shadow bg-white mt-2">
                      <div
                        v-for="(filter, index) in filters"
                        :key="index"
                        class="flex items-center mb-4"
                      >
                        <input
                          v-model="filter.checked"
                          :id="`filter-${index}`"
                          type="checkbox"
                          class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                        />
                        <label
                          :for="`filter-${index}`"
                          class="ms-2 text-sm font-medium text-gray-900 select-none"
                          v-text="filter.label"
                        ></label>
                      </div>
                    </div>
                  </PopoverPanel>
                </Popover>
              </div>
            </div>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
              <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr class="cursor-pointer">
                  <th
                    scope="col"
                    class="px-4 py-3 whitespace-nowrap select-none"
                    v-for="(column, index) in columns"
                    :key="index"
                    v-show="column.visible"
                    @click="sortByColumn(column)"
                  >
                    <div class="flex items-center">
                      <span>{{ column.label }}</span>

                      <span>
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke-width="1.5"
                          stroke="currentColor"
                          class="w-3 h-3 ml-1"
                          v-if="sortDirection === 'asc'"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M4.5 15.75l7.5-7.5 7.5 7.5"
                          />
                        </svg>

                        <svg
                          v-if="sortDirection === 'desc'"
                          xmlns="http://www.w3.org/2000/svg"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke-width="1.5"
                          stroke="currentColor"
                          class="w-3 h-3 ml-1"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                          />
                        </svg>
                      </span>
                    </div>
                  </th>
                  <th scope="col" class="px-4 py-3 whitespace-nowrap">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  class="border-b hover:bg-gray-100"
                  v-for="(call, index) in loadedCalls"
                  :key="call.id"
                >
                  <td
                    v-for="(column, colIndex) in columns"
                    :key="colIndex"
                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"
                    v-show="column.visible"
                    v-text="renderColumn(column, call)"
                  ></td>
                  <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">
                    <!-- Actions column content -->
                  </td>
                </tr>
              </tbody>
            </table>

            <div
              v-if="callsPaginator && callsPaginator.next_page_url"
              class="flex items-center justify-center py-4 mt-4"
            >
              <button
                @click.prevent="loadMore"
                class="bg-gray-200 hover:bg-gray-100 text-gray-800 cursor-pointer px-4 py-2 text-sm rounded-md flex items-center"
              >
                <GlobalSpinner :spinner="loading" />
                Load More
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>
  </AuthenticatedLayout>
</template>
