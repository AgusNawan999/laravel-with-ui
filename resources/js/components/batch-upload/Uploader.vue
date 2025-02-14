<script setup>
import { ref, computed } from 'vue'
import { _alert } from '@/js/utils/common.js'

const props = defineProps({
  accept: {
    type: Array,
    required: false,
    default: () => [
      'application/pdf',
      'image/gif',
      'image/jpg',
      'image/jpeg',
      'image/png',
      'image/tiff',
      'image/webp'
    ],
  },
  maxSize: {
    type: Number,
    required: false,
    default: 5
  },
})

const emits = defineEmits(['after-upload', 'remove-upload'])
const componentId = Math.random().toString(36).substring(2, 9)
const URL = window.URL || window.webkitURL
const localAccept = computed(() => props.accept.join(', '))
const fileUpload = ref({
  has: false,
  src: null,
  file: null,
  type: null
})

const onHandleChange = (e) => {
  if (!e.target.files.length) return

  try {
    const file = e.target.files[0]
    const filesize = file.size

    // validate type of file
    if (!props.accept.includes(file.type)) {
      throw (`Tipe dokumen yang dipilih tidak sesuai. Format yang disarankan: ${localAccept.value}`)
    }

    // validate size of file
    const toMib = filesize / 1024 /1024
    if (toMib > props.maxSize) {
      throw (`Ukuran dokumen melebihi batas. Ukuran yang disarankan maksimal: ${props.maxSize} Mb`)
    }

    fileUpload.value.file = file
    fileUpload.value.has = true
    fileUpload.value.type = file.type

    if (file.type.includes('image')) {
      fileUpload.value.src = URL.createObjectURL(file)
    }

    emits('after-upload', { file, id: componentId, reset: resetFileUpload })
  } catch (error) {
    _alert.fire({
      title: 'Dokumen bermasalah',
      text: error,
      icon: 'error'
    })
    fileUpload.value.has = false
  }
}

const onHandleDrop = () => emits('remove-upload', componentId)
const resetFileUpload = () => {
  fileUpload.value.has = false
  fileUpload.value.src = null
  fileUpload.value.file = null
  fileUpload.value.type = null
}
</script>

<template>
  <div class="part-of-batch-upload">
    <label
      :for="!fileUpload.has ? `${componentId}-input-file` : ''"
      :class="{ 'cursor-pointer': !fileUpload.has }"
    >
      <i v-if="!fileUpload.has" class="need-transform fad fa-cloud-upload light-gray m-0 p-0"></i>
      <template v-else>
        <img
          v-if="fileUpload.type.includes('image')"
          v-tippy="{ content: fileUpload.file.name }"
          :src="fileUpload.src"
        >
        <i
          v-else
          v-tippy="{ content: fileUpload.file.name }"
          class="fad fa-file-pdf fa-2x light-gray"
        ></i>
      </template>
    </label>
    <i v-if="fileUpload.has" class="action fad fa-times-circle text-danger" @click="onHandleDrop"></i>
    <input
      :id="`${componentId}-input-file`"
      type="file"
      :accept="localAccept"
      @change="onHandleChange"
    />
  </div>
</template>

<style lang="scss">
.part-of-batch-upload {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 64px;
  height: 64px;
  border: 1px dashed #bababa;
  border-radius: .5rem;

  &:hover {
    border-color: var(--theme-primary-300);
  }

  label {
    position: relative;
    margin: 0 auto;
    padding: 0;

    img {
      width: 64px;
      height: 64px;
      border: 1px solid #eaeaea;
      border-radius: .5rem;
      object-position: center;
      object-fit: cover;
    }

    i {
      &.need-transform {
        width: 100%;
        height: 100%;
        transform: scale(1.2) translateY(5%);
      }
    }
  }

  i {
    &.action {
        top: 0;
        right: 0;
        opacity: 1;
        cursor: pointer;
        position: absolute;
        transform: scale(0.85) translateY(-5px) translateX(8px);
      }
  }

  input {
    &[type=file] {
      display: none;
    }
  }
}

.light-gray {
  color: #cacaca;

  &:hover {
    color: var(--theme-primary-300);
  }
}
</style>
