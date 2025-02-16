const routes = [
  {
    path: 'content-banners',
    children: [
      {
        path: '',
        component: () => import('../../modules/settings/feature-access/Index.vue'),
        name: 'content.banners',
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
      }

    ],
  }
];

export const contents = [...routes]
