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
    protected $table = 'avaliacoes_risco';

    /**
     * Atributos que podem ser atribuídos em massa
     *
     * @var array
     */
    protected $fillable = [
        'input_json',
        'score_epidemiologia',
        'score_gravidade',
        'score_sintomas',
        'score_sociodemografico',
        'score_final',
        'nivel_risco',
        'justificativa',
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
