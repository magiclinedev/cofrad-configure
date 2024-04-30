<template>
    <div class="side-menu">
      <button v-for="option in options" :key="option.value" @click="selectOption(option.value)" :class="{ active: option.value === selectedOption }">
        {{ option.label }}
      </button>
    </div>
  </template>

  <script>
  export default {
    data() {
      return {
        options: [
          { label: 'Male', value: 'gender-male' },
          { label: 'Female', value: 'gender-female' },
          { label: 'Standing', value: 'pose-standing' },
          { label: 'Sitting', value: 'pose-sitting' },
          { label: 'Matte', value: 'finish-matte' },
          { label: 'Glossy', value: 'finish-glossy' }
        ],
        selectedOption: 'gender-male'
      };
    },
    methods: {
      selectOption(option) {
        this.selectedOption = option;
        this.$emit('option-selected', option);
      }
    }
  };
  </script>

  <style scoped>
  .side-menu {
    display: flex;
    flex-direction: column;
  }
  .side-menu button {
    margin-bottom: 10px;
    padding: 5px 10px;
    border: 1px solid #ccc;
    cursor: pointer;
  }
  .side-menu button.active {
    background-color: #ccc;
  }
  </style>
