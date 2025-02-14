<script setup>
import { ref, computed, watchEffect } from 'vue'
import { _ } from '@/js/utils/common'

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
  countCharacter: {
    type: Boolean,
    required: false,
    default: false
  }
})

const emits = defineEmits(['update:text'])

const componentId = Math.random().toString(36).substring(2, 9)
const elHeight = ref(26)
const instance = ref(null)
const inputText = ref(props.text || null)
const showFloatingLabel = ref(false)
const localErrorMessage = computed(() => props.errorMessage)

const onHandleInput = (e) => {
  doUpdate(e.target.value)

  if (!inputText.value) {
    elHeight.value = 26
    return
  }

  elHeight.value = instance.value.scrollHeight
}

const doUpdate = _.debounce((text) => emits('update:text', text), 250)
const unwatch = watchEffect(() => {
  if (!inputText.value || !instance.value) return

  setTimeout(() => {
    // dispatch event
    instance.value.dispatchEvent(new Event('input'))

    // stop and clear watcher
    unwatch()
  }, 100)
})
</script>

<template>
  <div :id="`${componentId}-textarea`" class="textarea-for-form-floating-label">
    <div
      :class="{
        'form-floating': true,
        'is-floating': showFloatingLabel || inputText,
        'is-error': errorMessage,
      }"
    >
      <label
        :for="`${componentId}-textarea-input`"
        :class="{
          'label-textarea': true,
          'is-floating': showFloatingLabel || inputText
        }"
      >
        {{ props.label }}
      </label>
      <textarea
        :id="`${componentId}-textarea-input`"
        ref="instance"
        v-model="inputText"
        :maxlength="props.maxlength"
        class="form-control"
        :style="{ height: `${elHeight}px` }"
        @focus="showFloatingLabel = true"
        @blur="showFloatingLabel = false"
        @input="onHandleInput"
        @change="onHandleInput"
      ></textarea>
    </div>
    <div class="d-flex helper-message justify-content-around">
      <div class="flex-1 d-flex align-items-baseline mt-1">
        <i v-if="localErrorMessage" class="fad fa-exclamation-triangle text-danger fa-sm mr-1"></i>
        <span class="flex-1 fs-sm text-danger">
          {{ localErrorMessage }}
        </span>
      </div>

      <Transition name="slide-fade">
        <span
          v-if="showFloatingLabel && props.countCharacter && inputText"
          class="fs-sm text-muted text-right mt-1"
        >
          {{ inputText && inputText.length }} / {{ props.maxlength }}
        </span>
      </Transition>
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

    textarea {
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

    .label-textarea {
      top: 0px;
      left: 0px;
      position: absolute;
      transition: opacity .1s ease-in-out, transform .1s ease-in-out;

      &.is-floating {
        opacity: .65;
        transform: scale(.85) translateY(-1.525rem) translateX(-0.825rem);
      }
    }
  }
}
</style>
