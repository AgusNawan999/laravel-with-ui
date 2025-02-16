<!-- eslint-disable vue/attribute-hyphenation -->
<script setup>
import { useRouter } from 'vue-router'
import { ref, reactive, onMounted } from 'vue'
import { Field, Form as VeeForm } from 'vee-validate'
import { _http, _route, _confirm, _alert,  _decrypt } from '@/js/utils/common'

// components
import ImageUploader from '@/js/components/ImageUploader.vue'
import RichEditor from '@/js/components/RichEditor.vue'

const defProps = defineProps({
  slug: {
    type: String,
    required: false
  },
})

const form = {}
const router = useRouter()
const IS_ADD = router.currentRoute.value.href.endsWith('add')
const formRef = ref(null)
const hasImage = ref(false)
const file = ref(null)
const showImage = ref(false)

const banner = {
  init: {
    title: null,
    deskripsi: null,
    file_image:null,
  },
  rules: {
    title: 'required|min:3',
    deskripsi: 'required',
    file_image: '',
  }
}

const image_file = reactive({
  media : [],
})


const handleSubmit = (values, { resetForm }) => {
  const method = IS_ADD ? 'post' : 'put'
  const url = `backend.content.banner.${method}`

  Object.keys(values).forEach(key => form[key] = values[key] || null)
  if (!IS_ADD) form['_method'] = 'put'
  const title = `${IS_ADD ? 'Tambah' : 'Ubah'} Banner`
  const text = `Apakah Anda yakin untuk ${IS_ADD ? 'menambahkan' : 'mengubah'} data?`
  _confirm(
    { title, icon: 'question', text },
    () => {
      return _http.post(
        _route(url),
        form,
        {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        }
      )
        .then(result => result)
        .catch(error => {
          let code = error.response.status
          let message = error.response.data.error

          if (code == '422') {
            const errors = error.response.data.errors
            message = Object.keys(errors).map(key => {
              return Array.isArray(errors[key])
                ? errors[key].join(', ')
                : errors[key]
            }).join(', ')

            _alert.showValidationMessage(`${ message }`)
          }
        })
    }
  )
    .then(({ value, isConfirmed, isDismissed }) => {
      // do nothing
      if (isDismissed) return false

      // show response
      if (isConfirmed && value.data.status == 'success')
        _alert.fire({ title: `${ IS_ADD ? 'Tambah' : 'Ubah' } Data Berhasil`, text: value.data.message, 'icon': 'success' }).then(() => {
          // clear form
          resetForm()
        }).then(() => {
          // redirect
          router.push({ name: 'content.banners' })
        })

    })
}

const onHandleCanceled = () => {
  _confirm({
    title: 'Batalkan Tambah Layanan',
    icon: 'question',
    text: 'Apakah Anda yakin untuk membatalkan proses?',
    reverseButtons: false,
    confirmButtonText: '<i class="fad fa-check-circle"></i> Ya, batalkan',
    cancelButtonText: '<i class="fad fa-times-circle"></i> Tidak, lanjutkan',
  })
    .then(({ isDismissed, isConfirmed }) => {
      // do nothing
      if (isDismissed) return

      if (isConfirmed) router.push({ name: 'content.banners' })
    })
}




const cekImageRules = () => {
  if(banner.init.file_image != null){
    banner.rules.file_image = ''
  }else{
    banner.rules.file_image = 'required|ext:jpeg,jpg,png|size:2048'
  }
}


const onProcess = async (result,meta) => {
  meta.touched = true
  const parse = defProps.slug ? JSON.parse(_decrypt(defProps.slug)) : null
  if ('output' in result) {
    banner.rules.file_image = 'ext:jpeg,jpg,png|size:2048'
    let file = new File([result.output], result.output.name,{type : result.output.type});
    file.$path = ""
    formRef.value.setFieldValue('file_image',file)
  } else{

    if( parse != null){
      if('file_image' in parse){
        image_file.media = [parse.file_image]
      }else{
        image_file.media = []
      }
    }else{
      image_file.media = []
    }
  }
}
onMounted(() => {
  cekImageRules()
  if (!IS_ADD) {
      const parse = JSON.parse(_decrypt(defProps.slug))
      formRef.value.setValues({
        slug: parse.slug,
        title: parse.title,
        deskripsi: parse.description,
      })
      if(parse.url_banner) {
        hasImage.value = true
        file.value = parse.url_banner
      }
  }
})

const onHandleShow = () => {
  showImage.value = true
}

const resetModal = () => {
  showImage.value = false
}


</script>

