import { useAuthStore } from '@/stores/auth'
import axios from 'axios'

export const api = axios.create({
  baseURL: 'http://localhost:9005/api/v1',
  headers: {
    'Content-Type': 'application/json'
  }
})

api.interceptors.request.use((config) => {
  const auth = useAuthStore()
  if (auth.token) {
    config.headers.Authorization = `Bearer ${auth.token}`
  }
  return config
})

api.interceptors.response.use(
  (response) => response,
  (error) => {
    const auth = useAuthStore()
    
    if (!error.response) {
      return Promise.reject({ message: 'Erro de conexão com o servidor' })
    }

    if (error.response.status === 401) {
      return Promise.reject({ message: error.response?.data })
    }

    const errorMessage = error.response?.data || 'Erro desconhecido'
    return Promise.reject({ message: errorMessage })
  }
)