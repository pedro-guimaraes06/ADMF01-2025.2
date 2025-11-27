# Guia de Instalação - ADMF01-2025.2

## 📋 Pré-requisitos

- Docker Desktop instalado
- Docker Compose instalado
- Git (opcional)

## 🚀 Passo a Passo

### 1. Navegar até o diretório do projeto

```bash
cd c:/projetos/ADMF01-2025.2
```

### 2. Construir e iniciar os containers

```bash
docker-compose up -d --build
```

Este comando irá:
- Construir a imagem do PHP 7.4 com Apache
- Construir a imagem do Node.js 14 para o Vue
- Criar a rede Docker
- Iniciar todos os containers

**Aguarde**: O processo inicial pode levar 5-10 minutos para baixar e construir as imagens.

### 3. Verificar se os containers estão rodando

```bash
docker-compose ps
```

Você deve ver:
- `app-api` (status: Up)
- `app-front` (status: Up)

### 4. Instalar dependências do Laravel

```bash
# Entrar no container do backend
docker exec -it app-api bash

# Dentro do container, executar:
composer install
cp .env.example .env
php artisan key:generate
chmod -R 777 storage bootstrap/cache

# Sair do container
exit
```

### 5. Testar o Backend

Abra o navegador e acesse:
- **API de teste**: http://localhost:8080/api/test

Você deve ver:
```json
{
  "message": "API funcionando!"
}
```

### 6. Testar o Frontend

Abra o navegador e acesse:
- **Frontend**: http://localhost:8070

O Vue deve carregar com a página inicial do Vuetify.

## 🔧 Comandos Úteis

### Backend (Laravel)

```bash
# Acessar o container
docker exec -it app-api bash

# Listar rotas
php artisan route:list

# Criar migration
php artisan make:migration create_nome_table

# Criar model
php artisan make:model NomeModel

# Criar controller
php artisan make:controller NomeController

# Limpar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### Frontend (Vue)

```bash
# Acessar o container
docker exec -it app-front sh

# Instalar novo pacote
yarn add nome-do-pacote

# Verificar logs
docker logs app-front -f
```

### Docker

```bash
# Ver logs de todos os containers
docker-compose logs -f

# Reiniciar um container específico
docker-compose restart app-api
docker-compose restart app-front

# Parar todos os containers
docker-compose down

# Parar e remover volumes
docker-compose down -v

# Reconstruir containers
docker-compose up -d --build --force-recreate
```

## 🐛 Solução de Problemas

### Backend não carrega / Erro 500

```bash
# Verificar logs do container
docker logs app-api

# Verificar permissões
docker exec -it app-api chmod -R 777 storage bootstrap/cache

# Limpar caches
docker exec -it app-api php artisan cache:clear
docker exec -it app-api php artisan config:clear
```

### Frontend não carrega

```bash
# Verificar logs
docker logs app-front

# Reconstruir node_modules
docker-compose down
docker-compose up -d --build
```

### Porta já em uso

Se as portas 8080 ou 8070 já estiverem em uso, edite o `docker-compose.yml`:

```yaml
ports:
  - "8081:80"  # Mude 8080 para 8081 (backend)
  - "8071:8070"  # Mude 8070 para 8071 (frontend)
```

### Container não inicia

```bash
# Ver status e erros
docker-compose ps
docker-compose logs app-api
docker-compose logs app-front

# Reiniciar do zero
docker-compose down
docker system prune -a
docker-compose up -d --build
```

## 📁 Estrutura de Pastas

```
ADMF01-2025.2/
├── .docker/
│   ├── php/           # Dockerfile do PHP + Apache
│   ├── vue/           # Dockerfile do Node.js + Vue
│   └── sql/           # Dockerfile do SQL Server (opcional)
├── backend/           # Código Laravel 7
│   ├── app/
│   ├── config/
│   ├── database/
│   ├── public/
│   ├── routes/
│   └── storage/
├── frontend/          # Código Vue 2 + Vuetify
│   ├── public/
│   ├── src/
│   │   ├── api/
│   │   ├── components/
│   │   ├── modules/
│   │   ├── plugins/
│   │   ├── router/
│   │   └── App.vue
│   └── package.json
└── docker-compose.yml
```

## ✅ Checklist de Verificação

- [ ] Docker Desktop está rodando
- [ ] Executou `docker-compose up -d --build`
- [ ] Containers `app-api` e `app-front` estão com status "Up"
- [ ] Executou `composer install` no container do backend
- [ ] Copiou `.env.example` para `.env`
- [ ] Executou `php artisan key:generate`
- [ ] Ajustou permissões com `chmod -R 777 storage bootstrap/cache`
- [ ] Backend responde em http://localhost:8080/api/test
- [ ] Frontend carrega em http://localhost:8070

## 🎯 Próximos Passos

Agora que o ambiente está configurado, você pode:

1. **Criar Models, Controllers e Services** no backend
2. **Criar componentes Vue** no frontend
3. **Configurar rotas** em `backend/routes/api.php`
4. **Conectar ao banco de dados** (edite `.env` do backend)
5. **Desenvolver sua aplicação**!

---

**Problemas?** Verifique os logs com `docker-compose logs -f`
