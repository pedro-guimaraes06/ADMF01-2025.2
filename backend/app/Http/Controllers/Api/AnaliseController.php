<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Regressao\RegressaoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * AnaliseController
 * 
 * Controller para análises preditivas, regressões e correlações
 */
class AnaliseController extends Controller
{
    protected $regressao;

    public function __construct(RegressaoService $regressao)
    {
        $this->regressao = $regressao;
    }

    /**
     * Regressão linear temporal
     * 
     * GET /api/analise/regressao?municipio=Salvador&uf=BA
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function regressaoTemporal(Request $request): JsonResponse
    {
        try {
            $municipio = $request->query('municipio');
            $uf = $request->query('uf');

            $resultado = $this->regressao->regressaoLinearTemporal($municipio, $uf);

            return response()->json([
                'success' => true,
                'data' => $resultado,
                'filtros' => [
                    'municipio' => $municipio,
                    'uf' => $uf,
                ],
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erro na regressão temporal', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao calcular regressão temporal',
            ], 500);
        }
    }

    /**
     * Previsão de casos futuros
     * 
     * GET /api/analise/previsao?semanas=4&municipio=Salvador&uf=BA
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function preverCasosFuturos(Request $request): JsonResponse
    {
        try {
            $semanas = (int) $request->query('semanas', 4);
            $municipio = $request->query('municipio');
            $uf = $request->query('uf');

            // Validar semanas
            if ($semanas < 1 || $semanas > 12) {
                return response()->json([
                    'success' => false,
                    'message' => 'O número de semanas deve estar entre 1 e 12',
                ], 400);
            }

            $previsao = $this->regressao->preverCasosFuturos($semanas, $municipio, $uf);

            return response()->json([
                'success' => true,
                'data' => $previsao,
                'parametros' => [
                    'semanas_a_frente' => $semanas,
                    'municipio' => $municipio,
                    'uf' => $uf,
                ],
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erro na previsão de casos', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao prever casos futuros',
            ], 500);
        }
    }

    /**
     * Correlação entre sintomas e gravidade
     * 
     * GET /api/analise/correlacao/sintomas-gravidade
     *
     * @return JsonResponse
     */
    public function correlacaoSintomasGravidade(): JsonResponse
    {
        try {
            $correlacao = $this->regressao->correlacaoSintomasGravidade();

            return response()->json([
                'success' => true,
                'data' => $correlacao,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erro ao calcular correlação sintomas-gravidade', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao calcular correlação',
            ], 500);
        }
    }

    /**
     * Correlação entre alarmes e gravidade
     * 
     * GET /api/analise/correlacao/alarmes-gravidade
     *
     * @return JsonResponse
     */
    public function correlacaoAlarmesGravidade(): JsonResponse
    {
        try {
            $correlacao = $this->regressao->correlacaoAlarmesGravidade();

            return response()->json([
                'success' => true,
                'data' => $correlacao,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erro ao calcular correlação alarmes-gravidade', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao calcular correlação',
            ], 500);
        }
    }

    /**
     * Dashboard completo de análises
     * 
     * GET /api/analise/dashboard?municipio=Salvador&uf=BA
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function dashboard(Request $request): JsonResponse
    {
        try {
            $municipio = $request->query('municipio');
            $uf = $request->query('uf');

            $dashboard = [
                'regressao_temporal' => $this->regressao->regressaoLinearTemporal($municipio, $uf),
                'previsao_4_semanas' => $this->regressao->preverCasosFuturos(4, $municipio, $uf),
                'correlacao_sintomas' => $this->regressao->correlacaoSintomasGravidade(),
                'correlacao_alarmes' => $this->regressao->correlacaoAlarmesGravidade(),
            ];

            return response()->json([
                'success' => true,
                'data' => $dashboard,
                'filtros' => [
                    'municipio' => $municipio,
                    'uf' => $uf,
                ],
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erro ao gerar dashboard de análises', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao gerar dashboard de análises',
            ], 500);
        }
    }
}
