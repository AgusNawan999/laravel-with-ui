<script setup>
import $ from 'jquery'
import { _settings } from '@/js/utils/common'
import { onMounted, ref, watch } from 'vue'

import '@theme/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js'
import '@theme/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.id.min.js'

const emits = defineEmits(['update:modelValue', 'on-changed'])
const props = defineProps({
  modelValue: {
    type: [String, Object, Date],
    required: true,
    default: ''
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
  disabledDayBefore: {
    type: Boolean,
    required: false,
    default: false,
  },
  disabledDaySelected: {
    type: Boolean,
    required: false,
    default: false,
  },
  offset: {
    type: Number,
    required: false,
    default: 40
  },
  selectedDate: {
    type: String,
    required: false,
    default: ''
  },
  useMaterial: {
    type: Boolean,
    required: false,
    default: false
  },
  fontSize: {
    type: String,
    required: false,
    default: '1rem'
  }
})

const componentId = Math.random().toString(36).substring(2, 9)
const el = {
  id: `datepicker-${componentId}`,
  ref: ref(null),
  instance: ref(null),
  settings: {
    autoclose: true,
    format: 'd MM yyyy',
    language: _settings.locale,
    locale: _settings.locale,
    todayHighlight: false,
    orientation: 'auto',
    templates: {
      leftArrow: '<i class="fal fa-angle-left" style="font-size: 1.25rem"></i>',
      rightArrow: '<i class="fal fa-angle-right" style="font-size: 1.25rem"></i>'
    },
  }
}

const onHandleChangeDate = (obj) => {
  const hide = () => $(el.instance.value).datepicker('hide')

  $(el.instance.value).datepicker('update', obj.date)
  emits('update:modelValue', obj.format())
  emits('on-changed', { value: obj.format(), date: obj.date, el: obj.currentTarget, hide })
}

const onHandleShow = (obj) => {
  const idx = Object.keys(obj.currentTarget)[1]
  if (!idx) return

  const pos = obj.currentTarget[idx].datepicker.picker[0].offsetTop
  if (!pos) return

  $(obj.currentTarget[idx].datepicker.picker[0]).css('top', (pos + props.offset))
}

onMounted(() => {
  if (el.instance.value) {
    el.instance.value.datepicker('destroy')
  }

  const container = $('.datepicker-wrapper')
  const defaultOptions = Object.assign({}, el.settings, props.options, container)

  setTimeout(() => {
    el.instance.value = $(el.ref.value)
      .datepicker(defaultOptions)
      .on('changeDate', onHandleChangeDate)
      .on('show', onHandleShow)

    if (props.disabledDayBefore) {
      let now = new Date(_settings.current_fulldate)
      let tomorrow = new Date(_settings.current_fulldate)
      tomorrow.setDate(now.getDate())
      $(el.instance.value).datepicker('setStartDate', tomorrow)

      const options = { year: 'numeric', month: 'long', day: 'numeric' }
      emits('update:modelValue', tomorrow.toLocaleString('id-ID', options))
      emits('on-changed', { value: tomorrow.toLocaleString('id-ID', options), date: tomorrow, el: el.ref.value, hide: null })
    }
    if (props.disabledDaySelected) {
      let now = new Date(props.modelValue)
      let tomorrow = new Date(_settings.current_fulldate)
      tomorrow.setDate(now.getDate())
      $(el.instance.value).datepicker('setStartDate', tomorrow)

      const options = { year: 'numeric', month: 'long', day: 'numeric' }
      emits('update:modelValue', tomorrow.toLocaleString('id-ID', options))
      emits('on-changed', { value: tomorrow.toLocaleString('id-ID', options), date: tomorrow, el: el.ref.value, hide: null })
    }

    if (props.modelValue.length)
      $(el.instance.value).datepicker('update', props.modelValue)
  }, 250)
})

watch(
  () => props.modelValue,
  () => {
    if (typeof props.modelValue == 'object') {
      const options = { year: 'numeric', month: 'long', day: 'numeric' }
      const event = new Date(props.modelValue)

      $(el.instance.value).datepicker('update', event)

      emits('update:modelValue', event.toLocaleString('id-ID', options))
      emits('on-changed', { value: event.toLocaleString('id-ID', options), date: props.modelValue, el: el.ref.value, hide: null })
    }
  },
)
</script>

<template :style="{ '--dtpFontSize': props.fontSize }">
  <div
    :key="componentId"
    class="datepicker-wrapper"
    :style="{ '--dtpFontSize': props.fontSize }"
  >
    <input
      :id="el.id"
      :ref="el.ref"
      :value="props.modelValue"
      :class="['form-control', { 'has-icon': !props.hideIcon }, { 'material outline-none': props.useMaterial }, props.class]"
      :placeholder="props.placeholder"
      readonly
    />
    <div v-if="!hideIcon" class="calendar-icon">
      <i class="fad fa-calendar-alt"></i>
    </div>
  </div>
</template>
<style lang="scss">
.datepicker-wrapper {
  position: relative;

  input {
    &.form-control {
      font-size: var(--dtpFontSize);
      padding-left: 0.875rem;

      &.has-icon {
        padding-right: 28px;
      }
    }

    &.form-control[readonly] {
      background-color: white;
    }
  }

  .calendar-icon {
    position: absolute;
    top: 10px;
    right: 12px
  }

}
</style>
