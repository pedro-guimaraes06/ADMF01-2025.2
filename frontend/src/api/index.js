import Vue from 'vue'
import axios from 'axios'
import VueAxios from 'vue-axios'

const Api = {
  init() {
    Vue.use(VueAxios, axios)
    Vue.axios.defaults.baseURL = process.env.VUE_APP_API_URL
    Vue.axios.defaults.headers.common['Content-Type'] = 'application/json'
    Vue.axios.defaults.headers.common['Accept'] = 'application/json'
  }
}

export default Api
