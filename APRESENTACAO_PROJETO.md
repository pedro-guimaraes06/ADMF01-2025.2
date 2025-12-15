# Sistema de Apoio à Decisão para Avaliação de Risco de Dengue

## 📋 Documento de Contexto para Apresentação

*Última atualização: 15 de dezembro de 2025*

---

## 🦟 1. CONTEXTO DA DENGUE NO BRASIL

### 1.1 Panorama Epidemiológico

A dengue representa um dos principais desafios de saúde pública no Brasil, sendo uma doença endêmica com padrões sazonais bem definidos. Nos últimos anos, o país tem enfrentado:

- **Epidemias recorrentes** com milhões de casos notificados anualmente
- **Aumento da circulação simultânea** dos quatro sorotipos do vírus (DENV-1, DENV-2, DENV-3, DENV-4)
- **Expansão geográfica** do Aedes aegypti para todas as regiões brasileiras
- **Alta taxa de mortalidade** em casos graves não diagnosticados precocemente
- **Sobrecarga dos sistemas de saúde** durante os períodos de pico epidemiológico

### 1.2 Impacto Socioeconômico

- **Custos hospitalares elevados** para tratamento de casos graves
- **Perda de produtividade** devido a afastamentos do trabalho
- **Impacto psicológico** nas comunidades afetadas
- **Desafios logísticos** na gestão de recursos de saúde durante epidemias

### 1.3 Desafios Atuais

- **Subnotificação de casos** em áreas remotas ou de difícil acesso
- **Dificuldade na triagem rápida** de casos de maior risco
- **Falta de ferramentas automatizadas** para apoio à decisão clínica
- **Variabilidade na capacidade de resposta** entre diferentes municípios

---

## 🎯 2. OBJETIVO DO PROJETO

### 2.1 Objetivo Geral

Desenvolver um **Sistema de Apoio à Decisão (SAD)** baseado no método **AHP (Analytic Hierarchy Process)** para avaliação automática e padronizada do risco de dengue em pacientes, auxiliando profissionais de saúde na tomada de decisões clínicas mais rápidas e assertivas.

### 2.2 Objetivos Específicos

1. **Calcular automaticamente** o nível de risco (Baixo, Médio, Alto) com base em critérios clínicos e epidemiológicos
2. **Fornecer justificativas explicáveis** para as decisões tomadas pelo sistema
3. **Gerar recomendações clínicas** personalizadas para cada nível de risco
4. **Realizar análises epidemiológicas** (sumarização, classificação, regressão)
5. **Integrar dados demográficos e epidemiológicos** locais na avaliação
6. **Facilitar a triagem e priorização** de pacientes em unidades de saúde

---

## 👥 3. PÚBLICO-ALVO

### 3.1 Usuários Principais

- **Médicos e enfermeiros** em unidades básicas de saúde (UBS)
- **Equipes de pronto-atendimento** e emergências
- **Epidemiologistas** e gestores de saúde pública
- **Profissionais de vigilância epidemiológica**

### 3.2 Contextos de Uso

- Triagem inicial de pacientes com suspeita de dengue
- Decisão sobre internação ou acompanhamento ambulatorial
- Monitoramento de pacientes em observação
- Análise de tendências epidemiológicas regionais
- Planejamento de recursos e estratégias de controle

---

## ❓ 4. PROBLEMA DE DECISÃO

### 4.1 Definição do Problema

**"Como determinar de forma rápida, objetiva e padronizada o nível de risco de um paciente com suspeita de dengue, considerando múltiplos critérios clínicos e epidemiológicos?"**

### 4.2 Complexidade do Problema

A decisão envolve:

- **Múltiplos critérios** com diferentes graus de importância
- **Incerteza** nos dados clínicos e laboratoriais
- **Variabilidade temporal** (sazonalidade da doença)
- **Contexto geográfico** (incidência local)
- **Fatores demográficos** (idade, comorbidades)
- **Pressão temporal** para tomada de decisão em cenários de emergência

### 4.3 Alternativas de Decisão

O sistema classifica o paciente em uma de três categorias:

1. **Risco Baixo** → Acompanhamento ambulatorial
2. **Risco Médio** → Observação por 24h em unidade de saúde
3. **Risco Alto** → Internação hospitalar imediata

---

## 🔬 5. ESCOPO DO PROJETO

### 5.1 Funcionalidades Implementadas

#### 5.1.1 Avaliação de Risco Individual
- Formulário interativo em 6 etapas para coleta de dados clínicos
- Cálculo automático do score de risco usando método AHP
- Classificação em níveis de risco com justificativas
- Geração de recomendações clínicas personalizadas

#### 5.1.2 Análises Epidemiológicas
- **Sumarização**: Estatísticas gerais, casos por UF/município, distribuição de sintomas
- **Classificação**: Identificação de fatores críticos e padrões de risco
- **Regressão**: Tendências temporais e previsão de casos futuros

#### 5.1.3 Dashboard Analítico
- Visualização de dados epidemiológicos
- Gráficos de incidência temporal e geográfica
- Indicadores de casos graves e sinais de alarme

### 5.2 Limitações e Exclusões

- Não substitui o julgamento clínico profissional
- Não realiza diagnóstico definitivo (apenas avaliação de risco)
- Não integra com sistemas hospitalares (SIH/SUS)
- Não possui módulo de gestão de leitos ou recursos

---

## 💻 6. STACK TECNOLÓGICA

### 6.1 Arquitetura Geral

```
┌─────────────────┐       ┌─────────────────┐       ┌─────────────────┐
│    Frontend     │◄─────►│    Backend      │◄─────►│   Database      │
│   Vue 2 + UI    │ HTTP  │  Laravel 7 API  │  SQL  │    SQLite       │
└─────────────────┘       └─────────────────┘       └─────────────────┘
```

### 6.2 Frontend

#### Tecnologias
- **Vue.js 2.6.11** - Framework JavaScript progressivo
- **Vuetify 2.3.8** - Framework UI Material Design
- **Vuex 3.4.0** - Gerenciamento de estado
- **Vue Router 3.4.3** - Roteamento SPA
- **Axios 0.20.0** - Cliente HTTP

