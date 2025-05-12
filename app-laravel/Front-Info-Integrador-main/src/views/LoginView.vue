<script setup lang="ts">
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { notifyError } from '@/services/notifications'

const email = ref('')
const senha = ref('')
const errors = ref('')
const loading = ref(false)

function onSubmit() {
  errors.value = ''
  loading.value = true

  const authStore = useAuthStore()

  authStore.login(email.value, senha.value)
    .catch((error) => {
      errors.value = error.message
      notifyError(error.message)
    })
    .finally(() => {
      loading.value = false
    })
}
</script>

<template>
  <div class="tela-login">
    <div class="lado-esquerdo"></div>
    <div class="lado-direito">
      <form class="formulario" @submit.prevent="onSubmit">
        <h2 class="titulo">LOGIN</h2>

        <input type="email" v-model="email" placeholder="@mail.com" required class="campo" />
        <input type="password" v-model="senha" placeholder="password" required class="campo" />

        <button type="submit" class="botao" :disabled="loading">
          {{ loading ? 'Carregando...' : 'Entrar' }}
        </button>

        <p class="texto-secundario">
          Não tem uma conta? <a href="/register" class="link">Inscrever-se</a>
        </p>
      </form>
    </div>
  </div>
</template>

<style scoped>
.tela-login {
  display: flex;
  height: 100vh;
  width: 100vw;
  font-family: sans-serif;
}

.lado-esquerdo {
  flex: 1;
  background: linear-gradient(to bottom right, #00b4ff, #007fd4);
}

.lado-direito {
  flex: 1;
  background-color: white;
  display: flex;
  align-items: center;
  justify-content: center;
}

.formulario {
  width: 100%;
  max-width: 300px;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.titulo {
  text-align: center;
  font-size: 1.8rem;
  font-weight: bold;
  color: #333;
}

.campo {
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 0.9rem;
}

.opcoes {
  display: flex;
  justify-content: space-between;
  font-size: 0.85rem;
  color: #555;
}

.link {
  color: #007bff;
  text-decoration: none;
}

.botao {
  padding: 10px;
  background-color: #00b4ff;
  color: white;
  font-weight: bold;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.botao:hover {
  background-color: #0096db;
}

.texto-secundario {
  text-align: center;
  font-size: 0.85rem;
}

.login-social {
  text-align: center;
  font-size: 0.85rem;
}

.botoes-sociais {
  display: flex;
  justify-content: center;
  gap: 0.5rem;
  margin-top: 0.5rem;
}

.social {
  background-color: #f1f1f1;
  border: none;
  padding: 8px 12px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 0.85rem;
  transition: background-color 0.2s ease;
}

.social:hover {
  background-color: #e0e0e0;
}
</style>
