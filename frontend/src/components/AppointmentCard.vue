<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  appointment: {
    type: Object,
    required: true,
  },
})

const emit = defineEmits(['cancel'])
const confirming = ref(false)

const badgeStyles = {
  pending: 'bg-yellow-100 text-yellow-800 border-yellow-200',
  confirmed: 'bg-green-100 text-green-800 border-green-200',
  cancelled: 'bg-gray-100 text-gray-500 border-gray-200',
}

const canCancel = computed(() => props.appointment.status !== 'cancelled')

function formatDate(dateStr) {
  return new Date(dateStr).toLocaleDateString('en-US', {
    weekday: 'short', month: 'short', day: 'numeric', year: 'numeric'
  })
}

function formatTime(timeStr) {
  const [hours, minutes] = timeStr.split(':')
  const hour = parseInt(hours)
  const ampm = hour >= 12 ? 'PM' : 'AM'
  const hour12 = hour % 12 || 12
  return `${hour12}:${minutes} ${ampm}`
}

function handleCancel() {
  if (confirming.value) {
    emit('cancel', props.appointment.id)
    confirming.value = false
  } else {
    confirming.value = true
    setTimeout(() => { confirming.value = false }, 3000)
  }
}
</script>

<template>
  <div :class="['bg-white p-5 rounded-lg border shadow-sm flex flex-col sm:flex-row justify-between sm:items-start gap-4 transition-colors', appointment.status === 'cancelled' ? 'border-gray-200 opacity-75' : 'border-gray-200 hover:border-gray-300']">
    <div class="space-y-2 flex-1">
      <div class="flex items-center gap-3">
        <h3 class="font-semibold text-gray-900 border-l-2 border-black pl-2 leading-none">
          {{ appointment.title }}
        </h3>
        <span :class="['px-2.5 py-0.5 text-xs font-semibold rounded-full border uppercase tracking-wider', badgeStyles[appointment.status] || badgeStyles.pending]">
          {{ appointment.status }}
        </span>
      </div>
      
      <div class="text-sm text-gray-600 flex items-center gap-2 pl-2">
        <span class="font-medium text-gray-800">{{ formatDate(appointment.date) }}</span>
        <span>at</span>
        <span class="font-medium text-gray-800">{{ formatTime(appointment.time) }}</span>
      </div>
      
      <p v-if="appointment.notes" class="text-sm text-gray-500 mt-2 pl-2 italic">
        "{{ appointment.notes }}"
      </p>
    </div>
    
    <div class="pt-1 flex sm:flex-col items-end gap-2">
      <button 
        v-if="canCancel"
        @click="handleCancel"
        :class="['text-xs font-medium px-3 py-1.5 rounded transition-colors', confirming ? 'bg-red-600 text-white hover:bg-red-700' : 'bg-red-50 text-red-600 hover:bg-red-100']"
      >
        {{ confirming ? 'Confirm Cancel?' : 'Cancel' }}
      </button>
    </div>
  </div>
</template>
