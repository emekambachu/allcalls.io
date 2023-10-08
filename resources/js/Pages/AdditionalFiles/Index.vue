<script setup>
import { ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { useForm } from "@inertiajs/vue3";

let file = ref(null); // Store the selected file

const onChange = () => {
  form.file = file.value.files[0]; // Bind selected file to Inertia form
};

const remove = () => {
  file.value = null; // Remove the file
  form.file = null;
};

const dragover = (event) => {
  event.preventDefault();
  if (!event.currentTarget.classList.contains("bg-green-300")) {
    event.currentTarget.classList.remove("bg-gray-100");
    event.currentTarget.classList.add("bg-green-300");
  }
};

const dragleave = (event) => {
  event.currentTarget.classList.add("bg-gray-100");
  event.currentTarget.classList.remove("bg-green-300");
};

const drop = (event) => {
  event.preventDefault();
  file.value = event.dataTransfer.files[0];
  onChange();
  event.currentTarget.classList.add("bg-gray-100");
  event.currentTarget.classList.remove("bg-green-300");
};

const form = useForm({
  file: null,
});

const submit = () => {
  if (form.file) {
    form.post("/additional-files");
  }
};
</script>
<template>
  <Head title="Additional Files" />
  <AuthenticatedLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
      >
        Additional Files
      </h2>
    </template>
    <div class="pt-14">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="px-4 sm:px-8 sm:rounded-lg">
          <div class="text-4xl text-custom-sky font-bold mb-6">
            Additional Files
          </div>
          <hr class="mb-4" />
        </div>
      </div>
    </div>
    <section class="p-3 px-16">
      <div
        @dragover="dragover"
        @dragleave="dragleave"
        @drop="drop"
        class="bg-gray-300 h-28 w-full rounded-lg text-center flex items-center justify-center text-sm text-gray-800"
      >
        <!-- If there's an uploaded file, display its name -->
        <span v-if="form.file">
            <span class="mr-6">
                {{ form.file.name }}
            </span>

          <button
            @click="submit"
            class="bg-green-500 text-white px-4 py-2 rounded shadow hover:bg-green-600 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 transition-transform duration-150 mr-1"
          >
            Upload
          </button>
          <button
            @click="remove"
            class="bg-red-500 text-white px-4 py-2 ml-2 rounded shadow hover:bg-red-600 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 transition-transform duration-150"
          >
            Cancel
          </button>
        </span>
        <!-- Otherwise, display the drag & drop message -->
        <span v-else>
          Drag and drop files here or&nbsp;
          <input
            type="file"
            id="fileInput"
            ref="file"
            @change="onChange"
            class="hidden"
          />
          <label for="fileInput" class="cursor-pointer underline">
            click and choose file
          </label>
        </span>

        <progress
          v-if="form.progress"
          :value="form.progress.percentage"
          max="100"
          class="ml-4"
        >
          {{ form.progress.percentage }}%
        </progress>
      </div>


    </section>

    <section class="p-3">
      <div class="mx-auto max-w-screen-xl sm:px-12">
        <div class="relative sm:rounded-lg overflow-hidden">
          <div class="overflow-x-auto">
            <table
              class="w-full text-sm text-left text-gray-400 table-responsive"
            >
              <thead class="text-xs text-gray-300 uppercase bg-sky-900">
                <tr>
                  <th scope="col" class="px-4 py-3">ID</th>
                  <th scope="col" class="px-4 py-3">LABEL</th>
                  <th scope="col" class="px-4 py-3" style="min-width: 240px">
                    ACTIONS
                  </th>
                </tr>
              </thead>
              <tbody>
                <!-- Static row as an example -->
                <tr class="border-b border-gray-500">
                  <th
                    scope="row"
                    class="px-4 py-3 font-medium text-custom-blue whitespace-nowrap"
                  >
                    1
                  </th>
                  <td class="text-gray-700 px-4 py-3">Sample Label</td>
                  <td class="text-gray-700 px-4 py-3">
                    <button class="text-blue-500 mr-2">Open</button>
                    <button class="mr-2 text-red-500">Delete</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
  </AuthenticatedLayout>
</template>