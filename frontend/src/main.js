import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import mixin from './mixin'

import vuetify from './plugins/vuetify'
import Vuebar from 'vuebar'
import VueParticles from 'vue-particles'
import Vuelidate from 'vuelidate'
import VuetifyMoney from 'vuetify-money'
import DatetimePicker from 'vuetify-datetime-picker'
import Filters from './filters'

import VueIziToast from 'vue-izitoast'
import money from 'v-money'
import { VueMaskDirective } from 'v-mask'
import ViaCep from 'vue-viacep'
import VueScrollTo from 'vue-scrollto'
import VueApexCharts from 'vue-apexcharts'
import Vue2Editor from "vue2-editor"

import 'izitoast/dist/css/iziToast.min.css'

import Api from './api'

Api.init()
Vue.use(VueParticles)
Vue.use(Vuelidate)
Vue.use(Vuebar)
Vue.use(VueIziToast)
Vue.use(VuetifyMoney)
Vue.use(DatetimePicker)
Vue.use(VueScrollTo, {
  container: '.vb-content',
  duration: 500,
  easing: 'ease',
  offset: -150,
  force: true,
  cancelable: true,
  onStart: false,
  onDone: false,
  onCancel: false,
  x: false,
  y: true
})
Vue.use(ViaCep)
Vue.use(VueApexCharts)
Vue.use(Vue2Editor)

Vue.component('apexchart', VueApexCharts)

Vue.mixin(mixin)
Vue.config.productionTip = false

Vue.directive('mask', VueMaskDirective)
Vue.use(money, { precision: 4 })

new Vue({
  router,
  store,
  vuetify,
  Filters,
  render: h => h(App)
}).$mount('#app')
