<template>
    <div class="model">
      <h1>{{ currentOption }}</h1>
      <ul>
        <li v-for="(value, key) in tags" :key="key">{{ key }}: {{ value }}</li>
      </ul>
    </div>
  </template>

  <script>
  export default {
    props: {
      currentOption: String,
      tags: Object,
    },
  };
  </script>

  <style scoped>
  .model {
    margin-left: 10px; /* Adjust margin as needed */
  }
  </style>
