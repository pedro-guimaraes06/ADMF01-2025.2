<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Dengue2025
 * 
 * Representa a tabela dengue_2025 com dados epidemiológicos reais do OpenDataSUS.
 * Esta model é usada apenas para leitura e consultas analíticas.
 * Não implementa regras de negócio - essas ficam nos Services.
 * 
 * @property int $id
 * @property string|null $UF
 * @property string|null $MUNICIPIO
 * @property int|null $IDADE
 * @property string|null $SEXO
 * @property string|null $RACA
 * @property int|null $FEBRE
 * @property int|null $MIALGIA
 * @property int|null $CEFALEIA
 * @property int|null $EXANTEMA
 * @property int|null $VOMITO
 * @property int|null $NAUSEA
 * @property int|null $DOR_COSTAS
 * @property int|null $CONJUNTVIT
 * @property int|null $ARTRITE
 * @property int|null $ARTRALGIA
 * @property int|null $PETEQUIA_N
 * @property int|null $LEUCOPENIA
 * @property int|null $LACO
 * @property int|null $DOR_RETRO
 * @property int|null $ALRM_HIPOT
 * @property int|null $ALRM_PLAQ
 * @property int|null $ALRM_VOM
 * @property int|null $ALRM_SANG
 * @property int|null $ALRM_HEMAT
 * @property int|null $ALRM_ABDOM
 * @property int|null $ALRM_LETAR
 * @property int|null $ALRM_HEPAT
 * @property int|null $ALRM_LIQ
 * @property int|null $GRAV_PULSO
 * @property int|null $GRAV_CONV
 * @property int|null $GRAV_ENCH
 * @property int|null $GRAV_INSC
 * @property int|null $GRAV_EXTRE
 * @property int|null $GRAV_HIPOT
 * @property int|null $GRAV_HEMAT
 * @property int|null $GRAV_MELEN
 * @property int|null $GRAV_METRO
 * @property int|null $GRAV_SANG
 * @property int|null $GRAV_AST
 * @property int|null $GRAV_MIOC
 * @property int|null $GRAV_CONSC
 * @property int|null $GRAV_ORGAO
 * @property int|null $SINTOMAS_TOTAL
 * @property int|null $ALARMES_TOTAIS
 * @property int|null $GRAVIDADE_TOTAL
 * @property float|null $TENDENCIA_TEMPORAL
 * @property int|null $SEM_PRI
 * @property string|null $DT_NOTIFIC
 * @property string|null $DT_SIN_PRI
 * @property string|null $DT_DIGITA
 * @property string|null $CLASSI_FIN
 * @property string|null $CRITERIO
 * @property string|null $EVOLUCAO
 */
class Dengue2025 extends Model
{
    /**
     * Nome da tabela
     *
     * @var string
     */
    protected $table = 'dengue_2025';

    /**
     * Indica que a model não usa timestamps
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Atributos que podem ser atribuídos em massa
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Conversão de tipos de atributos
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'IDADE' => 'integer',
        'FEBRE' => 'integer',
        'MIALGIA' => 'integer',
        'CEFALEIA' => 'integer',
        'EXANTEMA' => 'integer',
        'VOMITO' => 'integer',
        'NAUSEA' => 'integer',
        'DOR_COSTAS' => 'integer',
        'CONJUNTVIT' => 'integer',
        'ARTRITE' => 'integer',
        'ARTRALGIA' => 'integer',
        'PETEQUIA_N' => 'integer',
        'LEUCOPENIA' => 'integer',
        'LACO' => 'integer',
        'DOR_RETRO' => 'integer',
        'ALRM_HIPOT' => 'integer',
        'ALRM_PLAQ' => 'integer',
        'ALRM_VOM' => 'integer',
        'ALRM_SANG' => 'integer',
        'ALRM_HEMAT' => 'integer',
        'ALRM_ABDOM' => 'integer',
        'ALRM_LETAR' => 'integer',
        'ALRM_HEPAT' => 'integer',
        'ALRM_LIQ' => 'integer',
        'GRAV_PULSO' => 'integer',
        'GRAV_CONV' => 'integer',
        'GRAV_ENCH' => 'integer',
        'GRAV_INSC' => 'integer',
        'GRAV_EXTRE' => 'integer',
        'GRAV_HIPOT' => 'integer',
        'GRAV_HEMAT' => 'integer',
        'GRAV_MELEN' => 'integer',
        'GRAV_METRO' => 'integer',
        'GRAV_SANG' => 'integer',
        'GRAV_AST' => 'integer',
        'GRAV_MIOC' => 'integer',
        'GRAV_CONSC' => 'integer',
        'GRAV_ORGAO' => 'integer',
        'SINTOMAS_TOTAL' => 'integer',
        'ALARMES_TOTAIS' => 'integer',
        'GRAVIDADE_TOTAL' => 'integer',
        'TENDENCIA_TEMPORAL' => 'float',
        'SEM_PRI' => 'integer',
    ];

    /**
     * Scope: Filtrar por UF
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $uf
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePorUF($query, $uf)
    {
        return $query->where('UF', $uf);
    }

    /**
     * Scope: Filtrar por município
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $municipio
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePorMunicipio($query, $municipio)
    {
        return $query->where('MUNICIPIO', $municipio);
    }

    /**
     * Scope: Filtrar por semana epidemiológica
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $semana
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSemana($query, $semana)
    {
        return $query->where('SEM_PRI', $semana);
    }

    /**
     * Scope: Casos confirmados
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeConfirmados($query)
    {
        return $query->whereIn('CLASSI_FIN', [10, 11, 12]);
    }

    /**
     * Scope: Casos com sinais de gravidade
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGraves($query)
    {
        return $query->where('GRAVIDADE_TOTAL', '>', 0);
    }

    /**
     * Scope: Casos com sinais de alarme
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeComAlarme($query)
    {
        return $query->where('ALARMES_TOTAIS', '>', 0);
    }

    /**
     * Scope: Casos com febre
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeComFebre($query)
    {
        return $query->where('FEBRE', 1);
    }

    /**
     * Scope: Filtrar por faixa etária
     * NU_IDADE_N no SINAN usa formato: 4XXX onde XXX é a idade em anos
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $idadeMin
     * @param int $idadeMax
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFaixaEtaria($query, $idadeMin, $idadeMax)
    {
        // Converte idade para código SINAN: 4000 + idade
        $codigoMin = 4000 + $idadeMin;
        $codigoMax = 4000 + $idadeMax;
        
        return $query->whereBetween('NU_IDADE_N', [$codigoMin, $codigoMax]);
    }

    /**
     * Scope: Filtrar por sexo
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $sexo
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePorSexo($query, $sexo)
    {
        return $query->where('SEXO', $sexo);
    }
}
