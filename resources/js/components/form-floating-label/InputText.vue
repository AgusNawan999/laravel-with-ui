<script setup>
import { ref, computed } from 'vue'
import { _ } from '@/js/utils/common'
import { vMaska } from 'maska'

const props = defineProps({
  text: {
    type: [String, null],
    required: true
  },
  label: {
    type: String,
    required: true
  },
  errorMessage: {
    type: [String, null],
    required: false,
    default: null
  },
  maxlength: {
    type: Number,
    required: false,
    default: 200
  },
  fontScale: {
    type: String,
    required: false,
    default: '0.85'
  },
  inputType: {
    type: String,
    required: false,
    default: 'text'
  }
})

const emits = defineEmits(['update:text'])

const componentId = Math.random().toString(36).substring(2, 9)
const elHeight = ref(26)
const instance = ref(null)
const inputText = ref(props.text || null)
const showFloatingLabel = ref(false)
const localErrorMessage = computed(() => props.errorMessage)
const inputType = computed(() => props.inputType === 'text' ? true : false)

const doUpdate = _.debounce((e) => emits('update:text', e.target.value), 250)
</script>

<template>
  <div
    :id="`${componentId}-textarea`"
    class="textarea-for-form-floating-label"
    :style="{ '--fontScale': props.fontScale }"
  >
    <div
      :class="{
        'form-floating': true,
        'is-floating': showFloatingLabel || inputText,
        'is-error': errorMessage,
      }"
    >
      <label
        :for="`${componentId}-input-text`"
        :class="{
          'label-input-text': true,
          'is-floating': showFloatingLabel || inputText
        }"
      >
        {{ props.label }}
      </label>
      <input
        v-if="inputType"
        :id="`${componentId}-input-text`"
        ref="instance"
        v-model="inputText"
        type="text"
        :maxlength="props.maxlength"
        class="form-control"
        :style="{ height: `${elHeight}px` }"
        @focus="showFloatingLabel = true"
        @blur="showFloatingLabel = false"
        @input="doUpdate"
      />
      <input
        v-else
        :id="`${componentId}-input-text`"
        ref="instance"
        v-model="inputText"
        v-maska
        data-maska="08## #### #### ###"
        type="text"
        :maxlength="props.maxlength"
        class="form-control"
        :style="{ height: `${elHeight}px` }"
        @focus="showFloatingLabel = true"
        @blur="showFloatingLabel = false"
        @input="doUpdate"
      />
    </div>
    <div class="d-flex helper-message justify-content-around">
      <div class="flex-1 d-flex align-items-baseline mt-1">
        <i v-if="localErrorMessage" class="fad fa-exclamation-triangle text-danger fa-sm mr-1"></i>
        <span class="flex-1 fs-sm text-danger">
          {{ localErrorMessage }}
        </span>
      </div>
    </div>
  </div>
</template>

<style lang="scss">
.textarea-for-form-floating-label {
  .form-floating {
    position: relative;
    border-bottom: 1px solid #ced4da;

    &.is-floating {
      margin-top: 1.15rem;
    }

    &.is-error {
      border-bottom-color: var(--theme-danger-200);
    }

    input {
      resize: none;
      border: 0;
      padding: 0;
      border-radius: 0;
      overflow: hidden;
      color: rgb(33, 37, 41);

      &:focus {
        box-shadow: none;
      }
    }

    .label-input-text {
      top: 0px;
      left: 0px;
      position: absolute;
      transition: opacity .1s ease-in-out, transform .1s ease-in-out;

      &.is-floating {
        opacity: .65;
        transform: scale(var(--fontScale)) translateY(-1.525rem);
      }
    }
  }
}
</style>
