import { _settings } from '@/js/utils/common'
import { createWebHistory, createRouter } from 'vue-router'

// import some route
import { settings } from '../router/modules/settings-routes'
import { contents } from '../router/modules/contents-routes'


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
  routes: [
    {
      path: '/admin',
      component: () => import('@/js/components/bootstrap/admin/Container.vue'),
      meta: {
        auth: true,
        access: true,
      },
      children: [
        {
          path: '',
          component: () => import('@/js/modules/entry/Admin.vue'),
          name: 'admin.home',
          meta: {
            auth: true,
            access: true,
            subheader: 'Dashboard Admin'
          },
        },
        ...settings,
        ...contents,

      ],
    },
  ],
})

router.beforeEach((to, from, next) => {
  // cek apakah membutuhkan login
  if (to.meta.auth && !_settings.loggedInUser) window.location = '/'

  // cek hak akses
  if (!to.meta.access) window.location = '/forbidden'

  next()
})

export default router
