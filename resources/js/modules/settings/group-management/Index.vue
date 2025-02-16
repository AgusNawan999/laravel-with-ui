<script setup>
import { useRouter } from 'vue-router'
import { reactive, ref, onMounted } from 'vue'
import Spinner from '@/js/components/Spinner.vue'
import { toTitle } from '@/js/utils/convert-case'
import { fields, styles } from '@/js/modules/settings/group-management/basic'
import { _, _http, _route, _alert, _encrypt, _confirm } from '@/js/utils/common'

const router = useRouter()
const refTable = ref(null)
const isFocus = ref(false)
const exclude = ['action', 'rownum', 'status']
const table = reactive({
  busy: false,
  fields: fields,
  columns: fields.filter(field => !exclude.includes(field.key)).map(field => { return { item: field.key, name: field.label } }),
  search: {
    filter: null,
    columns: [],
  },
  status: {
    selected: 'semua',
    options: ['semua', 'aktif', 'tidak aktif']
  },
  meta: {
    page: 1,
    from: 1,
    limit: 15,
  }
})

onMounted(() => table.search.columns = table.columns.map(column => column.item))

const loadProvider = _.debounce((ctx, callback) => {
  table.busy = true
  _http.get(_route('backend.setting.groups.get', {
    search: table.search.filter,
    status: table.status.selected,
    columns: table.search.columns.join(','),
    page: ctx.currentPage,
    sortBy: ctx.sortBy,
    sortDesc: ctx.sortDesc,
    limit: ctx.perPage,
  }))
    .then(({ data }) => {
      if (data.status == 'error') throw (data.response.message)
      table.items = data.data
      table.meta.from = data.meta.from
      table.meta.page = data.meta.current_page
      table.meta.limit = data.meta.per_page
      table.meta.total = data.meta.total
      callback(table.items)
    })
    .catch(error => {
      callback([])
      _alert.fire('Terjadi Kesalahan', error.response.data.message, 'error')
    })
    .finally(() => setTimeout(() => table.busy = false, 750))

  return null
}, 250)

const onHandleEdit = (item) => {
  const data = _encrypt(JSON.stringify(item))
  router.push({ name: 'settings.groups.edit', params: { slug: data } })
}

const onHandleDelete = (item) => {
  _confirm(
    { title: 'Hapus Group', text: `Apakah Anda yakin untuk menghapus group: ${ item.nama }`, icon: 'question' },
    () => {
      _http.delete(
        _route('backend.setting.group.delete'),
        { params: { '_method': 'delete', slug: item.slug } }
      )
        .then(result => result)
        .catch(error => console.error({ error }))
    }
  )
    .then(({ value, isConfirmed, isDismissed }) => {
      // do nothing
      if (isDismissed) return false

      // show response
      if (isConfirmed && value)
        _alert.fire({ title: `Hapus Data Berhasil`, text: `Fitur ${ item.nama } berhasil dihapus`, 'icon': 'success' })

        // refresh table
        refTable.value.refresh()
    })
}

const hanldeClickColumnFilter = item => {
  const idx = table.search.columns.findIndex(column => column == item)

  if (idx === -1) {
    table.search.columns.push(item)
    return
  }

  table.search.columns.splice(idx, 1)
}

const onHandleChangeStatus = status => {
  table.status.selected = status
  refTable.value.refresh()
}
</script>

