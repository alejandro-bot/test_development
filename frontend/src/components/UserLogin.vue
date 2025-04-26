<template>
    <div class="user-login">
        <h2>Login</h2>
        <form @submit.prevent="handleLogin">
            <div class="form-group">
                <label for="email">Email:</label>
                <input id="email" v-model="credentials.email" type="email" required />
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input id="password" v-model="credentials.password" type="password" required />
            </div>
            <button type="submit" :disabled="userStore.state.loading">
                {{ userStore.state.loading ? 'Loading...' : 'Login' }}
            </button>
            <p v-if="userStore.state.error" class="error">
                {{ userStore.state.error }}
            </p>
        </form>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useUserStore } from '@/stores/userStore'
import type { UserCredentials } from '@/types/user'

const userStore = useUserStore()
const credentials = ref<UserCredentials>({
    email: '',
    password: ''
})

const handleLogin = async () => {
    try {
        await userStore.login(credentials.value)
        // Redirect or show success message
    } catch (error) {
        // Error is already handled in the store
    }
}
</script>

<style scoped>
.user-login {
    max-width: 400px;
    margin: 0 auto;
    padding: 20px;
}

.form-group {
    margin-bottom: 15px;
}

label {
    display: block;
    margin-bottom: 5px;
}

input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

button {
    width: 100%;
    padding: 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

button:disabled {
    background-color: #cccccc;
    cursor: not-allowed;
}

.error {
    color: red;
    margin-top: 10px;
}
</style>