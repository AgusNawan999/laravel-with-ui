import Pusher from 'pusher-js'
import Echo from 'laravel-echo'
import { _settings } from '@/js/utils/common.js'

if (_settings.wbsckt.enabled) {
  const wsConfig = {
    broadcaster: 'reverb',
    key: _settings.wbsckt.public_key,
    wsHost: _settings.wbsckt.wsHost,
    wsPort: _settings.wbsckt.wsPort ?? 80,
    wssPort: _settings.wbsckt.wssPort ?? 443,
    forceTLS: (_settings.wbsckt.scheme ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
    disableStats: true,
  }

  Pusher.logToConsole = _settings.wbsckt.pusher_log
  window.Pusher = Pusher
  window.Echo = new Echo(wsConfig)
}
