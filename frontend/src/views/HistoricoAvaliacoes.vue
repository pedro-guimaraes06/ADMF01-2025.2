<template>
  <v-container fluid class="historico-avaliacoes pa-6">
    <v-card elevation="4">
      <v-card-title class="primary white--text">
        <v-icon left color="white">mdi-history</v-icon>
        Histórico de Avaliações
      </v-card-title>
      <v-card-text class="pa-6">
        <v-alert type="info" prominent border="left">
          <v-row align="center">
            <v-col class="grow">
              Componente de histórico em desenvolvimento
            </v-col>
            <v-col class="shrink">
              <v-btn color="primary" @click="$router.push('/avaliacao')">
                Nova Avaliação
              </v-btn>
            </v-col>
          </v-row>
        </v-alert>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script>
export default {
  name: 'HistoricoAvaliacoes',
  
  metaInfo: {
    title: 'Histórico - SAD Dengue'
  }
}
</script>

<style scoped>
.historico-avaliacoes {
  min-height: 100vh;
  background: linear-gradient(135deg, #E3F2FD 0%, #FFFFFF 100%);
}
</style>
