<script setup>
import { ref, reactive, onMounted, onUnmounted, computed } from "vue";
import { Link } from "@inertiajs/vue3";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import DashboardFooter from "@/Components/DashboardFooter.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import Modal from "@/Components/Modal.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { toaster } from "@/helper.js";
import { Device } from "@twilio/voice-sdk";
import { usePage, router } from "@inertiajs/vue3";
import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";

let page = usePage();

console.log('user object',page.props.auth.user)

let isInternalLevel = ref(false);

if (
  page.props.auth.user_level &&
  page.props.auth.user_level.name &&
  page.props.auth.user_level.name.startsWith("Internal")
) {
  isInternalLevel.value = true;
}

let showMobileNotifications = ref(false);
let userNotifications = ref(page.props.auth.notifications);

let unreadNotifications = ref(
  page.props.auth.notifications.filter((notification) => {
    return notification.read_at === null;
  })
);
let disabledNavLink = ref(false);
if (page.props.auth.role === "internal-agent" && page.props.auth.user.is_locked) {
  disabledNavLink.value = true;
  console.log("agent here", disabledNavLink.value);
}

let markAllAsRead = () => {
  axios.post("/notifications/mark-all-as-read").then((response) => {
    let returnedNotifications = response.data;
    userNotifications.value = returnedNotifications;
    unreadNotifications.value = returnedNotifications.filter((notification) => {
      return notification.read_at === null;
    });
  });
};

let clearAllNotifications = () => {
  axios.post("/notifications/clear-all").then((response) => {
    userNotifications.value = response.data;
    unreadNotifications.value = response.data;
  });
};

let connectedClient = ref(null);
let callDuration = ref("00:00");
let callConnectionTime = reactive(null);
let connectedUniqueCallId = ref(null);
let showRinging = ref(false);
let showOngoing = ref(false);
let call = reactive(null);
let hasSixtySecondsPassed = ref(false);
let ringingTimeout = ref(null);

let getFormattedTime = (startTime) => {
  const now = new Date();
  const difference = new Date(now - startTime);
  const minutes = String(difference.getMinutes()).padStart(2, "0");
  const seconds = String(difference.getSeconds()).padStart(2, "0");
  return `${minutes}:${seconds}`;
};

let showIncomingCall = (conn) => {
  console.log("show incoming call now");
  console.log(conn);

  console.log("Params:");
  console.log(conn.parameters.Params);

  // add a timeout for 25 seconds to hide the ringing screen in case the user doesn't accept the call in 25 seconds
  if (ringingTimeout.value) {
    console.log("Clearing the previous timeout.");
    clearTimeout(ringingTimeout.value);
    ringingTimeout.value = null;
  }

  console.log("Setting timeout for 25 seconds now.");
  ringingTimeout.value = setTimeout(() => {
    console.log("Timeout for 25 seconds reached!");

    if (!showOngoing.value) {
      console.log("Show ongoing is false, so hide the ringing screen now.");
      showRinging.value = false;
      ringingTimeout.value = null;
    }
  }, 25000);

  let params = new URLSearchParams(conn.parameters.Params);
  let uniqueCallId = params.get("unique_call_id");

  connectedUniqueCallId.value = uniqueCallId;

  setTimeout(() => {
    axios
      .get("/call-client-info?unique_call_id=" + connectedUniqueCallId.value)
      .then((response) => {
        console.log(response.data.client);
        connectedClient.value = response.data.client;
        localStorage.setItem("latestClientId", connectedClient.value.id);

        console.log("connected client now: ");
        console.log(connectedClient.value);
      });
  }, 2000);

  showRinging.value = true;
};

let saveUserResponseTime = () => {
  axios
    .patch(
      "/web-api/calls/" + connectedUniqueCallId.value + "/user-response",
      {
        user_response_time: new Date(),
        device: "desktop",
      },
      {
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
      }
    )
    .then((response) => {
      console.log(response.data);
      console.log("user response time saved successfully");
    })
    .catch((error) => {
      // Handle any error that occurred during the request
      console.error("Error saving the user response time:", error);
    });
};

let acceptCall = () => {
  console.log("accept call now");

  Echo.private("calls." + page.props.auth.user.id).whisper("psst", {
    action: "accept",
  });

  if (call) {
    call.accept();
    showRinging.value = false;

    if (ringingTimeout.value) {
      console.log("Clearing the previous timeout.");
      clearTimeout(ringingTimeout.value);
      ringingTimeout.value = null;
    }

    showOngoing.value = true;

    callConnectionTime = new Date();
    saveUserResponseTime();

    setInterval(() => {
      callDuration.value = getFormattedTime(callConnectionTime);

      let now = new Date();
      let differenceInSeconds = (now - callConnectionTime) / 1000;

      if (differenceInSeconds >= 60 && !hasSixtySecondsPassed.value) {
        hasSixtySecondsPassed.value = true;
        console.log("Sixty seconds have passed!");
      }
    }, 1000);
  } else {
    console.log("call not found");
  }
};

let saveClient = () => {
  console.log("saving client now");
  axios
    .patch("/web-api/clients/" + connectedClient.value.id, {
      first_name: connectedClient.value.first_name,
      last_name: connectedClient.value.last_name,
      email: connectedClient.value.email,
      phone: connectedClient.value.phone,
      address: connectedClient.value.address,
      state: connectedClient.value.state,
      zipCode: connectedClient.value.zipCode,
      // status: connectedClient.value.status,
      dob: connectedClient.value.dob,
    })
    .then((response) => {
      console.log(response.data);
      console.log("client saved successfully");
      toaster("success", "Client updated.");
    })
    .catch((error) => {
      // Handle any error that occurred during the request
      console.error("Error saving the client:", error);
    });
};

let refetchClient = () => {
  axios
    .get("/call-client-info?unique_call_id=" + connectedUniqueCallId.value)
    .then((response) => {
      console.log(response.data.client);
      connectedClient.value = response.data.client;
      localStorage.setItem("latestClientId", connectedClient.value.id);

      console.log("connected client now: ");
      console.log(connectedClient.value);
    })
    .catch((error) => {
      // Handle any error that occurred during the request
      console.error("Error refetching the client:", error);
    });
};

let rejectCall = () => {
  console.log("reject call now");
  Echo.private("calls." + page.props.auth.user.id).whisper("psst", {
    action: "reject",
  });

  if (call) {
    call.reject();
    showRinging.value = false;
    saveUserResponseTime();
    console.log("Should update now");
  } else {
    console.log("call not found while rejecting");
  }
};

let disconnectCall = () => {
  console.log("disconnect call now");

  // send reject to the web-api to track who disconnected the call
  axios
    .post("/web-api/calls/" + connectedUniqueCallId.value + "/reject", {
      user_response_time: new Date(),
      device: "desktop",
    })
    .then((response) => {
      console.log(response.data);
      console.log("Hung up by values saved!");
    })
    .catch((error) => {
      // Handle any error that occurred during the request
      console.error("Error saving the hung up by values:", error);
    });

  Echo.private("calls." + page.props.auth.user.id).whisper("psst", {
    action: "disconnect",
  });
  if (call) {
    call.disconnect();
    showOngoing.value = false;
    showUpdateDispositionModal();
  } else {
    console.log("call not found while disconnecting");
  }
};

let latestClientDisposition = ref("Sale - Simplified Issue");
let repeatedNotificationToUpdateDispositionTimeout = ref(null);

let startTimeoutForRepeatedDispositionNotifications = () => {
  console.log("Starting timeout for repeated disposition notifications.");

  if (repeatedNotificationToUpdateDispositionTimeout.value) {
    console.log("Clearing the previous interval.");
    clearInterval(repeatedNotificationToUpdateDispositionTimeout.value);
    repeatedNotificationToUpdateDispositionTimeout.value = null;
  }

  console.log("Setting new timeout for disposition notifications");
  repeatedNotificationToUpdateDispositionTimeout.value = setInterval(() => {
    console.log("Send Notification To Update Disposition");
    toaster("success", "Please update the disposition for the client.");
  }, 15000);
};

let clearTimeoutForRepeatedDispositionNotifications = () => {
  console.log("Clearing timeout for repeated disposition notifications.");
  clearTimeout(repeatedNotificationToUpdateDispositionTimeout.value);
  repeatedNotificationToUpdateDispositionTimeout.value = null;
};

let updateLatestClientDisposition = () => {
  // fetch latestClientId from axios get request to /web-api/latest-client
  axios.get("/web-api/latest-client").then((response) => {
    console.log("Latest client:");
    console.log(response.data.client.id);
    let latestClientId = response.data.client.id;

    axios
      .post(`/web-api/clients/${latestClientId}/disposition`, {
        status: latestClientDisposition.value,
      })
      .then((response) => {
        console.log("Client disposition update response:");
        console.log(response.data);
        console.log('Status of the client that was updated: ', response.data.status)

        if (response.data.status.startsWith("Sale")) {
          console.log("Sale detected!");
          // router.visit('/my-business/')
          router.visit(`/internal-agent/my-business?clientId=${response.data.clientId}`);
        }

        localStorage.removeItem("latestClientId");
        localStorage.removeItem("showDispositionModal");
        showUpdateDispositionForLastClient.value = false;
        toaster("success", "Client disposition updated.");
        clearTimeoutForRepeatedDispositionNotifications();
      })
      .catch((error) => {
        console.log("Error updating client disposition:");
        console.log(error);
      });
  });
};

let setupTwilioDevice = () => {
  axios.get("/twilio-device-token").then((response) => {
    let token = response.data.token;
    console.log("token is ", token);

    let device = new Device(token, {
      // Set Opus as our preferred codec. Opus generally performs better, requiring less bandwidth and
      // providing better audio quality in restrained network conditions. Opus will be default in 2.0.
      codecPreferences: ["opus", "pcmu"],
      // Use fake DTMF tones client-side. Real tones are still sent to the other end of the call,
      // but the client-side DTMF tones are fake. This prevents the local mic capturing the DTMF tone
      // a second time and sending the tone twice. This will be default in 2.0.
      fakeLocalDTMF: true,
      // Use `enableRingingState` to enable the device to emit the `ringing`
      // state. The TwiML backend also needs to have the attribute
      // `answerOnBridge` also set to true in the `Dial` verb. This option
      // changes the behavior of the SDK to consider a call `ringing` starting
      // from the connection to the TwiML backend to when the recipient of
      // the `Dial` verb answers.
      enableRingingState: true,
      debug: true,
    });
    console.log("deviceee", device);

    device.on("ready", function (device) {
      console.log("Twilio.Device Ready!");
    });

    device.on("registered", function () {
      console.log("REGISTERED!");
    });

    device.on("error", function (error) {
      console.log("Twilio.Device Error: " + error.message);
    });

    device.on("connect", function (conn) {
      console.log("Successfully established call ! ");
    });

    device.on("disconnect", function (conn) {
      console.log("Call should disconnect now.");
      // showRinging.value = false;
      showOngoing.value = false;
      showUpdateDispositionModal();
    });

    device.addListener("disconnect", (device) => {
      console.log("The device is about to disconnect now.");
    });

    device.on("cancel", function () {
      console.log("Incoming call was canceled");
      // Update the UI to hide the incoming call notification
    });

    device.on("incoming", (incomingCall) => {
      console.log("Incoming!");
      call = incomingCall;
      showIncomingCall(call);
    });

    device.register();
  });
};

