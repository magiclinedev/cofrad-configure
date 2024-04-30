<template>
    <div>
      <h2>Add Model</h2>
      <form @submit.prevent="addModel">
        <label for="modelName">Model Name:</label>
        <input type="text" id="modelName" v-model="modelName">

        <label for="imageUrl">Image URL:</label>
        <input type="text" id="imageUrl" v-model="imageUrl">

        <h3>Tags</h3>
        <label for="gender">Gender:</label>
        <input type="text" id="gender" v-model="tags.gender">

        <label for="pose">Pose:</label>
        <input type="text" id="pose" v-model="tags.pose">

        <label for="head">Head:</label>
        <input type="text" id="head" v-model="tags.head">

        <label for="arms">Arms:</label>
        <input type="text" id="arms" v-model="tags.arms">

        <label for="finish">Finish:</label>
        <input type="text" id="finish" v-model="tags.finish">

        <label for="color">Color:</label>
        <input type="text" id="color" v-model="tags.color">

        <button type="submit">Add Model</button>
      </form>
    </div>
  </template>

  <script>
  export default {
    data() {
      return {
        modelName: '',
        imageUrl: '',
        tags: {
          gender: '',
          pose: '',
          head: '',
          arms: '',
          finish: '',
          color: ''
        }
      };
    },
    methods: {
      addModel() {
        const newModel = {
          name: this.modelName,
          imageUrl: this.imageUrl,
          tags: { ...this.tags }
        };
        console.log('New Model:', newModel);
        // Add logic to save the new model
      }
    }
  };
  </script>
