<?php

namespace App\Services\Sumarizacao;

use App\Models\Dengue2025;
use App\Helpers\IBGEHelper;
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
        // Calcular média de idade a partir de NU_IDADE_N (formato SINAN: 4XXX)
        $mediaIdadeCodificada = Dengue2025::whereNotNull('NU_IDADE_N')
            ->where('NU_IDADE_N', '>=', 4000)
            ->where('NU_IDADE_N', '<', 5000)
            ->avg('NU_IDADE_N');
        
        // Decodifica: subtrai 4000 para obter idade real
        $mediaIdade = $mediaIdadeCodificada ? round($mediaIdadeCodificada - 4000, 1) : 0;
        
        return [
            'total_casos' => Dengue2025::count(),
            'casos_confirmados' => Dengue2025::confirmados()->count(),
            'casos_graves' => Dengue2025::graves()->count(),
            'casos_com_alarme' => Dengue2025::comAlarme()->count(),
            'media_idade' => $mediaIdade,
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
        $query = Dengue2025::select('SG_UF', DB::raw('COUNT(*) as total'))
            ->whereNotNull('SG_UF')
            ->groupBy('SG_UF')
            ->orderByDesc('total');

        if ($uf) {
            $query->where('SG_UF', $uf);
        }

        $resultado = $query->get()->map(function($item) {
            return [
                'UF' => IBGEHelper::getSiglaUF($item->SG_UF),
                'nome_uf' => IBGEHelper::getNomeUF($item->SG_UF),
                'total' => $item->total
            ];
        })->toArray();

        return $resultado;
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
        $result = Dengue2025::select('CS_SEXO', DB::raw('COUNT(*) as total'))
            ->whereNotNull('CS_SEXO')
            ->where('CS_SEXO', '!=', '')
            ->where('CS_SEXO', '!=', 'CS_SEXO') // Ignorar linha de cabeçalho
            ->groupBy('CS_SEXO')
            ->get();
        
        // Mapear códigos para categorias
        // Códigos reais na base dengue_2025:
        // 0 = Feminino, 1 = Masculino, 9 = Ignorado
        $mapeamento = [
            'M' => 'M',
            'F' => 'F',
            'I' => 'I',
            '0' => 'F',  // Feminino
            '1' => 'M',  // Masculino
            '9' => 'I',  // Ignorado
            'Masculino' => 'M',
            'Feminino' => 'F',
            'Ignorado' => 'I',
        ];
        
        $distribuicao = [];
        
        foreach ($result as $item) {
            $codigo = $item->CS_SEXO;
            $total = (int) $item->total;
            
            // Mapear o código para a categoria padrão
            $categoria = $mapeamento[$codigo] ?? 'I'; // Se não encontrar, considerar Ignorado
            
            // Somar ao total da categoria
            if (!isset($distribuicao[$categoria])) {
                $distribuicao[$categoria] = 0;
            }
            $distribuicao[$categoria] += $total;
        }
        
        return $distribuicao;
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
        $resultado = Dengue2025::select('ID_MN_RESI', 'SG_UF', DB::raw('COUNT(*) as total'))
            ->whereNotNull('ID_MN_RESI')
            ->whereNotNull('SG_UF')
            ->groupBy('ID_MN_RESI', 'SG_UF')
            ->orderByDesc('total')
            ->limit($limite)
            ->get()
            ->map(function($item) {
                return [
                    'codigo_ibge' => str_replace('.0', '', $item->ID_MN_RESI),
                    'municipio' => IBGEHelper::getNomeMunicipio($item->ID_MN_RESI),
                    'uf' => IBGEHelper::getSiglaUF($item->SG_UF),
                    'nome_uf' => IBGEHelper::getNomeUF($item->SG_UF),
                    'total' => $item->total
                ];
            })
            ->toArray();

        return $resultado;
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
