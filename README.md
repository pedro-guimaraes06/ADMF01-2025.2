# ADMF01-2025.2

Projeto Laravel 7 + Vue 2 (Vuetify) com Docker

## Requisitos

- Docker
- Docker Compose

## Instalação

### 1. Subir os contêineres

```bash
docker-compose up -d --build
```

### 2. Instalar dependências do Backend

```bash
docker exec -it app-api composer install
docker exec -it app-api cp .env.example .env
docker exec -it app-api php artisan key:generate
docker exec -it app-api chmod -R 777 storage bootstrap/cache
```

### 3. Criar rota de teste

Edite `backend/routes/api.php` e adicione:

```php
Route::get('/test', function () {
    return response()->json(['message' => 'API funcionando!']);
});
```

## Acesso

- **Frontend**: http://localhost:8070
- **Backend API**: http://localhost:8080/api/test

## Estrutura

```
ADMF01-2025.2/
├── .docker/          # Configurações Docker
├── backend/          # Laravel 7
├── frontend/         # Vue 2 + Vuetify
└── docker-compose.yml
```

## Tecnologias

- **Backend**: Laravel 7 + PHP 7.4
- **Frontend**: Vue 2.6.11 + Vuetify 2.3.8
- **Container**: Docker + Docker Compose

## Desenvolvimento

### Backend

```bash
docker exec -it app-api bash
php artisan route:list
php artisan migrate
```

### Frontend

O frontend roda automaticamente com hot-reload em http://localhost:8070

## Comandos Úteis

```bash
# Ver logs dos containers
docker-compose logs -f

# Parar containers
docker-compose down

# Reconstruir containers
docker-compose up -d --build

# Acessar container do backend
docker exec -it app-api bash

# Acessar container do frontend
docker exec -it app-front sh
```
