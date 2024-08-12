<template>
    <div class="md:w-3/4 pr-4 h-1/2 md:h-screen relative">
        <div class="model relative" v-if="modelImage">
            <div v-if="loading" class="absolute inset-0 flex items-center justify-center z-50">
                <div role="status">
                    <h1>Loading...</h1>
                </div>
            </div>
            <img :src="'storage/' + modelImage" alt="Model Image" class="max-w-full h-screen"
                 :class="{ 'zoom-out': outZoom, 'zoom-head': headZoom, 'zoom-arms': armsZoom }"
                 @load="onLoad" />
        </div>
        <div class="text-white mt-2 pl-4 pr-4 flex justify-between items-center">
            <div class="text-2xl">
                <div>Design Your</div>
                <div>Model</div>
            </div>
            <div class="close-icon w-6 h-6 md:hidden">
                <img src="close-icon.png" alt="Close Icon">
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref } from 'vue';

    const props = defineProps({
        modelImage: String,
        loading: Boolean,
        outZoom: Boolean,
        headZoom: Boolean,
        armsZoom: Boolean,
    });

    const onLoad = () => {
        loading.value = false;
    };
</script>

<style scoped>
    .model {
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        height: 100vh; /* Set height to 100% of the viewport height */
        overflow: hidden; /* Hide overflow to prevent image from stretching */
    }
    .model img.zoom-out {
        transform: scale(1); /* Zoom factor for zoom-out effect */
    }

    .model img.zoom-head {
        transform: scale(5); /* Zoom factor */
        transform-origin: top; /* Zoom origin from the top */
        transition: transform 0.5s; /* Transition for smooth zoom effect */
        z-index: 10; /* Ensure zoomed head is on top */
    }

    .model img.zoom-arms {
        transform: scale(2.5); /* Zoom factor for arms */
        transform-origin: 85% 30%; /* Zoom origin at the middle right */
        transition: transform 0.5s; /* Transition for smooth zoom effect */
        z-index: 5; /* Ensure zoomed arms are on top */
    }
</style>
