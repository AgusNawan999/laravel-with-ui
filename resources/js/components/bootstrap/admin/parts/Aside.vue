<script setup>
import { onMounted, ref } from 'vue'
import Menu from '@/js/components/bootstrap/admin/parts/MenuItem.vue'
import { _settings, _initialName, _http, _route } from '@/js/utils/common'

const USER_NAME = _settings.user.name
const SECTION = _settings.user.nalok

const isReady = ref(false)
const structureMenu = ref([])
const options = {
  height:'100%',
  size:0
}

onMounted(() => {
  _http.get(_route('backend.setting.features.menu'))
    .then(res => structureMenu.value = res.data.data)
    .catch(error => console.log({ error }))
    .finally(() => isReady.value = true)
})
</script>
<template>
  <!-- BEGIN Left Aside -->
  <aside class="page-sidebar">
    <div class="page-logo">
      <a
        href="#"
        class="page-logo-link press-scale-down d-flex align-items-center position-relative"
        data-toggle="modal"
        data-target="#modal-shortcut"
      >
        <img
          src="@/images/logo-emasin2.png"
          class="img-responsive"
          :alt="_settings.appname"
          aria-roledescription="logo"
          style="width: 28px"
        />
        <span class="mr-1 page-logo-text">{{ _settings.appname }}</span>
        <span class="mr-2 text-white opacity-50 position-absolute small pos-top pos-right mt-n2"></span>
      </a>
    </div>
    <div class="line-divider"></div>
    <!-- BEGIN PRIMARY NAVIGATION -->
    <nav id="js-primary-nav" v-slimscroll="options" class="primary-nav" role="navigation">
      <Menu v-if="isReady" :items="structureMenu"></Menu>
    </nav>
    <!-- END PRIMARY NAVIGATION -->
    <!-- NAV FOOTER -->
    <div class="nav-footer">
      <div class="ava">
        <b-avatar
          class="profile-image"
          size="2rem"
          :alt="USER_NAME"
          :text="_initialName(USER_NAME)"
          variant="secondary"
        ></b-avatar>
      </div>
      <div class="ml-3 mr-2 ava-name d-flex flex-column justify-content-center">
        <span class="text-truncate text-truncate-sm d-inline-block fw-400">
          {{ USER_NAME }}
        </span>
        <span v-tippy="{ content: SECTION }" class="text-truncate text-truncate-sm d-inline-block fs-nano">
          {{ SECTION }}
        </span>
      </div>
      <!-- <div title="Perbesar menu" data-action="toggle" data-class="nav-function-minify" class="ml-auto minify-nav btn btn-icon"><i class="fad fa-angle-double-right fa-md"></i></div> -->
      <!-- data-class="nav-function-minify" -->
      <div
        v-tippy="{ content: 'Perkecil Menu' }"
        class="ml-auto minify-nav btn btn-icon"
        data-action="toggle"
        data-class=""
      >
        <i class="fad fa-angle-double-left fa-md"></i>
      </div>
    </div>
    <!-- END NAV FOOTER -->
  </aside>
  <!-- END Left Aside -->
</template>
