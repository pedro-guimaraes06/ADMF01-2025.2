# 📚 Índice da Documentação - Deploy Render

## 🚀 Começando

Para deploy rápido (5-10 minutos):
- **[QUICKSTART-DEPLOY.md](QUICKSTART-DEPLOY.md)** - Guia rápido de deploy

Para entender o que foi feito:
- **[RESUMO-ALTERACOES.md](RESUMO-ALTERACOES.md)** - Resumo de todas as alterações

## 📖 Documentação Completa

### Deploy no Render
1. **[DEPLOY-RENDER.md](DEPLOY-RENDER.md)** - Guia completo e detalhado
   - Pré-requisitos
   - Passo a passo completo
   - Configuração de variáveis
   - Persistent Disk
   - Troubleshooting

2. **[DEPLOY-CHECKLIST.md](DEPLOY-CHECKLIST.md)** - Checklist interativo
   - Lista de verificação completa
   - Organizado por etapas
   - Pode ser marcado conforme progride

### Operação e Manutenção
3. **[COMANDOS-UTEIS.md](COMANDOS-UTEIS.md)** - Comandos para Shell do Render
   - Comandos Laravel
   - Verificação de banco de dados
   - Troubleshooting
   - Backup

4. **[TESTES-API.md](TESTES-API.md)** - Exemplos de testes
   - Exemplos de requisições
   - Casos de teste (baixo, médio, alto risco)
   - Respostas esperadas
   - Testes com JavaScript/Postman

### Código e Configuração
5. **[README.md](README.md)** - README principal do backend
   - Instalação local
   - Stack tecnológica
   - Estrutura do projeto
   - Endpoints da API

## 📁 Arquivos de Configuração

### Deploy
- **`build.sh`** - Script de build do Render
- **`start.sh`** - Script de inicialização
- **`Procfile`** - Configuração de processo
- **`render.yaml`** - Blueprint do Render (deploy automático)

### Ambiente
- **`.env.example`** - Exemplo de variáveis (desenvolvimento)
- **`.env.render`** - Template de variáveis (produção)

### Banco de Dados
- **`database/migrations/`** - Migrations do banco
  - `2025_11_29_000001_create_avaliacoes_risco_table.php` - Tabela principal
  - `2025_11_29_000002_create_ahp_logs_table.php` - Logs AHP

### Configurações Laravel
- **`config/cors.php`** - Configuração CORS
- **`config/database.php`** - Configuração banco de dados
- **`config/logging.php`** - Configuração de logs
- **`config/ahp.php`** - Configuração método AHP

## 🔍 Navegação por Cenário

### "Preciso fazer deploy AGORA"
1. [QUICKSTART-DEPLOY.md](QUICKSTART-DEPLOY.md)
2. [DEPLOY-CHECKLIST.md](DEPLOY-CHECKLIST.md)

### "Quero entender todo o processo"
1. [RESUMO-ALTERACOES.md](RESUMO-ALTERACOES.md)
2. [DEPLOY-RENDER.md](DEPLOY-RENDER.md)
3. [README.md](README.md)

### "Deu erro, como resolver?"
1. [COMANDOS-UTEIS.md](COMANDOS-UTEIS.md)
2. [DEPLOY-RENDER.md](DEPLOY-RENDER.md#troubleshooting)
3. [DEPLOY-CHECKLIST.md](DEPLOY-CHECKLIST.md#troubleshooting)

### "Como testar se está funcionando?"
1. [TESTES-API.md](TESTES-API.md)
2. Acesse: `https://seu-app.onrender.com/api/status`

### "Preciso fazer manutenção"
1. [COMANDOS-UTEIS.md](COMANDOS-UTEIS.md)
2. Acesse Shell no Render Dashboard

## 📊 Estrutura de Arquivos de Documentação

```
backend/
├── README.md                    # README principal
├── DEPLOY-RENDER.md            # Guia completo de deploy
├── DEPLOY-CHECKLIST.md         # Checklist interativo
├── QUICKSTART-DEPLOY.md        # Deploy rápido
├── COMANDOS-UTEIS.md           # Comandos Shell
├── TESTES-API.md               # Exemplos de testes
├── RESUMO-ALTERACOES.md        # Resumo das alterações
├── INDICE-DOCUMENTACAO.md      # Este arquivo
├── build.sh                    # Script de build
├── start.sh                    # Script de start
├── Procfile                    # Config Render
├── render.yaml                 # Blueprint Render
├── .env.example                # Env desenvolvimento
└── .env.render                 # Env produção
```

## 🎯 Fluxo de Deploy Recomendado

```
1. Preparação Local
   ↓
   [QUICKSTART-DEPLOY.md - Passo 1]
   Gerar APP_KEY localmente

2. Configuração Render
   ↓
   [QUICKSTART-DEPLOY.md - Passos 2-4]
   Criar Web Service + Variáveis + Disk

3. Deploy
   ↓
   [DEPLOY-CHECKLIST.md]
   Aguardar build automático

4. Pós-Deploy
   ↓
   [COMANDOS-UTEIS.md]
   Executar migrations no Shell

5. Testes
   ↓
   [TESTES-API.md]
   Testar endpoints

6. Integração
   ↓
   [DEPLOY-RENDER.md - Seção Frontend]
   Configurar frontend no Vercel
```

## 📞 Suporte

### Em caso de dúvidas:

1. **Consulte primeiro**: [DEPLOY-RENDER.md](DEPLOY-RENDER.md#troubleshooting)
2. **Comandos úteis**: [COMANDOS-UTEIS.md](COMANDOS-UTEIS.md)
3. **Verifique**: Logs no Render Dashboard
4. **Teste**: Endpoint de health check

### Problemas comuns e soluções:

| Problema | Solução | Arquivo |
|----------|---------|---------|
| Erro 500 | Verificar APP_KEY e limpar caches | [COMANDOS-UTEIS.md](COMANDOS-UTEIS.md#erro-500) |
| CORS | Verificar configuração CORS | [DEPLOY-RENDER.md](DEPLOY-RENDER.md#cors-não-funciona) |
| Banco não conecta | Verificar Persistent Disk | [COMANDOS-UTEIS.md](COMANDOS-UTEIS.md#banco-não-conecta) |
| Deploy falha | Verificar logs de build | [DEPLOY-CHECKLIST.md](DEPLOY-CHECKLIST.md#se-o-deploy-falhar) |

## ✅ Status do Projeto

- ✅ Backend configurado para produção
- ✅ Migrations organizadas
- ✅ CORS configurado
- ✅ Health check implementado
- ✅ Scripts de deploy criados
- ✅ Documentação completa

## 🎉 Próximo Passo

**[Comece o deploy agora →](QUICKSTART-DEPLOY.md)**

---

**Última atualização**: 15/12/2025  
**Versão**: 1.0  
**Projeto**: SAD Dengue - Sistema de Apoio à Decisão
