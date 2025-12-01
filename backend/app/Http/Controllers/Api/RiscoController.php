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
        try {
            // 1. Capturar dados validados
            $dadosEntrada = $request->validated();
            $dadosCompat = [];
            // Sintomas clássicos
            foreach (config('ahp.sintomas_classicos') as $campo) {
                $dadosCompat[strtolower($campo)] = $dadosEntrada[$campo] ?? $dadosEntrada[strtolower($campo)] ?? 0;
            }
            // Sintomas inespecíficos
            foreach (config('ahp.sintomas_inespecificos') as $campo) {
                $dadosCompat[strtolower($campo)] = $dadosEntrada[$campo] ?? $dadosEntrada[strtolower($campo)] ?? 0;
            }
            // Sinais de alarme
            foreach (config('ahp.sinais_alarme') as $campo) {
                $dadosCompat[strtolower($campo)] = $dadosEntrada[$campo] ?? $dadosEntrada[strtolower($campo)] ?? 0;
            }
            // Sinais de gravidade
            foreach (config('ahp.sinais_gravidade') as $campo) {
                $dadosCompat[strtolower($campo)] = $dadosEntrada[$campo] ?? $dadosEntrada[strtolower($campo)] ?? 0;
            }
            // Demográficos
            $dadosCompat['idade'] = $dadosEntrada['idade'] ?? $dadosEntrada['NU_IDADE_N'] ?? 0;
            $dadosCompat['sexo'] = $dadosEntrada['sexo'] ?? $dadosEntrada['CS_SEXO'] ?? 'I';
            $dadosCompat['uf'] = $dadosEntrada['uf'] ?? $dadosEntrada['SG_UF'] ?? '';
            $dadosCompat['municipio'] = $dadosEntrada['municipio'] ?? $dadosEntrada['ID_MN_RESI'] ?? '';
            $dadosCompat['semana_epidemiologica'] = $dadosEntrada['semana_epidemiologica'] ?? $dadosEntrada['SEM_NOT'] ?? 1;
            $dadosCompat['tendencia_temporal'] = $dadosEntrada['tendencia_temporal'] ?? $dadosEntrada['TENDENCIA_TEMPORAL'] ?? 0;
            // Epidemiologia
            $dadosCompat['casos_municipio'] = $dadosEntrada['casos_municipio'] ?? 0;
            $dadosCompat['populacao_municipio'] = $dadosEntrada['populacao_municipio'] ?? 100000;

            // LOG: Verificar dados compatíveis
            Log::info('DADOS COMPAT', $dadosCompat);

            // 2. Normalizar dados (apenas uma vez, com todos os campos necessários)
            $dadosNormalizados = $this->normalizador->normalizar($dadosCompat);

            // 3. Calcular score AHP
            $resultadoAHP = $this->calculadora->calcular($dadosNormalizados);

            // LOG: Verificar resultado do cálculo AHP
            Log::info('RESULTADO AHP', $resultadoAHP);

            // 4. Classificar risco
            $classificacao = $this->classificador->classificar(
                $resultadoAHP['score_final'],
                $resultadoAHP['detalhes']
            );

            // LOG: Verificar classificação
            Log::info('CLASSIFICACAO', $classificacao);

            // 5. Montar dados para persistência
            $dadosParaSalvar = [];
            // Mapear campos do formulário para colunas da tabela
            $map = [
                'febre' => 'FEBRE',
                'mialgia' => 'MIALGIA',
                'cefaleia' => 'CEFALEIA',
                'exantema' => 'EXANTEMA',
                'vomito' => 'VOMITO',
                'nausea' => 'NAUSEA',
                'dor_costas' => 'DOR_COSTAS',
                'conjuntvit' => 'CONJUNTVIT',
                'artralgia' => 'ARTRALGIA',
                'dor_retro' => 'DOR_RETRO',
                'petequia_n' => 'PETEQUIA_N',
                'leucopenia' => 'LEUCOPENIA',
                'laco' => 'LACO',
                'alrm_hipot' => 'ALRM_HIPOT',
                'alrm_plaq' => 'ALRM_PLAQ',
                'alrm_vom' => 'ALRM_VOM',
                'alrm_sang' => 'ALRM_SANG',
                'alrm_hemat' => 'ALRM_HEMAT',
                'alrm_abdom' => 'ALRM_ABDOM',
                'alrm_letar' => 'ALRM_LETAR',
                'alrm_hepat' => 'ALRM_HEPAT',
                'alrm_liq' => 'ALRM_LIQ',
                'grav_pulso' => 'GRAV_PULSO',
                'grav_conv' => 'GRAV_CONV',
                'grav_ench' => 'GRAV_ENCH',
                'grav_extre' => 'GRAV_EXTRE',
                'grav_hipot' => 'GRAV_HIPOT',
                'grav_hemat' => 'GRAV_HEMAT',
                'grav_melen' => 'GRAV_MELEN',
                'grav_metro' => 'GRAV_METRO',
                'grav_sang' => 'GRAV_SANG',
                'grav_ast' => 'GRAV_AST',
                'grav_mioc' => 'GRAV_MIOC',
                'grav_consc' => 'GRAV_CONSC',
                'grav_orgao' => 'GRAV_ORGAO',
                'sexo' => 'CS_SEXO',
                'uf' => 'SG_UF',
                'municipio' => 'ID_MN_RESI',
                'idade' => 'NU_IDADE_N',
                'semana_epidemiologica' => 'SEM_NOT',
                'tendencia_temporal' => 'TENDENCIA_TEMPORAL',
                'casos_municipio' => 'CASOS_MUNICIPIO',
                'populacao_municipio' => 'POPULACAO_MUNICIPIO',
            ];
            foreach ($map as $input => $col) {
                if (isset($dadosCompat[$input])) {
                    // Corrigir tipos: idade deve ser int, sexo string, municipio string
                    if ($input === 'idade') {
                        $dadosParaSalvar[$col] = (int) $dadosCompat[$input];
                    } elseif ($input === 'sexo') {
                        $dadosParaSalvar[$col] = (string) $dadosCompat[$input];
                    } elseif ($input === 'municipio') {
                        $dadosParaSalvar[$col] = (string) $dadosCompat[$input];
                    } else {
                        $dadosParaSalvar[$col] = $dadosCompat[$input];
                    }
                }
            }

            // Adicionar campos calculados
            $dadosParaSalvar['score_epidemiologia'] = $resultadoAHP['score_epidemiologia'] ?? null;
            $dadosParaSalvar['score_gravidade'] = $resultadoAHP['score_gravidade'] ?? null;
            $dadosParaSalvar['score_sintomas'] = $resultadoAHP['score_sintomas'] ?? null;
            $dadosParaSalvar['score_sociodemografico'] = $resultadoAHP['score_sociodemografico'] ?? null;
            $dadosParaSalvar['score_final'] = $resultadoAHP['score_final'] ?? null;
            $dadosParaSalvar['nivel_risco'] = $classificacao['nivel_risco'] ?? null;
            $dadosParaSalvar['justificativa'] = $classificacao['justificativa'] ?? null;
            $dadosParaSalvar['input_json'] = json_encode($dadosEntrada);

            // 6. Persistir avaliação
            $avaliacao = AvaliacaoRisco::create($dadosParaSalvar);

            // LOG: Confirmar persistência
            Log::info('AVALIACAO PERSISTIDA', [
                'id' => $avaliacao->id,
                'score_final' => $avaliacao->score_final,
                'nivel_risco' => $avaliacao->nivel_risco,
            ]);

            // 7. Retornar resposta estruturada completa
            return response()->json([
                'success' => true,
                'data' => [
                    'avaliacao_id' => $avaliacao->id,
                    'scores' => [
                        'epidemiologia' => $avaliacao->score_epidemiologia,
                        'gravidade' => $avaliacao->score_gravidade,
                        'sintomas' => $avaliacao->score_sintomas,
                        'sociodemografico' => $avaliacao->score_sociodemografico,
                        'final' => $avaliacao->score_final,
                    ],
                    'classificacao' => [
                        'nivel_risco' => $avaliacao->nivel_risco,
                        'justificativa' => $avaliacao->justificativa,
                    ],
                    'detalhes' => $resultadoAHP['detalhes'] ?? [],
                ],
                'message' => 'Avaliação de risco registrada com sucesso',
            ], 201);
        } catch (\Exception $e) {
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
    public function registrarLogsAHP(int $avaliacaoId, array $resultadoAHP): void
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

            // Montar objeto de resposta compatível com o frontend
            $input = json_decode($avaliacao->input_json, true) ?? [];

            $response = [
                'id' => $avaliacao->id,
                'idade' => $input['idade'] ?? $avaliacao->NU_IDADE_N ?? null,
                'sexo' => $input['sexo'] ?? $avaliacao->CS_SEXO ?? null,
                'municipio' => $input['municipio'] ?? $avaliacao->ID_MN_RESI ?? null,
                'uf' => $input['uf'] ?? $avaliacao->SG_UF ?? null,
                'semana_epidemiologica' => $input['semana_epidemiologica'] ?? $avaliacao->SEM_NOT ?? null,
                'created_at' => $avaliacao->created_at,
                'score_final' => $avaliacao->score_final,
                'nivel_risco' => $avaliacao->nivel_risco,
                'justificativa' => $avaliacao->justificativa,
                // Scores detalhados para gráficos e interpretações
                'scores' => [
                    'epidemiologia' => $avaliacao->score_epidemiologia,
                    'gravidade' => $avaliacao->score_gravidade,
                    'sintomas' => $avaliacao->score_sintomas,
                    'sociodemografico' => $avaliacao->score_sociodemografico,
                ],
                // Sintomas clássicos (para interpretação)
                'febre' => $input['febre'] ?? $avaliacao->FEBRE ?? null,
                'cefaleia' => $input['cefaleia'] ?? $avaliacao->CEFALEIA ?? null,
                'mialgia' => $input['mialgia'] ?? $avaliacao->MIALGIA ?? null,
                'artralgia' => $input['artralgia'] ?? $avaliacao->ARTRALGIA ?? null,
                'dor_retro' => $input['dor_retro'] ?? $avaliacao->DOR_RETRO ?? null,
                'exantema' => $input['exantema'] ?? $avaliacao->EXANTEMA ?? null,
                // Campos extras do input original
                'input_original' => $input,
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'avaliacao' => $response,
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
