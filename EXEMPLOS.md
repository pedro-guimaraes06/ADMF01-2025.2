# 📚 EXEMPLOS DE CÓDIGO - ADMF01-2025.2

Exemplos práticos para começar a desenvolver rapidamente.

---

## 🔧 BACKEND (Laravel 7)

### 1. Criar um Controller

```bash
docker exec -it app-api php artisan make:controller UserController
```

Edite `backend/app/Http/Controllers/UserController.php`:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return response()->json([
            'users' => [
                ['id' => 1, 'name' => 'João Silva'],
                ['id' => 2, 'name' => 'Maria Santos']
            ]
        ]);
    }

    public function show($id)
    {
        return response()->json([
            'user' => ['id' => $id, 'name' => 'Usuário ' . $id]
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users'
        ]);

        return response()->json([
            'message' => 'Usuário criado com sucesso!',
            'user' => $validated
        ], 201);
    }
}
```

### 2. Registrar Rotas API

Edite `backend/routes/api.php`:

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// Rota de teste
Route::get('/test', function () {
    return response()->json(['message' => 'API funcionando!']);
});

// Rotas de usuários
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store']);

// Rotas com autenticação (exemplo)
Route::middleware('auth:api')->group(function () {
    Route::get('/profile', function () {
        return response()->json(['user' => auth()->user()]);
    });
});
```

### 3. Criar um Model

```bash
docker exec -it app-api php artisan make:model Product
```

Edite `backend/app/Models/Product.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
```

### 4. Criar um Service

Crie `backend/app/Services/ProductService.php`:

```php
<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    public function getAllProducts()
    {
        return Product::all();
    }

    public function findProduct($id)
    {
        return Product::findOrFail($id);
    }

    public function createProduct(array $data)
    {
        return Product::create($data);
    }

    public function updateProduct($id, array $data)
    {
        $product = $this->findProduct($id);
        $product->update($data);
        return $product;
    }

    public function deleteProduct($id)
    {
        $product = $this->findProduct($id);
        $product->delete();
        return true;
    }
}
```

### 5. Middleware Customizado

```bash
docker exec -it app-api php artisan make:middleware CheckApiKey
```

Edite `backend/app/Http/Middleware/CheckApiKey.php`:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckApiKey
{
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('X-API-Key');

        if ($apiKey !== env('API_KEY')) {
            return response()->json([
                'error' => 'API Key inválida'
            ], 401);
        }

        return $next($request);
    }
}
```

Registre em `backend/app/Http/Kernel.php`:

```php
protected $routeMiddleware = [
    // ... outros middlewares
    'api.key' => \App\Http\Middleware\CheckApiKey::class,
];
```

---

## 🎨 FRONTEND (Vue 2 + Vuetify)

### 1. Componente Simples

Crie `frontend/src/components/UI/Button.vue`:

```vue
<template>
  <v-btn
    :color="color"
    :loading="loading"
    :disabled="disabled"
    @click="handleClick"
  >
    <v-icon left v-if="icon">{{ icon }}</v-icon>
    {{ text }}
  </v-btn>
</template>

<script>
export default {
  name: 'Button',
  props: {
    text: {
      type: String,
      required: true
    },
    color: {
      type: String,
      default: 'primary'
    },
    icon: String,
    loading: Boolean,
    disabled: Boolean
  },
  methods: {
    handleClick() {
      this.$emit('click')
    }
  }
}
</script>
```

### 2. Componente de Lista

Crie `frontend/src/components/UI/DataTable.vue`:

```vue
<template>
  <v-data-table
    :headers="headers"
    :items="items"
    :loading="loading"
    :search="search"
    class="elevation-1"
  >
    <template v-slot:top>
      <v-toolbar flat>
        <v-toolbar-title>{{ title }}</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Buscar"
          single-line
          hide-details
        ></v-text-field>
      </v-toolbar>
    </template>

    <template v-slot:item.actions="{ item }">
      <v-icon small class="mr-2" @click="editItem(item)">
        mdi-pencil
      </v-icon>
      <v-icon small @click="deleteItem(item)">
        mdi-delete
      </v-icon>
    </template>
  </v-data-table>
