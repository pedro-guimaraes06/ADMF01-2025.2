# Backend - Laravel 7

API Backend do projeto ADMF01-2025.2

## Requisitos

- PHP 7.4
- Composer
- MySQL

## Instalação

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

## Estrutura

- `app/` - Código da aplicação
- `routes/` - Rotas da API
- `database/` - Migrations e seeds
- `config/` - Configurações
