<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed } from "vue";
import Multiselect from "@vueform/multiselect";
import InputError from "@/Components/InputError.vue";
import { router, usePage } from "@inertiajs/vue3";
import GuestTextInput from "@/Components/GuestTextInput.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import GuestInputLabel from "@/Components/GuestInputLabel.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { toaster } from "@/helper.js";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import axios from "axios";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import '@vueform/multiselect/themes/default.css';

const { users } = usePage().props;
const selectedUserId = ref('');
const selectedUserIds = ref([]);
const searchQuery = ref('');
const sendEmail = ref(false);
const emailTitle = ref('');
const emailButtonText = ref('');
const emailButtonUrl = ref('');
const emailDescription = ref('');
// const selectedDevices = ref([]);
const attachZoomLink = ref(false); // State to track if Zoom link should be attached
const zoomMeetingUrl = ref(''); // The actual Zoom meeting URL
const shouldShowZoomLinkInput = computed(() => attachZoomLink.value);
const groupName = ref('');
const groups = ref([]);
const selectedGroupId = ref('');


// Reactive form object
const form = useForm({
  title: '',
  message: '',
  user_id: '',
  devices: [],
  zoomLink: ''
});

// ... existing script ...

// function createGroup() {
//   if (groupName.value.trim() === '' || selectedUserIds.value.length === 0) {
//     toaster("error", "Please provide a group name and select at least one user.");
//     return;
//   }

//   // API call to create the group
//   const groupData = {
//     name: groupName.value,
//     user_ids: selectedUserIds.value
//   };

//   // Replace with your actual API call
//   // axios.post('/create-group', groupData)
//   //   .then(response => {
//   //     toaster("success", "Group created successfully!");
//   //     // Reset group name and selected users
//   //     groupName.value = '';
//   //     selectedUserIds.value = [];
//   //   })
//   //   .catch(error => {
//   //     toaster("error", "Failed to create group.");
//   //     console.error(error);
//   //   });
// }

function createGroup() {
  if (groupName.value.trim() === '' || selectedUserIds.value.length === 0) {
    toaster("error", "Please provide a group name and select at least one user.");
    return;
  }

  // Create group locally
  groups.value.push({
    id: Date.now().toString(), // Simple unique ID generation
    name: groupName.value,
    user_ids: [...selectedUserIds.value],
  });

  // Reset group name and selected users
  groupName.value = '';
  selectedUserIds.value = [];
  toaster("success", "Group created successfully!");
}

// ... rest of the script ...


function sendPushNotification() {
// If a group is selected, use its user IDs; otherwise, use selectedUserIds
  let userIdsToSend = selectedGroupId.value
    ? groups.value.find(group => group.id === selectedGroupId.value).user_ids
    : selectedUserIds.value;

  let payload = {
    user_ids: userIdsToSend, // Send array of user IDs
    title: form.title,
    message: form.message,
    zoomLink: attachZoomLink.value ? zoomMeetingUrl.value : '',
    sendEmail: sendEmail.value,
    emailData: sendEmail.value ? {
      title: emailTitle.value,
      buttonText: emailButtonText.value,
      buttonUrl: emailButtonUrl.value,
      description: emailDescription.value
    } : null
  };

  axios.post('/send-zoom-meeting-notification', payload)
    .then(response => {
      toaster("success", "Notification sent successfully!");
      // Reset form and states
      form.reset();
      attachZoomLink.value = false;
      zoomMeetingUrl.value = '';
    })
    .catch(error => {
      toaster("error", "Failed to send notification.");
      // Handle errors (show error messages)
      console.error(error);
    });
}

const getUserDetails = (userId) => {
  return users.find(user => user.id === userId) || {};
};

function removeUser(userId) {
  const index = selectedUserIds.value.indexOf(userId);
  if (index > -1) {
    selectedUserIds.value.splice(index, 1);
  }
}


const filteredUsers = computed(() => {
  if (!searchQuery.value) {
    return []; // No dropdown if there's no query
  }

  return users.filter(user => {
    const fullName = `${user.first_name} ${user.last_name}`.toLowerCase();
    const query = searchQuery.value.toLowerCase();
    return fullName.includes(query) || user.email.toLowerCase().includes(query);
  });
});

function selectUser(user) {
  const index = selectedUserIds.value.indexOf(user.id);
  if (index > -1) {
    selectedUserIds.value.splice(index, 1); // Remove if already selected
  } else {
    selectedUserIds.value.push(user.id); // Add if not selected
  }
}

// Watch the selectedUserId to update the devices array
watch(selectedUserId, (newVal, oldVal) => {
  if (newVal !== oldVal) {
    selectedDevices.value = []; // Clear the selected devices when a new user is selected
  }
});

let page = usePage();

if (page.props.flash.message) {
  toaster("success", page.props.flash.message);
}
</script>

