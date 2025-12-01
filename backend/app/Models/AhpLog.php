<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model AhpLog
 * 
 * Armazena detalhes do cálculo multicritério AHP para auditoria e rastreabilidade.
 * Cada registro representa um passo do cálculo (critério ou subcritério).
 * 
 * @property int $id
 * @property int $avaliacao_id ID da avaliação de risco
 * @property string $criterio Nome do critério (Epidemiologia, Gravidade, Sintomas, Sociodemografico)
 * @property string|null $subcriterio Nome do subcritério específico
 * @property float $peso Peso do critério no cálculo AHP
 * @property float|null $valor_original Valor original antes da normalização
 * @property float $valor_normalizado Valor normalizado (0-1)
 * @property float $score_parcial Contribuição para o score final (peso × valor_normalizado)
 * @property string|null $observacao Informações adicionais sobre o cálculo
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class AhpLog extends Model
{
    /**
     * Nome da tabela
     *
     * @var string
     */
    protected $table = 'ahp_logs';

    /**
     * Atributos que podem ser atribuídos em massa
     *
     * @var array
     */
    protected $fillable = [
        'avaliacao_id',
        'criterio',
        'subcriterio',
        'peso',
        'valor_original',
        'valor_normalizado',
        'score_parcial',
        'observacao',
    ];

    /**
     * Conversão de tipos de atributos
     *
     * @var array
     */
    protected $casts = [
        'avaliacao_id' => 'integer',
        'peso' => 'float',
        'valor_original' => 'float',
        'valor_normalizado' => 'float',
        'score_parcial' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relacionamento: Um log pertence a uma avaliação
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function avaliacaoRisco()
    {
        return $this->belongsTo(AvaliacaoRisco::class, 'avaliacao_id');
    }

    /**
     * Scope: Filtrar por critério
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $criterio
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePorCriterio($query, $criterio)
    {
        return $query->where('criterio', $criterio);
    }

    /**
     * Scope: Filtrar por avaliação
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $avaliacaoId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePorAvaliacao($query, $avaliacaoId)
    {
        return $query->where('avaliacao_id', $avaliacaoId);
    }
}
