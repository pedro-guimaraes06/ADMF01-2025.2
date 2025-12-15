import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    redirect: '/dashboard'
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: () => import('@/views/Dashboard.vue'),
    meta: {
      title: 'Dashboard Epidemiológico'
    }
  },
  {
    path: '/avaliacao',
    name: 'Avaliacao',
    component: () => import('@/views/AvaliacaoRisco.vue'),
    meta: {
      title: 'Avaliação de Risco'
    }
  },
  {
    path: '/resultado/:id',
    name: 'Resultado',
    component: () => import('@/views/ResultadoAvaliacao.vue'),
    meta: {
      title: 'Resultado da Avaliação'
    }
  },
  {
    path: '/historico',
    name: 'Historico',
    component: () => import('@/views/HistoricoAvaliacoes.vue'),
    meta: {
      title: 'Histórico de Avaliações'
    }
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: () => import('@/views/Dashboard.vue'),
    meta: {
      title: 'Dashboard Epidemiológico'
    }
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

// Navegação guard para atualizar título
router.beforeEach((to, from, next) => {
  document.title = to.meta.title ? `${to.meta.title} - SAD Dengue` : 'SAD Dengue'
  next()
})

export default router
