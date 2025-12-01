import api from './sadApi'

/**
 * API Service para avaliação de risco de dengue
 */
export default {
  /**
   * Avalia risco de dengue usando método AHP
   * @param {Object} dados - Dados do paciente e sintomas
   * @returns {Promise}
   */
  avaliarRisco(dados) {
    return api.post('/risco/avaliar', dados)
  },

  /**
   * Busca uma avaliação específica por ID
   * @param {Number} id
   * @returns {Promise}
   */
  buscarAvaliacao(id) {
    return api.get(`/risco/${id}`)
  },

  /**
   * Lista avaliações recentes
   * @returns {Promise}
   */
  listarAvaliacoes() {
    return api.get('/risco')
  },

  /**
   * Estatísticas das avaliações
   * @returns {Promise}
   */
  estatisticasAvaliacoes() {
    return api.get('/risco/stats/estatisticas')
  },

  /**
   * Estatísticas gerais dos casos
   * @returns {Promise}
   */
  estatisticasGerais() {
    return api.get('/casos/estatisticas')
  },

  /**
   * Casos por UF
   * @param {String} uf
   * @returns {Promise}
   */
  casosPorUF(uf = null) {
    return api.get('/casos/uf', { params: { uf } })
  },

  /**
   * Casos por município
   * @param {String} municipio
   * @param {String} uf
   * @returns {Promise}
   */
  casosPorMunicipio(municipio = null, uf = null) {
    return api.get('/casos/municipio', { params: { municipio, uf } })
  },

  /**
   * Top municípios mais afetados
   * @param {Number} limite
   * @returns {Promise}
   */
  topMunicipios(limite = 10) {
    return api.get('/casos/top-municipios', { params: { limite } })
  },

  /**
   * Tendência temporal
   * @returns {Promise}
   */
  tendenciaTemporal() {
    return api.get('/casos/tendencia')
  },

  /**
   * Resumo executivo
   * @returns {Promise}
   */
  resumoExecutivo() {
    return api.get('/casos/resumo-executivo')
  },

  /**
   * Distribuição de sintomas
   * @returns {Promise}
   */
  distribuicaoSintomas() {
    return api.get('/sintomas/distribuicao')
  },

  /**
   * Previsão de casos futuros
   * @param {Number} semanas
   * @param {String} municipio
   * @param {String} uf
   * @returns {Promise}
   */
  preverCasos(semanas = 4, municipio = null, uf = null) {
    return api.get('/analise/previsao', { params: { semanas, municipio, uf } })
  },

  /**
   * Dashboard de análises
   * @param {String} municipio
   * @param {String} uf
   * @returns {Promise}
   */
  dashboardAnalises(municipio = null, uf = null) {
    return api.get('/analise/dashboard', { params: { municipio, uf } })
  }
}
