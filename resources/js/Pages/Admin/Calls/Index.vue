<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Edit from "@//Pages/Admin/User/Edit.vue";
import { Head, router, usePage, useForm } from "@inertiajs/vue3";
import ClientDetailsModal from "@/Components/ClientDetailsModal.vue";
import { ref, onMounted } from "vue";
import { toaster } from "@/helper.js";
import Modal from "@/Components/Modal.vue";
import SearchFilter from "@/Components/SearchFilter.vue";
import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";
import { format, parse } from "date-fns";
import PrimaryButton from "@/Components/PrimaryButton.vue";

import {
  endOfMonth,
  endOfYear,
  startOfMonth,
  subDays,
  startOfYear,
  subMonths,
  startOfWeek,
  endOfWeek,
  subWeeks,
  startOfQuarter,
  endOfQuarter,
  subQuarters,
} from "date-fns";

let page = usePage();
if (page.props.flash.message) {
  toaster("success", page.props.flash.message);
}

let props = defineProps({
  calls: {
    type: Object,
  },
  requestData: Array,
});

const presetDates = ref([
  { label: "Today", value: [new Date(), new Date()] },
  {
    label: "Today (Slot)",
    value: [new Date(), new Date()],
    slot: "preset-date-range-button",
  },
  { label: "This month", value: [startOfMonth(new Date()), endOfMonth(new Date())] },
  {
    label: "Last month",
    value: [startOfMonth(subMonths(new Date(), 1)), endOfMonth(subMonths(new Date(), 1))],
  },
  { label: "This year", value: [startOfYear(new Date()), endOfYear(new Date())] },
  {
    label: "Last 7 Days",
    value: [subDays(new Date(), 6), new Date()],
  },
  {
    label: "Last 14 Days",
    value: [subDays(new Date(), 13), new Date()],
  },
  {
    label: "Last 30 Days",
    value: [subDays(new Date(), 29), new Date()],
  },
  {
    label: "This Week",
    value: [startOfWeek(new Date()), endOfWeek(new Date())],
  },
  {
    label: "Last Week",
    value: [startOfWeek(subWeeks(new Date(), 1)), endOfWeek(subWeeks(new Date(), 1))],
  },
]);
let dateRange = ref([]);
let form = ref({
  status: props.requestData.status || "Select an status",
  // sortOptions:props.requestData.sortColumn+'-'+props.requestData.sortOrder  || 'Sort By',
  sortOptions:
    props.requestData.sortColumn && props.requestData.sortOrder
      ? `${props.requestData.sortColumn}-${props.requestData.sortOrder}`
      : "Sort By",
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
const watchFromTo = () => {
  if (props.requestData.from && props.requestData.to) {
    const fromDate = new Date(props.requestData.from);
    const toDate = new Date(props.requestData.to);
    dateRange.value.push(formatDate(fromDate), formatDate(toDate));
  }
};

onMounted(() => {
  watchFromTo();
});
let firstStepErrors = ref({});
let slidingLoader = ref(false);

let fetchCalls = (page) => {
  // Create URL object from page
  let url = new URL(page);

  // Ensure the protocol is https
  if (url.protocol !== "https:") {
    url.protocol = "https:";
  }

  // Get the https URL as a string
  let httpsPage = url.toString();

  router.visit(httpsPage, { method: "get" });
};

let showModal = ref(false);
let selectedCall = ref(null);

let openClientModal = (call) => {
  selectedCall.value = call;
  showModal.value = true;
};
let isLoading = ref(false);
const formattedFrom = ref(null);
const formattedTo = ref(null);
let dateFormat = () => {
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
  formattedFrom.value = `${fromMonth}/${fromDate}/${fromYear}`;
  formattedTo.value = `${toMonth}/${toDate}/${toYear}`;
};

let fetchData = () => {
  isLoading.value = true;
  if (dateRange.value.length > 0) {
    dateFormat();
  }
  const [sortColumn, sortOrder] = form.value.sortOptions.split("-");
  const normalizedSortColumn =
    sortColumn === "Sort By" || typeof sortColumn === "undefined" ? null : sortColumn;
  const normalizedSortOrder = typeof sortOrder === "undefined" ? null : sortOrder;

  const queryParams = {
    from: formattedFrom.value,
    to: formattedTo.value,
    status: form.value.status === "Select an status" ? "" : form.value.status,
    sortColumn: normalizedSortColumn,
    sortOrder: normalizedSortOrder,
  };

  router.visit("/admin/calls", {
    data: queryParams,
  });
};
let isLoadingReset = ref(false);
let ClearFilter = () => {
  isLoadingReset.value = true;
  router.visit("/admin/calls");
};
let maxDate = ref(new Date());
maxDate.value.setHours(23, 59, 59, 999);
let paginate = (url) => {
  router.visit(url);
};

let publisherName = ref(null);
let publisherId = ref(null);
let publisherModal = ref(false);
let openPublisherDetails = (call) => {
  publisherName.value = null;
  publisherId.value = null;

  console.log("Open publisher info for call " + call.id);

  axios
    .get("/admin/web-api/publisher-info/" + call.id)
    .then((response) => {
      publisherModal.value = true;

      if (
        response.data.ringbaCallLogs.report &&
        response.data.ringbaCallLogs.report.records.length
      ) {
        // Set state for publisher info
        publisherName.value =
          response.data.ringbaCallLogs.report.records[0].publisherName;
        publisherId.value = response.data.ringbaCallLogs.report.records[0].publisherId;
      } else {
        console.log("No publisher found for call " + call.id);
      }
    })
    .catch((error) => {
      console.log(error);
    });
};
</script>

<template>
  <Head title="Client Information" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Calls
      </h2>
    </template>

    <Modal :show="publisherModal" :closeable="true" @close="publisherModal = false">
      <div class="p-4">
        <h3 class="text-lg font-semibold mb-4 text-center">Publisher Details</h3>
        <ul class="space-y-3" v-if="publisherName && publisherId">
          <li class="flex items-center">
            <span class="text-gray-600 mr-2">Publisher Name:</span>
            <span class="text-gray-800">{{ publisherName }}</span>
          </li>
          <li class="flex items-center">
            <span class="text-gray-600 mr-2">Publisher ID:</span>
            <span class="text-gray-800">{{ publisherId }}</span>
          </li>
        </ul>

        <div class="flex justify-end mt-3">
          <PrimaryButton @click.prevent="publisherModal = false">Close</PrimaryButton>
        </div>
      </div>
    </Modal>

    <div class="pt-14">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="px-4 sm:px-8 sm:rounded-lg">
          <div class="text-4xl text-custom-sky font-bold mb-6">Calls</div>
          <hr class="mb-4" />
        </div>
      </div>
    </div>
    <div class="px-16">
      <div
        class="mx-auto grid grid-cols-1 md:grid-cols-2 sm:grid-cols-1 lg:grid-cols-3 gap-6 mb-8"
      >
        <div>
          <VueDatePicker
            v-model="dateRange"
            range
            :preset-dates="presetDates"
            placeholder="Picker date range"
            format="dd-MMM-yyyy"
            :maxDate="maxDate"
            :multi-calendars="{ solo: true }"
            auto-apply
          >
          </VueDatePicker>
        </div>
        <div>
          <select
            v-model="form.status"
            id="countries"
            class="bg-white-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white"
          >
            <option selected disabled>Select an status</option>
            <option value="paid">Paid</option>
            <option value="unpaid">Not Paid</option>
          </select>
          <div
            v-if="firstStepErrors.status"
            class="text-red-500"
            v-text="firstStepErrors.status[0]"
          ></div>
        </div>
        <div>
          <select
            v-model="form.sortOptions"
            id="countries"
            class="bg-white-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:text-white"
          >
            <option selected disabled>Sort By</option>
            <option value="call_duration_in_seconds-desc">
              Call Duration (Higher to Lower)
            </option>
            <option value="call_duration_in_seconds-asc">
              Call Duration (Lower to Higher)
            </option>
          </select>
          <div
            v-if="firstStepErrors.sortColumn"
            class="text-red-500"
            v-text="firstStepErrors.sortColumn[0]"
          ></div>
        </div>
        <div>
          <PrimaryButton
            type="button"
            class="ml-2"
            :disabled="isLoading"
            @click.prevent="fetchData"
          >
            <global-spinner :spinner="isLoading" /> Filter
          </PrimaryButton>
          <button
            @click.prevent="ClearFilter()"
            type="button"
            class="button-custom-back px-4 py-3 rounded-md ml-2"
          >
            <global-spinner :spinner="isLoadingReset" /> Reset
          </button>
        </div>
      </div>
    </div>

    <section class="p-3">
      <div class="mx-auto max-w-screen-xl sm:px-12">
        <div class="relative sm:rounded-lg overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-400 table-responsive">
              <thead class="text-xs text-gray-300 uppercase bg-sky-900">
                <tr>
                  <th scope="col" class="px-4 py-2">ID</th>
                  <th scope="col" class="px-4 py-2" style="min-width: 250px">
                    Call Date
                  </th>
                  <th scope="col" class="px-4 py-2" style="min-width: 150px">Name</th>
                  <th scope="col" class="px-4 py-2" style="min-width: 150px">Role</th>
                  <th scope="col" class="px-4 py-2">Ring Duration</th>
                  <th scope="col" class="px-4 py-2">Connected Duration</th>
                  <th scope="col" class="px-4 py-2">Hang Up By</th>

                  <th scope="col" class="px-4 py-2">Revenue</th>
                  <th scope="col" class="px-4 py-2" style="min-width: 130px">
                    Disposition
                  </th>
                  <th scope="col" class="px-4 py-2" style="min-width: 130px">Vertical</th>
                  <th scope="col" class="px-4 py-2" style="min-width: 100px">
                    Caller ID
                  </th>
                  <th scope="col" class="px-4 py-2" style="min-width: 160px">URL</th>
                  <th scope="col" class="px-4 py-2 text-end" style="min-width: 110px">
                    Actions
                  </th>
                </tr>
              </thead>

              <tbody v-if="calls.data.length">
                <tr
                  v-for="call in calls.data"
                  :key="call.id"
                  class="border-b border-gray-500"
                >
                  <td class="text-gray-600">{{ call.id }}</td>
                  <th class="text-gray-600">{{ call.call_taken }}</th>
                  <td class="text-gray-600">
                    {{ call.user.first_name }} {{ call.user.last_name }}
                  </td>
                  <td class="text-gray-600">
                    <span
                      v-if="call.role === 'Internal Agent'"
                      class="bg-indigo-100 text-indigo-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded"
                      >Internal Agent</span
                    >
                    <span
                      v-else
                      class="bg-purple-100 text-purple-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded"
                      >Regular User</span
                    >
                  </td>

                  <td class="text-gray-600">
                    {{
                      String(Math.floor(call.ringing_duration / 60)).padStart(2, "0") +
                      ":" +
                      String(call.ringing_duration % 60).padStart(2, "0")
                    }}
                  </td>
                  <td class="text-gray-600">
                    {{
                      String(Math.floor(call.call_duration_in_seconds / 60)).padStart(
                        2,
                        "0"
                      ) +
                      ":" +
                      String(call.call_duration_in_seconds % 60).padStart(2, "0")
                    }}
                  </td>

                  <td class="text-gray-600">{{ call.hung_up_by }}</td>
                  <td class="text-gray-600">${{ call.amount_spent }}</td>
                  <td class="text-gray-600">
                    <div v-if="call.client">
                      {{ call.client.status }}
                    </div>
                    <div v-else>N/A</div>
                  </td>
                  <td class="text-gray-600">
                    {{ call.call_type.type }}
                  </td>
                  <td class="text-gray-600">{{ call.from }}</td>

                  <td class="text-gray-600">
                    <a
                      v-if="call.recording_url"
                      target="_blank"
                      :href="call.recording_url"
                      class="flex"
                      ><svg
                        xmlns="http://www.w3.org/2000/svg"
                        height="1.5em"
                        class="pr-1"
                        viewBox="0 0 512 512"
                      >
                        <path
                          d="M0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm256-96a96 96 0 1 1 0 192 96 96 0 1 1 0-192zm0 224a128 128 0 1 0 0-256 128 128 0 1 0 0 256zm0-96a32 32 0 1 0 0-64 32 32 0 1 0 0 64z"
                        /></svg
                      >Open Recording
                    </a>
                    <span v-else>_</span>
                  </td>
                  <td class="text-gray-700 px-4 py-3">
                    <Menu as="div" class="relative inline-block text-left">
                      <div>
                        <MenuButton
                          class="inline-flex justify-center rounded-md px-4 py-2 relative"
                          style="z-index: 1"
                        >
                          <svg
                            class="w-4 h-4 text-gray-800 dark:text-white"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 17 4"
                          >
                            <path
                              stroke="currentColor"
                              stroke-linecap="round"
                              stroke-width="2"
                              d="M2.49 2h.01m6 0h.01m5.99 0h.01"
                            />
                          </svg>
                        </MenuButton>
                      </div>

                      <transition
                        enter-active-class="transition duration-100 ease-out"
                        enter-from-class="transform scale-95 opacity-0"
                        enter-to-class="transform scale-100 opacity-100"
                        leave-active-class="transition duration-75 ease-in"
                        leave-from-class="transform scale-100 opacity-100"
                        leave-to-class="transform scale-95 opacity-0"
                      >
                        <MenuItems
                          class="absolute right-0 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                          style="z-index: 10"
                        >
                          <div class="px-1 py-1">
                            <MenuItem v-slot="{ active }">
                              <button
                                :class="[
                                  active ? 'bg-sky-900 text-white' : 'text-gray-900',
                                  'group flex w-full items-center rounded-md py-2 px-2 text-xs',
                                ]"
                                @click.prevent="openPublisherDetails(call)"
                              >
                                Open Publisher Details
                              </button>
                            </MenuItem>

                            <!-- <MenuItem v-slot="{ active }">
                            <button :class="[
                              active
                                ? 'bg-sky-900 text-white'
                                : 'text-gray-900',
                              'group flex w-full items-center rounded-md py-2 px-2 text-xs',
                            ]" @click.prevent="openClientModal(call)">
                              Open Client Details
                            </button>
                            </MenuItem>
                            <MenuItem v-slot="{ active }">
                            <button :class="[
                              active
                                ? 'bg-sky-900 text-white'
                                : 'text-gray-900',
                              'group flex w-full items-center rounded-md py-2 px-2 text-xs',
                            ]">
                              Open User Details
                            </button>
                            </MenuItem>
                            <MenuItem v-slot="{ active }">
                            <button :class="[
                              active
                                ? 'bg-sky-900 text-white'
                                : 'text-gray-900',
                              'group flex w-full items-center rounded-md py-2 px-2 text-xs',
                            ]">
                              Open Call Details
                            </button>
                            </MenuItem> -->
                          </div>
                        </MenuItems>
                      </transition>
                    </Menu>

                    <!-- <div
                      class="text-gray-900 hover:text-gray-800 cursor-pointer"
                      v-if="call.get_client"
                      @click="openClientModal(call)"
                    >
                      View Client
                    </div> -->
                  </td>
                </tr>
              </tbody>
              <tbody v-else>
                <tr>
                  <td class="text-center py-4 text-gray-600" colspan="12">
                    No data found.
                  </td>
                </tr>
              </tbody>
            </table>

            <nav class="flex justify-between my-4" v-if="calls.links">
              <div v-if="calls">
                <span class="text-sm text-gray-700">
                  Showing
                  <span class="font-semibold text-gray-900">{{ calls.from }}</span>
                  to
                  <span class="font-semibold text-gray-900">{{ calls.to }}</span> of
                  <span class="font-semibold text-gray-900">{{ calls.total }}</span>
                  Entries
                </span>
              </div>

              <ul class="inline-flex -space-x-px text-base h-10">
                <li
                  v-for="(link, index) in calls.links"
                  :key="link.label"
                  :class="{ disabled: link.url === null }"
                >
                  <a
                    href="#"
                    @click.prevent="paginate(link.url)"
                    :class="[
                      'flex items-center justify-center px-4 h-10 border border-gray-300',
                      link.active
                        ? 'text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-700'
                        : 'leading-tight text-gray-500 bg-white hover:bg-gray-100 hover:text-gray-700',
                      {
                        'rounded-l-lg': index === 0,
                        'rounded-r-lg': index === calls.links.length - 1,
                      },
                    ]"
                    v-html="link.label"
                  ></a>
                </li>
              </ul>
            </nav>
            <br />
          </div>
        </div>
      </div>
    </section>
  </AuthenticatedLayout>
</template>

<style>
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

input[type="number"] {
  -moz-appearance: textfield;
}

.multiselect {
  color: black !important;
  border: none;
  border-radius: 10px;
}

.multiselect-wrapper {
  background-color: #d7d7d7;
  border-radius: 5px;
}

.box-shadow {
  padding: 20px;
  width: 97%;
  margin: auto;
  box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
}

.button-custom-back {
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  font-weight: 600;
  border-width: 1px;
  align-items: center;
  display: inline-flex;
  border-color: rgb(107 114 128 / var(--tw-border-opacity));
}

.button-custom-back:hover {
  background-color: #03243d;
  color: #3cfa7a;
  transition-duration: 150ms;
}

.dp__pointer {
  height: 42px !important;
  border-radius: 8px !important;
}
</style>
