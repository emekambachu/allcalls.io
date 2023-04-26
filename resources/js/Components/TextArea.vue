<template>
    <textarea
      class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      ref="textarea"
    ></textarea>
  </template>
  
  <script setup>
  import { onMounted, ref } from 'vue';
  
  defineProps({
    modelValue: {
      type: String,
      required: true,
    },
  });
  
  defineEmits(['update:modelValue']);
  
  const textarea = ref(null);
  
  onMounted(() => {
    if (textarea.value.hasAttribute('autofocus')) {
      textarea.value.focus();
    }
  });
  
  defineExpose({ focus: () => textarea.value.focus() });
  </script>
  