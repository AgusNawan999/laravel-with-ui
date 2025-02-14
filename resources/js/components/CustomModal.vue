<script setup>
import { computed } from 'vue'
import Spinner from '@/js/components/Spinner.vue'

const props = defineProps({
  visible: {
    type: Boolean,
    required: true,
    default: false
  },
  title: {
    type: [String, null],
    required: false,
  },
  subtitle: {
    type: [String, null],
    required: false,
  },
  icon: {
    type: [String, null],
    required: false,
    default: 'fad fa-info',
  },
  hideIcon: {
    type: Boolean,
    required: false,
    default: false
  },
  isLoading: {
    type: Boolean,
    required: false,
    default: false
  },
  size: {
    type: [String, null],
    required: false,
    default: ' '
  },
  submitText: {
    type: String,
    required: false,
    default: 'Simpan'
  },
  submitIcon: {
    type: String,
    required: false,
    default: 'fad fa-paper-plane'
  },
  cancelText: {
    type: String,
    required: false,
    default: 'Batal'
  },
  cancelIcon: {
    type: String,
    required: false,
    default: ''
  },
})

const emits = defineEmits(['update:visible', 'hide', 'submit', 'shown'])
const localVisible = computed(() => props.visible)

const onHandleHide = () => {
  emits('update:visible', false)
  emits('hide')
}

const onHandleSubmit = () => emits('submit')
const onHandleShown = () => emits('shown')

</script>

<template>
  <b-modal
    v-model:visible="localVisible"
    :size="props.size"
    header-class="border-0 pb-0"
    footer-class="border-0 pt-0 pb-4"
    content-class="whitespace-modal-content"
    no-close-on-backdrop
    no-close-on-esc
    @shown="onHandleShown"
    @hide="onHandleHide"
  >
    <template #modal-header="{ hide }">
      <div class="w-100 d-flex p-3 pb-0 justify-content-between">
        <div class="d-flex header-whitespace align-items-center">
          <div v-if="!props.hideIcon" class="header-icon-with-bg d-flex align-items-center justify-content-center">
            <i :class="`${props.icon} fa-fw`"></i>
          </div>
          <div class="title-group">
            <h4 class="lead p-0 mb-0 header-title">{{ props.title }}</h4>
            <div v-if="props.subtitle" class="text-muted header-subtitle">{{ props.subtitle }}</div>
          </div>
        </div>
        <i class="header-close-icon fal fa-times text-muted" @click="hide"></i>
      </div>
    </template>
    <template #default>
      <Spinner v-if="isLoading" />
      <div v-else class="px-3">
        <slot></slot>
      </div>
    </template>
    <template #modal-footer="{ hide }">
      <div class="w-100 d-flex px-3">
        <slot name="footer" v-bind="{ hide }">
          <div class="ml-auto">
            <b-button
              variant="default"
              class="mr-2"
              @click="hide"
            >
              <i v-if="props.cancelIcon.length" :class="`${props.cancelIcon} mr-1`"></i>
              {{ props.cancelText }}
            </b-button>
            <b-button
              variant="primary"
              @click="onHandleSubmit"
            >
              <i :class="`${ props.submitIcon } mr-1`"></i>
              {{ props.submitText }}
            </b-button>
          </div>
        </slot>
      </div>
    </template>
  </b-modal>
</template>

<style lang="scss">
.modal {
  .modal-dialog {
    .modal-content {
      &.whitespace-modal-content {
        border-radius: .625rem;

        .alert {
          button {
            &.close {
              font-size: .825rem;
            }
          }
        }
      }

      header {
        &.modal-header {
          .header-whitespace {
            .header-icon-with-bg {
              padding: 1.125rem 1rem;
              border-radius: 8px;
              background-color: #f7f2ff;
              margin-right: .825rem;

              i {
                &.fad {
                  transform: scale(1.5);
                }
              }
            }

            .header-title {
              display: -webkit-box;
              -webkit-line-clamp: 2;
              -webkit-box-orient: vertical;
              overflow: hidden;
            }

            .header-subtitle {
              display: block;
              width: 350px;
              overflow: hidden;
              white-space: nowrap;
              text-overflow: ellipsis;
            }

            @media (max-width: 515px) {
              .header-subtitle {
                width: 300px;
              }
            }

            @media (max-width: 465px) {
              .header-subtitle {
                display: none;
              }
            }
          }

          .header-close-icon {
            &.fal {
              cursor: pointer;
              transform: scale(1.725) translateY(.7rem);
            }
          }
        }
      }
    }
  }
}
</style>
