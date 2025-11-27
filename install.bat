@echo off
echo ==========================================
echo   INSTALACAO ADMF01-2025.2
echo   Laravel 7 + Vue 2 + Vuetify + Docker
echo ==========================================
echo.

REM Verificar se Docker esta instalado
docker --version >nul 2>&1
if %errorlevel% neq 0 (
    echo X Docker nao encontrado. Instale o Docker Desktop primeiro.
    pause
    exit /b 1
)

echo * Docker encontrado

REM Verificar se Docker Compose esta instalado
docker-compose --version >nul 2>&1
if %errorlevel% neq 0 (
    echo X Docker Compose nao encontrado.
    pause
    exit /b 1
)

echo * Docker Compose encontrado
echo.

REM Subir containers
echo Construindo e iniciando containers...
docker-compose up -d --build

REM Aguardar containers iniciarem
echo.
echo Aguardando containers iniciarem...
timeout /t 10 /nobreak >nul

REM Verificar status dos containers
echo.
echo Status dos containers:
docker-compose ps

REM Instalar dependencias do Laravel
echo.
echo Instalando dependencias do Laravel...
docker exec -it app-api composer install

REM Configurar .env
echo.
echo Configurando arquivo .env...
docker exec -it app-api cp .env.example .env

REM Gerar APP_KEY
echo.
echo Gerando chave da aplicacao...
docker exec -it app-api php artisan key:generate

REM Ajustar permissoes
echo.
echo Ajustando permissoes...
docker exec -it app-api chmod -R 777 storage bootstrap/cache

REM Finalizacao
echo.
echo ==========================================
echo   INSTALACAO CONCLUIDA!
echo ==========================================
echo.
echo Acesse:
echo    Frontend: http://localhost:8070
echo    Backend:  http://localhost:8080/api/test
echo.
echo Consulte INSTALACAO.md para mais detalhes
echo.
pause
