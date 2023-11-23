<script setup>
import { ref, onMounted, watch } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { toaster } from "@/helper.js";
import {
  Menu,
  MenuButton,
  MenuItems,
  MenuItem,
  Popover,
  PopoverButton,
  PopoverPanel,
} from "@headlessui/vue";

let page = usePage();
if (page.props.flash.message) {
  toaster("success", page.props.flash.message);
}
let props = defineProps({
  calls: {
    type: Object,
    required: true,
  },
  totalCalls: {
    type: Number,
    required: true,
  },
  totalRevenue: {
    type: Number,
    required: true,
  },
});

let loadedCalls = ref(props.calls.data);

watch(
  () => props.calls,
  () => {
    loadedCalls.value = [...loadedCalls.value, ...props.calls.data];
  }
);

let initialUrl = usePage().url;

let loadMore = (url) => {
  router.get(
    props.calls.next_page_url,
    {},
    {
      preserveState: true,
      preserveScroll: true,
      onSuccess: () => {
        window.history.replaceState({}, "", initialUrl);
      },
    }
  );
};

let columns = ref([
  { label: "ID", columnMethod: "getIdColumn", visible: true },
  { label: "Call Date", columnMethod: "getCallTakenColumn", visible: true },
  { label: "Agent Name", columnMethod: "getAgentNameColumn", visible: true },
  { label: "Role", columnMethod: "getRoleColumn", visible: false },
  {
    label: "Connected Duration",
    columnMethod: "getConnectedDurationColumn",
    visible: false,
  },
  { label: "Revenue", columnMethod: "getRevenueColumn", visible: true },
  { label: "Vertical", columnMethod: "getVerticalColumn", visible: true },
  { label: "CallerID", columnMethod: "getCallerIdColumn", visible: true },
]);

let getIdColumn = (call) => {
  return call.id;
};

let getCallTakenColumn = (call) => {
  return call.call_taken;
};

let getAgentNameColumn = (call) => {
  return call.user.first_name + " " + call.user.last_name;
};

let getRoleColumn = (call) => {
  return call.role;
};

let getConnectedDurationColumn = (call) => {
  return (
    String(Math.floor(call.call_duration_in_seconds / 60)).padStart(2, "0") +
    ":" +
    String(call.call_duration_in_seconds % 60).padStart(2, "0")
  );
};

let getRevenueColumn = (call) => {
  return "$" + call.amount_spent;
};

let getVerticalColumn = (call) => {
  return call.call_type.type;
};

let getCallerIdColumn = (call) => {
  return call.from;
};

let callColumnMethod = (call, column) => {
  switch (column.columnMethod) {
    case "getIdColumn":
      return getIdColumn(call);
    case "getCallTakenColumn":
      return getCallTakenColumn(call);
    case "getAgentNameColumn":
      return getAgentNameColumn(call);
    case "getRoleColumn":
      return getRoleColumn(call);
    case "getConnectedDurationColumn":
      return getConnectedDurationColumn(call);
    case "getRevenueColumn":
      return getRevenueColumn(call);
    case "getVerticalColumn":
      return getVerticalColumn(call);
    case "getCallerIdColumn":
      return getCallerIdColumn(call);
    default:
      return "";
  }
};

let landmark = ref(null);

let observer = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      loadMore();
    }
  });
});

onMounted(() => {
  observer.observe(landmark.value);
});
</script>

<style scoped>
/* Keep existing styles or adjust as needed for the User Activities page. */
</style>

<template>
  <Head title="Calls" />
  <AuthenticatedLayout>
    <div class="pt-14 flex justify-between px-16">
      <div>
        <div class="text-4xl text-custom-sky font-bold mb-6">Calls</div>
      </div>

      <div class="flex items-center">
        <!-- <button class="button-custom-back px-3 py-2 rounded-md mr-2">Clear All</button> -->
      </div>
    </div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
      <div class="px-4 sm:px-8 sm:rounded-lg">
        <hr class="mb-4" />
      </div>
    </div>

    <section class="py-3 sm:py-5">
      <div class="px-4 mx-auto max-w-screen-2xl lg:px-12">
        <div class="relative overflow-hidden bg-white sm:rounded-lg">
          <div
            class="flex flex-col px-4 py-3 space-y-3 lg:flex-row lg:items-center lg:justify-between lg:space-y-0 lg:space-x-4"
          >
            <div class="flex items-center flex-1 space-x-4">
              <h5>
                <span class="text-gray-500">Total Calls: </span>
                <span class="">&nbsp;{{ totalCalls }}</span>
              </h5>
              <h5>
                <span class="text-gray-500">Total Revenue: </span>
                <span class="">&nbsp;${{ totalRevenue.toFixed(2) }}</span>
              </h5>
            </div>
            <div
              class="flex flex-col flex-shrink-0 space-y-3 md:flex-row md:items-center lg:justify-end md:space-y-0 md:space-x-3"
            >
              <div class="px-4">
                <Popover class="relative">
                  <PopoverButton>
                    <button
                      type="button"
                      class="flex items-center justify-center flex-shrink-0 px-3 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200"
                    >
                      Visible Columns
                    </button>
                  </PopoverButton>

                  <PopoverPanel class="absolute z-10 w-40 -left-20">
                    <div class="border border-gray-100 p-2 shadow bg-white mt-2">
                      <div
                        class="flex items-center mb-4"
                        v-for="(column, index) in columns"
                        :key="index"
                      >
                        <input
                          :id="`column-${index}`"
                          type="checkbox"
                          v-model="column.visible"
                          class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                        />
                        <label
                          :for="`column-${index}`"
                          class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300 select-none"
                          >{{ column.label }}</label
                        >
                      </div>
                    </div>
                  </PopoverPanel>
                </Popover>
              </div>
            </div>
          </div>
          <div v-if="loadedCalls.length" class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
              <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr class="cursor-pointer">
                  <th
                    scope="col"
                    class="px-4 py-3 whitespace-nowrap"
                    v-for="(column, index) in columns"
                    :key="index"
                    v-show="column.visible"
                  >
                    {{ column.label }}
                  </th>
                  <th scope="col" class="px-4 py-3 whitespace-nowrap">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  class="border-b hover:bg-gray-100"
                  v-for="(call, index) in loadedCalls"
                  :key="call.id"
                >
                  <td
                    v-for="(column, colIndex) in columns"
                    :key="colIndex"
                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                    v-show="column.visible"
                    v-text="callColumnMethod(call, column)"
                  ></td>
                  <td
                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                  >
                    <!-- Actions column content -->
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div v-if="loadedCalls.length" class="flex justify-center my-4">
            <!-- <button
              type="button"
              class="flex items-center justify-center flex-shrink-0 px-3 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200"
              @click.prevent="loadMore"
            >
              Load More
            </button> -->

            <div ref="landmark"></div>
          </div>

          <div v-else class="text-sm text-center py-20 text-gray-200">
            No calls found.
          </div>
        </div>
      </div>
    </section>
  </AuthenticatedLayout>
</template>
