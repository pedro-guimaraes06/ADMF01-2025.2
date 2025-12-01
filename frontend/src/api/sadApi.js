import axios from 'axios'

const api = axios.create({
  baseURL: process.env.VUE_APP_API_URL || 'http://localhost:8080/api',
  timeout: 30000,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
})

// Interceptor para requisições
api.interceptors.request.use(
  config => {
    // Aqui você pode adicionar token JWT se necessário
    // const token = localStorage.getItem('token')
    // if (token) {
    //   config.headers.Authorization = `Bearer ${token}`
    // }
    return config
  },
  error => {
    return Promise.reject(error)
  }
)

// Interceptor para respostas
api.interceptors.response.use(
  response => response,
  error => {
    if (error.response) {
      // Tratamento de erros HTTP
      console.error('Erro na API:', error.response.data)
    }
    return Promise.reject(error)
  }
)

export default api
