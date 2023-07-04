<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
  <ul
    class="flex flex-wrap px-4 -mb-px text-sm sm:text-lg font-bold text-center text-gray-300"
  >
    <li class="mr-2">
      <Link
        href="/billing/funds"
        class="inline-flex items-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-custom-green hover:border-gray-300 group"
        :class="{
          'inline-flex p-4 items-center text-custom-sky border-b-2 border-blue-500 rounded-t-lg active group': route().current('billing.funds.index'),
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

        <span>Funds</span>
      </Link>
    </li>
    <li class="mr-2">
      <Link
        href="/billing/cards"
        class="inline-flex items-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-custom-green hover:border-gray-300  group"
        :class="{
          'inline-flex p-4 items-center text-custom-sky border-b-2 border-blue-500 rounded-t-lg active group': route().current('billing.cards.index'),
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

        <span>Cards</span>
      </Link>
    </li>
    <li class="mr-2">
      <Link
        aria-current="page"
        class="inline-flex items-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-custom-green hover:border-gray-300 group"
        :class="{
          'inline-flex p-4 items-center text-custom-sky border-b-2 border-blue-500 rounded-t-lg active group': route().current('billing.autopay.index'),
        }"
        href="/billing/autopay"
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
      </Link>
    </li>
  </ul>
</template>