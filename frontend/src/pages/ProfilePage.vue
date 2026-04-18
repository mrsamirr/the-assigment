<script setup>
import { useAuthStore } from '../store/useAuthStore'

const authStore = useAuthStore()
</script>

<template>
  <div class="max-w-md mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Profile</h1>

    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-500">Name</label>
          <p class="mt-1 text-lg text-gray-900">{{ authStore.user?.name }}</p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-500">Email</label>
          <p class="mt-1 text-lg text-gray-900">{{ authStore.user?.email }}</p>
        </div>
      </div>
    </div>
  </div>
</template>
