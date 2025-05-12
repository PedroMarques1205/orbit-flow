import './assets/css/main.css'
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'
import Toast, { type PluginOptions } from 'vue-toastification'
import 'vue-toastification/dist/index.css'

const app = createApp(App)

const options: PluginOptions = {
  timeout: 5000,
  closeOnClick: true,
  pauseOnFocusLoss: true,
  pauseOnHover: true,
  draggable: true,
  draggablePercent: 0.6,
  showCloseButtonOnHover: true,
  closeButton: 'button',
  icon: false,
  rtl: false,
  position: 'bottom-left' as any
}

app.use(createPinia())
app.use(router)
app.use(Toast, options)

app.mount('#app')
