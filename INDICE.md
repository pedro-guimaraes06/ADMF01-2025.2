# 📖 ÍNDICE DE DOCUMENTAÇÃO - ADMF01-2025.2

Guia completo para navegar pela documentação do projeto.

---

## 🚀 COMEÇANDO

### Para Instalar o Projeto
1. **[README.md](README.md)** - Visão geral e instruções básicas
2. **[INSTALACAO.md](INSTALACAO.md)** - Guia detalhado de instalação
3. **[CHECKLIST.md](CHECKLIST.md)** - Checklist passo a passo de verificação

### Scripts de Instalação Automática
- **[install.bat](install.bat)** - Script para Windows
- **[install.sh](install.sh)** - Script para Linux/Mac

---

## 📚 DESENVOLVIMENTO

### Para Começar a Desenvolver
1. **[QUICKSTART.md](QUICKSTART.md)** - Referência rápida e comandos úteis
2. **[EXEMPLOS.md](EXEMPLOS.md)** - Exemplos práticos de código
3. **[STATUS.md](STATUS.md)** - Status completo do projeto

---

## 📁 ESTRUTURA DO PROJETO

```
ADMF01-2025.2/
│
├── 📘 DOCUMENTAÇÃO (você está aqui)
│   ├── README.md              # Visão geral
│   ├── INSTALACAO.md          # Guia de instalação completo
│   ├── QUICKSTART.md          # Referência rápida
│   ├── CHECKLIST.md           # Checklist de verificação
│   ├── EXEMPLOS.md            # Exemplos de código
│   ├── STATUS.md              # Status do projeto
│   └── INDICE.md              # Este arquivo
│
├── 🐳 DOCKER
│   ├── .docker/
│   │   ├── php/               # Dockerfile PHP 7.4 + Apache
│   │   ├── vue/               # Dockerfile Node.js 14
│   │   └── sql/               # Dockerfile SQL Server
│   └── docker-compose.yml     # Orquestração de containers
│
├── 🔧 BACKEND (Laravel 7)
│   └── backend/
│       ├── app/               # Código da aplicação
│       ├── routes/            # Rotas API e Web
│       ├── config/            # Configurações
│       ├── database/          # Migrations e seeds
│       └── ...
│
└── 🎨 FRONTEND (Vue 2 + Vuetify)
    └── frontend/
        ├── src/
        │   ├── components/    # Componentes Vue
        │   ├── modules/       # Páginas/módulos
        │   ├── router/        # Rotas frontend
        │   ├── api/           # Configuração Axios
        │   └── plugins/       # Plugins (Vuetify, etc)
        └── ...
```

---

## 🎯 FLUXO RECOMENDADO

### 1️⃣ Instalação (Primeira Vez)
```
README.md → INSTALACAO.md → install.bat/sh → CHECKLIST.md
```

### 2️⃣ Desenvolvimento (Dia a Dia)
```
QUICKSTART.md → EXEMPLOS.md
```

### 3️⃣ Verificação de Problemas
```
CHECKLIST.md → INSTALACAO.md (seção "Solução de Problemas")
```

### 4️⃣ Entender o Projeto
```
STATUS.md (estrutura completa)
```

---

## 📖 DESCRIÇÃO DOS DOCUMENTOS

### README.md
**O que é:** Introdução ao projeto  
**Quando usar:** Primeira vez que vê o projeto  
**Conteúdo:**
- Visão geral
- Requisitos
- Instalação básica
- Acesso às URLs
- Estrutura resumida

### INSTALACAO.md
**O que é:** Guia completo de instalação  
**Quando usar:** Para instalar do zero ou resolver problemas  
**Conteúdo:**
- Pré-requisitos detalhados
- Instalação passo a passo
- Comandos úteis
- Solução de problemas
- Checklist de verificação

### QUICKSTART.md
**O que é:** Referência rápida para desenvolvimento  
**Quando usar:** No dia a dia, como consulta rápida  
**Conteúdo:**
- Instalação rápida (1 comando)
- URLs importantes
- Comandos mais usados
- Exemplos básicos de código
- Hot reload

