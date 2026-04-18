<script setup>
import AppNav from './components/AppNav.vue'
</script>

<template>
  <div class="min-h-screen bg-gray-50 flex flex-col font-sans text-gray-900">
    <AppNav />
    <main class="flex-1 w-full max-w-5xl mx-auto p-4 sm:p-6 lg:p-8">
      <router-view />
    </main>
    <footer class="bg-white border-t border-gray-200 py-4">
      <div class="max-w-4xl mx-auto px-4 text-center text-sm text-gray-500">
        Assignment submitted by Md Samer Ansari |
        <a href="https://github.com/mrsamirr" class="text-blue-600 hover:underline">GitHub</a> |
        <a href="https://www.linkedin.com/in/mrsamirr/" class="text-blue-600 hover:underline">LinkedIn</a>
      </div>
    </footer>
  </div>
</template>
