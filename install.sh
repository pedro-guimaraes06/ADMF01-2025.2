#!/bin/bash

echo "=========================================="
echo "  INSTALAÇÃO ADMF01-2025.2"
echo "  Laravel 7 + Vue 2 + Vuetify + Docker"
echo "=========================================="
echo ""

# Verificar se Docker está instalado
if ! command -v docker &> /dev/null; then
    echo "❌ Docker não encontrado. Instale o Docker Desktop primeiro."
    exit 1
fi

echo "✓ Docker encontrado"

# Verificar se Docker Compose está instalado
if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose não encontrado."
    exit 1
fi

echo "✓ Docker Compose encontrado"
echo ""

# Subir containers
echo "📦 Construindo e iniciando containers..."
docker-compose up -d --build

# Aguardar containers iniciarem
echo ""
echo "⏳ Aguardando containers iniciarem..."
sleep 10

# Verificar status dos containers
echo ""
echo "📊 Status dos containers:"
docker-compose ps

# Instalar dependências do Laravel
echo ""
echo "📥 Instalando dependências do Laravel..."
docker exec -it app-api composer install

# Configurar .env
echo ""
echo "⚙️ Configurando arquivo .env..."
docker exec -it app-api cp .env.example .env

# Gerar APP_KEY
echo ""
echo "🔑 Gerando chave da aplicação..."
docker exec -it app-api php artisan key:generate

# Ajustar permissões
echo ""
echo "🔐 Ajustando permissões..."
docker exec -it app-api chmod -R 777 storage bootstrap/cache

# Finalização
echo ""
echo "=========================================="
echo "  ✅ INSTALAÇÃO CONCLUÍDA!"
echo "=========================================="
echo ""
echo "🌐 Acesse:"
echo "   Frontend: http://localhost:8070"
echo "   Backend:  http://localhost:8080/api/test"
echo ""
echo "📚 Consulte INSTALACAO.md para mais detalhes"
echo ""
