<template>
    <div>
      <div :class="['full-screen-container', {'white-bg': currentIndex === 0, 'brown-bg': currentIndex === 1 }]">
        <div class="slideshow-container flex">
          <!-- MAIN IMAGE -->
          <div class="w-full md:w-1/2 flex justify-center">
            <div class="item-left">
              <img :src="currentImage" alt="" class="image object-cover h-full">
            </div>
          </div>
          <!-- CONFIGURE -->
          <div class="w-full md:w-1/2 flex justify-center">
            <div class="chooseMat">
              <div class="flex justify-between mt-4">
                <button @click="selectImage(0)" class="text-left text-black text-lg font-bold py-2 px-4 rounded" :class="{ 'selected': currentIndex === 0 }">Fiber <span v-if="currentIndex === 0"><i class="fas fa-circle text-xs"></i></span></button>
                <button @click="selectImage(1)" class="text-left text-black text-lg font-bold py-2 px-4 rounded" :class="{ 'selected': currentIndex === 1 }">Fabric <span v-if="currentIndex === 1"><i class="fas fa-circle text-xs"></i></span></button>
              </div>
              <div v-if="currentIndex === 0" class="mt-4 flex justify-center">
                <button @click="showConfigureHead" class="bg-transparent hover:bg-gray-500 text-gray-700 font-semibold hover:text-white py-2 px-4 border border-gray-500 hover:border-transparent rounded-full">Head</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>

  <script>
  export default {
    data() {
      return {
        images: [
          'images/DIS.ELLE6-RF (13).png',
          'images/COF-DIS.ELLE6-COTTON (1).png',
        ],
        currentIndex: 0,
      };
    },
    computed: {
      currentImage() {
        return this.images[this.currentIndex];
      },
    },
    methods: {
      selectImage(index) {
        this.currentIndex = index;
      },
      showConfigureHead() {
        this.$emit('toggleMaterialHead'); // Emit event to parent component
      },
    },
  };
  </script>

  <style>
  .full-screen-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    transition: background-color 0.5s; /* Add transition for smoother color change */
  }

  .slideshow-container {
    position: relative;
    width: 100%;
    height: 100%;
    box-sizing: border-box; /* Ensure padding and border don't affect width */
    display: flex;
    flex-direction: row; /* Display items side by side */
    margin: 0; /* Remove margin */
    padding: 0; /* Remove padding */
  }

  .image {
    max-width: 100%;
    max-height: 100%;
  }

  .chooseMat {
    padding: 20px; /* Increase padding to make the section bigger */
  }

  .white-bg {
    background-image: linear-gradient(180deg, #fff, #475056); /* White to #aaa7a3 gradient for Fiber */
  }

  .brown-bg {
    background-image: linear-gradient(180deg, #fff, #dba457); /* White to #CFB59A gradient for Paper Mache */
  }
  </style>
