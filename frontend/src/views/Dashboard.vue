<template>
  <v-container fluid class="dashboard pa-6">
    <!-- Header -->
    <v-row class="mb-4">
      <v-col cols="12">
        <div class="d-flex align-center justify-space-between">
          <div>
            <h1 class="text-h4 font-weight-bold primary--text">
              <v-icon large color="primary" class="mr-2">mdi-view-dashboard</v-icon>
              Dashboard Epidemiológico
            </h1>
            <p class="text-subtitle-1 grey--text mt-2">
              Análise em tempo real dos casos de dengue - Ano 2025
            </p>
          </div>
          <div>
            <v-btn
              color="primary"
              large
              @click="$router.push('/avaliacao')"
              class="mr-2"
            >
              <v-icon left>mdi-clipboard-pulse</v-icon>
              Nova Avaliação
            </v-btn>
            <v-btn
              color="secondary"
              large
              outlined
              @click="carregarDados"
              :loading="loading"
            >
              <v-icon left>mdi-refresh</v-icon>
              Atualizar
            </v-btn>
          </div>
        </div>
      </v-col>
    </v-row>

    <!-- Loading State -->
    <v-row v-if="loading && !dadosCarregados" justify="center" align="center" style="min-height: 400px">
      <v-col cols="auto" class="text-center">
        <v-progress-circular
          indeterminate
          color="primary"
          size="64"
        ></v-progress-circular>
        <div class="mt-4 text-h6">Carregando dados epidemiológicos...</div>
      </v-col>
    </v-row>

    <!-- Error State -->
    <v-row v-else-if="erro">
      <v-col cols="12">
        <v-alert type="error" prominent border="left" icon="mdi-alert-circle">
          <v-row align="center">
            <v-col class="grow">
              {{ erro }}
            </v-col>
            <v-col class="shrink">
              <v-btn text @click="carregarDados">Tentar novamente</v-btn>
            </v-col>
          </v-row>
        </v-alert>
      </v-col>
    </v-row>

    <!-- Dashboard Content -->
    <div v-else>
      <!-- Cards de Estatísticas Principais -->
      <v-row class="mb-4">
        <v-col cols="12" sm="6" md="3">
          <v-card class="stat-card elevation-4" color="#E3F2FD">
            <v-card-text>
              <div class="d-flex align-center justify-space-between">
                <div>
                  <div class="text-overline grey--text">Total de Casos</div>
                  <div class="text-h4 primary--text font-weight-bold">
                    {{ formatarNumero(estatisticas.total_casos) }}
                  </div>
                </div>
                <v-icon size="48" color="primary">mdi-account-multiple</v-icon>
              </div>
            </v-card-text>
          </v-card>
        </v-col>

        <v-col cols="12" sm="6" md="3">
          <v-card class="stat-card elevation-4" color="#FFF3E0">
            <v-card-text>
              <div class="d-flex align-center justify-space-between">
                <div>
                  <div class="text-overline grey--text">Confirmados</div>
                  <div class="text-h4 orange--text font-weight-bold">
                    {{ formatarNumero(estatisticas.casos_confirmados) }}
                  </div>
                </div>
                <v-icon size="48" color="orange">mdi-check-circle</v-icon>
              </div>
            </v-card-text>
          </v-card>
        </v-col>

        <v-col cols="12" sm="6" md="3">
          <v-card class="stat-card elevation-4" color="#FFEBEE">
            <v-card-text>
              <div class="d-flex align-center justify-space-between">
                <div>
                  <div class="text-overline grey--text">Casos Graves</div>
                  <div class="text-h4 red--text font-weight-bold">
                    {{ formatarNumero(estatisticas.casos_graves) }}
                  </div>
                </div>
                <v-icon size="48" color="red">mdi-alert-octagon</v-icon>
              </div>
            </v-card-text>
          </v-card>
        </v-col>

        <v-col cols="12" sm="6" md="3">
          <v-card class="stat-card elevation-4" color="#FFF9C4">
            <v-card-text>
              <div class="d-flex align-center justify-space-between">
                <div>
                  <div class="text-overline grey--text">Com Alarme</div>
                  <div class="text-h4 amber--text text--darken-2 font-weight-bold">
                    {{ formatarNumero(estatisticas.casos_com_alarme) }}
                  </div>
                </div>
                <v-icon size="48" color="amber darken-2">mdi-alert</v-icon>
              </div>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>

      <!-- Gráficos Principais -->
      <v-row>
        <!-- Tendência Temporal -->
        <v-col cols="12" lg="8">
          <v-card elevation="4" class="card-animated">
            <v-card-title class="primary white--text">
              <v-icon left color="white">mdi-chart-line</v-icon>
              Tendência Temporal de Casos
              <v-spacer></v-spacer>
              <v-chip color="white" small>
                <v-icon left small>mdi-calendar-range</v-icon>
                {{ tendencia.semanas?.length || 0 }} semanas
              </v-chip>
            </v-card-title>
            <v-card-text class="pt-6">
              <apexchart
                v-if="seriesTendencia.length > 0"
                type="area"
                height="350"
                :options="optionsTendencia"
                :series="seriesTendencia"
              ></apexchart>
              <div v-else class="text-center pa-8">
                <v-icon size="64" color="grey">mdi-chart-line-variant</v-icon>
                <p class="text-h6 grey--text mt-4">Sem dados de tendência disponíveis</p>
              </div>
            </v-card-text>
          </v-card>
        </v-col>

        <!-- Distribuição por Sexo e Faixa Etária -->
        <v-col cols="12" lg="4">
          <v-card elevation="4" class="card-animated h-100">
            <v-card-title class="primary white--text">
              <v-icon left color="white">mdi-human-male-female</v-icon>
              Distribuição Demográfica
            </v-card-title>
            <v-card-text class="pt-6">
              <!-- Distribuição por Sexo -->
              <div class="mb-6">
                <h3 class="text-subtitle-1 font-weight-bold mb-3">Por Sexo</h3>
                <apexchart
                  v-if="seriesSexo.length > 0"
                  type="donut"
                  height="200"
                  :options="optionsSexo"
                  :series="seriesSexo"
                ></apexchart>
                <div v-else class="text-center pa-4">
                  <v-icon size="48" color="grey">mdi-human-male-female</v-icon>
                  <p class="grey--text mt-2">Sem dados de distribuição por sexo</p>
                </div>
              </div>
              
              <v-divider class="my-4"></v-divider>

              <!-- Média de Idade -->
              <div class="text-center">
                <v-icon size="48" color="primary">mdi-account-clock</v-icon>
                <div class="text-h5 primary--text font-weight-bold mt-2">
                  {{ formatarIdade(estatisticas.media_idade) }} anos
                </div>
                <div class="text-caption grey--text">Média de Idade</div>
              </div>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>

      <!-- Distribuição por Faixa Etária e Top Municípios -->
      <v-row class="mt-4">
        <v-col cols="12" md="6">
          <v-card elevation="4" class="card-animated">
            <v-card-title class="primary white--text">
              <v-icon left color="white">mdi-account-group</v-icon>
              Distribuição por Faixa Etária
            </v-card-title>
            <v-card-text class="pt-6">
              <apexchart
                v-if="seriesFaixaEtaria.length > 0"
                type="bar"
                height="350"
                :options="optionsFaixaEtaria"
                :series="seriesFaixaEtaria"
              ></apexchart>
              <div v-else class="text-center pa-8">
                <p class="grey--text">Sem dados disponíveis</p>
              </div>
            </v-card-text>
          </v-card>
        </v-col>

        <v-col cols="12" md="6">
          <v-card elevation="4" class="card-animated">
            <v-card-title class="primary white--text">
              <v-icon left color="white">mdi-map-marker-multiple</v-icon>
              Top 10 Municípios Mais Afetados
            </v-card-title>
            <v-card-text class="pt-6">
              <v-simple-table v-if="topMunicipios && topMunicipios.length > 0">
                <template v-slot:default>
                  <thead>
                    <tr>
                      <th class="text-left">#</th>
                      <th class="text-left">Município</th>
                      <th class="text-left">UF</th>
                      <th class="text-right">Casos</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(municipio, index) in topMunicipios.slice(0, 10)" :key="index">
                      <td>
                        <v-chip small :color="getColorByRank(index)" dark>
                          {{ index + 1 }}
                        </v-chip>
                      </td>
                      <td class="font-weight-medium">{{ municipio.municipio }}</td>
                      <td>
                        <v-chip small outlined>{{ municipio.uf }}</v-chip>
                      </td>
                      <td class="text-right font-weight-bold">
                        {{ formatarNumero(municipio.total) }}
                      </td>
                    </tr>
                  </tbody>
                </template>
              </v-simple-table>
              <div v-else class="text-center pa-8">
                <p class="grey--text">Sem dados disponíveis</p>
              </div>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>

      <!-- Casos por UF -->
      <v-row class="mt-4">
        <v-col cols="12">
          <v-card elevation="4" class="card-animated">
            <v-card-title class="primary white--text">
              <v-icon left color="white">mdi-map</v-icon>
              Casos por Estado (UF)
            </v-card-title>
            <v-card-text class="pt-6">
              <apexchart
                v-if="seriesCasosPorUF.length > 0"
                type="bar"
                height="400"
                :options="optionsCasosPorUF"
                :series="seriesCasosPorUF"
              ></apexchart>
              <div v-else class="text-center pa-8">
                <p class="grey--text">Sem dados disponíveis</p>
              </div>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>

      <!-- Sintomas, Alarmes e Gravidade -->
      <v-row class="mt-4">
        <v-col cols="12" md="4">
          <v-card elevation="4" class="card-animated h-100">
            <v-card-title class="orange white--text">
              <v-icon left color="white">mdi-thermometer</v-icon>
              Sintomas Mais Comuns
            </v-card-title>
            <v-card-text class="pt-6">
              <div v-if="sintomas && Object.keys(sintomas).length > 0">
                <div
                  v-for="(valor, sintoma) in sintomasTop10"
                  :key="sintoma"
                  class="mb-3"
                >
                  <div class="d-flex justify-space-between align-center mb-1">
                    <span class="text-caption">{{ formatarNomeCampo(sintoma) }}</span>
                    <v-chip x-small color="orange" dark>{{ formatarNumero(valor) }}</v-chip>
                  </div>
                  <v-progress-linear
                    :value="(valor / maxSintomas) * 100"
                    color="orange"
                    height="8"
                    rounded
                  ></v-progress-linear>
                </div>
              </div>
              <div v-else class="text-center pa-4">
                <p class="grey--text">Sem dados disponíveis</p>
              </div>
            </v-card-text>
          </v-card>
        </v-col>

        <v-col cols="12" md="4">
          <v-card elevation="4" class="card-animated h-100">
            <v-card-title class="amber darken-2 white--text">
              <v-icon left color="white">mdi-alert</v-icon>
              Sinais de Alarme
            </v-card-title>
            <v-card-text class="pt-6">
              <div v-if="alarmes && Object.keys(alarmes).length > 0">
                <div
                  v-for="(valor, alarme) in alarmesTop10"
                  :key="alarme"
                  class="mb-3"
                >
                  <div class="d-flex justify-space-between align-center mb-1">
                    <span class="text-caption">{{ formatarNomeCampo(alarme) }}</span>
                    <v-chip x-small color="amber darken-2" dark>{{ formatarNumero(valor) }}</v-chip>
                  </div>
                  <v-progress-linear
                    :value="(valor / maxAlarmes) * 100"
                    color="amber darken-2"
                    height="8"
                    rounded
                  ></v-progress-linear>
                </div>
              </div>
              <div v-else class="text-center pa-4">
                <p class="grey--text">Sem dados disponíveis</p>
              </div>
            </v-card-text>
          </v-card>
        </v-col>

        <v-col cols="12" md="4">
          <v-card elevation="4" class="card-animated h-100">
            <v-card-title class="red white--text">
              <v-icon left color="white">mdi-alert-octagon</v-icon>
              Sinais de Gravidade
            </v-card-title>
            <v-card-text class="pt-6">
              <div v-if="gravidade && Object.keys(gravidade).length > 0">
                <div
                  v-for="(valor, sinal) in gravidadeTop10"
                  :key="sinal"
                  class="mb-3"
                >
                  <div class="d-flex justify-space-between align-center mb-1">
                    <span class="text-caption">{{ formatarNomeCampo(sinal) }}</span>
                    <v-chip x-small color="red" dark>{{ formatarNumero(valor) }}</v-chip>
                  </div>
                  <v-progress-linear
                    :value="(valor / maxGravidade) * 100"
                    color="red"
                    height="8"
                    rounded
                  ></v-progress-linear>
                </div>
              </div>
              <div v-else class="text-center pa-4">
                <p class="grey--text">Sem dados disponíveis</p>
              </div>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>

      <!-- Análises Preditivas -->
      <v-row class="mt-4">
        <v-col cols="12">
          <v-card elevation="4" class="card-animated">
            <v-card-title class="indigo white--text">
              <v-icon left color="white">mdi-crystal-ball</v-icon>
              Análises Preditivas
              <v-spacer></v-spacer>
              <v-chip color="white" small>
                <v-icon left small>mdi-trending-up</v-icon>
                {{ previsao.tendencia || 'N/A' }}
              </v-chip>
            </v-card-title>
            <v-card-text class="pt-6">
              <v-row>
                <v-col cols="12" md="8">
                  <h3 class="text-h6 mb-4">Previsão de Casos (Próximas 4 Semanas)</h3>
                  <apexchart
                    v-if="seriesPrevisao.length > 0"
                    type="line"
                    height="300"
                    :options="optionsPrevisao"
                    :series="seriesPrevisao"
                  ></apexchart>
                  <div v-else class="text-center pa-8">
                    <p class="grey--text">Sem dados disponíveis para previsão</p>
                  </div>
                </v-col>

                <v-col cols="12" md="4">
                  <div class="info-box pa-4">
                    <h3 class="text-h6 mb-4">Confiabilidade do Modelo</h3>
                    
                    <div class="text-center mb-4">
                      <v-progress-circular
                        :rotate="-90"
                        :size="120"
                        :width="12"
                        :value="(previsao.r_squared || 0) * 100"
                        :color="getColorConfiabilidade(previsao.confiabilidade)"
                      >
                        <span class="text-h6">{{ ((previsao.r_squared || 0) * 100).toFixed(0) }}%</span>
                      </v-progress-circular>
                      <div class="mt-2">
                        <v-chip
                          :color="getColorConfiabilidade(previsao.confiabilidade)"
                          dark
                          small
                        >
                          {{ previsao.confiabilidade || 'N/A' }}
                        </v-chip>
                      </div>
                    </div>

                    <v-divider class="my-4"></v-divider>

                    <div v-if="previsao.previsoes && previsao.previsoes.length > 0">
                      <h4 class="text-subtitle-2 mb-2">Próximas Semanas:</h4>
                      <div
                        v-for="prev in previsao.previsoes"
                        :key="prev.semana"
                        class="d-flex justify-space-between align-center mb-2"
                      >
                        <span class="text-caption">{{ formatarSemanaEpidemiologica(prev.semana) }} Semana</span>
                        <v-chip x-small color="indigo" dark>
                          {{ prev.casos_previstos }} casos
                        </v-chip>
                      </div>
                    </div>
                  </div>
                </v-col>
              </v-row>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>

      <!-- Footer Info -->
      <v-row class="mt-6">
        <v-col cols="12">
          <v-card elevation="2" color="grey lighten-4">
            <v-card-text class="text-center">
              <v-icon left>mdi-information</v-icon>
              <span class="text-caption">
                Dados atualizados em tempo real do Sistema de Apoio à Decisão - Dengue 2025
              </span>
              <v-icon right>mdi-shield-check</v-icon>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </div>
  </v-container>
