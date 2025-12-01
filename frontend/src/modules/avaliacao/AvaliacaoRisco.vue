<template>
  <div class="avaliacao-risco">
    <FormAvaliacaoRisco />
  </div>
</template>

<script>
import FormAvaliacaoRisco from '@/components/FormAvaliacaoRisco.vue'

export default {
  name: 'AvaliacaoRisco',
  
  components: {
    FormAvaliacaoRisco
  },

  metaInfo: {
    title: 'Avaliação de Risco - SAD Dengue'
  }
}
</script>

<style scoped>
.avaliacao-risco {
  min-height: 100vh;
  background: linear-gradient(135deg, #E3F2FD 0%, #FFFFFF 100%);
}
</style>
