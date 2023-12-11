<script setup>
import { onMounted, ref } from "vue";

defineProps({
  modelValue: {
    type: String,
    required: true,
  },
});

defineEmits(["update:modelValue"]);

const input = ref(null);

onMounted(() => {
  if (input.value.hasAttribute("autofocus")) {
    input.value.focus();
  }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
  <input
    class="bg-custom-blue text-sm rounded-lg focus:ring-blue-500 border border-transparent focus:border focus:border-blue-500 block w-full p-2.5 text-black outline-none"
    style="background-color: #E8F0FE;"
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
    ref="input"
  />
</template>
