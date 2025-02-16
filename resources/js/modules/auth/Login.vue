<script setup>
import { ref, reactive, onMounted } from 'vue'
import { toTitle } from '@/js/utils/convert-case.js'
import { _, _settings, _http, _route, _encrypt } from '@/js/utils/common'

const form = reactive({
  username: '',
  password: '',
  captcha: '',
})

const captcha = reactive({
  src: null,
  hash: null,
})

const seek = reactive({
  type: 'password',
  icon: 'eye',
})

const isLoading = ref(false)

const errors = reactive({
  username: null,
  password: null,
  captcha: null,
  hasError: false,
})

const validateForm = () => {
  errors.hasError = Object.keys(form).some((key) => {
    let hasNoFill = form[key].length === 0

    errors[key] = hasNoFill ? `${toTitle(key)} harus diisi` : null

    if (key == 'captcha' && !_settings.validateCaptcha) {
      errors[key] = ''
      hasNoFill = false
    }

    return hasNoFill
  })
}
const handleOnKeyup = (e) => {
  const el = e.target

  if (el.value.length > 0) errors[el.id] = null

  validateForm()
}

const handleOnClick = () => {
  // quick return jika dalam kondisi pengecekan
  if (isLoading.value) return

  // validateForm.value = true
  validateForm()

  // quick return jika ada error
  if (errors.hasError) return

  isLoading.value = true
  const { username, password, captcha } = form
  const credentials = _encrypt(JSON.stringify({ username, password, captcha }))

  _http
    .post(_route('auth.login'), { credentials })
    .then((res) => {
      if (!res.status.toString().startsWith('20')) throw 'error'

      let queryString = window.location.search
      let urlParams = new URLSearchParams(queryString)
      let path = urlParams.get('path_before')
      if(path == null){
        window.location = '/'
      }else{
        window.location = path
      }
    })
    .catch(({ response }) => {
      if (response.status == 422) {
        if (Object.prototype.hasOwnProperty.call(response.data, 'errors'))
          Object.keys(response.data.errors).forEach((key) => (errors[key] = response.data.errors[key].join(', ')))
      }
    })
    .finally(() => (isLoading.value = false))
}

const loadCaptcha = _.debounce(async () => {
  const { status, data } = await _http.get(_route('backend.captcha.generate'))
  if (status != 200) {
    console.log('error saat generate captcha', { status })
    return
  }

  const { img, val } = data
  captcha.src = img
  captcha.hash = val
}, 850)

const readAudio = () => {
  const audio = new Audio(_route('backend.captcha.audio', { audio: captcha.hash }))
  audio.play()
}

const toDaftar = () => {
  window.location = '/daftar'
}

const toLanding = () => {
  window.location = '/'
}

const seekPassword = _.debounce(() => {
  const { type, icon } = seek
  seek.type = type == 'password' ? 'text' : 'password'
  seek.icon = icon == 'eye-slash' ? 'eye' : 'eye-slash'
}, 250)



onMounted(() => loadCaptcha())

</script>

