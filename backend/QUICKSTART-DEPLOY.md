# 🚀 Quick Start - Deploy Render

## Para quem tem pressa

### 1️⃣ Gerar APP_KEY (Local)
```bash
cd backend
php artisan key:generate --show
# Copie a chave gerada: base64:xxxxxxxxxxxx
```

### 2️⃣ No Render Dashboard

1. **New +** → **Web Service**
2. Conecte seu repositório Git
3. **Settings**:
   - Root Directory: `backend`
   - Build Command: `bash build.sh`
   - Start Command: `bash start.sh`
   - Environment: Native

### 3️⃣ Environment Variables (Cole todas de uma vez)

```env
APP_NAME=SAD Dengue API
APP_ENV=production
APP_KEY=base64:SUA_CHAVE_AQUI
APP_DEBUG=false
APP_URL=https://your-app.onrender.com
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

⚠️ **Substitua**:
- `SUA_CHAVE_AQUI` → pela chave gerada no passo 1
- `your-app` → pelo nome do seu app no Render

### 4️⃣ Adicionar Persistent Disk

No painel do seu Web Service:
- **Disks** → **Add Disk**
- Name: `sad-dengue-data`
- Mount Path: `/var/data`
- Size: `1 GB`
- **Create**

### 5️⃣ Deploy & Migrate

Deploy automático iniciará. Quando terminar:

1. Acesse **Shell** no painel
2. Execute:
```bash
php artisan migrate --force
```

### 6️⃣ Testar

Acesse: `https://seu-app.onrender.com/api/status`

Resposta esperada:
```json
{
  "status": "ok",
  "service": "SAD Dengue API",
  "database": "connected",
  "environment": "production"
}
```

### 7️⃣ Configurar Frontend (Vercel)

No Vercel, adicione variável:
```
VUE_APP_API_URL=https://seu-app.onrender.com/api
```

Redeploy o frontend.

## ✅ Pronto!

Seu backend está no ar! 🎉

---

**Problemas?** Consulte [DEPLOY-CHECKLIST.md](DEPLOY-CHECKLIST.md) ou [DEPLOY-RENDER.md](DEPLOY-RENDER.md)
