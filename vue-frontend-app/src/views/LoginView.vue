<template>
  <div class="login-view">
    <h1>Login</h1>
    <Login />
  </div>
</template>

<script>
import Login from '@/components/Login.vue';

export default {
  components: {
    Login
  }
}
</script>

<style scoped>
.login-view {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100vh;
}
</style>