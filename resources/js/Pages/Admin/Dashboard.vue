<template>
    <div>
        <Navbar />
        <div class="main-content">
            <h1>Welcome to my Vue.js App!</h1>
            <p>This is the main content of the app.</p>
        </div>
    </div>
</template>

  <script>
    import Navbar from '@/Components/Navbar.vue';

    export default {
        components: {
            Navbar
        }
    };
</script>

<style>
    /* Add your global styles here */
    .main-content {
    padding: 20px;
    }
</style>
