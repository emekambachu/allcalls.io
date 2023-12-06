<script setup>
import { ref, onMounted, watch, computed } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, usePage, Link } from "@inertiajs/vue3";
import { toaster } from "@/helper.js";
import GlobalSpinner from "@/Components/GlobalSpinner.vue";
import TextInput from "@/Components/TextInput.vue";
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
    sortable: true,
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

let filters = ref([]);

let callsPaginator = ref(null);
let loadedCalls = ref([]);
let sortColumn = ref(null);
let sortDirection = ref("asc");
let loading = ref(false);
let currentPage = ref(1);

let fetchCalls = async (replace = false) => {
  let url = "/admin/web-api/calls?page=" + currentPage.value;

  if (sortColumn.value) {
    url += "&sort_column=" + sortColumn.value;
  }

  if (sortDirection.value) {
    url += "&sort_direction=" + sortDirection.value;
  }

  loading.value = true;
  let response = await axios.get(url);

  if (replace) {
    loadedCalls.value = response.data.calls.data;
  } else {
    loadedCalls.value = [...loadedCalls.value, ...response.data.calls.data];
  }

  callsPaginator.value = response.data.calls;
  loading.value = false;
  console.log("Loaded Calls: ", loadedCalls.value);
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

  if (sortDirection.value === "asc") {
    sortDirection.value = "desc";
  } else {
    sortDirection.value = "asc";
  }

  await fetchCalls(true);
};

let callsGroupedByUserArray = Object.entries(props.callsGroupedByUser);
let minimizedCallsGroupedByUserArray = callsGroupedByUserArray.slice(0, 2);
let minimizedCallsGroupedByUser = ref(
  Object.fromEntries(minimizedCallsGroupedByUserArray)
);
let maxmizedCallsGroupedByUser = ref(Object.fromEntries(callsGroupedByUserArray));
let showMoreForGrouped = ref(false);

console.log("Mini Calls Grouped By User: ", minimizedCallsGroupedByUser.value);
console.log("Max Calls Grouped By User: ", maxmizedCallsGroupedByUser.value);

let groupedCalls = computed(() => {
  if (showMoreForGrouped.value) {
    return maxmizedCallsGroupedByUser.value;
  } else {
    return minimizedCallsGroupedByUser.value;
  }
});

let summaryFooterRow = computed(() => {
  let totalCalls = 0;
  let totalPaidCalls = 0;
  let totalRevenue = 0;
  let totalCallLength = 0;

  for (const userId in maxmizedCallsGroupedByUser.value) {
    const userData = maxmizedCallsGroupedByUser.value[userId];
    totalCalls += userData.totalCalls;
    totalPaidCalls += userData.paidCalls; // Summing up the paidCalls
    totalRevenue += userData.revenueEarned;
    totalCallLength += userData.totalCallLength; // Assuming this field exists in userData
  }

  let averageCallLength = totalCalls > 0 ? totalCallLength / totalCalls : 0;
  let revenuePerCall = totalCalls > 0 ? totalRevenue / totalCalls : 0;

  return {
    agentName: "Totals",
    totalCalls: totalCalls,
    paidCalls: totalPaidCalls,
    revenueEarned: totalRevenue,
    revenuePerCall: revenuePerCall,
    totalCallLength: totalCallLength,
    averageCallLength: averageCallLength,
  };
});

let exportCSV = () => {
  // Filter out only the visible columns
  let visibleColumns = columns.value.filter((c) => c.visible);

  // Extracting headers from the visible columns
  let headers = visibleColumns.map((c) => c.label);

  let csvContent = "data:text/csv;charset=utf-8," + headers.join(",") + "\r\n";

  // Adding the data
  loadedCalls.value.forEach((call) => {
    let row = visibleColumns
      .map((column) => {
        // Using the render function to get the displayed value
        return column.render(call);
      })
      .join(",");
    csvContent += row + "\r\n";
  });

  // Encoding URI
  var encodedUri = encodeURI(csvContent);

  // Creating a temporary link to trigger download
  var link = document.createElement("a");
  link.setAttribute("href", encodedUri);
  link.setAttribute("download", "calls.csv");
  document.body.appendChild(link);

  // Triggering the download
  link.click();

  // Cleaning up
  document.body.removeChild(link);
};

let currentlyPlayingAudio = ref(null);
let currentlyPlayingAudioCallId = ref(null);

let playRecording = (call) => {
  currentlyPlayingAudio.value = new Audio(call.recording_url);
  currentlyPlayingAudio.value.play();
  currentlyPlayingAudioCallId.value = call.id;

  // Assuming audio is your Audio element
  currentlyPlayingAudio.value.addEventListener("ended", () => {
    currentlyPlayingAudio.value.pause();
    currentlyPlayingAudio.value = null;
    currentlyPlayingAudioCallId.value = null;
  });
};

let stopPlayingRecording = (call) => {
  currentlyPlayingAudio.value.pause();
  currentlyPlayingAudio.value = null;
  currentlyPlayingAudioCallId.value = null;
};

let date = ref([new Date(), new Date()]);

watch(date, (newVal, oldVal) => {
  console.log("Date range: ", newVal);
});