#### Bibliotecas Complementares
- **ApexCharts** - Visualização de gráficos
- **Vue2Editor** - Editor WYSIWYG
- **VueIziToast** - Notificações
- **SweetAlert2** - Modais de confirmação
- **Vuelidate** - Validação de formulários
- **Moment.js** - Manipulação de datas

### 6.3 Backend

#### Tecnologias
- **Laravel 7.x** - Framework PHP MVC
- **PHP 7.4** - Linguagem de programação
- **Apache 2.4** - Servidor web

#### Packages Laravel
- **Laravel Passport** - Autenticação OAuth2 (preparado, não implementado)
- **Laravel Telescope** - Debugging e monitoramento
- **Maatwebsite Excel** - Import/Export de planilhas
- **DomPDF** - Geração de relatórios PDF

### 6.4 Infraestrutura

- **Docker** - Containerização da aplicação
- **Docker Compose** - Orquestração de containers
- **SQLite** - Banco de dados relacional

### 6.5 Containerização

```yaml
Serviços Docker:
├── app-api (Laravel)
│   └── Porta: 8080
│   └── PHP 7.4 + Apache + Composer
│
└── app-front (Vue)
    └── Porta: 8070
    └── Node.js 14 + Vue CLI + Hot Reload
```

---

## 📊 7. BASE DE DADOS

### 7.1 Origem dos Dados

Os dados utilizados no projeto são provenientes do **SINAN (Sistema de Informação de Agravos de Notificação)**, especificamente:

- **Fonte**: Dados de notificações de dengue do ano de 2025
- **Formato original**: CSV (valores separados por vírgula)
- **Volume**: Milhares de registros de casos notificados

### 7.2 Processo de Tratamento

#### 7.2.1 Etapas de Preparação

1. **Análise Inicial**
   - Identificação de colunas relevantes para o modelo AHP
   - Verificação de consistência e integridade dos dados

2. **Limpeza de Dados**
   - **Remoção de colunas administrativas** não essenciais para a análise
   - Exemplos removidos:
     - Identificadores internos de sistemas
     - Campos de auditoria e controle
     - Dados pessoais sensíveis (LGPD)
     - Informações redundantes ou duplicadas

3. **Transformação**
   - Conversão de campos de texto para códigos numéricos
   - Padronização de valores categóricos
   - Criação de campos calculados:
     - `SINTOMAS_TOTAL` (soma de sintomas presentes)
     - `ALARMES_TOTAIS` (soma de sinais de alarme)
     - `GRAVIDADE_TOTAL` (soma de sinais de gravidade)

4. **Conversão para SQLite**
   - Importação do CSV tratado para banco SQLite
   - Criação de índices para otimização de consultas
   - Definição de tipos de dados apropriados
   - Validação de integridade referencial

#### 7.2.2 Justificativa da Escolha do SQLite

- **Simplicidade**: Banco de dados embutido, sem necessidade de servidor
- **Portabilidade**: Arquivo único facilita distribuição e backup
- **Performance**: Adequado para operações de leitura intensiva
- **Desenvolvimento ágil**: Ideal para prototipagem e demonstrações
- **Zero configuração**: Não requer instalação ou administração

### 7.3 Estrutura da Base

#### Principais Campos Utilizados

**Dados Demográficos:**
- `NU_IDADE_N`: Idade do paciente (codificado SINAN: 4000 + idade)
- `CS_SEXO`: Sexo (M/F/I)
- `SG_UF`: Unidade federativa
- `MUNICIPIO`: Nome do município

**Dados Epidemiológicos:**
- `SEM_PRI`: Semana epidemiológica dos primeiros sintomas
- `DT_NOTIFIC`: Data de notificação
- `DT_SIN_PRI`: Data dos primeiros sintomas

**Sintomas Clássicos:**
- `FEBRE`: Presença de febre (1=Sim, 0=Não)
- `CEFALEIA`: Dor de cabeça
- `MIALGIA`: Dor muscular
- `ARTRALGIA`: Dor nas articulações
- `DOR_RETRO`: Dor retroorbital
- `EXANTEMA`: Erupções cutâneas

**Sintomas Inespecíficos:**
- `NAUSEA`, `VOMITO`, `DOR_COSTAS`
- `CONJUNTVIT`, `PETEQUIA_N`
- `LEUCOPENIA`, `LACO`

**Sinais de Alarme (9 sinais):**
- `ALRM_HIPOT`: Hipotensão postural
- `ALRM_PLAQ`: Plaquetopenia
- `ALRM_VOM`: Vômitos persistentes
- `ALRM_SANG`: Sangramento de mucosas
- `ALRM_HEMAT`: Aumento do hematócrito
- `ALRM_ABDOM`: Dor abdominal intensa
- `ALRM_LETAR`: Letargia/irritabilidade
- `ALRM_HEPAT`: Hepatomegalia dolorosa
- `ALRM_LIQ`: Acúmulo de líquidos

**Sinais de Gravidade (14 sinais):**
- `GRAV_PULSO`: Pulso filiforme
- `GRAV_CONV`: Convulsões
- `GRAV_ENCH`: Enchimento capilar lento
- `GRAV_INSC`: Insuficiência respiratória
- `GRAV_HIPOT`: Hipotensão arterial
- `GRAV_HEMAT`: Hematócrito muito elevado
- `GRAV_SANG`: Sangramento grave
- `GRAV_AST`: AST/ALT > 1000
- `GRAV_CONSC`: Alteração de consciência
- `GRAV_ORGAO`: Falência de órgãos
- E outros...

---

## 🧮 8. METODOLOGIA AHP (ANALYTIC HIERARCHY PROCESS)

### 8.1 Fundamentos do Método

O **AHP** é um método de tomada de decisão multicritério desenvolvido por Thomas Saaty na década de 1970. Permite estruturar problemas complexos em hierarquias e atribuir pesos relativos a cada critério.

### 8.2 Hierarquia de Decisão do Projeto

