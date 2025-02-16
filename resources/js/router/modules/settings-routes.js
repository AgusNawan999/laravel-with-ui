const routes = [
  {
    path: 'setting-features',
    children: [
      {
        path: '',
        component: () => import('../../modules/settings/feature-access/Index.vue'),
        name: 'settings.features',
        meta: {
          auth: true,
          access: true,
          subheader: 'Manajemen Akses Fitur',
          breadcrumbs: [
            {
              label: 'Beranda',
              route: 'admin.home',
            },
            {
              label: 'Manajemen Akses Fitur',
              route: null,
            }
          ]
        },
      },
      {
        path: 'add',
        component: () => import('@/js/modules/settings/feature-access/Form.vue'),
        name: 'settings.features.add',
        meta: {
          auth: true,
          access: true,
          subheader: 'Tambah Akses Fitur',
          breadcrumbs: [
            {
              label: 'Beranda',
              route: 'admin.home',
            },
            {
              label: 'Manajemen Akses Fitur',
              route: 'settings.features',
            },
            {
              label: 'Tambah',
              route: null,
            }
          ]
        },
      },{
        path: ':slug/edit',
        component: () => import('@/js/modules/settings/feature-access/Form.vue'),
        props: true,
        name: 'settings.features.edit',
        meta: {
          auth: true,
          access: true,
          subheader: 'Ubah Akses Fitur',
          breadcrumbs: [
            {
              label: 'Beranda',
              route: 'admin.home',
            },
            {
              label: 'Manajemen Akses Fitur',
              route: 'settings.features',
            },
            {
              label: 'Ubah',
              route: null,
            }
          ]
        },
      },
    ]
  },
  {
    path: 'setting-groups',
    children: [
      {
        path: '',
        component: () => import('@/js/modules/settings/group-management/Index.vue'),
        name: 'settings.groups',
        meta: {
          auth: true,
          access: true,
          subheader: 'Manajemen Grup dan Hak Akses',
          breadcrumbs: [
            {
              label: 'Beranda',
              route: 'admin.home',
            },
            {
              label: 'Manajemen Grup dan Hak Akses',
              route: null,
            }
          ]
        },
      },
      {
        path: 'add',
        component: () => import('@/js/modules/settings/group-management/Form.vue'),
        name: 'settings.group.add',
        meta: {
          auth: true,
          access: true,
          subheader: 'Tambah Akses Fitur',
          breadcrumbs: [
            {
              label: 'Beranda',
              route: 'admin.home',
            },
            {
              label: 'Manajemen Grup dan Hak Akses',
              route: 'settings.groups',
            },
            {
              label: 'Tambah',
              route: null,
            }
          ]
        },
      },
      {
        path: ':slug/edit',
        component: () => import('@/js/modules/settings/group-management/Form.vue'),
        props: true,
        name: 'settings.groups.edit',
        meta: {
          auth: true,
          access: true,
          subheader: 'Ubah Group',
          breadcrumbs: [
            {
              label: 'Beranda',
              route: 'admin.home',
            },
            {
              label: 'Manajemen Grup dan Hak Akses',
              route: 'settings.groups',
            },
            {
              label: 'Ubah',
              route: null,
            }
          ]
        },
      },
    ]
  },
  {
    path: 'setting-management-users',
    children: [
      {
        path: '',
        component: () => import('@/js/modules/settings/management-users/Index.vue'),
        name: 'settings.management.users',
        meta: {
          auth: true,
          access: true,
          subheader: 'Manajemen Users',
          breadcrumbs: [
            {
              label: 'Beranda',
              route: 'admin.home',
            },
            {
              label: 'Manajemen Users',
              route: null,
            }
          ]
        },
      },
      {
        path: 'add',
        component: () => import('@/js/modules/settings/management-users/Form.vue'),
        name: 'settings.management.users.add',
        meta: {
          auth: true,
          access: true,
          subheader: 'Tambah Pengguna',
          breadcrumbs: [
            {
              label: 'Beranda',
              route: 'admin.home',
            },
            {
              label: 'Manajemen Pengguna',
              route: 'settings.management.users',
            },
            {
              label: 'Tambah',
              route: null,
            }
          ]
        },
      },
      {
        path: ':slug/edit',
        component: () => import('@/js/modules/settings/management-users/Form.vue'),
        props: true,
        name: 'settings.management.users.edit',
        meta: {
          auth: true,
          access: true,
          subheader: 'Ubah Pengguna',
          breadcrumbs: [
            {
              label: 'Beranda',
              route: 'admin.home',
            },
            {
              label: 'Manajemen Pengguna',
              route: 'settings.management.users',
            },
            {
              label: 'Ubah',
              route: null,
            }
          ]
        },
      },

    ]
  },

];

export const settings = [...routes]