onMounted(() => {
  const startDate = new Date();
  const endDate = new Date(new Date().setDate(startDate.getDate() + 7));
  date.value = [startDate, endDate];
});
</script>

<template>
  <Head title="Calls" />
  <AuthenticatedLayout>
    <div class="pt-14 flex justify-between px-16">
      <div>
        <div class="text-4xl text-custom-sky font-bold mb-6">Summary</div>
      </div>
    </div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
      <div class="px-4 sm:px-8 sm:rounded-lg">
        <hr class="mb-4" />
      </div>
    </div>

    <section class="py-3 sm:py-5">
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
                  v-for="(userData, userId) in groupedCalls"
                  :key="userId"
                  class="border-b hover:bg-gray-100"
                >
                  <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">
                    <Link
                      :href="`/admin/customer/detail/${userId}`"
                      class="text-blue-400 hover:text-blue-500 underline"
                    >
                      {{ userData.agentName }}
                    </Link>
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
                    {{
                      String(Math.floor(userData.totalCallLength / 60)).padStart(2, "0") +
                      ":" +
                      String(userData.totalCallLength % 60).padStart(2, "0")
                    }}
                  </td>
                  <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">
                    {{ userData.averageCallLength.toFixed(2) }} sec
                  </td>
                  <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">
                    <!-- Actions column content -->
                  </td>
                </tr>

                <tr class="bg-gray-100 border-b" v-if="showMoreForGrouped">
                  <td
                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"
                    v-text="summaryFooterRow.agentName"
                  ></td>
                  <td
                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"
                    v-text="summaryFooterRow.totalCalls"
                  ></td>
                  <td
                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"
                    v-text="summaryFooterRow.paidCalls"
                  ></td>
                  <td
                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"
                    v-text="`$${summaryFooterRow.revenueEarned}`"
                  ></td>
                  <td
                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"
                    v-text="`$${summaryFooterRow.revenuePerCall.toFixed(2)}`"
                  ></td>
                  <td
                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"
                    v-text="`${summaryFooterRow.totalCallLength.toFixed(2)}`"
                  ></td>
                  <td
                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"
                    v-text="`${summaryFooterRow.averageCallLength.toFixed(2)}`"
                  ></td>
                  <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"></td>
                </tr>
              </tbody>
            </table>

            <div class="flex justify-center mt-4">
              <button
                @click.prevent="showMoreForGrouped = !showMoreForGrouped"
                class="bg-gray-200 hover:bg-gray-100 text-gray-800 cursor-pointer px-4 py-2 text-sm rounded-md flex items-center"
              >
                <span v-if="showMoreForGrouped">Show Less</span>
                <span v-else>Show More</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <div class="pt-14 flex justify-between px-16">
      <div>
        <div class="text-4xl text-custom-sky font-bold mb-6">Call Details</div>
      </div>
    </div>

    <div class="pt-14 flex justify-between px-16">
      <VueDatePicker range v-model="date"></VueDatePicker>
    </div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
      <div class="px-4 sm:px-8 sm:rounded-lg">
        <hr class="mb-4" />
      </div>
    </div>

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
              <div class="flex items-center">
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
                    <div class="border border-gray-100 p-3 shadow bg-white mt-2">
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
                          class="ms-2 text-xs font-medium text-gray-900 select-none whitespace-nowrap"
                          >{{ column.label }}</label
                        >
                      </div>
                    </div>
                  </PopoverPanel>
                </Popover>

                <button
                  type="button"
                  class="flex items-center justify-center flex-shrink-0 px-3 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200"
                  @click.prevent="exportCSV"
                >
                  Export
                </button>

                <!-- <Popover class="relative">
                  <PopoverButton>
                    <button
                      type="button"
                      class="flex items-center justify-center flex-shrink-0 px-3 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200"
                    >
                      Filters
                    </button>
                  </PopoverButton>

                  <PopoverPanel class="absolute z-10 -left-20">
                    <div class="border border-gray-100 p-2 shadow bg-white mt-2"></div>
                  </PopoverPanel>
                </Popover> -->
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

                      <span
                        v-if="column.sortable && sortColumn && sortColumn === column.name"
                      >
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
                  <th scope="col" class="px-4 py-3 whitespace-nowrap">Recording</th>
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
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      class="w-4 h-4 cursor-pointer ml-3"
                      v-if="currentlyPlayingAudioCallId !== call.id"
                      @click.prevent="playRecording(call)"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z"
                      />
                    </svg>

                    <div class="flex items-center">
                      <svg
                        class="w-4 h-4 cursor-pointer ml-3"
                        v-if="currentlyPlayingAudioCallId === call.id"
                        @click.prevent="stopPlayingRecording(call)"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M5.25 7.5A2.25 2.25 0 017.5 5.25h9a2.25 2.25 0 012.25 2.25v9a2.25 2.25 0 01-2.25 2.25h-9a2.25 2.25 0 01-2.25-2.25v-9z"
                        />
                      </svg>

                      <p
                        style="font-size: 10px"
                        class="text-gray-800 ml-1 user-select-none"
                        v-if="currentlyPlayingAudioCallId === call.id"
                      >
                        Playing
                      </p>
                    </div>
                  </td>
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