```
                    [Risco de Dengue]
                           |
        ┌──────────────────┼──────────────────┐
        │                  │                  │
   Epidemiologia      Gravidade          Sintomas      Sociodemográfico
     (15%)            (50%)              (30%)              (5%)
        │                  │                  │                  │
   ┌────┼────┐        ┌────┴────┐      ┌─────┴─────┐           │
   │    │    │        │         │      │           │           │
 Incid Tend Sem    Alarmes  Gravidade Class  Inespec          Idade
  50%  30%  20%     60%       40%      70%     30%            60%
```

### 8.3 Definição de Critérios e Pesos

#### 8.3.1 Critérios Principais

| Critério | Peso | Justificativa |
|----------|------|---------------|
| **Gravidade Clínica** | 50% | Sinais de alarme e gravidade indicam risco imediato de complicações graves ou óbito. Tem a maior prioridade na decisão clínica. |
| **Sintomas** | 30% | Presença e quantidade de sintomas clássicos de dengue confirmam a suspeita diagnóstica e indicam intensidade da infecção. |
| **Epidemiologia** | 15% | Contexto epidemiológico local (incidência, sazonalidade) aumenta ou reduz a probabilidade de dengue. |
| **Sociodemográfico** | 5% | Idade e comorbidades são fatores de risco secundários, mas importantes para grupos vulneráveis. |

#### 8.3.2 Subcritérios - Gravidade Clínica (50%)

| Subcritério | Peso | Descrição |
|-------------|------|-----------|
| **Sinais de Alarme** | 60% (= 30% do total) | 9 sinais que indicam evolução para dengue grave: vômitos persistentes, sangramento, plaquetopenia, hipotensão, dor abdominal intensa, etc. |
| **Sinais de Gravidade** | 40% (= 20% do total) | 14 sinais críticos que caracterizam dengue grave: choque, insuficiência respiratória, convulsões, falência de órgãos, etc. |

**Normalização:**
- Sinais de Alarme: Divide total de sinais presentes por 9 (máximo possível)
- Sinais de Gravidade: Divide total de sinais presentes por 14 (máximo possível)

#### 8.3.3 Subcritérios - Sintomas (30%)

| Subcritério | Peso | Descrição |
|-------------|------|-----------|
| **Sintomas Clássicos** | 70% (= 21% do total) | 6 sintomas típicos de dengue: febre, cefaleia, mialgia, artralgia, dor retroorbital, exantema |
| **Sintomas Inespecíficos** | 30% (= 9% do total) | 7 sintomas adicionais: náusea, vômito, dor nas costas, conjuntivite, petéquias, leucopenia, prova do laço |

**Normalização:**
- Clássicos: Divide total de sintomas presentes por 6
- Inespecíficos: Divide total de sintomas presentes por 7

#### 8.3.4 Subcritérios - Epidemiologia (15%)

| Subcritério | Peso | Descrição |
|-------------|------|-----------|
| **Incidência Municipal** | 50% (= 7.5% do total) | Taxa de casos por 100 mil habitantes. Normalizada até 500 casos/100k. |
| **Tendência Temporal** | 30% (= 4.5% do total) | Crescimento ou decrescimento de casos (via regressão linear). |
| **Semana Epidemiológica** | 20% (= 3% do total) | Sazonalidade: semanas 10-25 são consideradas período de pico. |

**Normalização:**
- Incidência: `min(incidência / 500, 1.0)`
- Semana: Função gaussiana centrada nas semanas 10-25

#### 8.3.5 Subcritérios - Sociodemográfico (5%)

| Subcritério | Peso | Descrição |
|-------------|------|-----------|
| **Idade** | 60% (= 3% do total) | Crianças (<5 anos) e idosos (>60 anos) têm maior risco. Normalização em curva U. |
| **Comorbidades** | 40% (= 2% do total) | Ainda não implementado (dados não disponíveis na base). |

### 8.4 Cálculo do Score Final

#### Fórmula AHP:

```
Score_Final = (Score_Gravidade × 0.50) + 
              (Score_Sintomas × 0.30) + 
              (Score_Epidemiologia × 0.15) + 
              (Score_Sociodemografico × 0.05)
```

Onde cada `Score_Critério` é calculado somando os subcritérios ponderados.

#### Exemplo de Cálculo:

**Caso Hipotético:**
- 3 sinais de alarme presentes
- 1 sinal de gravidade presente
- 5 sintomas clássicos
- 2 sintomas inespecíficos
- Incidência: 200 casos/100k hab
- Semana epidemiológica: 15
- Idade: 65 anos

**Passo 1: Normalizar dados**
- Alarmes: 3/9 = 0.333
- Gravidade: 1/14 = 0.071
- Sintomas clássicos: 5/6 = 0.833
- Sintomas inespecíficos: 2/7 = 0.286
- Incidência: 200/500 = 0.400
- Semana: 0.9 (pico)
- Idade: 0.75 (idoso)

**Passo 2: Calcular scores por critério**

```
Score_Gravidade = (0.333 × 0.60) + (0.071 × 0.40) = 0.228

Score_Sintomas = (0.833 × 0.70) + (0.286 × 0.30) = 0.669

Score_Epidemiologia = (0.400 × 0.50) + (0.05 × 0.30) + (0.9 × 0.20) = 0.395

Score_Sociodemografico = (0.75 × 0.60) + (0 × 0.40) = 0.450
```

**Passo 3: Score final AHP**

```
Score_Final = (0.228 × 0.50) + (0.669 × 0.30) + (0.395 × 0.15) + (0.450 × 0.05)
            = 0.114 + 0.201 + 0.059 + 0.023
            = 0.397
```

**Resultado:** Score de 0.397 = **Risco Médio** (faixa 0.34 - 0.66)

### 8.5 Classificação de Risco

| Nível | Faixa de Score | Cor | Ação Recomendada |
|-------|----------------|-----|------------------|
| **Baixo** | 0.00 - 0.33 | 🟢 Verde | Acompanhamento ambulatorial, hidratação oral, orientação sobre sinais de alarme |
| **Médio** | 0.34 - 0.66 | 🟠 Laranja | Observação em unidade de saúde por 24h, hidratação vigorosa, reavaliação periódica |
| **Alto** | 0.67 - 1.00 | 🔴 Vermelho | Internação hospitalar imediata, monitoramento contínuo, avaliar UTI |

