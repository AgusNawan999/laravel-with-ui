<script setup>
import { ref, computed, watch } from 'vue'

const props = defineProps({
  selected: {
    type: [Object, null],
    required: true,
  },
  label: {
    type: String,
    required: true,
  },
  multiselectOption: {
    type: Object,
    required: true,
    default: () => {}
  },
  errorMessage: {
    type: [String, null],
    required: false,
    default: null
  },
  loading: {
    type: Boolean,
    required: false,
    default: false
  }
})
const emits = defineEmits(['select', 'remove', 'update:selected'])
const componentId = Math.random().toString(36).substring(2, 9)
const selectedItem = ref(props.selected || null)
const showFloatingLabel = ref(false)
const hasOpen = ref(false)
const hasSelected = computed(() => {
  if (Array.isArray(selectedItem.value))
    return selectedItem.value.length

  return selectedItem.value
})
const localErrorMessage = computed(() => props.errorMessage)
const isLoading = computed(() => props.loading)

const onHandleSelectOrRemove = (selectedOption, id, event) => emits(event, selectedOption, id)
const onHandleOpenOrClose = (value) => {
  showFloatingLabel.value = value
  hasOpen.value = value
}

watch(selectedItem, (n) => emits('update:selected', n))
</script>

<template>
  <div :id="`${componentId}-select`" class="select-for-form-floating-label">
    <div
      :class="{
        'form-floating': true,
        'is-floating': showFloatingLabel || hasSelected,
        'is-error': errorMessage,
        'cursor-wait': isLoading
      }"
    >
      <vue-multiselect
        :id="`${componentId}-multiselect`"
        v-bind="multiselectOption"
        v-model:modelValue="selectedItem"
        :disabled="isLoading"
        @open="onHandleOpenOrClose(true)"
        @close="onHandleOpenOrClose(false)"
        @select="(selectedOption, id) => onHandleSelectOrRemove(selectedOption, id, 'select')"
        @remove="(selectedOption, id) => onHandleSelectOrRemove(selectedOption, id, 'remove')"
      >
        <template
          v-for="slot in Object.keys($slots)"
          #[slot]="scopeSlot"
        >
          <slot v-if="scopeSlot" :name="slot" v-bind="scopeSlot"></slot>
        </template>
      </vue-multiselect>
      <label
        :class="{
          'label-select': true,
          'is-floating': showFloatingLabel || hasSelected,
          'open-options': hasOpen,
        }"
      >
        <span v-if="isLoading" class="text-muted">
          <i class="fad fa-spinner-third fa-spin text-muted mr-1"></i>
          sedang memuat data...
        </span>
        <span v-else>{{ props.label }}</span>
      </label>
    </div>
    <div v-if="localErrorMessage" class="error-message fs-sm text-danger">
      <div class="d-flex align-items-baseline">
        <i class="fad fa-exclamation-triangle text-danger fa-sm mr-1"></i>
        <span class="flex-1">
          {{ localErrorMessage }}
        </span>
      </div>
    </div>
  </div>
</template>

<style lang="scss">
.select-for-form-floating-label {
  .form-floating {
    position: relative;
    margin-top: -0.675rem;
    border-bottom: 1px solid #ced4da;

    & > label {
      position: absolute;
      top: 0;
      left: 0;
      height: 100%;
      padding: 1rem 0.75rem;
      pointer-events: none;
      border: 1px solid transparent;
      transform-origin: 0 0;
      transition: opacity .1s ease-in-out, transform .1s ease-in-out;
    }

    &.is-floating {
      margin-top: 0rem;
      margin-bottom: 0.675rem;
    }

    .multiselect {
      min-height: 30px;
      &.multiselect--disabled {
        .multiselect__select {
          background: transparent;

          &::before {
            opacity: 0;
          }
        }
      }

      .multiselect__select {
        &::before {
          opacity: 0;
        }
      }

      .multiselect__tags {
        border: 0;
        padding: 0;
        border-radius: 0;

        .multiselect__tags-wrap {
          .multiselect__tag {
            margin-top: 1rem;
            margin-bottom: 0;
          }
        }

        .multiselect__single {
          margin-top: 0.675rem;
          color: rgb(33, 37, 41);
        }

        .multiselect__placeholder {
          opacity: 0;
        }
      }
    }

    .label-select {
      position: absolute;
      top: -12px;
      left: -14px;

      &.is-floating {
        opacity: .65;
        transform: scale(.85) translateY(-0.675rem) translateX(0.125rem);
        &.open-options {
          z-index: 50;
        }
      }
    }

    &.is-error {
      border-bottom-color: var(--theme-danger-200);
    }
  }
}
</style>
