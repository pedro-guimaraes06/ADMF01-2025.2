#!/bin/bash

echo "🌐 Iniciando servidor SAD Dengue API..."
echo "📍 Porta: $PORT"
echo "🔗 Endpoint: http://0.0.0.0:$PORT"

# Iniciar servidor PHP embutido
php -S 0.0.0.0:$PORT -t public public/index.php
