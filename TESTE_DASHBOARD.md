# 🚀 Teste Rápido - Dashboard Epidemiológico

## Passos para Testar

### 1️⃣ Iniciar Backend
```bash
cd backend
php artisan serve
```
✅ Backend deve estar em: `http://localhost:8080`

### 2️⃣ Iniciar Frontend
```bash
cd frontend
npm run serve
```
✅ Frontend estará em: `http://localhost:8081`

### 3️⃣ Acessar Aplicação
Abra: `http://localhost:8081`

Você verá automaticamente o **Dashboard Epidemiológico** com:
- 4 cards de estatísticas principais
- Gráfico de tendência temporal
- Distribuição demográfica
- Top 10 municípios
- Casos por UF
- Sintomas, alarmes e sinais de gravidade
- Análises preditivas com previsão

---

## 🔄 Navegação Entre Telas

Use os **tabs no topo** (desktop) ou **menu hambúrguer** (mobile):

1. **Dashboard** - Visualização de dados epidemiológicos
2. **Avaliação** - Criar nova avaliação de risco AHP
3. **Histórico** - Ver avaliações anteriores (em desenvolvimento)

---

## ✅ Endpoints Testados Automaticamente

Ao carregar o dashboard, os seguintes endpoints são chamados:

```
GET /api/casos/estatisticas
GET /api/casos/top-municipios
GET /api/casos/tendencia
GET /api/casos/faixa-etaria
GET /api/casos/uf
GET /api/sintomas/distribuicao
GET /api/sintomas/alarmes
GET /api/sintomas/gravidade
GET /api/analise/previsao?semanas=4
```

---

## 🎨 Funcionalidades Interativas

- **Hover nos cards** - Efeito de elevação
- **Gráficos com zoom** - Clique e arraste
- **Toolbar de gráficos** - Download, zoom, pan
- **Botão atualizar** - Recarrega todos os dados
- **Botão nova avaliação** - Vai para formulário
- **Responsivo** - Teste em diferentes tamanhos de tela

---

## ⚠️ Se Houver Erro

### Backend não está respondendo:
```
Erro ao carregar dados do dashboard. 
Verifique se o backend está ativo.
```
**Solução**: Certifique-se que `php artisan serve` está rodando

### CORS Error:
Verifique o arquivo `backend/config/cors.php`:
```php
'allowed_origins' => ['http://localhost:8081'],
```

### Dados vazios:
Alguns gráficos mostrarão "Sem dados disponíveis"
- É normal se o banco não tiver dados populados
- Execute seeders ou adicione dados manualmente

---

## 🔍 Debug

### Console do navegador (F12):
- Veja requisições na aba **Network**
- Veja erros JavaScript na aba **Console**

### Logs do Laravel:
```bash
tail -f backend/storage/logs/laravel.log
```

---

## 📊 Dados Esperados

O dashboard funciona melhor com:
- Pelo menos 100 registros na tabela `dengue_2025`
- Dados distribuídos por diferentes UFs
- Dados de múltiplas semanas epidemiológicas
- Campos de sintomas, alarmes e gravidade preenchidos

---

## 🎯 Teste Completo

1. ✅ Dashboard carrega sem erros
2. ✅ Cards exibem números
3. ✅ Gráficos renderizam corretamente
4. ✅ Navegação funciona entre telas
5. ✅ Botão atualizar recarrega dados
6. ✅ Responsivo em mobile
7. ✅ Ir para "Avaliação" e voltar

---

**Tudo pronto! 🎉**