### CHECKLIST.md
**O que é:** Lista de verificação completa  
**Quando usar:** Após instalação, para garantir que tudo funciona  
**Conteúdo:**
- Pré-requisitos
- Passos de instalação
- Testes funcionais
- Verificações
- Solução de problemas

### EXEMPLOS.md
**O que é:** Biblioteca de exemplos de código  
**Quando usar:** Ao desenvolver novas features  
**Conteúdo:**
- Controllers Laravel
- Models e Services
- Componentes Vue
- Formulários com Vuetify
- Requisições API
- Autenticação JWT

### STATUS.md
**O que é:** Status completo da estrutura do projeto  
**Quando usar:** Para entender o que está implementado  
**Conteúdo:**
- Estrutura completa de arquivos
- Configurações implementadas
- Tecnologias usadas
- Status de cada componente

---

## 🔍 ENCONTRE RÁPIDO

### "Como instalo o projeto?"
→ **[INSTALACAO.md](INSTALACAO.md)** ou execute **[install.bat](install.bat)** / **[install.sh](install.sh)**

### "Como faço X no Laravel?"
→ **[EXEMPLOS.md](EXEMPLOS.md)** (seção Backend)

### "Como faço X no Vue?"
→ **[EXEMPLOS.md](EXEMPLOS.md)** (seção Frontend)

### "Quais comandos Docker posso usar?"
→ **[QUICKSTART.md](QUICKSTART.md)** (seção Comandos Úteis)

### "O backend não está funcionando"
→ **[CHECKLIST.md](CHECKLIST.md)** (seção Solução de Problemas)

### "Quais tecnologias foram usadas?"
→ **[STATUS.md](STATUS.md)** (seção Tecnologias)

### "Onde ficam os arquivos X?"
→ **[STATUS.md](STATUS.md)** (Estrutura Completa)

---

## 📌 LINKS ÚTEIS

### Documentação Oficial
- **Laravel 7**: https://laravel.com/docs/7.x
- **Vue 2**: https://v2.vuejs.org/
- **Vuetify 2**: https://v2.vuetifyjs.com/
- **Docker**: https://docs.docker.com/

### URLs do Projeto (após instalação)
- **Frontend**: http://localhost:8070
- **Backend**: http://localhost:8080
- **API Teste**: http://localhost:8080/api/test

---

## 🆘 SUPORTE

### Problemas Comuns

| Problema | Solução |
|----------|---------|
| Container não inicia | [CHECKLIST.md](CHECKLIST.md) → Solução de Problemas |
| Erro 500 no backend | [INSTALACAO.md](INSTALACAO.md) → Backend não carrega |
| Frontend não compila | [INSTALACAO.md](INSTALACAO.md) → Frontend não carrega |
| Porta em uso | [INSTALACAO.md](INSTALACAO.md) → Porta já em uso |

### Comandos de Emergência

```bash
# Ver logs
docker-compose logs -f

# Reiniciar tudo
docker-compose restart

# Limpar e reconstruir
docker-compose down
docker-compose up -d --build
```

---

## ✅ CHECKLIST RÁPIDO

Após instalação, verifique:

- [ ] Containers rodando: `docker-compose ps`
- [ ] Backend: http://localhost:8080/api/test
- [ ] Frontend: http://localhost:8070
- [ ] Logs sem erros: `docker-compose logs`

Se todos os itens estão OK, você está pronto! 🚀

---

## 📞 PRÓXIMOS PASSOS

1. ✅ **Instalou?** → Vá para [QUICKSTART.md](QUICKSTART.md)
2. ✅ **Vai desenvolver?** → Vá para [EXEMPLOS.md](EXEMPLOS.md)
3. ❌ **Deu erro?** → Vá para [CHECKLIST.md](CHECKLIST.md)

---

**Dica:** Mantenha este arquivo como referência rápida de toda a documentação!
