import { _settings } from '@/js/utils/common'
import { createWebHistory, createRouter } from 'vue-router'

const landing = () => import('@/js/modules/entry/Landing.vue')
const beranda = () => import('@/js/modules/layanan/ListLayanan.vue')

// import some route
import { basicRoutes } from './parts/basic-routes'

const landingRoutes = [
  {
    path: '/',
    component: landing,
    name: 'landing.home',
    children: [
      {
        path: '',
        component: beranda,
        name: 'landing.beranda',
      }
    ],
    meta: {
      auth: false,
      access: true,
      title: 'Beranda', // Judul halaman untuk route ini
    },
  }
]

// create router
const router = createRouter({
  history: createWebHistory(),
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition)
      return savedPosition

    if (to.hash) {
      return {
        el: to.hash,
        behavior: 'smooth',
      }
    }

    // always scroll to top
    return { top: 0 }
  },
  routes: [...basicRoutes,...landingRoutes]
})

router.beforeEach((to, from, next) => {
  const title = to.meta?.title
  const singleTitle = Array.isArray(title) ? [...title].pop() : title

  document.title = singleTitle ? `${singleTitle} | ${_settings.appname}` : _settings.appname

  // cek apakah membutuhkan login
  if (to.meta.auth && !_settings.loggedInUser) next('/')

  // cek hak akses
  if (!to.meta.access) next({ name: 'forbidden' })

  next()
})

export default router
