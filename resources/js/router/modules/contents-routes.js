const routes = [
  {
    path: 'content-banners',
    children: [
      {
        path: '',
        component: () => import('@/js/modules/content/banner/Index.vue'),
        name: 'content.banners',
        meta: {
          auth: true,
          access: true,
          subheader: 'Manajemen Banner',
          breadcrumbs: [
            {
              label: 'Beranda',
              route: 'admin.home',
            },
            {
              label: 'Manajemen Banner',
              route: null,
            }
          ]
        },
      },
      {
        path: 'add',
        component: () => import('@/js/modules/content/banner/Form.vue'),
        name: 'content.banner.add',
        meta: {
          auth: true,
          access: true,
          subheader: 'Tambah Banner',
          breadcrumbs: [
            {
              label: 'Beranda',
              route: 'admin.home',
            },
            {
              label: 'Manajemen Banner',
              route: 'content.banners',
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
        component: () => import('@/js/modules/content/banner/Form.vue'),
        props: true,
        name: 'content.banner.edit',
        meta: {
          auth: true,
          access: true,
          subheader: 'Ubah Banner',
          breadcrumbs: [
            {
              label: 'Beranda',
              route: 'admin.home',
            },
            {
              label: 'Manajemen Banner',
              route: 'content.banners',
            },
            {
              label: 'Ubah',
              route: null,
            }
          ]
        },
      },

    ],
  }
];

export const contents = [...routes]
