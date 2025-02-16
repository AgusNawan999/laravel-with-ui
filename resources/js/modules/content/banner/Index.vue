<script setup>
import { onMounted, reactive, ref } from "vue"
import { fields, styles } from '@/js/modules/content/banner/basic'
import { _, _http, _route, _alert, _encrypt, _confirm } from '@/js/utils/common'
import { useRouter } from "vue-router"

const router = useRouter()
const refTable = ref(null)
const isFocus = ref(false)


const table = reactive({
  busy: false,
  fields: fields,
  columns: fields.filter(field => !['action', 'rownum'].includes(field.key)).map(field => { return { item: field.key, name: field.label } }),
  search: {
    filter: null,
    columns: [],
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
  _http.get(_route('backend.content.banner.get', {
    search: table.search.filter,
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
  router.push({ name: 'content.banner.edit', params: { slug: data } })
}

const onHandleDelete = (item) => {
  _confirm(
    { title: 'Hapus Akses Fitur', text: `Apakah Anda yakin untuk menghapus fitur: ${ item.title }`, icon: 'question' },
    () => {
      _http.delete(
        _route('backend.content.banner.delete'),
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
        _alert.fire({ title: `Hapus Data Berhasil`, text: `Fitur ${ item.title } berhasil dihapus`, 'icon': 'success' })

        // refresh table
        refTable.value.refresh()
    })
}

</script>

<template>
  <div class="panel">
    <div class="panel-hdr">
      <h2 class="fs-xl">
        <i class="mr-2 fad fa-th-list text-primary"></i>
        <strong class="fw-600">
          Daftar&nbsp;<span class="fw-300">Banner</span>
        </strong>
      </h2>
    </div>
    <div class="panel-container">
      <div class="panel-content">
        <div class="mb-2 action-wrapper d-flex flex-column flex-md-row align-items-baseline justify-content-md-between">
          <b-button
            variant="primary"
            class="mb-2 btn-sm-block mb-md-0"
            @click="$router.push({ name: 'content.banner.add' })"
          >
            <i class="fad fa-plus-circle"></i>&nbsp;
            Tambah
          </b-button>
          <div class="flex-1 searching-wrapper">
            <div class="flex-wrap d-flex justify-content-md-end align-items-center flex-lg-nowrap">
              <b-button variant="info" class="mb-2 mr-0 btn-sm-block mr-sm-2 mb-sm-0" @click.prevent="refTable.refresh()">
                <i class="fad fa-sync"></i>&nbsp;
                Muat Ulang
              </b-button>
              <div :class="{ 'search-input d-flex position-relative align-items-center': true, 'wider': isFocus }">
                <i class="px-2 fad fa-search position-absolute pos-left fs-lg fs-xs"></i>
                <input
                  v-model="table.search.filter"
                  type="text"
                  placeholder="Pencarian"
                  class="py-2 pr-4 rounded form-control pl-45"
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
                class="mt-2 ml-0 ml-sm-2 btn-sm-block mt-sm-0"
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
                <div class="mb-2 fs-lg fw-700">Kolom Filter</div>
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
            </div>
          </div>
        </div>
        <b-table
          id="fitur-akses-table"
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
                class="px-1 py-0 text-info"
                @click.prevent="onHandleEdit(item)"
              >
                <i class="fad fa-edit"></i>
              </b-button>
              <b-button
                variant="link"
                class="px-1 py-0 text-danger"
                @click.prevent="onHandleDelete(item)"
              >
                <i class="fad fa-trash"></i>
              </b-button>
            </div>
          </template>
          <template #cell(rownum)="{ index }">
            {{ table.meta.from + index }}
          </template>
          <template #cell(description)="{ item }">
            <p class="text-justify" v-html="item.description"></p>
          </template>
          <template #cell(image)="{ item }">
            <img :src="item.url_banner" width="110" height="110">
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
            :total-rows="table.meta.total"
            :per-page="table.meta.limit"
            aria-controls="fitur-akses-table"
            first-class="hidden-md-down"
            last-class="hidden-md-down"
            class="mb-0 pagination"
            @change="value => table.meta.page = value"
          ></b-pagination>
        </div>
      </div>
    </div>
  </div>
</template>
