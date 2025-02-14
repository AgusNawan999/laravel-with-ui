<script setup>
import { onMounted } from 'vue'
import { _settings } from '@/js/utils/common'
import Breadcrumbs from '@/js/components/bootstrap/admin/parts/Breadcrumbs.vue';

const props = defineProps({
  hideBreadcrumbs: {
    type: Boolean,
    required: false,
    default: false
  }
})

const APP_NAME = _settings.appname
const dinas = {
  short_name: 'Diskominfotik',
  long_name: 'Dinas Komunikasi, Informatika dan Statistik'
}

onMounted(() => {
  const body = document.getElementsByTagName('body')[0]
  body.classList.remove('nav-function-fixed')
  body.classList.add('mod-nav-link', 'nav-function-top', 'blur')
})
</script>

<template>
  <div class="page-wrapper">
    <div class="page-inner">
      <div class="page-content-wrapper">
        <header class="page-header" role="banner">
          <div class="page-logo">
            <a href="/" class="page-logo-link press-scale-down d-flex align-items-center position-relative">
              <img src="@/images/dki-logo.png" alt="Logo Pemprov DKI Jakarta" aria-roledescription="logo">
              <span class="page-logo-text mr-1">{{ _settings.appname }}</span>
            </a>
          </div>
          <div class="ml-auto d-flex">
            <b-dropdown
              variant="link"
              toggle-tag="div"
              class="m-2"
              toggle-class="text-decoration-none border-0"
            >
              <template #button-content>
                <i class="fad fa-cog text-muted mr-1 scale-up"></i>
              </template>
              <b-dropdown-item href="#" class="text-decoration-none">
                <a
                  href="javascript:void(0)"
                  class="text-dark cursor-pointer"
                  @click.prevent="$router.push('/')"
                >
                  <span>
                    <i class="fad fa-home-alt fa-fw mr-2 no-need-transform"></i>Beranda
                  </span>
                </a>
              </b-dropdown-item>
              <b-dropdown-item href="#" class="text-decoration-none">
                <a
                  href="javascript:void(0)"
                  class="text-dark cursor-pointer"
                  @click.prevent="$router.push({ name: 'landing.profile' })"
                >
                  <span>
                    <i class="fad fa-clipboard-user fa-fw mr-2 no-need-transform"></i>Profil
                  </span>
                </a>
              </b-dropdown-item>
              <b-dropdown-item href="#" class="text-decoration-none">
                <a
                  href="javascript:void(0)"
                  class="text-dark cursor-pointer"
                  @click.prevent="$router.push({ name: 'landing.manual-book' })"
                >
                  <span>
                    <i class="fad fa-books fa-fw mr-2 no-need-transform"></i>Buku Manual
                  </span>
                </a>
              </b-dropdown-item>
              <b-dropdown-item href="#" class="text-decoration-none">
                <a
                  href="javascript:void(0)"
                  class="text-dark cursor-pointer"
                  onclick="doLogout.apply(this, arguments)"
                >
                  <span><i class="fad fa-sign-out fa-fw mr-2 no-need-transform"></i>Logout</span>
                </a>
              </b-dropdown-item>
            </b-dropdown>
          </div>
        </header>

        <main role="main" class="page-content position-relative">
          <Breadcrumbs v-if="!props.hideBreadcrumbs" home="landing.beranda" />
          <div class="subheader">
            <h1 class="subheader-title">
              <slot name="subheader-icon"></slot>
              <slot name="subheader-title"></slot>
              <slot name="subheader-subtitle"></slot>
            </h1>
          </div>
          <div class="content-wrapper">
            <slot></slot>
          </div>
        </main>

        <footer class="page-footer" role="contentinfo">
          <div class="d-flex align-items-center flex-1 text-muted">
            <span class="ml-auto hidden-md-down fw-700">
              2023 © {{ APP_NAME }} made with <span class="text-danger">❤</span> by&nbsp;
              <a
                v-tippy="{ content: dinas.long_name }"
                href="#"
                class="text-primary fw-700"
                target="_blank"
              >
                {{ dinas.short_name }}
              </a>
            </span>
          </div>
        </footer>
      </div>
    </div>
  </div>
</template>

<style lang="scss">
.page-wrapper {
  .page-inner {
    .page-content-wrapper {
      margin-top: 0;
      header {
        &.page-header {
          border-bottom: 1px solid var(--border-primary-light) !important;
          .page-logo {
            height: 4rem;
            display: flex;
            border: 0;
            padding-left: 0;
            a {
              img {
                width: 24px;
              }

              span {
                font-weight: 700;
              }
            }
          }
          a {
            font-size: .875rem;
            i {
              transform: scale(1.5);
              &.no-need-transform {
                transform: scale(1);
              }
            }
          }
        }
      }

      main {
        &.page-content {
          ol {
            &.breadcrumb {
              padding: 0;
              background-color: transparent;
            }
          }

          .subheader {
            h1 {
              &.subheader-title {
                font-size: 1rem;
              }
            }
          }

          .content-wrapper {
            top: 0;
            margin-left: 0;
          }
        }
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
