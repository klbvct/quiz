<template>
  <div class="dashboard">
    <h1>User Dashboard</h1>
    <p>Welcome, {{ user.name }}!</p>
    <button @click="fetchUserData">Refresh Data</button>
    <div v-if="userData">
      <h2>Your Data:</h2>
      <pre>{{ userData }}</pre>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: {
        name: 'User', // Placeholder for user name
      },
      userData: null,
    };
  },
  methods: {
    fetchUserData() {
      // Simulate fetching user data
      this.userData = {
        email: 'user@example.com',
        preferences: {
          theme: 'dark',
          notifications: true,
        },
      };
    },
  },
  mounted() {
    this.fetchUserData();
  },
};
</script>

<style scoped>
.dashboard {
  padding: 20px;
}
</style>