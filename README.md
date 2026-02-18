# fltg — Telegram Integration API

## Docker (рекомендуемый способ)

### Необходимо собрать Docker, при пересборки используйте флаг --no-cache
```bash
docker compose build --no-cache
```

### В дальнейшем после сборки только запускаем

```bash
docker compose up -d
```



- **PostgreSQL** — порт 5432, БД `growth`, пользователь/пароль `growth`&
- **Backend** — при старте контейнера автоматически выполняются миграции и сидер (1 магазин, 10 заказов). API: http://localhost:8000
- **Frontend** — http://localhost:5173 (страница настроек Telegram: `/shops/1/growth/telegram`).

### При ошибки подключения к контейнеру базы например IDE PhpStorm, используйте host: localhost

После запуска откройте в браузере: http://localhost:5173/shops/1/growth/telegram
Откроется форма добавления нотификации в ТГ

## Локальный запуск без Docker

### Backend

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
```

В `.env` укажите подключение к PostgreSQL со своими параметрами.

### Выполните миграции, посев и запустите приложение

```bash
php artisan migrate --force
php artisan db:seed --force
php artisan serve
```

### Frontend

```bash
cd frontend
npm install
npm run dev
```

Прокси `/api` по умолчанию идёт на `http://localhost:8000` (см. `vite.config.ts`, при необходимости задайте `VITE_API_ORIGIN` в `.env`).

## Тесты

```bash
  cd backend && php artisan test
```

Используется SQLite in-memory (`phpunit.xml`). Сценарии: заказ создан → telegram отправлен → лог SENT; повторная отправка → без дубликата; ошибка Telegram → лог FAILED, заказ создан.

В итоге должны пройти все тесты, сейчас пока только:
✓ order created telegram sent log sent                                                                                                                                                    0.24s  
✓ duplicate send no duplicate                                                                                                                                                             0.01s  
✓ telegram error log failed order created

## API

Базовый путь: `/api` (backend на http://localhost:8000 → http://localhost:8000/api/...).

- `POST /api/shops/{shopId}/telegram/connect` — подключить Telegram (body: `{"botToken","chatId","enabled"}`).
- `GET /api/shops/{shopId}/telegram/status` — статус интеграции.
- `POST /api/shops/{shopId}/orders` — создать заказ (body: `{"orderId","amount"}`), отправка в Telegram; в ответе — `order` и `telegram_status` (sent/failed/skipped).

---