<template>
  <div class="page-wrapper">
    <div class="page-inner bg-brand-gradient">
      <div class="pl-0 m-0 bg-transparent page-content-wrapper">
        <div class="flex-1 auth-wrapper">
          <div class="d-flex flex-column align-items-between h-100">
            <div class="container flex-1 px-4 py-4 py-lg-5 my-lg-5 px-sm-0">
              <div class="row">
                <div class="ml-auto mr-auto col-xl-5">
                  <div class="p-5 card rounded-plus bg-faded">
                    <div class="mb-4 text-center logo">
                      <div class="mb-2 text-dark font-weight-bold lead">
                        {{ _settings.appname }}
                      </div>
                      <a href="#" class="page-logo-link press-scale-down">
                        <img
                          src="@/images/logo-emasin2.png"
                          class="img-fluid"
                          :alt="_settings.appname"
                          aria-roledescription="logo"
                          style="width: 3.3rem; height: auto"
                          tabindex="-1"
                        />
                      </a>
                    </div>
                    <form class="login-form">
                      <b-form-group
                        label=""
                        description="Gunakan NRK atau Username Anda yang sudah terdaftar"
                        label-for="username"
                      >
                        <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                          <template #prepend>
                            <span class="input-group-text">
                              <span class="fad fa-user-alt fs-xl"></span>
                            </span>
                          </template>
                          <b-form-input
                            id="username"
                            v-model="form.username"
                            v-focus
                            placeholder="Username"
                            tabindex="1"
                            trim
                            @keyup="handleOnKeyup"
                          ></b-form-input>
                        </b-input-group>
                        <small v-if="errors.username" class="text-danger">{{ errors.username }}</small>
                      </b-form-group>
                      <b-form-group
                        label=""
                        label-for="password"
                        description="Untuk keamanan silahkan ganti kata sandi Anda secara berkala"
                      >
                        <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                          <template #prepend>
                            <span class="input-group-text">
                              <span class="fad fa-lock fs-xl"></span>
                            </span>
                          </template>
                          <template #append>
                            <span class="cursor-pointer input-group-text" @click.prevent="seekPassword">
                              <span :class="`fad fa-${seek.icon}`" @click.prevent="seekPassword"></span>
                            </span>
                          </template>
                          <b-form-input
                            id="password"
                            v-model="form.password"
                            placeholder="Password"
                            tabindex="2"
                            :type="seek.type"
                            trim
                            @keyup="handleOnKeyup"
                            @keypress.enter="handleOnClick"
                          ></b-form-input>
                        </b-input-group>
                        <small v-if="errors.password" class="text-danger">{{ errors.password }}</small>
                      </b-form-group>
                      <b-form-group>
                        <div class="mt-0 mb-2">
                          <div class="captcha">
                            <div class="mb-2 d-flex align-items-center">
                              <div
                                :style="{
                                  display: 'block',
                                  borderRadius: '0.3rem',
                                  overflow: 'hidden',
                                  width: 'auto',
                                  height: '45px',
                                }"
                                class="mr-2"
                              >
                                <b-img
                                  id="captcha-img"
                                  ref="captcha-img"
                                  :blank="!captcha.src"
                                  :src="captcha.src"
                                  blank-color="#f2f2f2"
                                  width="130px"
                                  alt="Loading..."
                                />
                              </div>

                              <button
                                id="reload-captcha"
                                type="button"
                                class="py-1 mr-2 btn btn-danger btn-sm"
                                :disabled="!captcha.src"
                                tabindex="5"
                                @click.prevent="loadCaptcha"
                              >
                                Reload <br />
                                Captcha
                              </button>

                              <button
                                id="audio-captcha"
                                type="button"
                                class="py-1 btn btn-primary btn-sm reload"
                                :disabled="!captcha.src"
                                aria-describedby="Captcha Suara"
                                tabindex="6"
                                @click.prevent="readAudio"
                              >
                                Captcha <br />
                                Suara
                              </button>
                            </div>
                          </div>

                          <b-form-input
                            id="captcha"
                            v-model="form.captcha"
                            placeholder="Captcha"
                            tabindex="3"
                            trim
                            @keyup="handleOnKeyup"
                            @keypress.enter="handleOnClick"
                          ></b-form-input>
                        </div>
                        <small v-if="errors.captcha" class="text-danger">{{ errors.captcha }}</small>
                      </b-form-group>

                      <div class="row no-gutters mt-n2">
                        <b-button
                          variant="info"
                          block
                          :class="{
                            disabled: isLoading || errors.hasError,
                            'cursor-wait': isLoading,
                            'cursor-pointer': !isLoading,
                          }"
                          @click.prevent="handleOnClick"
                        >
                          <span v-if="isLoading" class="fas fa-spinner fa-spin"></span>
                          <span v-else class="fad fa-sign-in"></span>

                          &nbsp;{{ isLoading ? 'Proses Autentikasi' : 'Masuk' }}
                        </b-button>
                      </div>
                    </form>
                    <div class="mt-5 text-center">
                      <span>Belum memiliki akun? <a href="" class="font-weight-bold" @click.prevent="toDaftar()">Daftar</a></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="mb-3 text-center text-white d-block">
              <div class="container mx-auto sponsor" style="display: block">
                <div class="p-4 text-center">
                  <img src="@/images/jakartaplus-logo.svg" class="img-fluid" width="105px" />
                </div>
              </div>
              2023 © Portal Layanan by&nbsp;<a
                href="https://www.diskom.info/jakarta/"
                class="text-white opacity-40 fw-500"
                title="https://www.diskom.info/jakarta/"
                target="_blank"
              >Diskominfotik</a
              >
            </div>
          </div>
          <nav class="shortcut-menu d-none d-sm-block" style="bottom: 29px;" @click="toLanding()">
            <input id="menu_open" type="checkbox" class="menu-open" name="menu-open">
            <label for="menu_open" class="menu-open-button" >
              <i class="fas fa-home fa-lg"></i>
            </label>
          </nav>
        </div>
      </div>
    </div>
  </div>
</template>

<style lang="scss">
.login-form {
  input {
    &.form-control {
      height: calc(1.865em + 1rem + 2px);
    }
  }
}
.auth-wrapper nav.shortcut-menu{
  display: block !important;
}
</style>
