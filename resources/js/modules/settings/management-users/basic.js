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
    key: 'username',
    label: 'User ID',
    thStyle: { 'width': '100px' }
  },
  {
    key: 'v_full_name',
    label: 'Nama User',
    thStyle: { 'width': '25%' }
  },
  {
    key: 'si_user_enable',
    label: 'Status Aktif',
    class: 'text-center',
    thStyle: { 'width': '100px' }

  },
  {
    key: 'group',
    label: 'Group'
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
