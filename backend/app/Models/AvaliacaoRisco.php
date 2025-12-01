<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model AvaliacaoRisco
 * 
 * Armazena o input e output de cada avaliação de risco realizada pelo SAD.
 * Registra os scores calculados pelo método AHP e o nível de risco resultante.
 * 
 * @property int $id
 * @property string $input_json JSON com os dados de entrada da avaliação
 * @property float $score_epidemiologia Score do critério epidemiológico (peso 0.45)
 * @property float $score_gravidade Score do critério de gravidade clínica (peso 0.35)
 * @property float $score_sintomas Score do critério de sintomas (peso 0.15)
 * @property float $score_sociodemografico Score do critério sociodemográfico (peso 0.05)
 * @property float $score_final Score final calculado (0-1)
 * @property string $nivel_risco Nível de risco: Baixo, Médio ou Alto
 * @property string|null $justificativa Explicação da classificação
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class AvaliacaoRisco extends Model
{
    /**
     * Nome da tabela
     *
     * @var string
     */
    protected $table = 'dengue_2025';

    /**
     * Desabilita timestamps automáticos (created_at, updated_at)
     * pois a tabela dengue_2025 não possui essas colunas
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Atributos que podem ser atribuídos em massa
     * Mapeando para as colunas existentes na tabela dengue_2025
     *
     * @var array
     */
    protected $fillable = [
        // Campos que já existem na tabela dengue_2025
        'FEBRE',
        'MIALGIA',
        'CEFALEIA',
        'EXANTEMA',
        'VOMITO',
        'NAUSEA',
        'DOR_COSTAS',
        'CONJUNTVIT',
        'ARTRALGIA',
        'DOR_RETRO',
        'PETEQUIA_N',
        'LEUCOPENIA',
        'LACO',
        'ALRM_HIPOT',
        'ALRM_PLAQ',
        'ALRM_VOM',
        'ALRM_SANG',
        'ALRM_HEMAT',
        'ALRM_ABDOM',
        'ALRM_LETAR',
        'ALRM_HEPAT',
        'ALRM_LIQ',
        'GRAV_PULSO',
        'GRAV_CONV',
        'GRAV_ENCH',
        'GRAV_EXTRE',
        'GRAV_HIPOT',
        'GRAV_HEMAT',
        'GRAV_MELEN',
        'GRAV_METRO',
        'GRAV_SANG',
        'GRAV_AST',
        'GRAV_MIOC',
        'GRAV_CONSC',
        'GRAV_ORGAO',
        'CS_SEXO',
        'SG_UF',
        'ID_MN_RESI',
        'NU_IDADE_N',
        'SEM_NOT',
        'TENDENCIA_TEMPORAL',
        'SINTOMAS_TOTAL',
        'ALARMES_TOTAIS',
        'GRAVIDADE_TOTAL',
        // Campos de cálculo de risco
        'score_epidemiologia',
        'score_gravidade',
        'score_sintomas',
        'score_sociodemografico',
        'score_final',
        'nivel_risco',
        'justificativa',
        'input_json',
    ];

    /**
     * Conversão de tipos de atributos
     *
     * @var array
     */
    protected $casts = [
        'score_epidemiologia' => 'float',
        'score_gravidade' => 'float',
        'score_sintomas' => 'float',
        'score_sociodemografico' => 'float',
        'score_final' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relacionamento: Uma avaliação tem muitos logs AHP
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ahpLogs()
    {
        return $this->hasMany(AhpLog::class, 'avaliacao_id');
    }

    /**
     * Scope: Filtrar por nível de risco
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $nivel
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePorNivelRisco($query, $nivel)
    {
        return $query->where('nivel_risco', $nivel);
    }

    /**
     * Scope: Avaliações de alto risco
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAltoRisco($query)
    {
        return $query->where('nivel_risco', 'Alto');
    }

    /**
     * Scope: Avaliações recentes
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $dias
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRecentes($query, $dias = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($dias));
    }
}
