<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RiscoController;
use App\Http\Controllers\Api\SumarizacaoController;
use App\Http\Controllers\Api\AnaliseController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Handle preflight OPTIONS requests
Route::options('{any}', function () {
    return response()->json([], 200);
})->where('any', '.*');

// Teste de API
Route::get('/test', function () {
    return ['message' => 'OK'];
});

/*
|--------------------------------------------------------------------------
| Avaliação de Risco (Método AHP)
|--------------------------------------------------------------------------
*/
Route::prefix('risco')->group(function () {
    Route::post('/avaliar', [RiscoController::class, 'avaliar']);
    Route::get('/{id}', [RiscoController::class, 'buscar']);
    Route::get('/', [RiscoController::class, 'listar']);
    Route::get('/stats/estatisticas', [RiscoController::class, 'estatisticas']);
});

/*
|--------------------------------------------------------------------------
| Sumarização e Estatísticas Epidemiológicas
|--------------------------------------------------------------------------
*/
Route::prefix('casos')->group(function () {
    Route::get('/estatisticas', [SumarizacaoController::class, 'estatisticasGerais']);
    Route::get('/uf', [SumarizacaoController::class, 'casosPorUF']);
    Route::get('/municipio', [SumarizacaoController::class, 'casosPorMunicipio']);
    Route::get('/semana', [SumarizacaoController::class, 'casosPorSemana']);
    Route::get('/faixa-etaria', [SumarizacaoController::class, 'distribuicaoFaixaEtaria']);
    Route::get('/top-municipios', [SumarizacaoController::class, 'topMunicipios']);
    Route::get('/tendencia', [SumarizacaoController::class, 'tendenciaTemporal']);
    Route::get('/resumo-executivo', [SumarizacaoController::class, 'resumoExecutivo']);
});

Route::prefix('sintomas')->group(function () {
    Route::get('/distribuicao', [SumarizacaoController::class, 'distribuicaoSintomas']);
    Route::get('/alarmes', [SumarizacaoController::class, 'distribuicaoAlarmes']);
    Route::get('/gravidade', [SumarizacaoController::class, 'distribuicaoGravidade']);
});

/*
|--------------------------------------------------------------------------
| Análises Preditivas e Regressão
|--------------------------------------------------------------------------
*/
Route::prefix('analise')->group(function () {
    Route::get('/regressao', [AnaliseController::class, 'regressaoTemporal']);
    Route::get('/previsao', [AnaliseController::class, 'preverCasosFuturos']);
    Route::get('/correlacao/sintomas-gravidade', [AnaliseController::class, 'correlacaoSintomasGravidade']);
    Route::get('/correlacao/alarmes-gravidade', [AnaliseController::class, 'correlacaoAlarmesGravidade']);
    Route::get('/dashboard', [AnaliseController::class, 'dashboard']);
});