<template>
  <Head title="Send Push Notifications" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Send Push Notifications
      </h2>
    </template>

    <section class="py-8">
      <div class="container mx-auto px-4">
        <!-- User Selection -->
        <!-- <div class="mb-4">
          <InputLabel for="user" value="Select User:" />
          <select v-model="selectedUserId" class="w-full p-2 border rounded">
            <option disabled value="">Select a user</option>
            <option v-for="user in users" :key="user.id" :value="user.id">
              {{ user.email }}
            </option>
          </select>

        </div> -->
        <div class="mt-6">
          <div class="mb-4">
            <InputLabel for="groupName" value="Create a Group:" />
            <TextInput id="groupName" type="text" v-model="groupName" placeholder="Enter Group Name" class="w-full p-2 border rounded" />
          </div>

          <PrimaryButton @click="createGroup" :disabled="selectedUserIds.length === 0" class="px-4 py-2 bg-green-500 hover:bg-green-700 text-white font-bold rounded">
            Create Group
          </PrimaryButton>
        </div>

        <div class="mt-4">
          <h3 class="text-lg font-semibold">Created Groups</h3>
          <ul>
            <li v-for="group in groups" :key="group.id" class="mt-2">
              <div class="p-2 border rounded">
                <strong>{{ group.name }}</strong> ({{ group.user_ids.length }} users)
              </div>
            </li>
          </ul>
        </div>

        <!-- Group Selection -->
        <div class="mt-4">
          <InputLabel for="group" value="Select Group:" />
          <select v-model="selectedGroupId" class="w-full p-2 border rounded">
            <option disabled value="">Select a group</option>
            <option v-for="group in groups" :key="group.id" :value="group.id">{{ group.name }}</option>
          </select>
        </div>



        <div class="mb-4">
          <!-- Display Selected Users -->
          <div class="mt-4 flex flex-wrap">
            <div v-for="userId in selectedUserIds" :key="userId" class="flex items-center bg-blue-100 text-blue-800 text-sm font-semibold mr-2 mb-2 px-4 py-2 rounded-full">
              {{ getUserDetails(userId).first_name }} {{ getUserDetails(userId).last_name }}
              <button @click="removeUser(userId)" class="ml-2">
                <svg class="h-4 w-4 fill-current text-blue-500 cursor-pointer" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                  <path d="M10 8.586l2.293-2.293 1.414 1.414L11.414 10l2.293 2.293-1.414 1.414L10 11.414l-2.293 2.293-1.414-1.414L8.586 10 6.293 7.707l1.414-1.414L10 8.586z"/>
                </svg>
              </button>
            </div>
          </div>
        </div>

        <div class="mb-4">
          <InputLabel for="user" value="Select User:" />
          <!-- Search Input -->
          <TextInput type="text" v-model="searchQuery" placeholder="Search by name or email" class="w-full p-2 border rounded mb-2" />

          <!-- Dropdown for Filtered User List -->
          <div v-if="filteredUsers.length > 0" class="border rounded max-h-60 overflow-y-auto">
            <div 
              v-for="user in filteredUsers" 
              :key="user.id" 
              class="p-2 hover:bg-gray-100 cursor-pointer"
              :class="{'bg-blue-200': selectedUserIds.includes(user.id)}"
              @click="selectUser(user)"
            >
              {{ user.first_name }} {{ user.last_name }} ({{ user.email }})
            </div>
          </div>
        </div>
        
        <!-- Devices List -->
        <!-- <div v-if="selectedUserDevices.length > 0" class="mb-4">
          <h3 class="font-semibold mb-2">Select Devices:</h3>
          <ul>
            <li v-for="device in selectedUserDevices" :key="device.id" class="mb-2">
              <label class="inline-flex items-center">
                <input type="checkbox" :value="device" v-model="selectedDevices" class="form-checkbox">
                <span class="ml-2">
                  {{ device.device_type }} 
                   ({{ device.fcm_token }}) 
                   </span>
              </label>
            </li>
          </ul>
        </div> -->
        
        <!-- Notification Form -->
        <div class="mt-4">
          <InputLabel for="title" value="Notification Title:" />
          <TextInput id="title" type="text" v-model="form.title" required autofocus class="w-full p-2 border rounded" />
        </div>
        
        <div class="mt-4">
          <InputLabel for="message" value="Notification Message:" />
          <TextInput id="message" type="text" v-model="form.message" required class="w-full p-2 border rounded" />
        </div>

        <div class="mt-4">
          <label class="inline-flex items-center">
            <input type="checkbox" v-model="attachZoomLink" class="form-checkbox">
            <span class="ml-2">Attach Zoom Meeting Link</span>
          </label>

          <!-- Conditional TextInput for Zoom Meeting URL -->
          <TextInput 
            v-if="shouldShowZoomLinkInput" 
            v-model="zoomMeetingUrl" 
            placeholder="Enter Zoom Meeting URL" 
            class="w-full p-2 border rounded mt-2"
          />
        </div>

        <div class="mt-4">
          <label class="flex items-center">
            <input type="checkbox" v-model="sendEmail" class="form-checkbox">
            <span class="ml-2">Attach Email</span>
          </label>
        </div>

        <div v-if="sendEmail" class="mt-4">
          <TextInput v-model="emailTitle" placeholder="Email Title" class="mb-4" />
          <TextInput v-model="emailButtonText" placeholder="Action Button Text" class="mb-4" />
          <TextInput v-model="emailButtonUrl" placeholder="Action Button URL" class="mb-4" />
          <TextInput v-model="emailDescription" placeholder="Email Description" class="mb-4" />
        </div>
        
        <div class="mt-6">
          <PrimaryButton @click="sendPushNotification" :disabled="form.processing" class="px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded">
            Send Notification
          </PrimaryButton>
        </div>
      </div>
    </section>
  </AuthenticatedLayout>
</template>