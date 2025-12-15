# SAD Dengue - Backend API

Sistema de Apoio à Decisão para avaliação de risco de dengue usando o método AHP (Analytic Hierarchy Process).

## 📋 Stack Tecnológica

- **Framework**: Laravel 7.x
- **Linguagem**: PHP 7.2.5+
- **Banco de Dados**: SQLite
- **CORS**: Configurado para aceitar requisições do frontend
- **Logs**: Configurado para stderr (compatível com Render)

## 🎯 Funcionalidades

- Avaliação de risco de dengue usando método AHP
- Análise epidemiológica e estatísticas
- Sumarização de casos por região, tempo e sintomas
- Análises preditivas e correlações
- API REST completa

## 🚀 Deploy no Render (Free Web Service)

### Pré-requisitos

- Conta no [Render.com](https://render.com)
- Repositório Git com o código do projeto
- Frontend hospedado no Vercel (ou outro serviço)

### Passos para Deploy

#### 1. Criar novo Web Service no Render

1. Acesse o [Render Dashboard](https://dashboard.render.com)
2. Clique em **"New +"** → **"Web Service"**
3. Conecte seu repositório Git
4. Configure o serviço:
   - **Name**: `sad-dengue-api` (ou nome de sua preferência)
   - **Environment**: `Docker` ou `Native`
   - **Region**: Escolha a mais próxima
   - **Branch**: `main` (ou sua branch principal)
   - **Root Directory**: `backend`

#### 2. Configurar Build e Start Commands

**Se usar Native Environment (PHP):**
- **Build Command**: 
  ```bash
  composer install --no-dev --optimize-autoloader && php artisan config:clear && php artisan route:clear && php artisan view:clear
  ```
- **Start Command**:
  ```bash
  php -S 0.0.0.0:$PORT -t public
  ```

**Ou criar um `build.sh` (recomendado):**
```bash
#!/bin/bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

#### 3. Configurar Variáveis de Ambiente

No painel do Render, adicione as seguintes variáveis de ambiente:

```env
APP_NAME=SAD Dengue API
APP_ENV=production
APP_KEY=base64:SUA_CHAVE_AQUI
APP_DEBUG=false
APP_URL=https://seu-app.onrender.com

LOG_CHANNEL=stderr
LOG_LEVEL=info

DB_CONNECTION=sqlite
DB_DATABASE=/var/data/sad_dengue.sqlite

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

CORS_ALLOWED_ORIGINS=*
TELESCOPE_ENABLED=false
```

**⚠️ Importante**: 
- Para gerar `APP_KEY`, execute localmente: `php artisan key:generate --show`
- Copie a chave gerada e adicione no Render

#### 4. Configurar Persistent Disk (Banco de Dados)

1. No painel do seu Web Service, vá em **"Disks"**
2. Clique em **"Add Disk"**
3. Configure:
   - **Name**: `sad-dengue-data`
   - **Mount Path**: `/var/data`
   - **Size**: 1 GB (suficiente para o projeto)
4. Salve e aguarde a criação do disco

#### 5. Deploy e Health Check

1. O Render iniciará o deploy automaticamente
2. Aguarde a conclusão do build
3. Teste o health check: `https://seu-app.onrender.com/api/status`
4. Resposta esperada:
   ```json
   {
     "status": "ok",
     "service": "SAD Dengue API",
     "timestamp": "2025-12-15T10:30:00Z",
     "database": "connected",
     "environment": "production"
   }
   ```

#### 6. Executar Migrations

**Primeira vez (após deploy):**

Acesse o Shell do Render:
1. No dashboard do Web Service, clique em **"Shell"**
2. Execute:
   ```bash
   php artisan migrate --force
   ```

#### 7. Popular Banco de Dados (Opcional)

Se você tiver um dump dos dados de dengue:

1. Copie o arquivo SQLite para o persistent disk via Shell:
   ```bash
   cp /caminho/seu-arquivo.sqlite /var/data/sad_dengue.sqlite
   ```

Ou importe dados via script SQL.

### 🔧 Configuração do Frontend (Vercel)

No seu frontend Vue.js hospedado no Vercel, configure a URL da API:

```javascript
// No arquivo .env ou .env.production
VUE_APP_API_URL=https://seu-app.onrender.com/api
```

## 📡 Endpoints Disponíveis

### Health Check
- `GET /api/status` - Status do serviço

### Avaliação de Risco
- `POST /api/risco/avaliar` - Avaliar risco de um caso
- `GET /api/risco/{id}` - Buscar avaliação por ID
- `GET /api/risco` - Listar todas as avaliações
- `GET /api/risco/stats/estatisticas` - Estatísticas de risco

### Casos e Estatísticas
- `GET /api/casos/estatisticas` - Estatísticas gerais
- `GET /api/casos/uf` - Casos por UF
- `GET /api/casos/municipio` - Casos por município
- `GET /api/casos/semana` - Casos por semana epidemiológica
- `GET /api/casos/faixa-etaria` - Distribuição por faixa etária
- `GET /api/casos/top-municipios` - Top municípios
- `GET /api/casos/tendencia` - Tendência temporal
- `GET /api/casos/resumo-executivo` - Resumo executivo

### Sintomas
- `GET /api/sintomas/distribuicao` - Distribuição de sintomas
- `GET /api/sintomas/alarmes` - Distribuição de sinais de alarme
- `GET /api/sintomas/gravidade` - Distribuição de gravidade

### Análises
- `GET /api/analise/regressao` - Regressão temporal
- `GET /api/analise/previsao` - Previsão de casos futuros
- `GET /api/analise/correlacao/sintomas-gravidade` - Correlação sintomas x gravidade
- `GET /api/analise/correlacao/alarmes-gravidade` - Correlação alarmes x gravidade
- `GET /api/analise/dashboard` - Dados consolidados para dashboard

## 📝 Exemplo de Requisição

### POST /api/risco/avaliar

```json
{
  "febre": 1,
  "mialgia": 1,
  "cefaleia": 1,
  "exantema": 0,
  "vomito": 0,
  "nausea": 1,
  "dor_costas": 1,
  "conjuntvit": 0,
  "artralgia": 1,
  "dor_retro": 1,
  "alrm_plaq": 1,
  "alrm_vom": 0,
  "alrm_sang": 0,
  "alrm_abdom": 1,
  "grav_pulso": 0,
  "grav_hipot": 0,
  "idade": 35,
  "sexo": "F",
  "uf": "BA",
  "municipio": "Salvador",
  "semana_epidemiologica": 12,
  "casos_municipio": 150,
  "populacao_municipio": 2900000,
  "tendencia_temporal": 1.2
}
```

### Resposta

```json
{
  "success": true,
  "data": {
    "id": 123,
    "score_final": 0.6234,
    "nivel_risco": "Médio",
    "justificativa": "Paciente apresenta sintomas clássicos...",
    "detalhes": {
      "score_epidemiologia": 0.4521,
      "score_gravidade": 0.3245,
      "score_sintomas": 0.6123,
      "score_sociodemografico": 0.2341
    }
  }
}
```

## 🛠️ Comandos Úteis (Shell do Render)

```bash
# Limpar caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Verificar conexão com banco
php artisan tinker
>>> \DB::connection()->getPdo();

# Ver logs
tail -f storage/logs/laravel.log

# Executar migrations
php artisan migrate --force

# Ver tabelas do banco
php artisan tinker
>>> \Schema::getTableListing();
```

## 🐛 Troubleshooting

### Erro 500 após deploy
- Verifique se `APP_KEY` está configurada
- Execute `php artisan config:clear` no Shell
- Verifique os logs em Render → Logs

### Banco de dados não conecta
- Confirme que o Persistent Disk está montado em `/var/data`
- Verifique `DB_DATABASE=/var/data/sad_dengue.sqlite`
- Execute migrations: `php artisan migrate --force`

### CORS não funciona
- Confirme que `CORS_ALLOWED_ORIGINS=*` está configurado
- Ou especifique o domínio do Vercel: `https://seu-frontend.vercel.app`

### Render Free tier sleep
- O serviço gratuito "dorme" após 15 minutos de inatividade
- Primeira requisição pode demorar ~30 segundos
- Considere usar um serviço de "ping" para manter ativo

## 📄 Licença

Este projeto é para fins acadêmicos e de demonstração.

## 👥 Contato

Para dúvidas ou suporte, entre em contato com a equipe de desenvolvimento.

---

**Desenvolvido com ❤️ para o Sistema de Apoio à Decisão de Dengue**
