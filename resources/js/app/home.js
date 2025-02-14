import '@/js/bootstrap.js'
import '@/js/echo.js'

import VueTippy from 'vue-tippy'
import App from '@/js/MainApp.vue'
import { createPinia } from 'pinia'
import Vue, { createApp } from 'vue'
import route from '@/js/router/home-routes'
import { configureCompat } from '@/js/compat-config.js'
import HomeComponent from '@/js/components/bootstrap-vue/home-registered'

// vee-validate config
import rules from '@vee-validate/rules'
import { defineRule, configure } from 'vee-validate'
import id from '@vee-validate/i18n/dist/locale/id.json'
import { localize, setLocale } from '@vee-validate/i18n'

// custom validation
import { minHpLength } from '../../js/composable/useRules'

configure({ generateMessage: localize({ id }) })
Object.keys(rules).forEach(rule => defineRule(rule, rules[rule]))
defineRule('minHpLength', minHpLength)
setLocale('id')

configureCompat(Vue)
Vue.use(HomeComponent)

const pinia = createPinia()
createApp(App)
  .use(VueTippy, { size: 'small', directive: 'tippy', flipDuration: 0 })
  .use(route)
  .use(pinia)
  .mount('#app')
