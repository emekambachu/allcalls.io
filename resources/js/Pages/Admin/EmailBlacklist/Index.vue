<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Edit from "@/Pages/Admin/User/Edit.vue";
import Create from "@/Pages/Admin/AvailableNumber/Create.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import { toaster } from "@/helper.js";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from "@/Components/Modal.vue";

let page = usePage();
if (page.props.flash.message) {
  toaster("success", page.props.flash.message);
}

let props = defineProps({
  blacklist: {
    type: Object,
  },
});

let paginate = (url) => {
  router.visit(url);
};

// let showModal = ref(false);
let currentPage = ref(null);

let deleteBlacklisted = (id) => {
  router.delete("/admin/email-blacklist/" + id);
};
</script>

<template>
  <Head title="Email Blacklist" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Email Blacklist
      </h2>
    </template>

    <div class="pt-14 flex justify-between px-16">
      <div>
        <div class="text-4xl text-custom-sky font-bold mb-6">Email Blacklist</div>
      </div>
      <div class="flex items-center">
        <PrimaryButton @click.prevent="">Add New</PrimaryButton>
      </div>
    </div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
      <div class="px-4 sm:px-8 sm:rounded-lg">
        <hr class="mb-4" />
      </div>
    </div>
    <!-- <SearchFilterAvailableNumber :route="page.url" :requestData="requestData" /> -->
    <section v-if="blacklist.data.length" class="p-3">
      <div class="mx-auto max-w-screen-xl sm:px-12">
        <div class="relative sm:rounded-lg overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-400">
              <thead class="text-xs text-gray-300 uppercase bg-sky-900">
                <tr>
                  <th scope="col" class="px-4 py-3">ID</th>
                  <th scope="col" class="px-4 py-3">Email Address</th>
                  <th scope="col" class="px-4 py-3">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="blacklisted in blacklist.data"
                  :key="blacklisted.id"
                  class="border-b border-gray-500"
                >
                  <td class="text-gray-600 px-4 py-3">{{ blacklisted.id }}</td>
                  <td class="text-gray-600 px-4 py-3">
                    {{ blacklisted.email }}
                  </td>
                  <td class="text-gray-600 px-4 py-3">
                    <button @click="deleteBlacklisted(blacklisted.id)">
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-4 h-4 text-red-600 mr-1"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                        />
                      </svg>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>

            <nav class="flex justify-between my-4" v-if="blacklist.links">
              <div v-if="blacklist">
                <span class="text-sm text-gray-700">
                  Showing
                  <span class="font-semibold text-gray-900">{{ blacklist.from }}</span>
                  to
                  <span class="font-semibold text-gray-900">{{ blacklist.to }}</span>
                  of
                  <span class="font-semibold text-gray-900">{{ blacklist.total }}</span>
                  Entries
                </span>
              </div>

              <ul class="inline-flex -space-x-px text-base h-10">
                <li
                  v-for="(link, index) in blacklist.links"
                  :key="link.label"
                  :class="{ disabled: link.url === null }"
                >
                  <a
                    href="#"
                    @click.prevent="paginate(link.url)"
                    :class="[
                      'flex items-center justify-center px-4 h-10 border border-gray-300',
                      link.active
                        ? 'text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-700'
                        : 'leading-tight text-gray-500 bg-white hover:bg-gray-100 hover:text-gray-700',
                      {
                        'rounded-l-lg': index === 0,
                        'rounded-r-lg': index === blacklist.links.length - 1,
                      },
                    ]"
                    v-html="link.label"
                  ></a>
                </li>
              </ul>
            </nav>
            <br />
          </div>
        </div>
      </div>
    </section>

    <section v-else class="p-3">
      <p class="text-center text-gray-600">No numbers in blacklist yet.</p>
    </section>

    <!-- <Modal :show="showModal" @close="showModal = false">
      <Edit
        :showModal="showModal"
        :user="user"
        :currentPage="currentPage"
        @close="showModal = false"
        :callTypes="callTypes"
        :availableNumber="availableNumber"
        :route="'/admin/available-numbers'"
      ></Edit>
    </Modal> -->

    <!-- <Modal :show="availableNumberModal" @close="availableNumberModal = false">
      <Create
        :availableNumberModal="availableNumberModal"
        :currentPage="currentPage"
        :callTypes="callTypes"
        :states="states"
        :user="user"
        @close="availableNumberModal = false"
      ></Create>
    </Modal> -->
  </AuthenticatedLayout>
</template>

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
  border: none;
  border-radius: 10px;
}

.multiselect-wrapper {
  background-color: #d7d7d7;
  border-radius: 5px;
}

.box-shadow {
  padding: 20px;
  width: 97%;
  margin: auto;
  box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
}
</style>
