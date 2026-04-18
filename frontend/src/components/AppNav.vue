<script setup>
import { useAuthStore } from '../store/useAuthStore'
import { authService } from '../services/auth'
import { useRouter } from 'vue-router'

const authStore = useAuthStore()
const router = useRouter()

async function handleLogout() {
  try {
    await authService.logout()
  } catch (error) {
    // Ignore error
  }
  authStore.clearAuth()
}
</script>

<template>
  <nav class="bg-white border-b border-gray-200">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <div class="flex items-center gap-2">
          <div class="w-8 h-8 bg-black text-white rounded flex items-center justify-center font-bold text-sm">
            AB
          </div>
          <router-link to="/" class="font-semibold text-lg tracking-tight text-gray-900">
            Appointments
          </router-link>
        </div>
        
        <div class="flex items-center gap-6">
          <template v-if="authStore.isAuthenticated">
            <router-link to="/dashboard" class="text-sm font-medium text-gray-600 hover:text-black">Dashboard</router-link>
            <router-link to="/book" class="text-sm font-medium text-gray-600 hover:text-black">Book New</router-link>
            <router-link to="/profile" class="text-sm font-medium text-gray-600 hover:text-black">Profile</router-link>
            
            <div class="w-px h-4 bg-gray-300"></div>
            
            <button @click="handleLogout" class="text-sm font-medium text-gray-600 hover:text-red-500">
              Sign out
            </button>
          </template>
          <template v-else>
            <router-link to="/login" class="text-sm font-medium text-gray-600 hover:text-black">Login</router-link>
            <router-link to="/register" class="text-sm font-medium bg-black text-white px-4 py-2 rounded-md hover:bg-gray-800 transition-colors">
              Sign up
            </router-link>
          </template>
        </div>
      </div>
    </div>
  </nav>
</template>
