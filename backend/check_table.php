<?php

$db = new PDO('sqlite:database/dengue_2025.sqlite');

// Listar todas as tabelas
echo "=== TABELAS NO BANCO ===\n";
$tables = $db->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);
foreach ($tables as $table) {
    echo "- $table\n";
}

echo "\n=== ESTRUTURA DA PRIMEIRA TABELA ===\n";
if (!empty($tables)) {
    $firstTable = $tables[0];
    echo "Tabela: $firstTable\n\n";
    
    $columns = $db->query("PRAGMA table_info($firstTable)")->fetchAll(PDO::FETCH_ASSOC);
    echo "Colunas:\n";
    foreach ($columns as $col) {
        echo sprintf("  - %s (%s)%s\n", 
            $col['name'], 
            $col['type'],
            $col['pk'] ? ' PRIMARY KEY' : ''
        );
    }
    
    echo "\nTotal de colunas: " . count($columns) . "\n";
}
