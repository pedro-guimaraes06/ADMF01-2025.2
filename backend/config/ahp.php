<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Pesos dos Critérios AHP - SAD Dengue
    |--------------------------------------------------------------------------
    |
    | Método AHP (Analytic Hierarchy Process) para avaliação de risco.
    | Os pesos representam a importância relativa de cada critério.
    | Soma total deve ser 1.0 (100%)
    |
    */

    'pesos_criterios' => [
        'epidemiologia' => 0.45,      // 45% - Maior peso (contexto local)
        'gravidade' => 0.35,          // 35% - Sinais clínicos graves
        'sintomas' => 0.15,           // 15% - Sintomas gerais
        'sociodemografico' => 0.05,   // 5%  - Fatores demográficos
    ],

    /*
    |--------------------------------------------------------------------------
    | Subpesos do Critério Epidemiologia (45%)
    |--------------------------------------------------------------------------
    */
    'epidemiologia' => [
        'incidencia_municipal' => 0.50,    // 50% do critério epidemiologia
        'tendencia_temporal' => 0.30,      // 30% do critério epidemiologia
        'semana_epidemiologica' => 0.20,   // 20% do critério epidemiologia
    ],

    /*
    |--------------------------------------------------------------------------
    | Subpesos do Critério Gravidade Clínica (35%)
    |--------------------------------------------------------------------------
    */
    'gravidade' => [
        'sinais_alarme' => 0.60,     // 60% do critério gravidade
        'sinais_gravidade' => 0.40,  // 40% do critério gravidade
    ],

    /*
    |--------------------------------------------------------------------------
    | Subpesos do Critério Sintomas (15%)
    |--------------------------------------------------------------------------
    */
    'sintomas' => [
        'sintomas_classicos' => 0.70,    // 70% do critério sintomas
        'sintomas_inespecificos' => 0.30, // 30% do critério sintomas
    ],

    /*
    |--------------------------------------------------------------------------
    | Subpesos do Critério Sociodemográfico (5%)
    |--------------------------------------------------------------------------
    */
    'sociodemografico' => [
        'idade' => 0.60,      // 60% do critério sociodemográfico
        'comorbidades' => 0.40, // 40% do critério sociodemográfico (quando implementado)
    ],

    /*
    |--------------------------------------------------------------------------
    | Classificação de Risco por Score
    |--------------------------------------------------------------------------
    */
    'niveis_risco' => [
        'baixo' => [
            'min' => 0.0,
            'max' => 0.33,
            'label' => 'Baixo',
            'cor' => '#4CAF50', // Verde
        ],
        'medio' => [
            'min' => 0.34,
            'max' => 0.66,
            'label' => 'Médio',
            'cor' => '#FF9800', // Laranja
        ],
        'alto' => [
            'min' => 0.67,
            'max' => 1.0,
            'label' => 'Alto',
            'cor' => '#F44336', // Vermelho
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Parâmetros de Normalização
    |--------------------------------------------------------------------------
    */
    'normalizacao' => [
        'idade_min' => 0,
        'idade_max' => 120,
        'incidencia_max' => 500,  // Casos por 100k habitantes
        'sintomas_max' => 15,
        'alarmes_max' => 9,
        'gravidade_max' => 14,
    ],

    /*
    |--------------------------------------------------------------------------
    | Sintomas Clássicos de Dengue
    |--------------------------------------------------------------------------
    */
    'sintomas_classicos' => [
        'FEBRE',
        'CEFALEIA',
        'MIALGIA',
        'ARTRALGIA',
        'DOR_RETRO',
        'EXANTEMA',
    ],

    /*
    |--------------------------------------------------------------------------
    | Sintomas Inespecíficos
    |--------------------------------------------------------------------------
    */
    'sintomas_inespecificos' => [
        'NAUSEA',
        'VOMITO',
        'DOR_COSTAS',
        'CONJUNTVIT',
        'PETEQUIA_N',
        'LEUCOPENIA',
        'LACO',
    ],

    /*
    |--------------------------------------------------------------------------
    | Sinais de Alarme
    |--------------------------------------------------------------------------
    */
    'sinais_alarme' => [
        'ALRM_HIPOT',
        'ALRM_PLAQ',
        'ALRM_VOM',
        'ALRM_SANG',
        'ALRM_HEMAT',
        'ALRM_ABDOM',
        'ALRM_LETAR',
        'ALRM_HEPAT',
        'ALRM_LIQ',
    ],

    /*
    |--------------------------------------------------------------------------
    | Sinais de Gravidade
    |--------------------------------------------------------------------------
    */
    'sinais_gravidade' => [
        'GRAV_PULSO',
        'GRAV_CONV',
        'GRAV_ENCH',
        'GRAV_INSC',
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
    ],
];
