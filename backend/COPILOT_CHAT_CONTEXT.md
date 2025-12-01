# Contexto do Projeto: SAD Dengue

## Objetivo
Desenvolver um Sistema de Apoio à Decisão (SAD) para avaliação de risco de dengue. O backend é Laravel 7, o frontend é Vue 2 + Vuetify, e o banco de dados é SQLite. O sistema usa dados reais do OpenDataSUS (2025), armazenados na tabela `dengue_2025`.

## Estrutura dos Dados
A tabela `dengue_2025` (SQLite) contém cada caso individual de dengue com:
- Dados demográficos (idade, sexo, raça, UF, município)
- Sintomas (FEBRE, MIALGIA, CEFALEIA, etc.)
- Sinais de alarme (ALRM_*)
- Sinais de gravidade (GRAV_*)
- Dados laboratoriais
- Datas relevantes
- Semana epidemiológica recalculada
- Variáveis derivadas:
  - SINTOMAS_TOTAL
  - ALARMES_TOTAIS
  - GRAVIDADE_TOTAL
  - TENDENCIA_TEMPORAL
- Coluna `id` PRIMARY KEY AUTOINCREMENT

## Funcionalidades Principais do Backend
- Receber dados clínicos/epidemiológicos via JSON
- Normalizar valores
- Calcular risco através do método AHP
- Retornar score final, nível de risco e justificativas
- Registrar avaliações e logs (quando implementado)
- Realizar sumarização e análises exploratórias

## Método AHP (resumo)
Critérios:
- Epidemiologia Municipal (0.45)
- Gravidade Clínica (0.35)
- Sintomas (0.15)
- Sociodemográfico (0.05)

Formulação do score:
SCORE_FINAL = 0.45*EPI + 0.35*GRAV + 0.15*SINT + 0.05*SOCIO

Níveis de risco:
- Baixo (0–0.33)
- Médio (0.34–0.66)
- Alto (0.67–1.0)

## Estrutura do Backend
- `Services/` conterá: Normalizador, AHP, Classificador, Regressão.
- `DTOs` e `Enums` serão usados para organizar entradas/saídas.
- Controllers na pasta `Http/Controllers/Api`.

## O que o Copilot deve priorizar
- Gerar serviços para cálculo AHP e normalização
- Criar DTOs para requisições e respostas
- Criar estrutura de controllers e rotas
- Gerar lógica limpa, isolada em services
- Seguir arquitetura clara e modular
- Ajudar na integração do SQLite
