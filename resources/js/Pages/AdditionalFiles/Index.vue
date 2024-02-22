<script setup>
import { ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, router, usePage } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { toaster } from "@/helper.js";

let page = usePage();


if (page.props.flash.message) {
    toaster("success", page.props.flash.message);
}

defineProps(["additionalFiles"]);

let file = ref(null); // Store the selected file

const onChange = () => {
  form.file = file.value.files[0]; // Bind selected file to Inertia form
  form.label = file.value.files[0].name;
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
  label: "",
  file: null,
});

const submit = () => {
  if (form.file) {
    form.post("/additional-files", {
      preserveState: false,
    });
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
      <div class="sm:px-6 lg:px-8 space-y-6">
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
        class="bg-gray-300 p-4 w-full rounded-lg text-center flex items-center justify-center text-sm text-gray-800"
      >
        <!-- If there's an uploaded file, display its name -->
        <span v-if="form.file">
          <div class="my-6 mt-8 text-left">
            <InputLabel class="mb-1" for="label" :value="`Label (${form.file.name})`" />
            <TextInput
              id="label"
              v-model="form.label"
              style="width: 400px"
            />
          </div>

          <PrimaryButton @click="submit" class="mr-4">Upload</PrimaryButton>
          <PrimaryButton @click="remove">Cancel</PrimaryButton>
        </span>
        <!-- Otherwise, display the drag & drop message -->
        <div v-else>
          <div>
            <input
              type="file"
              id="fileInput"
              ref="file"
              @change="onChange"
              class="hidden"
            />
            <label for="fileInput" class="cursor-pointer underline">
              Click and choose file
            </label>
          </div>
        </div>

        <progress
          v-if="form.progress"
          :value="form.progress.percentage"
          max="100"
          class="ml-4 progress-bar-custom w-4xl"
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
              v-if="additionalFiles.length"
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
                <tr
                  class="border-b border-gray-500"
                  v-for="file in additionalFiles"
                  :key="file.id"
                >
                  <th
                    scope="row"
                    class="px-4 py-3 font-medium text-custom-blue whitespace-nowrap"
                    v-text="file.id"
                  ></th>
                  <td class="text-gray-700 px-4 py-3" v-text="file.label"></td>
                  <td class="text-gray-700 px-4 py-3">
                    <a
                      :href="`/additional-files/${file.id}`"
                      target="_blank"
                      class="text-blue-500 mr-2"
                      >Open</a
                    >
                  </td>
                </tr>
              </tbody>
            </table>

            <div v-else>
                <div class="text-center text-gray-500 py-8">
                    No additional files uploaded yet.
                </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </AuthenticatedLayout>
</template>


<style scoped>
.progress-bar-custom::-webkit-progress-bar {
  background-color: #cbd5e1; /* Slate 300 */
  border-radius: 0.5rem;
}

.progress-bar-custom::-webkit-progress-value {
  background-color: #0f172a; /* Slate 900 for the filled value */
  border-radius: 0.5rem;
}

.progress-bar-custom::-moz-progress-bar {
  background-color: #0f172a; /* Slate 900 for the filled value */
  border-radius: 0.5rem;
}
</style>
