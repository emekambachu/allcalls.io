<script setup>
import { Popover, PopoverButton, PopoverPanel } from "@headlessui/vue";
import { ref, onMounted, watch, computed } from "vue";
let props = defineProps({
  items: {
    type: Array,
    required: true,
  },

  columns: {
    type: Array,
    required: true,
  },

  filters: {
    type: Array,
    required: true,
  },

  sortColumn: {
    type: String,
    required: true,
  },

  sortDirection: {
    type: String,
    required: true,
  },

  loadMore: {
    type: Function,
    required: true,
  },

  renderColumn: {
    type: Function,
    required: true,
  },

  sortByColumn: {
    type: Function,
    required: true,
  },
});

console.log("Props passed to InfinityTable component: ", props);

let landmark = ref(null);

let observer = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      props.loadMore();
    }
  });
});

onMounted(() => {
  if (landmark.value) {
    observer.observe(landmark.value);
  }
});
</script>
<template>
  <section class="py-3 sm:py-5">
    <div class="px-4 mx-auto max-w-screen-2xl lg:px-12">
      <div class="relative overflow-hidden bg-white sm:rounded-lg">
        <div
          class="flex flex-col px-4 py-3 space-y-3 lg:flex-row lg:items-center lg:justify-between lg:space-y-0 lg:space-x-4"
        >
          <div class="flex items-center flex-1 space-x-4">
            <h5>
              <span class="text-gray-500">Total Items: </span>
              <span class="">500</span>
            </h5>
          </div>
          <div
            class="flex flex-col flex-shrink-0 space-y-3 md:flex-row md:items-center lg:justify-end md:space-y-0 md:space-x-3"
          >
            <div class="px-4 flex items-center">
              <Popover class="relative mr-2">
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
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                      />
                      <label
                        :for="`column-${index}`"
                        class="ms-2 text-sm font-medium text-gray-900 select-none"
                        >{{ column.label }}</label
                      >
                    </div>
                  </div>
                </PopoverPanel>
              </Popover>

              <Popover class="relative">
                <PopoverButton>
                  <button
                    type="button"
                    class="flex items-center justify-center flex-shrink-0 px-3 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200"
                  >
                    Filters
                  </button>
                </PopoverButton>

                <PopoverPanel class="absolute z-10 w-40 -left-20">
                  <div class="border border-gray-100 p-2 shadow bg-white mt-2">
                    <div
                      v-for="(filter, index) in filters"
                      :key="index"
                      class="flex items-center mb-4"
                    >
                      <input
                        v-model="filter.checked"
                        :id="`filter-${index}`"
                        type="checkbox"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                      />
                      <label
                        :for="`filter-${index}`"
                        class="ms-2 text-sm font-medium text-gray-900 select-none"
                        v-text="filter.label"
                      ></label>
                    </div>
                  </div>
                </PopoverPanel>
              </Popover>
            </div>
          </div>
        </div>
        <div v-if="items.length" class="overflow-x-auto">
          <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
              <tr class="cursor-pointer">
                <th
                  scope="col"
                  class="px-4 py-3 whitespace-nowrap select-none"
                  v-for="(column, index) in columns"
                  :key="index"
                  v-show="column.visible"
                  @click="sortByColumn(column)"
                >
                  <div class="flex items-center">
                    <span>{{ column.label }}</span>

                    <span v-if="sortColumn === column.label">
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-3 h-3 ml-1"
                        v-if="sortDirection === 'asc'"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M4.5 15.75l7.5-7.5 7.5 7.5"
                        />
                      </svg>

                      <svg
                        v-if="sortDirection === 'desc'"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-3 h-3 ml-1"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                        />
                      </svg>
                    </span>
                  </div>
                </th>
                <th scope="col" class="px-4 py-3 whitespace-nowrap">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr
                class="border-b hover:bg-gray-100"
                v-for="(item, index) in items"
                :key="item.id"
              >
                <td
                  v-for="(column, colIndex) in columns"
                  :key="colIndex"
                  class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"
                  v-show="column.visible"
                  v-text="renderColumn(column, item)"
                ></td>
                <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">
                  <!-- Actions column content -->
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div v-if="items.length" class="flex justify-center my-4">
          <div ref="landmark"></div>
        </div>

        <div v-else class="text-sm text-center py-20 text-gray-200">No calls found.</div>
      </div>
    </div>
  </section>
</template>