<template>
  <div class="panel">
    <div class="panel-hdr">
      <h2 class="fs-xl">
        <i class="fad fa-th-list mr-2 text-primary"></i>
        <strong class="fw-600">
          Daftar&nbsp;<span class="fw-300">Group</span>
        </strong>
      </h2>
    </div>
    <div class="panel-container">
      <div class="panel-content">
        <div class="action-wrapper d-flex flex-column flex-md-row align-items-baseline justify-content-md-between mb-2">
          <b-button
            variant="primary"
            class="btn-sm-block mb-2 mb-md-0"
            @click="$router.push({ name: 'settings.group.add' })"
          >
            <i class="fad fa-plus-circle"></i>&nbsp;
            Tambah
          </b-button>
          <div class="searching-wrapper flex-1">
            <div class="d-flex justify-content-md-end align-items-center flex-wrap flex-lg-nowrap">
              <b-button variant="info" class="btn-sm-block mr-0 mr-sm-2 mb-2 mb-sm-0" @click.prevent="refTable.refresh()">
                <i class="fad fa-sync"></i>&nbsp;
                Muat Ulang
              </b-button>
              <div :class="{ 'search-input d-flex position-relative align-items-center': true, 'wider': isFocus }">
                <i class="fad fa-search position-absolute pos-left fs-lg px-2 fs-xs"></i>
                <input
                  v-model="table.search.filter"
                  type="text"
                  placeholder="Pencarian"
                  class="form-control py-2 pl-45 pr-4 rounded"
                  @focus="isFocus = true"
                  @blur="isFocus = false"
                />
                <i
                  :class="{
                    'fad fa-times-circle text-danger position-absolute pos-right fs-lg px-2 cursor-pointer': true,
                    'transparent': !table.search.filter
                  }"
                  @click.prevent="table.search.filter = ''"
                >
                </i>
              </div>
              <b-button
                v-tippy="{ content: 'Filter Kolom' }"
                v-b-modal.my-modal
                variant="default"
                class="ml-0 ml-sm-2 btn-sm-block mt-2 mt-sm-0"
              >
                <i class="fad fa-filter"></i>&nbsp;
                Filter
              </b-button>
              <b-modal
                id="my-modal"
                size="sm"
                hide-footer
                hide-header
              >
                <div class="fs-lg fw-700 mb-2">Kolom Filter</div>
                <div class="d-flex flex-column align-items-start">
                  <b-button
                    v-for="(column, idx) in table.columns"
                    :key="idx"
                    variant="link"
                    size="sm"
                    block
                    class="text-left"
                    @click.prevent="hanldeClickColumnFilter(column.item)"
                  >
                    <i
                      :class="{
                        'fad mr-2': true,
                        'fa-check-circle text-primary': table.search.columns.includes(column.item),
                        'fa-times-circle text-danger': !table.search.columns.includes(column.item)
                      }"
                    >
                    </i>
                    <span class="text-dark">{{ column.name }}</span>
                  </b-button>
                </div>
              </b-modal>
              <b-dropdown
                variant="default"
                class="ml-0 mt-2 ml-md-2 mt-md-0 form-control-height-sm"
                :text="`Status: ${ toTitle(table.status.selected) }`"
              >
                <b-dropdown-item
                  v-for="(option, idx) in table.status.options"
                  :key="idx"
                  link-class="btn"
                  @click.prevent="onHandleChangeStatus(option)"
                >
                  {{ toTitle(option) }}
                </b-dropdown-item>
              </b-dropdown>
            </div>
          </div>
        </div>
        <b-table
          id="group-table"
          ref="refTable"
          v-bind="styles"
          :busy="table.busy"
          :fields="table.fields"
          :filter="table.search.filter"
          :items="loadProvider"
          :per-page="table.meta.limit"
          :current-page="table.meta.page"
        >
          <template #table-busy>
            <Spinner />
          </template>
          <template #cell(action)="{ item }">
            <div class="d-flex align-items-baseline">
              <b-button
                variant="link"
                class="text-info py-0 px-1"
                @click.prevent="onHandleEdit(item)"
              >
                <i class="fad fa-edit"></i>
              </b-button>
              <b-button
                variant="link"
                class="text-danger py-0 px-1"
                @click.prevent="onHandleDelete(item)"
              >
                <i class="fad fa-trash"></i>
              </b-button>
            </div>
          </template>
          <template #cell(rownum)="{ index }">
            {{ table.meta.from + index }}
          </template>
          <template #cell(status)="{ value }">
            <span
              :class="{
                'badge': true,
                [`badge-${value.toLowerCase().startsWith('tidak') ? 'danger' : 'info'}`]: true
              }"
            >
              {{ value }}
            </span>
          </template>
          <template #cell(features)="{ item }">
            <span
              v-for="(feature, idx) in item.features"
              :key="idx"
              :class="{
                'badge border border-primary text-primary': true,
                'mr-1': idx != (item.features.length - 1)
              }"
            >
              {{ feature.nama_fitur }}
            </span>
          </template>
        </b-table>
        <div class="d-flex justify-content-between align-items-center">
          <b-dropdown
            variant="default"
            class="mr-2 form-control-height-sm perpage"
            :text="table.meta.limit.toString()"
          >
            <b-dropdown-item
              v-for="item in [5, 10, 15, 25, 50, 100]"
              :key="item"
              link-class="btn"
              @click.prevent="table.meta.limit = item"
            >
              {{ item }}
            </b-dropdown-item>
          </b-dropdown>
          <b-pagination
            v-model="table.meta.page"
            :total-rows="table.meta.total"
            :per-page="table.meta.limit"
            aria-controls="group-table"
            first-class="hidden-md-down"
            last-class="hidden-md-down"
            class="pagination mb-0"
          ></b-pagination>
        </div>
      </div>
    </div>
  </div>
</template>
