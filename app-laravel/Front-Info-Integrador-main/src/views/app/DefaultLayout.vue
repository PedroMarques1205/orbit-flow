<template>
  <div class="flex h-screen bg-gray-100">
    <aside class="w-64 bg-blue-800 text-white p-4">
      <h2 class="text-xl font-bold mb-6">Menu</h2>
      <ul class="space-y-2">
        <li>
          <RouterLink to="/home" class="hover:underline">Home</RouterLink>
        </li>
        <li>
          <RouterLink to="/outra" class="hover:underline">Outra Página</RouterLink>
        </li>
      </ul>
    </aside>

    <div class="flex-1 flex flex-col">
      <nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
        <div class="text-lg font-semibold">Minha Aplicação</div>
        <div>
          <button @click="logout" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
            Sair
          </button>
        </div>
      </nav>

      <main class="p-6 overflow-auto">
        <RouterView />
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from '@/stores/auth'

const auth = useAuthStore()

function logout() {
  auth.logout()
}
</script>
