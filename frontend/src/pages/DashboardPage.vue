<script setup>
import { ref, onMounted } from 'vue'
import { appointmentsService } from '../services/appointments'
import AppointmentCard from '../components/AppointmentCard.vue'

const appointments = ref([])
const loading = ref(true)
const error = ref('')
const feedback = ref('')

async function fetchAppointments() {
  loading.value = true
  error.value = ''
  try {
    const response = await appointmentsService.getAll()
    if (response.success) appointments.value = response.data
  } catch (err) {
    error.value = 'Failed to load appointments.'
  } finally {
    loading.value = false
  }
}

async function handleCancel(id) {
  try {
    const response = await appointmentsService.cancel(id)
    if (response.success) {
      feedback.value = 'Appointment cancelled.'
      setTimeout(() => { feedback.value = '' }, 3000)
      await fetchAppointments()
    }
  } catch (err) {
    error.value = 'Failed to cancel appointment.'
  }
}

onMounted(() => {
  fetchAppointments()
})
</script>

<template>
  <div class="max-w-3xl mx-auto">
    <div class="flex items-center justify-between mb-8 border-b border-gray-200 pb-4">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Your Appointments</h1>
        <p class="text-sm text-gray-500 mt-1">Manage and track your schedule</p>
      </div>
      <router-link to="/book" class="bg-black text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-gray-800 transition-colors">
        + Book Appointment
      </router-link>
    </div>

    <div v-if="error" class="mb-6 p-4 rounded-md bg-red-50 border border-red-100 text-sm text-red-600">
      {{ error }}
    </div>
    
    <div v-if="feedback" class="mb-6 p-4 rounded-md bg-green-50 border border-green-100 text-sm text-green-700">
      {{ feedback }}
    </div>

    <div v-if="loading" class="text-gray-500 text-center py-12 border-2 border-dashed border-gray-200 rounded-lg">
      Loading your appointments...
    </div>

    <div v-else-if="appointments.length === 0" class="text-center py-16 border-2 border-dashed border-gray-200 rounded-lg">
      <h3 class="text-lg font-medium text-gray-900 mb-2">No appointments yet</h3>
      <p class="text-gray-500 text-sm mb-6">Looks like you haven't scheduled anything.</p>
      <router-link to="/book" class="text-black font-semibold border-b border-black pb-0.5 hover:text-gray-600 hover:border-gray-600 transition-colors">
        Book your first appointment
      </router-link>
    </div>

    <div v-else class="space-y-4">
      <AppointmentCard
        v-for="appointment in appointments"
        :key="appointment.id"
        :appointment="appointment"
        @cancel="handleCancel"
      />
    </div>
  </div>
</template>
