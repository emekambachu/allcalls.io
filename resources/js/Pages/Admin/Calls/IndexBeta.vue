<script setup>
import { ref, onMounted, watch, computed } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, usePage, Link } from "@inertiajs/vue3";
import { toaster } from "@/helper.js";
import GlobalSpinner from "@/Components/GlobalSpinner.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import Modal from "@/Components/Modal.vue";
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

const getTotalCalls = ref(props.totalCalls);
const getTotalRevenue = ref(props.totalRevenue);

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
        label: "Agent Name",
        name: "agent_name",
        visible: false,
        sortable: true,
        render(call) {
            return call.user !== null ? call.user.first_name+' '+call.user.last_name : '';
        },
    },

    {
        label: "Publisher ID",
        columnMethod: "getPublisherIdColumn",
        visible: true,
        sortable: true,
        render(call) {
            return call.publisher_id;
        },
    },

    {
        label: "Publisher Name",
        columnMethod: "getPublisherNameColumn",
        visible: true,
        sortable: true,
        render(call) {
            return call.publisher_name;
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

      return "Regular User";
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
        label: "Ringing Duration",
        name: "ringing_duration",
        visible: false,
        sortable: true,
        render(call) {
            return call.ringing_duration;
        },
    },



  {
    label: "Hung up by",
    name: "hung_up_by",
    visible: false,
    sortable: false,
    render(call) {
       return call.hung_up_by;
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
  {
    label: "User Email",
    name: "user_email",
    visible: false,
    sortable: false,
    render(call) {
      return call.user_email;
    },
  },
    {
        label: "Disposition",
        name: "disposition",
        visible: false,
        sortable: false,
        render(call) {
            return call.client?.status;
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

  // Include start_date and end_date in the query string if they both exist
  // if (startDate.value && endDate.value) {
  //   url += "&start_date=" + startDate.value;
  //   url += "&end_date=" + endDate.value;
  // }

  if (dateFilterFrom.value && dateFilterTo.value) {
    url += "&start_date=" + dateFilterFrom.value;
    url += "&end_date=" + dateFilterTo.value;
  }

  // Append filters to the query string
  appliedFilters.value.forEach((filter, index) => {
    url += `&filters[${index}][name]=${encodeURIComponent(filter.name)}`;
    url += `&filters[${index}][value]=${encodeURIComponent(filter.value)}`;
    url += `&filters[${index}][operator]=${encodeURIComponent(filter.operator)}`;
  });

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

onMounted(() => {});

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
const filteredGroupedCalls = ref({});

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

let filters = ref([
  {
    label: "ID",
    name: "id",
    operators: ["is", "is greater than", "is less than", "is greater than or equal to", "is less than or equal to"],
    inputType: "number",
  },
    {
        label: "Publisher ID",
        name: "publisher_id",
        operators: ["is"],
        inputType: "text"
    },
    {
        label: "Publisher Name",
        name: "publisher_name",
        operators: ["is"],
        inputType: "text"
    },
  {
    label: "Call Duration (in seconds)",
    name: "call_duration_in_seconds",
    operators: ["is", "is greater than", "is less than", "is greater than or equal to", "is less than or equal to"],
    inputType: "number"
  },
    {
        label: "Ring Duration (in seconds)",
        name: "ringing_duration",
        operators: ["is", "is greater than", "is less than", "is greater than or equal to", "is less than or equal to"],
        inputType: "number"
    },
    {
        label: "Agent Name",
        name: "agent_name",
        operators: ["is"],
        inputType: "text"
    },
  {
    label: "Hung up by",
    name: "hung_up_by",
    operators: ["is"],
    inputType: "text"
  },
  {
    label: "Revenue",
    name: "amount_spent",
    operators: ["is", "is greater than", "is less than", "is greater than or equal to", "is less than or equal to"],
    inputType: "number"
  },
  {
    label: "CallerID",
    name: "from",
    operators: ["is"],
    inputType: "text"
  },
  {
    label: "User Email",
    name: "user_email",
    operators: ["is"],
    inputType: "email"
  },
    {
        label: "Disposition",
        name: "disposition",
        operators: ["is"],
        inputType: "text"
    },
  {
    label: "Vertical",
    name: "vertical",
    operators: ["is"],
    inputType: "select",
    inputTypeOptions: [{
      label: "Auto Insurance",
      value: "Auto Insurance",
    }, {
      label: "Final Expense",
      value: "Final Expense",
    }, {
      label: "U65 Health",
      value: "U65 Health",
    }, {
      label: "ACA",
      value: "ACA",
    }, {
      label: "Medicare",
      value: "Medicare",
    }]
  },
  {
    label: "Role",
    name: "user_role",
    operators: ["is"],
    inputType: "select",
    inputTypeOptions: [
      {
        label: "Internal Agent",
        value: "internal-agent"
      },
      {
        label: "Regular User",
        value: "regular-user"
      }
    ]
  },
]);

let appliedFilters = ref([])

let showNewFilterModal = ref(false);
let filterName = ref("id");
let filterOperator = ref("is");
let filterValue = ref("");


let applyFilter = async () => {
  console.log({
    label: filterName.value,
    name: filterName.value,
    value: filterValue.value,
    operator: filterOperator.value,
    groupedCalls: groupedCalls.value
  });

  let label = filters.value.filter((f) => f.name === filterName.value)[0].label;
  console.log('Label: ', label);

  appliedFilters.value.push({
    label: label,
    name: filterName.value,
    value: filterValue.value,
    operator: filterOperator.value,
  });

  // Refetch the calls and replace them with current filters
  await fetchCalls(true);
  applyCallFiltersToSummary();

  showNewFilterModal.value = false;
}

const applyCallFiltersToSummary = () => {
    console.log("Loaded calls", loadedCalls.value);
    const unfilteredGroupedCalls = maxmizedCallsGroupedByUser.value;
    showMoreForGrouped.value = true;
    maxmizedCallsGroupedByUser.value = {};
    minimizedCallsGroupedByUser.value = {};
    getTotalCalls.value = 0;
    getTotalRevenue.value = 0;

    // Iterate loadedCalls first and then grouped calls to match the both user ids
    // if matched add the user group to the maxmizedCallsGroupedByUser
    for (const [key, value] of loadedCalls.value.entries()) {
        Object.values(unfilteredGroupedCalls).forEach(group => {
            if (group.userId === value.user_id) {

                getTotalCalls.value ++;
                getTotalRevenue.value += parseFloat(value.amount_spent);

                maxmizedCallsGroupedByUser.value[group.userId] = group;
                minimizedCallsGroupedByUser.value[group.userId] = group;
            }
        });
    }
}

const removeFiltersForSummary = () => {
    //populate maximized and minimized calls after removing the filter
    minimizedCallsGroupedByUserArray.value = Object.entries(props.callsGroupedByUser).slice(0, 2);
    console.log("Minimized Calls Grouped By User: ", minimizedCallsGroupedByUserArray.value)
    maxmizedCallsGroupedByUser.value = Object.fromEntries(callsGroupedByUserArray);
    showMoreForGrouped.value = false;

    // reset total calls and revenue
    getTotalCalls.value = props.totalCalls;
    getTotalRevenue.value = props.totalRevenue;
}

let removeFilter = async (index) => {
  appliedFilters.value.splice(index, 1);

  removeFiltersForSummary();

  await fetchCalls(true);
}

let operatorsForTheSelectedFilter = computed(() => {
  let filter = filters.value.filter((f) => f.name === filterName.value)[0];
  return filter.operators;
});

let inputTypeForTheSelectedFilter = computed(() => {
  let filter = filters.value.filter((f) => f.name === filterName.value)[0];
  return filter.inputType;
});


let dateFilterFrom = ref(null);
let dateFilterTo = ref(null);

let clearDateFilter = () => {
  dateFilterFrom.value = null;
  dateFilterTo.value = null;

  fetchCalls(true);
  removeFiltersForSummary();
}

let applyDateFilter = async () => {

  console.log("Date Filter From: ", dateFilterFrom.value);
  console.log("Date Filter To: ", dateFilterTo.value);

  await fetchCalls(true);
  applyCallFiltersToSummary();
}

onMounted(() => {
  fetchCalls();
});


let applyDatePreset = label => {
  const today = new Date();
  let from, to;

  switch(label) {
    case "Today":
      from = to = formatDate(today);
      break;
    case "Yesterday":
      let yesterday = new Date(today);
      yesterday.setDate(yesterday.getDate() - 1);
      from = to = formatDate(yesterday);
      break;
      case "Past 7 Days":
      let pastWeekStart = new Date(today);
      pastWeekStart.setDate(pastWeekStart.getDate() - 6); // 6 days ago from today
      from = formatDate(pastWeekStart);
      to = formatDate(today); // today's date
      break;
    case "Past 30 Days":
      let pastMonthStart = new Date(today);
      pastMonthStart.setDate(pastMonthStart.getDate() - 29); // 29 days ago from today
      from = formatDate(pastMonthStart);
      to = formatDate(today); // today's date
      break;
  }

  dateFilterFrom.value = from;
  dateFilterTo.value = to;
};

function formatDate(date) {
  let d = new Date(date),
      month = '' + (d.getMonth() + 1),
      day = '' + d.getDate(),
      year = d.getFullYear();

  if (month.length < 2)
      month = '0' + month;
  if (day.length < 2)
      day = '0' + day;

  return [year, month, day].join('-');
}

</script>

<template>
  <Head title="Calls" />
  <AuthenticatedLayout>

      <div class="pt-14 px-16 flex items-center mb-2">

          <Popover class="relative mr-2">
              <PopoverButton>
                  <button
                      type="button"
                      class="rounded shadow mr-2 px-3 py-1 bg-gray-100 hover:bg-gray-50 text-gray-800 text-md flex items-center text-sm"
                  >
                      <span v-if="!(dateFilterFrom && dateFilterTo)">Any Date</span>
                      <span v-if="dateFilterFrom && dateFilterTo">
                        <span class="font-bold">Range:</span> {{ dateFilterFrom }} - {{ dateFilterTo }}
                      </span>
                  </button>
              </PopoverButton>

              <PopoverPanel class="absolute z-10">
                  <div class="border border-gray-100 p-3 shadow bg-gray-50 mt-2">

                      <div class="flex items-center justify-between">
                          <div class="mr-2">
                              <label class="block mb-2 text-sm font-medium text-gray-900">From:</label>
                              <input v-model="dateFilterFrom"
                                     style="background-color: #E8F0FE;"
                                     class="bg-custom-blue text-sm rounded-lg focus:ring-blue-500 border border-transparent focus:border focus:border-blue-500 block w-full p-2.5 text-black outline-none" type="date">
                          </div>
                          <div class="mr-2">
                              <div class="w-3 h-0.5 bg-gray-200 mt-6"></div>
                          </div>
                          <div>
                              <label class="block mb-2 text-sm font-medium text-gray-900">To:</label>
                              <input v-model="dateFilterTo"
                                     style="background-color: #E8F0FE;"
                                     class="bg-custom-blue text-sm rounded-lg focus:ring-blue-500 border border-transparent focus:border focus:border-blue-500 block w-full p-2.5 text-black outline-none" type="date">
                          </div>
                      </div>

                      <div @click.prevent="applyDatePreset('Today')"
                           class="text-sm hover:bg-gray-50 bg-gray-100 p-3 flex items-center w-full my-3 rounded shadow border border-gray-200 cursor-pointer">
                          Today
                      </div>
                      <div @click.prevent="applyDatePreset('Yesterday')"
                           class="text-sm hover:bg-gray-50 bg-gray-100 p-3 flex items-center w-full my-3 rounded shadow border border-gray-200 cursor-pointer">
                          Yesterday
                      </div>
                      <div @click.prevent="applyDatePreset('Past 7 Days')"
                           class="text-sm hover:bg-gray-50 bg-gray-100 p-3 flex items-center w-full my-3 rounded shadow border border-gray-200 cursor-pointer">
                          Past 7 Days
                      </div>
                      <div @click.prevent="applyDatePreset('Past 30 Days')"
                           class="text-sm hover:bg-gray-50 bg-gray-100 p-3 flex items-center w-full my-3 rounded shadow border border-gray-200 cursor-pointer">
                          Past 30 Days
                      </div>

                      <PrimaryButton @click.prevent="applyDateFilter"
                                     class="w-full text-center flex justify-center text-md mb-4">Apply</PrimaryButton>

                      <button class="w-full text-center flex justify-center items-center text-md px-4 py-3 border rounded-md font-semibold text-md uppercase tracking-widest transition ease-in-out duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 hover:bg-white hover:text-custom-blue"
                              @click.prevent="clearDateFilter"
                              :class="{
                                  'border-transparent text-gray-900 bg-gray-100 hover:drop-shadow-2xl ': true,
                                }">
                          Clear Date
                      </button>
                  </div>
              </PopoverPanel>
          </Popover>
          <div
              v-for="(filter, index) in appliedFilters"
              :key="filter.name"
              class="rounded shadow mr-2 px-3 py-1 bg-gray-100 hover:bg-gray-50 text-gray-800 text-md cursor-pointer flex items-center"
          >
              <span class="font-bold mr-2">{{ filter.label }}</span>
              <span class="mr-2">{{ filter.operator }}</span>
              <span class="font-bold mr-2">{{ filter.value }}</span>

              <span class="cursor-pointer" @click.prevent="removeFilter(index)">&#x2715;</span>
          </div>

          <button
              class="rounded shadow mr-2 px-3 py-1 bg-gray-100 hover:bg-gray-50 text-gray-800 text-md flex items-center text-sm"
              @click.prevent="showNewFilterModal = true"
          >
              + Add Filter
          </button>
      </div>

      <Modal
          :show="showNewFilterModal"
          @close="showNewFilterModal = false"
          :closeable="true"
      >
          <div class="bg-gray-100 py-4 px-6 text-gray-900">
              <div class="flex justify-between mb-6">
                  <h3 class="text-2xl font-bold">Add New Filter</h3>
                  <span class="cursor-pointer" @click.prevent="showNewFilterModal = false">&#x2715</span>
              </div>

              <div class="mb-3">
                  <label class="block mb-2 text-sm font-medium text-gray-900">Filter:</label>

                  <select v-model="filterName" class="select-custom border border-gray-200">
                      <option v-for="(filter, index) in filters" :key="index" :value="filter.name">{{ filter.label }}</option>
                  </select>
              </div>

              <div class="mb-3">
                  <label class="block mb-2 text-sm font-medium text-gray-900">Operator:</label>

                  <select v-model="filterOperator" class="select-custom border border-gray-200">
                      <option v-for="(operator, index) in operatorsForTheSelectedFilter" :key="index">{{ operator }}</option>
                  </select>
              </div>

              <div class="mb-3">
                  <label class="block mb-2 text-sm font-medium text-gray-900">Value:</label>

                  <div v-if="inputTypeForTheSelectedFilter === 'number'">
                      <TextInput class="border-gray-200" v-model="filterValue" type="number" />
                  </div>

                  <div v-if="inputTypeForTheSelectedFilter === 'text'">
                      <TextInput class="border-gray-200" v-model="filterValue" type="text" />
                  </div>

                  <div v-if="inputTypeForTheSelectedFilter === 'email'">
                      <TextInput class="border-gray-200" v-model="filterValue" type="text" />
                  </div>

                  <div v-if="inputTypeForTheSelectedFilter === 'select'">
                      <select v-model="filterValue" class="select-custom border border-gray-200">
                          <option v-for="(option, index) in filters.filter((f) => f.name === filterName)[0].inputTypeOptions" :key="index" :value="option.value">{{ option.label }}</option>
                      </select>
                  </div>
              </div>

              <div class="flex items-center justify-end mt-4">
                  <PrimaryButton class="mr-2" @click.prevent="applyFilter">Apply</PrimaryButton>

                  <button
                      class="inline-flex items-center px-4 py-3 border rounded-md font-semibold text-md uppercase tracking-widest transition ease-in-out duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 hover:bg-white hover:text-custom-blue"
                      :class="{
              'border-transparent text-gray-900 bg-gray-100 hover:drop-shadow-2xl ': true,
            }"
                      :disabled="disabled"
                      @click.prevent="showNewFilterModal = false"
                  >
                      Cancel
                  </button>
              </div>
          </div>
      </Modal>

      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
          <div class="px-4 sm:px-8 sm:rounded-lg">
              <hr class="mb-4" />
          </div>
      </div>

    <div class="pt-12 flex justify-between px-16">
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


    <section class="py-3 sm:py-5">
      <div class="px-4 mx-auto max-w-screen-2xl lg:px-12">
        <div class="relative overflow-hidden bg-white sm:rounded-lg">
          <div
            class="flex flex-col px-4 py-3 space-y-3 lg:flex-row lg:items-center lg:justify-between lg:space-y-0 lg:space-x-4"
          >
            <div class="flex items-center flex-1 space-x-4">
              <h5>
                <span class="text-gray-500">Total Calls: </span>
                <span class="">{{ getTotalCalls }}</span>
              </h5>
              <h5>
                <span class="text-gray-500">Total Revenue: </span>
                <span class="">${{ getTotalRevenue.toFixed(2) }}</span>
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
