<?php

namespace App\Services\Regressao;

use App\Models\Dengue2025;
use Illuminate\Support\Facades\DB;

/**
 * RegressaoService
 * 
 * Responsável por análises preditivas, regressão linear e tendências
 * temporais usando dados históricos de dengue.
 */
class RegressaoService
{
    /**
     * Calcula regressão linear simples para tendência temporal
     *
     * @param string|null $municipio
     * @param string|null $uf
     * @return array
     */
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

        if ($dados->isEmpty()) {
            return [
                'coeficiente_angular' => 0,
                'intercepto' => 0,
                'r_squared' => 0,
                'tendencia' => 'Sem dados suficientes',
            ];
        }

        // Preparar dados para regressão
        $x = $dados->pluck('SEM_PRI')->toArray();
        $y = $dados->pluck('casos')->toArray();

        $resultado = $this->calcularRegressaoLinear($x, $y);
        $resultado['tendencia'] = $this->interpretarTendencia($resultado['coeficiente_angular']);

        return $resultado;
    }

    /**
     * Prevê casos futuros com base na tendência
     *
     * @param int $semanasAFrente
     * @param string|null $municipio
     * @param string|null $uf
     * @return array
     */
    public function preverCasosFuturos(int $semanasAFrente = 4, ?string $municipio = null, ?string $uf = null): array
    {
        $regressao = $this->regressaoLinearTemporal($municipio, $uf);
        
        if ($regressao['coeficiente_angular'] == 0) {
            return [
                'previsoes' => [],
                'confiabilidade' => 'Baixa',
                'mensagem' => 'Dados insuficientes para previsão',
            ];
        }

        // Última semana nos dados
        $ultimaSemana = Dengue2025::max('SEM_PRI') ?? 1;

        $previsoes = [];
        for ($i = 1; $i <= $semanasAFrente; $i++) {
            $semanaFutura = $ultimaSemana + $i;
            $casosPrevistos = $regressao['intercepto'] + ($regressao['coeficiente_angular'] * $semanaFutura);
            
            $previsoes[] = [
                'semana' => $semanaFutura,
                'casos_previstos' => max(0, round($casosPrevistos)),
            ];
        }

        return [
            'previsoes' => $previsoes,
            'confiabilidade' => $this->avaliarConfiabilidade($regressao['r_squared']),
            'r_squared' => round($regressao['r_squared'], 4),
            'tendencia' => $regressao['tendencia'],
        ];
    }

    /**
     * Calcula correlação entre sintomas e gravidade
     *
     * @return array
     */
    public function correlacaoSintomasGravidade(): array
    {
        $dados = Dengue2025::select('SINTOMAS_TOTAL', 'GRAVIDADE_TOTAL')
            ->whereNotNull('SINTOMAS_TOTAL')
            ->whereNotNull('GRAVIDADE_TOTAL')
            ->get();

        if ($dados->isEmpty()) {
            return ['correlacao' => 0, 'interpretacao' => 'Sem dados'];
        }

        $x = $dados->pluck('SINTOMAS_TOTAL')->toArray();
        $y = $dados->pluck('GRAVIDADE_TOTAL')->toArray();

        $correlacao = $this->calcularCorrelacaoPearson($x, $y);

        return [
            'correlacao' => round($correlacao, 4),
            'interpretacao' => $this->interpretarCorrelacao($correlacao),
        ];
    }

    /**
     * Calcula correlação entre alarmes e gravidade
     *
     * @return array
     */
    public function correlacaoAlarmesGravidade(): array
    {
        $dados = Dengue2025::select('ALARMES_TOTAIS', 'GRAVIDADE_TOTAL')
            ->whereNotNull('ALARMES_TOTAIS')
            ->whereNotNull('GRAVIDADE_TOTAL')
            ->get();

        if ($dados->isEmpty()) {
            return ['correlacao' => 0, 'interpretacao' => 'Sem dados'];
        }

        $x = $dados->pluck('ALARMES_TOTAIS')->toArray();
        $y = $dados->pluck('GRAVIDADE_TOTAL')->toArray();

        $correlacao = $this->calcularCorrelacaoPearson($x, $y);

        return [
            'correlacao' => round($correlacao, 4),
            'interpretacao' => $this->interpretarCorrelacao($correlacao),
        ];
    }

    /**
     * Calcula regressão linear simples
     *
     * @param array $x
     * @param array $y
     * @return array
     */
    protected function calcularRegressaoLinear(array $x, array $y): array
    {
        $n = count($x);
        
        if ($n < 2) {
            return [
                'coeficiente_angular' => 0,
                'intercepto' => 0,
                'r_squared' => 0,
            ];
        }

        $somaX = array_sum($x);
        $somaY = array_sum($y);
        $somaXY = 0;
        $somaX2 = 0;
        $somaY2 = 0;

        for ($i = 0; $i < $n; $i++) {
            $somaXY += $x[$i] * $y[$i];
            $somaX2 += $x[$i] * $x[$i];
            $somaY2 += $y[$i] * $y[$i];
        }

        // Coeficiente angular (slope)
        $denominador = ($n * $somaX2) - ($somaX * $somaX);
        $coeficienteAngular = $denominador != 0 
            ? (($n * $somaXY) - ($somaX * $somaY)) / $denominador 
            : 0;

        // Intercepto
        $intercepto = ($somaY - ($coeficienteAngular * $somaX)) / $n;

        // R²
        $rSquared = $this->calcularRSquared($x, $y, $coeficienteAngular, $intercepto);

        return [
            'coeficiente_angular' => round($coeficienteAngular, 4),
            'intercepto' => round($intercepto, 4),
            'r_squared' => round($rSquared, 4),
        ];
    }

    /**
     * Calcula R² (coeficiente de determinação)
     *
     * @param array $x
     * @param array $y
     * @param float $coef
     * @param float $inter
     * @return float
     */
    protected function calcularRSquared(array $x, array $y, float $coef, float $inter): float
    {
        $n = count($y);
        $mediaY = array_sum($y) / $n;

        $ssTotal = 0;
        $ssRes = 0;

        for ($i = 0; $i < $n; $i++) {
            $yPredito = $coef * $x[$i] + $inter;
            $ssTotal += pow($y[$i] - $mediaY, 2);
            $ssRes += pow($y[$i] - $yPredito, 2);
        }

        return $ssTotal != 0 ? 1 - ($ssRes / $ssTotal) : 0;
    }

    /**
     * Calcula correlação de Pearson
     *
     * @param array $x
     * @param array $y
     * @return float
     */
    protected function calcularCorrelacaoPearson(array $x, array $y): float
    {
        $n = count($x);
        
        if ($n < 2) {
            return 0;
        }

        $somaX = array_sum($x);
        $somaY = array_sum($y);
        $somaXY = 0;
        $somaX2 = 0;
        $somaY2 = 0;

        for ($i = 0; $i < $n; $i++) {
            $somaXY += $x[$i] * $y[$i];
            $somaX2 += $x[$i] * $x[$i];
            $somaY2 += $y[$i] * $y[$i];
        }

        $numerador = ($n * $somaXY) - ($somaX * $somaY);
        $denominador = sqrt((($n * $somaX2) - ($somaX * $somaX)) * (($n * $somaY2) - ($somaY * $somaY)));

        return $denominador != 0 ? $numerador / $denominador : 0;
    }

    /**
     * Interpreta o coeficiente angular da regressão
     *
     * @param float $coef
     * @return string
     */
    protected function interpretarTendencia(float $coef): string
    {
        if ($coef > 5) {
            return 'Crescimento acelerado';
        } elseif ($coef > 0) {
            return 'Crescimento moderado';
        } elseif ($coef < -5) {
            return 'Declínio acelerado';
        } elseif ($coef < 0) {
            return 'Declínio moderado';
        }
        
        return 'Estável';
    }

    /**
     * Interpreta o coeficiente de correlação
     *
     * @param float $r
     * @return string
     */
    protected function interpretarCorrelacao(float $r): string
    {
        $abs = abs($r);
        
        if ($abs >= 0.9) {
            return 'Correlação muito forte';
        } elseif ($abs >= 0.7) {
            return 'Correlação forte';
        } elseif ($abs >= 0.5) {
            return 'Correlação moderada';
        } elseif ($abs >= 0.3) {
            return 'Correlação fraca';
        }
        
        return 'Correlação muito fraca ou nula';
    }

    /**
     * Avalia confiabilidade da previsão baseada no R²
     *
     * @param float $rSquared
     * @return string
     */
    protected function avaliarConfiabilidade(float $rSquared): string
    {
        if ($rSquared >= 0.9) {
            return 'Muito alta';
        } elseif ($rSquared >= 0.7) {
            return 'Alta';
        } elseif ($rSquared >= 0.5) {
            return 'Moderada';
        } elseif ($rSquared >= 0.3) {
            return 'Baixa';
        }
        
        return 'Muito baixa';
    }
}
