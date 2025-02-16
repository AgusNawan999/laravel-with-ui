<script setup>
import { useRouter } from 'vue-router'
import duotone from '@/data/fa-duotone.json'
import { toTitle } from '@/js/utils/convert-case'
import { Field, Form as VeeForm } from 'vee-validate'
import { ref, reactive, onMounted, nextTick } from 'vue'
import { _, _confirm, _http, _route, _alert, _decrypt } from '@/js/utils/common'

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
const selected = reactive({
  induk: null,
  type: null,
  icon: null,
  isMenu: false
})

const options = reactive({
  type: ['Menu', 'Services', 'CRUD', 'Filter'],
  induk: [],
  icon: [...duotone]
})

const setting = {
  init: {
    nama: null,
    alias: null,
    induk: null,
    tipe: 'Menu',
    route: null,
    icon: null,
    order: 0,
    keterangan: null
  },
  rules: {
    nama: 'required|min:3|max:50|alpha_spaces',
    alias: 'required|min:3|max:100|alpha_dash',
    tipe: 'required',
    route: 'max:255',
    order: 'numeric',
    keterangan: 'max:255'
  }
}

const clearSelected = () => {
  selected.induk = null
  selected.type = null
  selected.icon = null
  selected.isMenu = false
}

const handleSubmit = (values, { resetForm }) => {
  const method = IS_ADD ? 'post' : 'put'
  const url = `backend.setting.feature.${ method }`

  Object.keys(values).forEach(key => form[key] = values[key] || null)
  if (!IS_ADD) form['_method'] = 'put'

  const title = `${IS_ADD ? 'Tambah' : 'Ubah'} Akses Fitur`
  const text = `Apakah Anda yakin untuk ${ IS_ADD ? 'menambahkan' : 'mengubah' } data?`

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
        clearSelected()

        // redirect
        router.push({ name: 'settings.features' })
    })
}

const fetchParents = (searchText = null ) => {
  return Promise.resolve(
    _http.get(_route('backend.setting.features.get', {
      search: searchText,
      columns: 'nama_fitur',
      limit: 100
    }))
  )
}

const getParents = _.debounce(function(searchText = null) {
  fetchParents(searchText)
    .then(res => {
      options.induk = res.data.data.map(feat => {
        return {
          slug: feat.slug,
          nama_fitur: feat.nama_fitur
        }
      })

      return options.induk
    })
}, 250)

const onHandleSelected = (selectedOption, id) => {
  let selectedValue = selectedOption

  if (id == 'tipe') selected.isMenu = ['menu', 'services'].includes(selectedOption.toLowerCase())
  if (id == 'induk') selectedValue = selectedOption.slug

  formRef.value.setFieldValue(id, selectedValue)
  formRef.value.setFieldTouched(id)
}

const onHandleRemoved = (e, id) => {
  formRef.value.setValues(id, '')

  let error = formRef.value.getErrors()
  formRef.value.setFieldError(id, error)
}

const onHandleCanceled = () => {
  _confirm({
    title: 'Batalkan Tambah Fitur Akses',
    icon: 'question',
    text: 'Apakah Anda yakin untuk membatalkan proses?',
    reverseButtons: false,
    confirmButtonText: '<i class="fad fa-check-circle"></i> Ya, batalkan',
    cancelButtonText: '<i class="fad fa-times-circle"></i> Tidak, lanjutkan',
  })
    .then(({ isDismissed, isConfirmed }) => {
      // do nothing
      if (isDismissed) return

      if (isConfirmed) router.push({ name: 'settings.features' })
    })
}

