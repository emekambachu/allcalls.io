<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import { reactive, computed } from "vue";
import { Link, Head } from "@inertiajs/vue3";

let props = defineProps({
  user: {
    type: Object,
  },

  callTypes: {
    type: Array,
  },

  bids: {
    type: Array,
  },
});

let formatDate = (date) => {
  const dateObj = new Date(date);

  const formattedDate = dateObj.toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });

  return formattedDate;
};

let getFirstLetter = (str) => {
  return str.charAt(0);
};

let state = reactive(props);

let selectedCallTypesWithStates = computed(() => {
  return state.callTypes
    .filter((callType) => callType.selected)
    .map((callType) => {
      // Clone the call type to avoid mutating the original object
      let clone = { ...callType };
      clone.states = clone.statesWithSelection.filter(
        (state) => state.selected
      );
      return clone;
    });
});

let formatMoney = (amount) => {
  return parseFloat(amount)
    .toFixed(2)
    .replace(/\d(?=(\d{3})+\.)/g, "$&,");
};
</script>

<template>
  <Head title="Profile" />

  <AuthenticatedLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800 leading-tight"
      >
        Profile
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 sm:rounded-lg">
          <div class="text-4xl text-custom-sky font-bold mb-6">My Profile</div>
          <hr class="mb-8" />

          <div
            class="flex flex-col items-start sm:flex-row sm:items-center sm:space-x-8 mb-4 lg:mb-10 relative"
          >
            <div
              class="relative inline-flex items-center justify-center w-28 h-28 overflow-hidden rounded-full bg-custom-blue"
            >
              <span class="text-5xl font-medium text-center text-custom-white"
                >{{ getFirstLetter(user.first_name)
                }}{{ getFirstLetter(user.last_name) }}</span
              >
            </div>
            <div class="font-medium text-white">
              <div class="text-4xl text-sky-950">
                {{ user.first_name }} {{ user.last_name }}
              </div>
              <div class="text-lg text-gray-400">
                Joined on {{ formatDate(user.created_at) }}
              </div>
              <div class="flex items-center gap-2">
                <!-- <div class="text-md text-gray-500">Balance: </div> -->
                <div class="text-2xl text-custom-green">
                  ${{ formatMoney(user.balance) }}
                </div>
              </div>
            </div>
            <Link
              href="/profile/edit"
              class="border border-gray-400 ease-in cursor-pointer bg-gray-400 bg-opacity-5 hover:shadow-2xl hover:bg-white hover:text-custom-blue hover:bg-opacity-80 rounded px-3 py-3 font-bold text-md text-gray-500 transition absolute right-0"
              >Edit Profile</Link
            >
          </div>

          <!-- <div>
                        <input class="text-white" type="file" ref="fileInput" accept="image/*" />
                        <button class="py-3 px-4 rounded bg-sky-950 text-white text-sm font-bold" @click.prevent="uploadProfilePicture" type="submit">Upload Profile Picture</button>
                    </div> -->

          <hr class="sm:hidden mb-10" />

          <div class="text-4xl text-custom-sky font-bold mb-6">
            Personal Information
          </div>
          <hr class="mb-4" />

          <div class="grid grid-cols-2 gap-10 mb-12">
            <div class="flex flex-col space-y-2 h-full overflow-auto">
              <div class="text-sm text-gray-400 font-bold">First Name</div>
              <div class="text-md sm:text-xl text-gray-600 font-bold">
                {{ user.first_name }}
              </div>
            </div>
            <div class="flex flex-col space-y-2 h-full overflow-auto">
              <div class="text-sm text-gray-400 font-bold">Last Name</div>
              <div class="text-md sm:text-xl text-gray-600 font-bold">
                {{ user.last_name }}
              </div>
            </div>
            <div class="flex flex-col space-y-2 h-full overflow-auto">
              <div class="text-sm text-gray-400 font-bold">Email Address</div>
              <div
                class="text-md sm:text-xl text-gray-600 font-bold flex-grow"
              >
                {{ user.email }}
              </div>
            </div>
            <div class="flex flex-col space-y-2 h-full overflow-auto">
              <div class="text-sm text-gray-400 font-bold">Phone</div>
              <div class="text-md sm:text-xl text-gray-600 font-bold">
                {{ user.phone }}
              </div>
            </div>
          </div>

          <div class="text-4xl text-custom-sky font-bold mb-6">Licences</div>
          <hr class="mb-4" />

          <div class="grid grid-cols-2 gap-10 mb-12">
            <div
              class="flex flex-col space-y-2 h-full overflow-auto"
              v-for="callType in selectedCallTypesWithStates"
              :key="callType.id"
            >
              <div class="text-sm text-gray-400 font-bold">
                {{ callType.type }}
              </div>
              <div class="text-md sm:text-xl text-gray-600 font-bold">
                {{ callType.states.map((state) => state.name).join(", ") }}
              </div>
            </div>
          </div>

          <div class="text-4xl text-custom-sky font-bold mb-2">Max Bids</div>
          <!-- <div class="text-2xl font-bold text-gray-600 mb-6" >Verticals</div> -->
          <hr class="mb-4" />

          <div class="grid grid-cols-2 gap-10 mb-12">
            <div v-for="bid in bids" :key="bid.id">
              <div class="flex flex-col space-y-2 h-full overflow-auto">
                <div class="text-sm text-gray-400 font-bold">Vertical: <span class="font-light text-gray-500">{{ bid.call_type.type }}</span></div>
                <div class="text-md sm:text-xl text-gray-600 font-bold">${{ formatMoney(bid.amount) }}</div>
              </div>
            </div>

          </div>

        </div>


        <div class="flex p-4 sm:p-8 sm:rounded-lg">
          <DeleteUserForm class="max-w-xl" />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
