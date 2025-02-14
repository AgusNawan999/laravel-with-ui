<!-- eslint-disable vue/no-v-html -->
<!-- eslint-disable vue/require-explicit-emits -->
<script setup>
  import {computed, onBeforeUnmount, onMounted, reactive, ref,watch} from 'vue'
  import $ from 'jQuery'
  import 'summernote/dist/summernote-lite.css'
  import 'summernote/dist/summernote-lite.js'

  const emits = defineEmits([ 'input','update'])

  if (!window.SUMMERNOTE_DEFAULT_CONFIGS) {
    window.SUMMERNOTE_DEFAULT_CONFIGS = {
        placeholder: 'Hello stand alone ui',
        tabsize: 2,
        height: 120,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      };
  }

  const props = defineProps({
    placeholderEditor: {
      type: String,
      required: false,
      default: "Type Here...",
    },
    valueEditor: {
      type: String,
      required: false,
      default: null,
    },
    stateEditor: {
      type: Boolean,
      required: false,
      default: null,
    },
    modelValue: {
      default: null,
      required: true,
      event: "change",
      validator(value) {
        return (
          value === null || typeof value === "string" || value instanceof String
        );
      },
    },
    config: {
      type: Object,
      default: () => window.SUMMERNOTE_DEFAULT_CONFIGS,
    },
  })

  const elem = ref()
  const sumRef = ref()

  const deskripsi = computed(() => props.valueEditor)
  const placeholder = computed(() => props.placeholderEditor)

  const events = reactive({
    "summernote.change": "summernoteChange",
    "summernote.image.link.insert": "summernoteImageLinkInsert",
  })


  const onChange = () => {
    unwatch()
    const value = $(elem.value).summernote("code");
    emits("update:modelValue", value);
    $(sumRef.value).summernote(props.config)
  }

  const registerEvents = () => {
    for (var realName in events) {
      elem.value.on(`${realName}`, (...args) => {
        emits(`${events[realName]}`, ...args);
      });
    }
  }

  watch(props.modelValue, (newValue) => {
    const currValue = $(elem.value).summernote("code");
    if (newValue != currValue) {
      $(elem.value).summernote("code", newValue);
    }
  }, {immediate: true})

  const unwatch = watch(deskripsi, (newValue) => {
    $(elem.value).summernote("code", newValue);
  }, {immediate: true})


  watch(placeholder, (newValue) => {
    window.SUMMERNOTE_DEFAULT_CONFIGS.placeholder = newValue
  }, {immediate: true})


  onMounted(() => {
    elem.value = $(sumRef.value);
    elem.value.summernote(props.config);
    $(elem.value).on("summernote.change", onChange);
    if (props.modelValue) {
      $(elem.value).summernote("code", props.modelValue);
    }
      registerEvents();
  })

  onBeforeUnmount(() => {
    if (elem.value) {
      elem.value.summernote("destroy");
      elem.value = null;
    }
  })


</script>


<template>
  <div ref="sumRef"></div>
</template>


