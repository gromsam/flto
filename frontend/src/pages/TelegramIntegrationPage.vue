<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { useTelegramStore } from '../stores/telegramStore'

const props = defineProps<{ shopId: string }>()

const store = useTelegramStore()

const botToken = ref('')
const chatId = ref('')
const enabled = ref(false)

async function loadStatus() {
  await store.fetchStatus(props.shopId)
  if (store.status?.connected) {
    chatId.value = store.status.chat_id ?? ''
    enabled.value = store.status.enabled ?? false
  }
}

onMounted(() => loadStatus())
watch(() => props.shopId, () => loadStatus())

async function save() {
  store.clearError()
  await store.connect(props.shopId, {
    botToken: botToken.value,
    chatId: chatId.value,
    enabled: enabled.value,
  })
}
</script>

<template>
  <div class="page">
    <h1>Telegram — магазин {{ shopId }}</h1>

    <form @submit.prevent="save" class="form">
      <div class="field">
        <label for="botToken">Bot Token</label>
        <input
          id="botToken"
          v-model="botToken"
          type="password"
          placeholder="Токен бота"
          autocomplete="off"
        />
      </div>
      <div class="field">
        <label for="chatId">Chat ID</label>
        <input
          id="chatId"
          v-model="chatId"
          type="text"
          placeholder="ID чата"
        />
      </div>
      <div class="field row">
        <input
          id="enabled"
          v-model="enabled"
          type="checkbox"
        />
        <label for="enabled">Включить уведомления</label>
      </div>
      <button type="submit" :disabled="store.saving">
        {{ store.saving ? 'Сохранение…' : 'Сохранить' }}
      </button>
    </form>

    <section class="status-block">
      <h2>Статус</h2>
      <p v-if="store.loading">Загрузка…</p>
      <p v-else-if="store.error" class="error">{{ store.error }}</p>
      <template v-else-if="store.status">
        <p v-if="store.status.connected">
          Подключено. Chat ID: {{ store.status.chat_id }}.
          Уведомления: {{ store.status.enabled ? 'вкл' : 'выкл' }}.
        </p>
        <p v-else>Не подключено.</p>
      </template>
      <p v-else>Нет данных.</p>
    </section>
  </div>
</template>

<style scoped>
.page {
  max-width: 420px;
  margin: 2rem auto;
  padding: 0 1rem;
}
h1 {
  font-size: 1.25rem;
  margin-bottom: 1.5rem;
}
.form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  margin-bottom: 2rem;
}
.field {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}
.field.row {
  flex-direction: row;
  align-items: center;
  gap: 0.5rem;
}
.field label {
  font-size: 0.875rem;
  font-weight: 500;
}
.field input[type="text"],
.field input[type="password"] {
  padding: 0.5rem 0.75rem;
  border: 1px solid #ccc;
  border-radius: 6px;
}
.field input[type="checkbox"] {
  width: 1.25rem;
  height: 1.25rem;
}
button {
  padding: 0.6rem 1rem;
  background: #0d6efd;
  color: #fff;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 1rem;
}
button:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}
.status-block {
  padding: 1rem;
  background: #f5f5f5;
  border-radius: 8px;
}
.status-block h2 {
  font-size: 1rem;
  margin: 0 0 0.5rem 0;
}
.status-block p {
  margin: 0;
  font-size: 0.875rem;
}
.error {
  color: #c00;
}
</style>
