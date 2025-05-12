import { api } from "@/services/axios"
import { defineStore } from "pinia"
import Cookies from "js-cookie"

export interface Usuario {
  id: number
  nome: string
  email: string
}

export interface Viagem {
  id: number
  nome_solicitante: string
  destino: string
  data_ida: string
  data_volta: string
  status: string
  created_at: string
  usuario: Usuario
}

export interface NovaViagemPayload {
  email_solicitante: string
  destino: string
  data_ida: string
  data_volta: string
}

export const useViagemStore = defineStore('viagem', {
  state: (): { viagens: Viagem[]; carregando: boolean; erro: string | null } => ({
    viagens: [],
    carregando: false,
    erro: null
  }),
  actions: {
    async buscarViagens() {
      this.carregando = true
      this.erro = null
      try {
        const token = Cookies.get('token') 
        const response = await api.get('/request-travels', {
          headers: {
            Authorization: `Bearer ${token}`
          }
        })
        this.viagens = response.data.data
      } catch (e: any) {
        this.erro = e.message || 'Erro ao buscar viagens'
        console.error('Erro ao buscar viagens:', e)
      } finally {
        this.carregando = false
      }
    },

    async criarViagem(payload: NovaViagemPayload) : Promise<void> {
      this.erro = null
      try {
        const token = Cookies.get('token')
        await api.post('/request-travels', payload, {
          headers: {
            Authorization: `Bearer ${token}`
          }
        })
        await this.buscarViagens()
      } catch (e: any) {
        this.erro = e.message || 'Erro ao criar viagem'
        console.error('Erro ao criar viagem:', e)
      }
    },
    
    async aprovarViagem(id: number) {
      const token = Cookies.get('token')
      await api.patch(`/request-travels/${id}/aprove`, {}, {
        headers: {
          Authorization: `Bearer ${token}`
        }
      })
      await this.buscarViagens()
    },

    async reprovarViagem(id: number) {
      const token = Cookies.get('token')
      await api.patch(`/request-travels/${id}/reject`, {}, {
        headers: {
          Authorization: `Bearer ${token}`
        }
      })
      await this.buscarViagens()
    }
  }
})
