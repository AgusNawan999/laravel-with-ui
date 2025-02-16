<script setup>
// import { useRoute } from 'vue-router'
import { _settings } from '@/js/utils/common'
import Aside from '@/js/components/bootstrap/admin/parts/Aside.vue'
import Footer from '@/js/components/bootstrap/admin/parts/Footer.vue'
import Subheader from '@/js/components/bootstrap/admin/parts/Subheader.vue'
import Breadcrumbs from '@/js/components/bootstrap/admin/parts/Breadcrumbs.vue'
import { ref } from 'vue'

const modalRef = ref()
const backToLanding = () => (window.location = '/')
const randomKey = (Math.random() + 1).toString(36).substring(7)

</script>

<template>
  <div class="page-wrapper">
    <div class="page-inner">
      <Aside />
      <div class="page-content-wrapper">
        <!-- BEGIN Page Header -->
        <header class="page-header" role="banner">
          <!-- we need this logo when user switches to nav-function-top -->
          <div class="page-logo">
            <a
              href="#"
              class="page-logo-link press-scale-down d-flex align-items-center position-relative"
              data-toggle="modal"
              data-target="#modal-shortcut"
            >
              <img src="@/images/logo-emasin2.png" alt="SmartAdmin WebApp" aria-roledescription="logo" />
              <span class="mr-1 page-logo-text">{{ _settings.appname }}</span>
              <span class="mr-2 text-white opacity-50 position-absolute small pos-top pos-right mt-n2"></span>
              <i class="ml-1 fal fa-angle-down d-inline-block fs-lg color-primary-300"></i>
            </a>
          </div>
          <!-- DOC: nav menu layout change shortcut -->
          <div class="hidden-md-down dropdown-icon-menu position-relative">
            <Subheader />
            <Breadcrumbs />
          </div>
          <!-- DOC: mobile button appears during mobile width -->
          <div class="hidden-lg-up">
            <a href="#" class="header-btn btn press-scale-down" data-action="toggle" data-class="mobile-nav-on">
              <i class="ni ni-menu"></i>
            </a>
          </div>
          <div class="ml-auto d-flex">
            <b-dropdown
              variant="link"
              toggle-tag="div"
              class="m-2"
              toggle-class="border-0 text-decoration-none"
            >
              <template #button-content>
                <i class="mr-1 fad fa-cog text-muted scale-up"></i>
              </template>
              <b-dropdown-item href="#" class="text-decoration-none">
                <a
                  href="javascript:void(0)"
                  class="cursor-pointer text-dark"
                  @click.prevent="backToLanding"
                >
                  <span>
                    <i class="mr-3 fad fa-desktop"></i>Landing
                  </span>
                </a>
              </b-dropdown-item>
              <b-dropdown-item href="#" class="text-decoration-none">
                <a
                  href="javascript:void(0)"
                  class="cursor-pointer text-dark"
                  onclick="doLogout.apply(this, arguments)"
                >
                  <span><i class="mr-3 fad fa-sign-out"></i>Logout</span>
                </a>
              </b-dropdown-item>
            </b-dropdown>
            <b-modal ref="modalRef" title="BootstrapVue">
              <p class="my-4">Hello from modal!</p>
            </b-modal>

          </div>
        </header>
        <!-- END Page Header -->
        <!-- BEGIN Page Content -->
        <!-- the #js-page-content id is needed for some plugins to initialize -->
        <main id="js-page-content" :key="randomKey" role="main" class="mt-5 page-content">
          <router-view :key="randomKey"></router-view>
        </main>
        <!-- this overlay is activated only when mobile menu is triggered -->
        <div class="page-content-overlay" data-action="toggle" data-class="mobile-nav-on"></div>
        <!-- END Page Content -->
        <!-- BEGIN Page Footer -->
        <Footer />
        <!-- END Page Footer -->
      </div>
    </div>
  </div>
</template>

<style lang="scss">
.header-menu {
  a {
    &[target] {
      &:not(.btn) {
        text-decoration: none !important;
      }
    }
  }
}

a {
  &.dropdown-item {
    &.text-decoration-none {
      text-decoration: none !important;
    }
  }
}

.dropdown-toggle {
  &.text-decoration-none {
    &::after {
      display: none;
    }
  }
}

.scale-up {
  transform: scale(1.5);
}
</style>

