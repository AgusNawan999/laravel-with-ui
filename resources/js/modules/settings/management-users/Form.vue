<script setup>
import { useRouter } from "vue-router";
import { ref, reactive, createApp, onMounted, nextTick } from "vue";
import { Field, Form as VeeForm } from "vee-validate";
import { _route, _http, _alert, _confirm, _decrypt, _settings } from "@/js/utils/common";
import Multiselect from "vue-multiselect";

const defProps = defineProps({
  slug: {
    type: String,
    required: false
  },
})

const app = createApp({});

const form = {};
const router = useRouter();
const IS_ADD = router.currentRoute.value.href.endsWith("add");
const formRef = ref(null);
const input = reactive({
  nrk: null,
  username: null,
  status: '',
  group: [],
});

// const isLoading = ref(false);
const isModal = ref(false);
const isOverlay = ref(false);
const isDisable = ref(false)
const handleCancel = () => {
  window.history.go(-1);
};

const clearSelected = () => {
  input.nrk = null;
  input.username = null;
  input.group = null;
};

const onChangeRadio = (selectedRadio) => {
  let selectedValue = selectedRadio;

  return input.status = selectedValue
}

const onHandleSelected = (selectedOption, id) => {
  let selectedValue = selectedOption;

  if (id == "group") selectedValue = input.group;

  formRef.value.setFieldValue(id, selectedValue);
  formRef.value.setFieldTouched(id);
};

// const onHandleRemoved = (e, id) => {

//   formRef.value.setValues(id, '')

//   let error = formRef.value.getErrors();
//   formRef.value.setFieldError(id, error);
// };


const hideModal = () => {
  isModal.value = false
  formRef.value.resetForm('nrk')


}

const handleOnClick = (values, { resetForm }) => {
  const method = IS_ADD ? "post" : "put";
  let groups = input.group
  let statuses = input.status
  const url = `backend.setting.management.users.${method}`;

  Object.keys(values).forEach((key) => (form[key] = values[key] || null));
  if (!IS_ADD) form["_method"] = "put";

  const title = `${IS_ADD ? "Tambah" : "Ubah"} Pengguna`;
  const text = `Apakah Anda yakin untuk ${IS_ADD ? "menambahkan" : "mengubah"} data?`;

  _confirm({ title, icon: "question", text }, () => {

    return _http[method](_route(url), { ...form, groups, statuses })
      .then((result) => result)
      .catch((error) => {
        let code = error.response.status;
        let message = error.response.data.error;

        if (code == "422") {
          const errors = error.response.data.errors;
          message = Object.keys(errors)
            .map((key) => {
              return Array.isArray(errors[key]) ? errors[key].join(", ") : errors[key];
            })
            .join(", ");

          _alert.showValidationMessage(`${message}`);
        }
        if (code == "400") {
          const errors = error.response.data.status;
          message = Object.keys(errors)
            .map((key) => {
              return Array.isArray(errors[key]) ? errors[key].join(", ") : errors[key];
            })
            .join(", ");

          _alert.fire("Terjadi Kesalahan", error.response.data.message, "error");
        }
      });
  }).then(({ value, isConfirmed, isDismissed }) => {
    // do nothing
    if (isDismissed) return false;

    // show response
    if (isConfirmed && value.data.status == "success")
      _alert.fire({
        title: `${IS_ADD ? "Tambah" : "Ubah"} Data Berhasil`,
        text: value.data.message,
        icon: "success",
      });

    // clear form
    resetForm();
    clearSelected();

    // redirect
    router.push({ name: "settings.management.users" });
  });
};

const options = reactive({
  group: [],
  status: [
    { text: 'Aktif', value: true },
    { text: 'Tidak Aktif', value: false },

  ]
});

const management = {
  init: {
    username: null,
    full_name: null,
    email: null,
    password: null,
    status: null,
    group: [],
  },
  rules: {
    username: "required|max:10",
    email: "required|email",
    password: IS_ADD ? "required" : "",
  },
};


const fetchGroup = (searchText = null) => {
  return Promise.resolve(
    _http.get(
      _route("backend.setting.management.user.getGroupUsers", {
        search: searchText,
        columns: "v_group_name",
        limit: 100,
      })
    )
  );
};

