<?php

namespace App\Services\Classificador;

/**
 * ClassificadorRiscoService
 * 
 * Responsável por classificar o score final AHP em níveis de risco
 * e gerar justificativas explicativas para a decisão.
 * 
 * Níveis de Risco:
 * - Baixo: 0.00 - 0.33
 * - Médio: 0.34 - 0.66
 * - Alto: 0.67 - 1.00
 */
class ClassificadorRiscoService
{
    /**
     * Classifica o risco com base no score final
     *
     * @param float $scoreFinal
     * @param array $detalhesCalculo
     * @return array
     */
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

    /**
     * Determina o nível de risco baseado no score
     *
     * @param float $score
     * @return array
     */
    protected function determinarNivel(float $score): array
    {
        $niveis = config('ahp.niveis_risco');

        foreach ($niveis as $chave => $nivel) {
            if ($score >= $nivel['min'] && $score <= $nivel['max']) {
                return $nivel;
            }
        }

        return $niveis['baixo'];
    }

    /**
     * Gera justificativa detalhada da classificação
     *
     * @param float $score
     * @param array $nivel
     * @param array $detalhes
     * @return string
     */
    protected function gerarJustificativa(float $score, array $nivel, array $detalhes): string
    {
        $justificativa = "Score final de risco: " . round($score * 100, 2) . "%. ";
        $justificativa .= "Classificação: Risco {$nivel['label']}. ";

        // Análise por critério
        $criterios = [];

        // Epidemiologia
        if (isset($detalhes['epidemiologia'])) {
            $epi = $detalhes['epidemiologia'];
            if ($epi['incidencia'] > 100) {
                $criterios[] = "Alta incidência no município ({$epi['incidencia']} casos/100k hab)";
            }
            if ($epi['semana_normalizada'] > 0.7) {
                $criterios[] = "Período de pico epidemiológico (semana {$epi['semana']})";
            }
        }

        // Gravidade
        if (isset($detalhes['gravidade'])) {
            $grav = $detalhes['gravidade'];
            if ($grav['alarmes_total'] > 0) {
                $criterios[] = "{$grav['alarmes_total']} sinal(is) de alarme detectado(s)";
            }
            if ($grav['gravidade_total'] > 0) {
                $criterios[] = "{$grav['gravidade_total']} sinal(is) de gravidade presente(s)";
            }
        }

        // Sintomas
        if (isset($detalhes['sintomas'])) {
            $sint = $detalhes['sintomas'];
            if ($sint['classicos_total'] >= 3) {
                $criterios[] = "{$sint['classicos_total']} sintomas clássicos de dengue";
            }
        }

        // Sociodemográfico
        if (isset($detalhes['sociodemografico'])) {
            $socio = $detalhes['sociodemografico'];
            if ($socio['idade'] <= 5) {
                $criterios[] = "Paciente em faixa etária de risco (criança)";
            } elseif ($socio['idade'] >= 60) {
                $criterios[] = "Paciente em faixa etária de risco (idoso)";
            }
        }

        if (!empty($criterios)) {
            $justificativa .= "Fatores contribuintes: " . implode('; ', $criterios) . ".";
        } else {
            $justificativa .= "Quadro clínico e epidemiológico sem fatores críticos.";
        }

        return $justificativa;
    }

    /**
     * Gera recomendações clínicas baseadas no nível de risco
     *
     * @param array $nivel
     * @param array $detalhes
     * @return array
     */
    protected function gerarRecomendacoes(array $nivel, array $detalhes): array
    {
        $recomendacoes = [];

        switch ($nivel['label']) {
            case 'Alto':
                $recomendacoes[] = "Internação hospitalar imediata recomendada";
                $recomendacoes[] = "Monitoramento contínuo de sinais vitais";
                $recomendacoes[] = "Hidratação venosa e hemograma seriado";
                
                if (isset($detalhes['gravidade']['gravidade_total']) && $detalhes['gravidade']['gravidade_total'] > 0) {
                    $recomendacoes[] = "Avaliar necessidade de UTI";
                }
                break;

            case 'Médio':
                $recomendacoes[] = "Observação em unidade de saúde por no mínimo 24h";
                $recomendacoes[] = "Hidratação oral vigorosa";
                $recomendacoes[] = "Reavaliar sinais de alarme a cada 4-6 horas";
                
                if (isset($detalhes['gravidade']['alarmes_total']) && $detalhes['gravidade']['alarmes_total'] > 0) {
                    $recomendacoes[] = "Considerar internação se sinais de alarme persistirem";
                }
                break;

            case 'Baixo':
                $recomendacoes[] = "Acompanhamento ambulatorial";
                $recomendacoes[] = "Orientar sinais de alarme ao paciente/família";
                $recomendacoes[] = "Retornar se aparecimento de novos sintomas";
                $recomendacoes[] = "Hidratação oral e repouso domiciliar";
                break;
        }

        // Recomendações específicas para grupos de risco
        if (isset($detalhes['sociodemografico']['idade'])) {
            $idade = $detalhes['sociodemografico']['idade'];
            if ($idade <= 5 || $idade >= 60) {
                $recomendacoes[] = "Atenção especial: paciente em faixa etária de risco";
            }
        }

        return $recomendacoes;
    }

    /**
     * Identifica os fatores mais críticos do caso
     *
     * @param array $detalhes
     * @return array
     */
    protected function identificarFatoresCriticos(array $detalhes): array
    {
        $fatores = [];

        // Análise de cada critério
        $scores = [
            'epidemiologia' => $detalhes['epidemiologia']['score'] ?? 0,
            'gravidade' => $detalhes['gravidade']['score'] ?? 0,
            'sintomas' => $detalhes['sintomas']['score'] ?? 0,
            'sociodemografico' => $detalhes['sociodemografico']['score'] ?? 0,
        ];

        // Ordenar por score (maior para menor)
        arsort($scores);

        foreach ($scores as $criterio => $score) {
            if ($score > 0.5) { // Threshold de criticidade
                $fatores[] = [
                    'criterio' => ucfirst($criterio),
                    'score' => round($score, 4),
                    'nivel' => $score > 0.8 ? 'Crítico' : 'Moderado',
                ];
            }
        }

        return $fatores;
    }

    /**
     * Gera resumo executivo da avaliação
     *
     * @param array $resultado
     * @return string
     */
    public function gerarResumoExecutivo(array $resultado): string
    {
        $nivel = $resultado['nivel_risco'];
        $score = round($resultado['score_final'] * 100, 1);
        
        $resumo = "AVALIAÇÃO DE RISCO DE DENGUE\n";
        $resumo .= str_repeat('=', 50) . "\n";
        $resumo .= "Nível de Risco: {$nivel} ({$score}%)\n";
        $resumo .= str_repeat('=', 50) . "\n\n";
        
        $resumo .= "JUSTIFICATIVA:\n";
        $resumo .= $resultado['justificativa'] . "\n\n";
        
        $resumo .= "RECOMENDAÇÕES:\n";
        foreach ($resultado['recomendacoes'] as $i => $rec) {
            $resumo .= ($i + 1) . ". " . $rec . "\n";
        }

        if (!empty($resultado['fatores_criticos'])) {
            $resumo .= "\nFATORES CRÍTICOS:\n";
            foreach ($resultado['fatores_criticos'] as $fator) {
                $resumo .= "- {$fator['criterio']}: {$fator['nivel']} (score: {$fator['score']})\n";
            }
        }

        return $resumo;
    }
}
