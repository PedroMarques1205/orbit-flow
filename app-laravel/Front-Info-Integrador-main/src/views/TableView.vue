<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useViagemStore } from '@/stores/travels'
import { notifySuccess } from '@/services/notifications'

const authStore = useAuthStore()
const viagemStore = useViagemStore()

const currentPage = ref(1)
const itemsPerPage = 5

const filtroTexto = ref('')
const filtroStatus = ref('')
const dataIdaInicio = ref('')
const dataIdaFim = ref('')
const dataVoltaInicio = ref('')
const dataVoltaFim = ref('')
const mostrarModal = ref(false)
const destino = ref('')
const dataIda = ref('')
const dataVolta = ref('')
const emailSolicitante = ref('')

function enviarNovaViagem() {
  const novaViagem = {
    destino: destino.value,
    data_ida: dataIda.value,
    data_volta: dataVolta.value,
    email_solicitante: emailSolicitante.value
  }
  
  viagemStore.criarViagem(novaViagem)
    .then(() => {
      fecharModal()
      viagemStore.buscarViagens()
      destino.value = ''
      dataIda.value = ''
      dataVolta.value = ''
      emailSolicitante.value = ''
      notifySuccess('Solicitação Realizada!')
    })
    .catch(erro => {
      console.error('Erro ao cadastrar viagem:', erro)
    })
}

function abrirModal() {
  mostrarModal.value = true
}

function fecharModal() {
  mostrarModal.value = false
}

const viagensFiltradas = computed(() => {
  return viagemStore.viagens.filter(viagem => {
    const termo = filtroTexto.value.toLowerCase()
    const matchTexto =
      viagem.destino.toLowerCase().includes(termo) ||
      viagem.nome_solicitante.toLowerCase().includes(termo)

    const matchStatus = !filtroStatus.value || viagem.status === filtroStatus.value

    const ida = new Date(viagem.data_ida)
    const volta = new Date(viagem.data_volta)

    const matchDataIda =
      (!dataIdaInicio.value || ida >= new Date(dataIdaInicio.value)) &&
      (!dataIdaFim.value || ida <= new Date(dataIdaFim.value))

    const matchDataVolta =
      (!dataVoltaInicio.value || volta >= new Date(dataVoltaInicio.value)) &&
      (!dataVoltaFim.value || volta <= new Date(dataVoltaFim.value))

    return matchTexto && matchStatus && matchDataIda && matchDataVolta
  })
})

const totalPages = computed(() => Math.ceil(viagensFiltradas.value.length / itemsPerPage))

const viagensPaginadas = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  return viagensFiltradas.value.slice(start, start + itemsPerPage)
})

function limparFiltros() {
  filtroTexto.value = ''
  filtroStatus.value = ''
  dataIdaInicio.value = ''
  dataIdaFim.value = ''
  dataVoltaInicio.value = ''
  dataVoltaFim.value = ''
  currentPage.value = 1
}

function proximaPagina() {
  if (currentPage.value < totalPages.value) currentPage.value++
}

function paginaAnterior() {
  if (currentPage.value > 1) currentPage.value--
}

function aprovar(id: number) {
  viagemStore.aprovarViagem(id)
}

function reprovar(id: number) {
  viagemStore.reprovarViagem(id)
}

onMounted(() => {
  viagemStore.buscarViagens()
})
</script>


