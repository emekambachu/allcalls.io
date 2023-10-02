<script setup>
import { ref, reactive, onMounted, onUnmounted } from "vue";
import { Link } from "@inertiajs/vue3";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import DashboardFooter from "@/Components/DashboardFooter.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import Modal from "@/Components/Modal.vue";
import { Device } from "@twilio/voice-sdk";
import { usePage } from "@inertiajs/vue3";


let page = usePage();




let connectedClient = ref(null);
let showRinging = ref(false);
let showOngoing = ref(false);
let call = reactive(null);

let showIncomingCall = (conn) => {
  console.log("show incoming call now");
  console.log(conn);


  console.log(conn.customParameters.unique_call_id);

  axios.get("/call-client-info?unique_call_id=" + conn.customParameters.unique_call_id).then((response) => {
    console.log(response.data.client);
    connectedClient.value = response.data.client;

    console.log('connected client now: ');
    console.log(connectedClient.value);
  });

  showRinging.value = true;
};

let acceptCall = () => {
  console.log('accept call now');
  Echo.private('calls.' + page.props.auth.user.id).whisper('psst', {
    action: 'accept'
  });

  if (call) {
    call.accept();
    showRinging.value = false;
    showOngoing.value = true;
  } else {
    console.log('call not found');
  }
};

let rejectCall = () => {
  console.log('reject call now');
  Echo.private('calls.' + page.props.auth.user.id).whisper('psst', {
    action: 'reject'
  });

  if (call) {
    call.reject();
    showRinging.value = false;
  } else {
    console.log('call not found while rejecting')
  }
}

let disconnectCall = () => {
  console.log('disconnect call now');
  Echo.private('calls.' + page.props.auth.user.id).whisper('psst', {
    action: 'disconnect'
  });
  if (call) {
    call.disconnect();
    showOngoing.value = false;
  } else {
    console.log('call not found while disconnecting')
  }
}

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
      console.log("Call ended.");
    });

    device.on("incoming", incomingCall => {
      console.log("Incoming!");
      call = incomingCall;
      showIncomingCall(call);
    });

    device.register();
  });
  
};