---

## 🔍 9. FUNÇÕES DE ANÁLISE

O sistema implementa três tipos principais de análises de dados, cada uma com objetivos específicos:

### 9.1 Classificação

#### 9.1.1 Objetivo
Categorizar pacientes em níveis de risco e identificar fatores críticos que contribuem para a classificação.

#### 9.1.2 Implementação

**Serviço:** `ClassificadorRiscoService.php`

```php
public function classificar(float $scoreFinal, array $detalhesCalculo): array
{
    $nivel = $this->determinarNivel($scoreFinal);
    $justificativa = $this->gerarJustificativa($scoreFinal, $nivel, $detalhesCalculo);
    $recomendacoes = $this->gerarRecomendacoes($nivel, $detalhesCalculo);

    return [
        'nivel_risco' => $nivel['label'],
        'score_final' => round($scoreFinal, 4),
        'cor' => $nivel['cor'],
        'justificativa' => $justificativa,
        'recomendacoes' => $recomendacoes,
        'fatores_criticos' => $this->identificarFatoresCriticos($detalhesCalculo),
    ];
}
```

#### 9.1.3 Outputs

1. **Nível de Risco**: Baixo / Médio / Alto
2. **Justificativa Explicável**:
   - "Score final de risco: 39.7%. Classificação: Risco Médio."
   - "Fatores contribuintes: 3 sinal(is) de alarme detectado(s); 5 sintomas clássicos de dengue; Alta incidência no município (200 casos/100k hab)."

3. **Recomendações Clínicas Personalizadas**:
   - "Observação em unidade de saúde por no mínimo 24h"
   - "Hidratação oral vigorosa"
   - "Reavaliar sinais de alarme a cada 4-6 horas"

4. **Fatores Críticos Identificados**:
   ```json
   [
     {"criterio": "Sintomas", "score": 0.669, "nivel": "Alto"},
     {"criterio": "Epidemiologia", "score": 0.395, "nivel": "Médio"}
   ]
   ```

### 9.2 Sumarização

#### 9.2.1 Objetivo
Fornecer estatísticas descritivas e agregações dos dados epidemiológicos para análise de tendências e padrões.

#### 9.2.2 Implementação

**Serviço:** `SumarizacaoService.php`

```php
public function estatisticasGerais(): array
{
    $mediaIdadeCodificada = Dengue2025::whereNotNull('NU_IDADE_N')
        ->where('NU_IDADE_N', '>=', 4000)
        ->where('NU_IDADE_N', '<', 5000)
        ->avg('NU_IDADE_N');
    
    $mediaIdade = $mediaIdadeCodificada ? round($mediaIdadeCodificada - 4000, 1) : 0;
    
    return [
        'total_casos' => Dengue2025::count(),
        'casos_confirmados' => Dengue2025::confirmados()->count(),
        'casos_graves' => Dengue2025::graves()->count(),
        'casos_com_alarme' => Dengue2025::comAlarme()->count(),
        'media_idade' => $mediaIdade,
        'distribuicao_sexo' => $this->distribuicaoSexo(),
    ];
}
```

#### 9.2.3 Tipos de Sumarizações

1. **Estatísticas Gerais**
   - Total de casos notificados
   - Casos confirmados vs. descartados
   - Casos graves e com sinais de alarme
   - Média de idade dos pacientes

2. **Distribuição Geográfica**
   - Casos por Unidade Federativa (UF)
   - Casos por município
   - Top 50 municípios com maior incidência

3. **Distribuição Temporal**
   - Casos por semana epidemiológica
   - Identificação de períodos de pico
   - Sazonalidade da doença

4. **Distribuição de Sintomas**
   - Frequência de cada sintoma clássico
   - Frequência de sinais de alarme
   - Frequência de sinais de gravidade

#### 9.2.4 Exemplo de Output

```json
{
  "total_casos": 45782,
  "casos_confirmados": 38654,
  "casos_graves": 1234,
  "casos_com_alarme": 5678,
  "media_idade": 34.5,
  "distribuicao_sexo": {
    "F": 24890,
    "M": 20892
  },
  "casos_por_uf": [
    {"UF": "SP", "total": 15234},
    {"UF": "RJ", "total": 8901},
    {"UF": "MG", "total": 6543}
  ]
}
```

### 9.3 Regressão

#### 9.3.1 Objetivo
Analisar tendências temporais, calcular correlações entre variáveis e prever casos futuros baseado em dados históricos.

#### 9.3.2 Implementação

**Serviço:** `RegressaoService.php`

```php
public function regressaoLinearTemporal(?string $municipio = null, ?string $uf = null): array
{
    $query = Dengue2025::select('SEM_PRI', DB::raw('COUNT(*) as casos'))
        ->whereNotNull('SEM_PRI')
        ->groupBy('SEM_PRI')
        ->orderBy('SEM_PRI');

    if ($municipio) {
        $query->where('MUNICIPIO', $municipio);
    }

    if ($uf) {
        $query->where('UF', $uf);
    }

    $dados = $query->get();
    
    // Preparar dados para regressão
    $x = $dados->pluck('SEM_PRI')->toArray();
    $y = $dados->pluck('casos')->toArray();

    $resultado = $this->calcularRegressaoLinear($x, $y);
    $resultado['tendencia'] = $this->interpretarTendencia($resultado['coeficiente_angular']);

    return $resultado;
}
```

#### 9.3.3 Tipos de Análises de Regressão

1. **Regressão Linear Temporal**
   - Análise de tendência de casos ao longo das semanas epidemiológicas
   - Cálculo de coeficiente angular (crescimento/decrescimento)
   - R² para avaliar qualidade do ajuste
   - Interpretação: "Crescente", "Estável", "Decrescente"