let showUpdateDispositionForLastClient = ref(false);

if (page.props.auth.showDispositionUpdateOption) {
  showUpdateDispositionForLastClient.value = true;
  startTimeoutForRepeatedDispositionNotifications();
}
// console.log('showDispositionModal:', page.props.auth.showDispositionUpdateOption);

let showUpdateDispositionModal = () => {
  // // Check if 'showDispositionModal' exists in localStorage
  // if (localStorage.getItem("showDispositionModal") === null) {
  //   // If not, create the variable in localStorage with a value (e.g., 'true')
  //   localStorage.setItem("showDispositionModal", "true");
  //   console.log("'showDispositionModal' variable created in localStorage.");
  //   showUpdateDispositionForLastClient.value = true;
  //   startTimeoutForRepeatedDispositionNotifications();
  //   return
  // }
  // console.log("'showDispositionModal' variable already exists in localStorage.");
  // showUpdateDispositionForLastClient.value = true;
  // startTimeoutForRepeatedDispositionNotifications();
};

let makeDispositionModalNull = () => {
  // localStorage.setItem("showDispositionModal", null);
  // console.log("'showDispositionModal' variable set to null in localStorage.");
  // showUpdateDispositionForLastClient.value = false;
};

onMounted(() => {
  // if the showDispositionModal is not null, display the modal
  // if (localStorage.getItem("showDispositionModal") !== null) {
  //   showUpdateDispositionForLastClient.value = true;
  //   startTimeoutForRepeatedDispositionNotifications();
  // }
});

onMounted(() => {
  console.log("mounted AuthenticatedLayout");

  console.log("Listening for completed call event.");
  Echo.private("calls." + page.props.auth.user.id).listen("CallEnded", (event) => {
    console.log("CallEnded:", event);
    if (
      event.client &&
      event.client.status === null &&
      !showUpdateDispositionForLastClient.value
    ) {
      showUpdateDispositionForLastClient.value = true;
      startTimeoutForRepeatedDispositionNotifications();
    }
  });

  Echo.private("calls." + page.props.auth.user.id).listen(
    "UserSavedNonNullStatus",
    (event) => {
      console.log("UserSavedNonNullStatus:", event);
      console.log("So, we can drop the modal now.");
      showUpdateDispositionForLastClient.value = false;
      clearTimeoutForRepeatedDispositionNotifications();
    }
  );

  Echo.private("calls." + page.props.auth.user.id).listenForWhisper("psst", (e) => {
    console.log("call event:");
    console.log(e);

    if (ringingTimeout.value) {
      console.log("Clearing the previous timeout.");
      clearTimeout(ringingTimeout.value);
      ringingTimeout.value = null;
    }
    showRinging.value = false;
    showOngoing.value = false;
    showUpdateDispositionModal();
  });

  console.log("Attaching call accepted or rejected listener:");
  console.log(`${page.props.auth.user.id}.notifications`);

  Echo.private(`${page.props.auth.user.id}.notifications`).listen(
    "CallAcceptedOrRejected",
    (e) => {
      console.log("call accepted or rejected by one of the phone devices");
      console.log(e);

      if (ringingTimeout.value) {
        console.log("Clearing the previous timeout.");
        clearTimeout(ringingTimeout.value);
        ringingTimeout.value = null;
      }
      showRinging.value = false;
      showOngoing.value = false;
      showUpdateDispositionModal();
    }
  );

  setupTwilioDevice();
});

onUnmounted(() => {
  console.log("unmounted AuthenticatedLayout");
});

const showingNavigationDropdown = ref(false);

let formatMoney = (amount) => {
  return parseFloat(amount)
    .toFixed(2)
    .replace(/\d(?=(\d{3})+\.)/g, "$&,");
};
const props = defineProps({
  role: String,
});
let appDownloadModal = ref(false);
</script>

