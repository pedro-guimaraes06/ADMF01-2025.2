# Backend - SAD Dengue API

API REST para Sistema de Apoio à Decisão de Avaliação de Risco de Dengue usando Método AHP.

## 📋 Stack Tecnológica

- **Framework**: Laravel 7.x
- **PHP**: 7.2.5+
- **Banco de Dados**: SQLite
- **CORS**: Configurado
- **Método**: AHP (Analytic Hierarchy Process)

## 🚀 Instalação Local

### Requisitos

- PHP 7.2.5 ou superior
- Composer
- SQLite3 (geralmente incluído no PHP)

### Passos

```bash
# 1. Instalar dependências
composer install

# 2. Configurar ambiente
cp .env.example .env
php artisan key:generate

# 3. Criar banco de dados SQLite
touch database/dengue_2025.sqlite

# 4. Executar migrations
php artisan migrate

# 5. Iniciar servidor de desenvolvimento
php artisan serve
# ou
php -S localhost:8000 -t public
```

A API estará disponível em `http://localhost:8000/api`

## 🌐 Deploy para Produção (Render)

Consulte o arquivo [DEPLOY-RENDER.md](DEPLOY-RENDER.md) para instruções detalhadas de deploy no Render.

### Quick Start para Deploy

1. **Conecte o repositório no Render**
2. **Configure as variáveis de ambiente** (veja `.env.render`)
3. **Adicione Persistent Disk** em `/var/data`
4. **Deploy automático** - O Render executará `build.sh` e `start.sh`

## 📡 Endpoints da API

### Health Check
- `GET /api/status` - Verificar status do serviço

### Avaliação de Risco (AHP)
- `POST /api/risco/avaliar` - Avaliar risco de dengue
- `GET /api/risco/{id}` - Buscar avaliação específica
- `GET /api/risco` - Listar avaliações
- `GET /api/risco/stats/estatisticas` - Estatísticas

### Casos e Estatísticas
- `GET /api/casos/estatisticas` - Estatísticas gerais
- `GET /api/casos/uf` - Por estado
- `GET /api/casos/municipio` - Por município
- `GET /api/casos/semana` - Por semana epidemiológica
- `GET /api/casos/faixa-etaria` - Por idade
- `GET /api/casos/tendencia` - Tendência temporal

### Análises
- `GET /api/analise/dashboard` - Dashboard completo
- `GET /api/analise/previsao` - Previsão de casos
- `GET /api/analise/correlacao/sintomas-gravidade` - Correlações

## 📂 Estrutura do Projeto

```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/Api/  # Controllers da API
│   │   ├── Middleware/       # CORS, CSRF, etc
│   │   └── Requests/         # Validação de requisições
│   ├── Models/               # Eloquent Models
│   ├── Services/             # Lógica de negócio
│   │   ├── AHP/             # Calculadora AHP
│   │   ├── Normalizador/    # Normalização de dados
│   │   └── Classificador/   # Classificação de risco
│   └── ...
├── config/
│   ├── ahp.php              # Configuração método AHP
│   ├── cors.php             # Configuração CORS
│   ├── database.php         # Configuração BD
│   └── logging.php          # Configuração logs
├── database/
│   ├── migrations/          # Migrations do banco
│   └── dengue_2025.sqlite   # Banco SQLite (local)
├── routes/
│   └── api.php              # Rotas da API
├── build.sh                 # Script de build (Render)
├── start.sh                 # Script de inicialização
├── render.yaml              # Configuração Render
└── .env.render              # Variáveis para produção
```

## 🧪 Testes

```bash
# Executar testes
php artisan test

# Ou com PHPUnit
vendor/bin/phpunit
```

## 🛠️ Comandos Úteis

```bash
# Limpar caches
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear

# Verificar rotas
php artisan route:list

# Acessar console interativo
php artisan tinker

# Verificar conexão com banco
php artisan tinker
>>> \DB::connection()->getPdo();

# Ver estrutura do banco
php artisan tinker
>>> \Schema::getTableListing();
```

## 📝 Configuração CORS

O CORS já está configurado para aceitar requisições de qualquer origem (`*`).

Para produção, edite [config/cors.php](config/cors.php):

```php
'allowed_origins' => ['https://seu-frontend.vercel.app'],
```

## 🔒 Segurança

- **CSRF**: Desabilitado para rotas `/api/*`
- **CORS**: Configurado para aceitar requisições do frontend
- **APP_DEBUG**: Deve ser `false` em produção
- **APP_ENV**: Deve ser `production` em produção

## 📄 Licença

Projeto acadêmico para demonstração do método AHP aplicado à dengue.

---

**Desenvolvido para ADMF01-2025.2**
