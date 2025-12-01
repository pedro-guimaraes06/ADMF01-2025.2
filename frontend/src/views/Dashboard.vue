<template>
  <v-container fluid class="dashboard pa-6">
    <v-card elevation="4">
      <v-card-title class="primary white--text">
        <v-icon left color="white">mdi-view-dashboard</v-icon>
        Dashboard Epidemiológico
      </v-card-title>
      <v-card-text class="pa-6">
        <v-alert type="info" prominent border="left">
          <v-row align="center">
            <v-col class="grow">
              Dashboard com estatísticas epidemiológicas em desenvolvimento
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
  name: 'Dashboard',
  
  metaInfo: {
    title: 'Dashboard - SAD Dengue'
  }
}
</script>

<style scoped>
.dashboard {
  min-height: 100vh;
  background: linear-gradient(135deg, #E3F2FD 0%, #FFFFFF 100%);
}
</style>
