<?php

namespace App\Services\Sumarizacao;

use App\Models\Dengue2025;
use Illuminate\Support\Facades\DB;

/**
 * SumarizacaoService
 * 
 * Responsável por consultas epidemiológicas agregadas e estatísticas descritivas
 * sobre os dados de dengue.
 */
class SumarizacaoService
{
    /**
     * Retorna estatísticas gerais dos casos
     *
     * @return array
     */
    public function estatisticasGerais(): array
    {
        return [
            'total_casos' => Dengue2025::count(),
            'casos_confirmados' => Dengue2025::confirmados()->count(),
            'casos_graves' => Dengue2025::graves()->count(),
            'casos_com_alarme' => Dengue2025::comAlarme()->count(),
            'media_idade' => round(Dengue2025::avg('IDADE'), 1),
            'distribuicao_sexo' => $this->distribuicaoSexo(),
        ];
    }

    /**
     * Casos por UF
     *
     * @param string|null $uf
     * @return array
     */
    public function casosPorUF(?string $uf = null): array
    {
        $query = Dengue2025::select('UF', DB::raw('COUNT(*) as total'))
            ->groupBy('UF')
            ->orderByDesc('total');

        if ($uf) {
            $query->where('UF', $uf);
        }

        return $query->get()->toArray();
    }

    /**
     * Casos por município
     *
     * @param string|null $municipio
     * @param string|null $uf
     * @return array
     */
    public function casosPorMunicipio(?string $municipio = null, ?string $uf = null): array
    {
        $query = Dengue2025::select('MUNICIPIO', 'UF', DB::raw('COUNT(*) as total'))
            ->groupBy('MUNICIPIO', 'UF')
            ->orderByDesc('total');

        if ($municipio) {
            $query->where('MUNICIPIO', 'LIKE', "%{$municipio}%");
        }

        if ($uf) {
            $query->where('UF', $uf);
        }

        return $query->limit(50)->get()->toArray();
    }

    /**
     * Casos por semana epidemiológica
     *
     * @param int|null $semana
     * @return array
     */
    public function casosPorSemana(?int $semana = null): array
    {
        $query = Dengue2025::select('SEM_PRI', DB::raw('COUNT(*) as total'))
            ->whereNotNull('SEM_PRI')
            ->groupBy('SEM_PRI')
            ->orderBy('SEM_PRI');

        if ($semana) {
            $query->where('SEM_PRI', $semana);
        }

        return $query->get()->toArray();
    }

    /**
     * Distribuição de sintomas
     *
     * @return array
     */
    public function distribuicaoSintomas(): array
    {
        $sintomas = config('ahp.sintomas_classicos');
        $resultado = [];

        foreach ($sintomas as $sintoma) {
            $resultado[$sintoma] = Dengue2025::where($sintoma, 1)->count();
        }

        arsort($resultado);
        return $resultado;
    }

    /**
     * Distribuição de sinais de alarme
     *
     * @return array
     */
    public function distribuicaoAlarmes(): array
    {
        $alarmes = config('ahp.sinais_alarme');
        $resultado = [];

        foreach ($alarmes as $alarme) {
            $resultado[$alarme] = Dengue2025::where($alarme, 1)->count();
        }

        arsort($resultado);
        return $resultado;
    }

    /**
     * Distribuição de sinais de gravidade
     *
     * @return array
     */
    public function distribuicaoGravidade(): array
    {
        $gravidade = config('ahp.sinais_gravidade');
        $resultado = [];

        foreach ($gravidade as $sinal) {
            $resultado[$sinal] = Dengue2025::where($sinal, 1)->count();
        }

        arsort($resultado);
        return $resultado;
    }

    /**
     * Distribuição por sexo
     *
     * @return array
     */
    protected function distribuicaoSexo(): array
    {
        return Dengue2025::select('SEXO', DB::raw('COUNT(*) as total'))
            ->whereNotNull('SEXO')
            ->groupBy('SEXO')
            ->get()
            ->pluck('total', 'SEXO')
            ->toArray();
    }

    /**
     * Distribuição por faixa etária
     *
     * @return array
     */
    public function distribuicaoFaixaEtaria(): array
    {
        return [
            '0-5' => Dengue2025::faixaEtaria(0, 5)->count(),
            '6-15' => Dengue2025::faixaEtaria(6, 15)->count(),
            '16-30' => Dengue2025::faixaEtaria(16, 30)->count(),
            '31-45' => Dengue2025::faixaEtaria(31, 45)->count(),
            '46-60' => Dengue2025::faixaEtaria(46, 60)->count(),
            '61+' => Dengue2025::faixaEtaria(61, 120)->count(),
        ];
    }

    /**
     * Top municípios mais afetados
     *
     * @param int $limite
     * @return array
     */
    public function topMunicipios(int $limite = 10): array
    {
        return Dengue2025::select('MUNICIPIO', 'UF', DB::raw('COUNT(*) as total'))
            ->whereNotNull('MUNICIPIO')
            ->groupBy('MUNICIPIO', 'UF')
            ->orderByDesc('total')
            ->limit($limite)
            ->get()
            ->toArray();
    }

    /**
     * Tendência temporal (casos por semana)
     *
     * @return array
     */
    public function tendenciaTemporal(): array
    {
        $dados = Dengue2025::select('SEM_PRI', DB::raw('COUNT(*) as total'))
            ->whereNotNull('SEM_PRI')
            ->groupBy('SEM_PRI')
            ->orderBy('SEM_PRI')
            ->get();

        return [
            'semanas' => $dados->pluck('SEM_PRI')->toArray(),
            'casos' => $dados->pluck('total')->toArray(),
        ];
    }

    /**
     * Resumo executivo completo
     *
     * @return array
     */
    public function resumoExecutivo(): array
    {
        return [
            'estatisticas_gerais' => $this->estatisticasGerais(),
            'top_municipios' => $this->topMunicipios(10),
            'distribuicao_faixa_etaria' => $this->distribuicaoFaixaEtaria(),
            'sintomas_mais_comuns' => array_slice($this->distribuicaoSintomas(), 0, 10, true),
            'tendencia_temporal' => $this->tendenciaTemporal(),
        ];
    }
}
