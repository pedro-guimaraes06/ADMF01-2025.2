<?php

namespace App\Helpers;

class IBGEHelper
{
    /**
     * Mapeia código de UF para nome
     */
    public static function getNomeUF(string $codigo): string
    {
        $ufs = [
            '11' => 'Rondônia',
            '12' => 'Acre',
            '13' => 'Amazonas',
            '14' => 'Roraima',
            '15' => 'Pará',
            '16' => 'Amapá',
            '17' => 'Tocantins',
            '21' => 'Maranhão',
            '22' => 'Piauí',
            '23' => 'Ceará',
            '24' => 'Rio Grande do Norte',
            '25' => 'Paraíba',
            '26' => 'Pernambuco',
            '27' => 'Alagoas',
            '28' => 'Sergipe',
            '29' => 'Bahia',
            '31' => 'Minas Gerais',
            '32' => 'Espírito Santo',
            '33' => 'Rio de Janeiro',
            '35' => 'São Paulo',
            '41' => 'Paraná',
            '42' => 'Santa Catarina',
            '43' => 'Rio Grande do Sul',
            '50' => 'Mato Grosso do Sul',
            '51' => 'Mato Grosso',
            '52' => 'Goiás',
            '53' => 'Distrito Federal',
        ];
        
        return $ufs[$codigo] ?? $codigo;
    }
    
    /**
     * Mapeia código de UF para sigla
     */
    public static function getSiglaUF(string $codigo): string
    {
        $siglas = [
            '11' => 'RO',
            '12' => 'AC',
            '13' => 'AM',
            '14' => 'RR',
            '15' => 'PA',
            '16' => 'AP',
            '17' => 'TO',
            '21' => 'MA',
            '22' => 'PI',
            '23' => 'CE',
            '24' => 'RN',
            '25' => 'PB',
            '26' => 'PE',
            '27' => 'AL',
            '28' => 'SE',
            '29' => 'BA',
            '31' => 'MG',
            '32' => 'ES',
            '33' => 'RJ',
            '35' => 'SP',
            '41' => 'PR',
            '42' => 'SC',
            '43' => 'RS',
            '50' => 'MS',
            '51' => 'MT',
            '52' => 'GO',
            '53' => 'DF',
        ];
        
        return $siglas[$codigo] ?? $codigo;
    }
    
    /**
     * Retorna nome do município baseado no código IBGE
     * Como não temos tabela de municípios, vamos mapear apenas os principais
     * que aparecem nos dados
     */
    public static function getNomeMunicipio(string $codigoIBGE): string
    {
        // Remove .0 apenas do final se existir
        $codigo = str_replace('.0', '', trim($codigoIBGE));
        
        // Mapeia os principais municípios
        $municipios = [
            // Acre (12)
            '120020' => 'Cruzeiro do Sul',
            '120030' => 'Feijó',
            '120040' => 'Rio Branco',
            '120042' => 'Porto Acre',
            
            // Bahia (29)
            '290570' => 'Camaçari',
            '291070' => 'Eunápolis',
            '291080' => 'Feira de Santana',
            '291360' => 'Ilhéus',
            '291480' => 'Itabuna',
            '292150' => 'Lauro de Freitas',
            '292740' => 'Salvador',
            '293135' => 'Teixeira de Freitas',
            '293330' => 'Vitória da Conquista',
            
            // Alagoas (27)
            '270430' => 'Maceió',
            '270860' => 'União dos Palmares',
            
            // Amazonas (13)
            '130020' => 'Alvarães',
            '130260' => 'Manaus',
            
            // Amapá (16)
            '160030' => 'Macapá',
            
            // São Paulo (35)
            '355030' => 'São Paulo',
            '354330' => 'Santo André',
            '354870' => 'São Bernardo do Campo',
            '350600' => 'Campinas',
            '354780' => 'Santos',
            
            // Rio de Janeiro (33)
            '330455' => 'Rio de Janeiro',
            '330170' => 'Duque de Caxias',
            '330250' => 'Niterói',
            
            // Minas Gerais (31)
            '310620' => 'Belo Horizonte',
            '313670' => 'Uberlândia',
            '311860' => 'Contagem',
            
            // Paraná (41)
            '410690' => 'Curitiba',
            '411370' => 'Londrina',
            '410830' => 'Foz do Iguaçu',
        ];
        
        return $municipios[$codigo] ?? "Município $codigo";
    }
}