</template>

<script>
import { carregarDadosDashboard } from '@/api/dashboardService'

export default {
  name: 'Dashboard',
  
  metaInfo: {
    title: 'Dashboard - SAD Dengue'
  },

  data() {
    return {
      loading: false,
      dadosCarregados: false,
      erro: null,
      estatisticas: {
        total_casos: 0,
        casos_confirmados: 0,
        casos_graves: 0,
        casos_com_alarme: 0,
        media_idade: 0,
        distribuicao_sexo: {}
      },
      topMunicipios: [],
      tendencia: { semanas: [], casos: [] },
      faixaEtaria: {},
      casosPorUF: [],
      sintomas: {},
      alarmes: {},
      gravidade: {},
      previsao: {}
    }
  },

  computed: {
    // Séries para gráfico de tendência temporal
    seriesTendencia() {
      if (!this.tendencia.semanas || this.tendencia.semanas.length === 0) {
        return []
      }
      return [{
        name: 'Casos',
        data: this.tendencia.casos || []
      }]
    },

    optionsTendencia() {
      return {
        chart: {
          type: 'area',
          toolbar: { show: true },
          zoom: { enabled: true }
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2 },
        fill: {
          type: 'gradient',
          gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.7,
            opacityTo: 0.3,
          }
        },
        xaxis: {
          categories: (this.tendencia.semanas || []).map(s => this.formatarSemanaEpidemiologica(s)),
          title: { text: 'Semana Epidemiológica' }
        },
        yaxis: {
          title: { text: 'Número de Casos' }
        },
        colors: ['#1976D2'],
        tooltip: {
          y: { formatter: (val) => `${val} casos` }
        }
      }
    },

    // Distribuição por sexo
    seriesSexo() {
      const dist = this.estatisticas.distribuicao_sexo || {}
      
      // Se não houver dados, retorna vazio
      if (!dist || Object.keys(dist).length === 0) {
        return []
      }
      
      // Pega todos os valores numéricos das chaves válidas (M, F, I)
      const valores = Object.entries(dist)
        .filter(([key, value]) => 
          ['M', 'F', 'I'].includes(key) && typeof value === 'number' && value > 0
        )
        .map(([, value]) => value)
      
      return valores
    },

    optionsSexo() {
      const dist = this.estatisticas.distribuicao_sexo || {}
      
      // Mapeamento de chaves para labels
      const sexoMap = { 
        'M': 'Masculino', 
        'F': 'Feminino', 
        'I': 'Ignorado' 
      }
      
      // Pega apenas as chaves válidas que existem e têm valores > 0
      const labels = []
      const colors = []
      const colorMap = {
        'M': '#42A5F5',
        'F': '#EC407A', 
        'I': '#BDBDBD'
      }
      
      Object.entries(dist).forEach(([key, value]) => {
        if (['M', 'F', 'I'].includes(key) && typeof value === 'number' && value > 0) {
          labels.push(sexoMap[key])
          colors.push(colorMap[key])
        }
      })
      
      return {
        chart: { type: 'donut' },
        labels: labels.length > 0 ? labels : ['Sem dados'],
        colors: colors.length > 0 ? colors : ['#BDBDBD'],
        legend: { position: 'bottom' },
        plotOptions: {
          pie: {
            donut: {
              labels: {
                show: true,
                total: {
                  show: true,
                  label: 'Total',
                  formatter: () => {
                    const total = this.seriesSexo.reduce((a, b) => a + b, 0)
                    return this.formatarNumero(total)
                  }
                }
              }
            }
          }
        }
      }
    },

    // Distribuição por faixa etária
    seriesFaixaEtaria() {
      if (!this.faixaEtaria || Object.keys(this.faixaEtaria).length === 0) {
        return []
      }
      return [{
        name: 'Casos',
        data: Object.values(this.faixaEtaria)
      }]
    },

    optionsFaixaEtaria() {
      return {
        chart: {
          type: 'bar',
          toolbar: { show: true }
        },
        plotOptions: {
          bar: {
            horizontal: false,
            borderRadius: 8,
            dataLabels: { position: 'top' }
          }
        },
        dataLabels: {
          enabled: true,
          offsetY: -20,
          style: { fontSize: '12px' }
        },
        xaxis: {
          categories: Object.keys(this.faixaEtaria),
          title: { text: 'Faixa Etária (anos)' }
        },
        yaxis: {
          title: { text: 'Número de Casos' }
        },
        colors: ['#66BB6A'],
        tooltip: {
          y: { formatter: (val) => `${val} casos` }
        }
      }
    },

    // Dados de UF filtrados (remove duplicatas de sigla quando existe nome completo)
    casosPorUFFiltrados() {
      if (!this.casosPorUF || this.casosPorUF.length === 0) {
        return []
      }
      
      // Filtra apenas registros que têm nome_uf preenchido E com mais de 2 caracteres
      // Isso remove entradas que são apenas siglas (ex: "BA") quando já existe o nome completo (ex: "Bahia")
      return this.casosPorUF.filter(item => {
        const nomeUf = item.nome_uf || item.UF || ''
        const nomeLimpo = nomeUf.trim()
        // Remove siglas (2 caracteres) e mantém apenas nomes completos (mais de 2 caracteres)
        return nomeLimpo.length > 2
      })
    },

    // Casos por UF
    seriesCasosPorUF() {
      if (!this.casosPorUFFiltrados || this.casosPorUFFiltrados.length === 0) {
        return []
      }
      return [{
        name: 'Casos',
        data: this.casosPorUFFiltrados.map(item => item.total)
      }]
    },

    optionsCasosPorUF() {
      return {
        chart: {
          type: 'bar',
          toolbar: { show: true }
        },
        plotOptions: {
          bar: {
            horizontal: true,
            borderRadius: 4,
            dataLabels: { position: 'top' }
          }
        },
        dataLabels: {
          enabled: true,
          offsetX: 30,
          style: { fontSize: '11px' }
        },
        xaxis: {
          categories: this.casosPorUFFiltrados.map(item => item.nome_uf),
          title: { text: 'Número de Casos' }
        },
        yaxis: {
          title: { text: 'Estado (UF)' }
        },
        colors: ['#5C6BC0'],
        tooltip: {
          y: { formatter: (val) => `${val} casos` }
        }
      }
    },

    // Top 10 sintomas
    sintomasTop10() {
      if (!this.sintomas) return {}
      const entries = Object.entries(this.sintomas)
      entries.sort((a, b) => b[1] - a[1])
      return Object.fromEntries(entries.slice(0, 10))
    },

    maxSintomas() {
      if (!this.sintomas) return 1
      return Math.max(...Object.values(this.sintomas), 1)
    },

    // Top 10 alarmes
    alarmesTop10() {
      if (!this.alarmes) return {}
      const entries = Object.entries(this.alarmes)
      entries.sort((a, b) => b[1] - a[1])
      return Object.fromEntries(entries.slice(0, 10))
    },

    maxAlarmes() {
      if (!this.alarmes) return 1
      return Math.max(...Object.values(this.alarmes), 1)
    },

    // Top 10 gravidade
    gravidadeTop10() {
      if (!this.gravidade) return {}
      const entries = Object.entries(this.gravidade)
      entries.sort((a, b) => b[1] - a[1])
      return Object.fromEntries(entries.slice(0, 10))
    },

    maxGravidade() {
      if (!this.gravidade) return 1
      return Math.max(...Object.values(this.gravidade), 1)
    },

    // Série de previsão
    seriesPrevisao() {
      if (!this.previsao.previsoes || this.previsao.previsoes.length === 0) {
        return []
      }
      return [{
        name: 'Casos Previstos',
        data: this.previsao.previsoes.map(p => p.casos_previstos)
      }]
    },

    optionsPrevisao() {
      return {
        chart: {
          type: 'line',
          toolbar: { show: true }
        },
        stroke: {
          curve: 'smooth',
          width: 3,
          dashArray: [0, 8]
        },
        markers: {
          size: 6,
          hover: { size: 8 }
        },
        xaxis: {
          categories: this.previsao.previsoes?.map(p => this.formatarSemanaEpidemiologica(p.semana)) || [],
          title: { text: 'Semana Epidemiológica' }
        },
        yaxis: {
          title: { text: 'Casos Previstos' }
        },
        colors: ['#7E57C2'],
        tooltip: {
          y: { formatter: (val) => `${val} casos` }
        }
      }
    }
  },

  mounted() {
    this.carregarDados()
  },

  methods: {
    async carregarDados() {
      this.loading = true
      this.erro = null

      try {
        const dados = await carregarDadosDashboard()
        
        this.estatisticas = dados.estatisticas || this.estatisticas
        this.topMunicipios = dados.topMunicipios || []
        this.tendencia = dados.tendencia || { semanas: [], casos: [] }
        this.faixaEtaria = dados.faixaEtaria || {}
        this.casosPorUF = dados.casosPorUF || []
        this.sintomas = dados.sintomas || {}
        this.alarmes = dados.alarmes || {}
        this.gravidade = dados.gravidade || {}
        this.previsao = dados.previsao || {}

        this.dadosCarregados = true
      } catch (error) {
        console.error('Erro ao carregar dashboard:', error)
        this.erro = 'Erro ao carregar dados do dashboard. Verifique se o backend está ativo.'
      } finally {
        this.loading = false
      }
    },

    formatarNumero(numero) {
      if (!numero && numero !== 0) return '0'
      return new Intl.NumberFormat('pt-BR').format(numero)
    },

    formatarIdade(idade) {
      if (!idade && idade !== 0) return '0'
      // Arredondar para 1 casa decimal
      return Number(idade).toFixed(1)
    },

    formatarNomeCampo(campo) {
      // Remove prefixos e formata
      const nome = campo.replace(/^(FEBRE_|DOR_|NAUSEA_|VOMITO_|EXANTEMA_|MIALGIA_|CEFALEIA_|ARTRALGIA_|PETEQUIAS_|HEPATOMEGALIA_|SANGRAMENTO_|CHOQUE_|DERRAME_|INSUF_)/gi, '')
      return nome
        .split('_')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
        .join(' ')
    },

    formatarSemanaEpidemiologica(semana) {
      // Converte formato '202549' para '49ª'
      if (!semana) return 'N/A'
      
      const semanaStr = String(semana)
      
      // Se tem 6 dígitos (formato YYYYWW), pega os 2 últimos
      if (semanaStr.length === 6) {
        const numeroSemana = semanaStr.slice(-2)
        return `${parseInt(numeroSemana)}ª`
      }
      
      // Se tem 4 ou 5 dígitos (formato YYWW ou YYYWW)
      if (semanaStr.length >= 4) {
        const numeroSemana = semanaStr.slice(-2)
        return `${parseInt(numeroSemana)}ª`
      }
      
      // Se já é apenas o número da semana
      return `${parseInt(semanaStr)}ª`
    },

    getColorByRank(index) {
      if (index === 0) return 'red darken-2'
      if (index === 1) return 'orange darken-2'
      if (index === 2) return 'amber darken-2'
      return 'grey'
    },

    getColorConfiabilidade(confiabilidade) {
      const map = {
        'Muito alta': 'green',
        'Alta': 'light-green',
        'Moderada': 'orange',
        'Baixa': 'deep-orange',
        'Muito baixa': 'red'
      }
      return map[confiabilidade] || 'grey'
    }
  }
}
</script>

<style scoped>
.dashboard {
  min-height: 100vh;
  background: linear-gradient(135deg, #E3F2FD 0%, #F5F5F5 100%);
}

.stat-card {
  transition: all 0.3s ease;
}

.stat-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 16px rgba(0,0,0,0.2) !important;
}

.card-animated {
  animation: fadeInUp 0.5s ease;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.info-box {
  background: #f5f5f5;
  border-radius: 8px;
  border: 1px solid #e0e0e0;
}

/* Responsive adjustments */
@media (max-width: 960px) {
  .stat-card {
    margin-bottom: 16px;
  }
}
</style>
