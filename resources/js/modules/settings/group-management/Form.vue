<script setup>
import { useRouter } from 'vue-router'
import Spinner from '@/js/components/Spinner.vue'
import { Field, Form as VeeForm } from 'vee-validate'
import { ref, reactive, onMounted, nextTick } from 'vue'
import { _, _http, _route, _confirm, _alert, _decrypt } from '@/js/utils/common'

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
const tree = reactive({
  nodes: [],
  selected: null,
  isReady: false
})

const setting = {
  init: {
    kode: null,
    nama: null,
    features: null,
  },
  rules: {
    kode: 'required|min:3|max:10|alpha_dash',
    nama: 'required|min:3|max:50|alpha_spaces',
    features: 'required',
  }
}

const handleSubmit = (values, { resetForm }) => {
  const method = IS_ADD ? 'post' : 'put'
  const url = `backend.setting.group.${method}`

  Object.keys(values).forEach(key => form[key] = values[key] || null)
  if (!IS_ADD) form['_method'] = 'put'

  const title = `${IS_ADD ? 'Tambah' : 'Ubah'} Group`
  const text = `Apakah Anda yakin untuk ${IS_ADD ? 'menambahkan' : 'mengubah'} data?`

  _confirm(
    { title, icon: 'question', text },
    () => {
      return _http[method](_route(url), { ...form })
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
        _alert.fire({ title: `${ IS_ADD ? 'Tambah' : 'Ubah' } Data Berhasil`, text: value.data.message, 'icon': 'success' })

        // clear form
        resetForm()

        // redirect
        router.push({ name: 'settings.groups' })
    })
}

const onHandleCanceled = () => {
  _confirm({
    title: 'Batalkan Tambah Group',
    icon: 'question',
    text: 'Apakah Anda yakin untuk membatalkan proses?',
    reverseButtons: false,
    confirmButtonText: '<i class="fad fa-check-circle"></i> Ya, batalkan',
    cancelButtonText: '<i class="fad fa-times-circle"></i> Tidak, lanjutkan',
  })
    .then(({ isDismissed, isConfirmed }) => {
      // do nothing
      if (isDismissed) return

      if (isConfirmed) router.push({ name: 'settings.groups' })
    })
}

const onUpdate = (node) => {
  const flatten = item => [item, _.flatMapDeep(item.nodes, flatten)]
  const flattenNode = _.flatMapDeep(node, flatten)

  Promise.resolve(flattenNode.filter(n => n.checked || n.indeterminate).map(n => n.id))
    .then(res => {
      if (!res.length) {
        const meta = formRef.value.getMeta()

        tree.selected = null
        formRef.value.setFieldValue('features', null)

        if (meta.touched || meta.dirty)
          formRef.value.setFieldTouched('features', true)

        return false
      }

      tree.selected = [...res]
      formRef.value.setFieldValue('features', tree.selected)
      formRef.value.setFieldTouched('features')
    })
}

onMounted(async () => {
  const parse = defProps.slug ? JSON.parse(_decrypt(defProps.slug)) : null

  Promise.resolve(parse)
    .then(res => {
      if (res) {
        form['slug'] = res.slug
        formRef.value.setValues({
          kode: res.kode,
          nama: res.nama,
        })
        return _http.get(_route('backend.setting.group.features', { 'slug': res.slug }))
      } else {
        return _http.get(_route('backend.setting.features.hierarcy'))
      }
    })
    .then(res => {
      if (res.data.status != 'success') throw (res.data.message)
      tree.nodes = res.data.data
    })
    .then(() => nextTick(() => tree.isReady = true))
    .catch(error => console.log({ error }))

  nextTick(() => {})
  // console.log(hierarcy)
  // hierarcy
})

</script>
<template>
  <div class="panel">
    <div class="panel-hdr">
      <h2 class="fs-xl">
        <i :class="`fad fa-${IS_ADD ? 'plus-circle' : 'pencil'} mr-2 text-primary`"></i>
        <strong class="fw-600">
          {{ IS_ADD ? 'Tambah' : 'Ubah' }}&nbsp;<span class="fw-300">Group</span>
        </strong>
      </h2>
    </div>
    <div class="panel-container">
      <div class="panel-content">
        <VeeForm ref="formRef" @submit="handleSubmit">
          <div class="row">
            <div class="col-12 col-md-4 mb-3">
              <Field
                v-slot="{ field, errorMessage, meta }"
                label="Kode Group"
                name="kode"
                :rules="setting.rules.kode"
              >
                <b-form-group
                  label="Kode Group"
                  label-for="kode"
                  label-class="fw-700 mb-0"
                  :state="!meta.touched ? null : errorMessage ? false : true"
                  :invalid-feedback="!meta.touched ? null : errorMessage"
                >
                  <b-form-input
                    id="kode"
                    v-bind="field"
                    placeholder="Masukkan kode group"
                    :state="!meta.touched ? null : errorMessage ? false : true"
                  />
                </b-form-group>
              </Field>
            </div>
            <div class="col-12 col-md-8 mb-3">
              <Field
                v-slot="{ field, errorMessage, meta }"
                label="Nama Group"
                name="nama"
                :rules="setting.rules.nama"
              >
                <b-form-group
                  label="Nama Group"
                  label-for="nama"
                  label-class="fw-700 mb-0"
                  :state="!meta.touched ? null : errorMessage ? false : true"
                  :invalid-feedback="!meta.touched ? null : errorMessage"
                >
                  <b-form-input
                    id="nama"
                    v-bind="field"
                    placeholder="Masukkan nama"
                    :state="!meta.touched ? null : errorMessage ? false : true"
                  />
                </b-form-group>
              </Field>
            </div>

            <div class="col-12 mb-3">
              <Field
                v-slot="{ errorMessage, meta }"
                label="Fitur Akses"
                name="features"
                :rules="setting.rules.features"
              >
                <b-form-group
                  label="Fitur Akses"
                  label-for="features"
                  label-class="fw-700 mb-0"
                  :class="{ 'is-invalid': errorMessage && errorMessage.features }"
                  :state="!meta.touched ? null : errorMessage ? false : true"
                  :invalid-feedback="!meta.touched ? null : errorMessage"
                >
                  <VueTree
                    v-if="tree.isReady"
                    v-model:nodes="tree.nodes"
                    :use-checkbox="true"
                    :show-child-count="true"
                    :use-icon="true"
                    :gap="0"
                    @update:nodes="onUpdate"
                  >
                    <template #iconActive>
                      <i class="fas fa-caret-right"></i>
                    </template>

                    <template #iconInactive>
                      <i class="fas fa-caret-down"></i>
                    </template>

                    <template #childCount="{ count, checkedCount }">
                      <span class="child-count">
                        dipilih: {{ checkedCount }} / total: {{ count }}
                      </span>
                    </template>
                  </VueTree>
                  <Spinner v-else loading-text="Sedang memuat fitur..." />
                </b-form-group>
              </Field>
            </div>
          </div>
          <div class="row">
            <div class="col-12 d-flex justify-content-end align-items-center">
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
      </div>
    </div>
  </div>
</template>
