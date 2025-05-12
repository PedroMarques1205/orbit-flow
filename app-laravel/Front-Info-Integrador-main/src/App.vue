<script setup lang="ts">
import { provide, shallowRef, onMounted } from 'vue'
import { RouterView } from 'vue-router'
import router from './router'

</script>

<template>
  <component>
    <RouterView />
  </component>
</template>