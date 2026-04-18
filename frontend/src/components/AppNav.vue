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
    // Ignore logout errors
  }
  authStore.clearAuth()
}
</script>

<template>
  <nav class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-4xl mx-auto px-4">
      <div class="flex justify-between h-14">
        <div class="flex items-center">
          <span class="text-lg font-semibold text-gray-800">Appointments</span>
        </div>
        <div class="flex items-center space-x-4">
          <template v-if="authStore.isAuthenticated">
            <router-link
              to="/dashboard"
              class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm"
            >
              Dashboard
            </router-link>
            <router-link
              to="/book"
              class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm"
            >
              Book
            </router-link>
            <router-link
              to="/profile"
              class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm"
            >
              Profile
            </router-link>
            <button
              @click="handleLogout"
              class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm"
            >
              Logout
            </button>
          </template>
          <template v-else>
            <router-link
              to="/login"
              class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm"
            >
              Login
            </router-link>
            <router-link
              to="/register"
              class="bg-blue-600 text-white hover:bg-blue-700 px-4 py-2 rounded text-sm"
            >
              Register
            </router-link>
          </template>
        </div>
      </div>
    </div>
  </nav>
</template>
