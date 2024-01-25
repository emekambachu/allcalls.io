<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { reactive, computed, ref } from "vue";
import { Link, Head, router, usePage } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";

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
  internalAgent: {
    type: Boolean,
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
const file = ref(null);  // Initialize the file variable

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

const selectedFile = ref(null);

const handleImageUpload = (event) => {
  file.value = event.target.files[0]; // Assign the selected file
  if (!file) return;

  // if (!['image/jpeg', 'image/jpg', 'image/png'].includes(file.type)) {
  //   alert('Please select a JPG, JPEG, or PNG file.');
  //   return;
  // }

  // if (file.size > 5 * 1024 * 1024) { // 5 MB
  //   alert('File size should not exceed 5 MB.');
  //   return;
  // }

  selectedFile.value = file.value;
  uploadImage();
};


const uploadImage = () => {
  const formData = new FormData();
  formData.append('profile_picture', selectedFile.value); // Assuming 'this.file' is your file to upload

  router.visit('/profile/upload-picture', {
    method: 'post',
    data: formData,
    // Inertia handles multipart/form-data automatically,
    // so you don't need to set Content-Type header.
    onSuccess: () => {
      console.log('Image uploaded successfully');
    },
    onError: errors => {
      console.error('Error uploading image:', errors);
    }
  });
};
let formatAgentLevel = (agentLevel) => {

  if (agentLevel && agentLevel.name) {
    // Extract the type and level number
    const match = agentLevel.name.match(/^(.+?) Level (\d+)$/);
    console.log('match',match);
    if (match) {
      const [, type, levelNumber] = match;
      return type === 'AC' ? `${type} ${levelNumber}` : `Level ${levelNumber}`;
    }
  }

  // Return a default value if agentLevel is not defined
  return 'Unknown Level';
  // Check if the agentLevel is defined
  // if (agentLevel && agentLevel.name) {
  //   // Extract the type and level number
  //   const match = agentLevel.name.match(/^(.+?) Level (\d+)$/);

  //   if (match) {
  //     const [, type, levelNumber] = match;
  //     return `${type} ${levelNumber}`;
  //   }
  // }

  // // Return a default value if agentLevel is not defined
  // return 'Unknown Level';
}

</script>
<style scoped>
.button-custom {
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  font-weight: 600;
  border-width: 1px;
  align-items: center;
  display: inline-flex;
  border-color: rgb(107 114 128 / var(--tw-border-opacity));
  background-color: #03243d;
  color: #3cfa7a;
  cursor: pointer;
}

.button-custom:hover {
  transition-duration: 150ms;
  background-color: white;
  color: black;
}
</style>
<template>
  <Head title="Profile" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Profile
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 sm:rounded-lg">
          <div class="text-4xl text-custom-sky font-small mb-6">My Profile </div>
          <hr class="mb-8" />

          <div class="flex flex-col items-start sm:flex-row sm:items-center sm:space-x-8 mb-4 lg:mb-10 relative">
            <div
              class="relative inline-flex items-center justify-center w-28 h-28 overflow-hidden rounded-full bg-custom-blue">
              <span class="text-5xl font-medium text-center text-custom-white">{{ getFirstLetter(user.first_name)
              }}{{ getFirstLetter(user.last_name) }}</span>
            </div>
            <!-- Check if user has a profile picture -->
            <!-- <img v-if="user.profile_picture" :src="`/storage/profile_pictures/${user.profile_picture}`" class="w-full h-full rounded-full"> -->
            <!-- Display placeholder with initials if no profile picture -->
            <!-- <span v-else class="text-5xl font-medium text-center text-custom-white">
                  {{ getFirstLetter(user.first_name) }}{{ getFirstLetter(user.last_name) }}
              </span> -->
            <!-- <div class="relative inline-flex items-center justify-center w-28 h-28 overflow-hidden rounded-full bg-custom-blue group">

              <div class="absolute inset-0 flex items-center justify-center w-full h-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                  <div class="absolute inset-0 w-full h-full bg-black bg-opacity-80 rounded-full"></div> Reduced opacity -->
            <!-- Centered SVG icon with higher z-index -->
            <!-- <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white z-10">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                  </svg> -->
            <!-- </div>
              <input type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" @change="handleImageUpload" accept="image/jpeg,image/jpg,image/png">
            </div> -->






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
              <div v-if="$page.props.auth.role == 'internal-agent'" style="background-color: #239dfa;"
                class="relative text-center grid items-center mt-3 lg:mr-24 md:mr-0  px-2 py-1 font-sans text-xs font-bold  uppercase rounded-md select-none whitespace-nowrap ">
                {{ formatAgentLevel(user.get_agent_level) }}
              </div>
              <!-- <button type="button"  style="background-color: #239dfa;" class="px-2 py-1  text-xs font-bold uppercase   text-center text-white bg-blue-700 rounded-md  focus:ring-4 focus:outline-none focus:ring-blue-300  ">{{ formatAgentLevel(user.get_agent_level) }}</button> -->
            </div>
            <Link href="/profile/edit" class="button-custom px-4 py-3 rounded-md  absolute right-0">Edit Profile</Link>
          </div>
          <!-- <div class="ml-10">{{ formatAgentLevel(user.get_agent_level) }}</div> -->

          <!-- <div>
                        <input class="text-white" type="file" ref="fileInput" accept="image/*" />
                        <button class="py-3 px-4 rounded bg-sky-950 text-white text-sm font-bold" @click.prevent="uploadProfilePicture" type="submit">Upload Profile Picture</button>
                    </div> -->

          <hr class="sm:hidden mb-10" />

          <div class="text-4xl text-custom-sky font-small mb-6 mt-6">
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
              <div class="text-md sm:text-xl text-gray-600 font-bold flex-grow">
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

          <div class="text-4xl text-custom-sky font-small mb-6">Licences</div>
          <hr class="mb-4" />

          <div class="grid grid-cols-2 gap-10 mb-12">
            <div class="flex flex-col space-y-2 h-full overflow-auto" v-for="callType in selectedCallTypesWithStates"
              :key="callType.id">
              <div class="text-sm text-gray-400 font-bold">
                {{ callType.type }}
              </div>
              <div class="text-md sm:text-xl text-gray-600 font-bold">
                {{ callType.states.map((state) => state.name).join(", ") }}
              </div>
            </div>
          </div>
          <div v-if="!internalAgent">
            <div class="text-4xl text-custom-sky font-small mb-2">Max Bids</div>
            <!-- <div class="text-2xl font-bold text-gray-600 mb-6" >Verticals</div> -->
            <hr class="mb-4" />

            <div class="grid grid-cols-2 gap-10 mb-12">
              <div v-for="bid in bids" :key="bid.id">
                <div class="flex flex-col space-y-2 h-full overflow-auto">
                  <div class="text-sm text-gray-400 font-bold">Vertical: <span class="font-light text-gray-500">{{
                    bid.call_type.type }}</span></div>
                  <div class="text-md sm:text-xl text-gray-600 font-bold">${{ formatMoney(bid.amount) }}</div>
                </div>
              </div>

            </div>
          </div>


        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