2. **Previsão de Casos Futuros**
   ```php
   public function preverCasosFuturos(int $semanasAFrente = 4): array
   {
       $regressao = $this->regressaoLinearTemporal($municipio, $uf);
       $ultimaSemana = Dengue2025::max('SEM_PRI');
       
       $previsoes = [];
       for ($i = 1; $i <= $semanasAFrente; $i++) {
           $semanaFutura = $ultimaSemana + $i;
           $casosPrevistos = $regressao['intercepto'] + 
                            ($regressao['coeficiente_angular'] * $semanaFutura);
           
           $previsoes[] = [
               'semana' => $semanaFutura,
               'casos_previstos' => max(0, round($casosPrevistos))
           ];
       }
       
       return [
           'previsoes' => $previsoes,
           'confiabilidade' => $this->avaliarConfiabilidade($regressao['r_squared']),
           'r_squared' => $regressao['r_squared'],
           'tendencia' => $regressao['tendencia']
       ];
   }
   ```

3. **Correlação entre Variáveis**
   - **Sintomas × Gravidade**: Mede se maior número de sintomas correlaciona com maior gravidade
   - **Alarmes × Gravidade**: Avalia se presença de sinais de alarme prediz evolução para quadro grave
   - Cálculo de **Coeficiente de Pearson** (r)
   - Interpretação:
     - |r| < 0.3: Correlação fraca
     - 0.3 ≤ |r| < 0.7: Correlação moderada
     - |r| ≥ 0.7: Correlação forte

#### 9.3.4 Fórmulas Matemáticas

**Regressão Linear (y = ax + b):**

```
a (coeficiente angular) = [n·Σ(xy) - Σx·Σy] / [n·Σ(x²) - (Σx)²]

b (intercepto) = [Σy - a·Σx] / n

R² = 1 - [Σ(y - ŷ)²] / [Σ(y - ȳ)²]
```

**Correlação de Pearson:**

```
r = [n·Σ(xy) - Σx·Σy] / √{[n·Σ(x²) - (Σx)²] · [n·Σ(y²) - (Σy)²]}
```

#### 9.3.5 Exemplo de Output

```json
{
  "coeficiente_angular": 12.5,
  "intercepto": 150.2,
  "r_squared": 0.78,
  "tendencia": "Crescente",
  "previsoes": [
    {"semana": 21, "casos_previstos": 412},
    {"semana": 22, "casos_previstos": 425},
    {"semana": 23, "casos_previstos": 437},
    {"semana": 24, "casos_previstos": 450}
  ],
  "confiabilidade": "Alta",
  "interpretacao": "A tendência é de crescimento de 12.5 casos por semana"
}
```

### 9.4 Integração das Análises no Sistema

As três funções trabalham de forma complementar:

1. **Classificação** → Decisão individual por paciente
2. **Sumarização** → Contexto epidemiológico para alimentar o critério "Epidemiologia" do AHP
3. **Regressão** → Previsão de demanda futura para planejamento de recursos

---

## 📝 10. FORMULÁRIO DE AVALIAÇÃO DE RISCO

### 10.1 Visão Geral

O formulário é o ponto de entrada principal do sistema, estruturado em **6 etapas (stepper)** para facilitar a coleta organizada de informações clínicas e epidemiológicas.

**Componente:** `FormAvaliacaoRisco.vue` (658 linhas)

### 10.2 Estrutura em Etapas

#### **Etapa 1: Dados do Paciente**
🎯 **Objetivo:** Coletar informações sociodemográficas básicas

**Campos:**
- **Idade** (obrigatório)
  - Tipo: Número
  - Validação: 0-120 anos
  - Peso no AHP: 3% (60% do critério Sociodemográfico de 5%)
  
- **Sexo** (obrigatório)
  - Opções: Masculino / Feminino / Ignorado
  - Uso: Estatísticas epidemiológicas
  
- **UF** (obrigatório)
  - Lista: 27 unidades federativas
  
- **Município** (obrigatório)
  - Texto livre
  - Uso: Análise de incidência local

**Decisões de Design:**
- Campos simples e rápidos de preencher
- Validação em tempo real
- Ícones visuais para facilitar identificação

---

#### **Etapa 2: Dados Epidemiológicos**
🎯 **Objetivo:** Capturar contexto epidemiológico local

**Campos:**
- **Casos no Município** (obrigatório)
  - Tipo: Número formatado (com separadores de milhar)
  - Peso no AHP: 7.5% (50% de 15%)
  - Cálculo automático: Incidência por 100k habitantes
  
- **População do Município** (obrigatório)
  - Tipo: Número formatado
  - Uso: Base para cálculo de incidência
  
- **Semana Epidemiológica** (obrigatório)
  - Seletor: Semanas 1-53
  - Peso no AHP: 3% (20% de 15%)
  - Lógica: Semanas 10-25 recebem score mais alto (pico epidêmico)

**Funcionalidades Especiais:**
- **Formatação automática**: Números grandes são exibidos com pontos (ex: 1.234.567)
- **Cálculo de incidência em tempo real**: Exibido ao usuário para validação
- **Alert informativo**: Explica a importância dos dados epidemiológicos

**Decisões de Design:**
- Separar campo formatado (exibição) do campo real (valor numérico)
- Mostrar incidência calculada para dar feedback ao usuário
- Usar alert para educar sobre relevância dos dados (peso: 15%)

---

#### **Etapa 3: Sintomas Clínicos**
🎯 **Objetivo:** Identificar sintomas presentes no paciente

**Peso no AHP:** 30% do score final

**Seção 1: Sintomas Clássicos de Dengue** (21% do total)
- Febre ⚠️ (destaque vermelho - sintoma cardinal)
- Cefaleia (dor de cabeça)
- Mialgia (dor muscular)
- Artralgia (dor nas articulações)
- Dor retroorbital (atrás dos olhos)
- Exantema (manchas vermelhas na pele)

**Componente:** `v-checkbox` (seleção múltipla)

**Seção 2: Sintomas Inespecíficos** (9% do total)
- Náusea
- Vômito
- Dor nas costas
- Conjuntivite
- Petéquias (pequenos pontos vermelhos)
- Leucopenia (baixa de glóbulos brancos)
- Prova do laço positiva

