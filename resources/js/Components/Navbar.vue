<template>
    <nav class="navbar">
      <div class="navbar-logo">
        <img src="/images/logo.png" alt="Logo" />
      </div>
      <ul class="navbar-nav">
        <li v-for="(link, index) in links" :key="index" class="nav-item">
          <a :href="link.url" class="nav-link">{{ link.text }}</a>
        </li>
        <li v-if="$page.props.auth.user" class="nav-item">
          <a @click="logout" class="nav-link">Logout</a>
        </li>
      </ul>
    </nav>
  </template>

  <script>
  export default {
    data() {
      return {
        links: [
          { text: 'Dashboard', url: 'dashboard' },
          { text: 'Model', url: '/model' },
          { text: 'Tags', url: '/tags' },
          { text: 'Configure', url: '/' },
        ],
      };
    },
    methods: {
      logout() {
        // Perform logout logic
        this.$inertia.post('/logout')
          .then(() => {
            // Redirect to the home page after logout
            this.$inertia.visit('/');
          })
          .catch(error => {
            console.error('Logout failed:', error);
          });
      }
    }
  };
  </script>
  <style>
  .navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 20px;
    background-color: #ffffff;
    color: rgb(0, 0, 0);
  }

  .navbar-logo img {
    height: 40px;
  }

  .navbar-nav {
    display: flex;
    list-style-type: none;
  }

  .nav-item {
    margin-right: 20px;
  }

  .nav-link {
    color: rgb(82, 79, 79);
    text-decoration: none;
  }

  .nav-link:hover {
    text-decoration: underline;
  }
  </style>
