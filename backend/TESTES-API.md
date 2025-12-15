# 🧪 Testes da API - Exemplos

## Health Check

### Requisição
```bash
curl -X GET https://seu-app.onrender.com/api/status
```

### Resposta Esperada
```json
{
  "status": "ok",
  "service": "SAD Dengue API",
  "timestamp": "2025-12-15T10:30:00Z",
  "database": "connected",
  "environment": "production"
}
```

---

## Avaliar Risco de Dengue

### Caso 1: Risco Baixo

#### Requisição
```bash
curl -X POST https://seu-app.onrender.com/api/risco/avaliar \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "febre": 1,
    "mialgia": 0,
    "cefaleia": 1,
    "exantema": 0,
    "vomito": 0,
    "nausea": 0,
    "dor_costas": 0,
    "conjuntvit": 0,
    "artralgia": 0,
    "dor_retro": 0,
    "alrm_hipot": 0,
    "alrm_plaq": 0,
    "alrm_vom": 0,
    "alrm_sang": 0,
    "alrm_abdom": 0,
    "grav_pulso": 0,
    "grav_hipot": 0,
    "grav_sang": 0,
    "idade": 25,
    "sexo": "M",
    "uf": "SP",
    "semana_epidemiologica": 10,
    "casos_municipio": 50,
    "populacao_municipio": 100000,
    "tendencia_temporal": 0.8
  }'
```

#### Resposta Esperada
```json
{
  "success": true,
  "data": {
    "id": 1,
    "score_final": 0.3245,
    "nivel_risco": "Baixo",
    "justificativa": "Paciente apresenta sintomas clássicos leves sem sinais de alarme...",
    "detalhes": {
      "score_epidemiologia": 0.2145,
      "score_gravidade": 0.0000,
      "score_sintomas": 0.4523,
      "score_sociodemografico": 0.1234
    }
  }
}
```

---

### Caso 2: Risco Médio

#### Requisição
```bash
curl -X POST https://seu-app.onrender.com/api/risco/avaliar \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "febre": 1,
    "mialgia": 1,
    "cefaleia": 1,
    "exantema": 1,
    "vomito": 1,
    "nausea": 1,
    "dor_costas": 1,
    "conjuntvit": 0,
    "artralgia": 1,
    "dor_retro": 1,
    "alrm_hipot": 0,
    "alrm_plaq": 1,
    "alrm_vom": 1,
    "alrm_sang": 0,
    "alrm_abdom": 1,
    "grav_pulso": 0,
    "grav_hipot": 0,
    "grav_sang": 0,
    "idade": 45,
    "sexo": "F",
    "uf": "BA",
    "semana_epidemiologica": 15,
    "casos_municipio": 250,
    "populacao_municipio": 500000,
    "tendencia_temporal": 1.3
  }'
```

#### Resposta Esperada
```json
{
  "success": true,
  "data": {
    "id": 2,
    "score_final": 0.6234,
    "nivel_risco": "Médio",
    "justificativa": "Paciente apresenta múltiplos sintomas clássicos e sinais de alarme...",
    "detalhes": {
      "score_epidemiologia": 0.5621,
      "score_gravidade": 0.4234,
      "score_sintomas": 0.7823,
      "score_sociodemografico": 0.3456
    }
  }
}
```

---

### Caso 3: Risco Alto

#### Requisição
```bash
curl -X POST https://seu-app.onrender.com/api/risco/avaliar \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "febre": 1,
    "mialgia": 1,
    "cefaleia": 1,
    "exantema": 1,
    "vomito": 1,
    "nausea": 1,
    "dor_costas": 1,
    "conjuntvit": 1,
    "artralgia": 1,
    "dor_retro": 1,
    "alrm_hipot": 1,
    "alrm_plaq": 1,
    "alrm_vom": 1,
    "alrm_sang": 1,
    "alrm_abdom": 1,
    "alrm_hemat": 1,
    "grav_pulso": 1,
    "grav_hipot": 1,
    "grav_sang": 1,
    "grav_hemat": 1,
    "idade": 65,
    "sexo": "F",
    "uf": "RJ",
    "semana_epidemiologica": 20,
    "casos_municipio": 800,
    "populacao_municipio": 1000000,
    "tendencia_temporal": 2.5
  }'
```

