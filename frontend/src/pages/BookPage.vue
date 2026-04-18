<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { appointmentsService } from '../services/appointments'

const router = useRouter()

const title = ref('')
const date = ref('')
const time = ref('')
const notes = ref('')

const errors = ref({})
const serverError = ref('')
const loading = ref(false)

const today = computed(() => new Date().toISOString().split('T')[0])

const timeSlots = computed(() => {
  const slots = []
  for (let hour = 9; hour <= 18; hour++) {
    const formattedHour = hour.toString().padStart(2, '0')
    slots.push(`${formattedHour}:00`)
    if (hour !== 18) slots.push(`${formattedHour}:30`)
  }
  return slots
})

function formatTime(slot) {
  const [hours, minutes] = slot.split(':')
  const hour = parseInt(hours)
  const ampm = hour >= 12 ? 'pm' : 'am'
  const hour12 = hour % 12 || 12
  return `${hour12}:${minutes} ${ampm}`
}

function validate() {
  errors.value = {}
  if (!title.value) errors.value.title = 'Title is required'
  else if (title.value.length > 150) errors.value.title = 'Title is too long'
  
  if (!date.value) errors.value.date = 'Date is required'
  else if (date.value < today.value) errors.value.date = 'Cannot pick a past date'
  
  if (!time.value) errors.value.time = 'Time is required'
  
  return Object.keys(errors.value).length === 0
}

async function handleSubmit() {
  serverError.value = ''
  if (!validate()) return

  loading.value = true
  try {
    const response = await appointmentsService.create(title.value, date.value, time.value, notes.value || null)
    if (response.success) router.push('/dashboard')
  } catch (error) {
    if (error.response?.status === 409) {
      serverError.value = 'Time slot is unavailable. Please choose another.'
    } else if (error.response?.data?.data) {
      const e = error.response.data.data
      if (e.title) errors.value.title = e.title[0]
      if (e.date) errors.value.date = e.date[0]
      if (e.time) errors.value.time = e.time[0]
    } else {
      serverError.value = error.response?.data?.message || 'Failed to book. Try again.'
    }
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="max-w-lg mx-auto mt-6 bg-white p-8 rounded-xl shadow-sm border border-gray-200">
    <div class="mb-6 flex justify-between items-center">
      <h1 class="text-2xl font-bold tracking-tight text-gray-900">Book Appointment</h1>
      <button @click="router.push('/dashboard')" class="text-sm font-medium text-gray-500 hover:text-black">Cancel</button>
    </div>

    <div v-if="serverError" class="mb-6 p-4 rounded-md bg-red-50 border border-red-100 text-sm text-red-600">
      {{ serverError }}
    </div>

    <form @submit.prevent="handleSubmit" class="space-y-5">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Appointment Title</label>
        <input 
          v-model="title" 
          type="text" 
          :class="['w-full px-4 py-2 border rounded-md text-sm outline-none transition-colors', errors.title ? 'border-red-500 focus:border-red-500 focus:ring-1 focus:ring-red-500' : 'border-gray-300 focus:border-black focus:ring-1 focus:ring-black']"
          placeholder="e.g. Weekly Meeting, Checkup"
        >
        <p v-if="errors.title" class="text-red-500 text-xs mt-1">{{ errors.title }}</p>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
          <input 
            v-model="date" 
            type="date" 
            :min="today"
            :class="['w-full px-4 py-2 border rounded-md text-sm outline-none transition-colors', errors.date ? 'border-red-500 focus:border-red-500 focus:ring-1 focus:ring-red-500' : 'border-gray-300 focus:border-black focus:ring-1 focus:ring-black']"
          >
          <p v-if="errors.date" class="text-red-500 text-xs mt-1">{{ errors.date }}</p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Time</label>
          <select 
            v-model="time" 
            :class="['w-full px-4 py-2 border rounded-md text-sm outline-none transition-colors bg-white', errors.time ? 'border-red-500 focus:border-red-500 focus:ring-1 focus:ring-red-500' : 'border-gray-300 focus:border-black focus:ring-1 focus:ring-black']"
          >
            <option value="" disabled>Select time</option>
            <option v-for="slot in timeSlots" :key="slot" :value="slot">
              {{ formatTime(slot) }}
            </option>
          </select>
          <p v-if="errors.time" class="text-red-500 text-xs mt-1">{{ errors.time }}</p>
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Notes <span class="text-gray-400 font-normal">(optional)</span></label>
        <textarea 
          v-model="notes" 
          rows="3" 
          class="w-full px-4 py-2 border border-gray-300 rounded-md text-sm outline-none focus:border-black focus:ring-1 focus:ring-black transition-colors resize-none"
          placeholder="Any special requests or details..."
        ></textarea>
      </div>

      <button 
        type="submit" 
        :disabled="loading"
        class="w-full bg-black text-white py-2.5 rounded-md text-sm font-medium hover:bg-gray-800 disabled:opacity-50 transition-colors mt-4"
      >
        {{ loading ? 'Booking...' : 'Confirm Booking' }}
      </button>
    </form>
  </div>
</template>
