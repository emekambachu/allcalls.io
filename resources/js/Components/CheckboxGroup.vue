<template>
    <div>
      <div v-for="option in options" :key="option.value">
        <input type="checkbox"
               :id="option.value"
               :value="option.value"
               :checked="isSelected(option)"
               @input="updateSelection($event.target.value)">
        <label :for="option.value">{{ option.label }}</label>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      options: {
        type: Array,
        required: true,
        validator: (options) => options.every(option => typeof option === 'object' && 'value' in option && 'label' in option)
      },
      value: {
        type: Array,
        default: () => []
      }
    },
    methods: {
      isSelected(option) {
        return this.value.includes(option.value);
      },
      updateSelection(value) {
        const index = this.value.indexOf(value);
        if (index === -1) {
          this.$emit('input', [...this.value, value]);
        } else {
          const newValue = [...this.value];
          newValue.splice(index, 1);
          this.$emit('input', newValue);
        }
      }
    }
  }
  </script>
  