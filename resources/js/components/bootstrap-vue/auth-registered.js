import { BContainer, BFormGroup, BFormInput, BButton, BInputGroup, BImg } from 'bootstrap-vue'

export default {
  install(Vue) {
    Vue.component('BButton', BButton)
    Vue.component('BContainer', BContainer)
    Vue.component('BFormGroup', BFormGroup)
    Vue.component('BFormInput', BFormInput)
    Vue.component('BImg', BImg)
    Vue.component('BInputGroup', BInputGroup)
  },
}
