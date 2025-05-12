<script setup lang="ts">
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { notifyError, notifySuccess } from '@/services/notifications'

const name = ref('')
const email = ref('')
const password = ref('')
const errors = ref('')

function onSubmit() {
   errors.value = ''
   const authStore = useAuthStore()

  authStore.register({ name: name.value, email: email.value, password: password.value })
  .then(() => {
      notifySuccess('Cadastro realizado com sucesso!')
    })
    .catch((error) => {
      errors.value = error.message
      notifyError(error.message)
    })
}
</script>

<template>
  <div class="tela-cadastro">
    <div class="lado-esquerdo"></div>
    <div class="lado-direito">
      <form class="formulario" @submit.prevent="onSubmit">
        <h2 class="titulo">CADASTRO</h2>

        <input type="text" v-model="name" placeholder="Nome completo" required class="campo" />
        <input type="email" v-model="email" placeholder="@mail.com" required class="campo" />
        <input type="password" v-model="password" placeholder="password" required class="campo" />

        <button type="submit" class="botao">Cadastrar</button>

        <p class="texto-secundario">
          Já tem uma conta? <a href="/" class="link">Entrar</a>
        </p>
      </form>
    </div>
  </div>
</template>

<style scoped>
.tela-cadastro {
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
</style>
