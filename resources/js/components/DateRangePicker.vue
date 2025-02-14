<template>
  <div class="d-inline-block custom-daterangepicker">
    <div class="dropdown">
      <span class="select-label">Pilih</span>
      <input v-model="init.dataValue.key" type="hidden" name="cd-dropdown">
      <ul class="dropdown-list">
        <li v-for="item in props.constumOptions" :id="item.key" :key="item.key" @click="onDateFilterClick(item)">
          {{ item.label }}
        </li>
      </ul>
    </div>
    <div>
      <VueCtkDateTimePicker v-model="init.dataValue.value" :no-clear-button="true" :no-label="true" class="d-none" :range="true" format="YYYY-MM-DD" :no-shortcuts="true" locale="id-ID" label="Pilih" right @validate="onValidate" />
    </div>
  </div>

</template>



<script setup>
import { onMounted, reactive, ref, watch } from "vue";
const emits = defineEmits(['onChange'])
import moment from 'moment'
// i18n for moment
import 'moment/dist/locale/id'
// settings for moment
moment.suppressDeprecationWarnings = true
moment.locale('id')

const props = defineProps({
  constumOptions: {
    type: Array,
    required: false,
    default: () =>  [
      {
        key: 'today',
        label: 'Hari ini',
        value: {
          start: moment().format('YYYY-MM-DD'),
          end: moment().format('YYYY-MM-DD'),
        },
      },
      {
        key: 'week',
        label: 'Minggu ini',
        value: {
          start: moment().startOf('week').format('YYYY-MM-DD'),
          end: moment().endOf('week').format('YYYY-MM-DD'),
        },
      },
      {
        key: 'month',
        label: 'Bulan ini',
        value: {
          start: moment().startOf('month').format('YYYY-MM-DD'),
          end: moment().endOf('month').format('YYYY-MM-DD'),
        },
      },
      {
        key: 'year',
        label: 'Tahun ini',
        value: {
          start: moment().startOf('year').format('YYYY-MM-DD'),
          end: moment().endOf('year').format('YYYY-MM-DD'),
        },
      },
      {
        key: 'custom_range',
        label: 'Custom range',
        value: {
          start: null,
          end: null,
        },
      },
    ]
  },
  position: {
    type: String,
    required: false,
    default: 'bottomLeft',
    description: `
        topLeft
        topRight
        bottomLeft
        bottomRight
      `,


  },

})


const toggleSelect = ref(false)

const init = reactive({
  dataValue: {
      key: null,
      label: null,
      value: null,
    },
})

const onDateFilterClick = (item) => {
  if(init.dataValue.key){
      const list = document.getElementById(init.dataValue.key).classList;
      list.remove('selected')
    }
  init.dataValue = item
  if(init.dataValue.key){
      const list2 = document.getElementById(init.dataValue.key).classList;
      list2.add('selected')
    }
}
const setDefaultFilter = () => {
  init.dataValue = props.constumOptions[0]
  window.jQuery('.select-label').text(init.dataValue.label);
  const list = document.getElementById(init.dataValue.key).classList;
  list.add('selected')
}

onMounted(()=>{
  positionChange()
  setDefaultFilter()
  window.jQuery('.select-label').click(function () {
    window.jQuery('.dropdown').toggleClass('active');
    toggleSelect.value = !toggleSelect.value
    if(toggleSelect.value == true && init.dataValue.key == 'custom_range'){
      document.querySelector(".date-time-picker .datetimepicker").style.display = "block"
    }else{
      document.querySelector(".date-time-picker .datetimepicker").style.display = "none"
    }

  });
  window.jQuery('.dropdown-list li').click(function () {
    window.jQuery('.select-label').text(window.jQuery(this).text());
    if(init.dataValue.key != 'custom_range'){
      window.jQuery('.dropdown').removeClass('active');
      toggleSelect.value = false
      document.querySelector(".date-time-picker .datetimepicker").style.display = "none"
    }else{
      document.querySelector(".date-time-picker .datetimepicker").style.display = "block"
    }
  });

})

