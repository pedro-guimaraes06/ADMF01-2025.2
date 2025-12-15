# 🛠️ Comandos Úteis - Render Shell

## Acessar Shell

No painel do Render: **Shell** (ícone de terminal)

## Comandos Laravel

### Verificar Status
```bash
# Ver status geral
php artisan --version

# Listar rotas
php artisan route:list

# Ver configuração atual
php artisan config:show

# Verificar conexão com banco
php artisan tinker
>>> \DB::connection()->getPdo();
>>> exit
```

### Limpar Caches
```bash
# Limpar tudo
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Ou em um comando só
php artisan optimize:clear
```

### Migrations
```bash
# Executar migrations
php artisan migrate --force

# Ver status das migrations
php artisan migrate:status

# Rollback última migration
php artisan migrate:rollback --step=1

# Resetar tudo e rodar novamente
php artisan migrate:fresh --force
```

### Verificar Banco de Dados
```bash
# Via Tinker
php artisan tinker

# Listar tabelas
>>> \Schema::getTableListing();

# Ver estrutura de uma tabela
>>> \DB::select("PRAGMA table_info(dengue_2025)");

# Contar registros
>>> \DB::table('dengue_2025')->count();

# Ver primeiros registros
>>> \DB::table('dengue_2025')->limit(3)->get();

# Ver registros de avaliações
>>> \App\Models\AvaliacaoRisco::count();
>>> \App\Models\AvaliacaoRisco::latest()->first();

# Sair
>>> exit
```

## Comandos de Sistema

### Verificar Arquivos
```bash
# Ver estrutura de diretórios
ls -la

# Ver arquivos de configuração
ls -la config/

# Ver migrations
ls -la database/migrations/

# Verificar banco de dados
ls -la /var/data/

# Ver tamanho do banco
du -h /var/data/sad_dengue.sqlite

# Ver permissões
ls -la /var/data/sad_dengue.sqlite
```

### Logs
```bash
# Ver logs do Laravel
tail -f storage/logs/laravel.log

# Ver últimas 50 linhas
tail -n 50 storage/logs/laravel.log

# Ver logs com grep
tail -n 100 storage/logs/laravel.log | grep ERROR

# Limpar logs
> storage/logs/laravel.log
```

### Verificar Ambiente
```bash
# Ver variáveis de ambiente
env | grep APP_
env | grep DB_

# Ver versão PHP
php -v

# Ver extensões PHP
php -m

# Ver configuração PHP
php -i | grep sqlite

# Verificar Composer
composer --version
```

## Troubleshooting

### Erro 500
```bash
# 1. Limpar caches
php artisan config:clear
php artisan route:clear
php artisan cache:clear

# 2. Verificar APP_KEY
env | grep APP_KEY

# 3. Ver logs
tail -f storage/logs/laravel.log
```

### Banco não conecta
```bash
# 1. Verificar arquivo existe
ls -la /var/data/sad_dengue.sqlite

# 2. Verificar permissões
chmod 664 /var/data/sad_dengue.sqlite

# 3. Criar se não existir
touch /var/data/sad_dengue.sqlite
chmod 664 /var/data/sad_dengue.sqlite

# 4. Rodar migrations
php artisan migrate --force

# 5. Testar conexão
php artisan tinker
>>> \DB::connection()->getPdo();
```

### CORS não funciona
```bash
# 1. Ver configuração CORS
cat config/cors.php

# 2. Verificar middleware
php artisan route:list | grep api

# 3. Limpar config
php artisan config:clear
```

### Performance
```bash
# Otimizar autoloader
composer dump-autoload -o

# Cachear configurações (cuidado com env vars)
php artisan config:cache
php artisan route:cache

# Limpar caches se der problema
php artisan config:clear
php artisan route:clear
```

## Backup do Banco de Dados

### Download via Shell (limitado)
```bash
# Fazer cópia
cp /var/data/sad_dengue.sqlite /tmp/backup.sqlite

# Ver tamanho
du -h /tmp/backup.sqlite
```

### Export de dados via SQL
```bash
php artisan tinker

# Exportar dados em JSON
>>> $data = \DB::table('dengue_2025')->get();
>>> file_put_contents('/tmp/dengue_export.json', $data->toJson());
>>> exit
```

## Testar API Localmente no Shell

### Health Check
```bash
curl http://localhost:$PORT/api/status
```

### Testar Endpoint de Risco
```bash
curl -X POST http://localhost:$PORT/api/risco/avaliar \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "febre": 1,
    "mialgia": 1,
    "cefaleia": 1,
    "idade": 35,
    "sexo": "F",
    "uf": "BA"
  }'
```

## Informações Úteis

### Paths Importantes
- **Aplicação**: `/opt/render/project/src/backend/`
- **Banco de Dados**: `/var/data/sad_dengue.sqlite`
- **Logs**: `/opt/render/project/src/backend/storage/logs/`
- **Cache**: `/opt/render/project/src/backend/storage/framework/cache/`

### Variáveis de Ambiente do Render
```bash
# Ver todas
env

# Principais
echo $PORT          # Porta do servidor
echo $RENDER        # true se estiver no Render
echo $IS_PULL_REQUEST  # true se for PR
```

## Comandos de Emergência

### Se tudo der errado
```bash
# 1. Limpar tudo
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# 2. Recriar banco
rm /var/data/sad_dengue.sqlite
touch /var/data/sad_dengue.sqlite
chmod 664 /var/data/sad_dengue.sqlite

# 3. Rodar migrations
php artisan migrate --force

# 4. Testar
php artisan tinker
>>> \DB::connection()->getPdo();
>>> exit
```

### Forçar Redeploy
No painel do Render: **Manual Deploy** → **Deploy latest commit**

---

**💡 Dica**: Sempre limpe os caches após mudanças de configuração!
