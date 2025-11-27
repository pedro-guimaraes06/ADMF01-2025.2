# ✅ CHECKLIST DE INSTALAÇÃO - ADMF01-2025.2

Use este checklist para garantir que tudo foi configurado corretamente.

---

## 📋 PRÉ-REQUISITOS

- [ ] Docker Desktop instalado e rodando
- [ ] Docker Compose instalado (geralmente vem com Docker Desktop)
- [ ] Portas 8070 e 8080 disponíveis
- [ ] Mínimo 4GB RAM disponível para Docker

**Verificar:**
```bash
docker --version
docker-compose --version
```

---

## 🚀 INSTALAÇÃO

### Opção A: Automática (Recomendado)

- [ ] Windows: Execute `install.bat`
- [ ] Linux/Mac: Execute `chmod +x install.sh && ./install.sh`

### Opção B: Manual

- [ ] `docker-compose up -d --build`
- [ ] `docker exec -it app-api composer install`
- [ ] `docker exec -it app-api cp .env.example .env`
- [ ] `docker exec -it app-api php artisan key:generate`
- [ ] `docker exec -it app-api chmod -R 777 storage bootstrap/cache`

---

## ✔️ VERIFICAÇÕES PÓS-INSTALAÇÃO

### 1. Containers Docker

```bash
docker-compose ps
```

Deve mostrar:
- [ ] `app-api` com status **Up**
- [ ] `app-front` com status **Up**

### 2. Backend (Laravel)

**Teste 1: API de teste**
- [ ] Abrir http://localhost:8080/api/test no navegador
- [ ] Deve retornar: `{"message":"API funcionando!"}`

**Teste 2: Rotas Laravel**
```bash
docker exec -it app-api php artisan route:list
```
- [ ] Comando executa sem erros
- [ ] Lista pelo menos 2 rotas (GET /api/test e GET /)

**Teste 3: Verificar .env**
```bash
docker exec -it app-api cat .env | grep APP_KEY
```
- [ ] Deve mostrar uma chave: `APP_KEY=base64:...`

**Teste 4: Logs do backend**
```bash
docker logs app-api
```
- [ ] Nenhum erro crítico aparece
- [ ] Apache/PHP iniciou corretamente

### 3. Frontend (Vue + Vuetify)

**Teste 1: Interface carrega**
- [ ] Abrir http://localhost:8070 no navegador
- [ ] Página carrega (pode estar em branco, mas sem erro 404/500)
- [ ] Console do navegador sem erros críticos (F12)

**Teste 2: Hot Reload funcionando**
```bash
docker logs app-front
```
- [ ] Mostra "Compiled successfully"
- [ ] Webpack Dev Server rodando na porta 8070

**Teste 3: Verificar package.json**
```bash
docker exec -it app-front cat package.json | grep vuetify
```
- [ ] Deve mostrar `"vuetify": "^2.3.8"`

### 4. Verificações Estruturais

**Backend:**
- [ ] Existe `backend/vendor/` (pasta criada após composer install)
- [ ] Existe `backend/.env` (copiado de .env.example)
- [ ] `backend/storage/` tem permissões corretas (777)

**Frontend:**
- [ ] Existe `frontend/node_modules/` (criado automaticamente)
- [ ] Arquivo `frontend/.env` existe
- [ ] `frontend/src/main.js` importa vuetify corretamente

---

## 🧪 TESTES FUNCIONAIS

### Teste 1: Requisição do Frontend para Backend

Abra o console do navegador (F12) em http://localhost:8070 e execute:

```javascript
axios.get('http://localhost:8080/api/test')
  .then(res => console.log('✅ Backend respondeu:', res.data))
  .catch(err => console.error('❌ Erro:', err))
```

- [ ] Deve mostrar: `✅ Backend respondeu: {message: "API funcionando!"}`

### Teste 2: Criar um Componente Simples

1. Criar `frontend/src/components/Teste.vue`:
```vue
<template>
  <v-container>
    <v-btn color="primary">Teste Vuetify</v-btn>
  </v-container>
</template>

<script>
export default {
  name: 'Teste'
}
</script>
```

2. Editar `frontend/src/App.vue`:
```vue
<template>
  <v-app>
    <Teste />
  </v-app>
</template>

<script>
import Teste from './components/Teste.vue'

export default {
  name: 'App',
  components: { Teste }
}
</script>
```

- [ ] Salvar arquivo
- [ ] Frontend recarrega automaticamente (hot reload)
- [ ] Botão azul "Teste Vuetify" aparece na tela

### Teste 3: Criar uma Rota API Simples

1. Editar `backend/routes/api.php`:
```php
Route::get('/hello', function () {
    return response()->json([
        'message' => 'Hello from Laravel!',
        'timestamp' => now()
    ]);
});
```

2. Acessar http://localhost:8080/api/hello

- [ ] Retorna JSON com mensagem e timestamp

---

## 🔧 COMANDOS DE MANUTENÇÃO

### Ver Logs em Tempo Real

```bash
# Todos os containers
docker-compose logs -f

# Apenas backend
docker logs app-api -f

# Apenas frontend
docker logs app-front -f
```

### Reiniciar Containers

```bash
# Reiniciar tudo
docker-compose restart

# Reiniciar apenas backend
docker-compose restart app-api

# Reiniciar apenas frontend
docker-compose restart app-front
```

### Limpar e Reconstruir

```bash
# Parar containers
docker-compose down

# Limpar volumes (CUIDADO: apaga dados)
docker-compose down -v

# Reconstruir do zero
docker-compose up -d --build --force-recreate
```

---

## 🚨 SOLUÇÃO DE PROBLEMAS

### ❌ Container não inicia

```bash
docker-compose down
docker system prune -a --volumes
docker-compose up -d --build
```

### ❌ Erro de permissão no backend

```bash
docker exec -it app-api chmod -R 777 storage bootstrap/cache
docker exec -it app-api chown -R www-data:www-data storage bootstrap/cache
```

### ❌ Frontend não compila

```bash
docker-compose down
rm -rf frontend/node_modules
docker-compose up -d --build
```

### ❌ Backend retorna erro 500

```bash
docker exec -it app-api php artisan cache:clear
docker exec -it app-api php artisan config:clear
docker exec -it app-api php artisan route:clear
docker logs app-api
```

---

## 📊 STATUS FINAL

Marque quando tudo estiver OK:

- [ ] ✅ Containers rodando
- [ ] ✅ Backend responde em /api/test
- [ ] ✅ Frontend carrega em localhost:8070
- [ ] ✅ Hot reload funcionando
- [ ] ✅ Requisições entre front e back funcionam
- [ ] ✅ Vuetify renderiza componentes
- [ ] ✅ Laravel serve rotas API
- [ ] ✅ Sem erros críticos nos logs

---

## 🎉 PRONTO PARA DESENVOLVER!

Se todos os itens estão marcados, seu ambiente está 100% funcional!

**Próximos passos:**
1. Ler `QUICKSTART.md` para começar a desenvolver
2. Consultar `INSTALACAO.md` para detalhes técnicos
3. Ver `README.md` para visão geral do projeto

Boa codificação! 🚀
