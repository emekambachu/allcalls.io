<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Transactions from "@/Pages/Admin/Agent/Transactions.vue";
import Calls from "@/Pages/Admin/Agent/Calls.vue";
import Activities from "@/Pages/Admin/Agent/Activities.vue";
import { Head, usePage, router } from "@inertiajs/vue3";
import { ref } from "vue";
import { toaster } from "@/helper.js";
import Clients from "@/Pages/Admin/Clients/Index.vue";
let page = usePage();
const tab = ref(0);

if (page.props.flash.message) {
  toaster("success", page.props.flash.message);
  if (page.props.flash.message == "Client updated successfully.") {
    tab.value = localStorage.getItem("tab");
  }
}
const props = defineProps({
  user: Object,
  callsCount: Number,
  ClientCount: Number,
  activitiesCount: Number,
  transactionsCount: Number,
  additionalFiles: Array,
});
let showTransactionModal = ref(false);
let showCallsModal = ref(false);
let showActivitiesModal = ref(false);
let detailType = ref(null);

let openTransactionModal = (type) => {
  if (type == "transactions") {
    detailType.value = type;
    showTransactionModal.value = true;
  } else if (type == "calls") {
    detailType.value = type;
    showCallsModal.value = true;
  } else if (type == "activities") {
    detailType.value = type;
    showActivitiesModal.value = true;
  }
};

let ChangeTab = (val) => {
  tab.value = val;
  localStorage.setItem("tab", val);
};

const deleteFile = file => {
  if (confirm("Are you sure you want to delete this file?")) {
    router.visit(`/additional-files/${file.id}`, {
      method: "DELETE",
    });
  }
};
</script>
<style>
.chemical-formula {
  display: inline-block;
  padding: 5px 10px;
  background-color: #0b4c73;
  color: white;
  font-size: 16px;
  border-radius: 50px;
  border: none;
  cursor: pointer;
  text-align: center;
  text-decoration: none;
  outline: none;
}

.active-tab {
  border-bottom: 2px solid #0b4c73;
}

.slide-enter-active,
.slide-leave-active {
  /* transition: all 0.2s ease; */
}

