<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VerificarColunasDengue extends Command
{
    protected $signature = 'db:verificar-colunas-dengue';
    protected $description = 'Verifica se as colunas de risco foram criadas na tabela dengue_2025';

    public function handle()
    {
        $this->info('Verificando colunas na tabela dengue_2025...');
        $this->line('');

        try {
            $colunas = DB::getSchemaBuilder()->getColumnListing('dengue_2025');
            
            $colunasEsperadas = [
                'score_epidemiologia',
                'score_gravidade',
                'score_sintomas',
                'score_sociodemografico',
                'score_final',
                'nivel_risco',
                'justificativa',
                'input_json',
            ];

            $this->table(
                ['Coluna', 'Status'],
                collect($colunasEsperadas)->map(function ($coluna) use ($colunas) {
                    $existe = in_array($coluna, $colunas);
                    return [
                        $coluna,
                        $existe ? '<fg=green>✓ EXISTE</>' : '<fg=red>✗ NÃO EXISTE</>'
                    ];
                })
            );

            $this->line('');
            $this->info('Total de colunas na tabela: ' . count($colunas));
            
            $faltam = array_diff($colunasEsperadas, $colunas);
            if (empty($faltam)) {
                $this->info('✓ Todas as colunas necessárias foram criadas!');
            } else {
                $this->error('✗ Faltam ' . count($faltam) . ' coluna(s): ' . implode(', ', $faltam));
                $this->line('');
                $this->warn('Execute: php artisan migrate');
            }

            // Verificar último registro
            $this->line('');
            $this->info('Verificando último registro...');
            $ultimo = DB::table('dengue_2025')->orderByDesc('id')->first();
            
            if ($ultimo) {
                $this->info("ID do último registro: {$ultimo->id}");
                $this->info("Score final: " . ($ultimo->score_final ?? 'NULL'));
                $this->info("Nível de risco: " . ($ultimo->nivel_risco ?? 'NULL'));
            } else {
                $this->warn('Nenhum registro encontrado na tabela.');
            }

        } catch (\Exception $e) {
            $this->error('Erro ao verificar colunas: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
