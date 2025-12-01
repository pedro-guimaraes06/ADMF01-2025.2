<template>
  <v-container fluid class="resultado-avaliacao pa-6">
    <v-row v-if="loading" justify="center" align="center" style="min-height: 400px">
      <v-col cols="auto">
        <v-progress-circular
          indeterminate
          color="primary"
          size="64"
        ></v-progress-circular>
        <div class="mt-4 text-center">Carregando avaliação...</div>
      </v-col>
    </v-row>

    <v-row v-else-if="erro" justify="center">
      <v-col cols="12" md="8">
        <v-alert type="error" prominent>
          <v-row align="center">
            <v-col class="grow">
              {{ erro }}
            </v-col>
            <v-col class="shrink">
              <v-btn text @click="$router.push('/avaliacao')">
                Voltar
              </v-btn>
            </v-col>
          </v-row>
        </v-alert>
      </v-col>
    </v-row>

    <div v-else>
      <!-- Header com Score -->
      <v-row justify="center" class="mb-6">
        <v-col cols="12" md="10">
          <v-card class="score-card elevation-8" :class="`border-${nivelCorClass}`">
            <v-card-text>
              <v-row align="center">
                <v-col cols="12" md="4" class="text-center">
                  <div class="score-container">
                    <v-progress-circular
                      :rotate="-90"
                      :size="180"
                      :width="18"
                      :value="scorePercentual"
                      :color="nivelCorClass"
                      class="score-circular"
                    >
                      <div class="score-content">
                        <div class="score-value">{{ scoreFormatado }}</div>
                        <div class="score-label">Score AHP</div>
                      </div>
                    </v-progress-circular>
                  </div>
                </v-col>

                <v-col cols="12" md="8">
                  <div class="d-flex align-center mb-3">
                    <v-chip
                      :color="nivelCorClass"
                      dark
                      large
                      label
                      class="mr-3 px-6"
                    >
                      <v-icon left>{{ nivelIcone }}</v-icon>
                      {{ nivelTexto }}
                    </v-chip>
                    <span class="text-h6 grey--text">Classificação de Risco</span>
                  </div>

                  <v-divider class="my-3"></v-divider>

                  <div class="info-grid">
                    <div class="info-item">
                      <v-icon left color="primary">mdi-account</v-icon>
                      <span><strong>Paciente:</strong> {{ avaliacao.idade }} anos, {{ sexoTexto }}</span>
                    </div>
                    <div class="info-item">
                      <v-icon left color="primary">mdi-map-marker</v-icon>
                      <span><strong>Local:</strong> {{ avaliacao.municipio }}/{{ avaliacao.uf }}</span>
                    </div>
                    <div class="info-item">
                      <v-icon left color="primary">mdi-calendar</v-icon>
                      <span><strong>Data:</strong> {{ dataFormatada }}</span>
                    </div>
                    <div class="info-item">
                      <v-icon left color="primary">mdi-calendar-week</v-icon>
                      <span><strong>Semana Epi:</strong> {{ avaliacao.semana_epidemiologica }}</span>
                    </div>
                  </div>
                </v-col>
              </v-row>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>

      <!-- Análise AHP -->
      <v-row>
        <v-col cols="12" md="6">
          <v-card class="h-100 card-animated" elevation="4">
            <v-card-title class="primary white--text">
              <v-icon left color="white">mdi-chart-donut</v-icon>
              Análise por Critérios AHP
            </v-card-title>
            <v-card-text class="pt-6">
              <apexchart
                type="radar"
                height="350"
                :options="radarOptions"
                :series="radarSeries"
              ></apexchart>
            </v-card-text>
          </v-card>
        </v-col>

        <v-col cols="12" md="6">
          <v-card class="h-100 card-animated" elevation="4">
            <v-card-title class="primary white--text">
              <v-icon left color="white">mdi-weight</v-icon>
              Contribuição por Critério
            </v-card-title>
            <v-card-text class="pt-6">
              <div v-for="criterio in criteriosDetalhados" :key="criterio.nome" class="mb-5">
                <div class="d-flex justify-space-between align-center mb-2">
                  <div class="d-flex align-center">
                    <v-icon :color="criterio.cor" left>{{ criterio.icone }}</v-icon>
                    <span class="font-weight-medium">{{ criterio.nome }}</span>
                  </div>
                  <v-chip small :color="criterio.cor" dark>
                    {{ (criterio.score * 100).toFixed(1) }}%
                  </v-chip>
                </div>
                <v-progress-linear
                  :value="criterio.score * 100"
                  :color="criterio.cor"
                  height="12"
                  rounded
                  class="progress-animated"
                ></v-progress-linear>
                <div class="text-caption grey--text mt-1">
                  Peso no modelo: {{ (criterio.peso * 100).toFixed(0) }}%
                </div>
              </div>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>

      <!-- Interpretação e Recomendações -->
      <v-row class="mt-4">
        <v-col cols="12" md="7">
          <v-card class="card-animated" elevation="4">
            <v-card-title :class="`${nivelCorClass} white--text`">
              <v-icon left color="white">mdi-text-box</v-icon>
              Interpretação Clínica
            </v-card-title>
            <v-card-text class="pt-6">
              <v-alert
                :type="nivelAlertType"
                prominent
                border="left"
                colored-border
                elevation="2"
              >
                <div class="text-h6 mb-2">{{ interpretacao.titulo }}</div>
                <div class="text-body-1">{{ interpretacao.descricao }}</div>
              </v-alert>

              <v-divider class="my-4"></v-divider>

              <div class="detalhes-clinicos">
                <div class="detalhe-item" v-if="avaliacao.scores.epidemiologia !== undefined">
                  <v-icon color="blue" left>mdi-chart-line-variant</v-icon>
                  <div>
                    <strong>Contexto Epidemiológico:</strong>
                    <p class="mb-0 mt-1">
                      {{ interpretacaoEpidemiologia }}
                    </p>
                  </div>
                </div>

                <div class="detalhe-item" v-if="avaliacao.scores.gravidade !== undefined">
                  <v-icon color="red" left>mdi-alert-octagon</v-icon>
                  <div>
                    <strong>Sinais Clínicos:</strong>
                    <p class="mb-0 mt-1">
                      {{ interpretacaoGravidade }}
                    </p>
                  </div>
                </div>

                <div class="detalhe-item" v-if="avaliacao.scores.sintomas !== undefined">
                  <v-icon color="orange" left>mdi-thermometer-alert</v-icon>
                  <div>
                    <strong>Sintomatologia:</strong>
                    <p class="mb-0 mt-1">
                      {{ interpretacaoSintomas }}
                    </p>
                  </div>
                </div>

                <div class="detalhe-item" v-if="avaliacao.scores.sociodemografico !== undefined">
                  <v-icon color="purple" left>mdi-account-group</v-icon>
                  <div>
                    <strong>Perfil Sociodemográfico:</strong>
                    <p class="mb-0 mt-1">
                      {{ interpretacaoSociodemografico }}
                    </p>
                  </div>
                </div>
              </div>
            </v-card-text>
          </v-card>
        </v-col>

        <v-col cols="12" md="5">
          <v-card class="card-animated" elevation="4">
            <v-card-title class="success white--text">
              <v-icon left color="white">mdi-clipboard-check</v-icon>
              Recomendações do SAD
            </v-card-title>
            <v-card-text class="pt-4">
              <v-timeline dense>
                <v-timeline-item
                  v-for="(recomendacao, index) in recomendacoes"
                  :key="index"
                  :color="recomendacao.cor"
                  small
                  fill-dot
                >
                  <template v-slot:icon>
                    <v-icon dark small>{{ recomendacao.icone }}</v-icon>
                  </template>
                  <v-card flat class="elevation-2">
                    <v-card-subtitle class="pb-2">
                      <strong>{{ recomendacao.titulo }}</strong>
                    </v-card-subtitle>
                    <v-card-text class="pt-0">
                      {{ recomendacao.texto }}
                    </v-card-text>
                  </v-card>
                </v-timeline-item>
              </v-timeline>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>

      <!-- Gráfico de Comparação -->
      <v-row class="mt-4">
        <v-col cols="12">
          <v-card class="card-animated" elevation="4">
            <v-card-title class="indigo white--text">
              <v-icon left color="white">mdi-chart-bar</v-icon>
              Comparação dos Scores AHP
            </v-card-title>
            <v-card-text class="pt-6">
              <apexchart
                type="bar"
                height="280"
                :options="barOptions"
                :series="barSeries"
              ></apexchart>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>

      <!-- Ações -->
      <v-row class="mt-4" justify="center">
        <v-col cols="auto">
          <v-btn
            large
            color="primary"
            @click="$router.push('/avaliacao')"
          >
            <v-icon left>mdi-clipboard-plus</v-icon>
            Nova Avaliação
          </v-btn>
        </v-col>
        <v-col cols="auto">
          <v-btn
            large
            color="secondary"
            outlined
            @click="$router.push('/historico')"
          >
            <v-icon left>mdi-history</v-icon>
            Ver Histórico
          </v-btn>
        </v-col>
        <v-col cols="auto">
          <v-btn
            large
            color="success"
            outlined
            @click="exportarPDF"
          >
            <v-icon left>mdi-file-pdf</v-icon>
            Exportar PDF
          </v-btn>
        </v-col>
      </v-row>
    </div>
  </v-container>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import moment from 'moment'
