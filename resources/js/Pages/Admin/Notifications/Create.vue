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
// const selectedDevices = ref([]);
const attachZoomLink = ref(false); // State to track if Zoom link should be attached
const zoomMeetingUrl = ref(''); // The actual Zoom meeting URL
const shouldShowZoomLinkInput = computed(() => attachZoomLink.value);

// Computed property to get devices for the selected user
// const selectedUserDevices = computed(() => {
//   const user = users.find(u => u.id === selectedUserId.value);
//   return user ? user.devices : [];
// });

// Reactive form object
const form = useForm({
  title: '',
  message: '',
  user_id: '',
  devices: [],
  zoomLink: ''
});

// function sendPushNotification() {
//   form.user_id = selectedUserId.value;
//   form.devices = selectedDevices.value.map(device => device.fcm_token);

//   // Conditionally add the Zoom meeting URL to the form data
//   if (attachZoomLink.value && zoomMeetingUrl.value) {
//     form.zoomLink = zoomMeetingUrl.value; // Add zoomLink to the form if the checkbox is checked and the URL is provided
//   }

//   form.post('/send-push-notification-test', {
//     onSuccess: () => {
//       toaster("success", "Notification sent successfully!");
//       form.reset(); // Reset the form fields
//       attachZoomLink.value = false; // Reset the checkbox
//       zoomMeetingUrl.value = ''; // Reset the Zoom meeting URL
//     },
//     onError: (errors) => {
//       toaster("error", "Failed to send notification.");
//       // Handle form errors if needed
//     }
//   });
// }

function sendPushNotification() {
  let payload = {
    user_ids: selectedUserIds.value, // Send array of user IDs
    title: form.title,
    message: form.message,
    zoomLink: attachZoomLink.value ? zoomMeetingUrl.value : '',
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
        
        <div class="mt-6">
          <PrimaryButton @click="sendPushNotification" :disabled="form.processing" class="px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded">
            Send Notification
          </PrimaryButton>
        </div>
      </div>
    </section>
  </AuthenticatedLayout>
</template>