onMounted(() => {
  console.log("mounted AuthenticatedLayout");
  Echo.private("calls." + page.props.auth.user.id)
  .listenForWhisper("psst", e => {
    console.log('call event:');
    console.log(e);
    showRinging.value = false;
    showOngoing.value = false;
  })

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
      <div class="bg-custom-sky text-gray-50 py-2 px-16 flex-col sm:flex-row flex justify-center items-center">
        <div class="mb-2 sm:mb-0 sm:mr-6 text-center sm:text-left">
          Download the mobile app and start buying calls now!
        </div>
        <div>
          <button type="button" @click="appDownloadModal = true"
            class="px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">
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
                    :href="route('admin.dashboard')">
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
                        <button type="button"
                          class="inline-flex items-center text-lg leading-4 font-bold text-gray-300 hover:text-custom-green focus:outline-none transition ease-in-out duration-150">
                          {{
                            $page.props.auth.user.first_name +
                            " " +
                            $page.props.auth.user.last_name
                          }}

                          <svg class="ml-2 -mr-0.5 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                              clip-rule="evenodd" />
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
                <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                  class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                  <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{
                      hidden: showingNavigationDropdown,
                      'inline-flex': !showingNavigationDropdown,
                    }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{
                      hidden: !showingNavigationDropdown,
                      'inline-flex': showingNavigationDropdown,
                    }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
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
          <div :class="{
            block: showingNavigationDropdown,
            hidden: !showingNavigationDropdown,
          }" class="md:hidden">
            <div class="pt-2 pb-3 space-y-1">
              <ResponsiveNavLink :href="route('admin.dashboard')" :active="route().current('admin.dashboard')">
                Dashboard
              </ResponsiveNavLink>
            </div>
            <div class="pt-2 pb-3 space-y-1">
              <ResponsiveNavLink :href="route('admin.customer.index')" :active="route().current('admin.customer.index') ||
                route().current('admin.customer.detail')
                ">
                Customers
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

        <div class="w-full mx-auto md:grid md:grid-cols-5 md:gap-28 md:max-w-screen-2xl xl:gap-0">
          <div class="py-12 hidden sm:-my-px sm:ml-10 col-span-1 md:flex md:flex-col">
            <NavLink class="mb-10 gap-2" :href="route('admin.dashboard')" :active="route().current('admin.dashboard')">
              <img src="/img/dashboard.png" alt="" />
              Dashboard
            </NavLink>
            <NavLink class="mb-10 gap-2" :href="route('admin.calls.index')" :active="route().current('admin.calls.index') ||
              route().current('admin.calls.detail')
              ">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-8 h-8 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
              </svg>
              Calls
            </NavLink>
            <NavLink class="mb-10 gap-2" :href="route('admin.customer.index')" :active="route().current('admin.customer.index') ||
              route().current('admin.customer.detail')
              ">
              <img src="/img/clients.png" alt="" />
              Customers
            </NavLink>
            <NavLink class="mb-10 gap-2" :href="route('admin.agent.index')" :active="route().current('admin.agent.index') ||
              route().current('admin.agent.detail')
              ">
              <img src="/img/clients.png" alt="" />
              Internal Agents
            </NavLink>
            <NavLink class="mb-10 gap-2" :href="route('admin.online-agents.index')"
              :active="route().current('admin.online-agents.index')">
              <img src="/img/clients.png" alt="" />
              Online Agents
            </NavLink>
            <NavLink class="mb-10 gap-2" :href="route('admin.agent-invites.index')"
              :active="route().current('admin.agent-invites.index')">
              <img src="/img/clients.png" alt="" />
              Agent Invites
            </NavLink>
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
                    :href="route('dashboard')">
                  <!-- AllCalls.io -->
                  <img style="max-width: 200px" src="/img/new-logo.png" />
                  </Link>
                </div>
              </div>

              <div class="hidden md:flex md:self-start sm:pt-6 sm:ml-6">
                <div class="flex items-center">
                  <div>
                    <Link href="/billing/funds"
                      class="mr-3 px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">
                    Add Funds
                    </Link>
                  </div>
                  <div class="flex flex-col justify-center items-center">
                    <div class="text-xs leading-4 font-medium rounded-md text-custom-white">
                      Balance
                    </div>
                    <div class="text-xl font-bold text-gray-300">
                      ${{ formatMoney($page.props.auth.user.balance) }}
                    </div>
                  </div>
                </div>

                <!-- Settings Dropdown -->
                <div class="ml-3 relative">
                  <Dropdown align="right" width="48">
                    <template #trigger>
                      <span class="inline-flex rounded-md">
                        <button type="button"
                          class="inline-flex items-center text-lg leading-4 font-bold text-gray-300 hover:text-custom-green focus:outline-none transition ease-in-out duration-150">
                          {{
                            $page.props.auth.user.first_name +
                            " " +
                            $page.props.auth.user.last_name
                          }}

                          <svg class="ml-2 -mr-0.5 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                              clip-rule="evenodd" />
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
                <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                  class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                  <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{
                      hidden: showingNavigationDropdown,
                      'inline-flex': !showingNavigationDropdown,
                    }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{
                      hidden: !showingNavigationDropdown,
                      'inline-flex': showingNavigationDropdown,
                    }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
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
          <div :class="{
            block: showingNavigationDropdown,
            hidden: !showingNavigationDropdown,
          }" class="md:hidden">
            <div class="pt-2 pb-3 space-y-1">
              <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                Dashboard
              </ResponsiveNavLink>

              <ResponsiveNavLink :href="route('clients.index')" :active="route().current('clients.index')">
                Clients
              </ResponsiveNavLink>
              <ResponsiveNavLink :href="route('calls.index')" :active="route().current('calls.index')">
                Calls
              </ResponsiveNavLink>

              <ResponsiveNavLink :href="route('activities.index')" :active="route().current('activities.index') ||
                route().current('activities.index')
                ">
                Activities
              </ResponsiveNavLink>

              <ResponsiveNavLink :href="route('transactions.index')" :active="route().current('transactions.index')">
                Transactions
              </ResponsiveNavLink>

              <ResponsiveNavLink :href="route('billing.funds.index')" :active="route().current('billing.funds.index') ||
                route().current('billing.cards.index') ||
                route().current('billing.autopay.index')
                ">
                <div class="row pb-3 flex">
                  <div class="columns-6 flex">Billing</div>
                  <div class="columns-6 flex pl-20">
                    <svg v-if="route().current('billing.funds.index') ||
                      route().current('billing.cards.index') ||
                      route().current('billing.autopay.index')
                      " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                      stroke="currentColor" class="w-6 h-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                  </div>
                </div>
                <div v-if="route().current('billing.funds.index') ||
                  route().current('billing.cards.index') ||
                  route().current('billing.autopay.index')
                  " class="pl-14 text-white text-xs mb-5">
                  <ul>
                    <li class="mb-3">
                      <Link href="/billing/funds" class="inline-flex items-center rounded-t-lg hover:text-custom-green"
                        :class="{
                          'text-custom-green': route().current('billing.funds.index'),
                        }">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                          d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                      </svg>

                      <span>Add Funds</span>
                      </Link>
                    </li>

                    <li class="mb-3">
                      <Link aria-current="page"
                        class="inline-flex items-center rounded-t-lg hover:text-custom-green group" :class="{
                          'text-custom-green': route().current('billing.autopay.index'),
                        }" href="">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                          d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                      </svg>
                      <span>Autopay</span>
                      <div class="p-2">
                        <span
                          class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">Soon</span>
                      </div>
                      </Link>
                    </li>

                    <!-- <li>
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
                    </li> -->
                  </ul>
                </div>
              </ResponsiveNavLink>

              <ResponsiveNavLink :href="route('support.index')" :active="route().current('support.index')">
                Support
              </ResponsiveNavLink>

              <ResponsiveNavLink :href="route('profile.view')" :active="route().current('profile.view') || route().current('profile.edit')
                ">
                Profile
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
        <div class="w-full mx-auto md:grid md:grid-cols-5 md:gap-28 md:max-w-screen-2xl xl:gap-0">
          <div class="py-12 hidden sm:-my-px sm:ml-10 col-span-1 md:flex md:flex-col">
            <NavLink class="mb-10 gap-2" :href="route('dashboard')" :active="route().current('dashboard')">
              <img src="/img/dashboard.png" alt="" />
              Dashboard
            </NavLink>
            <NavLink class="mb-10 gap-2" :href="route('clients.index')" :active="route().current('clients.index')">
              <img src="/img/clients.png" alt="" />
              Clients
            </NavLink>

            <NavLink class="mb-10 gap-2" :href="route('calls.index')" :active="route().current('calls.index')">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-8 h-8 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
              </svg>

              Calls
            </NavLink>

            <NavLink class="mb-10 gap-2" :href="route('activities.index')" :active="route().current('activities.index')">
              <img src="/img/activity.png" alt="" />
              Activities
            </NavLink>

            <NavLink class="mb-10 gap-2" :href="route('transactions.index')"
              :active="route().current('transactions.index')">
              <img src="/img/transactions.png" alt="" />
              Transactions
            </NavLink>

            <NavLink class="mb-10 gap-2" id="billing-nav-link" :href="route('billing.funds.index')" :active="route().current('billing.funds.index') ||
              route().current('billing.cards.index') ||
              route().current('billing.autopay.index')
              " :class="{
    'mb-5':
      route().current('billing.funds.index') ||
      route().current('billing.cards.index') ||
      route().current('billing.autopay.index'),
  }">
              <img src="/img/billing.png" alt="" />
              Billing

              <svg v-if="route().current('billing.funds.index') ||
                route().current('billing.cards.index') ||
                route().current('billing.autopay.index')
                " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
              </svg>
            </NavLink>

            <div v-if="route().current('billing.funds.index') ||
              route().current('billing.cards.index') ||
              route().current('billing.autopay.index')
              " class="pl-14 text-white text-xs mb-5">
              <ul>
                <li class="mb-3">
                  <Link href="/billing/funds" class="inline-flex items-center rounded-t-lg hover:text-custom-green"
                    :class="{
                      'text-custom-green': route().current('billing.funds.index'),
                    }">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-4 h-4 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                  </svg>

                  <span>Add Funds</span>
                  </Link>
                </li>

                <li class="mb-3">
                  <Link aria-current="page" class="inline-flex items-center rounded-t-lg hover:text-custom-green group"
                    :class="{
                      'text-custom-green': route().current('billing.autopay.index'),
                    }" href="">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-4 h-4 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                  </svg>
                  <span>Autopay</span>
                  <div class="p-2">
                    <span
                      class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">Soon</span>
                  </div>
                  </Link>
                </li>

                <li>
                  <Link href="/billing/cards" class="inline-flex items-center rounded-t-lg hover:text-custom-green group"
                    :class="{
                      'text-custom-green': route().current('billing.cards.index'),
                    }" aria-current="page">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-4 h-4 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />

                  </svg>

                  <span>Saved Cards</span>
                  </Link>
                </li>
              </ul>
            </div>

            <NavLink class="mb-10 gap-2" :href="route('support.index')" :active="route().current('support.index')">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" style="height: 38px; width: 38px">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
              </svg>

              Support
            </NavLink>
            <NavLink class="mb-10 gap-2" :href="route('profile.view')"
              :active="route().current('profile.edit') || route().current('profile.view')">
              <img src="/img/profile.png" alt="" />
              Profile
            </NavLink>
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
            <a href="https://play.google.com/apps/internaltest/4700422138103121465"><img
                style="max-width: 200px; margin: auto" src="/img/google-store.png" /></a>
            <a href="https://testflight.apple.com/join/ZI91F5fv"><img style="max-width: 200px; margin: auto"
                src="/img/app-store.png" /></a>
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
            <div @click="rejectCall()" class="bg-red-500 hover:bg-red-600 p-3 rounded-full">
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
            <div @click="acceptCall()" class="bg-green-500 hover:bg-green-400 p-3 rounded-full">
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
        <!-- <div>
          <p class="text-2xl font-medium text-black">00:05</p>
        </div> -->

        <h3 class="text-2xl font-medium">Ongoing Call</h3>

        <!-- Client's Basic Info -->
        <div class="w-full">
          <p class="text-md text-center text-black mb-2">Client's Basic Info</p>
          <ul class="w-full p-4 bg-gray-100 rounded-md space-y-2">
            <li class="flex justify-between">
              <span class="text-gray-600">Name:</span>
              <span class="text-black">John Doe</span>
            </li>
            <li class="flex justify-between">
              <span class="text-gray-600">Age:</span>
              <span class="text-black">32</span>
            </li>
            <li class="flex justify-between">
              <span class="text-gray-600">Contact:</span>
              <span class="text-black">+1 (234) 567-8900</span>
            </li>
          </ul>
        </div>

        <!-- Info Populating After 60 seconds -->
        <div class="w-full">
          <p class="text-md text-center text-black mb-2">
            Info will populate after 60 seconds
          </p>
          <ul class="w-full p-4 bg-gray-100 rounded-md space-y-2">
            <li class="flex justify-between">
              <span class="text-gray-600">Address:</span>
              <span class="text-black">123 Main St</span>
            </li>
            <li class="flex justify-between">
              <span class="text-gray-600">Email:</span>
              <span class="text-black">johndoe@email.com</span>
            </li>
          </ul>
        </div>



        <!-- Hang Up Button -->
        <div>
          <button @click.prevent="disconnectCall()" class="bg-red-500 hover:bg-red-400 text-white rounded-full py-2 px-6">
            Hang Up
          </button>
        </div>
      </div>
    </Modal>


  </div>
</template>
