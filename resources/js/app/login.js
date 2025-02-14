import '@/js/bootstrap.js'
import Vue, { createApp } from 'vue'
import LoginForm from '@/js/modules/auth/Login.vue'
import AuthComponent from '@/js/components/bootstrap-vue/auth-registered'

Vue.use(AuthComponent)
const app = createApp(LoginForm)

app.directive('focus', {
  mounted(el) {
    el.focus()
  },
})
app.mount('#app')
