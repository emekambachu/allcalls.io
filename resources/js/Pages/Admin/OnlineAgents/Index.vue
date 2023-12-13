<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { onMounted, ref, computed } from "vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import Multiselect from "@vueform/multiselect";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Popover, PopoverButton, PopoverPanel } from "@headlessui/vue";

const props = defineProps({
  onlineUsers: {
    type: Array,
  },
  onlineStats: Array,
  filters: Array,
});
let isLoading = ref(false);
let isLoadingReset = ref(false);

let selectedStates = ref([]);

if (
  props.filters &&
  props.filters.state_filteration &&
  props.filters.state_filteration.length > 0
) {
  selectedStates.value = props.filters.state_filteration.map(Number);
}

let stateOptions = computed(() => {
  return Object.values(props.onlineStats).map((state) => {
    return {
      value: state.id,
      label: state.name,
    };
  });
});

const refreshPage = () => {
  if (usePage().url === "/admin/online-agents") {
    router.visit("/admin/online-agents", {
      preserveScroll: true,
    });
  }
};
let fetchData = () => {
  isLoading.value = true;
  router.visit("/admin/online-agents", {
    preserveScroll: true,
    data: {
      state_filteration: selectedStates.value,
    },
  });
};
let ResetPage = () => {
  isLoadingReset.value = true;
  router.visit("/admin/online-agents", {
    preserveScroll: true,
  });
};
onMounted(() => {
  console.log("Registering event listener");
  window.Echo.channel("active-users-events")
    .listen("OnlineUserListUpdated", () => {
      console.log("OnlineUserListUpdated!");
      refreshPage();
    })
    .listen("CallStatusUpdated", () => {
      console.log("CallStatusUpdated!");
      refreshPage();
    });
});
let formatDate = (date) => {
  if (!date) {
    return "";
  }

  const dateObj = new Date(date);

  const formattedDate = dateObj.toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "numeric",
    minute: "numeric",
  });

  return formattedDate;
};

let removeAgent = (userId) => {
  router.visit(`/admin/online-agents/${userId}`, {
    method: "DELETE",
    preserveScroll: true,
  });
};
let deviceName = ref(null);
let getDeviceType = (userAgent) => {
  if (userAgent) {
    if (userAgent?.devices_details !== "Desktop") {
      if (userAgent?.user_agent.includes("iPhone")) {
        deviceName.value = "iPhone";
      } else if (userAgent?.user_agent.includes("Android")) {
        deviceName.value = "Android";
      }
    } else {
      deviceName.value = "Desktop";
    }
  } else {
    deviceName.value = "Desktop";
  }
};

let formatMoney = (money) => {
  return "$" + new Intl.NumberFormat().format(money);
};

let minimizedArray = (arr) => {
  // check if the elements are more than 2
  // if so, return the first two elements
  // otherwise, return the array as it is
  return arr.length > 2 ? arr.slice(0, 2) : arr;
};
</script>

