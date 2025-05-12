import { defineStore } from 'pinia'
import { api } from '@/services/axios'
import router from '@/router/index'
import Cookies from 'js-cookie'
import { notifySuccess } from '@/services/notifications'

export interface Usuario {
  id: number
  name: string
  email: string
  email_verified_at: string | null
  created_at: string
  updated_at: string
}

export const useAuthStore = defineStore({
  id: 'auth',
  state: (): { token: string | null; Usuario: Usuario | null; returnUrl?: string } => ({
  token: Cookies.get('token') || null,
  Usuario: Cookies.get('Usuario') ? JSON.parse(Cookies.get('Usuario')!) : null
}),
  actions: {
    async login(username: string, password: string) {
      try {
        const response = await api.post('/login', { 
          email: username, 
          password: password 
        })

        const user = response.data.user

        this.Usuario = user
        Cookies.set('Usuario', JSON.stringify(user))

        router.push('/home')
      } catch (error: any) {
        if (error.response) {
          console.error('Erro na resposta da API:', error.response.status, error.response.data)
        } else if (error.request) {
          console.error('A requisição foi feita mas não houve resposta:', error.request)
        } else {
          console.error('Erro ao configurar a requisição:', error.message)
        }
      }
    },  
    async register(payload: { name: string; email: string; password: string }) {
      try {
        console.log('chegou')
        const response = await api.post('/users', payload)

        const user = response.data.user

        this.Usuario = user

        router.push('/')
      } catch (error: any) {
        if (error.response) {
          console.error('Erro na resposta da API:', error.response.status, error.response.data)
        } else if (error.request) {
          console.error('A requisição foi feita mas não houve resposta:', error.request)
        } else {
          console.error('Erro ao configurar a requisição:', error.message)
        }
        throw new Error(error.response?.data?.message || 'Erro ao fazer cadastro')
      }
    },
    logout() {
      this.token = ''
      this.Usuario = null
      Cookies.remove('token')
      Cookies.remove('Usuario')
      router.push('/')
    }
  }
})

