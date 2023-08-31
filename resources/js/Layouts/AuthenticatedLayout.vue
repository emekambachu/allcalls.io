<script setup>
import { ref, onMounted } from "vue";
import { Link } from "@inertiajs/vue3";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import DashboardFooter from "@/Components/DashboardFooter.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import DownloadAppModal from "@/Components/DownloadAppModal.vue";
const showingNavigationDropdown = ref(false);

let formatMoney = (amount) => {
  return parseFloat(amount)
    .toFixed(2)
    .replace(/\d(?=(\d{3})+\.)/g, "$&,");
};
const props = defineProps({
  role: String,
});
let appDownloadModal = ref(false)
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
        <nav  class="bg-custom-indigo border-b border-gray-800">
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

              <ResponsiveNavLink :href="route('admin.customer.index')" :active="route().current('admin.customer.index') || route().current('admin.customer.detail') ">
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

        <div  class="w-full mx-auto md:grid md:grid-cols-5 md:gap-28 md:max-w-screen-2xl xl:gap-0">
          <div class="py-12 hidden sm:-my-px sm:ml-10 col-span-1 md:flex md:flex-col">
            <NavLink class="mb-10 gap-2" :href="route('admin.dashboard')" :active="route().current('admin.dashboard')">
              <img src="/img/dashboard.png" alt="" />
              Dashboard
            </NavLink>

              <NavLink class="mb-10 gap-2" :href="route('admin.customer.index')" :active="route().current('admin.customer.index') || route().current('admin.customer.detail') ">
                  <img src="/img/clients.png" alt="" />
                  Customers
              </NavLink>
              <NavLink class="mb-10 gap-2" :href="route('admin.agent.index')" :active="route().current('admin.agent.index') || route().current('admin.agent.detail') ">
                  <img src="/img/clients.png" alt="" />
                  Internal Agents
              </NavLink>
              <NavLink class="mb-10 gap-2" :href="route('admin.active-users.index')" :active="route().current('admin.active-users.index')">
                  <img src="/img/clients.png" alt="" />
                  Active Users
              </NavLink>
          </div>
          <!-- Page Content -->
          <main class="col-span-4 bg-white rounded-xl mt-16 mb-8">
            <slot />
          </main>
        </div>
    </div>
      <!-- Amin Navigation Menu -->

      <!-- User Navigation Menu -->
      <div v-else>
        <nav  class="bg-custom-indigo border-b border-gray-800">
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


              <ResponsiveNavLink :href="route('profile.view')" :active="route().current('profile.view') ||
                route().current('profile.edit')
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
        <div    class="w-full mx-auto md:grid md:grid-cols-5 md:gap-28 md:max-w-screen-2xl xl:gap-0">
          <div class="py-12 hidden sm:-my-px sm:ml-10 col-span-1 md:flex md:flex-col">
            <NavLink class="mb-10 gap-2" :href="route('dashboard')" :active="route().current('dashboard')">
              <img src="/img/dashboard.png" alt="" />
              Dashboard
            </NavLink>
            <NavLink class="mb-10 gap-2" :href="route('clients.index')" :active="route().current('clients.index')">
              <img src="/img/clients.png" alt="" />
              Clients
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
                  <Link href="/billing/funds" class="inline-flex items-center rounded-t-lg hover:text-custom-green" :class="{
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


            <NavLink class="mb-10 gap-2" :href="route('support.index')" :active="route().current('support.index')">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" style="height: 38px; width: 38px">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
              </svg>

              Support
            </NavLink>
            <NavLink class="mb-10 gap-2" :href="route('profile.view')" :active="route().current('profile.edit') || route().current('profile.view')
              ">
              <img src="/img/profile.png" alt="" />
              Profile
            </NavLink>
          </div>
          <!-- Page Content -->
          <main class="col-span-4 bg-white rounded-xl mt-16 mb-8">
            <slot />
          </main>

        </div>
      </div>
      <!-- User Navigation Menu -->

      <DownloadAppModal :appDownloadModal="appDownloadModal" @close="appDownloadModal = false" />

      <DashboardFooter></DashboardFooter>
    </div>
  </div>
</template>
