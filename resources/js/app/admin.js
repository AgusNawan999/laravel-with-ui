import '@/js/bootstrap.js'
import VueTippy from 'vue-tippy'
import App from '@/js/MainApp.vue'
import { createPinia } from 'pinia'
import Vue, { createApp } from 'vue'
import VueSlimScroll from 'vue-slimscroll'
import route from '../router/admin-routes.js'
import { configureCompat } from '@/js/compat-config.js'
import AdminComponent from '../components/bootstrap-vue/admin-registered.js'

import VueMultiselect from 'vue-multiselect'
import VueTree from 'vue3-tree'

// vee-validate config
import rules from '@vee-validate/rules'
import { defineRule, configure } from 'vee-validate'
import id from '@vee-validate/i18n/dist/locale/id.json'
import { localize, setLocale } from '@vee-validate/i18n'

// vueeasylightbox
import VueEasyLightbox from 'vue-easy-lightbox'

// vue-ctk-date-time-picker
import VueCtkDateTimePicker from 'vue-ctk-date-time-picker';

configure({ generateMessage: localize({ id }) })
Object.keys(rules).forEach(rule => defineRule(rule, rules[rule]))
setLocale('id')

configureCompat(Vue)

const pinia = createPinia()


Vue.component('VueTree', VueTree)
Vue.component('VueMultiselect', VueMultiselect)
Vue.component('VueCtkDateTimePicker', VueCtkDateTimePicker)
Vue.use(AdminComponent)
Vue.use(VueSlimScroll)
Vue.use(VueEasyLightbox)

createApp(App)
  .use(VueTippy, { size: 'small', directive: 'tippy', flipDuration: 0 })
  .use(route)
  .use(pinia)
  .mount('#app')
