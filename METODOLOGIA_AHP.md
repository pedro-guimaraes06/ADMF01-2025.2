# Metodologia AHP - Avaliação Multicritério de Risco

## Sistema de Apoio à Decisão - Dengue 2025

---

## 1. Método AHP (Analytic Hierarchy Process)

- **Processo analítico hierárquico** para avaliação de risco de dengue
- Estrutura multicritério com pesos baseados em importância clínica e epidemiológica
- Score final normalizado entre 0 e 1

---

## 2. Hierarquia de Critérios Principais

| Critério | Peso | Justificativa |
|----------|------|---------------|
| **Gravidade Clínica** | 50% | Prioridade máxima - sinais de risco iminente |
| **Sintomas** | 30% | Manifestações clínicas gerais |
| **Epidemiologia** | 15% | Contexto epidemiológico local |
| **Sociodemográfico** | 5% | Fatores demográficos de risco |

**Total:** 100%

---

## 3. Subcritérios e Pesos Internos

### 3.1 Gravidade Clínica (50%)

#### **Sinais de Alarme: 60%**
- Hipotensão postural
- Queda abrupta de plaquetas
- Vômitos persistentes
- Sangramento de mucosas
- Hematócrito elevado
- Dor abdominal intensa
- Letargia/irritabilidade
- Hepatomegalia (>2cm)
- Acúmulo de líquidos

#### **Sinais de Gravidade: 40%**
- Choque/pulso fraco
- Convulsões
- Enchimento capilar lento
- Insuficiência respiratória
- Extremidades frias
- Hipotensão arterial
- Hemorragias graves
- Melena/hematêmese
- Metrorragia volumosa
- AST/ALT > 1000
- Miocardite
- Alteração de consciência
- Disfunção orgânica

---

### 3.2 Sintomas (30%)

#### **Sintomas Clássicos: 70%**
- Febre
- Cefaleia (dor de cabeça)
- Mialgia (dor muscular)
- Artralgia (dor articular)
- Dor retroorbital
- Exantema (rash cutâneo)

#### **Sintomas Inespecíficos: 30%**
- Náusea
- Vômito
- Dor nas costas
- Conjuntivite
- Petéquias
- Leucopenia
- Prova do laço positiva

---

### 3.3 Epidemiologia (15%)

| Subcritério | Peso Interno |
|-------------|--------------|
| Incidência Municipal | 50% |
| Tendência Temporal | 30% |
| Semana Epidemiológica | 20% |

**Parâmetros:**
- **Incidência máxima considerada:** 500 casos/100k habitantes
- **Semanas de pico:** 10-25 (maior peso)
- **Tendência:** Calculada por regressão linear dos últimos dados

---

### 3.4 Sociodemográfico (5%)

| Subcritério | Peso Interno |
|-------------|--------------|
| Idade | 60% |
| Comorbidades | 40% |

**Faixas de maior risco:**
- Idosos (>60 anos)
- Crianças (<12 anos)
- Gestantes

---

## 4. Fórmula de Cálculo

### Score Final AHP

```
Score_Final = (Score_Gravidade × 0,50) + 
              (Score_Sintomas × 0,30) + 
              (Score_Epidemiologia × 0,15) + 
              (Score_Sociodemográfico × 0,05)
```

### Cálculo por Critério

#### Gravidade:
```
Score_Gravidade = (Alarmes_Norm × 0,60) + (Gravidade_Norm × 0,40)
```

#### Sintomas:
```
Score_Sintomas = (Clássicos_Norm × 0,70) + (Inespecíficos_Norm × 0,30)
```

#### Epidemiologia:
```
Score_Epidemiologia = (Incidência_Norm × 0,50) + 
                      (Tendência_Norm × 0,30) + 
                      (Semana_Norm × 0,20)
```

#### Sociodemográfico:
```
Score_Sociodemográfico = (Idade_Norm × 0,60) + (Comorbidades_Norm × 0,40)
```

---

## 5. Classificação de Risco

| Nível | Faixa de Score | Cor | Ação Recomendada |
|-------|----------------|-----|------------------|
| **Baixo** | 0,00 - 0,33 | 🟢 Verde | Monitoramento ambulatorial |
| **Médio** | 0,34 - 0,66 | 🟠 Laranja | Avaliação médica prioritária |
| **Alto** | 0,67 - 1,00 | 🔴 Vermelho | Intervenção imediata/hospitalização |

---

## 6. Normalização dos Dados

Todos os valores são normalizados para escala **0 a 1** antes do cálculo AHP:

### Parâmetros de Normalização

| Variável | Valor Mínimo | Valor Máximo |
|----------|--------------|--------------|
| Idade | 0 anos | 120 anos |
| Incidência | 0 | 500 casos/100k hab |
| Sintomas | 0 | 15 sintomas |
| Alarmes | 0 | 9 sinais |
| Gravidade | 0 | 14 sinais |

### Fórmula de Normalização

```
Valor_Normalizado = (Valor_Atual - Valor_Min) / (Valor_Max - Valor_Min)
```

---

## 7. Fundamentação Técnica

### Base Científica
- **Ministério da Saúde:** Diretrizes para Diagnóstico e Tratamento da Dengue
- **OMS:** Guidelines for Dengue Diagnosis, Treatment, Prevention and Control
- **Literatura científica:** Estudos sobre fatores de risco e gravidade da dengue

### Justificativa dos Pesos

1. **Gravidade (50%):** Maior peso devido ao impacto direto na sobrevida do paciente
2. **Sintomas (30%):** Importante para identificação precoce e classificação inicial
3. **Epidemiologia (15%):** Contexto local influencia probabilidade de transmissão
4. **Sociodemográfico (5%):** Fatores de risco secundários, porém relevantes

---

## 8. Implementação Técnica

### Arquitetura do Sistema

```
config/ahp.php
   ↓
CalculadoraAHP.php
   ↓
AvaliacaoController.php
   ↓
API REST (/api/avaliacao/calcular)
   ↓
Frontend Vue.js
```

### Fluxo de Dados

1. **Entrada:** Dados clínicos, demográficos e epidemiológicos
2. **Normalização:** Conversão para escala 0-1
3. **Cálculo AHP:** Aplicação dos pesos hierárquicos
4. **Classificação:** Determinação do nível de risco
5. **Registro:** Log em banco de dados para análise posterior

---

## 9. Vantagens da Metodologia AHP

✅ **Transparência:** Critérios e pesos explícitos e auditáveis  
✅ **Flexibilidade:** Fácil ajuste de pesos conforme necessidade clínica  
✅ **Objetividade:** Redução de viés subjetivo na avaliação  
✅ **Hierarquia clara:** Estrutura lógica de decisão  
✅ **Validação científica:** Baseado em evidências epidemiológicas  

---

## 10. Referências

- **Arquivo de Configuração:** `backend/config/ahp.php`
- **Serviço de Cálculo:** `backend/app/Services/AHP/CalculadoraAHP.php`
- **API Endpoint:** `POST /api/avaliacao/calcular`
- **Documentação Completa:** Ver arquivos `README.md` e `GUIA-DE-TESTE.md`

---

**Última atualização:** Dezembro 2025  
**Versão:** 1.0  
**Projeto:** ADMF01-2025.2 - Sistema de Apoio à Decisão para Dengue
