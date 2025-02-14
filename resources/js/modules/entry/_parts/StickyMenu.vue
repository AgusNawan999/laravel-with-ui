<!-- eslint-disable vue/no-v-html -->
<template>
  <nav class="shortcut-menu sticky-menu d-none d-sm-block" style="bottom: 29px;">
    <input id="menu_open" type="checkbox" class="menu-open" name="menu-open">
    <label for="menu_open" class="menu-open-button" @click="stickyMenuClicked = !stickyMenuClicked">
      <span :class="stickyMenuClicked ? 'fas fa-times' + ' transition-rotation' : 'fas fa-comment'" :style="stickyMenuClicked ? 'font-size:18px;' : 'font-size:22px;'" style="transition: all 1s;transition-timing-function: ease-in-out;" class="d-block " height="50"></span>
    </label>
    <template v-for="(item, key) in img_menu" :key="key">
      <div class="menu-item btn waves-effect waves-themed rounded-circle bg-white d-flex align-items-center justify-content-center" data-toggle="tooltip" data-placement="left" title="" @click.prevent="handleStickyMenu(item,$bvModal)" @mouseover="item.imageHover = true" @mouseleave="item.imageHover = false">
        <span :class="item.imageHover ? item.icon : item.icon" :style="item.imageHover ? 'color:white !important;' : 'color:#5e61ab !important;'" class="d-block" style="font-size: 22px;">
        </span>
      </div>
    </template>
  </nav>

  <!-- modal -->
  <b-modal
    id="modal-landing"
    :title="modal.title"
    :modal-class="modal.modalClass"
    :header-class="modal.headerClass"
    :body-class="modal.bodyClass"
    :hide-footer="modal.hideFooter">
    <div
      v-html="modal.konten">

    </div>
  </b-modal>
  <!-- modal end -->
</template>

<script setup>
  import { ref,reactive} from 'vue'

  const stickyMenuClicked = ref(false)
  const modal = reactive({
    title: null,
    konten: 'konten',
    modalClass: null,
    headerClass: 'pt-0 pb-0',
    bodyClass: 'pl-4 pr-4 pt-0 pb-4 overflow-hidden',
    hideFooter: false,
  })
  const img_menu = reactive([
    {
      name: 'Email',
      icon: 'far fa-envelope',
      imageHover: false,
      email: 'layanan-tik@jakarta.go.id',
      emailcc: '',
      emailsub: '',
      emailbody: '',
    },
    {
      name: 'Whatsapp',
      icon: 'fab fa-whatsapp',
      imageHover: false,
      nowa: '6281340003614',
    },
    {
      name: 'Map',
      icon: 'far fa-map',
      imageHover: false,
      namaDinas: 'Unit Pengelolaan Layanan Teknologi Informasi dan Komunikasi',
      namaSkpd: 'Dinas Komunikasi, Informatika dan Statistik Provinsi DKI Jakarta',
      alamat: 'Gedung Balaikota Blok H Lt.13 Jl. Medan Merdeka Selatan 8-9',
      provinsi: 'Jakarta',
      kodePos: '10110',
      noTelp: '021 xxxx xxxx',
      urlMap: 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.621310445476!2d106.82579637389533!3d-6.181412360565753!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f42de87813ef%3A0xc5bd9cad3397da3c!2sBalai%20Kota%20DKI%20Jakarta!5e0!3m2!1sid!2sid!4v1692606215119!5m2!1sid!2sid'


    },
  ])


  const handleStickyMenu = (item,$bvModal) => {
    if(item.name == 'Whatsapp'){
      handleWhatsapp(item)
    }else if(item.name == 'Email'){
      handleEmail(item)
    }else if(item.name == 'Map'){
      handleMap(item,$bvModal)
    }
  }

  const handleWhatsapp = (item) => {
    window.open(
      'https://wa.me/'+item.nowa,
      '_blank' // <- This is what makes it open in a new window.
    );
  }
  const handleEmail = (item) => {
    let emailTo = item.email
    let emailCC = item.emailcc
    let emailSub = item.emailsub
    let emailBody = item.emailbody
    window.open(
      "mailto:"+emailTo+'?cc='+emailCC+'&subject='+emailSub+'&body='+emailBody,
      '_blank' // <- This is what makes it open in a new window.
    );
  }
  const handleMap = (item,$bvModal) => {
    modal.headerClass = 'pt-1 pb-1 b border-bottom-0'
    modal.hideFooter = true
    modal.konten = '<div class="mapouter"><div class="gmap_canvas"><iframe src="'+item.urlMap+'" width="450" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></div></div>'

    modal.konten += '<span style="display: block;font-weight:bolder;font-size: 14px;" class="mt-4">'+item.namaDinas+'</span><span style="display: block;">'+item.namaSkpd+'</span><span style="display: block;">'+item.alamat+'</span><span style="display: block;">'+item.provinsi+' '+item.kodePos+'</span><span style="display: block;">'+item.noTelp+'</span>'
    $bvModal.show('modal-landing')
  }
</script>

<style>
  nav.shortcut-menu.sticky-menu .transition-rotation {
    display: block;
    position: relative;
    transform: rotate(720deg);
  }

  nav.shortcut-menu.sticky-menu .menu-item{
    background-color: white;
    transition: all 1s;
  }
  nav.shortcut-menu.sticky-menu .menu-item:hover{
    background-color: #8b8dc7 !important;
  }
  nav.shortcut-menu.sticky-menu{
    display: block !important;
  }
  nav.shortcut-menu.sticky-menu .menu-open-button{
    background-image: linear-gradient(#6e72c0 50%,#5e61ab 50%);
    height: 50px;
    width: 50px;
    top:-47px;
    left:-47px;
  }
</style>
