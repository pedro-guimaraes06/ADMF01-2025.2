#!/bin/bash
set -e

echo "🚀 Iniciando build do SAD Dengue API..."

# 1. Instalar dependências do Composer
echo "📦 Instalando dependências..."
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# 2. Criar diretório de logs se não existir
echo "📝 Configurando storage..."
mkdir -p storage/logs
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p bootstrap/cache

# 3. Configurar permissões
echo "🔐 Configurando permissões..."
chmod -R 775 storage bootstrap/cache

# 4. Limpar caches de configuração
echo "🧹 Limpando caches..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan cache:clear || true

# 5. Criar banco de dados SQLite se não existir
echo "🗄️ Configurando banco de dados..."
if [ ! -f /var/data/sad_dengue.sqlite ]; then
    echo "📄 Criando arquivo de banco de dados..."
    touch /var/data/sad_dengue.sqlite
    chmod 664 /var/data/sad_dengue.sqlite
fi

# 6. Executar migrations
echo "🔄 Executando migrations..."
php artisan migrate --force

echo "✅ Build concluído com sucesso!"
