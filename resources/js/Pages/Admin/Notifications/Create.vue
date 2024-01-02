<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed, nextTick } from "vue";
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
const emailSubject = ref('');
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
const showDropdown = ref(false);



// Reactive form object
const form = useForm({
  title: '',
  message: '',
  user_id: '',
  devices: [],
  zoomLink: ''
});

async function createGroup() {
  if (groupName.value.trim() === '' || selectedUserIds.value.length === 0) {
    toaster("error", "Please provide a group name and select at least one user.");
    return;
  }

  try {
    const response = await axios.post('/notification-groups', {
      name: groupName.value,
      user_ids: selectedUserIds.value
    });
    groups.value.push(response.data); // Assuming the backend now returns the created group
    toaster("success", "Group created successfully!");
  } catch (error) {
    toaster("error", "Failed to create group.");
    console.error(error);
  }

  groupName.value = '';
  selectedUserIds.value = [];
}



// Function to remove a group
async function removeGroup(groupId) {
  try {
    await axios.delete(`/notification-groups/${groupId}`);
    groups.value = groups.value.filter(group => group.id !== groupId);
    toaster("success", "Group deleted successfully!");
  } catch (error) {
    toaster("error", "Failed to delete group.");
    console.error(error);
  }
}


async function removeUserFromGroup(userId, group) {
  try {
    await axios.delete(`/notification-groups/${group.id}/remove-user/${userId}`);
    group.members = group.members.filter(member => member.user_id !== userId);
    toaster("success", "User removed from group successfully!");
  } catch (error) {
    toaster("error", "Failed to remove user from group.");
    console.error(error);
  }
}

function toggleGroup(group) {
  group.isExpanded = !group.isExpanded;
}
// ... rest of the script ...


function sendPushNotification() {
// If a group is selected, use its user IDs; otherwise, use selectedUserIds
let userIdsToSend = selectedGroupId.value
  ? groups.value.find(group => group.id === selectedGroupId.value).members.map(member => member.user_id)
  : selectedUserIds.value;
  let payload = {
    user_ids: userIdsToSend, // Send array of user IDs
    title: form.title,
    message: form.message,
    zoomLink: attachZoomLink.value ? zoomMeetingUrl.value : '',
    sendEmail: sendEmail.value,
    emailData: sendEmail.value ? {
      title: emailTitle.value,
      subject: emailSubject.value, 
      buttonText: emailButtonText.value,
      buttonUrl: processedEmailButtonUrl.value,
      description: emailDescription.value
    } : null
  };

  console.log("Selected Group ID:", selectedGroupId.value);
  const foundGroup = groups.value.find(group => group.id === selectedGroupId.value);
  console.log("Found Group:", foundGroup);
  // console.log("Payload ready to send out for push notification is: ", payload);

  if (foundGroup) {
      const extractedUserIds = foundGroup.members.map(member => member.user_id);
      console.log("Extracted User IDs:", extractedUserIds);
      userIdsToSend = extractedUserIds;
  } else {
      userIdsToSend = selectedUserIds.value;
  }

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
  return users.filter(user => {
    const fullName = `${user.first_name} ${user.last_name}`.toLowerCase();
    const query = searchQuery.value.toLowerCase();
    return fullName.includes(query) || user.email.toLowerCase().includes(query);
  });
});

const processedEmailButtonUrl = computed(() => {
  let url = emailButtonUrl.value.trim();
  if (!url) return '';

  // Remove any existing http://, https://, or malformed variants
  url = url.replace(/^(https?:\\+|http:\\+|htpts:\\+|https?:\/\/)/, '');

  // Prepend with 'https://'
  url = 'https://' + url;

  return url;
});


function selectUser(user) {
  const index = selectedUserIds.value.indexOf(user.id);
  if (index > -1) {
    selectedUserIds.value.splice(index, 1); // Remove if already selected
  } else {
    selectedUserIds.value.push(user.id); // Add if not selected
  }
}

// Function to add users to a group
async function addUserToGroup() {
  if (selectedUserIds.value.length === 0 || !selectedGroupId.value) {
    toaster("error", "Please select at least one user and a group.");
    return;
  }

  try {
    await axios.post(`/notification-groups/${selectedGroupId.value}/add-users-to-existing-group`, {
      user_ids: selectedUserIds.value
    });

    await fetchGroups();
    toaster("success", "Users added to group successfully!");
    // Optionally refresh group data or update UI
  } catch (error) {
    toaster("error", "Failed to add users to group.");
    console.error(error);
  }
}

const forceUpdate = () => {
  showDropdown.value = false;
  // NextTick ensures the update happens in the next cycle
  nextTick(() => {
    showDropdown.value = true;
  });
};

const selectAllUsers = () => {
  // Assuming selectedUserIds is an array holding IDs of selected users
  this.selectedUserIds = this.filteredUsers.map(user => user.id);
}

// Toggle dropdown visibility
const toggleDropdown = (event) => {
  event.stopPropagation(); // Prevent click from propagating
  showDropdown.value = !showDropdown.value;
};


// Close the dropdown when clicking outside
function handleClickOutside(event) {
  const withinDropdownContainer = event.target.closest('.dropdown-container');
  const withinDropdownHeader = event.target.closest('.dropdown-header');
  
  if (!withinDropdownContainer && !withinDropdownHeader) {
    showDropdown.value = false;
  }
}