**Componente:** `v-switch` (alternância visual diferente)

**Indicador:** Contador de sintomas selecionados em tempo real

**Decisões de Design:**
- **Separação visual clara** entre sintomas clássicos (mais importantes) e inespecíficos
- **Cores diferenciadas**: Vermelho para febre (sintoma crítico), azul para demais clássicos, laranja para inespecíficos
- **Componentes diferentes** (checkbox vs switch) para diferenciar grupos
- **Contador dinâmico** no título da etapa: "X sintomas selecionados"

---

#### **Etapa 4: Sinais de Alarme**
🎯 **Objetivo:** Detectar sinais de evolução para dengue grave

**Peso no AHP:** 30% do score final (60% do critério Gravidade de 50%)

**9 Sinais de Alarme:**
1. Hipotensão postural
2. Plaquetopenia (< 50.000/mm³)
3. Vômitos persistentes
4. Sangramento de mucosas
5. Aumento do hematócrito
6. Dor abdominal intensa e contínua
7. Letargia ou irritabilidade
8. Hepatomegalia dolorosa (fígado aumentado)
9. Acúmulo de líquidos (ascite, derrame)

**Componente:** `v-checkbox` com destaque visual em vermelho

**Alertas:**
- Se qualquer alarme selecionado → Alert vermelho: "⚠️ ATENÇÃO: X sinal(is) de alarme detectado(s)!"
- Cor da etapa muda para laranja no stepper

**Decisões de Design:**
- **Máxima visibilidade**: Fundo laranja claro, ícones de alerta
- **Feedback imediato**: Alert aparece assim que um alarme é marcado
- **Destaque no título**: Se houver alarmes, o título da etapa fica laranja
- **Labels em negrito e vermelho** quando selecionados
- **Educação contextual**: Texto explica que são 60% do critério Gravidade

---

#### **Etapa 5: Sinais de Gravidade**
🎯 **Objetivo:** Identificar dengue grave que requer internação urgente

**Peso no AHP:** 20% do score final (40% do critério Gravidade de 50%)

**14 Sinais de Gravidade:**
1. Pulso filiforme (fraco)
2. Convulsões
3. Enchimento capilar lento (> 2 segundos)
4. Insuficiência respiratória
5. Extremidades frias (choque)
6. Hipotensão arterial
7. Hematócrito muito elevado
8. Melena (sangue nas fezes)
9. Metrorragia (sangramento vaginal intenso)
10. Sangramento grave
11. AST/ALT > 1000 (lesão hepática severa)
12. Miocardite
13. Alteração de consciência
14. Falência de órgãos

**Componente:** `v-checkbox` com destaque máximo em vermelho intenso

**Alertas:**
- Se qualquer sinal presente → Alert vermelho escuro: "🔴 CRÍTICO: X sinal(is) de gravidade presente(s)!"
- Cor da etapa muda para vermelho no stepper

**Decisões de Design:**
- **Máxima urgência visual**: Fundo vermelho claro, ícones críticos
- **Alerta crítico imediato**: Mensagem de urgência médica
- **Stepper vermelho**: Indica gravidade no visual geral
- **Tipografia forte**: Negrito e vermelho quando selecionado
- **Educação**: Texto reforça necessidade de atenção médica imediata

---

#### **Etapa 6: Revisão e Envio**
🎯 **Objetivo:** Validar dados antes do cálculo e submeter avaliação

**Componentes:**

1. **Resumo em Tabela**
   - Paciente: Idade, Sexo
   - Localização: Município/UF
   - Semana epidemiológica
   - Total de casos no município
   - Sintomas selecionados (contador)
   - **Sinais de alarme** (laranja, negrito)
   - **Sinais de gravidade** (vermelho, negrito)

2. **Botões de Ação**
   - **Voltar**: Retorna à Etapa 5
   - **Limpar**: Reseta todo o formulário (confirmação)
   - **Calcular Risco** (botão verde, grande):
     - Validação completa do formulário
     - Loading spinner durante processamento
     - Desabilitado se formulário inválido

**Decisões de Design:**
- **Revisão obrigatória**: Usuário vê resumo antes de enviar
- **Cores semânticas**: Laranja para alarmes, vermelho para gravidade
- **Botão proeminente**: "Calcular Risco" é o call-to-action principal
- **Estado de loading**: Feedback visual durante processamento
- **Validação final**: Impede envio de dados incompletos

---

### 10.3 Validações Implementadas

```javascript
rules: {
  required: v => !!v || 'Campo obrigatório',
  requiredNumber: num => () => (num !== null && num !== undefined) || 'Campo obrigatório',
  idade: v => (v >= 0 && v <= 120) || 'Idade deve estar entre 0 e 120',
  nonNegativeNumber: num => () => (num !== null && num >= 0) || 'Valor não pode ser negativo',
  positiveNumber: num => () => (num !== null && num > 0) || 'Valor deve ser maior que zero'
}
```

### 10.4 Computed Properties (Cálculos Reativos)

```javascript
computed: {
  // Incidência calculada automaticamente
  incidenciaCalculada() {
    if (this.form.casos_municipio && this.form.populacao_municipio) {
      return ((this.form.casos_municipio / this.form.populacao_municipio) * 100000).toFixed(2);
    }
    return null;
  },

  // Contadores dinâmicos
  sintomasSelecionados() {
    return [...this.sintomasClassicos, ...this.sintomasInespecificos]
      .filter(s => this.form[s.campo]).length;
  },

  alarmesSelecionados() {
    return this.sinaisAlarme.filter(a => this.form[a.campo]).length;
  },

  gravidadeSelecionados() {
    return this.sinaisGravidade.filter(g => this.form[g.campo]).length;
  }
}
```

### 10.5 Decisões Críticas de UX/UI

#### 10.5.1 Por que Stepper Vertical?
- **Progressão clara**: Usuário vê onde está e o que falta
- **Navegação livre**: Pode voltar a qualquer etapa editável
- **Redução de sobrecarga cognitiva**: Foco em um grupo de campos por vez
- **Indicadores visuais**: Cores e ícones sinalizam criticidade

