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
    key: 'kode',
    label: 'Kode Group',
    thStyle: { 'width': '100px' }
  },
  {
    key: 'nama',
    label: 'Nama Group',
    thStyle: { 'width': '25%' }
  },
  {
    key: 'features',
    label: 'Hak Akses',
  },
  {
    key: 'status',
    label: 'Status',
    class: 'text-center',
    thStyle: { 'width': '50px' }
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