<template>
  <div>
    <div id="body-background-element" class="min-h-screen bg-custom-indigo">
      <div
        class="bg-custom-sky text-gray-50 py-2 px-16 flex-col sm:flex-row flex justify-center items-center"
      >
        <div class="mb-2 sm:mb-0 sm:mr-6 text-center sm:text-left">
          Download the mobile app and start buying calls now!
        </div>
        <div>
          <button
            type="button"
            @click="appDownloadModal = true"
            class="px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300"
          >
            Download App
          </button>
        </div>
      </div>

      <!-- Admin Navigation Menu -->
      <div v-if="$page.props.auth.role == 'admin'">
        <nav class="bg-custom-indigo border-b border-gray-800">
          <!-- Primary Navigation Menu -->
          <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
              <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                  <Link
                    class="mt-4 bg-clip-text text-4xl text-transparent bg-gradient-to-r from-blue-400 to-green-500 font-bold uppercase tracking-wider"
                    :href="route('admin.dashboard')"
                  >
                    <!-- AllCalls.io -->
                    <img style="max-width: 200px" src="/img/new-logo.png" />
                  </Link>
                </div>
              </div>

              <div class="hidden md:flex md:self-start sm:pt-6 sm:ml-6">
                <!-- Settings Dropdown -->
                <div class="ml-3 relative">
                  <Dropdown align="right" width="48">
                    <template #trigger>
                      <span class="inline-flex rounded-md">
                        <button
                          type="button"
                          class="inline-flex items-center text-lg leading-4 font-bold text-gray-300 hover:text-custom-green focus:outline-none transition ease-in-out duration-150"
                        >
                          {{
                            $page.props.auth.user.first_name +
                            " " +
                            $page.props.auth.user.last_name
                          }}

                          <svg
                            class="ml-2 -mr-0.5 h-4 w-4 text-white"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                          >
                            <path
                              fill-rule="evenodd"
                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                              clip-rule="evenodd"
                            />
                          </svg>
                        </button>
                      </span>
                    </template>

                    <template #content>
                      <DropdownLink :href="route('logout')" method="post" as="button">
                        Log Out
                      </DropdownLink>
                    </template>
                  </Dropdown>
                </div>
              </div>

              <!-- Hamburger -->
              <div class="-mr-2 flex items-center md:hidden">
                <button
                  @click="showingNavigationDropdown = !showingNavigationDropdown"
                  class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out"
                >
                  <svg
                    class="h-6 w-6"
                    stroke="currentColor"
                    fill="none"
                    viewBox="0 0 24 24"
                  >
                    <path
                      :class="{
                        hidden: showingNavigationDropdown,
                        'inline-flex': !showingNavigationDropdown,
                      }"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16"
                    />
                    <path
                      :class="{
                        hidden: !showingNavigationDropdown,
                        'inline-flex': showingNavigationDropdown,
                      }"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M6 18L18 6M6 6l12 12"
                    />
                  </svg>
                </button>
              </div>
            </div>

            <!-- <p class="mt-10 mb-2 text-lg text-white font-bold">
              Manage your business and preferences here.
            </p> -->
            <div class="mb-6"></div>
          </div>

          <!-- Responsive Navigation Menu -->
          <div
            :class="{
              block: showingNavigationDropdown,
              hidden: !showingNavigationDropdown,
            }"
            class="md:hidden"
          >
            <div class="pt-2 pb-3 space-y-1">
              <ResponsiveNavLink
                :href="route('admin.dashboard')"
                :active="route().current('admin.dashboard')"
              >
                Dashboard
              </ResponsiveNavLink>
            </div>
            <div class="pt-2 pb-3 space-y-1">
              <ResponsiveNavLink
                :href="route('admin.calls.index')"
                :active="
                  route().current('admin.calls.index') ||
                  route().current('admin.calls.detail')
                "
              >
                Calls
              </ResponsiveNavLink>
            </div>
            <div class="pt-2 pb-3 space-y-1">
              <ResponsiveNavLink
                :href="route('admin.customer.index')"
                :active="
                  route().current('admin.customer.index') ||
                  route().current('admin.customer.detail')
                "
              >
                Customers
              </ResponsiveNavLink>
            </div>
            <div class="pt-2 pb-3 space-y-1">
              <ResponsiveNavLink
                :href="route('admin.transactions')"
                :active="route().current('admin.transactions')"
              >
                Transactions
              </ResponsiveNavLink>
            </div>
            <div class="pt-2 pb-3 space-y-1">
              <ResponsiveNavLink
                :href="route('admin.agent.index')"
                :active="
                  route().current('admin.agent.index') ||
                  route().current('admin.agent.detail')
                "
              >
                Internal Agents
              </ResponsiveNavLink>
            </div>

            <div class="pt-2 pb-3 space-y-1">
              <ResponsiveNavLink
                :href="route('admin.online-agents.index')"
                :active="route().current('admin.online-agents.index')"
              >
                Online Agents
              </ResponsiveNavLink>
            </div>

            <div class="pt-2 pb-3 space-y-1">
              <ResponsiveNavLink
                :href="route('admin.internal.agent.level.index')"
                :active="route().current('admin.internal.agent.level.index')"
              >
                Agents Levels
              </ResponsiveNavLink>
            </div>

            <div class="pt-2 pb-3 space-y-1">
              <ResponsiveNavLink
                :href="route('admin.my-business.index')"
                :active="route().current('admin.my-business.index')"
              >
                My Business
              </ResponsiveNavLink>
            </div>

            <div class="pt-2 pb-3 space-y-1">
              <ResponsiveNavLink
                :href="route('admin.notifications.create')"
                :active="route().current('admin.notifications.create')"
              >
                Notifications
              </ResponsiveNavLink>
            </div>

            <div class="pt-2 pb-3 space-y-1">
              <ResponsiveNavLink
                :href="route('admin.agent-invites.index')"
                :active="route().current('admin.agent-invites.index')"
              >
                Agent Invites
              </ResponsiveNavLink>
            </div>
            <div class="pt-2 pb-3 space-y-1">
              <ResponsiveNavLink
                :href="route('admin.available-number.index')"
                :active="route().current('admin.available-number.index')"
              >
                Available Numbers
              </ResponsiveNavLink>
            </div>
            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
              <div class="px-4">
                <div class="font-medium text-base text-gray-200">
                  {{
                    $page.props.auth.user.first_name +
                    " " +
                    $page.props.auth.user.last_name
                  }}
                </div>
                <div class="font-medium text-sm text-gray-400">
                  {{ $page.props.auth.user.email }}
                </div>
              </div>

              <div class="mt-3 space-y-1">
                <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                  Log Out
                </ResponsiveNavLink>
              </div>
            </div>
          </div>
        </nav>

        <div
          class="w-full mx-auto md:grid md:grid-cols-5 md:gap-28 md:max-w-screen-2xl xl:gap-0"
        >
          <div class="py-12 hidden sm:-my-px sm:ml-10 col-span-1 md:flex md:flex-col">
            <NavLink
              class="mb-10 gap-2"
              :href="route('admin.dashboard')"
              :active="route().current('admin.dashboard')"
            >
              <img src="/img/dashboard.png" alt="" />
              Dashboard
            </NavLink>
            <NavLink
              class="mb-10 gap-2"
              :href="route('admin.calls.index')"
              :active="
                route().current('admin.calls.index') ||
                route().current('admin.calls.detail')
              "
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-8 h-8 mr-2"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M14.25 9.75v-4.5m0 4.5h4.5m-4.5 0l6-6m-3 18c-8.284 0-15-6.716-15-15V4.5A2.25 2.25 0 014.5 2.25h1.372c.516 0 .966.351 1.091.852l1.106 4.423c.11.44-.054.902-.417 1.173l-1.293.97a1.062 1.062 0 00-.38 1.21 12.035 12.035 0 007.143 7.143c.441.162.928-.004 1.21-.38l.97-1.293a1.125 1.125 0 011.173-.417l4.423 1.106c.5.125.852.575.852 1.091V19.5a2.25 2.25 0 01-2.25 2.25h-2.25z"
                />
              </svg>

              Calls
            </NavLink>
            <NavLink
              class="mb-10 gap-2"
              :href="route('admin.customer.index')"
              :active="
                route().current('admin.customer.index') ||
                route().current('admin.customer.detail')
              "
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-8 h-8 mr-2"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"
                />
              </svg>

              Customers
            </NavLink>

            <NavLink
              class="mb-10 gap-2"
              :href="route('admin.transactions')"
              :active="route().current('admin.transactions')"
            >
              <!-- <svg class="w-8 h-8 mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#ffffff">

                <g id="SVGRepo_bgCarrier" stroke-width="0"/>

                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>

                <g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M15.2929 3.29289C15.6834 2.90237 16.3166 2.90237 16.7071 3.29289L22.3657 8.95147C23.1216 9.70743 22.5862 11 21.5172 11H2C1.44772 11 1 10.5523 1 10C1 9.44772 1.44772 9 2 9H19.5858L15.2929 4.70711C14.9024 4.31658 14.9024 3.68342 15.2929 3.29289ZM4.41421 15H22C22.5523 15 23 14.5523 23 14C23 13.4477 22.5523 13 22 13H2.48284C1.41376 13 0.878355 14.2926 1.63431 15.0485L7.29289 20.7071C7.68342 21.0976 8.31658 21.0976 8.70711 20.7071C9.09763 20.3166 9.09763 19.6834 8.70711 19.2929L4.41421 15Z" fill="#0F0F0F"/> </g>

                </svg> -->
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-8 h-8 mr-2"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"
                />
              </svg>
              Transactions
            </NavLink>

            <NavLink
              class="mb-10 gap-2"
              :href="route('admin.agent-invites.index')"
              :active="route().current('admin.agent-invites.index')"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-8 h-8 mr-2"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"
                />
              </svg>

              Agent Invites
            </NavLink>

            <NavLink
              class="mb-10 gap-2"
              :href="route('admin.agent.index')"
              :active="
                route().current('admin.agent.index') ||
                route().current('admin.agent.detail')
              "
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-8 h-8 mr-2"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"
                />
              </svg>

              Internal Agents
            </NavLink>

            <NavLink
              class="mb-10 gap-2"
              :href="route('admin.online-agents.index')"
              :active="route().current('admin.online-agents.index')"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-8 h-8 mr-2"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M8.288 15.038a5.25 5.25 0 017.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 011.06 0z"
                />
              </svg>

              Online Agents
            </NavLink>

            <NavLink
              class="mb-10 gap-2"
              :href="route('admin.internal.agent.level.index')"
              :active="route().current('admin.internal.agent.level.index')"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-8 h-8 mr-2"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M6 13.5V3.75m0 9.75a1.5 1.5 0 010 3m0-3a1.5 1.5 0 000 3m0 3.75V16.5m12-3V3.75m0 9.75a1.5 1.5 0 010 3m0-3a1.5 1.5 0 000 3m0 3.75V16.5m-6-9V3.75m0 3.75a1.5 1.5 0 010 3m0-3a1.5 1.5 0 000 3m0 9.75V10.5"
                />
              </svg>
              Agents Levels
            </NavLink>

            <NavLink
              class="mb-10 gap-2"
              :href="route('admin.my-business.index')"
              :active="route().current('admin.my-business.index')"
            >
              <svg
                fill="#fff"
                class="w-8 h-8 mr-2"
                viewBox="0 0 50 50"
                xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink"
                stroke="#fff"
              >
                <path
                  d="M7 2L7 7L2 7L2 46L14 46L14 44L4 44L4 9L9 9L9 4L29 4L29 7L11 7L11 9L34 9L34 20L36 20L36 7L31 7L31 2 Z M 8 12L8 14L12 14L12 12 Z M 17 12L17 14L21 14L21 12 Z M 26 12L26 14L30 14L30 12 Z M 8 17L8 19L12 19L12 17 Z M 17 17L17 19L21 19L21 17 Z M 26 17L26 19L30 19L30 17 Z M 29 20C25.742188 20 23.328125 21.324219 22.074219 23.296875C20.929688 25.09375 20.875 27.339844 21.59375 29.433594C21.515625 29.566406 21.402344 29.679688 21.328125 29.839844C21.171875 30.191406 21.035156 30.589844 21.054688 31.097656L21.054688 31.101563C21.109375 32.378906 21.851563 33.046875 22.398438 33.421875C22.628906 34.640625 23.207031 35.660156 24 36.390625L24 38.53125C23.824219 38.953125 23.472656 39.308594 22.796875 39.679688C22.089844 40.070313 21.132813 40.4375 20.144531 40.917969C19.15625 41.398438 18.125 42.011719 17.324219 42.988281C16.519531 43.96875 16 45.308594 16 47L16 48L48.050781 48L47.992188 46.941406C47.902344 45.363281 47.316406 44.117188 46.488281 43.222656C45.664063 42.328125 44.644531 41.765625 43.679688 41.320313C42.714844 40.875 41.785156 40.535156 41.109375 40.171875C40.464844 39.832031 40.148438 39.511719 40 39.160156L40 37.472656C40.597656 36.609375 40.859375 35.617188 40.9375 34.6875C41.414063 34.265625 41.96875 33.617188 42.125 32.457031C42.230469 31.625 42.019531 30.996094 41.695313 30.464844C42.144531 29.277344 42.328125 27.84375 41.933594 26.417969C41.707031 25.589844 41.277344 24.777344 40.5625 24.171875C40.003906 23.691406 39.238281 23.425781 38.390625 23.308594L37.75 22L37.125 22C36.097656 22 35.085938 22.238281 34.214844 22.578125C33.871094 22.714844 33.558594 22.863281 33.265625 23.027344C33.101563 22.808594 32.921875 22.601563 32.714844 22.414063C32.105469 21.863281 31.261719 21.550781 30.324219 21.421875L29.621094 20 Z M 8 22L8 24L12 24L12 22 Z M 17 22L17 24L19.484375 24L20.761719 22 Z M 28.4375 22.113281L29.027344 23.300781L29.644531 23.300781C30.464844 23.300781 30.96875 23.535156 31.371094 23.894531C31.773438 24.257813 32.066406 24.796875 32.238281 25.429688C32.582031 26.695313 32.289063 28.339844 32.007813 28.792969L31.644531 29.371094L32.050781 29.921875C32.289063 30.238281 32.441406 30.566406 32.363281 31.007813C32.253906 31.625 32.03125 31.707031 31.589844 32.089844L31.257813 32.375L31.246094 32.8125C31.210938 33.792969 30.871094 34.777344 30.300781 35.339844L30 35.632813L30 38.988281L30.058594 39.152344C30.453125 40.25 31.335938 40.933594 32.234375 41.429688C33.132813 41.925781 34.101563 42.289063 34.976563 42.714844C35.851563 43.140625 36.609375 43.625 37.132813 44.261719C37.496094 44.699219 37.71875 45.289063 37.855469 46L18.144531 46C18.28125 45.289063 18.503906 44.699219 18.867188 44.261719C19.390625 43.625 20.148438 43.140625 21.023438 42.714844C21.898438 42.289063 22.867188 41.925781 23.765625 41.429688C24.664063 40.933594 25.546875 40.25 25.941406 39.152344L26 38.988281L26 35.523438L25.5625 35.226563C25.101563 34.914063 24.34375 33.769531 24.238281 32.742188L24.183594 32.1875L23.683594 31.945313C23.398438 31.808594 23.082031 31.753906 23.050781 31.015625C23.050781 31.015625 23.082031 30.824219 23.15625 30.65625C23.234375 30.484375 23.375 30.304688 23.332031 30.347656L23.8125 29.867188L23.542969 29.242188C22.796875 27.523438 22.898438 25.722656 23.761719 24.367188C24.550781 23.125 26.097656 22.269531 28.4375 22.113281 Z M 36.558594 24.113281L37.089844 25.199219L37.714844 25.199219C38.472656 25.199219 38.921875 25.398438 39.265625 25.691406C39.613281 25.984375 39.859375 26.414063 40.003906 26.949219C40.300781 28.019531 40.085938 29.480469 39.746094 30.144531L39.417969 30.796875L39.933594 31.308594C39.867188 31.242188 40.195313 31.785156 40.140625 32.195313C40.011719 33.175781 39.871094 33.113281 39.449219 33.390625L39.03125 33.667969L39 34.171875C38.953125 35.042969 38.515625 36.351563 38.28125 36.589844L38 36.878906L38 39.621094L38.058594 39.78125C38.4375 40.835938 39.296875 41.476563 40.167969 41.9375C41.035156 42.398438 41.980469 42.738281 42.84375 43.136719C43.707031 43.535156 44.476563 43.984375 45.019531 44.578125C45.367188 44.953125 45.601563 45.433594 45.769531 46L39.921875 46C39.757813 44.777344 39.3125 43.765625 38.675781 42.988281C37.875 42.011719 36.84375 41.398438 35.855469 40.917969C34.867188 40.4375 33.910156 40.070313 33.203125 39.679688C32.527344 39.308594 32.175781 38.953125 32 38.53125L32 36.296875C32.691406 35.421875 33.054688 34.390625 33.15625 33.34375C33.542969 33.003906 34.144531 32.417969 34.332031 31.359375C34.484375 30.492188 34.226563 29.785156 33.90625 29.210938C34.4375 27.988281 34.59375 26.460938 34.167969 24.902344C34.164063 24.886719 34.15625 24.871094 34.152344 24.855469C34.367188 24.71875 34.640625 24.5625 34.949219 24.441406C35.4375 24.25 36.007813 24.179688 36.558594 24.113281 Z M 8 27L8 29L12 29L12 27 Z M 17 27L17 29L19.753906 29L19.394531 27 Z M 8 32L8 34L12 34L12 32 Z M 17 32L17 34L20.449219 34L19.613281 32 Z M 8 37L8 39L12 39L12 37 Z M 17 37L17 39L21 39L21 37Z"
                ></path>
              </svg>
              Report Business
            </NavLink>

            <NavLink
              class="mb-10 gap-2"
              :href="route('admin.user-activities.index')"
              :active="route().current('admin.user-activities.index')"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-8 h-8 mr-2"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M15.042 21.672L13.684 16.6m0 0l-2.51 2.225.569-9.47 5.227 7.917-3.286-.672zM12 2.25V4.5m5.834.166l-1.591 1.591M20.25 10.5H18M7.757 14.743l-1.59 1.59M6 10.5H3.75m4.007-4.243l-1.59-1.59"
                />
              </svg>

              User Activities
            </NavLink>

            <NavLink
              class="mb-10 gap-2"
              :href="route('admin.notifications.create')"
              :active="route().current('admin.notifications.create')"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-8 h-8 mr-2"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0M3.124 7.5A8.969 8.969 0 015.292 3m13.416 0a8.969 8.969 0 012.168 4.5"
                />
              </svg>

              Notifications
            </NavLink>

            <NavLink
              class="mb-10 gap-2"
              :href="route('admin.available-number.index')"
              :active="route().current('admin.available-number.index')"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-8 h-8 mr-2"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"
                />
              </svg>

              Available Numbers
            </NavLink>

            <NavLink
              class="mb-10 gap-2"
              id="billing-nav-link"
              :href="route('profile.view')"
              :active="route().current('profile.view') || route().current('profile.edit')"
              :class="{
                'mb-5':
                  route().current('profile.view') || route().current('profile.edit'),
              }"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-8 h-8 mr-2"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"
                />
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                />
              </svg>
              Settings

              <svg
                v-if="route().current('profile.view') || route().current('profile.edit')"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-6 h-6"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                />
              </svg>
            </NavLink>
            <div
              v-if="route().current('profile.view') || route().current('profile.edit')"
              class="pl-14 text-white text-xs mb-5"
            >
              <ul>
                <li class="mb-3">
                  <Link
                    aria-current="page"
                    class="inline-flex items-center rounded-t-lg hover:text-custom-green group"
                    :class="{
                      'text-custom-green':
                        route().current('profile.view') ||
                        route().current('profile.edit'),
                    }"
                    :href="route('profile.view')"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      class="w-4 h-4 mr-2"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"
                      />
                    </svg>

                    <span>Profile</span>
                  </Link>
                </li>
              </ul>
            </div>
          </div>
          <!-- Page Content -->
          <main class="col-span-4 bg-white rounded-xl mt-14 mb-10">
            <slot />
          </main>
        </div>
      </div>
      <!-- Amin Navigation Menu -->

      <!-- User Navigation Menu -->
      <div v-else>
        <nav class="bg-custom-indigo border-b border-gray-800">
          <!-- Primary Navigation Menu -->
          <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
              <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                  <Link
                    class="mt-4 bg-clip-text text-4xl text-transparent bg-gradient-to-r from-blue-400 to-green-500 font-bold uppercase tracking-wider"
                    :href="route('dashboard')"
                  >
                    <!-- AllCalls.io -->
                    <img style="max-width: 200px" src="/img/new-logo.png" />
                  </Link>
                </div>
              </div>

              <div class="hidden md:flex md:self-start sm:pt-6 sm:ml-6">
                <div class="flex items-center">
                  <!-- <div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                      stroke="currentColor" class="w-6 h-6">
                      <path stroke-linecap="round" stroke-linejoin="round"
                        d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>

                  </div> -->
                  <div>
                    <Link
                      href="/billing/funds"
                      class="mr-3 px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300"
                      v-if="!isInternalLevel"
                    >
                      Add Funds
                    </Link>
                  </div>
                  <div class="flex flex-col justify-center items-center">
                    <div
                      class="text-xs leading-4 font-medium rounded-md text-custom-white"
                    >
                      Balance
                    </div>
                    <div class="text-xl font-bold text-gray-300">
                      ${{ formatMoney($page.props.auth.user.balance) }}
                    </div>
                  </div>
                </div>

                <!-- Notifications Dropdown -->
                <div v-if="true" class="ml-3 relative">
                  <div class="text-right">
                    <Menu as="div" class="relative inline-block text-left">
                      <div>
                        <MenuButton class="text-white">
                          <!-- Bell icon -->
                          <span class="sr-only">Open notifications menu</span>

                          <div v-if="unreadNotifications.length" class="relative">
                            <div
                              class="h-4 w-4 flex items-center justify-center absolute -top-2 -left-2 bg-red-500 rounded-full text-xs z-10"
                              v-text="unreadNotifications.length"
                            ></div>
                          </div>

                          <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-6 h-6"
                          >
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"
                            />
                          </svg>
                        </MenuButton>
                      </div>

                      <!-- Dropdown transition effect -->
                      <transition
                        enter-active-class="transition duration-100 ease-out"
                        enter-from-class="transform scale-95 opacity-0"
                        enter-to-class="transform scale-100 opacity-100"
                        leave-active-class="transition duration-75 ease-in"
                        leave-from-class="transform scale-100 opacity-100"
                        leave-to-class="transform scale-95 opacity-0"
                      >
                        <!-- Notifications list -->
                        <MenuItems
                          class="absolute right-0 mt-2 w-80 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none z-10"
                        >
                          <div class="text-lg font-bold px-3 py-2">Notifications</div>

                          <div
                            class="px-3 py-2 text-sm text-gray-600 font-bold flex items-center justify-between"
                            v-if="unreadNotifications.length"
                          >
                            <div class="flex items-center">
                              <div class="mr-1 font-bold">Unread</div>
                              <div
                                style="font-size: 10px"
                                class="bg-gray-200 rounded-full text-gray-600 w-4 h-4 flex items-center justify-center"
                              >
                                {{ unreadNotifications.length }}
                              </div>
                            </div>

                            <div
                              class="cursor-pointer text-xs py-2 text-gray-800 hover:text-gray-500"
                              @click.prevent="markAllAsRead"
                            >
                              Mark All As Read
                            </div>
                          </div>
                          <!-- Unread Notification Items Start -->
                          <div style="max-height: 400px; overflow-y: scroll">
                            <div
                              v-for="(notification, index) in unreadNotifications"
                              :key="notification.id"
                              class="p-1 cursor-pointer"
                            >
                              <MenuItem v-slot="{ active }">
                                <div
                                  :class="[
                                    'flex flex-col gap-1 p-2 rounded-md hover:bg-custom-blue hover:text-white',
                                  ]"
                                >
                                  <!-- Notification Title -->
                                  <p class="text-sm font-semibold">
                                    {{ notification.data.title }}
                                  </p>
                                  <!-- Notification Body -->
                                  <p class="text-xs">{{ notification.data.body }}</p>

                                  <p style="font-size: 9px">
                                    {{ notification.created_at_diff }}
                                  </p>
                                </div>
                              </MenuItem>
                            </div>
                          </div>

                          <!-- Unread Notification Items End -->

                          <div
                            class="px-3 py-2 text-sm text-gray-600 font-bold flex items-center justify-between"
                            v-if="userNotifications && userNotifications.length"
                          >
                            <div class="flex items-center">
                              <div class="mr-1 font-bold">All</div>
                              <div
                                style="font-size: 10px"
                                class="bg-gray-200 rounded-full text-gray-600 w-4 h-4 flex items-center justify-center"
                                v-if="userNotifications && userNotifications.length"
                              >
                                {{ userNotifications.length }}
                              </div>
                            </div>

                            <div
                              class="cursor-pointer text-xs py-2 text-gray-800 hover:text-gray-500"
                              @click.prevent="clearAllNotifications"
                            >
                              Clear All
                            </div>
                          </div>
                          <!-- Notification Items Start -->
                          <!-- Assuming 'notifications' is an array in your component's data -->
                          <div style="max-height: 400px; overflow-y: scroll">
                            <div
                              v-for="(notification, index) in userNotifications"
                              :key="notification.id"
                              class="p-1 cursor-pointer"
                            >
                              <MenuItem>
                                <div
                                  class="flex flex-col gap-1 p-2 rounded-md hover:bg-custom-blue hover:text-white"
                                >
                                  <!-- Notification Title -->
                                  <p class="text-sm font-semibold">
                                    {{ notification.data.title }}
                                  </p>
                                  <!-- Notification Body -->
                                  <p class="text-xs">{{ notification.data.body }}</p>

                                  <p style="font-size: 9px">
                                    {{ notification.created_at_diff }}
                                  </p>
                                </div>
                              </MenuItem>
                            </div>
                          </div>
                          <!-- Notification Items End -->

                          <div
                            v-if="!userNotifications.length"
                            class="text-center py-3 text-md"
                          >
                            You're all caught up!
                          </div>
                        </MenuItems>
                      </transition>
                    </Menu>
                  </div>
                </div>

                <div class="ml-3 relative">
                  <Dropdown align="right" width="48">
                    <template #trigger>
                      <span class="inline-flex rounded-md">
                        <button
                          type="button"
                          class="inline-flex items-center text-lg leading-4 font-bold text-gray-300 hover:text-custom-green focus:outline-none transition ease-in-out duration-150"
                        >
                          {{
                            $page.props.auth.user.first_name +
                            " " +
                            $page.props.auth.user.last_name
                          }}

                          <svg
                            class="ml-2 -mr-0.5 h-4 w-4 text-white"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                          >
                            <path
                              fill-rule="evenodd"
                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                              clip-rule="evenodd"
                            />
                          </svg>
                        </button>
                      </span>
                    </template>

                    <template #content>
                      <DropdownLink :href="route('logout')" method="post" as="button">
                        Log Out
                      </DropdownLink>
                    </template>
                  </Dropdown>
                </div>
              </div>

              <!-- Hamburger -->
              <div class="-mr-2 flex items-center md:hidden">
                <button
                  @click="showingNavigationDropdown = !showingNavigationDropdown"
                  class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out"
                >
                  <svg
                    class="h-6 w-6"
                    stroke="currentColor"
                    fill="none"
                    viewBox="0 0 24 24"
                  >
                    <path
                      :class="{
                        hidden: showingNavigationDropdown,
                        'inline-flex': !showingNavigationDropdown,
                      }"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16"
                    />
                    <path
                      :class="{
                        hidden: !showingNavigationDropdown,
                        'inline-flex': showingNavigationDropdown,
                      }"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M6 18L18 6M6 6l12 12"
                    />
                  </svg>
                </button>
              </div>
            </div>

            <!-- <p class="mt-10 mb-2 text-lg text-white font-bold">
              Manage your business and preferences here.
            </p> -->
            <div class="mb-6"></div>
          </div>

          <!-- Responsive Navigation Menu -->
          <div
            :class="{
              block: showingNavigationDropdown,
              hidden: !showingNavigationDropdown,
            }"
            class="md:hidden"
          >
            <div class="pt-2 pb-3 space-y-1">
              <ResponsiveNavLink
                :href="route('take-calls.show')"
                :class="{ 'opacity-50': disabledNavLink === true }"
                :disabledNavLink="disabledNavLink"
                :active="route().current('take-calls.show')"
              >
                Take Calls
              </ResponsiveNavLink>

              <ResponsiveNavLink
                :href="route('clients.index')"
                :class="{ 'opacity-50': disabledNavLink === true }"
                :disabledNavLink="disabledNavLink"
                :active="route().current('clients.index')"
              >
                Clients
              </ResponsiveNavLink>
              <ResponsiveNavLink
                v-if="$page.props.auth.role === 'internal-agent'"
                :class="{ 'opacity-50': disabledNavLink === true }"
                :disabledNavLink="disabledNavLink"
                :href="route('internal-agent.agent-agency.index')"
                :active="route().current('internal-agent.agent-agency.index')"
              >
                My Agency
              </ResponsiveNavLink>
              <ResponsiveNavLink
                v-if="$page.props.auth.role === 'internal-agent'"
                :class="{ 'opacity-50': disabledNavLink === true }"
                :disabledNavLink="disabledNavLink"
                :href="route('agent.my.business')"
                :active="route().current('agent.my.business')"
              >
                My Business
              </ResponsiveNavLink>
              <ResponsiveNavLink
                v-if="$page.props.auth.role === 'internal-agent'"
                :href="route('training.index')"
                :active="route().current('training.index')"
              >
                Training
              </ResponsiveNavLink>

              <!-- <ResponsiveNavLink v-if="$page.props.auth.role === 'internal-agent'" :href="route('internal-agent.my-agent.index')" :active="route().current('internal-agent.my-agent.index')">
                Registered Agents
              </ResponsiveNavLink> -->

              <ResponsiveNavLink
                :class="{ 'opacity-50': disabledNavLink === true }"
                :disabledNavLink="disabledNavLink"
                :href="route('billing.funds.index')"
                :active="
                  route().current('billing.funds.index') ||
                  route().current('billing.cards.index') ||
                  route().current('billing.autopay.index')
                "
                v-if="!isInternalLevel"
              >
                <div class="row pb-3 flex">
                  <div class="columns-6 flex">Add Funds</div>
                  <div class="columns-6 flex pl-20">
                    <svg
                      v-if="
                        route().current('billing.funds.index') ||
                        route().current('billing.cards.index') ||
                        route().current('billing.autopay.index')
                      "
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      class="w-6 h-6"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                      />
                    </svg>
                  </div>
                </div>
                <div
                  v-if="
                    route().current('billing.funds.index') ||
                    route().current('billing.cards.index') ||
                    route().current('billing.autopay.index')
                  "
                  class="pl-14 text-white text-xs mb-5"
                >
                  <ul>
                    <li class="mb-3">
                      <Link
                        href="/billing/funds"
                        class="inline-flex items-center rounded-t-lg hover:text-custom-green"
                        :class="{
                          'text-custom-green': route().current('billing.funds.index'),
                        }"
                      >
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke-width="1.5"
                          stroke="currentColor"
                          class="w-4 h-4 mr-2"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"
                          />
                        </svg>

                        <span>Add Funds</span>
                      </Link>
                    </li>

                    <li class="mb-3">
                      <Link
                        aria-current="page"
                        class="inline-flex items-center rounded-t-lg hover:text-custom-green group"
                        :class="{
                          'text-custom-green': route().current('billing.autopay.index'),
                        }"
                        href=""
                      >
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke-width="1.5"
                          stroke="currentColor"
                          class="w-4 h-4 mr-2"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"
                          />
                        </svg>
                        <span>Autopay</span>
                        <div class="p-2">
                          <span
                            class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20"
                            >Soon</span
                          >
                        </div>
                      </Link>
                    </li>
                    <li class="mb-3">
                      <Link
                        href="/billing/cards"
                        class="inline-flex items-center rounded-t-lg hover:text-custom-green"
                        :class="{
                          'text-custom-green': route().current('billing.cards.index'),
                        }"
                      >
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke-width="1.5"
                          stroke="currentColor"
                          class="w-4 h-4 mr-2"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"
                          />
                        </svg>

                        <span>Saved Cards</span>
                      </Link>
                    </li>
                  </ul>
                </div>
              </ResponsiveNavLink>

              <ResponsiveNavLink
                :class="{ 'opacity-50': disabledNavLink === true }"
                :disabledNavLink="disabledNavLink"
                :href="route('calls.index')"
                :active="route().current('calls.index')"
              >
                Call Reporting
              </ResponsiveNavLink>

              <ResponsiveNavLink
                :class="{ 'opacity-50': disabledNavLink === true }"
                :disabledNavLink="disabledNavLink"
                :href="route('additional-files.index')"
                :active="route().current('additional-files.index')"
              >
                Additional Files
              </ResponsiveNavLink>

              <ResponsiveNavLink
                :href="route('billing.funds.index')"
                :active="
                  route().current('billing.funds.index') ||
                  route().current('billing.cards.index') ||
                  route().current('billing.autopay.index')
                "
                v-if="!isInternalLevel"
              >
                <div class="row pb-3 flex">
                  <div class="columns-6 flex">Add Funds</div>
                  <div class="columns-6 flex pl-20">
                    <svg
                      v-if="
                        route().current('billing.funds.index') ||
                        route().current('billing.cards.index') ||
                        route().current('billing.autopay.index')
                      "
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      class="w-6 h-6"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                      />
                    </svg>
                  </div>
                </div>
                <div
                  v-if="
                    route().current('billing.funds.index') ||
                    route().current('billing.cards.index') ||
                    route().current('billing.autopay.index')
                  "
                  class="pl-14 text-white text-xs mb-5"
                >
                  <ul>
                    <li class="mb-3">
                      <Link
                        href="/billing/funds"
                        class="inline-flex items-center rounded-t-lg hover:text-custom-green"
                        :class="{
                          'text-custom-green': route().current('billing.funds.index'),
                        }"
                      >
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke-width="1.5"
                          stroke="currentColor"
                          class="w-4 h-4 mr-2"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"
                          />
                        </svg>

                        <span>Add Funds</span>
                      </Link>
                    </li>

                    <li class="mb-3">
                      <Link
                        aria-current="page"
                        class="inline-flex items-center rounded-t-lg hover:text-custom-green group"
                        :class="{
                          'text-custom-green': route().current('billing.autopay.index'),
                        }"
                        href=""
                      >
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke-width="1.5"
                          stroke="currentColor"
                          class="w-4 h-4 mr-2"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"
                          />
                        </svg>
                        <span>Autopay</span>
                        <div class="p-2">
                          <span
                            class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20"
                            >Soon</span
                          >
                        </div>
                      </Link>
                    </li>
                  </ul>
                </div>
              </ResponsiveNavLink>

              <ResponsiveNavLink
                :href="route('support.index')"
                :active="route().current('support.index')"
              >
                Support
              </ResponsiveNavLink>

              <ResponsiveNavLink
                v-if="$page.props.auth.role === 'internal-agent'"
                :href="route('promotion-guidelines.show')"
                :active="route().current('promotion-guidelines.show')"
                :class="{ 'opacity-50': disabledNavLink === true }"
                :disabledNavLink="disabledNavLink"
              >
                Promotion Guidelines
              </ResponsiveNavLink>

              <ResponsiveNavLink
                :href="route('activities.index')"
                :active="
                  route().current('activities.index') ||
                  route().current('transactions.index') ||
                  route().current('profile.view') ||
                  route().current('profile.edit')
                "
              >
                <div class="row pb-3 flex">
                  <div class="columns-6 flex">Settings</div>
                  <div class="columns-6 flex pl-20">
                    <svg
                      v-if="
                        route().current('activities.index') ||
                        route().current('transactions.index') ||
                        route().current('profile.view') ||
                        route().current('profile.edit')
                      "
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      class="w-6 h-6"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                      />
                    </svg>
                  </div>
                </div>
                <div
                  v-if="
                    route().current('activities.index') ||
                    route().current('transactions.index') ||
                    route().current('profile.view') ||
                    route().current('profile.edit')
                  "
                  class="pl-14 text-white text-xs mb-5"
                >
                  <ul>
                    <li class="mb-3">
                      <Link
                        href="/usage-activities"
                        class="inline-flex items-center rounded-t-lg hover:text-custom-green"
                        :class="{
                          'text-custom-green': route().current('activities.index'),
                        }"
                      >
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke-width="1.5"
                          stroke="currentColor"
                          class="w-4 h-4 mr-2"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"
                          />
                        </svg>

                        <span>Activities</span>
                      </Link>
                    </li>

                    <li class="mb-3">
                      <Link
                        :style="{
                          'pointer-events': disabledNavLink ? 'none' : 'pointer',
                        }"
                        href="/transactions"
                        class="inline-flex items-center rounded-t-lg hover:text-custom-green"
                        :class="{
                          'opacity-25': disabledNavLink === true,
                          'text-custom-green': route().current('transactions.index'),
                        }"
                      >
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke-width="1.5"
                          stroke="currentColor"
                          class="w-4 h-4 mr-2"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"
                          />
                        </svg>

                        <span>Transactions</span>
                      </Link>
                    </li>

                    <li class="mb-3">
                      <Link
                        href="/profile/view"
                        class="inline-flex items-center rounded-t-lg hover:text-custom-green"
                        :class="{
                          'text-custom-green': route().current('profile.view'),
                        }"
                      >
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke-width="1.5"
                          stroke="currentColor"
                          class="w-4 h-4 mr-2"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"
                          />
                        </svg>

                        <span>Profile</span>
                      </Link>
                    </li>
                  </ul>
                </div>
              </ResponsiveNavLink>

              <div
                class="w-full rounded-md bg-custom-indigo shadow-lg focus:outline-none"
              >
                <div
                  class="text-lg font-bold px-3 py-2 text-white"
                  @click.prevent="showMobileNotifications = !showMobileNotifications"
                >
                  Notifications
                </div>

                <!-- Unread Notifications -->
                <div v-if="showMobileNotifications && unreadNotifications.length">
                  <div
                    class="px-3 py-2 text-sm text-white font-bold flex items-center justify-between"
                  >
                    <div class="flex items-center">
                      <span class="mr-1 font-bold text-white">Unread</span>
                      <span
                        style="font-size: 10px"
                        class="bg-gray-600 rounded-full text-white w-4 h-4 flex items-center justify-center"
                      >
                        {{ unreadNotifications.length }}
                      </span>
                    </div>
                    <span
                      class="cursor-pointer text-xs py-2 text-white hover:text-gray-400"
                      @click.prevent="markAllAsRead"
                    >
                      Mark All As Read
                    </span>
                  </div>

                  <!-- Unread Notification Items -->
                  <div style="max-height: 400px; overflow-y: auto">
                    <div
                      v-for="(notification, index) in unreadNotifications"
                      :key="notification.id"
                      class="p-1 cursor-pointer hover:bg-custom-blue hover:text-white"
                    >
                      <div class="flex flex-col gap-1 p-2 rounded-md">
                        <p class="text-sm font-semibold text-white">
                          {{ notification.data.title }}
                        </p>
                        <p class="text-xs text-white">{{ notification.data.body }}</p>
                        <p style="font-size: 9px" class="text-white">
                          {{ notification.created_at_diff }}
                        </p>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- All Notifications -->
                <div
                  v-if="
                    showMobileNotifications &&
                    userNotifications &&
                    userNotifications.length
                  "
                >
                  <div
                    class="px-3 py-2 text-sm text-white font-bold flex items-center justify-between"
                  >
                    <div class="flex items-center">
                      <span class="mr-1 font-bold text-white">All</span>
                      <span
                        style="font-size: 10px"
                        class="bg-gray-600 rounded-full text-white w-4 h-4 flex items-center justify-center"
                      >
                        {{ userNotifications.length }}
                      </span>
                    </div>
                    <span
                      class="cursor-pointer text-xs py-2 text-white hover:text-gray-400"
                      @click.prevent="clearAllNotifications"
                    >
                      Clear All
                    </span>
                  </div>

                  <!-- Notification Items -->
                  <div style="max-height: 400px; overflow-y: auto">
                    <div
                      v-for="(notification, index) in userNotifications"
                      :key="notification.id"
                      class="p-1 cursor-pointer hover:bg-custom-blue hover:text-white"
                    >
                      <div class="flex flex-col gap-1 p-2 rounded-md">
                        <p class="text-sm font-semibold text-white">
                          {{ notification.data.title }}
                        </p>
                        <p class="text-xs text-white">{{ notification.data.body }}</p>
                        <p style="font-size: 9px" class="text-white">
                          {{ notification.created_at_diff }}
                        </p>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- No Notifications Message -->
                <div
                  v-if="showMobileNotifications && !userNotifications.length"
                  class="text-center py-3 text-md text-white"
                >
                  You're all caught up!
                </div>
              </div>
            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
              <div class="px-4">
                <div class="font-medium text-base text-gray-200">
                  {{
                    $page.props.auth.user.first_name +
                    " " +
                    $page.props.auth.user.last_name
                  }}
                </div>
                <div class="font-medium text-sm text-gray-400">
                  {{ $page.props.auth.user.email }}
                </div>
              </div>

              <div class="mt-3 space-y-1">
                <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                  Log Out
                </ResponsiveNavLink>
              </div>
            </div>
          </div>
        </nav>
        <div
          class="w-full mx-auto md:grid md:grid-cols-5 md:gap-28 md:max-w-screen-2xl xl:gap-0"
        >
          <div class="py-12 hidden sm:-my-px sm:ml-10 col-span-1 md:flex md:flex-col">
            <NavLink
              class="mb-10 gap-2"
              :class="{ 'opacity-50': disabledNavLink === true }"
              :disabledNavLink="disabledNavLink"
              :href="route('take-calls.show')"
              :active="route().current('take-calls.show')"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-8 h-8 mr-2"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"
                />
              </svg>
              Take Calls
            </NavLink>

            <NavLink
              class="mb-10 gap-2"
              :class="{ 'opacity-50': disabledNavLink === true }"
              :disabledNavLink="disabledNavLink"
              :href="route('clients.index')"
              :active="route().current('clients.index')"
            >
              <img src="/img/clients.png" alt="" />
              Clients
            </NavLink>

            <NavLink
              v-if="$page.props.auth.role === 'internal-agent'"
              :class="{ 'opacity-50': disabledNavLink === true }"
              :disabledNavLink="disabledNavLink"
              class="mb-10 gap-2"
              :href="route('internal-agent.agent-agency.index')"
              :active="route().current('internal-agent.agent-agency.index')"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-8 h-8 mr-2"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"
                />
              </svg>
              My Agency
            </NavLink>

            <NavLink
              v-if="$page.props.auth.role === 'internal-agent'"
              :class="{ 'opacity-50': disabledNavLink === true }"
              :disabledNavLink="disabledNavLink"
              class="mb-10 gap-2"
              :href="route('agent.my.business')"
              :active="route().current('agent.my.business')"
            >
              <svg
                fill="#fff"
                class="w-8 h-8 mr-2"
                viewBox="0 0 50 50"
                xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink"
                stroke="#fff"
              >
                <path
                  d="M7 2L7 7L2 7L2 46L14 46L14 44L4 44L4 9L9 9L9 4L29 4L29 7L11 7L11 9L34 9L34 20L36 20L36 7L31 7L31 2 Z M 8 12L8 14L12 14L12 12 Z M 17 12L17 14L21 14L21 12 Z M 26 12L26 14L30 14L30 12 Z M 8 17L8 19L12 19L12 17 Z M 17 17L17 19L21 19L21 17 Z M 26 17L26 19L30 19L30 17 Z M 29 20C25.742188 20 23.328125 21.324219 22.074219 23.296875C20.929688 25.09375 20.875 27.339844 21.59375 29.433594C21.515625 29.566406 21.402344 29.679688 21.328125 29.839844C21.171875 30.191406 21.035156 30.589844 21.054688 31.097656L21.054688 31.101563C21.109375 32.378906 21.851563 33.046875 22.398438 33.421875C22.628906 34.640625 23.207031 35.660156 24 36.390625L24 38.53125C23.824219 38.953125 23.472656 39.308594 22.796875 39.679688C22.089844 40.070313 21.132813 40.4375 20.144531 40.917969C19.15625 41.398438 18.125 42.011719 17.324219 42.988281C16.519531 43.96875 16 45.308594 16 47L16 48L48.050781 48L47.992188 46.941406C47.902344 45.363281 47.316406 44.117188 46.488281 43.222656C45.664063 42.328125 44.644531 41.765625 43.679688 41.320313C42.714844 40.875 41.785156 40.535156 41.109375 40.171875C40.464844 39.832031 40.148438 39.511719 40 39.160156L40 37.472656C40.597656 36.609375 40.859375 35.617188 40.9375 34.6875C41.414063 34.265625 41.96875 33.617188 42.125 32.457031C42.230469 31.625 42.019531 30.996094 41.695313 30.464844C42.144531 29.277344 42.328125 27.84375 41.933594 26.417969C41.707031 25.589844 41.277344 24.777344 40.5625 24.171875C40.003906 23.691406 39.238281 23.425781 38.390625 23.308594L37.75 22L37.125 22C36.097656 22 35.085938 22.238281 34.214844 22.578125C33.871094 22.714844 33.558594 22.863281 33.265625 23.027344C33.101563 22.808594 32.921875 22.601563 32.714844 22.414063C32.105469 21.863281 31.261719 21.550781 30.324219 21.421875L29.621094 20 Z M 8 22L8 24L12 24L12 22 Z M 17 22L17 24L19.484375 24L20.761719 22 Z M 28.4375 22.113281L29.027344 23.300781L29.644531 23.300781C30.464844 23.300781 30.96875 23.535156 31.371094 23.894531C31.773438 24.257813 32.066406 24.796875 32.238281 25.429688C32.582031 26.695313 32.289063 28.339844 32.007813 28.792969L31.644531 29.371094L32.050781 29.921875C32.289063 30.238281 32.441406 30.566406 32.363281 31.007813C32.253906 31.625 32.03125 31.707031 31.589844 32.089844L31.257813 32.375L31.246094 32.8125C31.210938 33.792969 30.871094 34.777344 30.300781 35.339844L30 35.632813L30 38.988281L30.058594 39.152344C30.453125 40.25 31.335938 40.933594 32.234375 41.429688C33.132813 41.925781 34.101563 42.289063 34.976563 42.714844C35.851563 43.140625 36.609375 43.625 37.132813 44.261719C37.496094 44.699219 37.71875 45.289063 37.855469 46L18.144531 46C18.28125 45.289063 18.503906 44.699219 18.867188 44.261719C19.390625 43.625 20.148438 43.140625 21.023438 42.714844C21.898438 42.289063 22.867188 41.925781 23.765625 41.429688C24.664063 40.933594 25.546875 40.25 25.941406 39.152344L26 38.988281L26 35.523438L25.5625 35.226563C25.101563 34.914063 24.34375 33.769531 24.238281 32.742188L24.183594 32.1875L23.683594 31.945313C23.398438 31.808594 23.082031 31.753906 23.050781 31.015625C23.050781 31.015625 23.082031 30.824219 23.15625 30.65625C23.234375 30.484375 23.375 30.304688 23.332031 30.347656L23.8125 29.867188L23.542969 29.242188C22.796875 27.523438 22.898438 25.722656 23.761719 24.367188C24.550781 23.125 26.097656 22.269531 28.4375 22.113281 Z M 36.558594 24.113281L37.089844 25.199219L37.714844 25.199219C38.472656 25.199219 38.921875 25.398438 39.265625 25.691406C39.613281 25.984375 39.859375 26.414063 40.003906 26.949219C40.300781 28.019531 40.085938 29.480469 39.746094 30.144531L39.417969 30.796875L39.933594 31.308594C39.867188 31.242188 40.195313 31.785156 40.140625 32.195313C40.011719 33.175781 39.871094 33.113281 39.449219 33.390625L39.03125 33.667969L39 34.171875C38.953125 35.042969 38.515625 36.351563 38.28125 36.589844L38 36.878906L38 39.621094L38.058594 39.78125C38.4375 40.835938 39.296875 41.476563 40.167969 41.9375C41.035156 42.398438 41.980469 42.738281 42.84375 43.136719C43.707031 43.535156 44.476563 43.984375 45.019531 44.578125C45.367188 44.953125 45.601563 45.433594 45.769531 46L39.921875 46C39.757813 44.777344 39.3125 43.765625 38.675781 42.988281C37.875 42.011719 36.84375 41.398438 35.855469 40.917969C34.867188 40.4375 33.910156 40.070313 33.203125 39.679688C32.527344 39.308594 32.175781 38.953125 32 38.53125L32 36.296875C32.691406 35.421875 33.054688 34.390625 33.15625 33.34375C33.542969 33.003906 34.144531 32.417969 34.332031 31.359375C34.484375 30.492188 34.226563 29.785156 33.90625 29.210938C34.4375 27.988281 34.59375 26.460938 34.167969 24.902344C34.164063 24.886719 34.15625 24.871094 34.152344 24.855469C34.367188 24.71875 34.640625 24.5625 34.949219 24.441406C35.4375 24.25 36.007813 24.179688 36.558594 24.113281 Z M 8 27L8 29L12 29L12 27 Z M 17 27L17 29L19.753906 29L19.394531 27 Z M 8 32L8 34L12 34L12 32 Z M 17 32L17 34L20.449219 34L19.613281 32 Z M 8 37L8 39L12 39L12 37 Z M 17 37L17 39L21 39L21 37Z"
                ></path>
              </svg>
              My Business
            </NavLink>

            <NavLink
              v-if="$page.props.auth.role === 'internal-agent'"
              class="mb-10 gap-2"
              :href="route('training.index')"
              :active="route().current('training.index')"
            >
              <svg
                fill="#fff"
                class="w-8 h-8 mr-2"
                version="1.1"
                id="Layer_1"
                xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink"
                viewBox="0 0 496 496"
                xml:space="preserve"
              >
                <g>
                  <g>
                    <g>
                      <path
                        d="M432,336h-10.84c16.344-13.208,26.84-33.392,26.84-56v-32c0-30.872-25.128-56-56-56h-32c-2.72,0-5.376,0.264-8,0.64V48
                      h16V0H0v48h16v232H0v48h187.056l40,80H304v88h192v-96C496,364.712,467.288,336,432,336z M422.584,371.328l-32.472,13.92
                      L412.28,352h5.472L422.584,371.328z M358.944,352h34.112L376,377.576L358.944,352z M361.872,385.248l-32.472-13.92L334.24,352
                      h5.472L361.872,385.248z M432.008,280C432,310.872,406.872,336,376,336s-56-25.128-56-56h37.424
                      c14.12,0,27.392-5.504,37.368-15.48l4.128-4.128c8.304,10.272,20.112,16.936,33.088,18.92V280z M48,192v72h107.176
                      c0.128,0.28,0.176,0.584,0.312,0.856L163.056,280H32V48h304v149.48c-18.888,9.008-32,28.24-32,50.52v32h-65.064l-24.816-46.528
                      C208.368,222.696,197.208,216,185,216c-10.04,0-18.944,4.608-25,11.712V192H48z M144,208v40H64v-40H144z M360,208h32
                      c22.056,0,40,17.944,40,40v15.072c-9.168-2.032-17.32-7.48-22.656-15.48l-8.104-12.152l-17.768,17.768
                      c-6.96,6.96-16.208,10.792-26.048,10.792H320v-16C320,225.944,337.944,208,360,208z M16,16h336v16H16V16z M16,312v-16h155.056
                      l8,16H16z M256,392h-19.056L169.8,257.712c-1.176-2.36-1.8-4.992-1.8-7.608V249c0-9.376,7.624-17,17-17c6.288,0,12.04,3.456,15,9
                      l56,104.992V392z M247.464,296h58.392c1.28,5.616,3.232,10.968,5.744,16H256L247.464,296z M264.536,328h57.952
                      c2.584,2.872,5.352,5.568,8.36,8H268.8L264.536,328z M480,480h-32v-32h32V480z M480,432h-32v-32h-16v80H320v-88h-48v-40h45.76
                      l-7.168,28.672L376,408.704l65.416-28.032l-7.144-28.56C459.68,353.312,480,374.296,480,400V432z"
                      />
                      <path
                        d="M160,128v-16h-16.808c-1.04-5.096-3.072-9.832-5.856-14.024l11.92-11.92l-11.312-11.312l-11.92,11.92
                      c-4.192-2.784-8.928-4.816-14.024-5.856V64H96v16.808c-5.096,1.04-9.832,3.072-14.024,5.856l-11.92-11.92L58.744,86.056
                      l11.92,11.92c-2.784,4.192-4.816,8.928-5.856,14.024H48v16h16.808c1.04,5.096,3.072,9.832,5.856,14.024l-11.92,11.92
                      l11.312,11.312l11.92-11.92c4.192,2.784,8.928,4.816,14.024,5.856V176h16v-16.808c5.096-1.04,9.832-3.072,14.024-5.856
                      l11.92,11.92l11.312-11.312l-11.92-11.92c2.784-4.192,4.816-8.928,5.856-14.024H160z M104,144c-13.232,0-24-10.768-24-24
                      s10.768-24,24-24s24,10.768,24,24S117.232,144,104,144z"
                      />
                      <polygon
                        points="244.28,80 272,80 272,64 235.72,64 203.72,112 176,112 176,128 212.28,128 			"
                      />
                      <rect x="288" y="64" width="32" height="16" />
                      <path d="M224,144h-48v48h48V144z M208,176h-16v-16h16V176z" />
                      <rect x="240" y="160" width="32" height="16" />
                      <rect x="288" y="160" width="32" height="16" />
                    </g>
                  </g>
                </g>
              </svg>
              Training
            </NavLink>

            <!--
            <NavLink v-if="$page.props.auth.role === 'internal-agent'" class="mb-10 gap-2" :href="route('internal-agent.my-agent.index')"
              :active="route().current('internal-agent.my-agent.index')">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-8 h-8 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
              </svg>
              Registered Agents
            </NavLink> -->

            <!-- <NavLink class="mb-10 gap-2" id="billing-nav-link" :disabledNavLink="disabledNavLink"
              :href="route('billing.funds.index')" :active="route().current('billing.funds.index') ||
                route().current('billing.cards.index') ||
                route().current('billing.autopay.index')
                " :class="{
                'opacity-25': disabledNavLink === true,
                'mb-5':
                  route().current('billing.funds.index') ||
                  route().current('billing.cards.index') ||
                  route().current('billing.autopay.index'),
              }"
              v-if="!isInternalLevel"
            >
              <img src="/img/billing.png" alt="" />
              Add Funds 

              <svg v-if="route().current('billing.funds.index') ||
                route().current('billing.cards.index') ||
                route().current('billing.autopay.index')
                " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
              </svg>
            </NavLink> -->

            <NavLink
              v-if="!isInternalLevel"
              class="mb-10 gap-2"
              id="billing-nav-link"
              :href="route('billing.funds.index')"
              :active="
                route().current('billing.funds.index') ||
                route().current('billing.cards.index') ||
                route().current('billing.autopay.index')
              "
              :class="{
                'mb-5':
                  route().current('billing.funds.index') ||
                  route().current('billing.cards.index') ||
                  route().current('billing.autopay.index'),
              }"
            >
              <img src="/img/billing.png" alt="" />
              Add Funds

              <svg
                v-if="
                  route().current('billing.funds.index') ||
                  route().current('billing.cards.index') ||
                  route().current('billing.autopay.index')
                "
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-6 h-6"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                />
              </svg>
            </NavLink>

            <div
              v-if="
                route().current('billing.funds.index') ||
                route().current('billing.cards.index') ||
                route().current('billing.autopay.index')
              "
              class="pl-14 text-white text-xs mb-5"
            >
              <ul>
                <li class="mb-3">
                  <Link
                    href="/billing/funds"
                    class="inline-flex items-center rounded-t-lg hover:text-custom-green"
                    :class="{
                      'text-custom-green': route().current('billing.funds.index'),
                    }"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      class="w-4 h-4 mr-2"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"
                      />
                    </svg>

                    <span>Add Funds</span>
                  </Link>
                </li>

                <li class="mb-3">
                  <Link
                    aria-current="page"
                    class="inline-flex items-center rounded-t-lg hover:text-custom-green group"
                    :class="{
                      'text-custom-green': route().current('billing.autopay.index'),
                    }"
                    href=""
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      class="w-4 h-4 mr-2"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"
                      />
                    </svg>
                    <span>Autopay</span>
                    <div class="p-2">
                      <span
                        class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20"
                        >Soon</span
                      >
                    </div>
                  </Link>
                </li>

                <li>
                  <Link
                    href="/billing/cards"
                    class="inline-flex items-center rounded-t-lg hover:text-custom-green group"
                    :class="{
                      'text-custom-green': route().current('billing.cards.index'),
                    }"
                    aria-current="page"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      class="w-4 h-4 mr-2"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"
                      />
                    </svg>

                    <span>Saved Cards</span>
                  </Link>
                </li>
              </ul>
            </div>

            <NavLink
              class="mb-10 gap-2"
              :class="{ 'opacity-50': disabledNavLink === true }"
              :disabledNavLink="disabledNavLink"
              :href="route('calls.index')"
              :active="route().current('calls.index')"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-8 h-8 mr-2"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"
                />
              </svg>

              Call Reporting
            </NavLink>

            <NavLink
              v-if="$page.props.auth.role === 'internal-agent'"
              :class="{ 'opacity-50': disabledNavLink === true }"
              :disabledNavLink="disabledNavLink"
              class="mb-10 gap-2"
              :href="route('additional-files.index')"
              :active="route().current('additional-files.index')"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-8 h-8 mr-2"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M12 10.5v6m3-3H9m4.06-7.19l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z"
                />
              </svg>

              Additional Files
            </NavLink>

            <NavLink
              class="mb-10 gap-2"
              :href="route('support.index')"
              :active="route().current('support.index')"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                style="height: 38px; width: 38px"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"
                />
              </svg>

              Support
            </NavLink>

            <NavLink
              v-if="$page.props.auth.role === 'internal-agent'"
              :class="{ 'opacity-50': disabledNavLink === true }"
              :disabledNavLink="disabledNavLink"
              class="mb-10 gap-2"
              :href="route('promotion-guidelines.show')"
              :active="route().current('promotion-guidelines.show')"
            >
              <svg
                class="w-8 h-8 mr-2"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"
                />
              </svg>

              Promotion Guidelines
            </NavLink>

            <NavLink
              class="mb-10 gap-2"
              id="billing-nav-link"
              :href="route('activities.index')"
              :active="
                route().current('activities.index') ||
                route().current('transactions.index') ||
                route().current('profile.view') ||
                route().current('profile.edit')
              "
              :class="{
                'mb-5':
                  route().current('activities.index') ||
                  route().current('transactions.index') ||
                  route().current('profile.view') ||
                  route().current('profile.edit'),
              }"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-8 h-8 mr-2"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"
                />
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                />
              </svg>
              Settings

              <svg
                v-if="
                  route().current('activities.index') ||
                  route().current('transactions.index') ||
                  route().current('profile.view') ||
                  route().current('profile.edit')
                "
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-6 h-6"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                />
              </svg>
            </NavLink>

            <div
              v-if="
                route().current('activities.index') ||
                route().current('transactions.index') ||
                route().current('profile.view') ||
                route().current('profile.edit')
              "
              class="pl-14 text-white text-xs mb-5"
            >
              <ul>
                <li class="mb-3">
                  <Link
                    :href="route('activities.index')"
                    class="inline-flex items-center rounded-t-lg hover:text-custom-green"
                    :class="{
                      'text-custom-green': route().current('activities.index'),
                    }"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      class="w-4 h-4 mr-2"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15.042 21.672L13.684 16.6m0 0l-2.51 2.225.569-9.47 5.227 7.917-3.286-.672zM12 2.25V4.5m5.834.166l-1.591 1.591M20.25 10.5H18M7.757 14.743l-1.59 1.59M6 10.5H3.75m4.007-4.243l-1.59-1.59"
                      />
                    </svg>

                    <span>Activities</span>
                  </Link>
                </li>

                <li v-if="$page.props.auth.role === 'internal-agent'" class="mb-3">
                  <Link
                    :style="{ 'pointer-events': disabledNavLink ? 'none' : 'pointer' }"
                    aria-current="page"
                    class="inline-flex items-center rounded-t-lg hover:text-custom-green group"
                    :class="{
                      'opacity-25': disabledNavLink === true,
                      'text-custom-green': route().current('transactions.index'),
                    }"
                    :href="route('transactions.index')"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      class="w-4 h-4 mr-2"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                      />
                    </svg>

                    <span>Transactions</span>
                  </Link>
                </li>

                <li class="mb-3">
                  <Link
                    aria-current="page"
                    class="inline-flex items-center rounded-t-lg hover:text-custom-green group"
                    :class="{
                      'text-custom-green':
                        route().current('profile.view') ||
                        route().current('profile.edit'),
                    }"
                    :href="route('profile.view')"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      class="w-4 h-4 mr-2"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"
                      />
                    </svg>

                    <span>Profile</span>
                  </Link>
                </li>
              </ul>
            </div>
          </div>
          <!-- Page Content -->
          <main class="col-span-4 bg-white rounded-xl mt-14 mb-10">
            <slot />
          </main>
        </div>
      </div>
      <!-- User Navigation Menu -->

      <!-- <DownloadAppModal :appDownloadModal="appDownloadModal" @close="appDownloadModal = false" /> -->

      <Modal :show="appDownloadModal" @close="appDownloadModal = false">
        <div class="bg-white p-4 rounded-lg shadow-lg">
          <h2 class="text-xl text-center mb-4 font-bold">Download AllCalls Mobile App</h2>

          <div>
            <a href="https://play.google.com/store/apps/details?id=io.allcalls"
              ><img style="max-width: 200px; margin: auto" src="/img/google-store.png"
            /></a>
            <a href="https://apps.apple.com/us/app/allcalls-io/id6464440586"
              ><img style="max-width: 200px; margin: auto" src="/img/app-store.png"
            /></a>
          </div>
        </div>
      </Modal>

      <DashboardFooter></DashboardFooter>
    </div>

    <Modal :show="showRinging" maxWidth="sm" :closeable="false">
      <div class="bg-white p-8 py-24 rounded-lg shadow-xl w-full">
        <div class="flex flex-col items-center">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="currentColor"
            class="w-16 h-16 text-green-700 animate-pulse mb-4"
          >
            <path
              fill-rule="evenodd"
              d="M19.5 9.75a.75.75 0 01-.75.75h-4.5a.75.75 0 01-.75-.75v-4.5a.75.75 0 011.5 0v2.69l4.72-4.72a.75.75 0 111.06 1.06L16.06 9h2.69a.75.75 0 01.75.75z"
              clip-rule="evenodd"
            />
            <path
              fill-rule="evenodd"
              d="M1.5 4.5a3 3 0 013-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 01-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 006.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 011.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 01-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5z"
              clip-rule="evenodd"
            />
          </svg>

          <h1 class="text-2xl font-bold text-black">Incoming Call</h1>
          <p class="text-md text-gray-600 mt-2">AllCalls Client</p>

          <div class="flex mt-20 space-x-10">
            <div
              @click="rejectCall()"
              class="bg-red-500 hover:bg-red-600 p-3 rounded-full"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-6 h-6 text-white cursor-pointer"
                style="transform: rotate(133deg)"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"
                />
              </svg>
            </div>
            <div
              @click="acceptCall()"
              class="bg-green-500 hover:bg-green-400 p-3 rounded-full"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-6 h-6 text-white cursor-pointer"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"
                />
              </svg>
            </div>
          </div>
        </div>
      </div>
    </Modal>

    <Modal :show="showOngoing" maxWidth="lg" :closeable="false">
      <!-- <Modal :show="true" maxWidth="lg" :closeable="false"> -->
      <div
        class="flex flex-col items-center justify-between h-full p-8 bg-white space-y-8"
      >
        <!-- Call Duration -->
        <div>
          <p class="text-2xl font-medium text-black" v-text="callDuration"></p>
        </div>

        <h3 class="text-2xl font-medium">Ongoing Call</h3>

        <!-- Client's Basic Info -->
        <div v-if="connectedClient && !hasSixtySecondsPassed" class="w-full">
          <p class="text-md text-center text-black mb-2">Client's Basic Info</p>
          <ul class="w-full p-4 bg-gray-100 rounded-md space-y-2">
            <li class="flex justify-between">
              <span class="text-gray-600">Name:</span>
              {{
                connectedClient.first_name || connectedClient.last_name
                  ? connectedClient.first_name + " " + connectedClient.last_name
                  : "N/A"
              }}
            </li>
            <li class="flex justify-between">
              <span class="text-gray-600">State:</span>
              <span class="text-black">{{ connectedClient.state }}</span>
            </li>
          </ul>
        </div>

        <!-- Info Populating After 60 seconds -->
        <div v-if="connectedClient && !hasSixtySecondsPassed" class="w-full">
          <p class="text-md text-center text-black mb-2">
            Info will populate after 60 seconds
          </p>
          <ul class="w-full p-4 bg-gray-100 rounded-md space-y-2">
            <li class="flex justify-between">
              <span class="text-gray-600">Email:</span>
              <span
                class="text-black"
                v-if="hasSixtySecondsPassed"
                v-text="connectedClient.email"
              ></span>
              <span class="text-black" v-else>
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  class="w-6 h-6"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"
                  />
                </svg>
              </span>
            </li>
            <li class="flex justify-between">
              <span class="text-gray-600">Date of Birth:</span>
              <span class="text-black" v-if="hasSixtySecondsPassed">{{
                connectedClient.dob
              }}</span>
              <span class="text-black" v-else>
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  class="w-6 h-6"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"
                  />
                </svg>
              </span>
            </li>
            <li class="flex justify-between">
              <span class="text-gray-600">Phone:</span>
              <span class="text-black" v-if="hasSixtySecondsPassed">{{
                connectedClient.phone
              }}</span>
              <span class="text-black" v-else>
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  class="w-6 h-6"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"
                  />
                </svg>
              </span>
            </li>
            <li class="flex justify-between">
              <span class="text-gray-600">Address:</span>
              <span class="text-black" v-if="hasSixtySecondsPassed">{{
                connectedClient.address
              }}</span>
              <span class="text-black" v-else>
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  class="w-6 h-6"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"
                  />
                </svg>
              </span>
            </li>
            <li class="flex justify-between">
              <span class="text-gray-600">Zip code:</span>
              <span class="text-black" v-if="hasSixtySecondsPassed">{{
                connectedClient.zipCode
              }}</span>
              <span class="text-black" v-else>
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  class="w-6 h-6"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"
                  />
                </svg>
              </span>
            </li>
          </ul>
        </div>

        <div v-if="connectedClient && hasSixtySecondsPassed" class="w-full bg-gray-100">
          <ul class="w-full p-4 rounded-md space-y-2">
            <li class="flex justify-between items-center">
              <span class="text-gray-600">First Name:</span>
              <TextInput style="width: 200px" v-model="connectedClient.first_name" />
            </li>
            <li class="flex justify-between items-center">
              <span class="text-gray-600">Last Name:</span>
              <TextInput style="width: 200px" v-model="connectedClient.last_name" />
            </li>
            <li class="flex justify-between items-center">
              <span class="text-gray-600">Email:</span>
              <TextInput style="width: 200px" v-model="connectedClient.email" />
            </li>
            <li class="flex justify-between items-center">
              <span class="text-gray-600">Phone:</span>
              <TextInput style="width: 200px" v-model="connectedClient.phone" />
            </li>
            <li class="flex justify-between items-center">
              <span class="text-gray-600">Date of Birth:</span>
              <TextInput style="width: 200px" v-model="connectedClient.dob" />
            </li>
            <li class="flex justify-between items-center">
              <span class="text-gray-600">Address:</span>
              <TextInput style="width: 200px" v-model="connectedClient.address" />
            </li>
            <li class="flex justify-between items-center">
              <span class="text-gray-600">State:</span>
              <TextInput style="width: 200px" v-model="connectedClient.state" />
            </li>
            <li class="flex justify-between items-center">
              <span class="text-gray-600">Zip Code:</span>
              <TextInput style="width: 200px" v-model="connectedClient.zipCode" />
            </li>
          </ul>

          <div class="flex justify-center py-3">
            <PrimaryButton @click.prevent="saveClient">Save Changes</PrimaryButton>
          </div>
        </div>

        <!-- Hang Up Button -->
        <div>
          <button
            @click.prevent="disconnectCall()"
            class="bg-red-500 hover:bg-red-400 text-white rounded-full py-2 px-6"
          >
            Hang Up
          </button>
        </div>
      </div>
    </Modal>

    <Modal :show="showUpdateDispositionForLastClient" :closeable="false">
      <div class="bg-white">
        <div class="p-4 my-3">
          <div class="mb-3">
            <label class="mb-2">Please update the client disposition for the call:</label>
            <select class="select-custom" v-model="latestClientDisposition">
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
              <option value="Not Qualified Nursing Home">
                Not Qualified Nursing Home
              </option>
              <option value="Not Qualified Memory Issues">
                Not Qualified Memory Issues
              </option>
              <option value="Language Barrier">Language Barrier</option>
              <option value="Do Not Call">Do Not Call</option>
            </select>
          </div>

          <div class="flex justify-end">
            <PrimaryButton @click.prevent="updateLatestClientDisposition"
              >Save Disposition</PrimaryButton
            >
          </div>
        </div>
      </div>
    </Modal>
  </div>
</template>
