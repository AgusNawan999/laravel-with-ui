<script setup>
import { ref } from 'vue'
import { _decrypt } from '@/js/utils/common';
const props = defineProps({
  text: {
    type: String,
    required: true,
  },
  isPassword: {
    type: Boolean,
    required: false,
    default: false
  }
})
const componentId = Math.random().toString(36).substring(2, 9)
const copyRef = { [componentId]: ref(null) }
const seekPass = ref(false)
const handleCopyTicket = () => {
  copyRef[componentId].value.focus()
  document.execCommand('copy')
}
</script>
<template>
  <span v-if="props.isPassword">
    <template v-if="seekPass">
      {{ _decrypt(props.text) }}
    </template>
    <template v-else>
      {{ ''.padEnd(props.text.length, '●') }}
    </template>
  </span>
  <span v-else>
    {{ props.text }}
  </span>
  <input
    :ref="copyRef[componentId]"
    :value="props.isPassword && seekPass ? _decrypt(props.text) : props.text"
    :type="props.isPassword && !seekPass ? 'password' : 'text'"
    class="border-0 p-0 m-0 text-muted copy-input"
    readonly
    @focus="$event.target.select()"
  />
  <i
    v-if="props.isPassword"
    :class="{
      'fad text-muted ml-2 cursor-pointer': true,
      'fa-eye': !seekPass,
      'fa-eye-slash': seekPass
    }"
    @click="seekPass = !seekPass"
  ></i>
  <i v-if="!isPassword || seekPass" class="fad fa-copy text-muted ml-2 cursor-pointer" @click="handleCopyTicket"></i>
</template>

<style lang="scss">
.copy-input {
  width: 1px;
  opacity: 0;
}
</style>
