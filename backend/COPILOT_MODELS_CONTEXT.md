# Contexto para Criação das Models – SAD Dengue (Laravel 7)

Este documento fornece ao GitHub Copilot instruções específicas sobre como gerar as models necessárias para o Sistema de Apoio à Decisão para análise de risco de dengue. Todas as orientações aqui devem ser refletidas no código que o Copilot gerar.

---

# 1. Banco de Dados Principal
O backend utiliza SQLite com um arquivo `sad_dengue.sqlite` contendo a tabela principal:

## Tabela: `dengue_2025`
- Cada linha corresponde a um caso individual de dengue.
- Coluna `id INTEGER PRIMARY KEY AUTOINCREMENT`.
- Contém dezenas de variáveis demográficas, clínicas, epidemiológicas e derivadas.
- Nenhuma coluna deve ser descartada.

Essa tabela é usada para:
- sumarizações,
- regressões,
- classificações,
- cálculo de scores AHP,
- análises exploratórias.

A model correspondente deve focar apenas em leitura e consultas específicas.

---

# 2. Models que devem ser criadas

O sistema utiliza uma abordagem DDD (Domain-Driven Design).  
Embora exista apenas uma tabela de dados epidemiológicos, diferentes models podem enxergar a tabela a partir de perspectivas distintas.

O Copilot deve gerar **somente estas models iniciais**:

---

## (A) Model Principal – Acesso à Tabela de Dados Epidemiológicos
### `Dengue2025`
- Representa *somente* a tabela `dengue_2025`.
- Deve usar `$table = 'dengue_2025'`.
- Não deve ter timestamps.
- Não implementa regras de negócio.
- Pode conter:
  - scopes de filtragem (UF, município, sintomas, gravidade)
  - casts básicos (inteiros, floats)
  - métodos auxiliares de leitura

---

## (B) Model de Registro das Avaliações do SAD
### `AvaliacaoRisco`
- Tabela: `avaliacoes_risco`
- Armazena o input e output de cada avaliação.
- Campos sugeridos:
  - `id`
  - `input_json`
  - `score_epidemiologia`
  - `score_gravidade`
  - `score_sintomas`
  - `score_sociodemografico`
  - `score_final`
  - `nivel_risco`
  - `created_at`
- Deve ser referenciada pelo controlador que processa o cálculo AHP.
- Não deve conter regras AHP dentro da model.

---

## (C) Model de Log Detalhado do Processo AHP
### `AhpLog`
- Tabela: `ahp_logs`
- Armazena detalhes do cálculo multicritério:
  - critério
  - subcritério
  - peso
  - valor normalizado
  - score parcial
  - id da avaliação (`avaliacao_id`)
- Relacionamento:
  - `AhpLog` pertence a `AvaliacaoRisco`.

---

## (D) Model opcional para base auxiliar
### `Municipio`
- Tabela: `municipios`
- Usada para enriquecer análises:
  - nome
  - UF
  - população
  - código IBGE
- Relacionamentos opcionais com consultas epidemiológicas.

---

# 3. Orientações Gerais para o Copilot ao Criar as Models

O Copilot deve seguir rigorosamente estas instruções:

### 3.1 Não colocar lógica de negócios nas models
Toda lógica deve ficar em:
- `/app/Services/Normalizador/`
- `/app/Services/AHP/`
- `/app/Services/Classificador/`

### 3.2 Models devem usar casts apropriados
- Colunas numéricas → `integer` ou `float`
- Datas → `datetime` ou `immutable_datetime`
- Variáveis binárias → `integer`

### 3.3 Models da tabela `dengue_2025` devem ser somente leitura
A tabela é grande e serve para análise epidemiológica.
Evitar mutações, exceto quando houver inserções explícitas de novos casos.

### 3.4 Criar Scopes (quando útil)
Exemplos:
- `scopePorUF($query, $uf)`
- `scopePorMunicipio($query, $id)`
- `scopeSemana($query, $semana)`
- `scopeConfirmados($query)`
- `scopeGraves($query)`

### 3.5 Relacionamentos
- `AvaliacaoRisco` hasMany `AhpLog`
- `AhpLog` belongsTo `AvaliacaoRisco`
- Models ligadas a `dengue_2025` geralmente **não têm relacionamentos** (tabela única de dados brutos)

### 3.6 Organização das Models
Cada model deve:
- ter `fillable` ou `guarded = []`
- documentar atributos com docblocks
- manter responsabilidades específicas por domínio
- evitar ser uma “God Model”

---

# 4. O Que o Copilot NÃO Deve Fazer
- Não gerar lógica AHP dentro das models.
- Não misturar lógica de normalização com lógica de persistência.
- Não criar models duplicadas sem propósito.
- Não remover colunas da tabela `dengue_2025`.
- Não fazer queries pesadas dentro de métodos estáticos.
- Não criar traits desnecessárias.

---

# 5. Objetivo Final das Models
Fornecer uma camada limpa e clara de acesso aos dados, permitindo que:

- os serviços façam cálculos AHP,
- os controladores respondam à API,
- o banco SQLite seja consultado eficientemente,
- o sistema registre avaliações e logs,
- as análises epidemiológicas sejam rápidas e organizadas.

---

Este arquivo serve de guia para o GitHub Copilot gerar models alinhadas à arquitetura e aos requisitos científicos do SAD Dengue.
