<script setup>
import { useAuthStore } from '../store/useAuthStore'

const authStore = useAuthStore()
</script>

<template>
  <div class="max-w-2xl mx-auto mt-6">
    <h1 class="text-2xl font-bold tracking-tight text-gray-900 mb-6 border-b border-gray-200 pb-4">Personal Profile</h1>
    
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden text-sm">
      <div class="p-6 border-b border-gray-100 flex items-center gap-4">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 text-xl font-bold border border-gray-200">
          {{ authStore.user?.name?.charAt(0)?.toUpperCase() || 'U' }}
        </div>
        <div>
          <h2 class="text-lg font-bold text-gray-900">{{ authStore.user?.name }}</h2>
          <p class="text-gray-500">Member</p>
        </div>
      </div>
      
      <div class="px-6 py-4 grid grid-cols-3 gap-4 border-b border-gray-100">
        <div class="text-gray-500 font-medium">Full Name</div>
        <div class="col-span-2 text-gray-900 font-medium">{{ authStore.user?.name }}</div>
      </div>
      
      <div class="px-6 py-4 grid grid-cols-3 gap-4 border-b border-gray-100 bg-gray-50">
        <div class="text-gray-500 font-medium">Email Address</div>
        <div class="col-span-2 text-gray-900 font-medium">{{ authStore.user?.email }}</div>
      </div>
      
      <div class="p-6 bg-gray-50 flex items-center justify-end">
        <router-link to="/dashboard" class="text-black font-semibold border-b border-black pb-0.5 hover:text-gray-600 transition-colors">
          Return to Dashboard
        </router-link>
      </div>
    </div>
  </div>
</template>
