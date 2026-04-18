<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../store/useAuthStore'
import { authService } from '../services/auth'

const router = useRouter()
const authStore = useAuthStore()

const email = ref('')
const password = ref('')
const errors = ref({})
const serverError = ref('')
const loading = ref(false)

function validate() {
  errors.value = {}
  if (!email.value) errors.value.email = 'Email is required'
  else if (!/\S+@\S+\.\S+/.test(email.value)) errors.value.email = 'Invalid email format'
  if (!password.value) errors.value.password = 'Password is required'
  return Object.keys(errors.value).length === 0
}

async function handleSubmit() {
  serverError.value = ''
  if (!validate()) return

  loading.value = true
  try {
    const response = await authService.login(email.value, password.value)
    if (response.success) {
      authStore.setAuth(response.data.token, response.data.user)
      router.push('/dashboard')
    }
  } catch (error) {
    if (error.response?.data?.message) {
      serverError.value = error.response.data.message
    } else {
      serverError.value = 'Failed to sign in. Please try again.'
    }
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="max-w-md mx-auto mt-12 bg-white p-8 rounded-xl shadow-sm border border-gray-200">
    <div class="text-center mb-8">
      <h1 class="text-2xl font-bold tracking-tight text-gray-900">Sign in</h1>
      <p class="text-sm text-gray-500 mt-2">Enter your details to access your appointments</p>
    </div>

    <div v-if="serverError" class="mb-6 p-4 rounded-md bg-red-50 border border-red-100 text-sm text-red-600">
      {{ serverError }}
    </div>

    <form @submit.prevent="handleSubmit" class="space-y-5">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
        <input 
          v-model="email" 
          type="email" 
          :class="['w-full px-4 py-2 border rounded-md text-sm outline-none transition-colors', errors.email ? 'border-red-500 focus:border-red-500 focus:ring-1 focus:ring-red-500' : 'border-gray-300 focus:border-black focus:ring-1 focus:ring-black']"
          placeholder="user@example.com"
        >
        <p v-if="errors.email" class="text-red-500 text-xs mt-1">{{ errors.email }}</p>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input 
          v-model="password" 
          type="password" 
          :class="['w-full px-4 py-2 border rounded-md text-sm outline-none transition-colors', errors.password ? 'border-red-500 focus:border-red-500 focus:ring-1 focus:ring-red-500' : 'border-gray-300 focus:border-black focus:ring-1 focus:ring-black']"
          placeholder="••••••••"
        >
        <p v-if="errors.password" class="text-red-500 text-xs mt-1">{{ errors.password }}</p>
      </div>

      <button 
        type="submit" 
        :disabled="loading"
        class="w-full bg-black text-white py-2.5 rounded-md text-sm font-medium hover:bg-gray-800 disabled:opacity-50 transition-colors mt-2"
      >
        {{ loading ? 'Signing in...' : 'Sign in' }}
      </button>
    </form>

    <div class="mt-6 text-center text-sm text-gray-600">
      Don't have an account? 
      <router-link to="/register" class="text-black font-medium hover:underline">Sign up</router-link>
    </div>
  </div>
</template>
