import api from './api'

export const authService = {
  async register(name, email, password) {
    const response = await api.post('/auth/register', { name, email, password })
    return response.data
  },

  async login(email, password) {
    const response = await api.post('/auth/login', { email, password })
    return response.data
  },

  async logout() {
    const response = await api.post('/auth/logout')
    return response.data
  },

  async getProfile() {
    const response = await api.get('/user/profile')
    return response.data
  },
}
