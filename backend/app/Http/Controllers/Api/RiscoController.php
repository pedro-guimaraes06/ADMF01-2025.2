<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AvaliarRiscoRequest;
use App\Models\AvaliacaoRisco;
use App\Models\AhpLog;
use App\Services\Normalizador\NormalizadorService;
use App\Services\AHP\CalculadoraAHP;
use App\Services\Classificador\ClassificadorRiscoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * RiscoController
 * 
 * Controller principal para avaliação de risco de dengue usando método AHP.
 * Orquestra os serviços de normalização, cálculo e classificação.
 */
class RiscoController extends Controller
{
    protected $normalizador;
    protected $calculadora;
    protected $classificador;

    public function __construct(
        NormalizadorService $normalizador,
        CalculadoraAHP $calculadora,
        ClassificadorRiscoService $classificador
    ) {
        $this->normalizador = $normalizador;
        $this->calculadora = $calculadora;
        $this->classificador = $classificador;
    }

    /**
     * Avalia o risco de dengue para um paciente
     * 
     * @param AvaliarRiscoRequest $request
     * @return JsonResponse
     */
    public function avaliar(AvaliarRiscoRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            // 1. Capturar dados validados
            $dadosEntrada = $request->validated();

            // 2. Normalizar dados
            $dadosNormalizados = $this->normalizador->normalizar($dadosEntrada);

            // 3. Calcular score AHP
            $resultadoAHP = $this->calculadora->calcular($dadosNormalizados);

            // 4. Classificar risco
            $classificacao = $this->classificador->classificar(
                $resultadoAHP['score_final'],
                $resultadoAHP['detalhes']
            );

            // 5. Registrar avaliação no banco
            $avaliacao = AvaliacaoRisco::create([
                'input_json' => json_encode($dadosEntrada),
                'score_epidemiologia' => $resultadoAHP['score_epidemiologia'],
                'score_gravidade' => $resultadoAHP['score_gravidade'],
                'score_sintomas' => $resultadoAHP['score_sintomas'],
                'score_sociodemografico' => $resultadoAHP['score_sociodemografico'],
                'score_final' => $resultadoAHP['score_final'],
                'nivel_risco' => $classificacao['nivel_risco'],
                'justificativa' => $classificacao['justificativa'],
            ]);

            // 6. Registrar logs detalhados do AHP
            $this->registrarLogsAHP($avaliacao->id, $resultadoAHP);

            DB::commit();

            // 7. Retornar resposta estruturada
            return response()->json([
                'success' => true,
                'data' => [
                    'avaliacao_id' => $avaliacao->id,
                    'score_final' => $resultadoAHP['score_final'],
                    'nivel_risco' => $classificacao['nivel_risco'],
                    'cor' => $classificacao['cor'],
                    'justificativa' => $classificacao['justificativa'],
                    'recomendacoes' => $classificacao['recomendacoes'],
                    'fatores_criticos' => $classificacao['fatores_criticos'],
                    'detalhes_calculo' => [
                        'scores_criterios' => [
                            'epidemiologia' => $resultadoAHP['score_epidemiologia'],
                            'gravidade' => $resultadoAHP['score_gravidade'],
                            'sintomas' => $resultadoAHP['score_sintomas'],
                            'sociodemografico' => $resultadoAHP['score_sociodemografico'],
                        ],
                        'detalhes' => $resultadoAHP['detalhes'],
                    ],
                    'timestamp' => $avaliacao->created_at->toIso8601String(),
                ],
                'message' => 'Avaliação de risco realizada com sucesso',
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erro ao avaliar risco de dengue', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar avaliação de risco',
                'error' => app()->environment('local') ? $e->getMessage() : 'Erro interno do servidor',
            ], 500);
        }
    }

    /**
     * Registra logs detalhados do cálculo AHP
     *
     * @param int $avaliacaoId
     * @param array $resultadoAHP
     * @return void
     */
    protected function registrarLogsAHP(int $avaliacaoId, array $resultadoAHP): void
    {
        $pesos = config('ahp.pesos_criterios');
        $logs = [];

        // Log do critério Epidemiologia
        if (isset($resultadoAHP['detalhes']['epidemiologia'])) {
            $epi = $resultadoAHP['detalhes']['epidemiologia'];
            $logs[] = [
                'avaliacao_id' => $avaliacaoId,
                'criterio' => 'Epidemiologia',
                'subcriterio' => 'Incidência Municipal',
                'peso' => $pesos['epidemiologia'],
                'valor_original' => $epi['incidencia'],
                'valor_normalizado' => $epi['incidencia_normalizada'],
                'score_parcial' => $resultadoAHP['score_epidemiologia'],
                'observacao' => "Semana {$epi['semana']}, Tendência: {$epi['tendencia_normalizada']}",
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Log do critério Gravidade
        if (isset($resultadoAHP['detalhes']['gravidade'])) {
            $grav = $resultadoAHP['detalhes']['gravidade'];
            $logs[] = [
                'avaliacao_id' => $avaliacaoId,
                'criterio' => 'Gravidade',
                'subcriterio' => 'Sinais de Alarme + Gravidade',
                'peso' => $pesos['gravidade'],
                'valor_original' => $grav['alarmes_total'] + $grav['gravidade_total'],
                'valor_normalizado' => ($grav['alarmes_normalizado'] + $grav['gravidade_normalizado']) / 2,
                'score_parcial' => $resultadoAHP['score_gravidade'],
                'observacao' => "{$grav['alarmes_total']} alarmes, {$grav['gravidade_total']} sinais graves",
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Log do critério Sintomas
        if (isset($resultadoAHP['detalhes']['sintomas'])) {
            $sint = $resultadoAHP['detalhes']['sintomas'];
            $logs[] = [
                'avaliacao_id' => $avaliacaoId,
                'criterio' => 'Sintomas',
                'subcriterio' => 'Clássicos + Inespecíficos',
                'peso' => $pesos['sintomas'],
                'valor_original' => $sint['classicos_total'] + $sint['inespecificos_total'],
                'valor_normalizado' => ($sint['classicos_normalizado'] + $sint['inespecificos_normalizado']) / 2,
                'score_parcial' => $resultadoAHP['score_sintomas'],
                'observacao' => "{$sint['classicos_total']} clássicos, {$sint['inespecificos_total']} inespecíficos",
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Log do critério Sociodemográfico
        if (isset($resultadoAHP['detalhes']['sociodemografico'])) {
            $socio = $resultadoAHP['detalhes']['sociodemografico'];
            $logs[] = [
                'avaliacao_id' => $avaliacaoId,
                'criterio' => 'Sociodemografico',
                'subcriterio' => 'Idade',
                'peso' => $pesos['sociodemografico'],
                'valor_original' => $socio['idade'],
                'valor_normalizado' => $socio['idade_normalizada'],
                'score_parcial' => $resultadoAHP['score_sociodemografico'],
                'observacao' => "Idade: {$socio['idade']} anos",
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Inserir todos os logs em batch
        AhpLog::insert($logs);
    }

    /**
     * Busca uma avaliação específica por ID
     *
     * @param int $id
     * @return JsonResponse
     */
    public function buscar(int $id): JsonResponse
    {
        try {
            $avaliacao = AvaliacaoRisco::with('ahpLogs')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'avaliacao' => $avaliacao,
                    'input_original' => json_decode($avaliacao->input_json, true),
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Avaliação não encontrada',
            ], 404);
        }
    }

    /**
     * Lista avaliações recentes
     *
     * @return JsonResponse
     */
    public function listar(): JsonResponse
    {
        try {
            $avaliacoes = AvaliacaoRisco::orderBy('created_at', 'desc')
                ->limit(50)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $avaliacoes,
                'total' => $avaliacoes->count(),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao listar avaliações',
            ], 500);
        }
    }

    /**
     * Estatísticas das avaliações
     *
     * @return JsonResponse
     */
    public function estatisticas(): JsonResponse
    {
        try {
            $estatisticas = [
                'total_avaliacoes' => AvaliacaoRisco::count(),
                'por_nivel_risco' => [
                    'baixo' => AvaliacaoRisco::where('nivel_risco', 'Baixo')->count(),
                    'medio' => AvaliacaoRisco::where('nivel_risco', 'Médio')->count(),
                    'alto' => AvaliacaoRisco::where('nivel_risco', 'Alto')->count(),
                ],
                'score_medio' => round(AvaliacaoRisco::avg('score_final'), 4),
                'avaliacoes_ultimos_7_dias' => AvaliacaoRisco::where('created_at', '>=', now()->subDays(7))->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $estatisticas,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao calcular estatísticas',
            ], 500);
        }
    }
}
