# Correção de Models, Tabelas e Referências – SAD Dengue (Laravel 7 + SQLite)

Este documento fornece instruções ao GitHub Copilot para padronizar models, corrigir referências de tabelas e ajustar migrations do projeto SAD Dengue.  
Ele deve ser utilizado para garantir que o Copilot gere código consistente com a arquitetura adotada.

---

# 1. Banco de Dados – SQLite Único

O backend usa o arquivo SQLite:

database/sad_dengue.sqlite

css
Copiar código

A tabela principal epidemiológica é:

dengue_2025

yaml
Copiar código

Características:
- Cada linha representa um caso de dengue
- Coluna `id INTEGER PRIMARY KEY AUTOINCREMENT`
- Contém todas as variáveis demográficas, clínicas, epidemiológicas e derivadas
- Nenhuma model deve tentar recriar ou remover essa tabela
- Não deve existir migration para `dengue_2025`

Essa tabela é **somente leitura** para o sistema (exceto quando importando novos casos, se o recurso existir).

---

# 2. Múltiplas Models para a Mesma Tabela

O sistema usa DDD (Domain-Driven Design).  
Por isso, diferentes models representam diferentes perspectivas da mesma tabela `dengue_2025`.

Para todas as models ligadas a esse domínio, Copilot deve garantir que:

```php
protected $table = 'dengue_2025';
public $timestamps = false;
Copilot deve corrigir automaticamente qualquer model que use nomes de tabela incorretos.

As models que representam domínios diferentes (clínico, sintomatológico, epidemiológico, etc.) podem apontar para a mesma tabela, desde que sigam as regras acima.

3. Models Auxiliares com Tabelas Próprias
Além da tabela dengue_2025, o sistema possui models que precisam de suas próprias tabelas.
Essas sim devem ter migrations geradas pelo Copilot.

3.1 AvaliacaoRisco
Tabela:

nginx
Copiar código
avaliacoes_risco
Campos necessários:

id

input_json (text ou longtext)

score_epidemiologia (float)

score_gravidade (float)

score_sintomas (float)

score_sociodemografico (float)

score_final (float)

nivel_risco (string)

justificativa (text)

created_at / updated_at

3.2 AhpLog
Tabela:

nginx
Copiar código
ahp_logs
Campos necessários:

id

avaliacao_id (FK para avaliacoes_risco)

criterio (string)

subcriterio (string)

peso (float)

valor_normalizado (float)

score_parcial (float)

created_at / updated_at

Copilot deve gerar migrations completas para essas duas tabelas, caso não existam.

4. Erro Atual a ser Corrigido
Erro recebido:

pgsql
Copiar código
SQLSTATE[HY000]: General error: 1 no such table: avaliacoes_risco
Motivo:

A model AvaliacaoRisco existe

MAS a tabela avaliacoes_risco não existe no SQLite

A migration não foi criada ou não foi aplicada

Copilot deve:

Gerar migrations para avaliacoes_risco e ahp_logs

Certificar-se de que php artisan migrate cria essas tabelas no SQLite

Ajustar controllers e services para usar corretamente essas models

5. Correções que o Copilot Deve Aplicar
Copilot deve revisar e corrigir o código conforme:

5.1 Models epidemiológicas
Para todas as models que fazem leitura da base de dengue:

php
Copiar código
protected $table = 'dengue_2025';
public $timestamps = false;
5.2 Models auxiliares
AvaliacaoRisco → deve usar a tabela avaliacoes_risco

AhpLog → deve usar a tabela ahp_logs

5.3 Controllers
Controllers que escrevem dados (ex.: RiscoController) devem usar:

AvaliacaoRisco::create()

AhpLog::create()

E nunca tentar inserir registros em dengue_2025.

5.4 Adjustar referências
Copilot deve ajustar qualquer trecho de código contendo:

nomes incorretos de tabela

nomes de models desalinhadas

referencias Eloquent incorretas

6. Regras Importantes para o Copilot
Ao gerar ou editar código:

NÃO criar migration para dengue_2025

NÃO modificar a tabela dengue_2025

Sempre definir $table = 'dengue_2025' nas models epidemiológicas

Sempre criar migrations completas para:

avaliacoes_risco

ahp_logs

Garantir que controllers não acessem tabelas erradas

Garantir que AvaliacaoRisco::create() funcione

Garantir que AhpLog::create() funcione

Nunca excluir ou recriar o arquivo sad_dengue.sqlite

7. Objetivo para o GitHub Copilot
Com base neste contexto, o Copilot deve:

Corrigir models

Corrigir referência de tabela

Criar ou ajustar migrations para tabelas auxiliares

Ajustar controllers para usar as models corretas

Garantir integridade entre Services, Models e SQLite

Impedir o erro “SQLSTATE… no such table: avaliacoes_risco”

Manter a modelagem consistente com o design do SAD Dengue

Este arquivo contém todas as diretrizes necessárias para que o GitHub Copilot ajuste o projeto corretamente.

yaml
Copiar código

---

## ✔ Próximo passo se quiser
Posso gerar agora:

### ▶ **as migrations oficiais**  
para:

- `avaliacoes_risco`  
- `ahp_logs`  

### ▶ **as models corrigidas**  
`AvaliacaoRisco.php` e `AhpLog.php`

### ▶ **os controllers corrigidos**  
`RiscoController` já estruturado no padrão correto.

Só pedir.