<script setup>
import {computed, onMounted, ref} from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, Link, router, usePage} from "@inertiajs/vue3";
import {toaster} from "@/helper.js";
import GlobalSpinner from "@/Components/GlobalSpinner.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import Modal from "@/Components/Modal.vue";
import CallLogs from "@/Components/CallLogs.vue";
import {Popover, PopoverButton, PopoverPanel, Tab, TabGroup, TabList, TabPanel, TabPanels} from "@headlessui/vue";

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
    callsGroupedByPublisherName: {
        type: Object,
        required: true,
    },
});

let columns = ref([
    // {
    //     // using serial number instead of ID for sorting purposes
    //     label: "SN",
    //     name: "serial_number",
    //     visible: true,
    //     sortable: true,
    //     render(call) {
    //         return call.serial_number;
    //     },
    // },

    {
        label: "ID",
        name: "id",
        visible: false,
        sortable: true,
        render(call) {
            return call.id;
        },
    },

    {
        label: "Agent Name",
        name: "agent_name",
        visible: true,
        sortable: true,
        render(call) {
            return call.user !== null ? call.user.first_name+' '+call.user.last_name : '';
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
        label: "CallerID",
        name: "from",
        visible: true,
        sortable: true,
        render(call) {
            return call.from;
        },
    },

    {
        label: "Ring Duration",
        name: "ringing_duration",
        visible: true,
        sortable: true,
        render(call) {
            return call.ringing_duration;
        },
    },

    {
        label: "Call Length",
        name: "call_duration_in_seconds",
        visible: true,
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
        label: "Hung up by",
        name: "hung_up_by",
        visible: true,
        sortable: true,
        render(call) {
            return call.hung_up_by;
        },
    },

    {
        label: "Disposition",
        name: "disposition",
        visible: true,
        sortable: true,
        render(call) {
            return call?.client?.status;
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
        label: "Cost",
        name: "cost",
        visible: true,
        sortable: true,
        render(call) {
            return call.cost === '' || call.cost === null ? `$${0}` : `$${call.cost}`;
        },
    },

    {
        label: "Publisher Name",
        name: "publisher_name",
        visible: true,
        sortable: true,
        render(call) {
            return call.publisher_name;
        },
    },

    {
        label: "Pub ID",
        name: "publisher_id",
        visible: true,
        sortable: true,
        render(call) {
            return call.publisher_id;
        },
    },

    {
        label: "Role",
        name: "role",
        visible: true,
        sortable: true,
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
        label: "Vertical",
        name: "vertical",
        visible: true,
        sortable: true,
        render(call) {
            return call.call_type.type;
        },
    },

    {
        label: "Recording URL",
        name: "recording_url",
        visible: false,
        sortable: false,
        render(call) {
            return call.recording_url;
        },
    },

    {
        label: "User Email",
        name: "user_email",
        visible: false,
        sortable: true,
        render(call) {
          return call.user_email;
        },
    },

]);

const agentColumns = ref([
    {
        label: "Agent Name",
        name: "agentName",
        visible: true,
        render(call) {
            return call.agentName;
        },

    },

    {
        label: "Total Calls",
        name: "totalCalls",
        visible: true,
        render(call) {
            return call.totalCalls;
        },
    },

    {
        label: "Paid Calls",
        name: "paidCalls",
        visible: true,
        render(call) {
            return call.paidCalls;
        },
    },

    {
        label: "Revenue Earned",
        name: "revenueEarned",
        visible: true,
        render(call) {
            return call.revenueEarned;
        },
    },

    {
        label: "Revenue Per Call",
        name: "revenuePerCall",
        visible: true,
        render(call) {
            return call.revenuePerCall.toFixed(2);
        },
    },

    {
        label: "Total Call Length",
        name: "totalCallLength",
        visible: true,
        render(call) {
            return String(Math.floor(call.totalCallLength / 60)).padStart(2, "0") +
                ":" +
                String(call.totalCallLength % 60).padStart(2, "0");
        },
    },

    {
        label: "Average Call Length",
        name: "averageCallLength",
        visible: true,
        render(call) {
            return call.averageCallLength.toFixed(2);
        },
    },
]);

const publisherColumns = ref([
    {
        label: "Publisher Name",
        name: "publisher_name",
        visible: true,
        render(call) {
            return call.publisher_name;
        },
    },

    {
        label: "Total Calls",
        name: "totalCalls",
        visible: true,
        render(call) {
            return call.totalCalls;
        },
    },

    {
        label: "Paid Calls",
        name: "paidCalls",
        visible: true,
        render(call) {
            return call.paidCalls;
        },
    },

    {
        label: "Revenue Earned",
        name: "revenueEarned",
        visible: true,
        render(call) {
            return call.revenueEarned;
        },
    },

    {
        label: "Revenue Per Call",
        name: "revenuePerCall",
        visible: true,
        render(call) {
            return call.revenuePerCall;
        },
    },

    {
        label: "Total Call Length",
        name: "totalCallLength",
        visible: true,
        render(call) {
            return call.totalCallLength;
        },
    },

    {
        label: "Average Call Length",
        name: "averageCallLength",
        visible: true,
        render(call) {
            return call.averageCallLength;
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
let sortDirection = ref("desc");
let loading = ref(false);
let currentPage = ref(1);
const getAllCalls = ref([]);
const getTotalCalls = ref(props.totalCalls);
const getTotalRevenue = ref(props.totalRevenue);
const getTotalCallResults = ref(0);
const getTotalRevenueResults = ref(0);
const paginate = ref({
    currentPage: currentPage.value,
    perPage: null,
});


let fetchCalls = async (replace = false, per_page = null) => {
  let url = "/admin/web-api/calls?page=" + currentPage.value;

  if (sortColumn.value) {
    url += "&sort_column=" + sortColumn.value;
  }

  if (sortDirection.value) {
    url += "&sort_direction=" + sortDirection.value;
  }

  if (dateFilterFrom.value && dateFilterTo.value) {
    url += "&start_date=" + dateFilterFrom.value;
    url += "&end_date=" + dateFilterTo.value;
  }

  if(per_page !== null) {
      url += "&per_page=" + per_page;
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
  getAllCalls.value = response.data.all_calls;
  getTotalCallResults.value = response.data.total;
  getTotalRevenueResults.value = response.data.total_revenue;

  // keep track of current page and pagination
  paginate.value.perPage = response.data.per_page;
  paginate.value.currentPage = currentPage.value;
  console.log({
      currentPage: paginate.value.currentPage,
      perPage: paginate.value.perPage,
  });

  loading.value = false;
  console.log("Loaded Calls: ", loadedCalls.value);
  console.log("Total Call Results: ", getTotalCallResults.value);
  console.log("Total Revenue: ", getTotalRevenueResults.value);
  console.log("Grouped Publisher Names", callsGroupedByPublisherName.value);
};


let loadMore = async () => {
  //currentPage.value++;

   // Reason for this solution: Sorting is done on every load more on the backend and applied to only per_pages records.
    // therefore it is a bug that causes the records on the table to not be sorted correctly.
   // Will need to implement a better solution that handles the sorting on the frontend and not backend

   // On every load more, increase per_page by 100, which is the default per_page on the backend
  let per_page;
  let new_per_page = parseInt(paginate.value.perPage) + 100;
  if(getTotalCalls.value > new_per_page){
      per_page = new_per_page;
  }else{
      per_page = getTotalCalls.value;
  }

  await fetchCalls(true, per_page);
};

let sortByColumn = async (column) => {
  console.log("Sort By Column: ", column.name);

  if (!column.sortable) return;

  sortColumn.value = column.name;

  if (sortDirection.value === "asc") {
    sortDirection.value = "desc";
  } else {
    sortDirection.value = "asc";
  }

  await fetchCalls(true, paginate.value.perPage);
};

let callsGroupedByUserArray = Object.entries(props.callsGroupedByUser);
let minimizedCallsGroupedByUserArray = callsGroupedByUserArray.slice(0, 2);
let minimizedCallsGroupedByUser = ref(
  Object.fromEntries(minimizedCallsGroupedByUserArray)
);
let maxmizedCallsGroupedByUser = ref(Object.fromEntries(callsGroupedByUserArray));
let showMoreForGrouped = ref(false);

const callsGroupedByPublisherNameArray = Object.entries(props.callsGroupedByPublisherName);
const callsGroupedByPublisherName = ref(Object.fromEntries(callsGroupedByPublisherNameArray));

console.log("Mini Calls Grouped By User: ", minimizedCallsGroupedByUser.value);
console.log("Max Calls Grouped By User: ", maxmizedCallsGroupedByUser.value);

let groupedCalls = computed(() => {
  if (showMoreForGrouped.value === true) {
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

const publisherFooterRow = computed(() => {
    let totalCalls = 0;
    let totalPaidCalls = 0;
    let totalRevenue = 0;
    let totalCallLength = 0;

    for (const publisher in callsGroupedByPublisherName.value) {
        const publisherData = callsGroupedByPublisherName.value[publisher];
        totalCalls += publisherData.totalCalls || 0;
        totalPaidCalls += publisherData.paidCalls || 0;
        totalRevenue += publisherData.revenueEarned || 0;
        totalCallLength += publisherData.totalCallLength || 0;
    }

    let averageCallLength = totalCalls > 0 ? totalCallLength / totalCalls : 0;
    let revenuePerCall = totalCalls > 0 ? totalRevenue / totalCalls : 0;

    return {
        publisher_name: "Totals",
        totalCalls,
        paidCalls: totalPaidCalls,
        revenueEarned: totalRevenue,
        revenuePerCall,
        totalCallLength,
        averageCallLength,
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

// export to be sent to backend
const exportSearchResults = computed(() => {
    return {
        totalCalls: getTotalCallResults.value,
        totalRevenue: getTotalRevenueResults.value,
        columns: columns.value.reduce((arr, col) => {
            if (col.visible === true) {
                arr.push({label: col.label, name: col.name});
            }
            return arr;
        }, []),
    };
})

let currentlyPlayingAudio = ref(null);
let currentlyPlayingAudioCallId = ref(null);

let playRecording = (call) => {

  // Stop any recording currently playing
  // stopPlayingRecording();

  currentlyPlayingAudio.value = new Audio(call.recording_url);
  currentlyPlayingAudio.value.play();
  currentlyPlayingAudioCallId.value = call.id;

  console.log("Playing Recording: ", {
    call_url: call.recording_url,
    currently_playing: currentlyPlayingAudio.value
  });

  currentlyPlayingAudio.value.addEventListener('loadedmetadata', () => {

    let duration = currentlyPlayingAudio.value.duration;
    let minutes = Math.floor(duration / 60);
    let seconds = Math.floor(duration % 60);
    seconds = seconds < 10 ? '0' + seconds : seconds; // Add leading zero if needed
    console.log(`Duration: ${minutes}:${seconds}`);

    document.getElementById('audio-duration'+call.id).textContent = `Duration: ${minutes}:${seconds}`;

  });

  // Assuming audio is your Audio element
  // Also when the audio is ended, pause and clear the audio element
  currentlyPlayingAudio.value.addEventListener("ended", () => {
    stopPlayingRecording();
  });
};

const stopPlayingRecording = (call) => {
  currentlyPlayingAudio.value.pause();
  currentlyPlayingAudio.value = null;
  currentlyPlayingAudioCallId.value = null;
};

function fastForwardRecording(seconds) {
    if (currentlyPlayingAudio && currentlyPlayingAudio.value) {
        // Check if the desired fast-forward time is more than the current time
        const newTime = currentlyPlayingAudio.value.currentTime + seconds;
        if (newTime < currentlyPlayingAudio.value.duration) {
            currentlyPlayingAudio.value.currentTime = newTime;
        } else {
            // If the fast-forward time exceeds the duration
            currentlyPlayingAudio.value.currentTime = currentlyPlayingAudio.value.duration;
        }
    }
}

let filters = ref([

    {
        label: "ID",
        name: "id",
        operators: ["is", "is greater than", "is less than", "is greater than or equal to", "is less than or equal to"],
        inputType: "number",
    },

    {
        label: "Agent Name",
        name: "agent_name",
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
        label: "Hung up by",
        name: "hung_up_by",
        operators: ["is"],
        inputType: "text"
    },

    {
        label: "Disposition",
        name: "disposition",
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
        label: "Cost",
        name: "cost",
        operators: ["is", "is greater than", "is less than", "is greater than or equal to", "is less than or equal to"],
        inputType: "number"
    },

    {
        label: "Publisher Name",
        name: "publisher_name",
        operators: ["is"],
        inputType: "text"
    },

    {
        label: "Publisher ID",
        name: "publisher_id",
        operators: ["is"],
        inputType: "text"
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

    console.log("Loaded calls before filter", getAllCalls.value);
    console.log("Grouped Publisher Names before filter", callsGroupedByPublisherName.value);
    console.log("Maximised calls grouped by user before apply", maxmizedCallsGroupedByUser.value);

    // Populate grouped calls when filter button is clicked
    maxmizedCallsGroupedByUser.value = Object.fromEntries(callsGroupedByUserArray);
    minimizedCallsGroupedByUser.value = Object.fromEntries(minimizedCallsGroupedByUserArray);
    callsGroupedByPublisherName.value = Object.fromEntries(callsGroupedByPublisherNameArray);

    // assigned grouped calls to new variables
    const unfilteredGroupedCalls = maxmizedCallsGroupedByUser.value;
    const unfilteredGroupedPublisherNames = callsGroupedByPublisherName.value;
    showMoreForGrouped.value = true;

    // Clear the grouped calls to be filled with filtered results
    maxmizedCallsGroupedByUser.value = {};
    minimizedCallsGroupedByUser.value = {};
    callsGroupedByPublisherName.value = {};

    // update with results
    getTotalCalls.value = getTotalCallResults.value;
    getTotalRevenue.value = getTotalRevenueResults.value;

    // Iterate loadedCalls first and then grouped calls to match the both user ids
    // if matched add the user group to the maxmizedCallsGroupedByUser
    let count = 0;
    for (const [key, value] of getAllCalls.value.entries()) {

        Object.values(unfilteredGroupedCalls).forEach(group => {
            if (group.userId === value.user_id) {
                maxmizedCallsGroupedByUser.value[group.userId] = group;
                if(count < 2){
                    minimizedCallsGroupedByUser.value[group.userId] = group;
                }
                count++;
            }
        });

        Object.values(unfilteredGroupedPublisherNames).forEach(publisher => {
            if (publisher.user_ids.includes(value.user_id)) {
                callsGroupedByPublisherName.value[publisher.publisher_name] = publisher;
            }
        });
    }

    console.log("Loaded calls after filter", loadedCalls.value);
    console.log("Grouped Publisher Names after filter", callsGroupedByPublisherName.value);
    console.log("Maximised calls grouped by user after apply", maxmizedCallsGroupedByUser.value);
}

const removeFiltersForSummary = () => {
    //populate maximized and minimized calls after removing the filter
    showMoreForGrouped.value = false;

    minimizedCallsGroupedByUserArray = callsGroupedByUserArray.slice(0, 2);
    minimizedCallsGroupedByUser.value = Object.fromEntries(minimizedCallsGroupedByUserArray);
    maxmizedCallsGroupedByUser.value = Object.fromEntries(callsGroupedByUserArray);

    callsGroupedByPublisherName.value = Object.fromEntries(callsGroupedByPublisherNameArray);

    // console.log("Minimized Calls Grouped By User: ", minimizedCallsGroupedByUserArray.value);

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

let dateFilterFrom = ref(new Date().toISOString().split('T')[0]);
let dateFilterTo = ref(new Date().toISOString().split('T')[0]);

let clearDateFilter = () => {
  dateFilterFrom.value = null;
  dateFilterTo.value = null;

  fetchCalls(true);
  removeFiltersForSummary();
}

let applyDateFilter = async (close) => {

  console.log("Date Filter From: ", dateFilterFrom.value);
  console.log("Date Filter To: ", dateFilterTo.value);

  await fetchCalls(true);
  applyCallFiltersToSummary();
  close();
}

onMounted(async () => {
    await fetchCalls();
    applyCallFiltersToSummary();
});


let applyDatePreset = (label) => {
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


let showLogsForCallId = ref(null);
let showLogsForCallModal = ref(false);
let openDetailedLogs = callId => {
  showLogsForCallId.value = callId;
  showLogsForCallModal.value = true;
}
let disposition = ref('Select a disposition')
let showDispositionModal = ref(false)
let call_id = ref(null)
let colIndex = ref(null)
let addDisposition = (call , index) => {
  firstStepErrors.value.disposition = [``];
  showDispositionModal.value = true
  call_id.value = call.id
  colIndex.value = index
  if(call?.client?.status){
    disposition.value = call?.client?.status
  }else{
    disposition.value = 'Select a disposition'
  }
}

let firstStepErrors = ref({})
let disposition_loader = ref(false)
let saveChanges = () => {
  firstStepErrors.value.disposition = [``];
  disposition_loader.value = true
  if(!disposition.value || disposition.value == 'Select a disposition' ){
    console.log('error');
    firstStepErrors.value.disposition = [`The disposition is required.`];
    disposition_loader.value = false
    console.log('firstStepErrors.value',firstStepErrors.value);
    return
  }
  axios.post('/admin/calls/disposition',{
    disposition:disposition.value,
    call_id:call_id.value
  })
  .then((res)=>{
    console.log('res',res);
    toaster("success", res.data.message);
    router.visit('/admin/calls')
    disposition_loader.value = false
    showDispositionModal.value = false
    // loadedCalls.value[colIndex.value].disposition = res.data.call.disposition;
    // console.log('loadedCalls.value[colIndex.value]',loadedCalls.value[colIndex.value].disposition);
  }).catch((error)=>{
    if(error.response.status == 400){
      toaster("error", error.response.data.errors);
    }else{
      toaster("error", error.message);
    }
    // console.log('error',error);
    disposition_loader.value = false
  })
}


// Autocomplete filter selections
const allowedAutoCompleteFilterNames = ref([
    "user_email",
    "agent_name",
    "publisher_name",
    "publisher_id",
    "disposition",
    "id"

])
const filterAutoCompleteDropdown = ref(false);

// Check if filter name is allowed for auto complete
const toggleFilterAutocompleteDropdown = (event, filterName) => {
    filterAutoCompleteDropdown.value = false;
    console.log('Filter name: ', filterName);
    if(allowedAutoCompleteFilterNames.value.includes(filterName)){
        event.stopPropagation(); // Prevent click from propagating
        filterAutoCompleteDropdown.value = true;
    }
}

const filteredCallRecords = computed(() => {
    if (filterValue.value === '') {
        return [];
    }
    let matches = 0;
    return getAllCalls.value.filter(call => {
        // Check if any of the specified fields contain the filter value
        const filterMatch = [call.user_email, call.agent_name, call.publisher_name, call.publisher_id, call.disposition, call.id]
            .some(field => field && field.toString().toLowerCase().includes(filterValue.value.toLowerCase()));

        if (filterMatch && matches < 10) {
            matches++;
            return true;
        }
    });
});

const selectFilteredResult = (event, selected, filterName) => {
    filterAutoCompleteDropdown.value = false;

    if(allowedAutoCompleteFilterNames.value.includes(filterName)){
        filterValue.value = selected;
    }else{
        filterValue.value = '';
    }
}

const getAutoCompleteFilterOptions = async (keyword) => {
    await axios.get(`/web-api/calls/autocomplete`, {
        headers: {
            'Accept' : 'application/json',
        },
        params: {
            keyword: keyword
        }
    }).then((response) => {
        if (response.data.success) {
            console.log(response.data.data);

        } else {
            console.log(response.data.data);
        }

    }).catch((error) => {
        console.log(error);
    });
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
                      <span class="font-extrabold text-lg font-sans" v-if="!(dateFilterFrom && dateFilterTo)">Select Date Range</span>
                      <span class="font-extrabold text-lg font-sans" v-if="dateFilterFrom && dateFilterTo">
                        <span>Selected Date Range:</span> {{ dateFilterFrom }} - {{ dateFilterTo }}
                      </span>
                  </button>
              </PopoverButton>

              <PopoverPanel class="absolute z-10" v-slot="{ close }">
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

                      <PrimaryButton @click.prevent="applyDateFilter(close)"
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
              class="rounded shadow mr-2 px-3 py-1 bg-gray-100 hover:bg-gray-50 text-gray-800 text-md flex items-center font-extrabold text-lg font-sans"
              @click.prevent="showNewFilterModal = true"
          >
              + Add Filter
          </button>
      </div>


      <Modal
        :show="showLogsForCallModal"
        @close="showLogsForCallModal = false; showLogsForCallId = null"
        :closeable="true"
      >
        <CallLogs :callId="showLogsForCallId" />
      </Modal>

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
                      <TextInput
                          class="border-gray-200"
                          v-model="filterValue"
                          type="number"
                          @keyup="toggleFilterAutocompleteDropdown($event, filterName)"
                      />

                      <!-- Dropdown for Filtered List -->
                      <div v-if="filterAutoCompleteDropdown" class="border rounded max-h-60 overflow-y-auto">
                          <div v-for="call in filteredCallRecords"
                               :key="call.id"
                               class="p-2 hover:bg-gray-100 cursor-pointer dropdown-container"
                               @click="selectFilteredResult(
                                  $event,
                                  call[filterName],
                                  filterName
                                  )"
                          >
                              {{ call[filterName] }}
                          </div>
                      </div>
                  </div>

                  <div v-if="inputTypeForTheSelectedFilter === 'text'">
                      <TextInput
                          class="border-gray-200"
                          v-model="filterValue"
                          type="text"
                          role="button"
                          @keyup="toggleFilterAutocompleteDropdown($event, filterName)"
                      />

                      <!-- Dropdown for Filtered List -->
                      <div v-if="filterAutoCompleteDropdown" class="border rounded max-h-60 overflow-y-auto">
                          <div v-for="call in filteredCallRecords"
                              :key="call.id"
                              class="p-2 hover:bg-gray-100 cursor-pointer dropdown-container"
                              @click="selectFilteredResult(
                                  $event,
                                  call[filterName],
                                  filterName
                                  )"
                          >
                              {{ call[filterName] }}
                          </div>
                      </div>
                  </div>

                  <div v-if="inputTypeForTheSelectedFilter === 'email'">
                      <TextInput
                          class="border-gray-200"
                          v-model="filterValue"
                          type="text"
                          @keyup="toggleFilterAutocompleteDropdown($event, filterName)"
                      />

                      <!-- Dropdown for Filtered List -->
                      <div v-if="filterAutoCompleteDropdown" class="border rounded max-h-60 overflow-y-auto">
                          <div v-for="call in filteredCallRecords"
                               :key="call.id"
                               class="p-2 hover:bg-gray-100 cursor-pointer dropdown-container"
                               @click="selectFilteredResult(
                                  $event,
                                  call[filterName],
                                  filterName
                                  )"
                          >
                              {{ call[filterName] }}
                          </div>
                      </div>
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

      <Modal
          :show="showDispositionModal"
          @close="showDispositionModal = false"
          :closeable="true"
      >
          <div class="bg-gray-100 py-4 px-6 text-gray-900">
              <div class="flex justify-between mb-6">
                  <h3 class="text-2xl font-bold">Update  Disposition</h3>
                  <span class="cursor-pointer" @click.prevent="showDispositionModal = false">&#x2715</span>
              </div>

              <div class="mb-3">
                  <label class="block mb-2 text-sm font-medium text-gray-900">Disposition:</label>

                  <select v-model="disposition" class="select-custom border border-gray-200">
                      <!-- <option v-for="(filter, index) in filters" :key="index" :value="filter.name">{{ filter.label }}</option> -->
                      <option disabled value="Select a disposition">Select a disposition</option>
                      <option value="Sale - Simplified Issue">Sale - Simplified Issue</option>
                      <option value="Sale - Guaranteed Issue">Sale - Guaranteed Issue</option>
                      <option value="Follow Up Needed">Follow Up Needed</option>
                      <option value="Quoted - Not Interested">Quoted - Not Interested</option>
                      <option value="Not Interested">Not Interested</option>
                      <option value="Transfer Handoff Too Long">Transfer Handoff Too Long</option>
                      <option value="Client Hung Up">Client Hung Up</option>
                      <option value="No Income">No Income</option>
                      <option value="Wrong State">Wrong State</option>
                      <option value="Not Qualified Age">Not Qualified Age</option>
                      <option value="Not Qualified Nursing Home">Not Qualified Nursing Home</option>
                      <option value="Not Qualified Memory Issues">Not Qualified Memory Issues</option>
                      <option value="Language Barrier">Language Barrier</option>
                      <option value="Do Not Call">Do Not Call</option>
                      <option value="Dead Air/No Response">Dead Air/No Response</option>
                      <option value="Thought Was Free">Thought Was Free</option>
                  </select>
                  <div v-if="firstStepErrors.disposition" class="text-red-500 mt-1" v-text="firstStepErrors.disposition[0]"></div>

              </div>


              <div class="flex items-center justify-end mt-4">
                  <PrimaryButton :disabled=" disposition_loader" class="mr-2"
                                 @click.prevent="saveChanges">
                      <global-spinner :spinner="disposition_loader" /> Save Changes</PrimaryButton>

                  <button
                      class="inline-flex items-center px-4 py-3 border rounded-md font-semibold text-md uppercase tracking-widest transition ease-in-out duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 hover:bg-white hover:text-custom-blue"
                      :class="{
                              'border-transparent text-gray-900 bg-gray-100 hover:drop-shadow-2xl ': true,
                            }"
                      :disabled="disabled"
                      @click.prevent="showDispositionModal = false"
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

    <div class="px-4 max-w-screen-3xl lg:px-12">
        <TabGroup>
          <TabList class="flex space-x-1 rounded-xl bg-blue-500/20 p-1">
              <Tab
                  v-slot="{ selected }"
                  class="w-2/4">
                  <button
                      :class="[
                      'w-full rounded-lg py-2.5 text-sm font-medium leading-5 p-2',
                      'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2',
                      selected
                        ? 'bg-white text-blue-700 shadow'
                        : 'text-gray-900 hover:bg-white',
                    ]"
                  >
                      Agents
                  </button>
              </Tab>

              <Tab v-slot="{ selected }" class="w-2/4">
                  <button
                      :class="[
                      'w-full rounded-lg py-2.5 text-sm font-medium leading-5',
                      'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2',
                      selected
                        ? 'bg-white text-blue-700 shadow'
                        : 'text-gray-900 hover:bg-white',
                    ]"
                  >
                      Publishers
                  </button>
              </Tab>
          </TabList>

          <TabPanels class="mt-2">
              <TabPanel
                  :class="[
                    'rounded-xl bg-white p-3',
                    'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2',
                  ]"
              >
                  <div class="overflow-hidden bg-white sm:rounded-lg" :class="{'height-600': loadedCalls.length <= 14}">
                      <!--Grouped Agent calls-->
                      <div class="flex justify-end">
                          <Popover class="relative mr-2">
                              <PopoverButton>
                                  <button
                                      type="button"
                                      class="flex items-center justify-center flex-shrink-0 px-3 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200"
                                  >
                                      Show Agent Columns
                                  </button>
                              </PopoverButton>

                              <PopoverPanel class="z-10 w-40 -left-20">
                                  <div class="absolute border border-gray-100 p-3 shadow bg-white mt-2">
                                      <div
                                          class="flex items-center mb-4"
                                          v-for="(column, index) in agentColumns"
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
                      </div>

                      <div class="overflow-x-auto">
                          <table class="w-full text-sm text-left text-gray-500">
                              <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr class="cursor-pointer">

                                  <th
                                      scope="col"
                                      class="px-4 py-3 whitespace-nowrap"
                                      v-for="(column, index) in agentColumns"
                                      :key="index"
                                      v-show="column.visible"
                                  >
                                      {{ column.label }}
                                  </th>
                              </tr>
                              </thead>
                              <tbody v-if="loadedCalls.length > 0">
                                  <tr
                                      v-for="(userData, userId) in groupedCalls"
                                      :key="userId"
                                      class="border-b hover:bg-gray-100"
                                  >
                                      <td
                                          v-for="(column, colIndex) in agentColumns"
                                          :key="colIndex"
                                          class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"
                                          v-show="column.visible"
                                          v-text="renderColumn(column, userData)"
                                      >
                                      </td>
                                  </tr>

                                  <tr class="bg-gray-100 border-b" v-if="showMoreForGrouped">
                                        <td
                                            v-for="(column, colIndex) in agentColumns"
                                            :key="colIndex"
                                            class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"
                                            v-show="column.visible"
                                            v-text="renderColumn(column, summaryFooterRow)"
                                        ></td>

<!--                                      <td-->
<!--                                          class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"-->
<!--                                          v-text="summaryFooterRow.agentName"-->
<!--                                      ></td>-->
<!--                                      <td-->
<!--                                          class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"-->
<!--                                          v-text="summaryFooterRow.totalCalls"-->
<!--                                      ></td>-->
<!--                                      <td-->
<!--                                          class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"-->
<!--                                          v-text="summaryFooterRow.paidCalls"-->
<!--                                      ></td>-->
<!--                                      <td-->
<!--                                          class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"-->
<!--                                          v-text="`$${summaryFooterRow.revenueEarned}`"-->
<!--                                      ></td>-->
<!--                                      <td-->
<!--                                          class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"-->
<!--                                          v-text="`$${summaryFooterRow.revenuePerCall.toFixed(2)}`"-->
<!--                                      ></td>-->
<!--                                      <td-->
<!--                                          class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"-->
<!--                                          v-text="`${summaryFooterRow.totalCallLength.toFixed(2)}`"-->
<!--                                      ></td>-->
<!--                                      <td-->
<!--                                          class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"-->
<!--                                          v-text="`${summaryFooterRow.averageCallLength.toFixed(2)}`"-->
<!--                                      ></td>-->
<!--                                      <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"></td>-->
                                  </tr>

                              </tbody>
                              <tbody v-else>
                                <tr>
                                    <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap text-center text-lg" colspan="8">
                                        No data available
                                    </td>
                                </tr>
                              </tbody>
                          </table>

                          <div v-if="loadedCalls.length > 0" class="flex justify-center mt-4">
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
              </TabPanel>

              <TabPanel
                  :class="[
                    'rounded-xl bg-white p-3',
                    'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2',
                  ]"
              >
                  <div class="overflow-hidden bg-white sm:rounded-lg" :class="{'height-600': loadedCalls.length <= 14}">
                      <!--Grouped Publishers-->
                      <div class="flex justify-end">
                          <Popover class="relative mr-2">
                              <PopoverButton>
                                  <button
                                      type="button"
                                      class="flex items-center justify-center flex-shrink-0 px-3 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200"
                                  >
                                      Show Publisher Columns
                                  </button>
                              </PopoverButton>

                              <PopoverPanel class="z-10 w-40 -left-20">
                                  <div class="absolute border border-gray-100 p-3 shadow bg-white mt-2">
                                      <div
                                          class="flex items-center mb-4"
                                          v-for="(column, index) in publisherColumns"
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
                                          >{{ column.label }}
                                          </label>
                                      </div>
                                  </div>
                              </PopoverPanel>
                          </Popover>
                      </div>

                      <div class="overflow-x-auto">
                          <table class="w-full text-sm text-left text-gray-500">
                              <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr class="cursor-pointer">
                                    <th
                                        scope="col"
                                        class="px-4 py-3 whitespace-nowrap"
                                        v-for="(column, index) in publisherColumns"
                                        :key="index"
                                        v-show="column.visible"
                                    >
                                        {{ column.label }}
                                    </th>

<!--                                  <th scope="col" class="px-4 py-3 whitespace-nowrap">Publisher Name</th>-->
<!--                                  <th scope="col" class="px-4 py-3 whitespace-nowrap">Total Calls</th>-->
<!--                                  <th scope="col" class="px-4 py-3 whitespace-nowrap">Paid Calls</th>-->
<!--                                  <th scope="col" class="px-4 py-3 whitespace-nowrap">Revenue Earned</th>-->
<!--                                  <th scope="col" class="px-4 py-3 whitespace-nowrap">-->
<!--                                      Revenue Per Call-->
<!--                                  </th>-->
<!--                                  <th scope="col" class="px-4 py-3 whitespace-nowrap">-->
<!--                                      Total Call Length-->
<!--                                  </th>-->
<!--                                  <th scope="col" class="px-4 py-3 whitespace-nowrap">-->
<!--                                      Average Call Length-->
<!--                                  </th>-->
<!--                                  <th scope="col" class="px-4 py-3 whitespace-nowrap">Actions</th>-->
                              </tr>
                              </thead>
                              <tbody v-if="loadedCalls.length > 0">
                                <tr
                                  v-for="(publisher, index) in callsGroupedByPublisherName"
                                  :key="index"
                                  class="border-b hover:bg-gray-100"
                              >
                                    <td
                                        v-for="(column, colIndex) in publisherColumns"
                                        :key="colIndex"
                                        class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"
                                        v-show="column.visible"
                                        v-text="renderColumn(column, publisher)"
                                    ></td>

<!--                                  <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">-->
<!--                                      {{ publisher.publisher_name }}-->
<!--                                  </td>-->
<!--                                  <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">-->
<!--                                      {{ publisher.totalCalls }}-->
<!--                                  </td>-->
<!--                                  <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">-->
<!--                                      {{ publisher.paidCalls }}-->
<!--                                  </td>-->
<!--                                  <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">-->
<!--                                      ${{ publisher.revenueEarned }}-->
<!--                                  </td>-->
<!--                                  <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">-->
<!--                                      ${{ publisher.revenuePerCall.toFixed(2) }}-->
<!--                                  </td>-->
<!--                                  <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">-->
<!--                                      {{-->
<!--                                          String(Math.floor(publisher.totalCallLength / 60)).padStart(2, "0") +-->
<!--                                          ":" +-->
<!--                                          String(publisher.totalCallLength % 60).padStart(2, "0")-->
<!--                                      }}-->
<!--                                  </td>-->
<!--                                  <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">-->
<!--                                      {{ publisher.averageCallLength.toFixed(2) }} sec-->
<!--                                  </td>-->
<!--                                  <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">-->
<!--                                  </td>-->
                              </tr>

                              <tr class="bg-gray-100 border-b">
                                      <td
                                          v-for="(column, colIndex) in publisherColumns"
                                          :key="colIndex"
                                          class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"
                                          v-show="column.visible"
                                          v-text="renderColumn(column, publisherFooterRow)"
                                      ></td>
<!--                                  <td-->
<!--                                      class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"-->
<!--                                      v-text="publisherFooterRow.publisherName"-->
<!--                                  ></td>-->
<!--                                  <td-->
<!--                                      class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"-->
<!--                                      v-text="publisherFooterRow.totalCalls"-->
<!--                                  ></td>-->
<!--                                  <td-->
<!--                                      class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"-->
<!--                                      v-text="publisherFooterRow.paidCalls"-->
<!--                                  ></td>-->
<!--                                  <td-->
<!--                                      class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"-->
<!--                                      v-text="`$${publisherFooterRow.revenueEarned}`"-->
<!--                                  ></td>-->
<!--                                  <td-->
<!--                                      class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"-->
<!--                                      v-text="`$${publisherFooterRow.revenuePerCall.toFixed(2)}`"-->
<!--                                  ></td>-->
<!--                                  <td-->
<!--                                      class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"-->
<!--                                      v-text="`${publisherFooterRow.totalCallLength.toFixed(2)}`"-->
<!--                                  ></td>-->
<!--                                  <td-->
<!--                                      class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"-->
<!--                                      v-text="`${publisherFooterRow.averageCallLength.toFixed(2)}`"-->
<!--                                  ></td>-->
<!--                                  <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"></td>-->
                              </tr>
                              </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap text-center text-lg" colspan="8">
                                            No data available
                                        </td>
                                    </tr>
                                </tbody>
                          </table>

<!--                          <div class="flex justify-center mt-4">-->
<!--                              <button-->
<!--                                  @click.prevent="showMoreForGrouped = !showMoreForGrouped"-->
<!--                                  class="bg-gray-200 hover:bg-gray-100 text-gray-800 cursor-pointer px-4 py-2 text-sm rounded-md flex items-center"-->
<!--                              >-->
<!--                                  <span v-if="showMoreForGrouped">Show Less</span>-->
<!--                                  <span v-else>Show More</span>-->
<!--                              </button>-->
<!--                          </div>-->

                      </div>
                  </div>
              </TabPanel>
          </TabPanels>
      </TabGroup>
    </div>


<!--    <section class="py-3 sm:py-5">-->
<!--      <div class="px-4 mx-auto max-w-screen-2xl lg:px-12">-->
<!--        <div class="relative overflow-hidden bg-white sm:rounded-lg">-->
<!--          <div class="overflow-x-auto">-->
<!--            <table class="w-full text-sm text-left text-gray-500">-->
<!--              <thead class="text-xs text-gray-700 uppercase bg-gray-50">-->
<!--                <tr class="cursor-pointer">-->
<!--                  <th scope="col" class="px-4 py-3 whitespace-nowrap">Agent Name</th>-->
<!--                  <th scope="col" class="px-4 py-3 whitespace-nowrap">Total Calls</th>-->
<!--                  <th scope="col" class="px-4 py-3 whitespace-nowrap">Paid Calls</th>-->
<!--                  <th scope="col" class="px-4 py-3 whitespace-nowrap">Revenue Earned</th>-->
<!--                  <th scope="col" class="px-4 py-3 whitespace-nowrap">-->
<!--                    Revenue Per Call-->
<!--                  </th>-->
<!--                  <th scope="col" class="px-4 py-3 whitespace-nowrap">-->
<!--                    Total Call Length-->
<!--                  </th>-->
<!--                  <th scope="col" class="px-4 py-3 whitespace-nowrap">-->
<!--                    Average Call Length-->
<!--                  </th>-->
<!--                  <th scope="col" class="px-4 py-3 whitespace-nowrap">Actions</th>-->
<!--                </tr>-->
<!--              </thead>-->
<!--              <tbody>-->
<!--                <tr-->
<!--                  v-for="(userData, userId) in groupedCalls"-->
<!--                  :key="userId"-->
<!--                  class="border-b hover:bg-gray-100"-->
<!--                >-->
<!--                  <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">-->
<!--                    <Link-->
<!--                      :href="`/admin/customer/detail/${userId}`"-->
<!--                      class="text-blue-400 hover:text-blue-500 underline"-->
<!--                    >-->
<!--                      {{ userData.agentName }}-->
<!--                    </Link>-->
<!--                  </td>-->
<!--                  <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">-->
<!--                    {{ userData.totalCalls }}-->
<!--                  </td>-->
<!--                  <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">-->
<!--                    {{ userData.paidCalls }}-->
<!--                  </td>-->
<!--                  <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">-->
<!--                    ${{ userData.revenueEarned }}-->
<!--                  </td>-->
<!--                  <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">-->
<!--                    ${{ userData.revenuePerCall.toFixed(2) }}-->
<!--                  </td>-->
<!--                  <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">-->
<!--                    {{-->
<!--                      String(Math.floor(userData.totalCallLength / 60)).padStart(2, "0") +-->
<!--                      ":" +-->
<!--                      String(userData.totalCallLength % 60).padStart(2, "0")-->
<!--                    }}-->
<!--                  </td>-->
<!--                  <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">-->
<!--                    {{ userData.averageCallLength.toFixed(2) }} sec-->
<!--                  </td>-->
<!--                  <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">-->
<!--                  </td>-->
<!--                </tr>-->

<!--                <tr class="bg-gray-100 border-b" v-if="showMoreForGrouped">-->
<!--                  <td-->
<!--                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"-->
<!--                    v-text="summaryFooterRow.agentName"-->
<!--                  ></td>-->
<!--                  <td-->
<!--                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"-->
<!--                    v-text="summaryFooterRow.totalCalls"-->
<!--                  ></td>-->
<!--                  <td-->
<!--                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"-->
<!--                    v-text="summaryFooterRow.paidCalls"-->
<!--                  ></td>-->
<!--                  <td-->
<!--                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"-->
<!--                    v-text="`$${summaryFooterRow.revenueEarned}`"-->
<!--                  ></td>-->
<!--                  <td-->
<!--                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"-->
<!--                    v-text="`$${summaryFooterRow.revenuePerCall.toFixed(2)}`"-->
<!--                  ></td>-->
<!--                  <td-->
<!--                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"-->
<!--                    v-text="`${summaryFooterRow.totalCallLength.toFixed(2)}`"-->
<!--                  ></td>-->
<!--                  <td-->
<!--                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"-->
<!--                    v-text="`${summaryFooterRow.averageCallLength.toFixed(2)}`"-->
<!--                  ></td>-->
<!--                  <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"></td>-->
<!--                </tr>-->
<!--              </tbody>-->
<!--            </table>-->

<!--            <div class="flex justify-center mt-4">-->
<!--              <button-->
<!--                @click.prevent="showMoreForGrouped = !showMoreForGrouped"-->
<!--                class="bg-gray-200 hover:bg-gray-100 text-gray-800 cursor-pointer px-4 py-2 text-sm rounded-md flex items-center"-->
<!--              >-->
<!--                <span v-if="showMoreForGrouped">Show Less</span>-->
<!--                <span v-else>Show More</span>-->
<!--              </button>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->
<!--    </section>-->

    <div class="pt-14 flex justify-between px-16">
      <div>
        <div class="text-4xl text-custom-sky font-bold mb-6">Call Details</div>
      </div>
    </div>


    <section class="py-3 sm:py-5">
      <div class="px-4 max-w-screen-3xl lg:px-12">
        <div class="relative overflow-hidden bg-white sm:rounded-lg"
             :class="{'height-600': loadedCalls.length <= 14}">

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

                  <PopoverPanel class="z-10 w-40 -left-20">
                    <div class="absolute border border-gray-100 p-3 shadow bg-white mt-2">
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

                  <a :href="'/admin/calls/export/'+JSON.stringify(exportSearchResults)">
                      <button
                          type="button"
                          class="flex items-center justify-center flex-shrink-0 px-3 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200">
                          Export
                      </button>
                  </a>
              </div>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500" style="min-height: 50px;">
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

                      <span v-if="column.sortable && sortColumn && sortColumn === column.name">
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
              <tbody v-if="loadedCalls.length > 0">
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

                    <div class="flex items-center">
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
                        class="text-gray-800 mx-1 user-select-none"
                        v-if="currentlyPlayingAudioCallId === call.id">
                        Playing<br/>
                          <span :id="'audio-duration'+call.id"></span>
                      </p>

                    <svg version="1.0" xmlns="http://www.w3.org/2000/svg"
                         width="10.000000pt" viewBox="0 0 512.000000 512.000000"
                         preserveAspectRatio="xMidYMid meet"
                         v-if="currentlyPlayingAudioCallId === call.id"
                         @click.prevent="fastForwardRecording(5)">

                        <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none">
                            <path d="M2560 4469 c-70 -14 -163 -65 -210 -115 -23 -25 -56 -75 -73 -112 l-32 -67 -5 -421 -5 -422 -640 535 c-715 597 -712 595 -852 601 -100 5 -171 -15 -246 -67 -69 -49 -123 -122 -147 -201 -20 -64 -20 -91 -18 -1665 l3 -1600 33 -67 c108 -220 378 -295 573 -161 24 17 330 268 679 557 l635 526 5 -432 5 -433 28 -58 c40 -80 112 -151 194 -190 62 -29 77 -32 163 -32 85 0 102 3 160 31 51 24 260 192 935 753 479 397 896 750 929 783 170 177 188 446 42 641 -23 31 -60 70 -83 86 -22 16 -430 354 -907 750 -477 397 -889 732 -915 745 -46 24 -144 47 -185 45 -12 -1 -41 -5 -66 -10z m-1756 -361 c35 -29 373 -309 750 -623 l685 -570 0 -355 0 -355 -738 -614 c-406 -338 -746 -618 -755 -623 -23 -13 -61 -3 -86 22 -20 20 -20 40 -20 1570 l0 1551 25 24 c36 37 66 32 139 -27z m1882 30 c68 -52 1692 -1404 1726 -1436 72 -69 86 -164 35 -241 -20 -30 -1634 -1385 -1764 -1480 -36 -27 -70 -24 -103 9 -20 20 -20 40 -20 1570 l0 1551 25 24 c30 31 64 32 101 3z"/>
                        </g>
                    </svg>

                    </div>
                  </td>
                  <td class="px-4 flex  py-2 font-medium text-gray-900 whitespace-nowrap">
                    <svg @click.prevent="openDetailedLogs(call.id)" title="Open Detailed Logs" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 cursor-pointer text-gray-800 hover:text-gray-900">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <svg @click.prevent="addDisposition(call , colIndex)" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 cursor-pointer text-gray-800 hover:text-gray-900 ml-3">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>

                  </td>
                </tr>
              </tbody>
              <tbody v-else>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap text-center text-lg" colspan="8">
                        No data available
                    </td>
                </tr>
              </tbody>
            </table>

            <div
              v-if="(callsPaginator && callsPaginator.next_page_url && loadedCalls.length === paginate.perPage) || getTotalCalls !== loadedCalls.length"
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