#### 10.5.2 Formatação de Números Grandes
```javascript
formatarCasosMunicipio(value) {
  const numeros = String(value || '').replace(/\D/g, '');
  this.form.casos_municipio = numeros ? parseInt(numeros) : null;
  this.casosMunicipioFormatted = numeros ? parseInt(numeros).toLocaleString('pt-BR') : '';
}
```
- **Experiência do usuário**: Mais fácil ler "1.234.567" que "1234567"
- **Validação interna**: Armazena número puro para cálculos
- **Exibição externa**: Mostra valor formatado

#### 10.5.3 Feedback Visual Imediato
- **Alerts contextuais**: Aparecem quando alarmes/gravidade são detectados
- **Contadores dinâmicos**: Atualizam em tempo real
- **Cores semânticas**:
  - Verde: Informação / Sucesso
  - Laranja: Atenção / Alarmes
  - Vermelho: Crítico / Gravidade
  - Azul: Informativo / Neutro

#### 10.5.4 Educação do Usuário
Cada etapa possui um **alert informativo** explicando:
- Qual o peso daquele critério no AHP
- Por que aqueles dados são importantes
- Como serão usados no cálculo

**Exemplo:**
> "ℹ️ Estas informações são essenciais para o cálculo do risco epidemiológico (peso: 15%)"

### 10.6 Fluxo de Submissão

```javascript
async calcularRisco() {
  // 1. Validação final
  if (!this.$refs.form.validate()) {
    this.mostrarMensagem('Por favor, preencha todos os campos obrigatórios', 'error');
    return;
  }

  // 2. Estado de loading
  this.loading = true;

  try {
    // 3. Chamada à API
    const resultado = await this.avaliarRisco(this.form);

    // 4. Feedback de sucesso
    this.mostrarMensagem('Avaliação realizada com sucesso!', 'success');

    // 5. Navegação para resultado
    // (implementação varia por projeto)

  } catch (erro) {
    // 6. Tratamento de erro
    this.mostrarMensagem('Erro ao calcular risco: ' + erro.message, 'error');
  } finally {
    // 7. Reset do loading
    this.loading = false;
  }
}
```

---

## 📊 11. ARQUITETURA DO SISTEMA

### 11.1 Diagrama de Componentes

```
┌───────────────────────────────────────────────────────────────┐
│                         FRONTEND (Vue.js)                      │
├───────────────────────────────────────────────────────────────┤
│  ┌──────────────────┐  ┌──────────────────┐  ┌─────────────┐ │
│  │ FormAvaliacaoRisco│  │   Dashboard      │  │  Relatórios  │ │
│  │   (6 Etapas)      │  │   (Gráficos)     │  │   (Análises) │ │
│  └──────────────────┘  └──────────────────┘  └─────────────┘ │
│                                                                 │
│  ┌──────────────────────────────────────────────────────────┐ │
│  │              Vuex Store (Estado Global)                   │ │
│  └──────────────────────────────────────────────────────────┘ │
│                              ▼                                  │
│  ┌──────────────────────────────────────────────────────────┐ │
│  │               Axios (Cliente HTTP)                        │ │
│  └──────────────────────────────────────────────────────────┘ │
└────────────────────────────────┬──────────────────────────────┘
                                 │ HTTP/JSON
                                 ▼
┌───────────────────────────────────────────────────────────────┐
│                        BACKEND (Laravel)                       │
├───────────────────────────────────────────────────────────────┤
│  ┌──────────────────────────────────────────────────────────┐ │
│  │                   Controllers (API)                       │ │
│  │  ┌────────────────┐  ┌────────────────┐  ┌────────────┐ │ │
│  │  │ RiscoController│  │   Sumarizacao  │  │  Análise   │ │ │
│  │  └────────────────┘  └────────────────┘  └────────────┘ │ │
│  └──────────────────────────────────────────────────────────┘ │
│                              ▼                                  │
│  ┌──────────────────────────────────────────────────────────┐ │
│  │                        Services                           │ │
│  │  ┌────────────┐  ┌────────────┐  ┌──────────────────┐  │ │
│  │  │ AHP/       │  │Classificador│  │   Regressao      │  │ │
│  │  │Calculadora │  │   Service   │  │    Service       │  │ │
│  │  └────────────┘  └────────────┘  └──────────────────┘  │ │
│  │  ┌──────────────────────────────────────────────────┐  │ │
│  │  │           Sumarizacao Service                     │  │ │
│  │  └──────────────────────────────────────────────────┘  │ │
│  └──────────────────────────────────────────────────────────┘ │
│                              ▼                                  │
│  ┌──────────────────────────────────────────────────────────┐ │
│  │                    Models (Eloquent)                      │ │
│  │                     Dengue2025.php                        │ │
│  └──────────────────────────────────────────────────────────┘ │
└────────────────────────────────┬──────────────────────────────┘
                                 │ SQL
                                 ▼
┌───────────────────────────────────────────────────────────────┐
│                    DATABASE (SQLite)                           │
│                     dengue_2025.sqlite                         │
└───────────────────────────────────────────────────────────────┘
```

### 11.2 Fluxo de Dados - Avaliação de Risco

```
1. USUÁRIO preenche formulário (6 etapas)
          ▼
2. FRONTEND valida dados localmente
          ▼
3. AXIOS envia POST /api/avaliar-risco
          ▼
4. RiscoController recebe request
          ▼
5. Normalizador processa dados brutos
          ▼
6. CalculadoraAHP calcula scores por critério
          ▼
7. ClassificadorRiscoService determina nível de risco
          ▼
8. Gera justificativa e recomendações
          ▼
9. RESPONSE JSON retorna ao frontend
          ▼
10. FRONTEND exibe resultado com cores, gráficos, recomendações
```

---

## 📸 12. ESPAÇOS PARA IMAGENS NA APRESENTAÇÃO

### 12.1 Slide: Contexto da Dengue
**Imagens sugeridas:**
- Mapa do Brasil com incidência de dengue por estado
- Gráfico de casos de dengue nos últimos 5 anos
- Foto de mosquito Aedes aegypti
- Infográfico sobre o ciclo de transmissão

