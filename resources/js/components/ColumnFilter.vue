<script setup>
import { computed } from 'vue'
const props = defineProps({
  columns: {
    type: Array,
    required: true,
  },
  selected: {
    type: Array,
    required: true,
  }
})
const emits = defineEmits(['update:selected', 'selected'])

const componentId = Math.random().toString(36).substring(2, 9)
const localColumns = computed(() => props.columns)
const localSelected = computed(() => props.selected)
const showFilter = () => {
  const wrapper = document.getElementsByClassName(`column-filter-wrapper-${componentId}`)[0]
  const filterOverlay = wrapper.querySelector('.filter-overlay-wrapper')

  if (!filterOverlay) return

  const filter = filterOverlay.querySelector('.filter-wrapper')
  filterOverlay.style.display = 'block'

  if (filter) {
    filter.style.transform = `translateY(55px) translateX(-10px)`
    filter.style.top = 0
    filter.style.right = 0
    filter.style.willChange = 'transform'
  }
}
const hideFilter = (e) => {
  const wrapper = document.getElementsByClassName(`column-filter-wrapper-${componentId}`)[0]
  const filterOverlay = wrapper.querySelector('.filter-overlay-wrapper')
  const isOverlay = e.target.classList.contains('filter-overlay-wrapper')
    || e.target.classList.contains('apply-filter')
    || e.target.classList.contains('span-apply-filter')
    || e.target.classList.contains('fa-check-circle')

  if (!isOverlay) return

  if (filterOverlay) {
    filterOverlay.style.display = 'none'

    if (e.target.classList.contains('filter-overlay-wrapper')) return
    emits('selected', [...localSelected.value])
  }
}
const handleClickColumnFilter = (item) => {
  let currentSelected = [...localSelected.value]
  const idx = currentSelected.findIndex(column => column == item)

  if (idx === -1) {
    currentSelected.push(item)
    emits('update:selected', currentSelected)
    return
  }

  currentSelected.splice(idx, 1)
  emits('update:selected', currentSelected)
}
</script>
<template>
  <div :class="`column-filter-wrapper-${componentId}`">
    <b-button
      :id="`btn-filter-${componentId}`"
      v-tippy="{ content: 'Filter Kolom' }"
      variant="default"
      class="ml-0 ml-sm-2 btn-sm-block mt-2 mt-sm-0"
      data-target="filter-wrapper"
      @click="showFilter"
    >
      <i class="fad fa-filter"></i>&nbsp;
      Filter
    </b-button>
    <div
      class="filter-overlay-wrapper"
      @click="hideFilter"
    >
      <div class="filter-content-wrapper">
        <div class="filter-wrapper">
          <a
            v-for="(column, idx) in localColumns"
            :key="idx"
            @click="handleClickColumnFilter(column.item)"
          >
            <i
              :class="{
                'fas fa-check-square text-primary': localSelected?.includes(column.item),
                'fas fa-square text-primary opacity-20': !localSelected?.includes(column.item)
              }"
            >
            </i>
            <span class="text-dark">{{ column.name }}</span>
          </a>
          <a class="apply-filter" @click="hideFilter">
            <span class="text-dark span-apply-filter" @click="hideFilter">
              Terapkan Filter
            </span>
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<style lang="scss">
.filter-overlay-wrapper {
  display: none;
  position: absolute;
  width: 100%;
  height: 100%;
  background-color: transparent !important;
  top: 0;
  left: 0;
  z-index: 5;

  .filter-content-wrapper {
    position: relative;

    .filter-wrapper {
      position: absolute;
      flex-direction: column;
      background-color: #fff;
      border: 1px solid var(--border-primary-light);
      border-radius: 8px;
      box-shadow: 0 0 15px 1px rgba(90, 80, 105, 0.2);
      width: fit-content;
      z-index: 6;

      a {
        cursor: pointer;
        padding: .425rem 1rem;
        display: flex;
        align-items: center;
        gap: .625rem;

        &.apply-filter {
          font-weight: 600;
          border-bottom-left-radius: 8px;
          border-bottom-right-radius: 8px;

          > span {
            &.span-apply-filter {
              width: 100%;
              text-align: center;
            }
          }
        }

        &:first-child {
          margin-top: .525rem;
        }

        &:last-child {
          background-color: var(--bg-primary-light);
          margin-bottom: 0;
        }

        &:hover {
          background-color: var(--bg-primary-light);

          &.apply-filter {
            span {
              color: var(--theme-primary) !important;
            }
          }
        }
      }
    }
  }
}
</style>
