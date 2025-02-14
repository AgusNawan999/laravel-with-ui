<script setup>
import { ref } from 'vue'

const props = defineProps({
  modelValue: {
    type: [File, String, null],
    required: true,
    default: null
  },
  class: {
    type: [String, Object],
    required: false,
    default: 'form-control'
  },
  placeholder: {
    type: String,
    required: false,
    default: 'Pilih dokumen'
  },
  useMaterial: {
    type: Boolean,
    required: false,
    default: false
  },
  accept: {
    type: String,
    required: false,
    default: 'application/pdf'
  },
  hideIcon: {
    type: Boolean,
    required: false,
    default: false,
  },
})

const emits = defineEmits(['update:modelValue', 'change'])

const componentId = Math.random().toString(36).substring(2, 9)
const files = ref({
  name: '',
  selected: null,
})

const onChangeFile = (e) => {
  if (!e.target.files.length) return

  const file = e.target.files[0]
  files.value.name = file.name
  files.value.selected = file

  emits('update:modelValue', file)
  emits('change', file, { resetFiles })
}

const resetFiles = () => {
  files.value.name = ''
  files.value.selected = null
}
</script>

<template>
  <div
    :key="componentId"
    :class="[
      {
        'input-file-wrapper form-control': true,
        'material': props.useMaterial,
      },
      props.class
    ]"
  >
    <label :for="componentId" class="label-wrapper cursor-pointer w-100">
      <div v-if="!props.hideIcon" class="icon-wrapper">
        <i class="fad fa-upload ml-auto fa-sm"></i>
      </div>

      <div v-if="files.selected" class="filename-wrapper text-dark">
        {{ files.name }}
      </div>
      <div v-else class="placeholder-wrapper">{{ props.placeholder }}</div>
    </label>
    <input
      :id="componentId"
      :accept="props.accept"
      type="file"
      class="d-none"
      @change="onChangeFile"
    >
  </div>
</template>

<style lang="scss">
.input-file-wrapper {
  label {
    &.label-wrapper {
      position: relative;

      .icon-wrapper {
        position: absolute;
        top: 0;
        right: .625rem;
      }
    }
    .placeholder-wrapper {
      color: #6c757d;
    }

    .filename-wrapper {
      width: 90%;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
    }
  }
}
</style>
