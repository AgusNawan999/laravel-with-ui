import {
  BContainer,
  BButton,
  BAvatar,
  BTooltip,
  BModal,
  VBModal,
  BCollapse,
  VBToggle,
  BCard,
  BTable,
  BPagination,
  BDropdown,
  BDropdownItem,
  BFormGroup,
  BFormInput,
  BFormTextarea,
  BFormRadioGroup,
  BFormFile,
  BFormSelect,
  BInputGroup,
  BBadge,
  BOverlay,
  BFormDatepicker,
  BTime,
  BListGroup,
  BSpinner,
  BFormRating,
  BForm,
  BLink,
  SidebarPlugin,
  TabsPlugin,
  BFormCheckboxGroup,
  FormCheckboxPlugin,
  BImg

} from 'bootstrap-vue'

export default {
  install(Vue) {
    // component register
    Vue.component('BContainer', BContainer)
    Vue.component('BButton', BButton)
    Vue.component('BAvatar', BAvatar)
    Vue.component('BTooltip', BTooltip)
    Vue.component('BModal', BModal)
    Vue.component('BCollapse', BCollapse)
    Vue.component('BCard', BCard)
    Vue.component('BTable', BTable)
    Vue.component('BPagination', BPagination)
    Vue.component('BDropdown', BDropdown)
    Vue.component('BDropdownItem', BDropdownItem)
    Vue.component('BFormGroup', BFormGroup)
    Vue.component('BFormInput', BFormInput)
    Vue.component('BFormTextarea', BFormTextarea)
    Vue.component('BFormRadioGroup', BFormRadioGroup)
    Vue.component('BFormFile', BFormFile)
    Vue.component('BFormSelect', BFormSelect)
    Vue.component('BInputGroup', BInputGroup)
    Vue.component('BBadge', BBadge)
    Vue.component('BOverlay', BOverlay)
    Vue.component('BFormDatepicker', BFormDatepicker)
    Vue.component('BTime', BTime)
    Vue.component('BSpinner', BSpinner)
    Vue.component('BFormRating', BFormRating)
    Vue.component('BForm', BForm)
    Vue.component('BLink', BLink)
    Vue.component('BFormCheckBoxGroup', BFormCheckboxGroup)
    Vue.component('BImg', BImg)

    // directive register
    Vue.directive('b-list-group', BListGroup)
    Vue.directive('b-modal', VBModal)
    Vue.directive('b-toggle', VBToggle)
    // plugins register
    Vue.use(SidebarPlugin)
    Vue.use(TabsPlugin)
    Vue.use(FormCheckboxPlugin)
  },
}