const positionChange = () => {
  if(props.position == 'topLeft'){
    document.querySelector(".custom-daterangepicker .dropdown-list").style.bottom = "36px"
    document.querySelector(".custom-daterangepicker .date-time-picker .datetimepicker .datepicker").style.bottom = "100%"
    document.querySelector(".custom-daterangepicker .date-time-picker .datetimepicker .datepicker").style.right = "100%"

  }else if(props.position == 'topRight'){
    document.querySelector(".custom-daterangepicker .dropdown-list").style.bottom = "36px"
    document.querySelector(".custom-daterangepicker .date-time-picker .datetimepicker .datepicker").style.bottom = "100%"
    document.querySelector(".custom-daterangepicker .date-time-picker .datetimepicker .datepicker").style.left = "100%"

  }else if(props.position == 'bottomLeft'){
    document.querySelector(".custom-daterangepicker .dropdown-list").style.top = "36px"
    document.querySelector(".custom-daterangepicker .date-time-picker .datetimepicker .datepicker").style.top = "100%"
    document.querySelector(".custom-daterangepicker .date-time-picker .datetimepicker .datepicker").style.right = "100%"

  }else if(props.position == 'bottomRight'){
    document.querySelector(".custom-daterangepicker .date-time-picker .datetimepicker .datepicker").style.top = "100%"
    document.querySelector(".custom-daterangepicker .date-time-picker .datetimepicker .datepicker").style.left = "100%"

  }else{
    document.querySelector(".custom-daterangepicker .dropdown-list").style.top = "36px"
    document.querySelector(".custom-daterangepicker .date-time-picker .datetimepicker .datepicker").style.top = "100%"
    document.querySelector(".custom-daterangepicker .date-time-picker .datetimepicker .datepicker").style.left = "100%"
  }
}

const onValidate = () => {
  if(init.dataValue.value != null){
    if(init.dataValue.value.start != null && init.dataValue.value.end != null){
      const start = init.dataValue.value.start.split('-').reverse().join('-')
      const end = init.dataValue.value.end.split('-').reverse().join('-')
      window.jQuery('.select-label').text( start+' - '+ end);
      window.jQuery('.dropdown').removeClass('active');
      toggleSelect.value = false
      document.querySelector(".date-time-picker .datetimepicker").style.display = "none"
      emits("onChange",init.dataValue)
    }
  }
}

watch(() => init.dataValue,(newValue)=>{
  if(newValue.key != 'custom_range'){
    emits("onChange",newValue)
  }
})

</script>

<style lang="css">
  .custom-daterangepicker .multiselect__option{
    padding: 0 !important;
  }

  .custom-daterangepicker .dropdown {
    width: 100%;
    position: relative;
    margin-right: 20px;
    border-radius: 3px;
    border: 1px solid rgba(0, 0, 0, 0.1);
  }
  .custom-daterangepicker .dropdown .select-label {
    position: relative;
    display: block;
    width: 100%;
    height: 35px;
    line-height: 35px;
    color: #35495e;
    border-radius: 3px;
    font-size: 12px;
    letter-spacing: 2px;
    text-indent:5px;
    background: white;
    z-index: 9999;
    cursor: pointer;
    user-select: none;
  }
  .custom-daterangepicker .dropdown .select-label:after {
    content: '▼';
    position: absolute;
    top: 0;
    right: 5px;
    height: 35px;
    line-height: 35px;
    text-align: center;
    font-size: 12px;
  }
  .custom-daterangepicker .dropdown-list {
    position: absolute;
    margin-bottom: 0;
    padding-left: 0px;
    width: 100%;
    height: 0;
    overflow: hidden;
    transition: height 3s;
  }
  .custom-daterangepicker .dropdown-list li {
    display: block;
    position: relative;
    top: 0;
    left: 0;
    width: 100%;
    font-size: 12px;
    line-height: 33px;
    height: 33px;
    text-indent:30px;
    color: #35495e;
    cursor: pointer;
    letter-spacing: 2px;
    background-color: #fff;
    list-style: none;
    opacity: 1;
    user-select: none;
    border-radius: 3px 3px 0 0;
    border-left: 1px solid rgba(0, 0, 0, 0.1) !important;
    border-right: 1px solid rgba(0, 0, 0, 0.1) !important;
  }
  .custom-daterangepicker .dropdown-list li:hover {background-color:#29D8B5; box-shadow:inset 0 1px 2px rgba(0,0,0,.2), 0 1px 2px rgba(0,0,0,.12) !important; color: #fff;}
  .custom-daterangepicker .dropdown-list li:nth-child(1){z-index:4;}
  .custom-daterangepicker .dropdown-list li:nth-child(2){z-index:3;}
  .custom-daterangepicker .dropdown-list li:nth-child(3){z-index:2;}
  .custom-daterangepicker .dropdown-list li:nth-child(4){z-index:1;}

  .custom-daterangepicker .dropdown .select-label:active:after {
    content: '▲';
  }

  .custom-daterangepicker .dropdown.active .select-label:after {
    content: '▲';
  }

  .custom-daterangepicker .dropdown.active .dropdown-list li {
    border-radius: 0px 0px 0 0;
  }

  .custom-daterangepicker .dropdown.active .dropdown-list{
    height: max-content;
    overflow: auto;
  }
  .custom-daterangepicker .dropdown.active .dropdown-list li:last-child {
    border-radius: 0 0 3px 3px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1) !important;
  }
  .custom-daterangepicker .dropdown.active .dropdown-list li:first-child {
    border-radius: 3px 3px 0 0;
    border-top: 1px solid rgba(0, 0, 0, 0.1) !important;
  }

  .custom-daterangepicker .dropdown.active .selected{
    background-color: #29D8B5;
  }
</style>
