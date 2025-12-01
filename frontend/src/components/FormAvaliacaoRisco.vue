<template>
  <v-container fluid class="pa-6">
    <v-card elevation="4" class="mx-auto" max-width="1200">
      <!-- Header -->
      <v-card-title class="primary white--text text-h5">
        <v-icon left color="white" large>mdi-clipboard-pulse</v-icon>
        Avaliação de Risco de Dengue
      </v-card-title>

      <v-card-subtitle class="primary white--text pb-3">
        Sistema de Apoio à Decisão baseado em critérios clínicos e epidemiológicos
      </v-card-subtitle>

      <v-divider></v-divider>

      <!-- Form -->
      <v-form ref="form" v-model="valid" @submit.prevent="calcularRisco">
        <v-card-text>
          <v-stepper v-model="step" vertical non-linear>
            
            <!-- Step 1: Dados do Paciente -->
            <v-stepper-step :complete="step > 1" step="1" editable>
              Dados do Paciente
              <small>Informações sociodemográficas</small>
            </v-stepper-step>
            <v-stepper-content step="1">
              <v-row>
                <v-col cols="12" md="4">
                  <v-text-field
                    v-model.number="form.idade"
                    label="Idade *"
                    type="number"
                    :rules="[rules.required, rules.idade]"
                    outlined
                    dense
                    prepend-icon="mdi-calendar"
                    suffix="anos"
                    hint="Idade do paciente"
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="4">
                  <v-select
                    v-model="form.sexo"
                    :items="opcoesSexo"
                    label="Sexo *"
                    :rules="[rules.required]"
                    outlined
                    dense
                    prepend-icon="mdi-gender-male-female"
                  ></v-select>
                </v-col>

                <v-col cols="12" md="4">
                  <v-select
                    v-model="form.uf"
                    :items="ufs"
                    label="UF *"
                    :rules="[rules.required]"
                    outlined
                    dense
                    prepend-icon="mdi-map-marker"
                  ></v-select>
                </v-col>

                <v-col cols="12" md="8">
                  <v-text-field
                    v-model="form.municipio"
                    label="Município *"
                    :rules="[rules.required]"
                    outlined
                    dense
                    prepend-icon="mdi-city"
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="4">
                  <v-text-field
                    v-model="form.codigo_municipio"
                    label="Código IBGE"
                    outlined
                    dense
                    prepend-icon="mdi-numeric"
                    maxlength="7"
                  ></v-text-field>
                </v-col>
              </v-row>

              <v-btn color="primary" @click="step = 2">
                Continuar
                <v-icon right>mdi-arrow-right</v-icon>
              </v-btn>
            </v-stepper-content>

            <!-- Step 2: Dados Epidemiológicos -->
            <v-stepper-step :complete="step > 2" step="2" editable>
              Dados Epidemiológicos
              <small>Contexto epidemiológico local</small>
            </v-stepper-step>
            <v-stepper-content step="2">
              <v-alert type="info" dense outlined class="mb-4">
                <v-icon left>mdi-information</v-icon>
                Estas informações são essenciais para o cálculo do risco epidemiológico (peso: 45%)
              </v-alert>

              <v-row>
                <v-col cols="12" md="6">
                  <v-text-field
                    v-model.number="form.casos_municipio"
                    label="Casos no Município *"
                    type="number"
                    :rules="[rules.required, rules.nonNegative]"
                    outlined
                    dense
                    prepend-icon="mdi-account-multiple"
                    hint="Total de casos confirmados no município"
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="6">
                  <v-text-field
                    v-model.number="form.populacao_municipio"
                    label="População do Município *"
                    type="number"
                    :rules="[rules.required, rules.positive]"
                    outlined
                    dense
                    prepend-icon="mdi-account-group"
                    hint="População total estimada"
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="6">
                  <v-select
                    v-model.number="form.semana_epidemiologica"
                    :items="semanas"
                    label="Semana Epidemiológica *"
                    :rules="[rules.required]"
                    outlined
                    dense
                    prepend-icon="mdi-calendar-week"
                    hint="Semana atual do ano (1-53)"
                  ></v-select>
                </v-col>

                <v-col cols="12" md="6">
                  <v-text-field
                    v-model.number="form.tendencia_temporal"
                    label="Tendência Temporal"
                    type="number"
                    step="0.01"
                    outlined
                    dense
                    prepend-icon="mdi-chart-line"
                    hint="Coeficiente de tendência (opcional)"
                  ></v-text-field>
                </v-col>

                <!-- Cálculo automático de incidência -->
                <v-col cols="12">
                  <v-alert type="success" dense text v-if="incidenciaCalculada">
                    <strong>Incidência calculada:</strong> {{ incidenciaCalculada }} casos/100k habitantes
                  </v-alert>
                </v-col>
              </v-row>

              <v-btn text @click="step = 1">
                <v-icon left>mdi-arrow-left</v-icon>
                Voltar
              </v-btn>
              <v-btn color="primary" @click="step = 3" class="ml-2">
                Continuar
                <v-icon right>mdi-arrow-right</v-icon>
              </v-btn>
            </v-stepper-content>

            <!-- Step 3: Sintomas -->
            <v-stepper-step :complete="step > 3" step="3" editable>
              Sintomas Clínicos
              <small>{{ sintomasSelecionados }} sintomas selecionados</small>
            </v-stepper-step>
            <v-stepper-content step="3">
              <v-alert type="warning" dense outlined class="mb-4">
                <v-icon left>mdi-alert</v-icon>
                Selecione todos os sintomas presentes no paciente (peso: 15%)
              </v-alert>

              <!-- Sintomas Clássicos -->
              <v-card outlined class="mb-4">
                <v-card-subtitle class="font-weight-bold primary--text">
                  <v-icon left color="primary">mdi-thermometer-alert</v-icon>
                  Sintomas Clássicos de Dengue
                </v-card-subtitle>
                <v-card-text>
                  <v-row dense>
                    <v-col cols="12" sm="6" md="4" v-for="sintoma in sintomasClassicos" :key="sintoma.campo">
                      <v-checkbox
                        v-model="form[sintoma.campo]"
                        :label="sintoma.label"
                        :color="sintoma.cor"
                        hide-details
                        dense
                      ></v-checkbox>
                    </v-col>
                  </v-row>
                </v-card-text>
              </v-card>

              <!-- Sintomas Inespecíficos -->
              <v-card outlined>
                <v-card-subtitle class="font-weight-bold">
                  <v-icon left>mdi-alert-circle-outline</v-icon>
                  Sintomas Inespecíficos
                </v-card-subtitle>
                <v-card-text>
                  <v-row dense>
                    <v-col cols="12" sm="6" md="4" v-for="sintoma in sintomasInespecificos" :key="sintoma.campo">
                      <v-switch
                        v-model="form[sintoma.campo]"
                        :label="sintoma.label"
                        color="orange"
                        hide-details
                        dense
                        inset
                      ></v-switch>
                    </v-col>
                  </v-row>
                </v-card-text>
              </v-card>

              <div class="mt-4">
                <v-btn text @click="step = 2">
                  <v-icon left>mdi-arrow-left</v-icon>
                  Voltar
                </v-btn>
                <v-btn color="primary" @click="step = 4" class="ml-2">
                  Continuar
                  <v-icon right>mdi-arrow-right</v-icon>
                </v-btn>
              </div>
            </v-stepper-content>

            <!-- Step 4: Sinais de Alarme -->
            <v-stepper-step :complete="step > 4" step="4" editable :color="alarmesSelecionados > 0 ? 'warning' : 'primary'">
              Sinais de Alarme
              <small>{{ alarmesSelecionados }} sinais detectados</small>
            </v-stepper-step>
            <v-stepper-content step="4">
              <v-alert type="error" dense outlined class="mb-4" v-if="alarmesSelecionados > 0">
                <v-icon left>mdi-alert</v-icon>
                <strong>ATENÇÃO:</strong> {{ alarmesSelecionados }} sinal(is) de alarme detectado(s)!
              </v-alert>

              <v-card outlined color="orange lighten-5">
                <v-card-subtitle class="font-weight-bold error--text">
                  <v-icon left color="error">mdi-alert-octagon</v-icon>
                  Sinais que indicam gravidade potencial (peso: 60% do critério Gravidade)
                </v-card-subtitle>
                <v-card-text>
                  <v-row dense>
                    <v-col cols="12" sm="6" md="4" v-for="alarme in sinaisAlarme" :key="alarme.campo">
                      <v-checkbox
                        v-model="form[alarme.campo]"
                        :label="alarme.label"
                        color="error"
                        hide-details
                        dense
                      >
                        <template v-slot:label>
                          <span :class="form[alarme.campo] ? 'font-weight-bold error--text' : ''">
                            {{ alarme.label }}
                          </span>
                        </template>
                      </v-checkbox>
                    </v-col>
                  </v-row>
                </v-card-text>
              </v-card>

              <div class="mt-4">
                <v-btn text @click="step = 3">
                  <v-icon left>mdi-arrow-left</v-icon>
                  Voltar
                </v-btn>
                <v-btn color="primary" @click="step = 5" class="ml-2">
                  Continuar
                  <v-icon right>mdi-arrow-right</v-icon>
                </v-btn>
              </div>
            </v-stepper-content>

            <!-- Step 5: Sinais de Gravidade -->
            <v-stepper-step :complete="step > 5" step="5" editable :color="gravidadeSelecionados > 0 ? 'error' : 'primary'">
              Sinais de Gravidade
              <small>{{ gravidadeSelecionados }} sinais detectados</small>
            </v-stepper-step>
            <v-stepper-content step="5">
              <v-alert type="error" dense outlined class="mb-4" v-if="gravidadeSelecionados > 0">
                <v-icon left>mdi-alert-octagram</v-icon>
                <strong>CRÍTICO:</strong> {{ gravidadeSelecionados }} sinal(is) de gravidade presente(s)!
              </v-alert>

              <v-card outlined color="red lighten-5">
                <v-card-subtitle class="font-weight-bold error--text">
                  <v-icon left color="error">mdi-alert-octagram-outline</v-icon>
                  Sinais de dengue grave - Requer atenção médica imediata (peso: 40% do critério Gravidade)
                </v-card-subtitle>
                <v-card-text>
                  <v-row dense>
                    <v-col cols="12" sm="6" md="4" v-for="gravidade in sinaisGravidade" :key="gravidade.campo">
                      <v-checkbox
                        v-model="form[gravidade.campo]"
                        :label="gravidade.label"
                        color="error"
                        hide-details
                        dense
                      >
                        <template v-slot:label>
                          <span :class="form[gravidade.campo] ? 'font-weight-bold error--text' : ''">
                            {{ gravidade.label }}
                          </span>
                        </template>
                      </v-checkbox>
                    </v-col>
                  </v-row>
                </v-card-text>
              </v-card>

              <div class="mt-4">
                <v-btn text @click="step = 4">
                  <v-icon left>mdi-arrow-left</v-icon>
                  Voltar
                </v-btn>
                <v-btn color="primary" @click="step = 6" class="ml-2">
                  Revisar
                  <v-icon right>mdi-arrow-right</v-icon>
                </v-btn>
              </div>
            </v-stepper-content>

            <!-- Step 6: Revisão -->
            <v-stepper-step step="6">
              Revisão e Envio
              <small>Confirmar dados antes do cálculo</small>
            </v-stepper-step>
            <v-stepper-content step="6">
              <v-card outlined class="mb-4">
                <v-card-title class="subtitle-1">
                  <v-icon left>mdi-file-document-check</v-icon>
                  Resumo da Avaliação
                </v-card-title>
                <v-divider></v-divider>
                <v-simple-table dense>
                  <tbody>
                    <tr><td class="font-weight-bold">Paciente:</td><td>{{ form.idade }} anos, {{ form.sexo }}</td></tr>
                    <tr><td class="font-weight-bold">Localização:</td><td>{{ form.municipio }}/{{ form.uf }}</td></tr>
                    <tr><td class="font-weight-bold">Semana Epi:</td><td>{{ form.semana_epidemiologica }}</td></tr>
                    <tr><td class="font-weight-bold">Casos no município:</td><td>{{ form.casos_municipio }}</td></tr>
                    <tr><td class="font-weight-bold">Sintomas:</td><td>{{ sintomasSelecionados }}</td></tr>
                    <tr><td class="font-weight-bold text-orange">Sinais de alarme:</td><td class="text-orange font-weight-bold">{{ alarmesSelecionados }}</td></tr>
                    <tr><td class="font-weight-bold text-red">Sinais de gravidade:</td><td class="text-red font-weight-bold">{{ gravidadeSelecionados }}</td></tr>
                  </tbody>
                </v-simple-table>
              </v-card>

              <div class="d-flex justify-space-between">
                <v-btn text @click="step = 5">
                  <v-icon left>mdi-arrow-left</v-icon>
                  Voltar
                </v-btn>

                <div>
                  <v-btn text color="error" @click="limparFormulario" class="mr-2">
                    <v-icon left>mdi-refresh</v-icon>
                    Limpar
                  </v-btn>

                  <v-btn
                    type="submit"
                    color="success"
                    large
                    :loading="loading"
                    :disabled="!valid || loading"
                  >
                    <v-icon left>mdi-calculator</v-icon>
                    Calcular Risco
                  </v-btn>
                </div>
              </div>
            </v-stepper-content>

          </v-stepper>
        </v-card-text>
      </v-form>
    </v-card>

    <!-- Snackbar para mensagens -->
    <v-snackbar v-model="snackbar" :color="snackbarColor" :timeout="5000" top>
      {{ snackbarMessage }}
      <template v-slot:action="{ attrs }">
        <v-btn text v-bind="attrs" @click="snackbar = false">Fechar</v-btn>
      </template>
    </v-snackbar>
  </v-container>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
  name: 'FormAvaliacaoRisco',

  data() {
    return {
      valid: false,
      step: 1,
      loading: false,
      snackbar: false,
      snackbarMessage: '',
      snackbarColor: 'success',

      form: {
        // Dados do paciente
        idade: null,
        sexo: '',
        uf: '',
        municipio: '',
        codigo_municipio: '',

        // Dados epidemiológicos
        casos_municipio: null,
        populacao_municipio: null,
        semana_epidemiologica: null,
        tendencia_temporal: null,

        // Sintomas clássicos
        febre: false,
        cefaleia: false,
        mialgia: false,
        artralgia: false,
        dor_retro: false,
        exantema: false,

        // Sintomas inespecíficos
        nausea: false,
        vomito: false,
        dor_costas: false,
        conjuntvit: false,
        petequia_n: false,
        leucopenia: false,
        laco: false,

        // Sinais de alarme
        alrm_hipot: false,
        alrm_plaq: false,
        alrm_vom: false,
        alrm_sang: false,
        alrm_hemat: false,
        alrm_abdom: false,
        alrm_letar: false,
        alrm_hepat: false,
        alrm_liq: false,

        // Sinais de gravidade
        grav_pulso: false,
        grav_conv: false,
        grav_ench: false,
        grav_insc: false,
        grav_extre: false,
        grav_hipot: false,
        grav_hemat: false,
        grav_melen: false,
        grav_metro: false,
        grav_sang: false,
        grav_ast: false,
        grav_mioc: false,
        grav_consc: false,
        grav_orgao: false
      },

      // Regras de validação
      rules: {
        required: v => !!v || 'Campo obrigatório',
        idade: v => (v >= 0 && v <= 120) || 'Idade deve estar entre 0 e 120',
        nonNegative: v => v >= 0 || 'Valor não pode ser negativo',
        positive: v => v > 0 || 'Valor deve ser maior que zero'
      },

      // Opções para selects
      opcoesSexo: [
        { text: 'Masculino', value: 'M' },
        { text: 'Feminino', value: 'F' },
        { text: 'Ignorado', value: 'I' }
      ],

      ufs: ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'],

      // Sintomas
      sintomasClassicos: [
        { campo: 'febre', label: 'Febre', cor: 'error' },
        { campo: 'cefaleia', label: 'Cefaleia (dor de cabeça)', cor: 'primary' },
        { campo: 'mialgia', label: 'Mialgia (dor muscular)', cor: 'primary' },
        { campo: 'artralgia', label: 'Artralgia (dor nas articulações)', cor: 'primary' },
        { campo: 'dor_retro', label: 'Dor retroorbital', cor: 'primary' },
        { campo: 'exantema', label: 'Exantema (manchas vermelhas)', cor: 'primary' }
      ],

      sintomasInespecificos: [
        { campo: 'nausea', label: 'Náusea' },
        { campo: 'vomito', label: 'Vômito' },
        { campo: 'dor_costas', label: 'Dor nas costas' },
        { campo: 'conjuntvit', label: 'Conjuntivite' },
        { campo: 'petequia_n', label: 'Petéquias' },
        { campo: 'leucopenia', label: 'Leucopenia' },
        { campo: 'laco', label: 'Prova do laço positiva' }
      ],

      // Sinais de alarme
      sinaisAlarme: [
        { campo: 'alrm_hipot', label: 'Hipotensão postural' },
        { campo: 'alrm_plaq', label: 'Plaquetopenia' },
        { campo: 'alrm_vom', label: 'Vômitos persistentes' },
        { campo: 'alrm_sang', label: 'Sangramento de mucosas' },
        { campo: 'alrm_hemat', label: 'Aumento do hematócrito' },
        { campo: 'alrm_abdom', label: 'Dor abdominal intensa' },
        { campo: 'alrm_letar', label: 'Letargia/irritabilidade' },
        { campo: 'alrm_hepat', label: 'Hepatomegalia dolorosa' },
        { campo: 'alrm_liq', label: 'Acúmulo de líquidos' }
      ],

      // Sinais de gravidade
      sinaisGravidade: [
        { campo: 'grav_pulso', label: 'Pulso filiforme' },
        { campo: 'grav_conv', label: 'Convulsões' },
        { campo: 'grav_ench', label: 'Enchimento capilar lento' },
        { campo: 'grav_insc', label: 'Insuficiência respiratória' },
        { campo: 'grav_extre', label: 'Extremidades frias' },
        { campo: 'grav_hipot', label: 'Hipotensão arterial' },
        { campo: 'grav_hemat', label: 'Hematócrito elevado' },
        { campo: 'grav_melen', label: 'Melena' },
        { campo: 'grav_metro', label: 'Metrorragia' },
        { campo: 'grav_sang', label: 'Sangramento grave' },
        { campo: 'grav_ast', label: 'AST/ALT > 1000' },
        { campo: 'grav_mioc', label: 'Miocardite' },
        { campo: 'grav_consc', label: 'Alteração de consciência' },
        { campo: 'grav_orgao', label: 'Falência de órgãos' }
      ]
    }
  },

  computed: {
    ...mapGetters('risco', ['isLoading', 'erro']),

    semanas() {
      return Array.from({ length: 53 }, (_, i) => ({
        text: `Semana ${i + 1}`,
        value: i + 1
      }))
    },

    incidenciaCalculada() {
      if (this.form.casos_municipio && this.form.populacao_municipio) {
        return ((this.form.casos_municipio / this.form.populacao_municipio) * 100000).toFixed(2)
      }
      return null
    },

    sintomasSelecionados() {
      return [...this.sintomasClassicos, ...this.sintomasInespecificos]
        .filter(s => this.form[s.campo]).length
    },

    alarmesSelecionados() {
      return this.sinaisAlarme.filter(a => this.form[a.campo]).length
    },

    gravidadeSelecionados() {
      return this.sinaisGravidade.filter(g => this.form[g.campo]).length
    }
  },

  methods: {
    ...mapActions('risco', ['avaliarRisco']),

    async calcularRisco() {
      if (!this.$refs.form.validate()) {
        this.mostrarMensagem('Por favor, preencha todos os campos obrigatórios', 'error')
        return
      }

      this.loading = true

      try {
        const resultado = await this.avaliarRisco(this.form)

        this.mostrarMensagem('Avaliação realizada com sucesso!', 'success')
        
        // Redirecionar para página de resultado
        this.$router.push({
          name: 'Resultado',
          params: { id: resultado.avaliacao_id }
        })
      } catch (error) {
        console.error('Erro ao calcular risco:', error)
        this.mostrarMensagem(
          error.response?.data?.message || 'Erro ao processar avaliação. Tente novamente.',
          'error'
        )
      } finally {
        this.loading = false
      }
    },

    limparFormulario() {
      this.$refs.form.reset()
      Object.keys(this.form).forEach(key => {
        if (typeof this.form[key] === 'boolean') {
          this.form[key] = false
        } else {
          this.form[key] = null
        }
      })
      this.step = 1
      this.mostrarMensagem('Formulário limpo', 'info')
    },

    mostrarMensagem(mensagem, cor = 'success') {
      this.snackbarMessage = mensagem
      this.snackbarColor = cor
      this.snackbar = true
    }
  }
}
</script>

<style scoped>
.v-stepper__content {
  padding: 24px 24px 16px 24px;
}

.text-orange {
  color: #FF9800 !important;
}

.text-red {
  color: #F44336 !important;
}
</style>
