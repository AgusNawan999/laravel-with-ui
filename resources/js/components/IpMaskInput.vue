<script setup>
import { ref, toRef, watchEffect, onMounted } from 'vue'

// https://robinherbots.github.io/Inputmask/#/documentation
import '@theme/js/formplugins/inputmask/inputmask.bundle.js'

const props = defineProps({
  modelValue: {
    type: [String, null],
    required: true,
    default: null
  },
  class: {
    type: [String, Object],
    required: false,
    default: ''
  },
  setFocus: {
    type: Boolean,
    required: false,
    default: false
  },
  readonly: {
    type: Boolean,
    required: false,
    default: false
  }
})
const emits = defineEmits(['update:modelValue', 'valid', 'invalid'])

const componentId = Math.random().toString(36).substring(2, 9)
const localClass = toRef(props, 'class')
const localModelValue = toRef(props, 'modelValue')

const component = ref({ [componentId]: null })
const hasComplete = ref(false)

const onHandleCompleteOrIncomplete = (isComplete) => {
  const value = component.value[componentId].value
  hasComplete.value = isComplete
  emits('update:modelValue', value)
  emits(isComplete ? 'valid' : 'invalid')
}

const _im = new window.Inputmask({
  alias: 'ip',
  greedy: false,
  clearIncomplete: true,
  showMaskOnHover: false,
  oncomplete: () => onHandleCompleteOrIncomplete(true),
  onincomplete: () => onHandleCompleteOrIncomplete(false),
})

const setValue = (value, silent = false) => {
  if (!component.value[componentId]) return

  component.value[componentId].inputmask.setValue(value)

  if (silent) return

  const valid = component.value[componentId].inputmask.isValid()
  onHandleCompleteOrIncomplete(valid)
}

const getValue = () => component.value[componentId]?.value

const unwatch = watchEffect(
  () => {
    if (component.value[componentId]) {
      _im.mask(component.value[componentId])
      unwatch()
    }
  }
)

onMounted(() => {
  setTimeout(() => {
    if (props.setFocus)
      component?.value[componentId]?.focus()
  }, 250)
})

defineExpose({ setValue, getValue })
</script>

<template>
  <input
    :id="componentId"
    :ref="el => component[componentId] = el"
    :value="localModelValue"
    type="text"
    :class="['form-control', localClass]"
    :readonly="props.readonly"
  >
</template>
