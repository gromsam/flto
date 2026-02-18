import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import type { TelegramStatus } from '../api/telegram'
import { getTelegramStatus, connectTelegram } from '../api/telegram'

export const useTelegramStore = defineStore('telegram', () => {
  const status = ref<TelegramStatus | null>(null)
  const loading = ref(false)
  const saving = ref(false)
  const error = ref<string | null>(null)

  const isConnected = computed(() => status.value?.connected ?? false)

  async function fetchStatus(shopId: string) {
    loading.value = true
    error.value = null
    try {
      const { data } = await getTelegramStatus(shopId)
      status.value = data
    } catch (e: unknown) {
      error.value = e instanceof Error ? e.message : 'Failed to load status'
      status.value = null
    } finally {
      loading.value = false
    }
  }

  async function connect(shopId: string, payload: { botToken: string; chatId: string; enabled: boolean }) {
    saving.value = true
    error.value = null
    try {
      await connectTelegram(shopId, payload)
      await fetchStatus(shopId)
    } catch (e: unknown) {
      error.value = e instanceof Error ? e.message : 'Failed to save'
      throw e
    } finally {
      saving.value = false
    }
  }

  function clearError() {
    error.value = null
  }

  return {
    status,
    loading,
    saving,
    error,
    isConnected,
    fetchStatus,
    connect,
    clearError,
  }
})
