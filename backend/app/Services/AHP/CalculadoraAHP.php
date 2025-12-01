<?php

namespace App\Services\AHP;

/**
 * CalculadoraAHP
 * 
 * Implementa o método AHP (Analytic Hierarchy Process) para cálculo
 * de risco de dengue baseado em múltiplos critérios hierárquicos.
 * 
 * Critérios e Pesos:
 * - Epidemiologia Municipal: 45%
 * - Gravidade Clínica: 35%
 * - Sintomas: 15%
 * - Sociodemográfico: 5%
 * 
 * Não acessa banco de dados.
 * Não grava logs (responsabilidade do controller).
 */
class CalculadoraAHP
{
    protected array $pesos;
    protected array $detalhes = [];

    public function __construct()
    {
        $this->pesos = config('ahp.pesos_criterios');
    }

    /**
     * Calcula o score final AHP
     *
     * @param array $dadosNormalizados
     * @return array
     */
    public function calcular(array $dadosNormalizados): array
    {
        $this->detalhes = [];

        $scoreEpidemiologia = $this->calcularEpidemiologia($dadosNormalizados['epidemiologico'] ?? []);
        $scoreGravidade = $this->calcularGravidade(
            $dadosNormalizados['alarmes'] ?? [],
            $dadosNormalizados['gravidade'] ?? []
        );
        $scoreSintomas = $this->calcularSintomas($dadosNormalizados['sintomas'] ?? []);
        $scoreSociodemografico = $this->calcularSociodemografico($dadosNormalizados['demografico'] ?? []);

        $scoreFinal = 
            ($scoreEpidemiologia * $this->pesos['epidemiologia']) +
            ($scoreGravidade * $this->pesos['gravidade']) +
            ($scoreSintomas * $this->pesos['sintomas']) +
            ($scoreSociodemografico * $this->pesos['sociodemografico']);

        return [
            'score_epidemiologia' => round($scoreEpidemiologia, 4),
            'score_gravidade' => round($scoreGravidade, 4),
            'score_sintomas' => round($scoreSintomas, 4),
            'score_sociodemografico' => round($scoreSociodemografico, 4),
            'score_final' => round($scoreFinal, 4),
            'detalhes' => $this->detalhes,
        ];
    }

    /**
     * Calcula score do critério Epidemiologia (45%)
     *
     * @param array $dados
     * @return float
     */
    protected function calcularEpidemiologia(array $dados): float
    {
        $subpesos = config('ahp.epidemiologia');
        
        // Normalizar incidência
        $incidencia = $dados['incidencia'] ?? 0;
        $incidenciaMax = config('ahp.normalizacao.incidencia_max');
        $incidenciaNorm = min(1.0, $incidencia / $incidenciaMax);

        // Tendência temporal (já vem normalizada ou calculada)
        $tendenciaNorm = abs($dados['tendencia_temporal'] ?? 0);
        $tendenciaNorm = min(1.0, $tendenciaNorm);

        // Semana epidemiológica (picos entre semanas 10-25)
        $semana = $dados['semana_epidemiologica'] ?? 1;
        $semanaNorm = $this->normalizarSemana($semana);

        $score = 
            ($incidenciaNorm * $subpesos['incidencia_municipal']) +
            ($tendenciaNorm * $subpesos['tendencia_temporal']) +
            ($semanaNorm * $subpesos['semana_epidemiologica']);

        $this->detalhes['epidemiologia'] = [
            'incidencia' => $incidencia,
            'incidencia_normalizada' => round($incidenciaNorm, 4),
            'tendencia_normalizada' => round($tendenciaNorm, 4),
            'semana' => $semana,
            'semana_normalizada' => round($semanaNorm, 4),
            'score' => round($score, 4),
        ];

        return $score;
    }

    /**
     * Calcula score do critério Gravidade Clínica (35%)
     *
     * @param array $alarmes
     * @param array $gravidade
     * @return float
     */
    protected function calcularGravidade(array $alarmes, array $gravidade): float
    {
        $subpesos = config('ahp.gravidade');

        $alarmesNorm = $alarmes['total_normalizado'] ?? 0;
        $gravidadeNorm = $gravidade['total_normalizado'] ?? 0;

        $score = 
            ($alarmesNorm * $subpesos['sinais_alarme']) +
            ($gravidadeNorm * $subpesos['sinais_gravidade']);

        $this->detalhes['gravidade'] = [
            'alarmes_total' => $alarmes['total'] ?? 0,
            'alarmes_normalizado' => round($alarmesNorm, 4),
            'gravidade_total' => $gravidade['total'] ?? 0,
            'gravidade_normalizado' => round($gravidadeNorm, 4),
            'score' => round($score, 4),
        ];

        return $score;
    }

    /**
     * Calcula score do critério Sintomas (15%)
     *
     * @param array $sintomas
     * @return float
     */
    protected function calcularSintomas(array $sintomas): float
    {
        $subpesos = config('ahp.sintomas');
        
        $sintomasClassicos = config('ahp.sintomas_classicos');
        $sintomasInesp = config('ahp.sintomas_inespecificos');

        // Contar sintomas clássicos presentes
        $totalClassicos = 0;
        foreach ($sintomasClassicos as $sint) {
            $totalClassicos += $sintomas[strtolower($sint)] ?? 0;
        }
        $classicosNorm = $totalClassicos / count($sintomasClassicos);

        // Contar sintomas inespecíficos
        $totalInesp = 0;
        foreach ($sintomasInesp as $sint) {
            $totalInesp += $sintomas[strtolower($sint)] ?? 0;
        }
        $inespNorm = $totalInesp / count($sintomasInesp);

        $score = 
            ($classicosNorm * $subpesos['sintomas_classicos']) +
            ($inespNorm * $subpesos['sintomas_inespecificos']);

        $this->detalhes['sintomas'] = [
            'classicos_total' => $totalClassicos,
            'classicos_normalizado' => round($classicosNorm, 4),
            'inespecificos_total' => $totalInesp,
            'inespecificos_normalizado' => round($inespNorm, 4),
            'score' => round($score, 4),
        ];

        return $score;
    }

    /**
     * Calcula score do critério Sociodemográfico (5%)
     *
     * @param array $demografico
     * @return float
     */
    protected function calcularSociodemografico(array $demografico): float
    {
        $subpesos = config('ahp.sociodemografico');

        $idadeNorm = $demografico['idade_normalizada'] ?? 0;
        $comorbidadesNorm = 0; // TODO: implementar quando disponível

        $score = 
            ($idadeNorm * $subpesos['idade']) +
            ($comorbidadesNorm * $subpesos['comorbidades']);

        $this->detalhes['sociodemografico'] = [
            'idade' => $demografico['idade'] ?? 0,
            'idade_normalizada' => round($idadeNorm, 4),
            'score' => round($score, 4),
        ];

        return $score;
    }

    /**
     * Normaliza semana epidemiológica
     * Maior risco entre semanas 10-25 (outono/inverno)
     *
     * @param int $semana
     * @return float
     */
    protected function normalizarSemana(int $semana): float
    {
        if ($semana >= 10 && $semana <= 25) {
            return 1.0; // Pico de transmissão
        }
        
        if ($semana >= 5 && $semana < 10) {
            return 0.7; // Período de aumento
        }
        
        if ($semana > 25 && $semana <= 30) {
            return 0.7; // Período de declínio
        }
        
        return 0.3; // Período de baixa transmissão
    }

    /**
     * Retorna os detalhes do cálculo
     *
     * @return array
     */
    public function getDetalhes(): array
    {
        return $this->detalhes;
    }
}
