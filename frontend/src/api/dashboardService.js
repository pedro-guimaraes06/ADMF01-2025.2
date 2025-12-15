import api from './sadApi'

/**
 * Serviço de API para Dashboard Epidemiológico
 * Consumo de endpoints de sumarização e análises preditivas
 */

// ==================== ESTATÍSTICAS GERAIS ====================

/**
 * Obtém estatísticas gerais dos casos
 */
export const getEstatisticasGerais = () => {
  return api.get('/casos/estatisticas')
}

/**
 * Obtém casos por UF
 * @param {string} uf - Sigla da UF (opcional)
 */
export const getCasosPorUF = (uf = null) => {
  const params = uf ? { uf } : {}
  return api.get('/casos/uf', { params })
}

/**
 * Obtém casos por município
 * @param {string} municipio - Nome do município (opcional)
 * @param {string} uf - Sigla da UF (opcional)
 */
export const getCasosPorMunicipio = (municipio = null, uf = null) => {
  const params = {}
  if (municipio) params.municipio = municipio
  if (uf) params.uf = uf
  return api.get('/casos/municipio', { params })
}

/**
 * Obtém casos por semana epidemiológica
 * @param {number} semana - Número da semana (opcional)
 */
export const getCasosPorSemana = (semana = null) => {
  const params = semana ? { semana } : {}
  return api.get('/casos/semana', { params })
}

/**
 * Obtém distribuição por faixa etária
 */
export const getDistribuicaoFaixaEtaria = () => {
  return api.get('/casos/faixa-etaria')
}

/**
 * Obtém top municípios mais afetados
 * @param {number} limite - Quantidade de municípios (padrão: 10)
 */
export const getTopMunicipios = (limite = 10) => {
  return api.get('/casos/top-municipios', { params: { limite } })
}

/**
 * Obtém tendência temporal
 */
export const getTendenciaTemporal = () => {
  return api.get('/casos/tendencia')
}

/**
 * Obtém resumo executivo completo
 */
export const getResumoExecutivo = () => {
  return api.get('/casos/resumo-executivo')
}

// ==================== SINTOMAS E GRAVIDADE ====================

/**
 * Obtém distribuição de sintomas
 */
export const getDistribuicaoSintomas = () => {
  return api.get('/sintomas/distribuicao')
}

/**
 * Obtém distribuição de sinais de alarme
 */
export const getDistribuicaoAlarmes = () => {
  return api.get('/sintomas/alarmes')
}

/**
 * Obtém distribuição de sinais de gravidade
 */
export const getDistribuicaoGravidade = () => {
  return api.get('/sintomas/gravidade')
}

// ==================== ANÁLISES PREDITIVAS ====================

/**
 * Obtém regressão linear temporal
 * @param {string} municipio - Nome do município (opcional)
 * @param {string} uf - Sigla da UF (opcional)
 */
export const getRegressaoTemporal = (municipio = null, uf = null) => {
  const params = {}
  if (municipio) params.municipio = municipio
  if (uf) params.uf = uf
  return api.get('/analise/regressao', { params })
}

/**
 * Obtém previsão de casos futuros
 * @param {number} semanas - Número de semanas à frente (1-12)
 * @param {string} municipio - Nome do município (opcional)
 * @param {string} uf - Sigla da UF (opcional)
 */
export const getPrevisaoCasos = (semanas = 4, municipio = null, uf = null) => {
  const params = { semanas }
  if (municipio) params.municipio = municipio
  if (uf) params.uf = uf
  return api.get('/analise/previsao', { params })
}

/**
 * Obtém correlação entre sintomas e gravidade
 */
export const getCorrelacaoSintomasGravidade = () => {
  return api.get('/analise/correlacao/sintomas-gravidade')
}

/**
 * Obtém correlação entre alarmes e gravidade
 */
export const getCorrelacaoAlarmesGravidade = () => {
  return api.get('/analise/correlacao/alarmes-gravidade')
}

/**
 * Obtém dashboard consolidado de análises
 */
export const getDashboardAnalises = () => {
  return api.get('/analise/dashboard')
}

// ==================== HELPERS ====================

/**
 * Carrega todos os dados necessários para o dashboard
 */
export const carregarDadosDashboard = async () => {
  try {
    const [
      estatisticas,
      topMunicipios,
      tendencia,
      faixaEtaria,
      casosPorUF,
      sintomas,
      alarmes,
      gravidade,
      previsao
    ] = await Promise.all([
      getEstatisticasGerais(),
      getTopMunicipios(10),
      getTendenciaTemporal(),
      getDistribuicaoFaixaEtaria(),
      getCasosPorUF(),
      getDistribuicaoSintomas(),
      getDistribuicaoAlarmes(),
      getDistribuicaoGravidade(),
      getPrevisaoCasos(4)
    ])

    return {
      estatisticas: estatisticas.data.data,
      topMunicipios: topMunicipios.data.data,
      tendencia: tendencia.data.data,
      faixaEtaria: faixaEtaria.data.data,
      casosPorUF: casosPorUF.data.data,
      sintomas: sintomas.data.data,
      alarmes: alarmes.data.data,
      gravidade: gravidade.data.data,
      previsao: previsao.data.data
    }
  } catch (error) {
    console.error('Erro ao carregar dados do dashboard:', error)
    throw error
  }
}

export default {
  getEstatisticasGerais,
  getCasosPorUF,
  getCasosPorMunicipio,
  getCasosPorSemana,
  getDistribuicaoFaixaEtaria,
  getTopMunicipios,
  getTendenciaTemporal,
  getResumoExecutivo,
  getDistribuicaoSintomas,
  getDistribuicaoAlarmes,
  getDistribuicaoGravidade,
  getRegressaoTemporal,
  getPrevisaoCasos,
  getCorrelacaoSintomasGravidade,
  getCorrelacaoAlarmesGravidade,
  getDashboardAnalises,
  carregarDadosDashboard
}
