import api from './api'

export const appointmentsService = {
  async getAll() {
    const response = await api.get('/appointments')
    return response.data
  },

  async create(title, date, time, notes = null) {
    const response = await api.post('/appointments', { title, date, time, notes })
    return response.data
  },

  async cancel(id) {
    const response = await api.delete(`/appointments/${id}`)
    return response.data
  },
}
