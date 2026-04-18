<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  appointment: {
    type: Object,
    required: true,
  },
})

const emit = defineEmits(['cancel'])

const showConfirm = ref(false)

const statusColors = {
  pending: 'bg-yellow-100 text-yellow-800',
  confirmed: 'bg-green-100 text-green-800',
  cancelled: 'bg-red-100 text-red-800',
}

const canCancel = computed(() => {
  return props.appointment.status !== 'cancelled'
})

function formatDate(dateStr) {
  return new Date(dateStr).toLocaleDateString('en-US', {
    weekday: 'short',
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  })
}

function formatTime(timeStr) {
  const [hours, minutes] = timeStr.split(':')
  const hour = parseInt(hours)
  const ampm = hour >= 12 ? 'PM' : 'AM'
  const hour12 = hour % 12 || 12
  return `${hour12}:${minutes} ${ampm}`
}

function confirmCancel() {
  if (window.confirm('Are you sure you want to cancel this appointment?')) {
    emit('cancel', props.appointment.id)
  }
}
</script>

<template>
  <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
    <div class="flex justify-between items-start">
      <div>
        <h3 class="font-medium text-gray-900">{{ appointment.title }}</h3>
        <p class="text-sm text-gray-600 mt-1">
          {{ formatDate(appointment.date) }} at {{ formatTime(appointment.time) }}
        </p>
        <p v-if="appointment.notes" class="text-sm text-gray-500 mt-2">
          {{ appointment.notes }}
        </p>
      </div>
      <div class="flex flex-col items-end gap-2">
        <span
          :class="[
            'px-2 py-1 rounded text-xs font-medium capitalize',
            statusColors[appointment.status],
          ]"
        >
          {{ appointment.status }}
        </span>
        <button
          v-if="canCancel"
          @click="confirmCancel"
          class="text-red-600 hover:text-red-800 text-sm"
        >
          Cancel
        </button>
      </div>
    </div>
  </div>
</template>
