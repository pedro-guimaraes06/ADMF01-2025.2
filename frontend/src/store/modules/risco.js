import riscoService from '@/api/riscoService'

const state = {
  avaliacaoAtual: null,
  historico: [],
  loading: false,
  erro: null
}

const getters = {
  avaliacaoAtual: state => state.avaliacaoAtual,
  historico: state => state.historico,
  isLoading: state => state.loading,
  erro: state => state.erro,
  nivelRisco: state => state.avaliacaoAtual?.nivel_risco || null,
  scoreFinal: state => state.avaliacaoAtual?.score_final || null
}

const mutations = {
  SET_LOADING(state, loading) {
    state.loading = loading
  },

  SET_ERRO(state, erro) {
    state.erro = erro
  },

  SET_AVALIACAO_ATUAL(state, avaliacao) {
    state.avaliacaoAtual = avaliacao
  },

  SET_HISTORICO(state, historico) {
    state.historico = historico
  },

  ADD_HISTORICO(state, avaliacao) {
    state.historico.unshift(avaliacao)
  },

  CLEAR_AVALIACAO(state) {
    state.avaliacaoAtual = null
  },

  CLEAR_ERRO(state) {
    state.erro = null
  }
}

const actions = {
  async avaliarRisco({ commit }, dados) {
    commit('SET_LOADING', true)
    commit('CLEAR_ERRO')

    try {
      const response = await riscoService.avaliarRisco(dados)
      
      if (response.data.success) {
        commit('SET_AVALIACAO_ATUAL', response.data.data)
        commit('ADD_HISTORICO', response.data.data)
        return response.data.data
      } else {
        throw new Error(response.data.message || 'Erro ao avaliar risco')
      }
    } catch (error) {
      const mensagem = error.response?.data?.message || error.message || 'Erro ao avaliar risco'
      commit('SET_ERRO', mensagem)
      throw error
    } finally {
      commit('SET_LOADING', false)
    }
  },

  async buscarAvaliacao({ commit }, id) {
    commit('SET_LOADING', true)
    commit('CLEAR_ERRO')

    try {
      const response = await riscoService.buscarAvaliacao(id)
      
      if (response.data.success) {
        commit('SET_AVALIACAO_ATUAL', response.data.data.avaliacao)
        return response.data.data
      } else {
        throw new Error(response.data.message || 'Avaliação não encontrada')
      }
    } catch (error) {
      const mensagem = error.response?.data?.message || error.message
      commit('SET_ERRO', mensagem)
      throw error
    } finally {
      commit('SET_LOADING', false)
    }
  },

  async listarAvaliacoes({ commit }) {
    commit('SET_LOADING', true)
    commit('CLEAR_ERRO')

    try {
      const response = await riscoService.listarAvaliacoes()
      
      if (response.data.success) {
        commit('SET_HISTORICO', response.data.data)
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

  limparAvaliacao({ commit }) {
    commit('CLEAR_AVALIACAO')
  },

  limparErro({ commit }) {
    commit('CLEAR_ERRO')
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
