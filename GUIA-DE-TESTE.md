# Guia de Teste - SAD Dengue

## 🚀 Iniciando o projeto

### 1. Backend (Laravel)
```bash
cd c:/projetos/ADMF01-2025.2/backend
docker-compose up -d
```

### 2. Frontend (Vue.js)
```bash
cd c:/projetos/ADMF01-2025.2/frontend
npm run serve
```

---

## 🌐 Acessos

- **Frontend**: http://localhost:8070
- **Backend API**: http://localhost:8080/api
- **Teste API**: http://localhost:8080/api/test

---

## 🧪 Cenários de Teste

### Teste 1: Caso de RISCO BAIXO
Acesse: `http://localhost:8070/avaliacao`

**Dados do Paciente:**
- Idade: 25
- Sexo: M
- UF: SP
- Município: São Paulo

**Dados Epidemiológicos:**
- Casos no município: 100
- População: 12000000
- Semana epidemiológica: 10

**Sintomas:**
- ✅ Febre
- ✅ Cefaleia
- ✅ Mialgia

**Sinais de Alarme:** Nenhum
**Sinais de Gravidade:** Nenhum

**Resultado Esperado:** Score < 0.33, classificação VERDE (Risco Baixo)

---

### Teste 2: Caso de RISCO MÉDIO
**Dados do Paciente:**
- Idade: 55
- Sexo: F
- UF: RJ
- Município: Rio de Janeiro

**Dados Epidemiológicos:**
- Casos no município: 5000
- População: 6700000
- Semana epidemiológica: 15

**Sintomas:**
- ✅ Febre
- ✅ Cefaleia
- ✅ Mialgia
- ✅ Artralgia
- ✅ Exantema

**Sinais de Alarme:**
- ✅ Vômitos persistentes
- ✅ Dor abdominal intensa

**Sinais de Gravidade:** Nenhum

**Resultado Esperado:** Score 0.34-0.66, classificação AMARELA (Risco Médio)

---

### Teste 3: Caso de RISCO ALTO
**Dados do Paciente:**
- Idade: 65
- Sexo: M
- UF: BA
- Município: Salvador

**Dados Epidemiológicos:**
- Casos no município: 15000
- População: 2900000
- Semana epidemiológica: 20

**Sintomas:**
- ✅ Febre
- ✅ Cefaleia
- ✅ Mialgia
- ✅ Artralgia
- ✅ Dor retroorbital
- ✅ Exantema

**Sinais de Alarme:**
- ✅ Hipotensão postural
- ✅ Plaquetopenia
- ✅ Vômitos persistentes
- ✅ Sangramento de mucosas
- ✅ Dor abdominal intensa

**Sinais de Gravidade:**
- ✅ Hipotensão arterial
- ✅ Sangramento grave
- ✅ Alteração de consciência

**Resultado Esperado:** Score > 0.67, classificação VERMELHA (Risco Alto)

---

## 🎨 Elementos Visuais para Conferir

### No Formulário (FormAvaliacaoRisco.vue):
- [x] Stepper vertical com 6 etapas
- [x] Ícones coloridos em cada step
- [x] Campos de idade com validação (0-120)
- [x] Select de UF com 27 estados
- [x] Select de semana epidemiológica (1-53)
- [x] Cálculo automático de incidência
- [x] Checkboxes coloridos para sintomas clássicos
- [x] Switches para sintomas inespecíficos
- [x] Alertas visuais quando alarmes/gravidade selecionados
- [x] Contadores dinâmicos nos steps
- [x] Tabela de revisão no step 6
- [x] Botão "Calcular Risco" verde grande

### Na Tela de Resultado (ResultadoAvaliacao.vue):
- [x] Score circular animado com percentual (0-100%)
- [x] Chip colorido com classificação (Verde/Amarelo/Vermelho)
- [x] Border-top colorido no card principal
- [x] Gráfico Radar com 4 critérios (animado)
- [x] Gráfico de Barras horizontais coloridas
- [x] 4 Progress bars com cores diferentes
- [x] Alert grande com interpretação
- [x] 4 cards de detalhes clínicos com ícones
- [x] Timeline vertical com recomendações
- [x] Animações de entrada (fadeInUp)
- [x] Hover effects em todos os cards
- [x] 3 botões de ação no rodapé

---

## 🔧 Verificações Técnicas

### API Response Structure:
```json
{
  "success": true,
  "data": {
    "avaliacao_id": 1,
    "score_final": 0.456,
    "nivel_risco": "Médio",
    "scores": {
      "epidemiologia": 0.512,
      "gravidade": 0.423,
      "sintomas": 0.387,
      "sociodemografico": 0.125
    },
    "created_at": "2025-12-01T10:30:00.000000Z"
  }
}
```

### Vuex State:
```javascript
store.state.risco = {
  avaliacaoAtual: { /* dados da última avaliação */ },
  historico: [],
  loading: false,
  erro: null
}
```

### Router Navigation:
```
/ → /avaliacao → (submit) → /resultado/:id
```

---

## ⚠️ Troubleshooting

### Problema: Página em branco
**Solução:** Verificar console do navegador (F12). Pode ser erro de importação.

### Problema: Erro 404 ao submeter formulário
**Solução:** Verificar se backend está rodando em `http://localhost:8080`

### Problema: CORS error
**Solução:** Backend Laravel já deve ter CORS configurado. Verificar arquivo `config/cors.php`

### Problema: Gráficos não aparecem
**Solução:** ApexCharts já está instalado. Verificar se não há erro no console.

---

## 📊 Validações Implementadas

- Idade: obrigatória, entre 0-120
- Sexo: obrigatório (M/F/I)
- UF: obrigatória
- Município: obrigatório
- Casos no município: obrigatório, não negativo
- População: obrigatória, > 0
- Semana epidemiológica: obrigatória (1-53)

---

## 🎯 Próximos Passos (Opcional)

1. Implementar tela de Histórico com v-data-table
2. Criar Dashboard com estatísticas epidemiológicas
3. Adicionar exportação PDF no resultado
4. Implementar busca/filtros no histórico
5. Adicionar autenticação de usuários
6. Criar relatórios personalizados