import VueApexCharts from 'vue-apexcharts'

export default {
  name: 'ResultadoAvaliacao',

  components: {
    apexchart: VueApexCharts
  },

  data() {
    return {
      loading: false,
      erro: null,
      avaliacao: null
    }
  },

  computed: {
    ...mapGetters('risco', ['avaliacaoAtual']),

    scoreFormatado() {
      return this.avaliacao?.score_final?.toFixed(3) || '0.000'
    },

    scorePercentual() {
      return (this.avaliacao?.score_final || 0) * 100
    },

    nivelTexto() {
      const nivel = this.avaliacao?.nivel_risco || ''
      const map = {
        'Baixo': 'RISCO BAIXO',
        'Médio': 'RISCO MÉDIO',
        'Alto': 'RISCO ALTO'
      }
      return map[nivel] || nivel.toUpperCase()
    },

    nivelCorClass() {
      const nivel = this.avaliacao?.nivel_risco || ''
      const map = {
        'Baixo': 'success',
        'Médio': 'warning',
        'Alto': 'error'
      }
      return map[nivel] || 'grey'
    },

    nivelAlertType() {
      const nivel = this.avaliacao?.nivel_risco || ''
      const map = {
        'Baixo': 'success',
        'Médio': 'warning',
        'Alto': 'error'
      }
      return map[nivel] || 'info'
    },

    nivelIcone() {
      const nivel = this.avaliacao?.nivel_risco || ''
      const map = {
        'Baixo': 'mdi-check-circle',
        'Médio': 'mdi-alert',
        'Alto': 'mdi-alert-octagon'
      }
      return map[nivel] || 'mdi-help-circle'
    },

    sexoTexto() {
      const map = { 'M': 'Masculino', 'F': 'Feminino', 'I': 'Não informado' }
      return map[this.avaliacao?.sexo] || this.avaliacao?.sexo
    },

    dataFormatada() {
      return moment(this.avaliacao?.created_at).format('DD/MM/YYYY HH:mm')
    },

    criteriosDetalhados() {
      if (!this.avaliacao?.scores) return []

      return [
        {
          nome: 'Epidemiologia',
          score: this.avaliacao.scores.epidemiologia || 0,
          peso: 0.45,
          cor: 'blue',
          icone: 'mdi-chart-line-variant'
        },
        {
          nome: 'Gravidade',
          score: this.avaliacao.scores.gravidade || 0,
          peso: 0.35,
          cor: 'red',
          icone: 'mdi-alert-octagon'
        },
        {
          nome: 'Sintomas',
          score: this.avaliacao.scores.sintomas || 0,
          peso: 0.15,
          cor: 'orange',
          icone: 'mdi-thermometer-alert'
        },
        {
          nome: 'Sociodemográfico',
          score: this.avaliacao.scores.sociodemografico || 0,
          peso: 0.05,
          cor: 'purple',
          icone: 'mdi-account-group'
        }
      ]
    },

    radarSeries() {
      return [{
        name: 'Score AHP',
        data: [
          (this.avaliacao?.scores?.epidemiologia || 0) * 100,
          (this.avaliacao?.scores?.gravidade || 0) * 100,
          (this.avaliacao?.scores?.sintomas || 0) * 100,
          (this.avaliacao?.scores?.sociodemografico || 0) * 100
        ]
      }]
    },

    radarOptions() {
      return {
        chart: {
          type: 'radar',
          toolbar: { show: false },
          animations: {
            enabled: true,
            easing: 'easeinout',
            speed: 1000
          }
        },
        xaxis: {
          categories: ['Epidemiologia', 'Gravidade', 'Sintomas', 'Sociodemográfico']
        },
        yaxis: {
          show: true,
          max: 100
        },
        fill: {
          opacity: 0.3
        },
        stroke: {
          show: true,
          width: 3,
          colors: [this.nivelCorClass === 'success' ? '#4CAF50' : this.nivelCorClass === 'warning' ? '#FF9800' : '#F44336']
        },
        markers: {
          size: 5,
          colors: [this.nivelCorClass === 'success' ? '#4CAF50' : this.nivelCorClass === 'warning' ? '#FF9800' : '#F44336'],
          strokeWidth: 2,
          hover: { size: 7 }
        },
        tooltip: {
          y: {
            formatter: (val) => `${val.toFixed(1)}%`
          }
        }
      }
    },

    barSeries() {
      return [{
        name: 'Score Normalizado',
        data: [
          (this.avaliacao?.scores?.epidemiologia || 0) * 100,
          (this.avaliacao?.scores?.gravidade || 0) * 100,
          (this.avaliacao?.scores?.sintomas || 0) * 100,
          (this.avaliacao?.scores?.sociodemografico || 0) * 100
        ]
      }]
    },

    barOptions() {
      return {
        chart: {
          type: 'bar',
          toolbar: { show: false },
          animations: {
            enabled: true,
            easing: 'easeinout',
            speed: 1200,
            animateGradually: {
              enabled: true,
              delay: 150
            }
          }
        },
        plotOptions: {
          bar: {
            horizontal: true,
            distributed: true,
            borderRadius: 8,
            dataLabels: {
              position: 'top'
            }
          }
        },
        colors: ['#2196F3', '#F44336', '#FF9800', '#9C27B0'],
        dataLabels: {
          enabled: true,
          formatter: (val) => `${val.toFixed(1)}%`,
          offsetX: -10,
          style: {
            fontSize: '12px',
            colors: ['#fff']
          }
        },
        xaxis: {
          categories: ['Epidemiologia (45%)', 'Gravidade (35%)', 'Sintomas (15%)', 'Sociodemográfico (5%)'],
          max: 100
        },
        yaxis: {
          labels: {
            style: {
              fontSize: '13px',
              fontWeight: 600
            }
          }
        },
        legend: {
          show: false
        },
        tooltip: {
          y: {
            formatter: (val) => `${val.toFixed(2)}%`
          }
        }
      }
    },

    interpretacao() {
      const nivel = this.avaliacao?.nivel_risco || ''

      const interpretacoes = {
        'Baixo': {
          titulo: 'Paciente com risco controlado',
          descricao: 'A análise multicritério indica baixa probabilidade de complicações. O paciente apresenta sintomas leves e o contexto epidemiológico é favorável. Recomenda-se acompanhamento ambulatorial com orientações de hidratação e sinais de alarme.'
        },
        'Médio': {
          titulo: 'Atenção - Monitoramento requerido',
          descricao: 'A avaliação AHP identifica fatores que elevam moderadamente o risco. O paciente necessita de acompanhamento mais rigoroso. Considere consultas de retorno programadas e orientação detalhada sobre sinais de alarme que demandam atenção médica imediata.'
        },
        'Alto': {
          titulo: 'ALERTA - Risco elevado de complicações',
          descricao: 'ATENÇÃO: O método AHP detectou múltiplos fatores de risco significativos. Este paciente requer avaliação médica presencial URGENTE. Considere internação hospitalar para monitoramento contínuo, hidratação venosa e exames laboratoriais seriados.'
        }
      }

      return interpretacoes[nivel] || {
        titulo: 'Avaliação indisponível',
        descricao: 'Não foi possível gerar interpretação para este resultado.'
      }
    },

    interpretacaoEpidemiologia() {
      const score = this.avaliacao?.scores?.epidemiologia || 0
      const incidencia = this.avaliacao?.casos_municipio && this.avaliacao?.populacao_municipio
        ? ((this.avaliacao.casos_municipio / this.avaliacao.populacao_municipio) * 100000).toFixed(1)
        : null

      if (score < 0.33) {
        return `Baixa circulação viral na região (score: ${(score * 100).toFixed(1)}%). ${incidencia ? `Incidência: ${incidencia}/100k hab.` : ''} Contexto epidemiológico favorável.`
      } else if (score < 0.67) {
        return `Circulação viral moderada (score: ${(score * 100).toFixed(1)}%). ${incidencia ? `Incidência: ${incidencia}/100k hab.` : ''} Requer vigilância epidemiológica.`
      } else {
        return `ALERTA: Alta transmissão na área (score: ${(score * 100).toFixed(1)}%). ${incidencia ? `Incidência elevada: ${incidencia}/100k hab.` : ''} Situação epidemiológica crítica.`
      }
    },

    interpretacaoGravidade() {
      const score = this.avaliacao?.scores?.gravidade || 0

      if (score < 0.33) {
        return `Ausência de sinais de alarme ou gravidade (score: ${(score * 100).toFixed(1)}%). Quadro clínico estável.`
      } else if (score < 0.67) {
        return `Presença de sinais de alarme (score: ${(score * 100).toFixed(1)}%). Necessário monitoramento para prevenir evolução para formas graves.`
      } else {
        return `CRÍTICO: Sinais de gravidade identificados (score: ${(score * 100).toFixed(1)}%). Requer intervenção médica IMEDIATA.`
      }
    },

    interpretacaoSintomas() {
      const score = this.avaliacao?.scores?.sintomas || 0
      const total = [
        this.avaliacao?.febre, this.avaliacao?.cefaleia, this.avaliacao?.mialgia,
        this.avaliacao?.artralgia, this.avaliacao?.dor_retro, this.avaliacao?.exantema
      ].filter(Boolean).length

      return `Paciente apresenta ${total} sintoma(s) clássico(s) de dengue (score: ${(score * 100).toFixed(1)}%). ${score > 0.5 ? 'Quadro sintomático significativo.' : 'Sintomatologia leve.'}`
    },

    interpretacaoSociodemografico() {
      const idade = this.avaliacao?.idade
      let faixa = ''

      if (idade < 15) faixa = 'pediátrica (maior risco)'
      else if (idade > 60) faixa = 'idosa (maior risco)'
      else faixa = 'adulta'

      return `Paciente em faixa etária ${faixa}. ${this.avaliacao?.municipio}/${this.avaliacao?.uf}.`
    },

    recomendacoes() {
      const nivel = this.avaliacao?.nivel_risco || ''

      const recomendacoesBaixo = [
        {
          titulo: 'Hidratação Oral',
          texto: 'Orientar ingestão de líquidos abundantes (água, soro caseiro, sucos naturais). Mínimo 2-3 litros/dia.',
          cor: 'blue',
          icone: 'mdi-water'
        },
        {
          titulo: 'Repouso',
          texto: 'Recomendado repouso relativo. Evitar atividades físicas intensas durante período sintomático.',
          cor: 'green',
          icone: 'mdi-bed'
        },
        {
          titulo: 'Analgesia/Antitérmico',
          texto: 'Paracetamol conforme prescrição médica. EVITAR AAS e anti-inflamatórios (risco de sangramento).',
          cor: 'orange',
          icone: 'mdi-pill'
        },
        {
          titulo: 'Sinais de Alarme',
          texto: 'Orientar retorno imediato se: dor abdominal intensa, vômitos persistentes, sangramento, tontura ao levantar.',
          cor: 'red',
          icone: 'mdi-alert-circle'
        }
      ]

      const recomendacoesMedio = [
        {
          titulo: 'Consulta de Retorno',
          texto: 'Agendar reavaliação médica em 24-48h. Monitorar evolução do quadro clínico.',
          cor: 'primary',
          icone: 'mdi-calendar-check'
        },
        {
          titulo: 'Hidratação Rigorosa',
          texto: 'Aumentar ingesta hídrica para 3-4 litros/dia. Considerar hidratação venosa se vômitos persistentes.',
          cor: 'blue',
          icone: 'mdi-water-alert'
        },
        {
          titulo: 'Hemograma de Controle',
          texto: 'Solicitar hemograma para avaliar hematócrito e plaquetas. Repetir conforme evolução.',
          cor: 'purple',
          icone: 'mdi-test-tube'
        },
        {
          titulo: 'Vigilância de Sinais',
          texto: 'Monitorar sinais vitais, diurese e aparecimento de petéquias/sangramentos. Atenção a sinais de choque.',
          cor: 'error',
          icone: 'mdi-heart-pulse'
        },
        {
          titulo: 'Orientação Familiar',
          texto: 'Instruir familiar/cuidador sobre sinais de alarme e necessidade de buscar atendimento urgente.',
          cor: 'warning',
          icone: 'mdi-account-alert'
        }
      ]

      const recomendacoesAlto = [
        {
          titulo: 'ATENDIMENTO URGENTE',
          texto: 'ENCAMINHAR IMEDIATAMENTE para serviço de emergência. Não postergar avaliação médica presencial.',
          cor: 'error',
          icone: 'mdi-ambulance'
        },
        {
          titulo: 'Considerar Internação',
          texto: 'Avaliar necessidade de internação hospitalar para monitoramento contínuo e suporte intensivo.',
          cor: 'red darken-2',
          icone: 'mdi-hospital-building'
        },
        {
          titulo: 'Hidratação Venosa',
          texto: 'Iniciar hidratação endovenosa imediata. Controle rigoroso de balanço hídrico e diurese.',
          cor: 'blue',
          icone: 'mdi-iv-bag'
        },
        {
          titulo: 'Exames Laboratoriais',
          texto: 'Hemograma completo URGENTE, função hepática, coagulograma. Repetir a cada 6-12h conforme quadro.',
          cor: 'purple',
          icone: 'mdi-flask'
        },
        {
          titulo: 'Monitoramento Intensivo',
          texto: 'Sinais vitais de 2/2h, controle de PA, FC, diurese horária. Atentar para sinais de choque.',
          cor: 'orange darken-2',
          icone: 'mdi-monitor-dashboard'
        },
        {
          titulo: 'Suporte Especializado',
          texto: 'Acionar equipe multidisciplinar. Considerar transferência para unidade de terapia intensiva se necessário.',
          cor: 'indigo',
          icone: 'mdi-doctor'
        }
      ]

      const mapRecomendacoes = {
        'Baixo': recomendacoesBaixo,
        'Médio': recomendacoesMedio,
        'Alto': recomendacoesAlto
      }

      return mapRecomendacoes[nivel] || []
    }
  },

  async mounted() {
    await this.carregarAvaliacao()
  },

  methods: {
    ...mapActions('risco', ['buscarAvaliacao']),

    async carregarAvaliacao() {
      this.loading = true
      this.erro = null

      try {
        const id = this.$route.params.id
        const resultado = await this.buscarAvaliacao(id)
        this.avaliacao = resultado
      } catch (error) {
        console.error('Erro ao carregar avaliação:', error)
        this.erro = 'Não foi possível carregar os dados da avaliação.'
      } finally {
        this.loading = false
      }
    },

    exportarPDF() {
      // Implementar exportação PDF com jspdf
      this.$toast.info('Funcionalidade de exportação em desenvolvimento')
    }
  }
}
</script>

