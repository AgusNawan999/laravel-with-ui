<template>
  <section class="image-editor">
    <label v-if="withIcon" :for="idInput" class="change-icon">
      <i class="fas fa-camera"></i>
      <slot></slot>
    </label>
    <b-modal
      id="modal-image"
      ref="modalRef"
      :size="size"
      hide-header
      no-close-on-esc
      no-close-on-backdrop
      @hidden="reset"
    >
      <template v-if="isLoading && !done">
        <Spinner />
      </template>
      <template v-else>
        <div class="d-flex flex-column align-items-center justify-content-center">
          <VueCropper
            ref="cropTarget"
            class="img-fluid"
            :src="outputURL"
            :aspect-ratio="aspectRatio"
            :auto-crop-area="autoCropArea"
          >
          </VueCropper>
        </div>
      </template>
      <template #modal-footer="{ cancel }">
        <b-button variant="secondary" :disabled="isLoading" @click="cancel()">
          <i class="far fa-times"></i>{{ ' Cancel' }}
        </b-button>
        <b-button variant="primary" :disabled="isLoading" @click="onSubmit">
          <template v-if="isLoading">
            <b-spinner small variant="light" label="Spinning"></b-spinner>
          </template>
          <template v-else><i class="far fa-upload"></i>{{ ' Upload' }}</template>
        </b-button>
      </template>
    </b-modal>
    <div
      v-if="lightbox.media.length > 0">
      <vue-easy-lightbox
        :visible="lightbox.visible"
        :imgs="lightbox.media"
        :index="lightbox.index"
        @hide="onHide"
      >
        <template #close-btn="{ close }">
          <div class="float-right cursor-pointer p-2 text-light container-button container-close" @click="close"><i class="far fa-times fa-2x"></i></div>
        </template>

        <template #prev-btn="{ prev }">
          <div class="float-left cursor-pointer p-2 text-light container-button" style="position: relative;top: 50vh !important;" @click="prev"><i class="far fa-angle-left fa-2x"></i></div>
        </template>
        <template #next-btn="{ next }">
          <div class="float-right cursor-pointer p-2 text-light container-button" style="position: relative;top: 50vh !important;" @click="next"><i class="far fa-angle-right fa-2x"></i></div>
        </template>

        <template #toolbar="{ toolbarMethods }">
          <div class="toolbar-wrapper">
            <div
              class="container-button d-inline-block p-2 toolbar">
              <div @click="toolbarMethods.zoomIn"><i class="ni ni-magnifier-add fa-2x"></i></div>
            </div>
            <div
              class="container-button d-inline-block p-2 toolbar">
              <div @click="toolbarMethods.zoomOut"><i class="ni ni-magnifier-remove fa-2x"></i></div>
            </div>
            <div
              class="container-button d-inline-block p-2 toolbar">
              <div @click="toolbarMethods.resize"><i class="ni ni-size-actual fa-2x"></i></div>
            </div>
            <div
              class="container-button d-inline-block p-2 toolbar">
              <div @click="toolbarMethods.rotateLeft"><i class="far fa-undo fa-2x"></i></div>
            </div>
            <div
              class="container-button d-inline-block p-2 toolbar">
              <div @click="toolbarMethods.rotateRight"><i class="far fa-redo fa-2x"></i></div>
            </div>
          </div>
        </template>
      </vue-easy-lightbox>
      <div class="images-wrapper">
        <img
          v-for="(image, imageIndex) in lightbox.media"
          :key="imageIndex"
          class="image mb-3 mt-2"
          height="70"
          width="70"
          style="cursor: pointer;filter: drop-shadow(5px 5px 5px rgba(0,0,0,0.1));"
          :src="image"
          @click="showImg(index)"
        >
      </div>
    </div>
    <b-form-file
      :id="idInput"
      ref="input"
      :accept="acceptInput"
      :state="stateInput"
      type="file"
      :placeholder="placeholderInput"
      :drop-placeholder="dropPlaceholderInput"
      :disabled="disabledInput"
      @change="onChange"
    ></b-form-file>
  </section>
</template>

<script>
import Compressor from 'compressorjs'
import VueCropper from 'vue-cropperjs'
import 'cropperjs/dist/cropper.css'
import { _http, _route, _alert } from '@/js/utils/common'
import Spinner from '@/js/components/Spinner.vue'

