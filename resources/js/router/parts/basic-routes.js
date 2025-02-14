const landing = () => import('../../modules/entry/Landing.vue')
const forbidden = () => import('../../modules/error/403.vue')
const pageNotFound = () => import('../../modules/error/404.vue')

export const basicRoutes = [
  {
    path: '/',
    component: landing,
    name: 'landing.home',
    meta: {
      auth: false,
      access: true,
    },
  },
  {
    path: '/coba',
    component: landing,
    name: 'coba',
    meta: {
      auth: true,
      access: true,
    },
  },
  {
    path: '/forbidden',
    component: forbidden,
    name: 'forbidden',
    meta: {
      auth: false,
      access: true,
    },
  },
  {
    path: '/:pathMatch(.*)',
    component: pageNotFound,
    name: 'pagenotfound',
    meta: {
      auth: false,
      access: true,
    },
  },
]
