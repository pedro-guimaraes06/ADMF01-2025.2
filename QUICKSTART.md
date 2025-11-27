# Quick Start - ADMF01-2025.2

## ⚡ Instalação Rápida

```bash
# Windows
install.bat

# Linux/Mac
chmod +x install.sh
./install.sh
```

## 🌐 URLs

- **Frontend**: http://localhost:8070
- **Backend API**: http://localhost:8080/api/test

## 🛠️ Desenvolvimento

### Backend - Criar Nova Rota API

Edite `backend/routes/api.php`:

```php
Route::get('/users', function () {
    return response()->json(['users' => []]);
});
```

### Frontend - Criar Novo Componente

1. Crie `frontend/src/components/MeuComponente.vue`:

```vue
<template>
  <v-container>
    <h1>Meu Componente</h1>
  </v-container>
</template>

<script>
export default {
  name: 'MeuComponente'
}
</script>
```

2. Use no `App.vue` ou em rotas

### Frontend - Criar Nova Rota

Edite `frontend/src/router/index.js`:

```javascript
import MeuComponente from '@/components/MeuComponente.vue'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: MeuComponente
  }
]
```

## 📦 Comandos Úteis

```bash
# Ver logs
docker-compose logs -f

# Reiniciar containers
docker-compose restart

# Parar containers
docker-compose down

# Backend: Acessar container
docker exec -it app-api bash

# Frontend: Acessar container
docker exec -it app-front sh
```

## 🔄 Hot Reload

- **Frontend**: Ativo automaticamente (Webpack Dev Server)
- **Backend**: Reinicie o container após mudanças em configs

## 🎨 Vuetify

Componentes disponíveis: https://v2.vuetifyjs.com/

Exemplo:

```vue
<template>
  <v-btn color="primary">Botão</v-btn>
</template>
```

## 📡 Fazer Requisição API

No Vue:

```javascript
// Em um método do componente
async getData() {
  try {
    const response = await this.$axios.get('/test')
    console.log(response.data)
  } catch (error) {
    console.error(error)
  }
}
```

Já está configurado para usar `http://localhost:8080/api/` como base URL.

## 🗄️ Banco de Dados (Opcional)

Para usar MySQL:

1. Edite `docker-compose.yml` e adicione:

```yaml
db:
  image: mysql:5.7
  environment:
    MYSQL_ROOT_PASSWORD: root
    MYSQL_DATABASE: laravel
  ports:
    - "3306:3306"
  networks:
    - app-network
```

2. Atualize `backend/.env`:

```env
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=root
```

3. Execute migrations:

```bash
docker exec -it app-api php artisan migrate
```

---

**Documentação completa**: Veja `INSTALACAO.md` e `README.md`