### 12.2 Slide: Problema de Decisão
**Imagens sugeridas:**
- Foto de profissional de saúde atendendo paciente
- Fluxograma de decisão clínica tradicional vs. SAD
- Imagem de fila em posto de saúde (sobrecarga)

### 12.3 Slide: Stack Tecnológica
**Imagens sugeridas:**
- Logos das tecnologias (Vue.js, Laravel, Docker)
- Screenshot da arquitetura de containers
- Diagrama de arquitetura do sistema

### 12.4 Slide: Formulário de Avaliação
**Imagens sugeridas (PRINTS DO SISTEMA):**
- Screenshot do formulário - Etapa 1 (Dados do Paciente)
- Screenshot do formulário - Etapa 3 (Sintomas com contadores)
- Screenshot do formulário - Etapa 4 (Sinais de Alarme com alert vermelho)
- Screenshot do formulário - Etapa 6 (Revisão com tabela de resumo)

### 12.5 Slide: Método AHP
**Imagens sugeridas:**
- Diagrama da hierarquia de critérios (árvore de decisão)
- Gráfico de pizza com pesos dos critérios (50%, 30%, 15%, 5%)
- Infográfico explicando o cálculo do score

### 12.6 Slide: Resultado da Avaliação
**Imagens sugeridas (PRINTS DO SISTEMA):**
- Screenshot de resultado "Risco Baixo" (verde)
- Screenshot de resultado "Risco Alto" (vermelho) com recomendações
- Screenshot de justificativa explicável
- Gráfico radar mostrando scores por critério

### 12.7 Slide: Dashboard e Análises
**Imagens sugeridas (PRINTS DO SISTEMA):**
- Screenshot do dashboard com gráficos de incidência
- Screenshot de análise de sumarização (estatísticas gerais)
- Screenshot de gráfico de regressão (tendência temporal)
- Mapa de calor de casos por município

### 12.8 Slide: Base de Dados
**Imagens sugeridas:**
- Screenshot do arquivo CSV original
- Screenshot do processo de conversão para SQLite
- Diagrama do esquema de tabelas
- Gráfico comparativo antes/depois da limpeza de dados

### 12.9 Slide: Arquitetura Docker
**Imagens sugeridas:**
- Diagrama dos containers (app-api, app-front)
- Screenshot do docker-compose.yml
- Screenshot de containers rodando (docker ps)
- Screenshot da aplicação rodando em localhost:8070

---

## 📊 13. RESULTADOS E IMPACTOS ESPERADOS

### 13.1 Benefícios Clínicos

- **Triagem mais rápida**: Redução de tempo de avaliação inicial
- **Padronização de decisões**: Critérios objetivos e consistentes
- **Redução de mortalidade**: Identificação precoce de casos graves
- **Otimização de recursos**: Internação apenas para casos de risco médio/alto

### 13.2 Benefícios Epidemiológicos

- **Monitoramento em tempo real**: Dashboards de incidência
- **Previsão de surtos**: Análises de regressão para antecipar picos
- **Identificação de padrões**: Correlações entre sintomas e gravidade
- **Planejamento de campanhas**: Baseado em dados regionais

### 13.3 Benefícios Tecnológicos

- **Sistema escalável**: Arquitetura Docker facilita deploy em múltiplas unidades
- **Manutenção facilitada**: Código modular e bem documentado
- **Custo baixo**: Stack open-source, sem licenças proprietárias
- **Portabilidade**: SQLite permite uso offline ou em áreas remotas

---

## 🔮 14. TRABALHOS FUTUROS

### 14.1 Melhorias Técnicas

- Integração com sistemas DATASUS/SINAN para importação automática de dados
- Implementação de autenticação OAuth2 com Laravel Passport
- Módulo de Machine Learning para refinamento dos pesos do AHP
- API para integração com prontuários eletrônicos

### 14.2 Funcionalidades Adicionais

- Módulo de comorbidades (diabetes, hipertensão, gravidez)
- Geolocalização automática para dados epidemiológicos
- Notificações push para alertas de surtos
- Exportação de relatórios em PDF

### 14.3 Validação Clínica

- Estudo retrospectivo comparando decisões do SAD com outcomes reais
- Ajuste de pesos do AHP baseado em validação empírica
- Testes em unidades de saúde piloto

---

## 📚 15. REFERÊNCIAS

### 15.1 Metodologia AHP
- Saaty, T. L. (1980). *The Analytic Hierarchy Process*. McGraw-Hill.
- Saaty, T. L. (2008). *Decision making with the analytic hierarchy process*. International Journal of Services Sciences, 1(1), 83-98.

### 15.2 Diretrizes Clínicas
- Ministério da Saúde do Brasil. (2023). *Dengue: Diagnóstico e Manejo Clínico - Adulto e Criança*.
- OMS/WHO. (2009). *Dengue: Guidelines for Diagnosis, Treatment, Prevention and Control*.

### 15.3 Dados Epidemiológicos
- SINAN - Sistema de Informação de Agravos de Notificação
- DATASUS - Departamento de Informática do SUS
- InfoDengue - Sistema de alerta precoce

---

## 🎓 16. AUTORES E CRÉDITOS

**Projeto desenvolvido como parte da disciplina:**
- **Curso**: [Nome do Curso]
- **Disciplina**: ADMF01-2025.2
- **Instituição**: [Nome da Instituição]
- **Professor(a)**: [Nome do Professor]

**Equipe de Desenvolvimento:**
- [Nomes dos integrantes]

**Agradecimentos:**
- Ministério da Saúde pela disponibilização dos dados do SINAN
- Comunidade open-source das tecnologias utilizadas

---

## 📞 17. CONTATO

**Para mais informações sobre o projeto:**
- Email: [seu_email@exemplo.com]
- GitHub: [link do repositório]
- Documentação completa: [link da documentação]

---

**Última atualização:** 15 de dezembro de 2025  
**Versão do documento:** 1.0  
**Status do projeto:** ✅ Funcional e pronto para demonstração

---

