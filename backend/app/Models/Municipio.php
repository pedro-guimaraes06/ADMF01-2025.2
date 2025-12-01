<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Municipio
 * 
 * Base auxiliar para enriquecer análises epidemiológicas com dados municipais.
 * Fornece contexto populacional e geográfico para os cálculos de risco.
 * 
 * @property int $id
 * @property string $nome Nome do município
 * @property string $uf Unidade Federativa (sigla)
 * @property string $codigo_ibge Código IBGE completo (7 dígitos)
 * @property int|null $populacao População estimada
 * @property float|null $densidade_demografica Habitantes por km²
 * @property string|null $regiao_saude Nome da região de saúde
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Municipio extends Model
{
    /**
     * Nome da tabela
     *
     * @var string
     */
    protected $table = 'municipios';

    /**
     * Atributos que podem ser atribuídos em massa
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'uf',
        'codigo_ibge',
        'populacao',
        'densidade_demografica',
        'regiao_saude',
    ];

    /**
     * Conversão de tipos de atributos
     *
     * @var array
     */
    protected $casts = [
        'populacao' => 'integer',
        'densidade_demografica' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
        return $query->where('uf', $uf);
    }

    /**
     * Scope: Filtrar por região de saúde
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $regiao
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePorRegiaoSaude($query, $regiao)
    {
        return $query->where('regiao_saude', $regiao);
    }

    /**
     * Scope: Municípios com população acima de um valor
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $minPopulacao
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePopulacaoAcimaDe($query, $minPopulacao)
    {
        return $query->where('populacao', '>=', $minPopulacao);
    }

    /**
     * Scope: Buscar por código IBGE
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $codigoIbge
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePorCodigoIbge($query, $codigoIbge)
    {
        return $query->where('codigo_ibge', $codigoIbge);
    }

    /**
     * Buscar município por nome e UF
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $nome
     * @param string $uf
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBuscarPorNomeUF($query, $nome, $uf)
    {
        return $query->where('nome', $nome)->where('uf', $uf);
    }
}
