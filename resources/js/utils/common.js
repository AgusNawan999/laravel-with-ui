import moment from 'moment'
import route from 'ziggy-js'
import Hashids from 'hashids'
import { Buffer } from 'buffer'
import { useAxios } from '@/js/composable/useAxios'
import { useAlert, useConfirmation, useToast } from '@/js/composable/useSwal'

// i18n for moment
import 'moment/dist/locale/id'

const _ = window._
const _http = useAxios()
const _route = route
const _settings = window.settings
const _alert = useAlert()
const _confirm = useConfirmation
const _toast = useToast()

// settings for moment
moment.suppressDeprecationWarnings = true
moment.locale('id')
const _moment = moment

const hex2String = (hex) => {
  let string = ''
  for (let i = 0; i < hex.length; i += 2) {
    string += String.fromCharCode(parseInt(hex.substr(i, 2), 16))
  }
  return string
}

const _encrypt = (text) => {
  const hashids = new Hashids(btoa(_settings.appname), 5) // pad to length 10
  var hex = Buffer(text).toString('hex')
  return hashids.encodeHex(hex)
}

const _decrypt = (text) => {
  const hashids = new Hashids(btoa(_settings.appname), 5) // pad to length 10
  return hex2String(hashids.decodeHex(text))
}

const _initialName = (name) => {
  let convertStr = _.startCase(name)
  return convertStr.split(' ', 2).filter(n => !n.includes('.')).map(n => n.trim().charAt(0)).join('').toUpperCase()
}

if (_settings && _settings.user) {
  _settings.user.can = permission => _settings.user.permissions.includes(permission)
}

export { _, _moment, _http, _route, _alert, _confirm, _toast, _settings, _encrypt, _decrypt, _initialName }

