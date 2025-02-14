<!-- eslint-disable vue/no-v-html -->
<template>
  <HeaderSection class="mt-1"></HeaderSection>
  <div class="landing-page">
    <div class="container-fluid">
      <!-- konten 1 -->
      <div class="row content1">
        <div class="pt-3 pb-3 col-12">
          <div class="row">
            <!-- konten 1 sub 1 -->
            <div class="content1sub1" :class="isMdScreen ? 'col-md-5 pt-5' : 'col-md-5 pt-5 padding-kontainer-kiri'">
              <SideContent></SideContent>
            </div>
            <!-- konten 1 sub 1 end -->

            <!-- konten 1 sub 2 -->
            <div class="content1sub2" :class="isMdScreen ? 'col-md-7 pt-3' : 'col-md-7 pt-3 padding-kontainer-kanan'">
              <router-view></router-view>
            </div>
            <!-- konten 1 sub 2 end -->
          </div>
        </div>
      </div>
      <!-- konten 1 end-->

      <!-- konten 2 -->
      <div class="row content2 drop-shadow-cust">
        <div class="pt-4 pb-4 col-12 bg-utama">
          <Timeline></Timeline>
        </div>
      </div>
      <!-- konten 2 end -->

      <!-- sticky Menu -->
      <StickyMenu></StickyMenu>
      <!-- sticky Menu End -->
    </div>
  </div>
  <FooterSection></FooterSection>
</template>

<script setup>
  import { ref, onMounted, onUnmounted } from 'vue'
  import HeaderSection from "./HeaderSection.vue"
  import FooterSection from "./FooterSection.vue"
  import Timeline from "./_parts/Timeline.vue"
  import SideContent from "./_parts/SideContent.vue"
  import StickyMenu from "./_parts/StickyMenu.vue"


  const isMdScreen = ref(false)

  onMounted(() => {
    // Periksa saat komponen dibuat jika layar merupakan ukuran md
    isMdScreen.value = window.innerWidth < 992;

    // Tambahkan event listener untuk memantau perubahan lebar layar
    window.addEventListener('resize', handleResize);
  })
  onUnmounted(() => {
    // Hapus event listener saat komponen dihancurkan
    window.removeEventListener('resize', handleResize);
  })

  const handleResize = () => {
    // Periksa kembali saat terjadi perubahan lebar layar
    isMdScreen.value =  window.innerWidth < 992;
  }
</script>