const getGroup = _.debounce(function (searchText = null) {
  fetchGroup(searchText).then((res) => {
     options.group = res.data.data.map((feat) => {
      return {
        v_group_code: feat.v_group_code,
        v_group_name: feat.v_group_name,
      };
    });
    return options.group;
  });
}, 250);


app.component("Multiselect", Multiselect);

onMounted(() => {
  if (IS_ADD) {
    getGroup();
  } else {
    const parse = JSON.parse(_decrypt(defProps.slug))
    Promise.resolve(fetchGroup())
    .then((res) => {
      options.group = res.data.data.map(feat => {
        return {
            v_group_code : feat.v_group_code,
            v_group_name: feat.v_group_name
          }
      })
      return options.group
    })
    .then(groups => groups.find(key => key.v_group_name == parse.item.user_group))
      .then(selectedGroup => {
        if (selectedGroup) {
          formRef.value.setFieldValue('group', selectedGroup.slug)
          input.nrk = selectedGroup
        }
      })
    nextTick(() => {
      let group_id = [];
      let status = parse.item.si_user_enable
      const group = parse.item.user_group
      group.map(function(value) {
        group_id.push({v_group_code: value.detail_group.v_group_code, v_group_name: value.detail_group.v_group_name});
      });
      formRef.value.setValues({
        username: parse.item.username,
        full_name: parse.item.v_full_name,
        email: parse.item.email,
        group: group_id
      })
      input.status = status
      input.group = group_id
      form['slug'] = parse.item.slug
      if(_settings.user.roles.find(i => i == 'ADM')) isDisable.value = false
      else isDisable.value = true
    })
  }
});

</script>