<template>
  <Head title="Online Agents" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-2xl font-semibold">Online Agents</h2>
    </template>
    <div class="py-14">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 rounded-lg bg-white">
          <h3 class="text-4xl text-custom-sky font-bold mb-6">Online Agents</h3>
          <hr class="mb-4" />
          <div
            v-for="(state, index) in onlineStats"
            :key="state.id"
            :class="{
              'bg-green-200 hover:bg-green-300 text-black-800 border border-green-400':
                state.user_count > 1,
            }"
            class="bg-blue-100 hover:bg-blue-200 mb-6 cursor-pointer text-blue-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-blue-400 border border-blue-400 inline-flex items-center justify-center"
          >
            {{ state.name }} ({{ state.user_count }})
          </div>
          <div class="mb-4 grid lg:grid-cols-2 mb-2 md:grid-cols-2 sm:grid-cols-1 gap-4">
            <Multiselect
              v-model="selectedStates"
              :options="stateOptions"
              track-by="value"
              label="label"
              mode="tags"
              :close-on-select="false"
              placeholder="Choose States"
            >
            </Multiselect>
            <div>
              <PrimaryButton type="button" class="ml-2" @click.prevent="fetchData">
                <global-spinner :spinner="isLoading" /> Filter
              </PrimaryButton>
              <button
                @click.prevent="ResetPage()"
                type="button"
                class="button-custom-back px-4 py-3 rounded-md ml-2"
              >
                <global-spinner :spinner="isLoadingReset" /> Reset
              </button>
            </div>
          </div>

          <!-- The Table Header -->
          <table class="w-full text-sm text-left text-gray-400">
            <thead class="text-xs text-gray-300 uppercase bg-sky-900">
              <tr>
                <th scope="col" class="px-4 py-3">User ID</th>
                <th scope="col" class="px-4 py-3">First Name</th>
                <th scope="col" class="px-4 py-3">Last Name</th>
                <th scope="col" class="px-4 py-3">Email</th>
                <th scope="col" class="px-4 py-3">Balance</th>
                <th scope="col" class="px-4 py-3">States</th>
                <th scope="col" class="px-4 py-3">Call Status</th>
                <th scope="col" class="px-4 py-3">Last call taken</th>
                <th scope="col" class="px-4 py-3">Listening For</th>
                <th scope="col" class="px-4 py-3">Actions</th>
              </tr>
            </thead>
            <tbody>
              <!-- The Table Body -->
              <tr
                v-for="onlineUser in onlineUsers"
                :key="onlineUser.id"
                class="border-b border-gray-500"
              >
                <td class="text-gray-600 px-4 py-3">
                  {{ onlineUser.user.id }}
                </td>
                <td class="text-gray-600 px-4 py-3">
                  {{ onlineUser.user.first_name }}
                </td>
                <td class="text-gray-600 px-4 py-3">
                  {{ onlineUser.user.last_name }}
                </td>
                <td class="text-gray-600 px-4 py-3">
                  {{ onlineUser.user.email }}
                </td>
                <td class="text-gray-600 px-4 py-3">
                  {{ formatMoney(onlineUser.user.balance) }}
                </td>
                <td class="text-gray-600 px-4 py-3">
                  <div class="flex items-center">
                    <div
                      v-for="(state, index) in minimizedArray(onlineUser.user.states)"
                      :key="state.id"
                      class="bg-blue-100 hover:bg-blue-200 mb-2 text-blue-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-blue-400 border border-blue-400 inline-flex items-center justify-center"
                    >
                      {{ state.name }}
                    </div>

                    <div v-if="onlineUser.user.states.length > 2">
                      <Popover class="relative mr-2">
                        <PopoverButton>
                          <svg
                            class="w-3 h-3 text-gray-800 cursor-pointer"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor"
                            viewBox="0 0 16 3"
                          >
                            <path
                              d="M2 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm6.041 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM14 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Z"
                            />
                          </svg>
                        </PopoverButton>

                        <PopoverPanel class="absolute z-10 w-40 -left-20">
                          <div class="bg-white p-2 border border-gray-100 shadow flex justify-center items-center">
                            <div
                              v-for="(state, index) in onlineUser.user.states"
                              :key="state.id"
                              class="bg-blue-100 hover:bg-blue-200 mb-2 text-blue-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-blue-400 border border-blue-400 inline-flex items-center justify-center"
                            >
                              {{ state.name }}
                            </div>
                          </div>
                        </PopoverPanel>
                      </Popover>
                    </div>
                  </div>
                </td>
                <td class="text-gray-600 px-4 py-3">
                  <span
                    :class="`${getStatusBadge(
                      onlineUser.user.call_status
                    )} text-white text-xs px-2 py-1 rounded-2xl`"
                  >
                    {{ onlineUser.user.call_status }}
                  </span>
                </td>
                <td class="text-gray-600 px-4 py-3">
                  {{
                    onlineUser.user.last_called_at
                      ? formatDate(onlineUser.user.last_called_at)
                      : ""
                  }}
                </td>
                <td class="text-gray-600 px-4 py-3">
                  {{ onlineUser.call_type.type }}
                </td>

                <td class="text-gray-600 px-4 py-3">
                  <button
                    @click.prevent="removeAgent(onlineUser.user.id)"
                    class="text-white bg-red-500 px-3 py-1.5 rounded-lg flex items-center shadow hover:bg-red-400"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      class="w-4 h-4 mr-1"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                      />
                    </svg>

                    Remove
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script>
export default {
  methods: {
    getStatusBadge(status) {
      console.log("status", status);
      switch (status) {
        case "Waiting":
          return "bg-green-600";
        case "Ringing":
          return "bg-yellow-400";
        case "Talking":
          return "bg-red-400";
        default:
          return "bg-gray-400";
      }
    },
  },
};
</script>

<style src="@vueform/multiselect/themes/default.css"></style>
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
  border: none !important;
  border-radius: 10px;
}

.multiselect-wrapper {
  background-color: #d7d7d7;
  border-radius: 5px;
  height: 49px !important;
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
</style>
