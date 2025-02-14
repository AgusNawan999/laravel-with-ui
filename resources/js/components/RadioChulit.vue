<script setup>
const props = defineProps({
  label: {
    type: String,
    default: 'Label'
  },
  useLabel: {
    type: Boolean,
    default: false
  },
  options: {
    type: Array,
    default: () => []
  }
})

const emits = defineEmits(['on-change'])
const componentId = Math.random().toString(36).substring(2, 9)
const onHandleChange = (e) => emits('on-change', e)
</script>

<template>
  <div :id="`radio-group-custom-${componentId}`">
    <div class="d-flex align-items-center">
      <div class="pr-3">{{ props.label }}</div>
      <b-form-group
        class="radio-chulit-custom"
        :class="{ 'has-label': props.useLabel }"
      >
        <div
          class="tabs"
        >
          <template v-for="(option, key) in options" :key="key">
            <input
              :id="`radio-${key}`"
              :checked="option.checked"
              type="radio"
              name="tabs"
              @change="onHandleChange"
            />
            <label
              class="tab d-flex"
              :class="{
                'justify-content-center': !props.useLabel,
                'align-items-center': props.useLabel,
                'p-2': props.useLabel
              }"
              :for="`radio-${key}`"
            >
              <div class="radio-content d-flex justify-content-center align-items-center">
                <i :class="`${option.icon}`"></i>
                <div
                  v-if="option.label && props.useLabel"
                  :title="option.label"
                  class="pl-2 label"
                >
                  {{ option.label }}
                </div>
              </div>
            </label>
          </template>
          <span class="glider"></span>
        </div>
      </b-form-group>
    </div>
  </div>
</template>

<style lang="scss">
:root {
	--active-color: var(--primary-theme);
	--inactive-color: var(--theme-fusion);
}

.radio-chulit-custom {
  .tabs {
    display: flex;
    position: relative;
    background-color: #eaeaea;
    padding: 0.35rem;
    border-radius: 4px;
    * {
      z-index: 2;
    }
  }

  input[type="radio"] {
    display: none;
  }

  .tab {
    width: 35px;
    height: 26px;
    margin-bottom: 0;
    font-weight: 500;
    border-radius: 4px;
    cursor: pointer;
    transition: color 0.15s ease-in;

    i {
      font-size: .825rem;
    }
    .label {
      font-size: .75rem;
      margin-top: 2.3px;
    }
  }

  input[type="radio"] {
    &:checked {
      & + label {
        color: var(--active-color);
      }
    }
  }

  @for $i from 0 through 10 {
    input[id="radio-#{$i}"] {
      &:checked {
        & ~ .glider {
          transform: translateX(($i*100)+0%);
        }
      }
    }
  }

  .glider {
    position: absolute;
    display: flex;
    width: 35px;
    height: 26px;
    background-color: var(--theme-light);
    z-index: 1;
    border-radius: 4px; // just a high number to create pill effect
    transition: 0.25s ease-out;
  }
}

.radio-chulit-custom {
  &.has-label {
    .tab {
      width: 93.99px;

      .label {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
      }
    }

    .glider {
      width: 93.99px;
    }
  }
}
</style>