<template>
  <div class="panel">
    <div class="panel-hdr">
      <h2 class="fs-xl">
        <i :class="`fad fa-${IS_ADD ? 'plus-circle' : 'pencil'} mr-2 text-primary`"></i>
        <strong class="fw-600">
          {{ IS_ADD ? 'Tambah' : 'Ubah' }}&nbsp;<span class="fw-300">Banner</span>
        </strong>
      </h2>
    </div>
    <div class="panel-container">
      <div class="panel-content">
        <VeeForm
          ref="formRef"
          keep-values
          @submit="handleSubmit"
        >
          <div class="row">
            <div class="mb-3 col-4">
              <Field
                v-slot="{ field, errorMessage, meta }"
                label="Judul Banner"
                name="title"
                :rules="banner.rules.title"
              >
                <b-form-group
                  label="Judul Banner"
                  label-for="title"
                  label-class="mb-0 fw-700"
                  :state="!meta.touched ? null : errorMessage ? false : true"
                  :invalid-feedback="!meta.touched ? null : errorMessage"
                >
                  <b-form-input
                    id="kode"
                    v-bind="field"
                    placeholder="Masukkan Judul Banner"
                    :state="!meta.touched ? null : errorMessage ? false : true"
                  />
                </b-form-group>
              </Field>
            </div>
          </div>
          <div v-if="!hasImage" class="row">
            <div class="mb-3 col-12">
              <Field
                v-slot="{ field, errorMessage, meta }"
                label="File Image Banner"
                name="file_image"
                :rules="banner.rules.file_image"
              >
                <b-form-group
                  label="File Image Banner"
                  label-for="file_image"
                  label-class="mb-0 fw-700"
                  :state="!meta.touched ? null : errorMessage ? false : true"
                  :invalid-feedback="!meta.touched ? null : errorMessage"
                >
                  <ImageUploader
                    v-bind="field"
                    id-input="file_image"
                    accept-input=".jpg,.jpeg,.png"
                    :media-lightbox="image_file.media"
                    :state-input="!meta.touched ? null : errorMessage ? false : true"
                    placeholder-input="Pilih image Banner atau letakan disini..."
                    drop-placeholder-input="Letakan image Banner disini..."
                    size="md"
                    quality="med"
                    @change="(e) => { e.target.files.length > 0 ? field = null : field}"
                    @process="(result) => { onProcess(result,meta) }"
                  >
                  </ImageUploader>
                  <div>
                    <span
                      style="font-size: 10px;font-style: italic;color: #666;"
                    >
                      {{
                        '(Format file jpeg/jpg/png, ukuran maksimal 2 Mb)'
                      }}
                    </span>
                  </div>
                </b-form-group>
              </Field>
            </div>
          </div>
          <div v-else class="row">
            <div class="mb-3 col-12">
              <i class="fad fa-file-pdf"></i>
              <b-link @click="onHandleShow">
                <span class="ml-2">Banner</span>
              </b-link>
              <b-link @click="hasImage = false"><i class="ml-2 fad fa-pencil"></i></b-link>
            </div>
          </div>
          <div class="row">
            <div class="mb-3 col-12">
              <Field
                v-slot="{ field, errorMessage, meta }"
                label="Deskripsi Banner"
                name="deskripsi"
                :rules="banner.rules.deskripsi"
              >
                <b-form-group
                  label="Deskripsi Banner"
                  label-for="deskripsi"
                  label-class="mb-0 fw-700"
                  :state="!meta.touched ? null : errorMessage ? false : true"
                  :invalid-feedback="!meta.touched ? null : errorMessage"
                >
                  <RichEditor
                    id="deskripsi"
                    :value-editor="field.value"
                    v-bind="field"
                    placeholder-editor="Masukkan deskripsi Banner"
                    :state-editor="!meta.touched ? null : errorMessage ? false : true"
                  ></RichEditor>
                </b-form-group>
              </Field>
            </div>
          </div>
          <div class="row">
            <div class="mt-4 col-12 d-flex justify-content-end align-items-center">
              <b-button variant="default" class="mr-2" @click.prevent="onHandleCanceled">
                <i class="fad fa-times-circle"></i>
                Batalkan
              </b-button>
              <b-button variant="primary" type="submit">
                <i class="fad fa-check-circle"></i>
                {{ IS_ADD ? 'Tambah' : 'Ubah' }}
              </b-button>
            </div>
          </div>
        </VeeForm>
        <b-modal
          id="modal-prevent-closing"
          ref="modal"
          v-model:visible="showImage"
          title="Banner"
          hide-footer
          @hidden="resetModal"
        >
          <b-img :src="file" fluid-grow alt="Fluid-grow image"></b-img>
        </b-modal>
      </div>
    </div>
  </div>
</template>