onMounted(() => {
  if (IS_ADD) {
    getParents()
  } else {
    const parse = JSON.parse(_decrypt(defProps.slug))
    Promise.resolve(fetchParents())
      .then((res) => {
        options.induk = res.data.data.map(feat => {
          return {
            slug: feat.slug,
            nama_fitur: feat.nama_fitur
          }
        })

        return options.induk
      })
      .then(parents => parents.find(key => key.nama_fitur == parse.nama_induk))
      .then(selectedInduk => {
        if (selectedInduk) {
          formRef.value.setFieldValue('induk', selectedInduk.slug)
          selected.induk = selectedInduk
        }
      })

    nextTick(() => {
      formRef.value.setValues({
        nama: parse.nama_fitur,
        alias: parse.alias,
        tipe: parse.tipe_fitur,
        route: parse.route,
        icon: parse.icon,
        order: parse.order,
        keterangan: parse.deskripsi,
      })

      selected.type = parse.tipe_fitur
      selected.isMenu = ['menu', 'services'].includes(parse.tipe_fitur.toLowerCase())
      selected.icon = parse.icon
      form['slug'] = parse.slug
    })
  }

})
</script>
<template>
  <div class="panel form-wrapper">
    <div class="panel-hdr">
      <h2 class="fs-xl">
        <i :class="`fad fa-${ IS_ADD ? 'plus-circle' : 'pencil' } mr-2 text-primary`"></i>
        <strong class="fw-600">
          {{ IS_ADD ? 'Tambah' : 'Ubah' }}&nbsp;<span class="fw-300">Akses Fitur</span>
        </strong>
      </h2>
    </div>
    <div class="panel-container">
      <div class="panel-content">
        <VeeForm ref="formRef" @submit="handleSubmit">
          <div class="row">
            <div class="col-12 mb-3">
              <Field
                v-slot="{ field, errorMessage, meta }"
                label="Nama Fitur Akses"
                name="nama"
                :rules="setting.rules.nama"
              >
                <b-form-group
                  label="Nama Fitur Akses"
                  label-for="nama"
                  label-class="fw-700 mb-0"
                  :state="!meta.touched ? null : errorMessage ? false : true"
                  :invalid-feedback="!meta.touched ? null : errorMessage"
                >
                  <b-form-input
                    id="nama"
                    v-bind="field"
                    placeholder="Masukkan nama fitur akses"
                    :state="!meta.touched ? null : errorMessage ? false : true"
                  />
                </b-form-group>
              </Field>
            </div>
            <div class="col-12 col-md-6 mb-3">
              <Field
                v-slot="{ field, errorMessage, meta }"
                label="Alias"
                name="alias"
                :rules="setting.rules.alias"
              >
                <b-form-group
                  label="Alias"
                  label-for="alias"
                  label-class="fw-700 mb-0"
                  :state="!meta.touched ? null : errorMessage ? false : true"
                  :invalid-feedback="!meta.touched ? null : errorMessage"
                >
                  <b-form-input
                    id="alias"
                    v-bind="field"
                    placeholder="Masukkan alias"
                    :state="!meta.touched ? null : errorMessage ? false : true"
                  />
                </b-form-group>
              </Field>
            </div>
            <div class="col-12 col-md-6 mb-3">
              <Field
                v-slot="{ errorMessage, meta }"
                label="Tipe Fitur Akses"
                name="tipe"
                :rules="setting.rules.tipe"
              >
                <b-form-group
                  label="Tipe"
                  label-for="tipe"
                  label-class="fw-700 mb-0"
                  :class="{ 'is-invalid': errorMessage && errorMessage.tipe }"
                  :state="!meta.touched ? null : errorMessage ? false : true"
                  :invalid-feedback="!meta.touched ? null : errorMessage"
                >
                  <vue-multiselect
                    id="tipe"
                    v-model="selected.type"
                    :options="options.type"
                    select-label=""
                    selected-label=""
                    deselect-label=""
                    placeholder="Pilih Tipe"
                    @select="onHandleSelected"
                    @remove="onHandleRemoved"
                  >
                    <template #noOptions>Tidak ada data</template>
                    <template #noResult>Data tidak ditemukan</template>
                  </vue-multiselect>
                  <div class="text-danger fs-nano mt-1">
                    {{ errorMessage && errorMessage.tipe }}
                  </div>
                </b-form-group>
              </Field>
            </div>
            <div class="col-12 col-md-6 mb-3">
              <b-form-group
                label="Fitur Induk"
                label-for="induk"
                label-class="fw-700 mb-0"
              >
                <vue-multiselect
                  id="induk"
                  v-model="selected.induk"
                  label="nama_fitur"
                  :options="options.induk"
                  :internal-search="false"
                  :clear-on-select="false"
                  track_by="slug"
                  select-label=""
                  selected-label=""
                  deselect-label=""
                  placeholder="Pilih Fitur Induk"
                  @select="onHandleSelected"
                  @remove="onHandleRemoved"
                  @search-change="getParents"
                >
                  <template #noOptions>Tidak ada data</template>
                  <template #noResult>Data tidak ditemukan</template>
                </vue-multiselect>
              </b-form-group>
            </div>
            <div class="col-12 col-md-6 mb-3">
              <Field
                v-slot="{ field, errorMessage, meta, value }"
                label="Urutan"
                name="order"
                :rules="setting.rules.order"
              >
                <b-form-group
                  label="Urutan"
                  label-for="order"
                  label-class="fw-700 mb-0"
                  :state="!meta.touched ? null : errorMessage ? false : true"
                  :invalid-feedback="!meta.touched ? null : errorMessage"
                >
                  <b-form-input
                    id="order"
                    v-bind="field"
                    placeholder="Masukkan urutan"
                    :state="!meta.touched ? null : errorMessage ? false : (value ? true : null)"
                  />
                </b-form-group>
              </Field>
            </div>
            <div v-if="selected.isMenu" class="col-12">
              <div class="panel shadow-0 border">
                <div class="panel-container">
                  <div class="panel-content">
                    <div class="row">
                      <div class="col-12 col-md-6 mb-0">
                        <Field
                          v-slot="{ field, errorMessage, meta }"
                          label="URL Route"
                          name="route"
                          :rules="setting.rules.route"
                        >
                          <b-form-group
                            label="URL Route"
                            label-for="route"
                            label-class="fw-700 mb-0"
                            :state="!meta.touched ? null : errorMessage ? false : true"
                            :invalid-feedback="!meta.touched ? null : errorMessage"
                          >
                            <b-form-input
                              id="route"
                              v-bind="field"
                              placeholder="Masukkan url route"
                              :state="!meta.touched ? null : errorMessage ? false : true"
                            />
                          </b-form-group>
                        </Field>
                      </div>
                      <div class="col-12 col-md-6 mb-0">
                        <b-form-group
                          label="Icon"
                          label-for="icon"
                          label-class="fw-700 mb-0"
                        >
                          <vue-multiselect
                            id="icon"
                            v-model="selected.icon"
                            :options="options.icon"
                            :options-limit="25"
                            select-label=""
                            selected-label=""
                            deselect-label=""
                            placeholder="Pilih Icon"
                            @select="onHandleSelected"
                            @remove="onHandleRemoved"
                          >
                            <template #noOptions>Tidak ada data</template>
                            <template #noResult>Data tidak ditemukan</template>
                            <template #singleLabel="props">
                              <i :class="`fad fa${props.option} text-dark mr-1`"></i>
                              {{ toTitle(props.option.slice(1)) }}
                            </template>
                            <template #option="props">
                              <i :class="`fad fa${props.option} text-dark mr-1`"></i>
                              {{ toTitle(props.option.slice(1)) }}
                            </template>
                          </vue-multiselect>
                        </b-form-group>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 mb-3">
              <Field
                v-slot="{ field, errorMessage, meta, value }"
                label="Keterangan"
                name="keterangan"
                :rules="setting.rules.keterangan"
              >
                <b-form-group
                  label="Keterangan"
                  label-for="keterangan"
                  label-class="fw-700 mb-0"
                  :state="!meta.touched ? null : errorMessage ? false : true"
                  :invalid-feedback="!meta.touched ? null : errorMessage"
                >
                  <b-form-textarea
                    id="keterangan"
                    v-bind="field"
                    placeholder="Masukkan keterangan"
                    :state="!meta.touched ? null : errorMessage ? false : (value ? true : null)"
                  />
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
