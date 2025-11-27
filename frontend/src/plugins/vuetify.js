import '@mdi/font/css/materialdesignicons.css'
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import Vue from 'vue'
import Vuetify from 'vuetify/lib'
import { Ripple } from 'vuetify/lib/directives'
import pt from 'vuetify/es5/locale/pt'

Vue.use(Vuetify, {
  directives: {
    Ripple
  }
})

export default new Vuetify({
  icons: {
    iconfont: 'md',
  },
  theme: {
    themes: {
      dark: {
        primary: '#ff8c00',
        secondary: '#868686',
        accent: '#ffb900',
        error: '#e91e63',
        warning: '#f44336',
        info: '#2196f3',
        success: '#41e185'
      }
    }
  },
  lang: {
    locales: { pt },
    current: 'pt',
  },
})