<template>
  <div class="container">

    <div class="main">
      <header class="navbar">
        <h1>Bem-vindo</h1>
        <div class="user-info">Usuário: {{ authStore.Usuario?.name }}</div>
      </header>

      <main class="content">
        <h2>Dashboard viagens</h2>
        
        <div v-if="viagemStore.carregando" class="loading">
          Carregando viagens...
        </div>
        
        
        <div v-if="!viagemStore.carregando">
            <div class="filtros">
                <input
                    v-model="filtroTexto"
                    type="text"
                    placeholder="Buscar por destino ou solicitante..."
                    class="filter-input"
                />

                <select v-model="filtroStatus" class="filter-select">
                    <option value="">Todos os Status</option>
                    <option value="pendente">Pendente</option>
                    <option value="aprovado">Aprovado</option>
                    <option value="rejeitado">Rejeitado</option>
                    <option value="cancelado">Cancelado</option>
                </select>
                
                <div class="date-range">
                    <label>Data Ida:</label>
                    <input type="date" v-model="dataIdaInicio" />
                    <input type="date" v-model="dataIdaFim" />
                </div>

                <div class="date-range">
                    <label>Data Volta:</label>
                    <input type="date" v-model="dataVoltaInicio" />
                    <input type="date" v-model="dataVoltaFim" />
                </div>
                <button class="btn limpar" @click="limparFiltros">
                    Limpar Filtros
                </button>
            </div>
            <button class="btn nova-solicitacao" @click="abrirModal">
              Realizar Nova Solicitação
            </button>
            <table class="viagem-table">
                <thead>
                    <tr>
                    <th>Solicitante</th>
                    <th>Destino</th>
                    <th>Data Ida</th>
                    <th>Data Volta</th>
                    <th>Status</th>
                    <th>Usuário</th>
                    <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="viagem in viagensPaginadas" :key="viagem.id">
                    <td>{{ viagem.nome_solicitante }}</td>
                    <td>{{ viagem.destino }}</td>
                    <td>{{ viagem.data_ida }}</td>
                    <td>{{ viagem.data_volta }}</td>
                    <td>
                        <span
                        :class="[
                            'status-badge',
                            viagem.status === 'pendente' ? 'pending' : 
                            viagem.status === 'aprovado' ? 'approved' : 
                            'rejected'
                        ]"
                        >
                        {{ viagem.status }}
                        </span>
                    </td>
                    <td>{{ viagem.usuario.nome }}</td>
                    <td class="actions">
                        <button class="btn approve" @click="aprovar(viagem.id)">Aprovar</button>
                        <button class="btn reject" @click="reprovar(viagem.id)">Cancelar</button>
                    </td>
                    </tr>
                </tbody>
            </table>
            <div class="pagination">
                <button class="btn small" @click="paginaAnterior" :disabled="currentPage === 1">
                    « Anterior
                </button>
                <span class="page-info">Página {{ currentPage }} de {{ totalPages }}</span>
                <button class="btn small" @click="proximaPagina" :disabled="currentPage === totalPages">
                    Próxima »
                </button>
            </div>
            <div v-if="mostrarModal" class="modal-overlay">
              <div class="modal-content">
                <h3>Nova Solicitação de Viagem</h3>
                <form @submit.prevent="enviarNovaViagem">
                  <input v-model="destino" type="text" placeholder="Destino" required />
                  
                  <p>Data Ida:</p>
                  <input v-model="dataIda" type="date" required />
                  
                  <p>Data Volta:</p>
                  <input v-model="dataVolta" type="date" required />
                  
                  <input v-model="emailSolicitante" type="text" placeholder="Email do Solicitante" required />

                  <div class="modal-actions">
                    <button type="submit" class="btn approve">Enviar</button>
                    <button type="button" class="btn reject" @click="fecharModal">Cancelar</button>
                  </div>
                </form>
              </div>
            </div>
        </div>

        <div v-if="!viagemStore.carregando && viagemStore.viagens.length === 0">
          Nenhuma viagem encontrada.
        </div>
      </main>
    </div>
  </div>
</template>

<style scoped>
.container {
  display: flex;
  height: 100vh;
  background-color: #f5f9ff; 
  color: #1e293b;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.main {
  flex: 1;
  display: flex;
  flex-direction: column;
}

.navbar {
  background-color: #e0f2fe; 
  padding: 1rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.navbar h1 {
  font-size: 1.25rem;
  font-weight: 600;
  color: #1e3a8a;
}

.user-info {
  font-size: 0.875rem;
  color: #3b82f6;
}

.content {
  flex: 1;
  padding: 1.5rem;
}

.content h2 {
  font-size: 1.75rem;
  font-weight: bold;
  color: #3b82f6;
  margin-bottom: 1rem;
}

.loading {
  color: #3b82f6;
  font-size: 1.25rem;
  font-weight: bold;
}

.viagem-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 1.5rem;
  background-color: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}

.viagem-table thead {
  background-color: #bfdbfe;
}

.viagem-table th,
.viagem-table td {
  padding: 1rem;
  text-align: left;
  color: #1e293b;
  border-bottom: 1px solid #e2e8f0;
}

.viagem-table tr:hover {
  background-color: #e0f2fe;
}

.status-badge {
  padding: 0.4rem 0.8rem;
  border-radius: 999px;
  font-weight: 600;
  text-transform: capitalize;
}

.status-badge.pending {
  background-color: #fde68a;
  color: #78350f;
}

.status-badge.approved {
  background-color: #bbf7d0;
  color: #065f46;
}

.status-badge.rejected {
  background-color: #fecaca;
  color: #7f1d1d;
}

.actions {
  display: flex;
  gap: 0.5rem;
}

.btn {
  padding: 0.5rem 0.8rem;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: bold;
  transition: background 0.3s ease;
}

.btn.approve {
  background-color: #4ade80;
  color: #065f46;
}

.btn.reject {
  background-color: #f87171;
  color: #7f1d1d;
}

.btn.limpar {
  background-color: #e0e7ff;
  color: #3730a3;
}

.btn.small {
  font-size: 0.85rem;
  background-color: #e2e8f0;
  color: #1e293b;
}

.filter-input,
.filter-select,
input[type="date"] {
  padding: 0.5rem;
  border: 1px solid #cbd5e1;
  border-radius: 6px;
  background-color: #ffffff;
  color: #1e293b;
  margin-right: 0.5rem;
}

.filtros {
  margin-bottom: 1rem;
  display: flex;
  flex-wrap: wrap;
  gap: 0.75rem;
  align-items: center;
}

.date-range {
  display: flex;
  flex-direction: column;
}

.pagination {
  margin-top: 1rem;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 1rem;
}

.page-info {
  color: #1e293b;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(30, 41, 59, 0.6);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background-color: white;
  padding: 2rem;
  border-radius: 12px;
  width: 400px;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
}

.modal-content h3 {
  margin-bottom: 1rem;
  color: #1e3a8a;
}

.modal-content form input {
  display: block;
  width: 100%;
  margin-bottom: 1rem;
  padding: 0.5rem;
  border: 1px solid #cbd5e1;
  border-radius: 6px;
}

.modal-actions {
  display: flex;
  justify-content: space-between;
}

</style>