#### Resposta Esperada
```json
{
  "success": true,
  "data": {
    "id": 3,
    "score_final": 0.8567,
    "nivel_risco": "Alto",
    "justificativa": "Paciente em condição crítica com múltiplos sinais de gravidade...",
    "detalhes": {
      "score_epidemiologia": 0.8945,
      "score_gravidade": 0.9234,
      "score_sintomas": 0.9123,
      "score_sociodemografico": 0.6789
    }
  }
}
```

---

## Estatísticas Gerais

### Requisição
```bash
curl -X GET https://seu-app.onrender.com/api/casos/estatisticas \
  -H "Accept: application/json"
```

### Resposta Esperada
```json
{
  "success": true,
  "data": {
    "total_casos": 15234,
    "casos_confirmados": 12456,
    "casos_suspeitos": 2778,
    "obitos": 89,
    "taxa_letalidade": 0.58,
    "media_idade": 42.5,
    "distribuicao_sexo": {
      "M": 6234,
      "F": 9000
    }
  }
}
```

---

## Casos por UF

### Requisição
```bash
curl -X GET https://seu-app.onrender.com/api/casos/uf \
  -H "Accept: application/json"
```

### Resposta Esperada
```json
{
  "success": true,
  "data": [
    {
      "uf": "SP",
      "total_casos": 5234,
      "percentual": 34.35
    },
    {
      "uf": "RJ",
      "total_casos": 3456,
      "percentual": 22.68
    },
    {
      "uf": "BA",
      "total_casos": 2345,
      "percentual": 15.39
    }
  ]
}
```

---

## Dashboard Completo

### Requisição
```bash
curl -X GET https://seu-app.onrender.com/api/analise/dashboard \
  -H "Accept: application/json"
```

### Resposta Esperada
```json
{
  "success": true,
  "data": {
    "resumo": {
      "total_casos": 15234,
      "casos_graves": 1523,
      "obitos": 89,
      "taxa_letalidade": 0.58
    },
    "distribuicao_geografica": {
      "por_uf": [...],
      "por_municipio": [...]
    },
    "tendencia_temporal": {
      "semanas": [10, 11, 12, 13, 14, 15],
      "casos": [234, 345, 456, 567, 678, 789]
    },
    "sintomas_mais_comuns": {
      "febre": 95.6,
      "cefaleia": 87.3,
      "mialgia": 76.5
    },
    "distribuicao_risco": {
      "baixo": 45.2,
      "medio": 38.7,
      "alto": 16.1
    }
  }
}
```

---

## Erros Comuns

### 404 - Rota não encontrada
```json
{
  "message": "Not Found"
}
```
**Solução**: Verifique se a URL está correta e inclui `/api/`

### 422 - Validação falhou
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "febre": ["O campo febre é obrigatório."],
    "idade": ["O campo idade deve ser um número."]
  }
}
```
**Solução**: Corrija os campos indicados

### 500 - Erro interno
```json
{
  "message": "Server Error"
}
```
**Solução**: Verifique logs no Render Shell

---

## Testando com JavaScript (Frontend)

```javascript
// Health Check
async function testHealthCheck() {
  const response = await fetch('https://seu-app.onrender.com/api/status');
  const data = await response.json();
  console.log('Health Check:', data);
}

// Avaliar Risco
async function avaliarRisco(dados) {
  const response = await fetch('https://seu-app.onrender.com/api/risco/avaliar', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    },
    body: JSON.stringify(dados)
  });
  
  const result = await response.json();
  console.log('Resultado:', result);
  return result;
}

// Exemplo de uso
const dadosPaciente = {
  febre: 1,
  mialgia: 1,
  cefaleia: 1,
  idade: 35,
  sexo: 'F',
  uf: 'SP'
};

avaliarRisco(dadosPaciente);
```

---

## Testando com Postman

1. Importe a collection (se disponível)
2. Configure a variável `base_url`: `https://seu-app.onrender.com`
3. Execute os endpoints na ordem:
   - Health Check
   - Avaliar Risco
   - Estatísticas

---

## Campos Obrigatórios Mínimos

Para `POST /api/risco/avaliar`:

```json
{
  "febre": 0 ou 1,
  "idade": número,
  "sexo": "M" ou "F",
  "uf": "XX"
}
```

Todos os outros campos são opcionais e assumem valor `0` se não fornecidos.

---

**💡 Dica**: Use ferramentas como Postman, Insomnia ou Thunder Client para testar a API mais facilmente.
