<script setup>
import { onMounted } from 'vue'
import { _settings } from '@/js/utils/common.js'

onMounted(() => {
  // prevent if websocket disabled
  if (!_settings?.wbsckt?.enabled) return

  // // broadcast-message via channel only
  // window.Echo.channel(`broadcast-message`).listen('.broadcast-message', (e) => console.log({ e }))

  // prevent if user not login
  if (!_settings.user) return

  // // broadcast-message via presence channel
  // window.Echo.join(`join.${_settings.user.id}`)
  //   .here((users) => console.log({ users }))
  //   .joining((user) => console.log(user.name))
  //   .leaving((user) => console.log(user.name))
  //   .error((error) => console.error(error))
  //   .listen('.with', (e) => console.log({ e }))

  // // broadcast-message via private channel
  // window.Echo.private(`message.${_settings.user.id}`).listen(`.from`, (e) => console.log({ e }))

  // listen new message via private channel
  _settings.user.roles.map(role =>
      window
        .Echo
        .private(`new-request.${role}`)
        .listen('.complaint', (e) => console.log({ e }))
  )

  // for listen:
  // confirmation
  // complaint
  // submission
})
</script>

<template>
  <div class="router-view-wrapper">
    <router-view></router-view>
  </div>
</template>
