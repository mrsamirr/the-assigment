<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { appointmentsService } from '../services/appointments'
import AppointmentCard from '../components/AppointmentCard.vue'

const router = useRouter()

const appointments = ref([])
const loading = ref(true)
const error = ref('')

async function fetchAppointments() {
  loading.value = true
  error.value = ''
  try {
    const response = await appointmentsService.getAll()
    if (response.success) {
      appointments.value = response.data
    }
  } catch (err) {
    error.value = 'Failed to load appointments'
  } finally {
    loading.value = false
  }
}

async function handleCancel(id) {
  try {
    const response = await appointmentsService.cancel(id)
    if (response.success) {
      await fetchAppointments()
    }
  } catch (err) {
    error.value = 'Failed to cancel appointment'
  }
}

onMounted(() => {
  fetchAppointments()
})
</script>

<template>
  <div class="max-w-2xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-900">My Appointments</h1>
      <router-link
        to="/book"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm"
      >
        Book New
      </router-link>
    </div>

    <div v-if="loading" class="text-center py-8 text-gray-500">
      Loading...
    </div>

    <div v-else-if="error" class="bg-red-50 text-red-600 p-4 rounded">
      {{ error }}
    </div>

    <div v-else-if="appointments.length === 0" class="text-center py-8 text-gray-500">
      <p>No appointments yet.</p>
      <router-link to="/book" class="text-blue-600 hover:underline mt-2 inline-block">
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
