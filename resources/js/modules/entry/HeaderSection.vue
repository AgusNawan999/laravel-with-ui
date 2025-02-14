<template>
  <div class="container-fluid landing-page">
    <!-- header / navbar -->
    <div class="row header pb-3">
      <div class="col-12 mt-5">
        <div class="row">
          <div :class="isMdScreen ? 'col-md-5 text-center' : 'col-md-5 padding-kontainer-kiri'">
            <img
              src="@/images/logo_diskominfotik.png" height="50" alt="" style="cursor: pointer;" @click.prevent="handleHome()">
          </div>
          <div class="col-md-7 rounded-left-cust d-flex justify-content-right align-items-center text-white bg-utama font-weight-bold padding-kontainer-kanan pb-2 pt-2 drop-shadow-cust" :class="isMdScreen ? 'mt-3' : ''">
            <button type="button" class="btn btn-sm btn-no-outline-dark ml-1 pl-2 pr-2" @click="handleTentangLayanan">TENTANG LAYANAN TIK</button>
            <div class="right-section ml-auto">
              <template v-if="settings.loggedInUser">
                <button
                  type="button"
                  class="btn btn-sm btn-no-outline-dark mr-2"
                  @click="handleToProfile"
                >
                  PROFIL
                </button>
                <button
                  v-if="canAccessAdminHome"
                  type="button"
                  class="btn btn-sm btn-no-outline-dark mr-2"
                  @click="handleToAdmin"
                >
                  HALAMAN ADMIN
                </button>
                <a
                  class="btn btn-sm btn-cust-dark text-white no-decoration"
                  href="javascript:void(0)"
                  onclick="doLogout.apply(this, arguments)"
                >
                  LOGOUT
                </a>
              </template>
              <template v-else>
                <button type="button" class="btn btn-sm btn-no-outline-dark mr-2" @click="loginUser">LOGIN</button>
                <button type="button" class="btn btn-sm btn-cust-dark text-white" @click="daftarUser">DAFTAR</button>
              </template>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- header / navbar end -->
  </div>
</template>

<script>
    export default {
        components: {
        },
        data() {
          return {
            isMdScreen: false,
            settings: window.settings
          };
        },
        computed: {
          canAccessAdminHome() {
            if(this.settings.user){
              return this.settings.user.permissions.includes(this.settings.user.admin_home_permission)
            }
            return null
          }
        },
        created() {
          // Periksa saat komponen dibuat jika layar merupakan ukuran md
          this.isMdScreen = window.innerWidth < 992;

          // Tambahkan event listener untuk memantau perubahan lebar layar
          window.addEventListener('resize', this.handleResize);
        },
        mounted() {

        },
        unmounted() {
          // Hapus event listener saat komponen dihancurkan
          window.removeEventListener('resize', this.handleResize);
        },
        methods: {
          handleResize() {
            // Periksa kembali saat terjadi perubahan lebar layar
            this.isMdScreen =  window.innerWidth < 992;
          },
          loginUser(){
            // this.$router.push('/login');
            window.location.href = '/login';
          },
          daftarUser(){
            this.$router.push('/daftar');
          },
          handleToAdmin() {
            window.location.href = '/admin'
          },
          handleToProfile() {
            this.$router.push({ name: 'landing.profile' });
          },
          handleTentangLayanan(){
            this.$router.push('/layanan');
          },
          handleHome(){
            this.$router.push('/');
          },
        },

    }
</script>

<style lang="scss">
a {
  &.no-decoration {
    text-decoration: none !important;
    color: var(--theme-light) !important;
  }
}
</style>
