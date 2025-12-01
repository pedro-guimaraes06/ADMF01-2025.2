<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Sumarizacao\SumarizacaoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * SumarizacaoController
 * 
 * Controller para consultas epidemiológicas agregadas e estatísticas descritivas
 */
class SumarizacaoController extends Controller
{
    protected $sumarizacao;

    public function __construct(SumarizacaoService $sumarizacao)
    {
        $this->sumarizacao = $sumarizacao;
    }

    /**
     * Estatísticas gerais dos casos
     * 
     * GET /api/casos/estatisticas
     *
     * @return JsonResponse
     */
    public function estatisticasGerais(): JsonResponse
    {
        try {
            $estatisticas = $this->sumarizacao->estatisticasGerais();

            return response()->json([
                'success' => true,
                'data' => $estatisticas,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erro ao obter estatísticas gerais', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao obter estatísticas gerais',
            ], 500);
        }
    }

    /**
     * Casos por UF
     * 
     * GET /api/casos/uf?uf=BA
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function casosPorUF(Request $request): JsonResponse
    {
        try {
            $uf = $request->query('uf');
            $casos = $this->sumarizacao->casosPorUF($uf);

            return response()->json([
                'success' => true,
                'data' => $casos,
                'filtro' => $uf ? ['uf' => $uf] : null,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erro ao obter casos por UF', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao obter casos por UF',
            ], 500);
        }
    }

    /**
     * Casos por município
     * 
     * GET /api/casos/municipio?municipio=Salvador&uf=BA
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function casosPorMunicipio(Request $request): JsonResponse
    {
        try {
            $municipio = $request->query('municipio');
            $uf = $request->query('uf');
            
            $casos = $this->sumarizacao->casosPorMunicipio($municipio, $uf);

            return response()->json([
                'success' => true,
                'data' => $casos,
                'filtro' => [
                    'municipio' => $municipio,
                    'uf' => $uf,
                ],
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erro ao obter casos por município', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao obter casos por município',
            ], 500);
        }
    }

    /**
     * Casos por semana epidemiológica
     * 
     * GET /api/casos/semana?semana=15
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function casosPorSemana(Request $request): JsonResponse
    {
        try {
            $semana = $request->query('semana');
            $casos = $this->sumarizacao->casosPorSemana($semana);

            return response()->json([
                'success' => true,
                'data' => $casos,
                'filtro' => $semana ? ['semana' => $semana] : null,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erro ao obter casos por semana', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao obter casos por semana',
            ], 500);
        }
    }

    /**
     * Distribuição de sintomas
     * 
     * GET /api/sintomas/distribuicao
     *
     * @return JsonResponse
     */
    public function distribuicaoSintomas(): JsonResponse
    {
        try {
            $sintomas = $this->sumarizacao->distribuicaoSintomas();

            return response()->json([
                'success' => true,
                'data' => $sintomas,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erro ao obter distribuição de sintomas', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao obter distribuição de sintomas',
            ], 500);
        }
    }

    /**
     * Distribuição de sinais de alarme
     * 
     * GET /api/sintomas/alarmes
     *
     * @return JsonResponse
     */
    public function distribuicaoAlarmes(): JsonResponse
    {
        try {
            $alarmes = $this->sumarizacao->distribuicaoAlarmes();

            return response()->json([
                'success' => true,
                'data' => $alarmes,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erro ao obter distribuição de alarmes', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao obter distribuição de alarmes',
            ], 500);
        }
    }

    /**
     * Distribuição de sinais de gravidade
     * 
     * GET /api/sintomas/gravidade
     *
     * @return JsonResponse
     */
    public function distribuicaoGravidade(): JsonResponse
    {
        try {
            $gravidade = $this->sumarizacao->distribuicaoGravidade();

            return response()->json([
                'success' => true,
                'data' => $gravidade,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erro ao obter distribuição de gravidade', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao obter distribuição de gravidade',
            ], 500);
        }
    }

    /**
     * Distribuição por faixa etária
     * 
     * GET /api/casos/faixa-etaria
     *
     * @return JsonResponse
     */
    public function distribuicaoFaixaEtaria(): JsonResponse
    {
        try {
            $faixas = $this->sumarizacao->distribuicaoFaixaEtaria();

            return response()->json([
                'success' => true,
                'data' => $faixas,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erro ao obter distribuição por faixa etária', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao obter distribuição por faixa etária',
            ], 500);
        }
    }

    /**
     * Top municípios mais afetados
     * 
     * GET /api/casos/top-municipios?limite=10
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function topMunicipios(Request $request): JsonResponse
    {
        try {
            $limite = (int) $request->query('limite', 10);
            $municipios = $this->sumarizacao->topMunicipios($limite);

            return response()->json([
                'success' => true,
                'data' => $municipios,
                'limite' => $limite,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erro ao obter top municípios', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao obter top municípios',
            ], 500);
        }
    }

    /**
     * Tendência temporal
     * 
     * GET /api/casos/tendencia
     *
     * @return JsonResponse
     */
    public function tendenciaTemporal(): JsonResponse
    {
        try {
            $tendencia = $this->sumarizacao->tendenciaTemporal();

            return response()->json([
                'success' => true,
                'data' => $tendencia,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erro ao obter tendência temporal', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao obter tendência temporal',
            ], 500);
        }
    }

    /**
     * Resumo executivo completo
     * 
     * GET /api/casos/resumo-executivo
     *
     * @return JsonResponse
     */
    public function resumoExecutivo(): JsonResponse
    {
        try {
            $resumo = $this->sumarizacao->resumoExecutivo();

            return response()->json([
                'success' => true,
                'data' => $resumo,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erro ao obter resumo executivo', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao obter resumo executivo',
            ], 500);
        }
    }
}
