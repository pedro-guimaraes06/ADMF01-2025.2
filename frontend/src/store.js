import Vue from 'vue'
import Vuex from 'vuex'
import risco from './store/modules/risco'
import epidemiologia from './store/modules/epidemiologia'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    appName: 'SAD Dengue',
    version: '1.0.0'
  },
  mutations: {},
  actions: {},
  modules: {
    risco,
    epidemiologia
  }
})
