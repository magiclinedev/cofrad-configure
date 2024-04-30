<template>
    <div class="white-bg relative">
      <ul class="head-list absolute top-0 left-0 w-full flex justify-center items-center z-20" :style="{ transform: 'translateX(' + translateX + 'px)' }">
        <li v-for="(head, index) in headImages" :key="index" @click="selectHead(index)"
            :class="{ 'selected': currentIndex === index, 'centered': selectedIndex === index }">
          <img :src="head" alt="" class="head-image">
        </li>
      </ul>
      <div class="zoomed-out-container relative z-10">
        <img :src="currentImage" alt="" class="zoomed-out-image">
        <button @click="goBack" class="back-button bg-gray-500 font-semibold py-2 px-4 border rounded-full">Back</button>
      </div>
    </div>
  </template>

  <script>
  export default {
    data() {
      return {
        images: [
          'images/DIS.ELLE6-RF (13).png',
          'images/DIS.ELLE6-RF (19).png',
          'images/DIS.ELLE6-RF (1).png',
        ],
        headImages: [
          'images/normalhead.png',
          'images/pinkhead.png',
          'images/woodhead.png',
        ],
        currentIndex: 0,
        selectedIndex: 0, // Initially set to 0 to hide the default head
        translateX: 0, // Translation for horizontal scroll
      };
    },
    computed: {
      currentImage() {
        return this.images[this.currentIndex];
      },
    },
    methods: {
      goBack() {
        this.$emit('toggleMaterialHead');
      },
      selectHead(index) {
        this.selectedIndex = index;
        this.currentIndex = index;
        this.updateTranslateX(index);
      },
      updateTranslateX(index) {
        // Calculate the translation based on the selected index
        this.translateX = index * -100; // Assuming each head is 100px wide
      },
    },
  };
  </script>

  <style>
  .zoomed-out-container {
    position: relative;
  }

  .zoomed-out-image {
    width: 100vw; /* Adjust the width to control the zoom level */
    height: auto;
    margin: 0 auto; /* Center the image horizontally */
    display: block;
    position: relative;
    z-index: 1;
  }

  .back-button {
    position: fixed;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    z-index: 2;
    background-color: rgba(255, 255, 255, 0.8);
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }

  .white-bg {
    background-image: linear-gradient(180deg, #fff, #475056); /* White to #475056 gradient */
    background-size: 100% 100vh; /* Ensure background covers full screen without being zoomed */
  }

  .head-list {
    position: absolute;
    top: 10%; /* Adjust the percentage to position the head list lower */
    left: 30%;
    transform: translateX(-50%);
    display: flex;
    justify-content: center;
    align-items: center;
    list-style-type: none;
    padding: 0;
    width: 100%; /* Make the head list the same width as the container */
    height: 150px; /* Adjust the height to match the model size */
    margin: 0;
    transition: transform 0.3s; /* Add a transition for smooth scrolling */
  }

  .head-list li {
    margin: 0 10px;
    cursor: pointer;
  }

  .head-image {
    width: 150px; /* Set the width of the head images */
    height: auto;
  }

  .selected {
    border: 2px solid #000;
  }

  .centered {
    /* Optional: Add styling for centered head */
  }
  </style>
