<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * AvaliarRiscoRequest
 * 
 * Validação de dados para avaliação de risco de dengue
 */
class AvaliarRiscoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // Dados demográficos
            'idade' => 'required|integer|min:0|max:120',
            'sexo' => 'required|string|in:M,F,I',
            'uf' => 'required|string|size:2',
            'municipio' => 'required|string|max:100',
            'codigo_municipio' => 'nullable|string|max:7',

            // Dados epidemiológicos
            'casos_municipio' => 'required|integer|min:0',
            'populacao_municipio' => 'required|integer|min:1',
            'semana_epidemiologica' => 'required|integer|min:1|max:53',
            'tendencia_temporal' => 'nullable|numeric',

            // Sintomas clássicos
            'febre' => 'required|boolean',
            'cefaleia' => 'nullable|boolean',
            'mialgia' => 'nullable|boolean',
            'artralgia' => 'nullable|boolean',
            'dor_retro' => 'nullable|boolean',
            'exantema' => 'nullable|boolean',

            // Sintomas inespecíficos
            'nausea' => 'nullable|boolean',
            'vomito' => 'nullable|boolean',
            'dor_costas' => 'nullable|boolean',
            'conjuntvit' => 'nullable|boolean',
            'petequia_n' => 'nullable|boolean',
            'leucopenia' => 'nullable|boolean',
            'laco' => 'nullable|boolean',

            // Sinais de alarme
            'alrm_hipot' => 'nullable|boolean',
            'alrm_plaq' => 'nullable|boolean',
            'alrm_vom' => 'nullable|boolean',
            'alrm_sang' => 'nullable|boolean',
            'alrm_hemat' => 'nullable|boolean',
            'alrm_abdom' => 'nullable|boolean',
            'alrm_letar' => 'nullable|boolean',
            'alrm_hepat' => 'nullable|boolean',
            'alrm_liq' => 'nullable|boolean',

            // Sinais de gravidade
            'grav_pulso' => 'nullable|boolean',
            'grav_conv' => 'nullable|boolean',
            'grav_ench' => 'nullable|boolean',
            'grav_insc' => 'nullable|boolean',
            'grav_extre' => 'nullable|boolean',
            'grav_hipot' => 'nullable|boolean',
            'grav_hemat' => 'nullable|boolean',
            'grav_melen' => 'nullable|boolean',
            'grav_metro' => 'nullable|boolean',
            'grav_sang' => 'nullable|boolean',
            'grav_ast' => 'nullable|boolean',
            'grav_mioc' => 'nullable|boolean',
            'grav_consc' => 'nullable|boolean',
            'grav_orgao' => 'nullable|boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'idade.required' => 'A idade é obrigatória',
            'idade.min' => 'A idade deve ser no mínimo 0',
            'idade.max' => 'A idade deve ser no máximo 120',
            'sexo.required' => 'O sexo é obrigatório',
            'sexo.in' => 'O sexo deve ser M, F ou I',
            'uf.required' => 'A UF é obrigatória',
            'uf.size' => 'A UF deve ter 2 caracteres',
            'municipio.required' => 'O município é obrigatório',
            'casos_municipio.required' => 'O número de casos no município é obrigatório',
            'populacao_municipio.required' => 'A população do município é obrigatória',
            'semana_epidemiologica.required' => 'A semana epidemiológica é obrigatória',
            'semana_epidemiologica.min' => 'A semana deve ser entre 1 e 53',
            'semana_epidemiologica.max' => 'A semana deve ser entre 1 e 53',
            'febre.required' => 'A informação sobre febre é obrigatória',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Normalizar UF para maiúsculas
        if ($this->has('uf')) {
            $this->merge(['uf' => strtoupper($this->uf)]);
        }

        // Normalizar sexo para maiúsculas
        if ($this->has('sexo')) {
            $this->merge(['sexo' => strtoupper($this->sexo)]);
        }
    }
}
