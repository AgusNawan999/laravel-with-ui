<script setup>
import { computed } from 'vue'
const props = defineProps({
  modelValue: {
    type: [Object, null],
    required: true,
    default: () => {}
  },
  items: {
    type: Array,
    required: true,
    default: () => []
  },
  loading: {
    type: Boolean,
    required: false,
    default: false
  },
  label: {
    type: String,
    required: false,
    default: ''
  },
  itemKey: {
    type: String,
    required: false,
    default: 'key'
  },
  itemValue: {
    type: String,
    required: false,
    default: 'value'
  },
  class: {
    type: [String, Object, Array],
    required: false,
    default: ''
  }
})

const emits = defineEmits(['update:modelValue', 'selected'])
const componentId = Math.random().toString(36).substring(2, 9)
const localItems = computed(() => props.items)
const localLoading = computed(() => props.loading)
const localSelected = computed(() => props.modelValue)

const onHandleSelectedItem = item => {
  emits('update:modelValue', item)
  emits('selected', item)
}
</script>

<template>
  <b-dropdown
    :id="`dropdown-filter-${componentId}`"
    variant="default"
    :class="[
      {
        'dropdown-status mt-2 mt-md-0 form-control-height-sm': true,
        'disabled': localLoading,
      },
      props.class
    ]"
  >
    <template #button-content>
      <span v-if="localLoading" class="text-muted">
        <i class="fad fa-circle-notch fa-spin"></i>&nbsp;
        Sedang memuat...
      </span>
      <span v-else>{{ props.label }} {{ localSelected[props.itemValue] }}</span>
    </template>
    <b-dropdown-item
      v-for="item in localItems"
      :key="item[props.itemKey]"
      link-class="btn"
      @click.prevent="onHandleSelectedItem(item)"
    >
      <div class="d-flex align-items-baseline">
        <i :class="{ 'fas fa-dot-circle text-primary mr-2': item[props.itemKey] == localSelected[props.itemKey] }"></i>
        <i :class="{ 'fas fa-circle text-primary opacity-20 mr-2': item[props.itemKey] != localSelected[props.itemKey] }"></i>
        <span>{{ item[props.itemValue] }}</span>
      </div>
    </b-dropdown-item>
  </b-dropdown>
</template>
