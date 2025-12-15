# ✅ Checklist de Deploy - Render

## Pré-Deploy

- [x] Migrations organizadas e testadas
- [x] CORS configurado para aceitar requisições do frontend
- [x] Arquivo `.env.render` criado com configurações de produção
- [x] Scripts `build.sh` e `start.sh` criados
- [x] `Procfile` configurado
- [x] `render.yaml` criado para deploy automático
- [x] Endpoint de health check (`/api/status`) implementado
- [x] CSRF desabilitado para rotas da API
- [x] Logs configurados para stderr
- [x] Vendor no `.gitignore`
- [x] Documentação de deploy criada

## Configuração no Render

### 1. Criar Web Service
- [ ] Acessar [Render Dashboard](https://dashboard.render.com)
- [ ] Clicar em "New +" → "Web Service"
- [ ] Conectar repositório Git
- [ ] Selecionar branch `main`
- [ ] Definir Root Directory como `backend`

### 2. Configurar Build
- [ ] **Environment**: Native (PHP) ou Docker
- [ ] **Build Command**: `bash build.sh`
- [ ] **Start Command**: `bash start.sh`
- [ ] **Plan**: Free

### 3. Variáveis de Ambiente

Adicionar as seguintes variáveis no painel do Render:

#### Essenciais
- [ ] `APP_NAME` = `SAD Dengue API`
- [ ] `APP_ENV` = `production`
- [ ] `APP_KEY` = (gerar com `php artisan key:generate --show`)
- [ ] `APP_DEBUG` = `false`
- [ ] `APP_URL` = `https://seu-app.onrender.com`

#### Banco de Dados
- [ ] `DB_CONNECTION` = `sqlite`
- [ ] `DB_DATABASE` = `/var/data/sad_dengue.sqlite`

#### Logs
- [ ] `LOG_CHANNEL` = `stderr`
- [ ] `LOG_LEVEL` = `info`

#### Outros
- [ ] `BROADCAST_DRIVER` = `log`
- [ ] `CACHE_DRIVER` = `file`
- [ ] `QUEUE_CONNECTION` = `sync`
- [ ] `SESSION_DRIVER` = `file`
- [ ] `SESSION_LIFETIME` = `120`
- [ ] `CORS_ALLOWED_ORIGINS` = `*` (ou URL específica do frontend)
- [ ] `TELESCOPE_ENABLED` = `false`

### 4. Persistent Disk
- [ ] Clicar em "Disks" no painel do Web Service
- [ ] Adicionar novo disco:
  - **Name**: `sad-dengue-data`
  - **Mount Path**: `/var/data`
  - **Size**: 1 GB
- [ ] Salvar configuração

### 5. Deploy Inicial
- [ ] Aguardar build e deploy automático
- [ ] Verificar logs no painel do Render
- [ ] Testar health check: `https://seu-app.onrender.com/api/status`

### 6. Migrations e Dados
- [ ] Acessar Shell no Render
- [ ] Executar: `php artisan migrate --force`
- [ ] (Opcional) Importar dados existentes para `/var/data/sad_dengue.sqlite`

## Pós-Deploy

### Testes
- [ ] Endpoint de health check funcionando
- [ ] Banco de dados conectado (verificar resposta do `/api/status`)
- [ ] Testar endpoint `POST /api/risco/avaliar` com dados de exemplo
- [ ] Testar endpoints de estatísticas (`GET /api/casos/estatisticas`)
- [ ] Verificar CORS - requisições do frontend funcionando

### Frontend (Vercel)
- [ ] Atualizar variável `VUE_APP_API_URL` no Vercel
- [ ] Valor: `https://seu-app.onrender.com/api`
- [ ] Fazer redeploy do frontend
- [ ] Testar integração completa

### Monitoramento
- [ ] Verificar logs no painel do Render
- [ ] Confirmar que não há erros críticos
- [ ] Testar algumas requisições manualmente
- [ ] Documentar URL da API para equipe

## Troubleshooting

### Se o deploy falhar:
1. [ ] Verificar logs de build no Render
2. [ ] Confirmar que `composer.json` está válido
3. [ ] Verificar se PHP 7.2.5+ está disponível
4. [ ] Checar se `build.sh` tem permissões corretas

### Se a API não responder:
1. [ ] Verificar se `APP_KEY` está configurada
2. [ ] Executar no Shell: `php artisan config:clear`
3. [ ] Verificar logs: `tail -f storage/logs/laravel.log`
4. [ ] Testar conexão com banco no Shell:
   ```bash
   php artisan tinker
   >>> \DB::connection()->getPdo();
   ```

### Se CORS não funcionar:
1. [ ] Verificar `CORS_ALLOWED_ORIGINS` no Render
2. [ ] Confirmar que middleware CORS está ativo
3. [ ] Testar com `CORS_ALLOWED_ORIGINS=*` primeiro
4. [ ] Depois especificar domínio do Vercel

### Se banco não conectar:
1. [ ] Confirmar que Persistent Disk está montado
2. [ ] Verificar path: `/var/data/sad_dengue.sqlite`
3. [ ] Executar migrations: `php artisan migrate --force`
4. [ ] Verificar permissões do arquivo SQLite

## 🎯 URLs de Produção

Após deploy concluído, documentar:

- **API Backend**: https://_____.onrender.com
- **Health Check**: https://_____.onrender.com/api/status
- **Frontend**: https://_____.vercel.app
- **Repositório**: https://github.com/___/___

## 📝 Notas Adicionais

- Render Free tier pode "adormecer" após 15 minutos de inatividade
- Primeira requisição após sleep pode demorar ~30 segundos
- Para manter ativo, considere usar serviço de ping externo
- Banco SQLite persistente está em `/var/data` (não será perdido no redeploy)

---

**Status do Deploy**: ⏳ Pendente | ✅ Concluído | ❌ Com problemas

**Data do último deploy**: _______________

**Responsável**: _______________
