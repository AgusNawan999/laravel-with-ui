<template>
  <div class="timeline">
    <div class="timeline-element">
      <div class="main-step-container">
        <ul class="step-list">
          <!-- <div class="line-timeline"></div> -->
          <li class="d-flex justify-content-center">
            <div class="item-timeline">
              <img src="@/images/icon_alur1.png" class="img-responsive">
              <div class="content-item">
                <span class="title-icon-timeline">Ajukan Layanan</span>
                <p class="p-icon-timeline">Pemohon ASN DKI Jakarta melakukan penginputan dan penyampaian permohonan layanan maupun pengaduan layanan beserta dokumen yang dipersyaratkan.</p>
              </div>
            </div>
          </li>
          <li class="d-flex justify-content-center">
            <div class="item-timeline">
              <img src="@/images/icon_alur2.png" class="img-responsive">
              <div class="content-item">
                <span class="title-icon-timeline">Verifikasi</span>
                <p class="p-icon-timeline">Diskominfotik memverifikasi permohonan layanan maupun pengaduan layanan dari Pemohon ASN DKI Jakarta sesuai dengan ketentuan yang berlaku.</p>
              </div>
            </div>
          </li>
          <li class="d-flex justify-content-center">
            <div class="item-timeline">
              <img src="@/images/icon_alur3.png" class="img-responsive">
              <div class="content-item">
                <span class="title-icon-timeline">Tindak Lanjut</span>
                <p class="p-icon-timeline">Tindak lanjut permohonan layanan maupun pengaduan layanan oleh Unit Penanggung Jawab.</p>
              </div>
            </div>
          </li>
          <li class="d-flex justify-content-center">
            <div class="item-timeline">
              <img src="@/images/icon_alur4.png" class="img-responsive">
              <div class="content-item">
                <span class="title-icon-timeline">Konfirmasi</span>
                <p class="p-icon-timeline">Diskominfotik menyampaikan tindak lanjut permohonan layanan maupun pengaduan gangguan kepada Pemohon ASN DKI Jakarta.</p>
              </div>
            </div>
          </li>
          <li class="d-flex justify-content-center">
            <div class="item-timeline">
              <img src="@/images/icon_alur5.png" class="img-responsive">
              <div class="content-item">
                <span class="title-icon-timeline">Selesai</span>
                <p class="p-icon-timeline">Pemohon ASN DKI Jakarta menerima hasil layanan.</p>
              </div>
            </div>
          </li>

        </ul>
      </div>

    </div>
  </div>
  <div class="d-flex justify-content-center">
    <button class="btn btn-sm btn-inpage-dark ml-2 mr-2">Pelajari Lebih Lanjut</button>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isMdScreen: false
    };
  },
  created() {
    // Periksa saat komponen dibuat jika layar merupakan ukuran md
    this.isMdScreen = window.innerWidth < 992;

    // Tambahkan event listener untuk memantau perubahan lebar layar
    window.addEventListener('resize', this.handleResize);
  },
  mounted () {

  },
  unmounted() {
    // Hapus event listener saat komponen dihancurkan
    window.removeEventListener('resize', this.handleResize);
  },
  methods: {
    handleResize() {
      // Periksa kembali saat terjadi perubahan lebar layar
      this.isMdScreen =  window.innerWidth < 992;
    }
  },

}
</script>

<style>
	.timeline .main-step-container{
		/* width: 100%;
		height: auto; */
		justify-content: center;
		align-items:center;
		padding-left:0 !important;
    text-align: center;
	}

	.timeline ul.step-list{
		display:block !important;
		margin-left: 0 !important;
		margin-block-start: 0 !important;
		padding-inline-start: 0 !important;
    justify-content: center;
    position: relative;
    z-index: 99;
	}

	.timeline .img-responsive {
		width: 50px;
	}

	.timeline ul.step-list li{
		list-style: none;
    position: relative;
    z-index: 99;
    left: -70px;
    height: 200px;
	}
	.timeline ul.step-list li:last-child{
    height: 100px;
	}

	.timeline ul.step-list li img{
		list-style: none;
	}

  .timeline ul li .item-timeline .title-icon-timeline{
		font-weight: bold;
		display: block;
		font-size: 12px;
  }
  .timeline ul li .item-timeline .p-icon-timeline{
		font-size: 11px;
  }
  .timeline ul li .item-timeline{
		color: white;
    width: 200px;
    text-align: center;
  }
  .timeline ul li:not(:first-child):before{
    content: "";
    display: block;
    width: 80px;
    height: 2px;
    background: #ffffff;
    left: 50% - 20px;
    top: -2.25rem;
    position: absolute;
    transform: rotate(90deg) !important;
  }

  .timeline ul li:not(:last-child):after{
    content: "";
    display: block;
    width: 80px;
    height: 2px;
    background: #ffffff;
    left: 50% + 20px;
    top: 5.3rem;
    position: absolute;
    transform: rotate(90deg) !important;
  }

  .timeline li .content-item{
    position: relative;
    top: -60px;
    left: 130px;
    text-align: left;
  }

  @media only screen and (min-width: 992px) {
    .timeline ul.step-list{
      display:inline-flex !important;
    }

    .timeline ul li:not(:first-child):before{
      transform: rotate(0deg) !important;
      left: 0px;
      top: 1.7rem;
      width: 97px;
    }

    .timeline ul li:not(:last-child):after{
      transform: rotate(0deg) !important;
      right: -1px;
      top: 1.7rem;
      width: 97px;
    }

    .timeline li .content-item{
      position: relative;
      top: 0;
      left: 0;
      text-align: center;
      padding-top: 10px;
    }

    .timeline ul.step-list li{
      left: 0;
      margin-right: 0px;
      margin-left: 0px;
      width: 240px;
      height: 100%;
    }
  }

</style>
