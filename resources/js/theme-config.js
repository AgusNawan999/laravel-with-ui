import $ from 'jquery'
import { rgb2hex } from '@/js/utils/helper'

Object.assign(window, { $, jQuery: $, jquery: $, rgb2hex })

// theme setting
const classHolder = document.getElementsByTagName('body')[0]

// load from storage
const DEFAULT_THEME_CLASS = 'mod-bg-1 header-function-fixed nav-function-fixed mod-skin-light'

const themeSettings = localStorage.getItem('themeSettings')
  ? JSON.parse(localStorage.getItem('themeSettings'))
  : {
      themeOptions: DEFAULT_THEME_CLASS,
      themeURL: '',
    }

const themeURL = themeSettings.themeURL || ''
// const themeOptions = themeSettings.themeOptions || DEFAULT_THEME_CLASS

// load theme options
if (themeSettings.themeOptions) classHolder.className = themeSettings.themeOptions

if (themeSettings.themeURL && !document.getElementById('mytheme')) {
  const cssFile = document.createElement('link')
  cssFile.id = 'mytheme'
  cssFile.rel = 'stylesheet'
  cssFile.href = themeURL
  document.getElementsByTagName('head')[0].appendChild(cssFile)
}
