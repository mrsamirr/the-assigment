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

const today = computed(() => {
  const d = new Date()
  return d.toISOString().split('T')[0]
})

const timeSlots = computed(() => {
  const slots = []
  for (let hour = 9; hour < 18; hour++) {
    slots.push(`${hour.toString().padStart(2, '0')}:00`)
    slots.push(`${hour.toString().padStart(2, '0')}:30`)
  }
  slots.push('18:00')
  return slots
})

function validate() {
  errors.value = {}
  if (!title.value) {
    errors.value.title = 'Title is required'
  } else if (title.value.length > 150) {
    errors.value.title = 'Title must be less than 150 characters'
  }
  if (!date.value) {
    errors.value.date = 'Date is required'
  } else if (date.value < today.value) {
    errors.value.date = 'Date cannot be in the past'
  }
  if (!time.value) {
    errors.value.time = 'Time is required'
  }
  return Object.keys(errors.value).length === 0
}

async function handleSubmit() {
  serverError.value = ''
  if (!validate()) return

  loading.value = true
  try {
    const response = await appointmentsService.create(
      title.value,
      date.value,
      time.value,
      notes.value || null
    )
    if (response.success) {
      router.push('/dashboard')
    }
  } catch (error) {
    if (error.response?.status === 409) {
      serverError.value = 'This time slot is already booked. Please pick another time.'
    } else if (error.response?.data?.data) {
      const fieldErrors = error.response.data.data
      if (fieldErrors.title) errors.value.title = fieldErrors.title[0]
      if (fieldErrors.date) errors.value.date = fieldErrors.date[0]
      if (fieldErrors.time) errors.value.time = fieldErrors.time[0]
    } else if (error.response?.data?.message) {
      serverError.value = error.response.data.message
    } else {
      serverError.value = 'An error occurred. Please try again.'
    }
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="max-w-md mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Book Appointment</h1>

    <form @submit.prevent="handleSubmit" class="space-y-4">
      <div v-if="serverError" class="bg-red-50 text-red-600 p-3 rounded text-sm">
        {{ serverError }}
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
        <input
          v-model="title"
          type="text"
          placeholder="e.g. Haircut, Checkup"
          class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
          :class="{ 'border-red-500': errors.title }"
        />
        <p v-if="errors.title" class="text-red-500 text-sm mt-1">{{ errors.title }}</p>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
        <input
          v-model="date"
          type="date"
          :min="today"
          class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
          :class="{ 'border-red-500': errors.date }"
        />
        <p v-if="errors.date" class="text-red-500 text-sm mt-1">{{ errors.date }}</p>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Time</label>
        <select
          v-model="time"
          class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
          :class="{ 'border-red-500': errors.time }"
        >
          <option value="">Select a time</option>
          <option v-for="slot in timeSlots" :key="slot" :value="slot">
            {{ slot }}
          </option>
        </select>
        <p v-if="errors.time" class="text-red-500 text-sm mt-1">{{ errors.time }}</p>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Notes (optional)</label>
        <textarea
          v-model="notes"
          rows="3"
          class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
        ></textarea>
      </div>

      <div class="flex gap-3">
        <button
          type="button"
          @click="router.push('/dashboard')"
          class="flex-1 px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50"
        >
          Cancel
        </button>
        <button
          type="submit"
          :disabled="loading"
          class="flex-1 bg-blue-600 text-white py-2 rounded hover:bg-blue-700 disabled:opacity-50"
        >
          {{ loading ? 'Booking...' : 'Book Appointment' }}
        </button>
      </div>
    </form>
  </div>
</template>
