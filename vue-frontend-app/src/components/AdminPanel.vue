<template>
  <div class="admin-panel">
    <h1>Admin Panel</h1>
    <div>
      <h2>User Management</h2>
      <button @click="fetchUsers">Fetch Users</button>
      <ul>
        <li v-for="user in users" :key="user.id">
          {{ user.username }} - {{ user.email }}
          <button @click="deleteUser(user.id)">Delete</button>
        </li>
      </ul>
    </div>
    <div>
      <h2>Statistics</h2>
      <p>Total Users: {{ totalUsers }}</p>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      users: [],
      totalUsers: 0,
    };
  },
  methods: {
    fetchUsers() {
      // Fetch users from the API
      // Example: axios.get('/api/users').then(response => {
      //   this.users = response.data;
      //   this.totalUsers = response.data.length;
      // });
    },
    deleteUser(userId) {
      // Delete user from the API
      // Example: axios.delete(`/api/users/${userId}`).then(() => {
      //   this.fetchUsers();
      // });
    },
  },
  mounted() {
    this.fetchUsers();
  },
};
</script>

<style scoped>
.admin-panel {
  padding: 20px;
}
.admin-panel h1 {
  margin-bottom: 20px;
}
.admin-panel h2 {
  margin-top: 20px;
}
.admin-panel ul {
  list-style-type: none;
  padding: 0;
}
.admin-panel li {
  margin: 5px 0;
}
</style>