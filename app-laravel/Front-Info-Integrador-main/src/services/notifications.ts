// notificationService.ts
import { useToast } from 'vue-toastification'

const toast = useToast()

export function notifySuccess(message: string) {
  toast.success(message, {
    toastClassName: 'toast-success'
  })
}

export function notifyError(message: string) {
  toast.error(message, {
    toastClassName: 'toast-error'
  })
}

export function notifyWarning(message: string) {
  toast.warning(message, {
    toastClassName: 'toast-warning'
  })
}