<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { onMounted, ref, computed } from "vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import Multiselect from "@vueform/multiselect";
import PrimaryButton from "@/Components/PrimaryButton.vue";

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
                <th scope="col" class="px-4 py-3 whitespace-nowrap">User ID</th>
                <th scope="col" class="px-4 py-3 whitespace-nowrap">First Name</th>
                <th scope="col" class="px-4 py-3 whitespace-nowrap">Last Name</th>
                <th scope="col" class="px-4 py-3 whitespace-nowrap">Email</th>
                <th scope="col" class="px-4 py-3 whitespace-nowrap">Balance</th>
                <th scope="col" class="px-4 py-3 whitespace-nowrap">States</th>
                <th scope="col" class="px-4 py-3 whitespace-nowrap">Call Status</th>
                <th scope="col" class="px-4 py-3 whitespace-nowrap">Last call taken</th>
                <th scope="col" class="px-4 py-3 whitespace-nowrap">Listening For</th>
                <th scope="col" class="px-4 py-3 whitespace-nowrap">last login device</th>
                <th scope="col" class="px-4 py-3 whitespace-nowrap">Actions</th>
              </tr>
            </thead>
            <tbody>
              <!-- The Table Body -->
              <tr
                v-for="onlineUser in onlineUsers"
                :key="onlineUser.id"
                class="border-b border-gray-500"
              >
                <td class="text-gray-600 px-4 py-3 whitespace-nowrap">
                  {{ onlineUser.user.id }}
                </td>
                <td class="text-gray-600 px-4 py-3 whitespace-nowrap">
                  {{ onlineUser.user.first_name }}
                </td>
                <td class="text-gray-600 px-4 py-3 whitespace-nowrap">
                  {{ onlineUser.user.last_name }}
                </td>
                <td class="text-gray-600 px-4 py-3 whitespace-nowrap">
                  {{ onlineUser.user.email }}
                </td>
                <td class="text-gray-600 px-4 py-3 whitespace-nowrap">
                  {{ formatMoney(onlineUser.user.balance) }}
                </td>
                <td class="text-gray-600 px-4 py-3 whitespace-nowrap">
                  <div
                    v-for="(state, index) in onlineUser.user.states"
                    :key="state.id"
                    class="bg-blue-100 hover:bg-blue-200 mb-2 text-blue-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-blue-400 border border-blue-400 inline-flex items-center justify-center"
                  >
                    {{ state.name }}
                  </div>
                </td>
                <td class="text-gray-600 px-4 py-3 whitespace-nowrap">
                  <span
                    :class="`${getStatusBadge(
                      onlineUser.user.call_status
                    )} text-white text-xs px-2 py-1 rounded-2xl`"
                  >
                    {{ onlineUser.user.call_status }}
                  </span>
                </td>
                <td class="text-gray-600 px-4 py-3 whitespace-nowrap">
                  {{
                    onlineUser.user.last_called_at
                      ? formatDate(onlineUser.user.last_called_at)
                      : ""
                  }}
                </td>
                <td class="text-gray-600 px-4 py-3 whitespace-nowrap">
                  {{ onlineUser.call_type.type }}
                </td>
                <td class="text-gray-600 px-4 py-3 whitespace-nowrap">
                  {{ getDeviceType(onlineUser.user?.latest_activity) }}
                  <div class="flex items-center justify-center">
                    <svg
                      v-if="deviceName === 'Desktop'"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      class="w-8 h-8"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25"
                      />
                    </svg>
                    <svg
                      v-if="deviceName === 'Android'"
                      class="w-8 h-8"
                      viewBox="-22.5 0 301 301"
                      version="1.1"
                      xmlns="http://www.w3.org/2000/svg"
                      xmlns:xlink="http://www.w3.org/1999/xlink"
                      preserveAspectRatio="xMidYMid"
                    >
                      <g>
                        <path
                          d="M78.3890161,0.858476242 C76.9846593,0.871877584 75.5269206,1.21067383 74.1988355,1.94683705 C69.9813154,4.28464966 68.4344792,9.70448752 70.7705059,13.9187887 L80.2936432,31.1148585 C57.3501835,45.3109605 42.146676,69.5583356 42.146676,97.23264 C42.146676,97.3488107 42.1463538,97.5233203 42.146676,97.6951925 C42.1467894,97.7558421 42.1461099,97.7904107 42.146676,97.8584397 C42.1467112,97.9488816 42.146676,98.0809536 42.146676,98.1033235 L42.146676,102.37513 C37.7401995,97.3051619 31.2627337,94.103607 24.0255064,94.103607 C10.766574,94.103607 0,104.870185 0,118.129121 L0,192.137501 C0,205.396437 10.766574,216.163015 24.0255064,216.163015 C31.2627337,216.163015 37.7401995,212.96146 42.146676,207.891492 L42.146676,218.258109 C42.146676,232.234601 53.5833566,243.671281 67.5598484,243.671281 L74.0083724,243.671281 L74.0083724,276.594135 C74.0083724,289.853131 84.774955,300.619649 98.0338856,300.619649 C111.292821,300.619649 122.0594,289.853131 122.0594,276.594135 L122.0594,243.671281 L133.215081,243.671281 L133.215081,276.594135 C133.215081,289.853131 143.981659,300.619649 157.240595,300.619649 C170.499522,300.619649 181.266118,289.853131 181.266118,276.594135 L181.266118,243.671281 L187.714637,243.671281 C201.691129,243.671281 213.127809,232.234601 213.127809,218.258109 L213.127809,207.891492 C217.534299,212.96146 224.011752,216.163015 231.248984,216.163015 C244.507919,216.163015 255.274498,205.396437 255.274498,192.137501 L255.274498,118.129121 C255.274498,104.870185 244.507919,94.103607 231.248984,94.103607 C224.011752,94.103607 217.534299,97.3051619 213.127809,102.37513 L213.127809,98.1849514 L213.127809,98.1033407 C213.128367,97.9723769 213.127955,97.8421262 213.127809,97.8584655 C213.129527,97.5976548 213.127809,97.3898395 213.127809,97.2326572 C213.127809,69.5631979 197.890397,45.339215 174.95363,31.1420821 L184.503985,13.918763 C186.840011,9.70446174 185.293178,4.28462389 181.075655,1.94681128 C179.747565,1.21064805 178.289834,0.871868993 176.885477,0.85845047 C173.770979,0.828641074 170.714038,2.4700306 169.103704,5.37514094 L159.118011,23.4146964 C149.353914,19.811505 138.730068,17.8368515 127.637245,17.8368515 C116.555726,17.8368515 105.912363,19.7912913 96.1564693,23.3874813 L86.1707769,5.37514094 C84.5604527,2.47002201 81.503506,0.828709799 78.3890161,0.85845047 L78.3890161,0.858476242 Z"
                          fill="#FFFFFF"
                        ></path>
                        <path
                          d="M24.0260725,100.361664 C14.1317,100.361664 6.25861893,108.234747 6.25861893,118.129121 L6.25861893,192.137501 C6.25861893,202.031875 14.1317,209.904958 24.0260725,209.904958 C33.9204441,209.904958 41.7935257,202.031875 41.7935257,192.137501 L41.7935257,118.129121 C41.7935257,108.234747 33.9204441,100.361664 24.0260725,100.361664 L24.0260725,100.361664 Z M231.249551,100.361664 C221.355176,100.361664 213.482094,108.234747 213.482094,118.129121 L213.482094,192.137501 C213.482094,202.031875 221.355176,209.904958 231.249551,209.904958 C241.143925,209.904958 249.016999,202.031875 249.016999,192.137501 L249.016999,118.129121 C249.016999,108.234747 241.143925,100.361664 231.249551,100.361664 L231.249551,100.361664 Z"
                          fill="#A4C639"
                        ></path>
                        <path
                          d="M98.0338856,184.818075 C88.1395114,184.818075 80.2664341,192.691157 80.2664341,202.585531 L80.2664341,276.593963 C80.2664341,286.488363 88.1395114,294.361308 98.0338856,294.361308 C107.92826,294.361308 115.801342,286.488363 115.801342,276.593963 L115.801342,202.585531 C115.801342,192.691157 107.92826,184.818075 98.0338856,184.818075 L98.0338856,184.818075 Z M157.240595,184.818075 C147.346221,184.818075 139.473138,192.691157 139.473138,202.585531 L139.473138,276.593963 C139.473138,286.488363 147.346221,294.361308 157.240595,294.361308 C167.134969,294.361308 175.008043,286.488363 175.008043,276.593963 L175.008043,202.585531 C175.008043,192.691157 167.134969,184.818075 157.240595,184.818075 L157.240595,184.818075 Z"
                          fill="#A4C639"
                        ></path>
                        <path
                          d="M78.4434341,7.11654228 C78.0234231,7.12083758 77.6320498,7.22919946 77.2462398,7.44304537 C75.9792855,8.14533584 75.5626532,9.60121987 76.2667168,10.8713836 L88.782836,33.4820338 C64.7023936,46.0117562 48.4373365,69.8232526 48.4047377,97.1510121 L206.869751,97.1510121 C206.837193,69.8232526 190.572096,46.0117562 166.491645,33.4820338 L179.007777,10.8713836 C179.711837,9.60121987 179.295201,8.14533584 178.02825,7.44304537 C177.642438,7.22919946 177.251067,7.1205455 176.831055,7.11654228 C175.931919,7.10786577 175.079646,7.55712 174.599912,8.42257181 L161.920533,31.2781058 C151.548297,26.6773219 139.914231,24.0949434 127.637245,24.0949434 C115.360249,24.0949434 103.726174,26.6773219 93.3539479,31.2781058 L80.6745686,8.42257181 C80.1948375,7.55712 79.3425576,7.10791732 78.4434341,7.11654228 L78.4434341,7.11654228 Z M48.4047377,103.40907 L48.4047377,218.258109 C48.4047377,228.870039 56.9479173,237.413214 67.5598484,237.413214 L187.714637,237.413214 C198.326576,237.413214 206.869751,228.870039 206.869751,218.258109 L206.869751,103.40907 L48.4047377,103.40907 L48.4047377,103.40907 Z"
                          fill="#A4C639"
                        ></path>
                        <path
                          d="M91.0681772,54.9226953 C87.4507168,54.9226953 84.4563973,57.9170105 84.4563973,61.5344795 C84.4563973,65.1519399 87.4507168,68.146255 91.0681772,68.146255 C94.6856376,68.146255 97.6799528,65.1519399 97.6799528,61.5344795 C97.6799528,57.9170105 94.6856376,54.9226953 91.0681772,54.9226953 L91.0681772,54.9226953 Z M164.205874,54.9226953 C160.588413,54.9226953 157.59409,57.9170105 157.59409,61.5344795 C157.59409,65.1519399 160.588413,68.146255 164.205874,68.146255 C167.823326,68.146255 170.817649,65.1519399 170.817649,61.5344795 C170.817649,57.9170105 167.823326,54.9226953 164.205874,54.9226953 L164.205874,54.9226953 Z"
                          fill="#FFFFFF"
                        ></path>
                      </g>
                    </svg>
                    <svg
                      v-if="deviceName === 'iPhone'"
                      class="w-12 h-12"
                      viewBox="0 0 24 24"
                      fill="none"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <g id="SVGRepo_bgCarrier" stroke-width="0" />
                      <g
                        id="SVGRepo_tracerCarrier"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                      />
                      <g id="SVGRepo_iconCarrier">
                        <path
                          d="M16.52 12.46C16.508 11.8438 16.6682 11.2365 16.9827 10.7065C17.2972 10.1765 17.7534 9.74476 18.3 9.46C17.9558 8.98143 17.5063 8.5883 16.9862 8.31089C16.466 8.03349 15.8892 7.87923 15.3 7.86C14.03 7.76 12.65 8.6 12.14 8.6C11.63 8.6 10.37 7.9 9.40999 7.9C7.40999 7.9 5.29999 9.49 5.29999 12.66C5.30963 13.6481 5.48194 14.6279 5.80999 15.56C6.24999 16.84 7.89999 20.05 9.61999 20C10.52 20 11.16 19.36 12.33 19.36C13.5 19.36 14.05 20 15.06 20C16.79 20 18.29 17.05 18.72 15.74C18.0689 15.4737 17.5119 15.0195 17.1201 14.4353C16.7282 13.8511 16.5193 13.1634 16.52 12.46ZM14.52 6.59C14.8307 6.23965 15.065 5.82839 15.2079 5.38245C15.3508 4.93651 15.3992 4.46569 15.35 4C14.4163 4.10239 13.5539 4.54785 12.93 5.25C12.6074 5.58991 12.3583 5.99266 12.1983 6.43312C12.0383 6.87358 11.9708 7.34229 12 7.81C12.4842 7.82361 12.9646 7.71974 13.3999 7.50728C13.8353 7.29482 14.2127 6.98009 14.5 6.59H14.52Z"
                          fill="#909090"
                        />
                      </g>
                    </svg>
                  </div>
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
