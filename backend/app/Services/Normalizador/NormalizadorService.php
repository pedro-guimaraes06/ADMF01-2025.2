<?php

namespace App\Services\Normalizador;

/**
 * NormalizadorService
 * 
 * Responsável por padronizar, validar e normalizar dados de entrada
 * antes do processamento pelo método AHP.
 * 
 * Funções:
 * - Corrigir tipos de dados
 * - Validar ranges
 * - Preencher valores ausentes
 * - Normalizar valores para escala 0-1
 */
class NormalizadorService
{
    /**
     * Normaliza os dados de entrada completos
     *
     * @param array $dados
     * @return array
     */
    public function normalizar(array $dados): array
    {
        return [
            'demografico' => $this->normalizarDemografico($dados),
            'sintomas' => $this->normalizarSintomas($dados),
            'alarmes' => $this->normalizarAlarmes($dados),
            'gravidade' => $this->normalizarGravidade($dados),
            'epidemiologico' => $this->normalizarEpidemiologico($dados),
        ];
    }

    /**
     * Normaliza dados demográficos
     *
     * @param array $dados
     * @return array
     */
    protected function normalizarDemografico(array $dados): array
    {
        $idade = $this->garantirInteiro($dados['idade'] ?? 0);
        $idade = max(0, min(120, $idade)); // Clamp entre 0 e 120

        return [
            'idade' => $idade,
            'idade_normalizada' => $this->normalizarIdade($idade),
            'sexo' => strtoupper($dados['sexo'] ?? 'I'),
            'uf' => strtoupper($dados['uf'] ?? ''),
            'municipio' => $dados['municipio'] ?? '',
            'codigo_municipio' => $dados['codigo_municipio'] ?? null,
        ];
    }

    /**
     * Normaliza sintomas clínicos
     *
     * @param array $dados
     * @return array
     */
    protected function normalizarSintomas(array $dados): array
    {
        $config = config('ahp.sintomas_classicos');
        $configInesp = config('ahp.sintomas_inespecificos');
        
        $sintomas = [];
        $total = 0;

        // Sintomas clássicos
        foreach ($config as $sintoma) {
            $valor = $this->normalizarBinario($dados[strtolower($sintoma)] ?? 0);
            $sintomas[strtolower($sintoma)] = $valor;
            $total += $valor;
        }

        // Sintomas inespecíficos
        foreach ($configInesp as $sintoma) {
            $valor = $this->normalizarBinario($dados[strtolower($sintoma)] ?? 0);
            $sintomas[strtolower($sintoma)] = $valor;
            $total += $valor;
        }

        $sintomas['total'] = $total;
        $sintomas['total_normalizado'] = $this->escala($total, 0, config('ahp.normalizacao.sintomas_max'));

        return $sintomas;
    }

    /**
     * Normaliza sinais de alarme
     *
     * @param array $dados
     * @return array
     */
    protected function normalizarAlarmes(array $dados): array
    {
        $config = config('ahp.sinais_alarme');
        $alarmes = [];
        $total = 0;

        foreach ($config as $alarme) {
            $valor = $this->normalizarBinario($dados[strtolower($alarme)] ?? 0);
            $alarmes[strtolower($alarme)] = $valor;
            $total += $valor;
        }

        $alarmes['total'] = $total;
        $alarmes['total_normalizado'] = $this->escala($total, 0, config('ahp.normalizacao.alarmes_max'));
        $alarmes['tem_alarme'] = $total > 0;

        return $alarmes;
    }

    /**
     * Normaliza sinais de gravidade
     *
     * @param array $dados
     * @return array
     */
    protected function normalizarGravidade(array $dados): array
    {
        $config = config('ahp.sinais_gravidade');
        $gravidade = [];
        $total = 0;

        foreach ($config as $sinal) {
            $valor = $this->normalizarBinario($dados[strtolower($sinal)] ?? 0);
            $gravidade[strtolower($sinal)] = $valor;
            $total += $valor;
        }

        $gravidade['total'] = $total;
        $gravidade['total_normalizado'] = $this->escala($total, 0, config('ahp.normalizacao.gravidade_max'));
        $gravidade['tem_gravidade'] = $total > 0;

        return $gravidade;
    }

    /**
     * Normaliza dados epidemiológicos
     *
     * @param array $dados
     * @return array
     */
    protected function normalizarEpidemiologico(array $dados): array
    {
        return [
            'casos_municipio' => $this->garantirInteiro($dados['casos_municipio'] ?? 0),
            'populacao_municipio' => $this->garantirInteiro($dados['populacao_municipio'] ?? 100000),
            'incidencia' => $this->calcularIncidencia(
                $dados['casos_municipio'] ?? 0,
                $dados['populacao_municipio'] ?? 100000
            ),
            'semana_epidemiologica' => $this->garantirInteiro($dados['semana_epidemiologica'] ?? 1),
            'tendencia_temporal' => (float)($dados['tendencia_temporal'] ?? 0),
        ];
    }

    /**
     * Normaliza valor binário (0 ou 1)
     *
     * @param mixed $valor
     * @return int
     */
    protected function normalizarBinario($valor): int
    {
        if (is_bool($valor)) {
            return $valor ? 1 : 0;
        }
        
        if (is_numeric($valor)) {
            return (int)$valor > 0 ? 1 : 0;
        }

        $valor = strtolower(trim($valor));
        return in_array($valor, ['sim', 's', 'yes', 'y', '1', 'true']) ? 1 : 0;
    }

    /**
     * Normaliza idade para escala 0-1
     * Maior peso para idades extremas (crianças e idosos)
     *
     * @param int $idade
     * @return float
     */
    protected function normalizarIdade(int $idade): float
    {
        if ($idade <= 5 || $idade >= 60) {
            return 1.0; // Maior risco
        }
        
        if ($idade > 5 && $idade <= 15) {
            return 0.7; // Risco moderado-alto
        }
        
        if ($idade > 45 && $idade < 60) {
            return 0.6; // Risco moderado
        }
        
        return 0.3; // Adultos jovens - menor risco
    }

    /**
     * Calcula incidência por 100.000 habitantes
     *
     * @param int $casos
     * @param int $populacao
     * @return float
     */
    protected function calcularIncidencia(int $casos, int $populacao): float
    {
        if ($populacao == 0) {
            return 0.0;
        }
        
        return ($casos / $populacao) * 100000;
    }

    /**
     * Normaliza valor para escala 0-1
     *
     * @param float $valor
     * @param float $min
     * @param float $max
     * @return float
     */
    protected function escala(float $valor, float $min, float $max): float
    {
        if ($max == $min) {
            return 0.0;
        }
        
        $normalizado = ($valor - $min) / ($max - $min);
        return max(0.0, min(1.0, $normalizado)); // Clamp entre 0 e 1
    }

    /**
     * Garante que o valor seja um inteiro
     *
     * @param mixed $valor
     * @return int
     */
    protected function garantirInteiro($valor): int
    {
        return (int)$valor;
    }

    /**
     * Garante que o valor seja um float
     *
     * @param mixed $valor
     * @return float
     */
    protected function garantirFloat($valor): float
    {
        return (float)$valor;
    }
}
