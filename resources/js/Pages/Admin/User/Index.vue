<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Edit from "@//Pages/Admin/User/Edit.vue";
import { Head, router, usePage, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { toaster } from "@/helper.js";
import Modal from "@/Components/Modal.vue";
import SearchFilter from "@/Components/SearchFilter.vue";
import DeleteModal from "@/Components/DeleteModal.vue";
import { faL } from "@fortawesome/free-solid-svg-icons";
let page = usePage();
if (page.props.flash.message) {
  toaster("success", page.props.flash.message);
}

let deleteUserModal = ref(false)

let props = defineProps({
  users: {
    type: Object,
  },

  totalusers: {
    type: Number,
  },

  totalAmountSpent: {
    type: Number,
  },

  averageuserDuration: {
    type: Number,
  },
  requestData: {
    type: Array,
  },
  callTypes: Array,
  states: Array,
  roles: {
    type: Object,
  },
});

let formData = ref({
  name: props.requestData.name,
  email: props.requestData.email,
  phone: props.requestData.phone,
  first_six_card_no: props.requestData.first_six_card_no,
  last_four_card_no: props.requestData.last_four_card_no,
});

let paginate = (url) => {
  router.visit(url);
};

let showModal = ref(false);
let userDetail = ref(null);
let currentPage = ref(null);

let openClientModal = (user, page) => {
  userDetail.value = user;
  currentPage.value = page;
  showModal.value = true;
};
let formatMoney = (amount) => {
  return parseFloat(amount)
    .toFixed(2)
    .replace(/\d(?=(\d{3})+\.)/g, "$&,");
};
let confirmMessage = ref(null)
let isLoading = ref(false)
let deleteUser = (user_id) => {
 confirmMessage.value = {
    heading: 'Delete User',
    confirm: 'Are you sure you want to delete this user?',
    user_id: user_id
  }
  deleteUserModal.value = true
};

let actionToDeleteUser = () => {
  isLoading.value = true
  axios.delete(`/admin/customer/${confirmMessage.value.user_id}`)
    .then((res) => {
      deleteUserModal.value = false
      toaster("success", res.data.message)
      router.visit('/admin/customers')
    }).catch((error) => {
      isLoading.value = false
      toaster("error", error.message)
    })
}
let dateFormat = (data) => {
    if (data) {
        let date = new Date(data)
        const day = date.getDate().toString().padStart(2, "0"); // Add leading zero if needed
        const month = (date.getMonth() + 1).toString().padStart(2, "0"); // Month is zero-based, so add 1
        const year = date.getFullYear();
        // Create the formatted date string
        return `${day}/${month}/${year}`;
    }

}
</script>

<template>
  <Head title="Client Information" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Customers
      </h2>
    </template>

    <div class="pt-14">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="px-4 sm:px-8 sm:rounded-lg">
          <div class="text-4xl text-custom-sky font-bold mb-6">Customers</div>
          <hr class="mb-4" />
        </div>
      </div>
    </div>
    <SearchFilter :route="page.url" :requestData="requestData" />
    <section v-if="users.data.length" class="p-3">
      <div class="mx-auto max-w-screen-xl sm:px-12">
        <div class="relative sm:rounded-lg overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-400">
              <thead class="text-xs text-gray-300 uppercase bg-sky-900">
                <tr>
                  <th scope="col" class="px-4 py-3">ID</th>
                  <th scope="col" class="px-4 py-3">First Name</th>
                  <th scope="col" class="px-4 py-3">Last Name</th>
                  <th scope="col" class="px-4 py-3">Email</th>
                  <th scope="col" class="px-4 py-3">Balance</th>
                  <th scope="col" class="px-4 py-3">Phone</th>
                  <th scope="col" class="px-4 py-3">Role</th>
                  <th scope="col" class="px-4 py-3">Sign Up Date</th>
                  <th scope="col" class="px-4 py-3 text-end">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="user in users.data" :key="user.id" class="border-b border-gray-500">
                  <td class="text-gray-600 px-4 py-3">{{ user.id }}</td>
                  <td class="text-gray-600 px-4 py-3">{{ user.first_name }}</td>
                  <td class="text-gray-600 px-4 py-3">{{ user.last_name }}</td>
                  <th class="text-gray-600 px-4 py-3">{{ user.email }}</th>
                  <td class="text-gray-600 px-4 py-3">
                    ${{ formatMoney(user.balance) }}
                  </td>
            
                  <td class="text-gray-600 px-4 py-3"> <div class="flex"><span class="mr-1" v-if="user.phone_code">{{ user.phone_code}}</span> <span>{{ user.phone }}</span> </div> </td>
                  <td class="text-gray-600 px-4 py-3 text-center">
                    <div v-if="user.roles && user.roles.length > 0">
                      <span v-for="role in user.roles" :key="role.id">{{ role.name }}</span>
                    </div>
                    <div v-else>
                        User
                    </div>
                  </td>
                  <th class="text-gray-600 px-4 py-3">{{ dateFormat(user.created_at)  }}</th>
                  
                  <td class="text-gray-700 px-4 py-3 flex items-center justify-end">
                    <a :href="route('admin.customer.detail', user.id)">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                          d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      </svg>
                    </a>
                    <button @click="openClientModal(user, users.current_page)"
                      class="inline-flex items-center mx-2 p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none"
                      type="button">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                          d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                      </svg>
                    </button>
                    <button @click="deleteUser(user.id)">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4 text-red-600 mr-1">
                        <path stroke-linecap="round" stroke-linejoin="round"
                          d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                      </svg>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
            
            <nav class="flex justify-between my-4" v-if="users.links">
              <div v-if="users">
                <span class="text-sm text-gray-700">
                  Showing
                  <span class="font-semibold text-gray-900">{{ users.from }}</span>
                  to
                  <span class="font-semibold text-gray-900">{{ users.to }}</span> of
                  <span class="font-semibold text-gray-900">{{ users.total }}</span>
                  Entries
                </span>
              </div>

              <ul class="inline-flex -space-x-px text-base h-10">
                <li v-for="(link, index) in users.links" :key="link.label"
                  :class="{ disabled: link.url === null }">
                  <a href="#" @click.prevent="paginate(link.url)" :class="[
                    'flex items-center justify-center px-4 h-10 border border-gray-300',
                    link.active
                      ? 'text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-700'
                      : 'leading-tight text-gray-500 bg-white hover:bg-gray-100 hover:text-gray-700',
                    {
                      'rounded-l-lg': index === 0,
                      'rounded-r-lg': index === users.links.length - 1,
                    },
                  ]" v-html="link.label"></a>
                </li>
              </ul>
            </nav>
            <br>

            
          </div>
        </div>
      </div>
    </section>

    <section v-else class="p-3">
      <p class="text-center text-gray-600">No clients yet.</p>
    </section>
    <Modal :show="showModal" @close="showModal = false">
      <Edit :showModal="showModal" :userDetail="userDetail" :currentPage="currentPage" @close="showModal = false"
        :callTypes="callTypes" :states="states" :user_type="'Customer'" :roles="roles" :route="'/admin/customer'"></Edit>
    </Modal>
    <DeleteModal :isLoading="isLoading" @actionToDeleteUser="actionToDeleteUser" :deleteUserModal="deleteUserModal"
      @close="deleteUserModal = false" :confirmMessage="confirmMessage" />
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
