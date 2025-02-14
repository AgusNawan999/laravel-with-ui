import {
  BContainer,
  BButton,
  BAlert,
  IconsPlugin,
  ModalPlugin,
  BFormGroup,
  BFormInput,
  BInputGroup,
  BFormCheckbox,
  BFormCheckboxGroup,
  BFormTextarea,
  BFormFile,
  SidebarPlugin,
  FormRadioPlugin,
  AvatarPlugin,
  DropdownPlugin,
  FormRatingPlugin,
  BCollapse,
  CardPlugin,
  VBModal,
  BFormRadioGroup,
  BSpinner,
  BOverlay,
  BLink,
  ListGroupPlugin,
  TablePlugin,
  BPagination,
  ProgressPlugin,
} from 'bootstrap-vue'

import VueMultiselect from 'vue-multiselect'


export default {
  install(Vue) {
    Vue.component('BContainer', BContainer)
    Vue.component('BButton', BButton)
    Vue.component('BAlert', BAlert)
    Vue.component('BFormGroup', BFormGroup)
    Vue.component('BFormInput', BFormInput)
    Vue.component('BInputGroup', BInputGroup)
    Vue.component('BFormCheckbox', BFormCheckbox)
    Vue.component('BFormCheckboxGroup', BFormCheckboxGroup)
    Vue.component('BFormTextarea', BFormTextarea)
    Vue.component('BFormFile', BFormFile)
    Vue.component('BCollapse', BCollapse)
    Vue.component('BPagination', BPagination)
    Vue.component('VueMultiselect', VueMultiselect)
    Vue.component('BFormRadioGroup', BFormRadioGroup)
    Vue.component('BSpinner', BSpinner)
    Vue.component('BOverlay', BOverlay)
    Vue.component('BLink', BLink)

    Vue.use(ModalPlugin)
    Vue.use(IconsPlugin)
    Vue.use(SidebarPlugin)
    Vue.use(FormRadioPlugin)
    Vue.use(AvatarPlugin)
    Vue.use(DropdownPlugin)
    Vue.use(FormRatingPlugin)
    Vue.use(CardPlugin)
    Vue.use(ListGroupPlugin)
    Vue.use(TablePlugin)
    Vue.use(ProgressPlugin)

    Vue.directive('b-modal', VBModal)
  },
}