onMounted(() => {
  window.addEventListener('click', handleClickOutside);
});

// onUnmounted(() => {
//   window.removeEventListener('click', handleClickOutside);
// });

// Watch the selectedUserId to update the devices array
watch(selectedUserId, (newVal, oldVal) => {
  if (newVal !== oldVal) {
    selectedDevices.value = []; // Clear the selected devices when a new user is selected
  }
});

async function fetchGroups() {
  try {
    const response = await axios.get('/notification-groups');
    groups.value = response.data;
  } catch (error) {
    console.error("Failed to fetch groups:", error);
    toaster("error", "Failed to fetch groups.");
  }
}

onMounted(async () => {
  await fetchGroups();
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
          <InputLabel for="user" value="Select a User:" />
          <!-- Search Input -->
          <TextInput
            type="text"
            v-model="searchQuery"
            placeholder="Search by name or email"
            class="w-full p-2 border rounded mb-2"
            @click="toggleDropdown"
          />

          <!-- Dropdown for Filtered User List -->
          <div v-if="showDropdown" class="border rounded max-h-60 overflow-y-auto">
            
            <!-- Header Row -->
            <div class="flex justify-between items-center p-2 bg-gray-200 dropdown-header">
              <!-- Total Number of Users -->
              <div>Total Users: {{ filteredUsers.length }}</div>
              
              <!-- Select All Button -->
              <div 
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded-full cursor-pointer"
                @click="selectAllUsers"
              >
                Select All
              </div>
            </div>

            <div 
              v-for="user in filteredUsers"
              :key="user.id" 
              class="p-2 hover:bg-gray-100 cursor-pointer dropdown-container"
              :class="{'bg-blue-200': selectedUserIds.includes(user.id)}"
              @click="selectUser(user)"
            >
              {{ user.first_name }} {{ user.last_name }} ({{ user.email }})
            </div>
          </div>
        </div>
        
        <div class="mt-1">
          <div class="mb-4">
            <InputLabel for="groupName" value="Create a Group:" />
            <TextInput id="groupName" type="text" v-model="groupName" placeholder="Enter Group Name" class="w-full p-2 border rounded" />
          </div>

                  <!-- Group Selection -->
        <div class="mb-4">
          <InputLabel for="group" value="Select Group:" />
          <select v-model="selectedGroupId" class="w-full p-2 border rounded">
            <option disabled value="">Select a group</option>
            <option v-for="group in groups" :key="group.id" :value="group.id">{{ group.name }}</option>
          </select>
        </div>

          <div class="mt-4 flex gap-4">
            <PrimaryButton @click="createGroup" :disabled="selectedUserIds.length === 0" class="px-4 py-2 bg-green-500 hover:bg-green-700 text-white font-bold rounded">
              Create Group
            </PrimaryButton>
          
            
            <PrimaryButton @click="addUserToGroup" :disabled="selectedUserIds.length === 0 || !selectedGroupId" class="px-4 py-2 bg-green-500 hover:bg-green-700 text-white font-bold rounded">
              Add Users to Selected Group
            </PrimaryButton>
          </div>
        </div>



        
      <div class="mt-4">
        <h3 class="text-lg font-semibold">Created Groups</h3>
        
        <div v-if="groups && groups.length > 0">
          <ul>
            <li v-for="group in groups" :key="group.id" class="mt-2">
              <div class="flex items-center justify-between p-2 border rounded bg-gray-100">
                <div>
                  <strong>{{ group.name }}</strong> ({{ group.members.length }} users)
                </div>
                <div class="flex items-center">
                  <button @click="toggleGroup(group)" class="mr-2">
                    <svg v-if="!group.isExpanded" class="h-6 w-6 fill-current text-gray-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M5 6l7 7 7-7"/></svg>
                    <svg v-else class="h-6 w-6 fill-current text-gray-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M5 14l7-7 7 7"/></svg>
                  </button>
                  <button @click="removeGroup(group.id)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded">
                    Delete
                  </button>
                </div>
              </div>
              <ul v-if="group.isExpanded" class="mt-2 pl-4">
                <li v-for="member in group.members" :key="member.id" class="flex items-center bg-blue-100 text-blue-800 text-sm font-semibold mr-2 mb-2 px-4 py-2 rounded-full">
                  {{ getUserDetails(member.user_id).first_name }} {{ getUserDetails(member.user_id).last_name }}
                  <button @click="removeUserFromGroup(member.user_id, group)" class="ml-2">
                    <svg class="h-4 w-4 fill-current text-blue-500 cursor-pointer" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                      <path d="M10 8.586l2.293-2.293 1.414 1.414L11.414 10l2.293 2.293-1.414 1.414L10 11.414l-2.293 2.293-1.414-1.414L8.586 10 6.293 7.707l1.414-1.414L10 8.586z"/>
                    </svg>
                  </button>
                </li>
              </ul>
            </li>
          </ul>
        </div>
        
        <div v-else class="text-gray-600">
          No groups found.
        </div>
      </div>

        
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
          <TextInput v-model="emailSubject" placeholder="Email Subject" class="mb-4" />
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