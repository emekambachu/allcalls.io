<script setup>
import { ref, onMounted } from 'vue';
import { Link } from "@inertiajs/vue3";
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import BillingNav from '@/Components/BillingNav.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

// import tippy from 'tippy.js';
// import 'tippy.js/dist/tippy.css';

// onMounted(() => {
//   tippy('#billing-nav-link', {
//     content: `
//         <h1 class="text-gray-100 text-lg font-bold mb-2">Step 1</h1>

//         <p class="text-gray-400 text-sm">Click here to manage your billing information.</p>
//     `,
//     allowHTML: true,
//     showOnCreate: true,
//     placement: "bottom",
//     trigger: 'manual',
//     theme: 'light',
//   });
// });

const showingNavigationDropdown = ref(false);

let formatMoney = (amount) => {
  return parseFloat(amount)
    .toFixed(2)
    .replace(/\d(?=(\d{3})+\.)/g, "$&,");
};
</script>

<template>
    <div>
        <div class="min-h-screen bg-custom-indigo">
            <nav class="bg-custom-indigo border-b border-gray-100 dark:border-gray-700">
                <!-- Primary Navigation Menu -->
                <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link class="bg-clip-text text-4xl text-transparent bg-gradient-to-r from-blue-400 to-green-500 font-bold uppercase tracking-wider" :href="route('dashboard')">
                                    AllCalls.io
                                </Link>
                            </div>


                        </div>

                        <div class="hidden md:flex md:self-start sm:pt-6 sm:ml-6">
                            <div class="flex flex-col justify-center items-center ">
                                <div class="text-xs leading-4 font-medium rounded-md text-custom-white">Balance</div>
                                <div class="text-xl font-bold text-gray-300">${{ formatMoney($page.props.auth.user.balance) }}</div>
                            </div>
                            <!-- Settings Dropdown -->
                            <div class="ml-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center text-lg leading-4 font-bold text-gray-300 hover:text-custom-green focus:outline-none transition ease-in-out duration-150"
                                            >
                                                {{ $page.props.auth.user.first_name + ' ' + $page.props.auth.user.last_name }}

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
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
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

                    <p class="mt-10 mb-2 text-lg text-white font-bold ">Manage your team and preferences here.</p>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                    class="md:hidden"
                >
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </ResponsiveNavLink>

                        <ResponsiveNavLink :href="route('billing.funds.index')" :active="route().current('billing.funds.index') || route().current('billing.cards.index') || route().current('billing.autopay.index')">
                            Billing
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('clients.index')" :active="route().current('clients.current')">
                            Clients
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('transactions.index')" :active="route().current('transactions.index')">
                            Transactions
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('activities.index')" :active="route().current('activities.index') || route().current('activities.index')">
                            Activities
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('profile.view')" :active="route().current('profile.view') || route().current('profile.edit')">
                            Profile
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-200">
                                {{ $page.props.auth.user.first_name + ' ' + $page.props.auth.user.last_name }}
                            </div>
                            <div class="font-medium text-sm text-gray-400">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <!-- <header class="bg-white dark:bg-gray-800 shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header> -->

            <!-- Navigation Links -->
            <div class="w-full mx-auto md:grid md:grid-cols-5 md:gap-28 md:max-w-screen-2xl xl:gap-0">
                <div class="py-12 hidden sm:-my-px sm:ml-10 col-span-1 md:flex md:flex-col">
                    <NavLink class="mb-10 gap-2" :href="route('dashboard')" :active="route().current('dashboard')">
                        <img src="/img/dashboard.png" alt="">
                        Dashboard
                    </NavLink>
                    <!-- <NavLink class="mb-10" href="#" :active="route().current('reporting')">
                        Reporting
                    </NavLink> -->
                    <NavLink class="mb-10 gap-2" id="billing-nav-link" :href="route('billing.funds.index')" :active="route().current('billing.funds.index') || route().current('billing.cards.index') || route().current('billing.autopay.index')">
                        <img src="/img/billing.png" alt="">
                        Billing
                    </NavLink>
                    <NavLink class="mb-10 gap-2" :href="route('clients.index')" :active="route().current('clients.index')">
                        <img src="/img/clients.png" alt="">
                        Clients
                    </NavLink>
                    <NavLink class="mb-10 gap-2" :href="route('transactions.index')" :active="route().current('transactions.index')">
                        <img src="/img/transactions.png" alt="">
                        Transactions
                    </NavLink>
                    <NavLink class="mb-10 gap-2" :href="route('activities.index')" :active="route().current('activities.index')">
                        <img src="/img/activity.png" alt="">
                        Activities
                    </NavLink>
                    <NavLink class="mb-10 gap-2" :href="route('profile.view')" :active="route().current('profile.edit') || route().current('profile.view')">
                        <img src="/img/profile.png" alt="">
                        Profile
                    </NavLink>
                </div>
                <!-- Page Content -->
                <main class="col-span-4 ">
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>
