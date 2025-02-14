<script setup>
import { ref, onMounted } from 'vue'
import { _settings} from '@/js/utils/common'


const navbar = {
  dropdown: ref(null)
}

const props = defineProps({
  slug: {
    type: String,
    required: false,
    default: 'virtual-meeting'
  }
})

const editData = ref({})
const mode = ref('add')
const showSidebar = ref(false)

onMounted(() => {
  navbar.dropdown.value.toggler.classList.remove('btn')
  navbar.dropdown.value.toggler.classList.remove('btn-link')
})

const onSidebarShown = () => {
  const body = document.getElementsByTagName('body')[0]
  const sidebarWrapper = document.getElementsByClassName('b-sidebar-body')[0]

  body.style.overflow = 'hidden'
  sidebarWrapper.scrollTop = 0
}

</script>

<template>
  <div class="frontend-wrapper d-flex flex-row">
    <div class="sidebar p-4 mr-4 border-right">
      <a
        v-tippy="{ content: 'Kembali ke beranda' }"
        href="javascript:void(0)"
        class="btn-link text-muted"
        @click="$router.push('/')"
      >
        <i class="fad fa-home fa-lg"></i>
      </a>
    </div>
    <div class="main flex-1">
      <div class="d-flex flex-column">
        <div class="top-navbar d-flex pl-0 pr-3 pt-3 pb-0 justify-content-between">
          <div class="d-flex flex-column">
            <span class="lead fw-700 d-none d-md-block"><slot name="header-title">Title Here</slot></span>
            <span class="fw-300 text-muted d-none d-md-block">
              <slot name="header-description">Description here..</slot>
            </span>
          </div>
          <b-dropdown
            :ref="navbar.dropdown"
            toggle-tag="div"
            variant="link"
            toggle-class="text-decoration-none border-0"
            no-caret
            right
          >
            <template #button-content>
              <div class="d-flex align-items-center mb-3">
                <b-avatar text="SA" variant="default"></b-avatar>
                <div class="d-flex flex-column px-3">
                  <span class="fw-700 text-dark text-uppercase">
                    {{ _settings.user.name }}
                  </span>
                  <span
                    v-tippy="{ content: _settings.user.nalok }"
                    class="nalok text-muted fs-sm"
                  >
                    {{ _settings.user.nalok }}
                  </span>
                </div>
              </div>
            </template>
            <b-dropdown-item class="no-decoration">Action</b-dropdown-item>
            <b-dropdown-item class="no-decoration">Another action</b-dropdown-item>
            <b-dropdown-item class="no-decoration">Something else here...</b-dropdown-item>
          </b-dropdown>
        </div>
        <div class="main-container flex-1 pt-4">
          <slot name="content">Content Here</slot>
        </div>
      </div>
    </div>

    <b-sidebar
      id="sidebar-frontend"
      v-model:visible="showSidebar"
      backdrop-variant="dark"
      right
      shadow
      backdrop
      no-header
      no-close-on-esc
      no-close-on-backdrop
      @shown="onSidebarShown"
    >
      <template #default="{ hide }">
        <Form
          :slug="props.slug"
          :mode="mode"
          :edit-data="editData"
          @on-close="({ resetForm, isDelete }) => handleOnHideForm({ hide, resetForm, isDelete })"
          @after-add="handleOnAfterAdd"
        />
      </template>
    </b-sidebar>
  </div>
</template>

<style lang="scss">
.frontend-wrapper {
  background-color: #f9f9f9;
  height: 100%;

  .sidebar {
    width: 68px;
  }

  .main-container {
    max-width: calc(100% - 1.5rem);
  }

  .b-sidebar-body {
    &::-webkit-scrollbar {
      width: 8px;
      height: 8px;
    }

    &::-webkit-scrollbar-track-piece {
      background-color: rgb(243, 244, 245);
    }

    &::-webkit-scrollbar-thumb {
      background-color: #666;
    }
  }

  .sidebar {
    a:hover {
      i {
        color: var(--theme-primary);
      }
    }
  }

  .no-decoration {
    text-decoration-style: none !important;
    text-decoration-color: none !important;
    text-decoration-line: none !important;

  }

  .b-avatar {
    &.badge-default {
      background-color: #eaeaea;
    }
  }

  .table-bordered {
    thead {
      th, td {
        border-bottom-width: 1px;
      }
    }
  }

  .fc-scrollgrid {
    &.table-bordered {
      border-radius: 4px;
    }
  }

  .fc-scrollgrid-sync-inner {
    padding: .425rem 0;
  }

  .fc-v-event {
    &.text-dark {
      .fc-event-main {
        color: var(--theme-dark);
      }
    }
  }

  .calendar-nav {
    height: auto;
    padding: 7px 12px 4px 11px;
    border: 1px solid #eaeaea;
    border-radius: 5px;
    cursor: pointer;

    &.primary {
      background-color: var(--theme-primary);
      color: var(--theme-light);
      border: 1px solid var(--theme-primary-600);

      &:hover {
        color: var(--theme-light);
        background-color: var(--theme-primary-400);
      }
    }

    &:hover {
      background-color: #ececec;
    }
  }

  #sidebar-frontend {
    &.b-sidebar {
      width: 500px;
    }

    @media (min-width: 768px) {
      &.b-sidebar {
        width: 500px;
      }
    }

    @media (max-width: 576px) {
      &.b-sidebar {
        width: 100%;
      }
    }
  }

  .nalok {
    max-width: 200px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .bg-light-grey {
    background-color: #ddd;
    color: var(--theme-dark);
  }
}
</style>