// import { reverseGeocode } from 'esri-leaflet-geocoder'

let URL = window.URL || window.webkitURL

export default {
  name: 'ImageUploader',
  components: { VueCropper,Spinner },
  props: {
    idInput: {
      type: String,
      required: false,
      default: '',
    },
    disabledInput: {
      type: Boolean,
      required: false,
      default: false,
    },
    acceptInput: {
      type: String,
      required: false,
      default: '',
    },
    stateInput: {
      type: Boolean,
      required: false,
      default: null,
    },
    placeholderInput: {
      type: String,
      required: false,
      default: 'Pilih File',
    },
    dropPlaceholderInput: {
      type: String,
      required: false,
      default: 'Letakan file disini',
    },
    mediaLightbox: {
      type: Array,
      required: false,
      default: () => [],
    },
    withIcon: {
      type: Boolean,
      required: false,
      default: false,
    },
    quality: {
      type: String,
      required: false,
      default: 'med',
      description: 'low|medium|high'
    },
    aspectRatio: {
      type: Number,
      required: false,
      default: () => 1 / 1
    },
    autoCropArea: {
      type: Number,
      required: false,
      default: 0.5
    },
    size: {
      type: String,
      required: false,
      default: ' '
    },
    maxWidth: {
      type: Number,
      required: false,
      default: 1024
    },
    minWidth: {
      type: Number,
      required: false,
      default: 32
    },
    strict: {
      type: Boolean,
      required: false,
      default: true
    },
    checkOrientation: {
      type: Boolean,
      required: false,
      default: true
    },
    width: {
      type: Number,
      required: false,
      default: undefined
    },
    height: {
      type: Number,
      required: false,
      default: undefined
    },
    mimeType: {
      type: String,
      required: false,
      default: ''
    },
    convertSize: {
      type: Number,
      required: false,
      default: 1024000
    },
    useWatermark: {
      type: Boolean,
      required: false,
      default: false,
    },
    watermark: {
      type: Object,
      required: false,
      default: () => {}
    },
    onlyFillText: {
      type: Boolean,
      required: false,
      default: false,
    },
  },
  data() {
    let vm = this
    return {
      lightbox:{
        visible: null,
        index: 0,
        media: [],
      },
      options: {
        strict: vm.strict,
        checkOrientation: vm.checkOrientation,
        maxWidth: vm.maxWidth,
        maxHeight: vm.getMaxHeight,
        minWidth: vm.minWidth,
        minHeight: vm.getMinHeight,
        width: vm.width,
        height: vm.height,
        quality: vm.getQuality,
        mimeType: vm.mimeType,
        convertSize: vm.convertSize,
        success: vm.onSuccess,
        drew: vm.onDrew,
        error: vm.onError,
      },
      defaultWatermark: {
        fillStyle: '#fff',
        font: '2rem serif',
        fillText: '',
        left: 20,
        bottom: 20,
      },
      inputURL: '',
      outputURL: '',
      input: {},
      output: {},
      isLoading: false,
      done: false,
      latLng: {
        lat: -6.2237176,
        lng: 106.7364678,
      },
      address: {},
      timestamp: '',
      fillText: '',
    }
  },
  computed: {
    getQuality() {
      let vm = this
      let quality = vm.quality.toLowerCase()
      if (quality == 'low') {
        return 0.2
      } else if (quality == 'high') {
        return 0.6
      } else {
        return 0.4
      }
    },
    getMaxHeight() {
      return this.aspectRatio != 'free'
        ? this.aspectRatio * this.maxWidth
        : this.maxWidth
    },
    getMinHeight() {
      return this.aspectRatio != 'free'
        ? this.aspectRatio * this.minWidth
        : this.minWidth
    }
  },
  watch:{
    mediaLightbox: {
      immediate: true,
      handler(n) {
        this.lightbox.media = n
      }
    }
  },
  beforeUnmount() {
    if (this.$refs.cropTarget) this.$refs.cropTarget.destroy()
  },

  methods: {
    compress: function (file) {
      if (!file) return
      if (URL) this.inputURL = URL.createObjectURL(file)

      this.input = file
      let newOptions = window._.merge({ quality: this.getQuality }, this.options)
      new Compressor(file, newOptions)
    },
    onChange(e) {
      let vm = this
      if (e.target.files.length > 0){
        if (! URL) return
        this.output = e.target.files[0]
        if (this.output.type.includes('image')) {
          this.outputURL = URL.createObjectURL(e.target.files[0])
          this.$refs.modalRef.show('modal-image')
        } else {
          _alert.error('File gambar tidak valid')
        }
      }else{
        if(this.mediaLightbox.length > 0){
          this.lightbox.media = this.mediaLightbox
        }else{
          this.lightbox.media = []
          this.lightbox.visible = false
        }
      }
      vm.$emit('change', e)

    },
    onSuccess(result) {
      this.isLoading = false
      if (URL) this.outputURL = URL.createObjectURL(result)
      this.output = result
      this.onProcess()
    },
    onDrew(context, canvas) {
      let vm = this
      if (! vm.useWatermark) return
      let prop = Object.assign({}, vm.defaultWatermark, vm.watermark)
      context.fillStyle = prop.fillStyle
      context.font = prop.font
      if (! vm.onlyFillText) {
        context.fillText(prop.fillText, prop.left, canvas.height - (prop.bottom + 120))
        context.fillText(vm.timestamp, prop.left, canvas.height - (prop.bottom + 80))
        context.fillText(vm.address.ShortLabel, prop.left, canvas.height - (prop.bottom + 40))
        context.fillText(
          `${ vm.address.City }, ${ vm.address.Subregion }, ${ vm.address.Region }, ${ vm.address.Postal }`,
          prop.left,
          canvas.height - prop.bottom
        )
      } else {
        context.fillText(prop.fillText, prop.left, canvas.height - prop.bottom)
      }
    },
    onError(err) {
      _alert.error(err.message)
    },
    async getTimestamp() {
      let vm = this
      const timestamp = await _http.get(_route('timestamp'))
      vm.timestamp = timestamp.data
    },
    reset() {
      let vm = this
      vm.input = {}
      vm.output = {}
      vm.inputURL = ''
      vm.outputURL = ''
      vm.isLoading = false
      vm.options.quality = 0.6
      vm.done = false
    },
    onSubmit() {
      let vm = this
      vm.done = true
      vm.isLoading = true
      vm.$refs.cropTarget.disable()
      vm.getTimestamp()
      vm.$refs.cropTarget.getCroppedCanvas().toBlob(result => {
        result.name = vm.output.name
        result.lastModified = vm.output.lastModified
        result.lastModifiedDate = vm.output.lastModifiedDate
        vm.compress(result)
      })
    },
    onProcess() {
      let vm = this
      const data = {
        input: vm.input,
        output: vm.output,
        inputURL: vm.inputURL,
        outputUrl: vm.outputURL,
      }


      vm.$emit('process', data)
      let reader = new FileReader()
      reader.onload = (e) => {
        this.lightbox.media = [e.target.result]
      }
      reader.readAsDataURL(vm.output)
      if (vm.done) {
        vm.$refs.modalRef.hide('modal-image')
        vm.reset()
      }
    },
    showImg(index) {
      this.lightbox.index = index
      this.lightbox.visible = true
    },
    onHide() {
      this.lightbox.visible = false
    },

  }
}
</script>

<style lang="scss">
.image-editor {
  .change-icon {
    cursor: pointer;
    i {
      position: relative;
      font-size: 1.5rem;
      top: 5px;
      left: 50%;
      opacity: 0;
    }
    .avatar {
      margin-top: 0px;
      width: 100px;
      height: 100px;
      object-fit: cover;
      object-position: center top;
    }
    &:hover {
      opacity: .8;
      i {
        opacity: 1 !important;
        z-index: 2;
      }
    }
  }
  .container-button{
    background-color: rgba($color: #000000, $alpha: 0.5);
  }
  .container-close{
    position: absolute;
    right: 0px !important;
  }

  .toolbar-wrapper{
    top: 94vh !important;
    position: absolute;
    width: 100% !important;
    display: flex;
    justify-content: center;

    .toolbar:hover{
      background-color: rgba($color: #252525, $alpha: 0.5);
      cursor: pointer;
    }
    .toolbar{
      position: relative;
      color: white !important;
    }
  }
}

</style>
