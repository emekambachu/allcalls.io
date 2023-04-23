<template>
    <select
      class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      ref="select"
    >
      <option disabled value="">Please select one</option>
      <slot></slot>
    </select>
  </template>
  
  <script setup>
  import { onMounted, ref } from 'vue';
  
  defineProps({
    modelValue: {
      type: String,
      required: true,
    },

    multiple: {
      type: Boolean,
      default: false,
    },
  });
  
  defineEmits(['update:modelValue']);
  
  const select = ref(null);
  
  onMounted(() => {
    if (select.value.hasAttribute('autofocus')) {
      select.value.focus();
    }
  });
  
  defineExpose({ focus: () => select.value.focus() });
  </script>
  