import riscoService from '@/api/riscoService'

const state = {
  estatisticasGerais: null,
  casosPorUF: [],
  topMunicipios: [],
  tendencia: null,
  loading: false,
  erro: null
}

const getters = {
  estatisticasGerais: state => state.estatisticasGerais,
  casosPorUF: state => state.casosPorUF,
  topMunicipios: state => state.topMunicipios,
  tendencia: state => state.tendencia,
  isLoading: state => state.loading,
  erro: state => state.erro
}

const mutations = {
  SET_LOADING(state, loading) {
    state.loading = loading
  },

  SET_ERRO(state, erro) {
    state.erro = erro
  },

  SET_ESTATISTICAS_GERAIS(state, estatisticas) {
    state.estatisticasGerais = estatisticas
  },

  SET_CASOS_POR_UF(state, casos) {
    state.casosPorUF = casos
  },

  SET_TOP_MUNICIPIOS(state, municipios) {
    state.topMunicipios = municipios
  },

  SET_TENDENCIA(state, tendencia) {
    state.tendencia = tendencia
  },

  CLEAR_ERRO(state) {
    state.erro = null
  }
}

const actions = {
  async carregarEstatisticas({ commit }) {
    commit('SET_LOADING', true)
    commit('CLEAR_ERRO')

    try {
      const response = await riscoService.estatisticasGerais()
      
      if (response.data.success) {
        commit('SET_ESTATISTICAS_GERAIS', response.data.data)
        return response.data.data
      }
    } catch (error) {
      const mensagem = error.response?.data?.message || error.message
      commit('SET_ERRO', mensagem)
      throw error
    } finally {
      commit('SET_LOADING', false)
    }
  },

  async carregarCasosPorUF({ commit }, uf = null) {
    commit('SET_LOADING', true)

    try {
      const response = await riscoService.casosPorUF(uf)
      
      if (response.data.success) {
        commit('SET_CASOS_POR_UF', response.data.data)
        return response.data.data
      }
    } catch (error) {
      console.error('Erro ao carregar casos por UF:', error)
      throw error
    } finally {
      commit('SET_LOADING', false)
    }
  },

  async carregarTopMunicipios({ commit }, limite = 10) {
    commit('SET_LOADING', true)

    try {
      const response = await riscoService.topMunicipios(limite)
      
      if (response.data.success) {
        commit('SET_TOP_MUNICIPIOS', response.data.data)
        return response.data.data
      }
    } catch (error) {
      console.error('Erro ao carregar top municípios:', error)
      throw error
    } finally {
      commit('SET_LOADING', false)
    }
  },

  async carregarTendencia({ commit }) {
    commit('SET_LOADING', true)

    try {
      const response = await riscoService.tendenciaTemporal()
      
      if (response.data.success) {
        commit('SET_TENDENCIA', response.data.data)
        return response.data.data
      }
    } catch (error) {
      console.error('Erro ao carregar tendência:', error)
      throw error
    } finally {
      commit('SET_LOADING', false)
    }
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
