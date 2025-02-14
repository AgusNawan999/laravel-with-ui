<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const props = defineProps({
  items: {
    type: Array,
    required: true,
    default: () => []
  }
})

const navs = ref(null)
const router = useRouter()
const onHandleClick = (item, parent = null) => {
  navs.value
    .querySelectorAll('a')
    .forEach(a => {
      const dataset = a.dataset
      const classList = a.parentNode.classList
      const sign = a.querySelectorAll('.fas')[0]

      if (!item.childs.length) {
        classList.remove('active', 'open')

        if (sign && !parent) {
          sign.classList.add('fa-angle-down')
          sign.classList.remove('fa-angle-up')
        }
      }

      // item has parent
      if (parent && dataset.alias == parent)
        classList.add('active', 'open')

      if (dataset.alias == item.alias) {
        if (!item.childs.length)
          classList.add('active')

        if (!parent && item.childs.length) {
          // is parent & have childs
          const fn = classList.contains('open') ? 'remove' : 'add'
          classList[fn]('open')

          // change sign
          if (sign) {
            if (sign.classList.contains('fa-angle-down')) {
              sign.classList.remove('fa-angle-down')
              sign.classList.add('fa-angle-up')
            } else {
              sign.classList.add('fa-angle-down')
              sign.classList.remove('fa-angle-up')
            }
          }
        }

        // single link
        if (item.route) {
          router.push({ name: item.route })
          return
        }
      }
    })
}
</script>
<template>
  <ul id="js-nav-menu" ref="navs" class="nav-menu">
    <template v-for="(item, idx) in props.items" :key="idx">
      <li
        v-if="Object.hasOwnProperty.call(item, 'category') && item.category !== 'root'"
        :data-idx="idx"
        class="nav-title"
      >
        {{ item.category }}
      </li>
      <template v-for="(parent, key) in item.items" :key="key">
        <li>
          <a
            href="#"
            class="waves-effect waves-themed"
            :data-alias="parent.alias"
            @click="onHandleClick(parent)"
          >
            <i v-if="parent.icon" :class="`fad fa${parent.icon}`"></i>
            <span class="nav-link-text">{{ parent.label }}</span>
            <b v-if="parent.childs.length" class="collapse-sign">
              <i class="fas fa-angle-down"></i>
            </b>
          </a>
          <ul v-if="parent.childs.length">
            <li v-for="(child, childIdx) in parent.childs" :key="childIdx">
              <a
                :title="child.label"
                class="waves-effect waves-themed"
                :data-alias="child.alias"
                :data-parent="parent.alias"
                @click="onHandleClick(child, parent.alias)"
              >
                <span class="nav-link-text">{{ child.label }}</span>
              </a>
            </li>
          </ul>
        </li>
      </template>
    </template>
  </ul>
</template>
