<script setup>
import { useRouter } from 'vue-router'

const props = defineProps({
  home: {
    type: String,
    required: false,
    default: 'admin.home'
  }
})

const router = useRouter()

const onHandleRoute = (to) => {
  if (to == 'landing.home') {
    router.push('/')
    return
  }

  router.push({ name: to })
}
</script>

<template>
  <ol class="breadcrumb page-breadcrumb">
    <template v-if="$route?.meta?.breadcrumbs?.length">
      <li
        v-for="(breadcrumb, idx) in $route.meta.breadcrumbs"
        :key="idx"
        class="breadcrumb-item"
      >
        <a
          v-if="breadcrumb.route"
          href="javascript:void(0)"
          @click="onHandleRoute(breadcrumb.route)"
        >
          {{ breadcrumb.label }}
        </a>
        <span v-else>
          {{ breadcrumb.label }}
        </span>
      </li>
    </template>
    <template v-else>
      <li class="breadcrumb-item">
        <a
          href="javascript:void(0)"
          @click="onHandleRoute(props.home)"
        >
          Beranda
        </a>
      </li>
    </template>
  </ol>
</template>
