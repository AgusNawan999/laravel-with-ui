export const fields = [
  {
    key: 'action',
    label: 'Aksi',
    class: 'text-center',
    sortable: false,
    thStyle: { 'width': '60px' }
  },
  {
    key: 'rownum',
    label: 'No',
    class: 'text-center',
    thStyle: { 'width': '43px' }
  },
  {
    key: 'nama_fitur',
    label: 'Nama Akses Fitur',
  },
  {
    key: 'alias',
    label: 'Alias',
  },
  {
    key: 'tipe_fitur',
    label: 'Tipe',
  },
  {
    key: 'nama_induk',
    label: 'Induk',
    formatter: value => value ? value : '-'
  },
  {
    key: 'deskripsi',
    label: 'Deskripsi',
  },
]

export const styles = {
  responsive: true,
  striped: true,
  hover: true,
  bordered: true,
  borderless: true,
  outlined: false,
  dark: false,
  stickyHeader: false,
  stacked: false,
  headVariant: 'light',
  tableClass: 'custom-bordered',
}
