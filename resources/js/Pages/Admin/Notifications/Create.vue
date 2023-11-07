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

const { users } = usePage().props;
const selectedUserId = ref('');

// Computed property to get devices for the selected user
const selectedUserDevices = computed(() => {
  const user = users.find(user => user.id === selectedUserId.value);
  return user ? user.devices : [];
});


// Create a reactive form object
const form = useForm({
  title: '',
  message: ''
});

function sendPushNotification() {
  // Disable the button to prevent multiple submissions
  form.processing = true;

  // Post the form data
  form.get('/send-push-notification-test', {
    data: {
      user_id: selectedUserId.value,
      devices: selectedDevices.value.map(device => device.fcm_token),
      title: form.title,
      message: form.message
    },
    onSuccess: () => {
      toaster("success", "Notification sent successfully!");
      form.reset(); // Reset the form after successful submission
    },
    onError: () => {
      toaster("error", "Failed to send notification.");
    },
    onFinish: () => {
      form.processing = false; // Re-enable the button after the request
    }
  });
}

let page = usePage();

if (page.props.flash.message) {
  toaster("success", page.props.flash.message);
}
</script>
<template>
  <Head title="Notifications" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Notifications
      </h2>
    </template>

    <div class="pt-14">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="px-4 sm:px-8 sm:rounded-lg">
          <div class="text-4xl text-custom-sky font-bold mb-6">Notifications</div>
          <hr class="mb-4" />
        </div>
      </div>
    </div>
    <section class="px-16">
        Send Notifications To Any Device Here

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
          <!-- <ul>
            <li v-for="device in devices" :key="device.id">
              {{ device.user_id }} - {{ device.device_type }}
            </li>
          </ul> -->

          <!-- User Selection -->
          <select v-model="selectedUserId">
            <option value="">Select a user</option>
            <option v-for="user in users" :key="user.id" :value="user.id">
              {{ user.name }}
            </option>
          </select>
          
          <!-- Devices List -->
          <div v-if="selectedUserId">
            <h3>Select a Device</h3>
            <ul>
              <li v-for="device in selectedUserDevices" :key="device.id">
                {{ device.name }} - {{ device.fcm_token }}
              </li>
            </ul>
          </div>

        </div>

        <div class="max-w-xl">
          <form @submit.prevent="sendPushNotification">
            <div class="mt-4">
              <InputLabel for="title" value="Push Title:" />
              <TextInput id="title" type="text" class="mt-1" v-model="form.title" required autofocus />
            </div>

            <div class="mt-4">
              <InputLabel for="message" value="Push Description" />
              <TextInput id="message" type="text" class="mt-1" v-model="form.message" required autofocus />
            </div>

            <div class="mt-6">
              <PrimaryButton type="submit" :disabled="form.processing">Send Notification</PrimaryButton>
            </div>
          </form>
        </div>
    </section>
  </AuthenticatedLayout>
</template>