<template>
  <div>
    <div class="col-xl-12 pt-4">
      <div class="panel form-wrapper">
        <div class="panel-hdr">
          <h2 class="fs-xl">
            <i
              :class="`fad fa-${IS_ADD ? 'plus-circle' : 'pencil'} mr-2 text-primary`"
            ></i>
            <strong class="fw-600">
              {{ IS_ADD ? "Tambah" : "Ubah" }}&nbsp;<span class="fw- 300">Pengguna</span>
            </strong>
          </h2>
        </div>
        <div class="panel-container show">
          <div class="panel-content">
            <VeeForm ref="formRef" @submit="handleOnClick">
              <Field
                v-slot="{ field, errorMessage, meta }"
                label="Username"
                name="username"
                :rules="management.rules.username"
              >
                <b-form-group
                  label="Username"
                  label-for="username"
                  label-class="fw-700 mb-0"
                  :state="!meta.touched ? null : errorMessage ? false : true"
                  :invalid-feedback="!meta.touched ? null : errorMessage"
                >
                  <b-form-input
                    id="username"
                    v-bind="field"
                    placeholder="Masukkan Username"
                    :state="!meta.touched ? null : errorMessage ? false : true"
                    :disabled="!IS_ADD"
                  />
                </b-form-group>
              </Field>
              <Field
                v-slot="{ field, errorMessage, meta }"
                label="Nama Lengkap"
                name="full_name"
                :rules="management.rules.full_name"

              >
                <b-form-group
                  label="Nama Lengkap"
                  label-for="full_name"
                  label-class="fw-700 mb-0"
                  :state="!meta.touched ? null : errorMessage ? false : true"
                  :invalid-feedback="!meta.touched ? null : errorMessage"
                >
                  <b-form-input
                    id="full_name"
                    v-bind="field"
                    placeholder="Masukkan nama lengkap"
                    :state="!meta.touched ? null : errorMessage ? false : true"
                    :disabled="isDisable"
                  />
                </b-form-group>
              </Field>
              <Field
                v-slot="{ field, errorMessage, meta }"
                label="Email"
                name="email"
                :rules="management.rules.email"

              >
                <b-form-group
                  label="Email"
                  label-for="email"
                  label-class="fw-700 mb-0"
                  :state="!meta.touched ? null : errorMessage ? false : true"
                  :invalid-feedback="!meta.touched ? null : errorMessage"
                >
                  <b-form-input
                    id="email"
                    v-bind="field"
                    placeholder="Masukkan email"
                    :state="!meta.touched ? null : errorMessage ? false : true"
                    :disabled="isDisable"
                  />
                </b-form-group>
              </Field>
              <Field
                v-slot="{ field, errorMessage, meta }"
                label="Password"
                name="password"
                :rules="management.rules.password"

              >
                <b-form-group
                  label="Password"
                  label-for="password"
                  label-class="fw-700 mb-0"
                  :state="!meta.touched ? null : errorMessage ? false : true"
                  :invalid-feedback="!meta.touched ? null : errorMessage"
                >
                  <b-form-input
                    id="password"
                    v-bind="field"
                    :placeholder="!IS_ADD ? 'Biarkan Kosong Jika Tidak Dirubah' : 'Masukkan password'"
                    :state="!meta.touched ? null : errorMessage ? false : true"
                    :disabled="isDisable"
                  />
                </b-form-group>
              </Field>
              <Field
                v-if="!IS_ADD"
                v-slot="{ field, errorMessage, meta }"
                label="Status  Aktif"
                name="status"
              >
                <b-form-group
                  label="Status Aktif"
                  label-for="status"
                  label-class="fw-700 mb-0"
                  :state="!meta.touched ? null : errorMessage ? false : true"
                  :invalid-feedback="!meta.touched ? null : errorMessage"
                >
                  <b-form-radio-group
                    id="status"
                    v-model="input.status"
                    v-bind="field"
                    :options="options.status"
                    :aria-describedby="ariaDescribedby"
                    name="radio-options"
                    :checked="input.status"
                    @input="onChangeRadio"
                  ></b-form-radio-group>
                </b-form-group>

              </Field>
              <Field
                v-slot="{ errorMessage, meta }"
                label="Group Pengguna"
                name="group"
              >
                <b-form-group
                  label="Group Pengguna"
                  label-for="group"
                  label-class="fw-700 mb-0"
                  :class="{ 'is-invalid': errorMessage && errorMessage.group }"
                  :state="!meta.touched ? null : errorMessage ? false : true"
                  :invalid-feedback="!meta.touched ? null : errorMessage"
                >
                  <vue-multiselect
                    id="group"
                    v-model="input.group"
                    tag-placeholder="Tambah group pengguna"
                    placeholder="Pilih group pengguna"
                    label="v_group_name"
                    track-by="v_group_code"
                    :options="options.group"
                    :multiple="true"
                    :taggable="true"
                    @select="onHandleSelected"
                    @search-change="getGroup"
                  >
                    <template #noOptions>Tidak ada data</template>
                    <template #noResult>Data tidak ditemukan</template>
                  </vue-multiselect>
                  <div class="text-danger fs-nano mt-1">
                    {{ errorMessage && errorMessage.tipe }}
                  </div>
                </b-form-group>
              </Field>

              <div class="row">
                <div class="col-12 d-flex justify-content-end align-items-center">
                  <b-button variant="default" class="mr-2" @click.prevent="handleCancel">
                    <i class="fad fa-times-circle"></i>
                    Batalkan
                  </b-button>
                  <b-button variant="primary" type="submit">
                    <i class="fad fa-check-circle"></i>
                    {{ IS_ADD ? 'Tambah' : 'Ubah' }}
                  </b-button>
                </div>
              </div>
              <b-modal :visible="isModal == true" hide-header hide-footer centered>
                <b-overlay
                  id="overlay-background"
                  :show=isOverlay
                  :variant="variant"
                  :opacity="opacity"
                  :blur="blur"
                  rounded="sm"
                >

                  <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle fa-3x text-info"></i>
                    <div class="ml-2">
                      <div class="fs-xl fw-600 mb-0"><b>User Tidak Ditemukan</b></div>

                    </div>
                  </div>
                  <hr />
                  <div class="col-lg-12">
                    <div class="d-flex align-items-center justify-content-center">
                      <p>User dengan <b>NRK</b> tersebut tidak ditemukan pada E-TPP.</p>
                    </div>
                  </div>
                  <b-col col lg="12" md="auto" class="d-flex justify-content-center">
                    <div>
                      <b-button
                        class="mr-3"
                        size="md"
                        variant="success"
                        @click="hideModal">
                        Ok !</b-button
                      >
                    </div>
                  </b-col>

                </b-overlay>
              </b-modal>
            </VeeForm>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