<style scoped lang="scss">
.resultado-avaliacao {
  min-height: 100vh;
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

.score-card {
  border-top: 6px solid;
  transition: all 0.3s ease;

  &.border-success {
    border-color: #4CAF50 !important;
  }

  &.border-warning {
    border-color: #FF9800 !important;
  }

  &.border-error {
    border-color: #F44336 !important;
  }

  &:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.15) !important;
  }
}

.score-container {
  padding: 16px;
}

.score-circular {
  animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.05); }
}

.score-content {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.score-value {
  font-size: 2.5rem;
  font-weight: 700;
  line-height: 1;
}

.score-label {
  font-size: 0.875rem;
  color: #666;
  margin-top: 4px;
}

.info-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 12px;
}

.info-item {
  display: flex;
  align-items: center;
  padding: 8px;
  background: #f8f9fa;
  border-radius: 8px;
  transition: background 0.2s;

  &:hover {
    background: #e9ecef;
  }
}

.card-animated {
  animation: fadeInUp 0.6s ease-out;
  transition: all 0.3s ease;

  &:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important;
  }
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.progress-animated {
  transition: all 0.6s ease-out;
}

.detalhes-clinicos {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.detalhe-item {
  display: flex;
  gap: 12px;
  padding: 16px;
  background: #f8f9fa;
  border-radius: 12px;
  border-left: 4px solid #2196F3;
  transition: all 0.3s ease;

  &:hover {
    background: #e3f2fd;
    transform: translateX(4px);
  }

  strong {
    display: block;
    margin-bottom: 4px;
    color: #333;
  }

  p {
    color: #666;
    font-size: 0.9rem;
    line-height: 1.5;
  }
}

.h-100 {
  height: 100%;
}

@media (max-width: 960px) {
  .score-value {
    font-size: 2rem;
  }

  .info-grid {
    grid-template-columns: 1fr;
  }
}
</style>
