<template>
  <div class="register">
    <h1>Register</h1>
    <Register />
  </div>
</template>

<script>
import Register from '../components/Register.vue';

export default {
  components: {
    Register
  }
}
</script>

<style scoped>
.register {
  max-width: 400px;
  margin: auto;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 5px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}
</style>