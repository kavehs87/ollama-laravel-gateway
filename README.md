

# 🧠 Laravel Ollama API Gateway

This project provides a secure Laravel-based REST API for exposing an **on-premise Ollama LLM server** to customers. It supports:

- ✅ Prompt-based text generation
- 💬 Conversational chat API
- 🔍 Embedding endpoint
- ⚙️ Model control (pull, delete)
- 💵 Per-model token-based billing
- 🔐 User authentication (API tokens via Laravel Sanctum)

---

## 🚀 Features

| Feature             | Description                                                                 |
|---------------------|-----------------------------------------------------------------------------|
| `/generate`         | Generate text from a prompt                                                 |
| `/chat`             | Chat-style interaction using message history                                |
| `/embedding`        | Generate vector embeddings                                                  |
| `/models/pull`      | Pull a model from Ollama's registry                                         |
| `/models/{model}`   | Delete a model from local Ollama instance                                   |
| `/usage`            | Get current month's usage (tokens and cost)                                 |
| `/usage/history`    | View daily token usage and charges                                          |
| ✅ Token Tracking   | Logs prompt + output tokens, cost per request                               |
| 📊 Per-Model Pricing| Each model has its own cost per 1K tokens (`model_prices` table)            |
| 🔐 API Auth         | Laravel Sanctum token-based authentication                                  |

---

## 🏗️ Setup Instructions

### 1. Clone & Install

```bash
git clone https://your-repo-url/laravel-ollama-api.git
cd laravel-ollama-api
composer install
cp .env.example .env
php artisan key:generate
```

### 2. Configure `.env`

Update:

```env
DB_CONNECTION=mysql
DB_DATABASE=ollama_api
DB_USERNAME=root
DB_PASSWORD=secret

# Ollama endpoint
OLLAMA_BASE=http://localhost:11434
```

### 3. Migrate Database

```bash
php artisan migrate
php artisan db:seed --class=ModelPriceSeeder
```

### 4. Create User & API Token

```bash
php artisan tinker

$user = \App\Models\User::factory()->create(['email' => 'test@example.com'])
$token = $user->createToken('api-token')->plainTextToken
echo $token
```

---

## 🔌 API Endpoints

### Authentication
All routes require a Bearer token (`Authorization: Bearer YOUR_API_TOKEN`).

### Core Inference

| Method | URL         | Description                |
|--------|-------------|----------------------------|
| POST   | `/generate` | Generate from a prompt     |
| POST   | `/chat`     | Chat message interaction   |
| POST   | `/embedding`| Get text embedding         |

### Token Billing

| Method | URL              | Description                    |
|--------|------------------|--------------------------------|
| GET    | `/usage`         | This month’s usage summary     |
| GET    | `/usage/history` | Daily token usage log          |

### Model Control (Admin)

| Method | URL                    | Description              |
|--------|------------------------|--------------------------|
| POST   | `/models/pull`         | Pull a model             |
| DELETE | `/models/{model}`      | Delete a model           |

---

## 🧮 Token Billing Model

- Token usage is calculated using prompt + output
- Cost is derived from the `model_prices` table
- Example pricing: `llama3 → $0.002 / 1K tokens`, `mistral → $0.0015 / 1K tokens`

---

## 🧰 Developer Notes

- Responses include `usage.prompt_tokens`, `completion_tokens`, and `total_tokens`
- Middleware auto-logs and bills each LLM request
- Usage is stored in the `token_usages` table
- Models and pricing can be updated via database or admin controller

---

## 🔒 Security Tips

- Protect `/models/pull` and `/models/delete` using roles/permissions if needed
- Use rate limiting and logging in production
- Consider quota limits per user/month

---

## 📜 License

MIT – Use it for personal, commercial, or internal purposes.

---

## 🧑‍💻 Author

Kaveh Sarkhanlou