</template>

<script>
export default {
  name: 'DataTable',
  props: {
    title: String,
    headers: Array,
    items: Array,
    loading: Boolean
  },
  data() {
    return {
      search: ''
    }
  },
  methods: {
    editItem(item) {
      this.$emit('edit', item)
    },
    deleteItem(item) {
      this.$emit('delete', item)
    }
  }
}
</script>
```

### 3. Página com Requisições API

Crie `frontend/src/modules/dashboard/Users.vue`:

```vue
<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <h1 class="mb-4">Usuários</h1>
        
        <DataTable
          title="Lista de Usuários"
          :headers="headers"
          :items="users"
          :loading="loading"
          @edit="editUser"
          @delete="deleteUser"
        />
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import DataTable from '@/components/UI/DataTable.vue'

export default {
  name: 'Users',
  components: {
    DataTable
  },
  data() {
    return {
      users: [],
      loading: false,
      headers: [
        { text: 'ID', value: 'id' },
        { text: 'Nome', value: 'name' },
        { text: 'Email', value: 'email' },
        { text: 'Ações', value: 'actions', sortable: false }
      ]
    }
  },
  mounted() {
    this.loadUsers()
  },
  methods: {
    async loadUsers() {
      this.loading = true
      try {
        const response = await this.$axios.get('/users')
        this.users = response.data.users
      } catch (error) {
        this.$toast.error('Erro ao carregar usuários')
        console.error(error)
      } finally {
        this.loading = false
      }
    },
    editUser(user) {
      console.log('Editar:', user)
      this.$router.push(`/users/${user.id}/edit`)
    },
    async deleteUser(user) {
      const confirm = await this.$swal({
        title: 'Confirmar exclusão?',
        text: `Deseja excluir ${user.name}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir',
        cancelButtonText: 'Cancelar'
      })

      if (confirm.isConfirmed) {
        try {
          await this.$axios.delete(`/users/${user.id}`)
          this.$toast.success('Usuário excluído com sucesso')
          this.loadUsers()
        } catch (error) {
          this.$toast.error('Erro ao excluir usuário')
        }
      }
    }
  }
}
</script>
```

### 4. Formulário com Validação

Crie `frontend/src/modules/dashboard/UserForm.vue`:

```vue
<template>
  <v-container>
    <v-row justify="center">
      <v-col cols="12" md="8">
        <v-card>
          <v-card-title>{{ isEdit ? 'Editar' : 'Novo' }} Usuário</v-card-title>
          
          <v-card-text>
            <v-form ref="form" v-model="valid">
              <v-text-field
                v-model="form.name"
                label="Nome"
                :rules="[rules.required]"
                required
              ></v-text-field>

              <v-text-field
                v-model="form.email"
                label="Email"
                :rules="[rules.required, rules.email]"
                required
              ></v-text-field>

              <v-text-field
                v-model="form.phone"
                label="Telefone"
                v-mask="'(##) #####-####'"
              ></v-text-field>
            </v-form>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn text @click="$router.back()">Cancelar</v-btn>
            <v-btn
              color="primary"
              :loading="loading"
              :disabled="!valid"
              @click="submit"
            >
              Salvar
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: 'UserForm',
  data() {
    return {
      valid: false,
      loading: false,
      isEdit: false,
      form: {
        name: '',
        email: '',
        phone: ''
      },
      rules: {
        required: v => !!v || 'Campo obrigatório',
        email: v => /.+@.+\..+/.test(v) || 'Email inválido'
      }
    }
  },
  mounted() {
    if (this.$route.params.id) {
      this.isEdit = true
      this.loadUser()
    }
  },
  methods: {
    async loadUser() {
      const id = this.$route.params.id
      try {
        const response = await this.$axios.get(`/users/${id}`)
        this.form = response.data.user
      } catch (error) {
        this.$toast.error('Erro ao carregar usuário')
        this.$router.back()
      }
    },
    async submit() {
      if (!this.$refs.form.validate()) return

      this.loading = true
      try {
        if (this.isEdit) {
          await this.$axios.put(`/users/${this.$route.params.id}`, this.form)
          this.$toast.success('Usuário atualizado com sucesso')
        } else {
          await this.$axios.post('/users', this.form)
          this.$toast.success('Usuário criado com sucesso')
        }
        this.$router.push('/users')
      } catch (error) {
        this.$toast.error('Erro ao salvar usuário')
      } finally {
        this.loading = false
      }
    }
  }
}
</script>
```

### 5. Configurar Rotas

Edite `frontend/src/router/index.js`:

```javascript
import Vue from 'vue'
import VueRouter from 'vue-router'

// Importar componentes
import Users from '@/modules/dashboard/Users.vue'
import UserForm from '@/modules/dashboard/UserForm.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/users',
    name: 'Users',
    component: Users
  },
  {
    path: '/users/new',
    name: 'UserNew',
    component: UserForm
  },
  {
    path: '/users/:id/edit',
    name: 'UserEdit',
    component: UserForm
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
```

### 6. Mixin Utilitário

Edite `frontend/src/mixins/utils.mixin.js`:

```javascript
export default {
  methods: {
    formatCurrency(value) {
      return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
      }).format(value)
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString('pt-BR')
    },
    formatDateTime(date) {
      return new Date(date).toLocaleString('pt-BR')
    },
    copyToClipboard(text) {
      navigator.clipboard.writeText(text)
      this.$toast.success('Copiado para a área de transferência')
    }
  }
}
```

Use em componentes:

```vue
<script>
import utilsMixin from '@/mixins/utils.mixin.js'

export default {
  mixins: [utilsMixin],
  data() {
    return {
      price: 1250.50
    }
  },
  computed: {
    formattedPrice() {
      return this.formatCurrency(this.price) // R$ 1.250,50
    }
  }
}
</script>
```

---

## 🔐 AUTENTICAÇÃO (JWT)

### Backend - Configurar Passport

```bash
docker exec -it app-api php artisan passport:install
```

Edite `backend/app/Models/User.php`:

```php
<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password', 'remember_token'];
}
```

Crie `backend/app/Http/Controllers/AuthController.php`:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['error' => 'Credenciais inválidas'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->accessToken;

        return response()->json([
            'user' => $user,
            'access_token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Logout realizado']);
    }
}
```

### Frontend - Interceptor Axios

Edite `frontend/src/api/index.js`:

```javascript
import Vue from 'vue'
import axios from 'axios'
import VueAxios from 'vue-axios'
import { getToken } from './jwt'

const Api = {
  init() {
    Vue.use(VueAxios, axios)
    Vue.axios.defaults.baseURL = process.env.VUE_APP_API_URL
    Vue.axios.defaults.headers.common['Content-Type'] = 'application/json'
    Vue.axios.defaults.headers.common['Accept'] = 'application/json'

    // Interceptor para adicionar token
    Vue.axios.interceptors.request.use(
      config => {
        const token = getToken()
        if (token) {
          config.headers.Authorization = `Bearer ${token}`
        }
        return config
      },
      error => Promise.reject(error)
    )

    // Interceptor para tratar erros
    Vue.axios.interceptors.response.use(
      response => response,
      error => {
        if (error.response && error.response.status === 401) {
          // Redirecionar para login
          window.location.href = '/login'
        }
        return Promise.reject(error)
      }
    )
  }
}

export default Api
```

---

## 🎉 Pronto!

Com esses exemplos, você já pode começar a desenvolver sua aplicação completa!

**Dica**: Combine esses exemplos e adapte conforme sua necessidade.
