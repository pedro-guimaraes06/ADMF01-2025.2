# Guia para o GitHub Copilot: Services e Controllers do SAD Dengue

## 1. Arquitetura Geral
O projeto segue Clean Architecture e Domain-Driven Design.  
Models devem ser finas e sem lógica.  
Toda lógica pesada fica em Services.  
Controllers apenas orquestram chamadas entre services.

---

## 2. Services que devem existir

### NormalizadorService
- Pasta: `app/Services/Normalizador/`
- Função: padronizar entrada, corrigir tipos, preparar dados para o AHP.

### CalculadoraAHP
- Pasta: `app/Services/AHP/`
- Carrega pesos de `config/ahp.php`
- Calcula subcritérios, critérios e score final
- Não acessa banco
- Não grava logs (controller faz isso)

### ClassificadorRiscoService
- Pasta: `app/Services/Classificador/`
- Recebe o score final
- Retorna nível de risco e justificativas

### RegressaoService
- Pasta: `app/Services/Regressao/`
- Faz regressão linear, tendências e previsões usando SQLite

### SumarizacaoService
- Pasta: `app/Services/Sumarizacao/`
- Consultas epidemiológicas e agregações

---

## 3. Controllers que devem existir

### RiscoController
- Pasta: `app/Http/Controllers/Api/`
- Rota: `POST /avaliar-risco`
- Fluxo:
  1. Validar request
  2. Normalizar entrada
  3. Calcular AHP
  4. Classificar risco
  5. Registrar AvaliacaoRisco
  6. Registrar AhpLog
  7. Retornar score + nível + justificativa

### SumarizacaoController
- Endpoints:
  - `/casos/uf`
  - `/casos/municipio`
  - `/casos/semana`
  - `/sintomas/distribuicao`

### AnaliseController (opcional)
- Endpoints de regressão e tendência

### ImportacaoController (opcional)
- Adição de novos casos à tabela dengue_2025

---

## 4. Convenções
- Services devem ser simples classes PHP
- Controllers não devem conter lógica pesada
- Nenhum cálculo AHP deve aparecer dentro de models
- Requests devem ser validados com Form Requests

---

Este documento serve como referência para o GitHub Copilot gerar serviços e controladores alinhados ao SAD Dengue.