.slide-enter,
.slide-leave-to {
  opacity: 0;
  transform: translateX(-20px);
}
</style>
<template>
  <Head title="Dashboard" />
  <AuthenticatedLayout>
    <div class="pt-14">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="px-4 sm:px-8 sm:rounded-lg">
          <div class="text-4xl text-custom-sky font-bold mb-6">
            {{ user.first_name }} {{ user.last_name }}
          </div>
          <hr class="mb-4" />
        </div>
      </div>
    </div>

    <div class="px-16 pt-2">
      <div
        class="mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8"
      >
        <div
          class="max-w-sm p-6 bg-custom-darksky rounded-lg shadow overflow-auto"
        >
          <p class="mb-1 text-sm text-gray-300">Total Transactions</p>
          <h2
            class="mb-2 text-2xl sm:text-3xl lg:text-4xl font-bold text-white"
          >
            {{ transactionsCount }}
          </h2>
        </div>
        <div
          class="max-w-sm p-6 bg-custom-darksky rounded-lg shadow overflow-auto"
        >
          <p class="mb-1 text-sm text-gray-300">Total Calls</p>
          <h2
            class="mb-2 text-2xl sm:text-3xl lg:text-4xl font-bold text-white"
          >
            {{ callsCount }}
          </h2>
        </div>
        <div
          class="max-w-sm p-6 bg-custom-darksky rounded-lg shadow overflow-auto"
        >
          <p class="mb-1 text-sm text-gray-300">Total Clients</p>
          <h2
            class="mb-2 text-2xl sm:text-3xl lg:text-4xl font-bold text-white"
          >
            {{ ClientCount }}
          </h2>
        </div>
        <div
          class="max-w-sm p-6 bg-custom-darksky rounded-lg shadow overflow-auto"
        >
          <p class="mb-1 text-sm text-gray-300">Total Activities</p>
          <h2
            class="mb-2 text-2xl sm:text-3xl lg:text-4xl font-bold text-white"
          >
            {{ activitiesCount }}
          </h2>
        </div>
      </div>
    </div>

    <div class="mb-4 border-gray-200 dark:border-gray-700 px-16">
      <ul
        class="flex flex-wrap -mb-px text-sm font-medium text-center"
        id="myTab"
        data-tabs-toggle="#myTabContent"
        role="tablist"
      >
        <li
          @click="ChangeTab(0)"
          class="mr-8"
          :class="{ 'active-tab': tab == 0 }"
          role="presentation"
        >
          <button
            class="inline-block pb-2 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
            id="profile-tab"
            type="button"
            role="tab"
            aria-controls="profile"
            aria-selected="false"
          >
            User Information
            <!-- <span class="chemical-formula">{{ transactionsCount }}</span>  -->
          </button>
        </li>
        <li
          @click="ChangeTab(1)"
          class="mr-8"
          :class="{ 'active-tab': tab == 1 }"
          role="presentation"
        >
          <button
            class="inline-block pb-2 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
            id="profile-tab"
            type="button"
            role="tab"
            aria-controls="profile"
            aria-selected="false"
          >
            Transactions
            <!-- <span class="chemical-formula">{{ transactionsCount }}</span>  -->
          </button>
        </li>
        <li
          @click="ChangeTab(2)"
          class="mr-8"
          :class="{ 'active-tab': tab == 2 }"
          role="presentation"
        >
          <button
            class="inline-block pb-2 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
            id="dashboard-tab"
            data-tabs-target="#dashboard"
            type="button"
            role="tab"
            aria-controls="dashboard"
          >
            Calls
            <!-- <span class="chemical-formula">{{ callsCount }}</span> -->
          </button>
        </li>
        <li
          @click="ChangeTab(3)"
          class="mr-8"
          :class="{ 'active-tab': tab == 3 }"
          role="presentation"
        >
          <button
            class="inline-block pb-2 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
            id="settings-tab"
            data-tabs-target="#settings"
            type="button"
            role="tab"
            aria-controls="settings"
          >
            Clients
          </button>
        </li>
        <li
          @click="ChangeTab(4)"
          class="mr-8"
          :class="{ 'active-tab': tab == 4 }"
          role="presentation"
        >
          <button
            class="inline-block pb-2 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
            id="settings-tab"
            data-tabs-target="#settings"
            type="button"
            role="tab"
            aria-controls="settings"
          >
            Activities
          </button>
        </li>
        <li
          @click="ChangeTab(5)"
          class="mr-8"
          :class="{ 'active-tab': tab == 5 }"
          role="presentation"
        >
          <button
            class="inline-block pb-2 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
            id="additional-files-tab"
            type="button"
          >
            Additional Files
          </button>
        </li>
      </ul>
    </div>

    <transition name="slide">
      <section
        v-if="tab == 0"
        class="px-16 pt-14 sliding-section"
        :class="{ show: tab == 0 }"
      >
        <div v-if="user">
          <div class="flex justify-between items-center">
            <h4 class="text-2xl font-small text-custom-sky mb-2">
              Personal Details
            </h4>
          </div>

          <div
            class="grid sm:grid-cols-1 md:grid-cols-2 gap-2 text-gray-700 mb-10"
          >
            <div>
              <strong class="text-lg">First Name: </strong>
              {{ user.first_name }}
            </div>
            <div>
              <strong class="text-lg">Last Name: </strong>
              {{ user.last_name }}
            </div>
          </div>

          <h4 class="text-2xl font-small text-custom-sky mb-2">
            Contact Information
          </h4>
          <div
            class="grid sm:grid-cols-1 md:grid-cols-2 gap-2 text-gray-700 mb-10"
          >
            <div>
              <strong class="text-lg">Phone: </strong>
              {{ user.phone }}
            </div>
            <div>
              <strong class="text-lg">Email: </strong>
              {{ user.email || "N/A" }}
            </div>
          </div>

          <h4 class="text-2xl font-small text-custom-sky mb-2">
            Other Information
          </h4>
          <div
            class="grid sm:grid-cols-1 md:grid-cols-2 gap-2 text-gray-700 mb-10"
          >
            <div>
              <strong class="text-lg">Balance: </strong>
              {{ user.balance || "N/A" }}
            </div>

            <div>
              <strong class="text-lg">PM Type: </strong>
              {{ user.pm_type || "N/A" }}
            </div>

            <div>
              <strong class="text-lg">PM Last Four: </strong>
              {{ user.pm_last_four || "N/A" }}
            </div>

            <div>
              <strong class="text-lg">Trail Ends At: </strong>
              {{ user.trial_ends_at || "N/A" }}
            </div>

            <div>
              <strong class="text-lg">Balance: </strong>
              {{ user.balance || "N/A" }}
            </div>
          </div>
        </div>
      </section>
    </transition>
    <transition name="slide">
      <section
        v-if="tab == 1"
        class="sliding-section"
        :class="{ show: tab == 1 }"
      >
        <Transactions :user="user"></Transactions>
      </section>
    </transition>
    <transition name="slide">
      <section
        v-if="tab == 2"
        class="sliding-section"
        :class="{ show: tab == 1 }"
      >
        <Calls :user="user"></Calls>
      </section>
    </transition>
    <transition name="slide">
      <section
        v-if="tab == 3"
        class="sliding-section"
        :class="{ show: tab == 1 }"
      >
        <Clients :user="user" :url="'/admin/agent/clients'"></Clients>
      </section>
    </transition>
    <transition name="slide">
      <section
        v-if="tab == 4"
        class="sliding-section"
        :class="{ show: tab == 1 }"
      >
        <Activities :user="user"></Activities>
      </section>
    </transition>

    <transition name="slide">
      <section
        v-if="tab == 5"
        class="sliding-section mx-auto max-w-screen-xl sm:px-12"
        :class="{ show: tab == 5 }"
      >
        <table
          v-if="additionalFiles.length"
          class="w-full text-sm text-left text-gray-400 table-responsive"
        >
          <thead class="text-xs text-gray-300 uppercase bg-sky-900">
            <tr>
              <th scope="col" class="px-4 py-3">ID</th>
              <th scope="col" class="px-4 py-3">LABEL</th>
              <th scope="col" class="px-4 py-3" style="min-width: 240px">
                ACTIONS
              </th>
            </tr>
          </thead>
          <tbody>
            <tr
              class="border-b border-gray-500"
              v-for="file in additionalFiles"
              :key="file.id"
            >
              <th
                scope="row"
                class="px-4 py-3 font-medium text-custom-blue whitespace-nowrap"
                v-text="file.id"
              ></th>
              <td class="text-gray-700 px-4 py-3" v-text="file.label"></td>
              <td class="text-gray-700 px-4 py-3">
                <a
                  :href="`/additional-files/${file.id}`"
                  target="_blank"
                  class="text-blue-500 hover:text-blue-600 mr-2"
                  >Open</a
                >

                <a
                  href="#"
                  class="text-red-500 hover:text-red-600 mr-2"
                  @click.prevent="deleteFile(file)"
                  >Delete</a
                >
              </td>
            </tr>
          </tbody>
        </table>

        <div v-else>
          <div class="text-center text-gray-500 py-8">
            No additional files uploaded by the agent yet.
          </div>
        </div>
      </section>
    </transition>
  </AuthenticatedLayout>
</template>
