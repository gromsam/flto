import axios from 'axios'

const api = axios.create({
  baseURL: '/api',
  headers: { 'Content-Type': 'application/json' },
})

export interface TelegramStatus {
  connected: boolean
  chat_id?: string
  enabled?: boolean
}

export interface ConnectPayload {
  botToken: string
  chatId: string
  enabled: boolean
}

export function getTelegramStatus(shopId: string): Promise<{ data: TelegramStatus }> {
  return api.get<TelegramStatus>(`/shops/${shopId}/telegram/status`)
}

export function connectTelegram(shopId: string, payload: ConnectPayload): Promise<{ data: { success: boolean } }> {
  return api.post(`/shops/${shopId}/telegram/connect`, payload)
}
