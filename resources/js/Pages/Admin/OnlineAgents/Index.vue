<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { onMounted } from "vue";
import { Head, router } from "@inertiajs/vue3";

const props = defineProps({
  onlineUsers: {
    type: Array,
  },
});

const refreshPage = () => {
  router.visit("/admin/online-agents");
};

onMounted(() => {
  console.log("Registering event listener");
  window.Echo.channel("active-users-events")
    .listen("OnlineUserListUpdated", () => {
      console.log("OnlineUserListUpdated!");
      refreshPage();
    })
    .listen("CallStatusUpdated", () => {
      console.log("CallStatusUpdated!");
      refreshPage();
    });
});
let formatDate = (date) => {
  if (!date) {
    return "";
  }

  const dateObj = new Date(date);

  const formattedDate = dateObj.toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "numeric",
    minute: "numeric",
  });

  return formattedDate;
};

let removeAgent = userId => {
    router.visit(`/admin/online-agents/${userId}`, {
        method: 'DELETE'
    });
}

</script>

<template>
  <Head title="Online Agents" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-2xl font-semibold">Online Agents</h2>
    </template>
    <div class="py-14">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 rounded-lg bg-white">
          <h3 class="text-4xl text-custom-sky font-bold mb-6">Online Agents</h3>
          <hr class="mb-4" />
          <!-- The Table Header -->
          <table class="w-full text-sm text-left text-gray-400">
            <thead class="text-xs text-gray-300 uppercase bg-sky-900">
              <tr>
                <th scope="col" class="px-4 py-3">User ID</th>
                <th scope="col" class="px-4 py-3">First Name</th>
                <th scope="col" class="px-4 py-3">Last Name</th>
                <th scope="col" class="px-4 py-3">Email</th>
                <th scope="col" class="px-4 py-3">Call Status</th>
                <th scope="col" class="px-4 py-3">Last call taken</th>
                <th scope="col" class="px-4 py-3">Listening For</th>
                <th scope="col" class="px-4 py-3">Actions</th>
              </tr>
            </thead>
            <tbody>
              <!-- The Table Body -->
              <tr
                v-for="onlineUser in onlineUsers"
                :key="onlineUser.id"
                class="border-b border-gray-500"
              >
                <td class="text-gray-600 px-4 py-3">
                  {{ onlineUser.user.id }}
                </td>
                <td class="text-gray-600 px-4 py-3">
                  {{ onlineUser.user.first_name }}
                </td>
                <td class="text-gray-600 px-4 py-3">
                  {{ onlineUser.user.last_name }}
                </td>
                <td class="text-gray-600 px-4 py-3">
                  {{ onlineUser.user.email }}
                </td>
                <td class="text-gray-600 px-4 py-3">
                  <span
                    :class="`${getStatusBadge(
                      onlineUser.user.call_status
                    )} text-white text-xs px-2 py-1 rounded-2xl`"
                  >
                    {{ onlineUser.user.call_status }}
                  </span>
                </td>
                <td class="text-gray-600 px-4 py-3">
                  {{
                    onlineUser.user.last_called_at
                      ? formatDate(onlineUser.user.last_called_at)
                      : ""
                  }}
                </td>
                <td class="text-gray-600 px-4 py-3">
                  {{ onlineUser.call_type.type }}
                </td>
                <td class="text-gray-600 px-4 py-3">
                  <button
                    @click.prevent="removeAgent(onlineUser.user.id)"
                    class="text-white bg-red-500 px-3 py-1.5 rounded-lg flex items-center shadow hover:bg-red-400"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      class="w-4 h-4 mr-1"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                      />
                    </svg>

                    Remove
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script>
export default {
  methods: {
    getStatusBadge(status) {
      console.log("status", status);
      switch (status) {
        case "Waiting":
          return "bg-green-600";
        case "Ringing":
          return "bg-yellow-400";
        case "Talking":
          return "bg-red-400";
        default:
          return "bg-gray-400";
      }
    },
  },
};
</script>

<style scoped>
/* Add your custom styles here */
</style>
