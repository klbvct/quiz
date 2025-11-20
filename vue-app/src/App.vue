<template>
  <div class="container">
    <h1>Quiz Vue App</h1>
    <div class="form">
      <input v-model="input" placeholder="Type something to check" />
      <button @click="runCheck">Check</button>
    </div>
    <p><strong>Result:</strong> {{ result }}</p>
    <p v-if="error" style="color:crimson"><strong>Error:</strong> {{ error }}</p>
  </div>
</template>

<script>
import { ref } from 'vue'

export default {
  setup() {
    const input = ref('')
    const result = ref('')
    const error = ref('')

    async function runCheck() {
      error.value = ''
      result.value = ''
      try {
        const res = await fetch('http://localhost:3000/check', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ input: input.value })
        })
        if (!res.ok) throw new Error("Server response " + res.status)
        const data = await res.json()
        result.value = JSON.stringify(data)
      } catch (e) {
        error.value = e.message
      }
    }

    return { input, result, error, runCheck }
  }
}
</script>

<style>
  .container { max-width: 600px; margin: 40px auto; font-family: Arial, sans-serif }
  .form { display:flex; gap:8px }
  input { flex:1; padding:8px }
  button { padding:8px 12px }
</style>
