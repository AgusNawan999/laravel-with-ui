<script setup>
import { ref, shallowRef } from 'vue'
import Uploader from './Uploader.vue'
import FilePdf from '@/images/file-pdf.png'
import FileImage from '@/images/file-image.png'

const props = defineProps({
  label: {
    type: String,
    required: true
  },
  files: {
    type: Array,
    required: true,
  },
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
  uploadedFiles: {
    type: [Array, null],
    required: false,
    default: () => []
  }
})
const emits = defineEmits(['update:files', 'update:uploadedFiles', 'on-removed'])

let batching = shallowRef([Uploader])
let hasUploads = ref([])
const thumbnail = ref({
  pdf: FilePdf,
  image: FileImage,
})

const onHandleAdd = ({ file, id, reset }) => {
  if (hasUploads.value.length) {
    // search for identical files
    const hasIdx = hasUploads.value.findIndex(u => u.file.name == file.name && u.file.size == file.size && u.file.type == file.type)
    if (hasIdx != -1) {
      // data sudah ada
      reset()

      // quick return
      return
    }
  }

  hasUploads.value.push({ id, file })
  addMoreUploader()
  doUpdateFiles()
}

const addMoreUploader = () => batching.value = [...batching.value, Uploader]
const doUpdateFiles = () => {
  const validFileUpload = hasUploads.value.map(u => u.file)
  emits('update:files', validFileUpload)
}

const onHandleRemove = (idx, id) => {
  let current = [...batching.value]
  delete current[idx]

  const findIndex = hasUploads.value.findIndex(u => u.id == id)
  if (findIndex != -1) hasUploads.value.splice(findIndex, 1)

  batching.value = [...current]
  doUpdateFiles()
}

const onHandleRemoveUploaded = (idx, file) => {
  emits('on-removed', { file, idx })
}
</script>

<template>
  <div class="batch-upload-wrapper">
    <label>{{ props.label }}</label>
    <div class="upload-wrapper d-flex flex-wrap">
      <transition-group name="fade" mode="out-in">
        <template v-for="(file, idx) in props.uploadedFiles" :key="idx">
          <div class="position-relative">
            <a
              :id="`uploaded_${idx}`"
              v-tippy="{ content: file.filename }"
              :href="file.url"
              :title="file.filename"
              target="_blank"
            >
              <img
                :src="file.mime_type.includes('image') ? thumbnail.image : thumbnail.pdf"
                class="thumbnail"
              />
            </a>
            <i
              class="fad fa-times-circle text-danger drop-uploaded"
              @click="onHandleRemoveUploaded(idx, file)"
            ></i>
          </div>
        </template>
        <template v-for="(batch, idx) in batching" :key="idx">
          <component
            :is="batch"
            :id="idx"
            :max-size="props.maxSize"
            @after-upload="onHandleAdd"
            @remove-upload="(id) => onHandleRemove(idx, id)"
          ></component>
        </template>
      </transition-group>
    </div>
  </div>
</template>

<style lang="scss">
.batch-upload-wrapper {
  position: relative;
  border-bottom: 1px solid #ced4da;

  & > label {
    opacity: .65;
    transform: scale(.85) translateY(-0.675rem) translateX(-2rem);
  }

  @media screen and (max-width: 400px) {
    & > label {
      opacity: .65;
      transform: scale(.85) translateY(-0.675rem) translateX(-1.5rem);
    }
  }

  .upload-wrapper {
    gap: .825rem;
    transform: translateY(-0.825rem);

    i {
      &.drop-uploaded {
        position: absolute;
        top: -4px;
        right: -4px;
        cursor: pointer;
      }
    }

    .thumbnail {
      width: 64px;
      height: 64px;
    }
  }
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease-out;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
