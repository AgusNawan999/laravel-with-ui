<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import ComplaintComponent from '@/js/modules/pengaduan/Index.vue'

const showModal = ref(false)
const isMdScreen = ref(false)

// Periksa kembali saat terjadi perubahan lebar layar
const handleResize = () => isMdScreen.value = window.innerWidth < 992

const onModal = () => showModal.value = true

onMounted(() => {
  handleResize()
  window.addEventListener('resize', handleResize)

  const hasPendingComplaint = sessionStorage.getItem('pending-complaint')
  if (!hasPendingComplaint) return

  console.log({ hasPendingComplaint })
  onModal()
})

onBeforeUnmount(() => window.removeEventListener('resize', handleResize))
</script>

<template>
  <div :class="isMdScreen ? 'text-center' : 'mt-0'">
    <h3 class="subtitle-prefix">Portal</h3>
    <h1 class="text-color-utama title t-n18">Layanan TIK</h1>
    <h1 class="title t-n48">DKI Jakarta</h1>
    <div class="content-wrapper">
      <div :class="{ 'd-flex justify-content-center': isMdScreen }">
        <p class="description">
          Selamat datang di portal elektronik layanan teknologi informasi dan komunikasi Pemerintah Provinsi DKI Jakarta.
        </p>
      </div>
      <div class="mt-4" :class="isMdScreen ? 'text-center' : 'd-flex'">
        <button class="btn btn-sm btn-inpage-light mr-2" @click.prevent="onModal">
          <span>Pengaduan</span>
        </button>
      </div>
    </div>

    <ComplaintComponent v-model:visible="showModal" />
  </div>
</template>

<style lang="scss">
.title {
  position: relative;
  font-size: 70px;
  font-weight: 900;
}

.subtitle-prefix {
  font-weight: 700;
  margin-bottom: 0;
  font-size: 40px;
}

.content-wrapper {
  position: relative;
  top: -63px;
  margin-left: 5px;

  p {
    &.description {
      max-width: 400px;
    }
  }
}

.t-n18 {
  top: -18px;
}

.t-n48 {
  top: -48px;
}
</style>
