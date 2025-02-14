<script setup>
import $ from 'jquery'
import { vMaska } from 'maska'
import { _ } from '@/js/utils/common'
import { onMounted, ref } from 'vue'
import '@theme/js/formplugins/jquery-clockpicker/jquery-clockpicker.js'

const emits = defineEmits(['after-done', 'update:modelValue'])
const props = defineProps({
  modelValue: {
    type: String
  },
  options: {
    type: Object,
    required: false,
    default: () => {}
  },
  class: {
    type: [String, Object],
    required: false,
    default: ''
  },
  placeholder: {
    type: String,
    required: false,
    default: ''
  },
  hideIcon: {
    type: Boolean,
    required: false,
    default: false
  },
  useMaterial: {
    type: Boolean,
    required: false,
    default: false
  }
})

const componentId = Math.random().toString(36).substring(2, 9)
const el = {
  id: `clockpicker-${componentId}`,
  ref: ref(null),
}

onMounted(() => {
  const options = {
    placement: 'bottom',
    align: 'right',
    autoclose: true,
    afterDone: onHandleAfterDone
  }

  const defaultOptions = Object.assign(options, props.options)
  $(el.ref.value).clockpicker(defaultOptions)
})

const doEmit = (value = '') => {
  emits('update:modelValue', value)
  emits('after-done', { value, el: el.ref.value, id: el.id })
}

const onHandleAfterDone = () => doEmit(el.ref.value.value)

const onHandleInput = _.debounce(function (value) {
  const maskValue = value.detail.masked
  const isValid = /^(?:[01][0-9]|2[0-3]):[0-5][0-9](?::[0-5][0-9])?$/.test(maskValue)

  if (!isValid && maskValue.length === 5) {
    doEmit()
    return
  }

  doEmit(maskValue)
  if (maskValue.length === 5)
    $(el.ref.value).clockpicker('hide')
}, 500)
</script>

<template>
  <div
    :key="componentId"
    class="clock-picker-wrapper"
  >
    <input
      :id="el.id"
      :ref="el.ref"
      v-maska
      :value="props.modelValue"
      :class="['form-control', { 'has-icon': !props.hideIcon }, props.class, { 'material outline-none': props.useMaterial }]"
      :placeholder="props.placeholder"
      data-maska="##:##"
      maxlength="5"
      @maska="onHandleInput"
    />
    <div v-if="!hideIcon" class="clock-icon">
      <i class="fad fa-clock"></i>
    </div>
  </div>
</template>

<style lang="scss">
.popover {
  &.clockpicker-popover {
    font-family: -apple-system, linkMacSystemFont,'Nunito',sans-serif !important;
    border: none !important;
    z-index: 1040 !important;
  }
}

.clock-picker-wrapper {
  input {
    &.form-control {
      padding-left: 0.875rem;

      &.has-icon {
        padding-right: 28px;
      }
    }
  }

  position: relative;

  .clock-icon {
    position: absolute;
    top: 10px;
    right: 12px
  }

}
</style>
