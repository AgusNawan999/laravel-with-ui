<script setup>
import { _c3 } from '@/js/utils/chart'
import { onMounted, reactive } from 'vue'
import { _http, _route, _alert, _moment } from '@/js/utils/common'
// import GridDaftarLayanan from "@/js/modules/entry/_parts_admin/GridDaftarLayanan.vue"
// import GridDaftarPengaduan from "@/js/modules/entry/_parts_admin/GridDaftarPengaduan.vue"
// import GridDaftarExtendTl from "@/js/modules/entry/_parts_admin/GridDaftarExtendTl.vue"
// import DateRangePicker from '@/js/components/DateRangePicker.vue'

const getColor = () => {
    let i = 0;
    let color = null;
    while (i == 0) {
      color = '#' + Math.floor(Math.random()*16777215).toString(16);
      if(color.length == 7){
        i++;
      }
    }
    return color;
}

const chartColor = reactive({
  pieChartColor: [],
  donutChartColor: [],
  pengaduanBarChartColor: [],
  layananBarChartColor: [],
  layananSkpdBarChartColor: [],
  pengaduanSkpdBarChartColor: [],
})

const dashboard = reactive({
  jmlPengaduan: 0,
  jmlLayanan: 0,
  jmlExtendTl: 0,
  pengajuanLayananByStatus: [],
  pengaduanByStatus: [],
  top5Layanan: [],
  top5Pengaduan: [],
  layananBySkpd: [],
  pengaduanBySkpd: [],
  dateFilter: {
    "v_key": "today",
    "v_name": "Hari ini"
  },
})

const isLoading = reactive({
  sectionOne: false,
  sectionTwo: false,
  sectionFour: false,
  sectionFive: false,
})


onMounted(() => {

})



const sectionOne = () => {
  isLoading.sectionOne = true
  setTimeout(() => {
    _http.get(_route('backend.dashboard.sectionOne', {filter: dashboard.dateFilter}))
    .then(({ data }) => {
      if (data.status == 'error') throw (data.response.message)
      dashboard.jmlPengaduan = data.data.pengaduan
      dashboard.jmlLayanan = data.data.layanan
      dashboard.jmlExtendTl = data.data.extend_tl
    })
    .catch(error => {
      _alert.fire('Terjadi Kesalahan', error.response.data.message, 'error')
    })
    isLoading.sectionOne = false
  }, 2000);

}

const sectionTwo = () => {
  isLoading.sectionTwo = true
  setTimeout(() => {
    _http.get(_route('backend.dashboard.sectionTwo', {filter: dashboard.dateFilter}))
    .then(({ data }) => {
      if (data.status == 'error') throw (data.response.message)
      dashboard.pengajuanLayananByStatus = data.data.pengajuanLayananByStatus
      dashboard.pengaduanByStatus = data.data.pengaduanByStatus
      dashboard.top5Layanan = data.data.top5Layanan
      dashboard.top5Pengaduan = data.data.top5Pengaduan
      createPieChart(data.data.pengajuanLayananByStatus)
      createDonutChart(data.data.pengaduanByStatus)
      createPengaduanBarChart(data.data.top5Pengaduan)
      createLayananBarChart(data.data.top5Layanan)

    })
    .catch(error => {
      _alert.fire('Terjadi Kesalahan', error.response.data.message, 'error')
    })
    isLoading.sectionTwo = false
  }, 2000);
}

const sectionFour = () => {
  isLoading.sectionFour = true
  setTimeout(() => {
    _http.get(_route('backend.dashboard.sectionFour', {filter: dashboard.dateFilter}))
    .then(({ data }) => {
      if (data.status == 'error') throw (data.response.message)
      dashboard.layananBySkpd = data.data.layananBySkpd
      createLayananSKPDBarChart(data.data.layananBySkpd)
    })
    .catch(error => {
      _alert.fire('Terjadi Kesalahan', error.response.data.message, 'error')
    })
    isLoading.sectionFour = false
  }, 2000);
}

const sectionFive = () => {
  isLoading.sectionFive = true
  setTimeout(() => {
    _http.get(_route('backend.dashboard.sectionFive', {filter: dashboard.dateFilter}))
    .then(({ data }) => {
      if (data.status == 'error') throw (data.response.message)
      dashboard.pengaduanBySkpd = data.data.pengaduanBySkpd
      createPengaduanSKPDBarChart(data.data.pengaduanBySkpd)
    })
    .catch(error => {
      _alert.fire('Terjadi Kesalahan', error.response.data.message, 'error')
    })
    isLoading.sectionFive = false
  }, 2000);
}


const createPieChart = (newValue) => {
  let jsonData = newValue

  let data = {};
  let label = [];
  jsonData.forEach(function(e) {
      label.push(e.name);
      data[e.name] = e.total;
  })
  _c3.generate({
    bindto: '#pieChart', // ID elemen DOM yang akan menampilkan grafik
    data: {
      json: [ data ],
      type : 'pie',
      keys: {
        value: label,
      },
      empty: {
          label: {
              text: "Data tidak ditemukan"
          }
      },
    },
    pie: {
        label: {
            format: function (value) {
                return value;
            }
        }
    },
    tooltip: {
        format: {
            value: function (value) {
                return value;
            }
        }
    },
  });

}

const createDonutChart = (newValue) => {
  let jsonData = newValue

  let data = {};
  let label = [];
  jsonData.forEach(function(e) {
      label.push(e.name);
      data[e.name] = e.total;
  })
  _c3.generate({
    bindto: '#donutChart', // ID elemen DOM yang akan menampilkan grafik
    data: {
      json: [ data ],
      type : 'donut',
      keys: {
        value: label,
      },
      empty: {
          label: {
              text: "Data tidak ditemukan"
          }
      },
    },
    donut: {
        label: {
            format: function (value) {
                return value;
            }
        }
    },
    tooltip: {
        format: {
            value: function (value) {
                return value;
            }
        }
    },
  });

}

const createPengaduanBarChart = (newValue) => {

  if (chartColor.pengaduanBarChartColor.length != newValue.length){
    let color = []
    newValue.forEach(function() {
          color.push(getColor());
    })
    chartColor.pengaduanBarChartColor = color
  }

  _c3.generate({
    bindto: '#pengaduanBarChart', // ID elemen DOM yang akan menampilkan grafik
    data: {
        json: newValue,
        keys: {
            x: 'name',
            value: ['total']
        },
        type : 'bar',
        color:  (color,d) => {return chartColor.pengaduanBarChartColor[d.x]},
        empty: {
          label: {
              text: "Data tidak ditemukan"
          }
        },

    },
    legend: {
        show: false
    },
    axis: {
      x: {
        show: false,
        type: 'category'
      }
    },
    grid: {
      y: {
        show: true
      }
    },
    bar: {
      space: 0.2,
      width:{
        ratio: 0.7,
        max: 100,
      },
    },
  });
}

const createLayananBarChart = (newValue) => {

  if (chartColor.layananBarChartColor.length != newValue.length){
    let color = []
    newValue.forEach(function() {
          color.push(getColor());
    })
    chartColor.layananBarChartColor = color
  }

  _c3.generate({
    bindto: '#layananBarChart', // ID elemen DOM yang akan menampilkan grafik
    data: {
        json: newValue,
        keys: {
            x: 'name',
            value: ['total']
        },
        type : 'bar',
        color:  (color,d) => {return chartColor.layananBarChartColor[d.x]},
        empty: {
            label: {
                text: "Data tidak ditemukan"
            }
        },

    },
    legend: {
        show: false
    },
    axis: {
      x: {
        show: false,
        type: 'category'
      }
    },
    grid: {
      y: {
        show: true
      }
    },
    bar: {
      space: 0.2,
      width:{
        ratio: 0.7,
        max: 100,
      },
    },
  });
}

const createLayananSKPDBarChart = (newValue) => {

  if (chartColor.layananSkpdBarChartColor.length != newValue.length){
    let color = []
    newValue.forEach(function() {
          color.push(getColor());
    })
    chartColor.layananSkpdBarChartColor = color
  }

  _c3.generate({
    bindto: '#layananSKPDBarChart', // ID elemen DOM yang akan menampilkan grafik
    data: {
        json: newValue,
        keys: {
            x: 'name',
            value: ['total']
        },
        type : 'bar',
        color:  (color,d) => {return chartColor.layananSkpdBarChartColor[d.x]},
        empty: {
            label: {
                text: "Data tidak ditemukan"
            }
        },
    },
    legend: {
        show: false
    },
    axis: {
      x: {
        show: false,
        type: 'category'
      }
    },
    grid: {
      y: {
        show: true
      }
    },
    bar: {
      space: 0.2,
      width:{
        ratio: 0.7,
        max: 100,
      },
    },
  });
}

const createPengaduanSKPDBarChart = (newValue) => {

  if (chartColor.pengaduanSkpdBarChartColor.length != newValue.length){
    let color = []
    newValue.forEach(function() {
          color.push(getColor());
    })
    chartColor.pengaduanSkpdBarChartColor = color
  }

  _c3.generate({
    bindto: '#pengaduanSKPDBarChart', // ID elemen DOM yang akan menampilkan grafik
    data: {
        json: newValue,
        keys: {
            x: 'name',
            value: ['total']
        },
        type : 'bar',
        color:  (color,d) => {return chartColor.pengaduanSkpdBarChartColor[d.x]},
        empty: {
            label: {
                text: "Data tidak ditemukan"
            }
        },
    },
    legend: {
        show: false
    },
    axis: {
      x: {
        show: false,
        type: 'category'
      }
    },
    grid: {
      y: {
        show: true
      }
    },
    bar: {
      space: 0.2,
      width:{
        ratio: 0.7,
        max: 100,
      },
    },
  });
}

const onChangeDateRangePicker = (event) => {
  let startDate = _moment(event.value.start).startOf('day').format('YYYY-MM-DD HH:mm:ss')
  let endDate = _moment(event.value.end).endOf('day').format('YYYY-MM-DD HH:mm:ss')
  event.value.start = startDate
  event.value.end = endDate
  dashboard.dateFilter = event
  Promise.resolve(sectionOne()).then(()=>{
    sectionTwo()
  }).then(() => {
    sectionFour()
  }).then(() => {
    sectionFive()
  })
}
</script>

<template>
  <div class="dashboard-wrapper">
    <div class="panel">
      <div class="mb-0 panel-tag head-section-one">
        <h6 class="pt-2 fw-900 d-inline-block">Total Pengajuan</h6>
        <div class="float-right bg-white d-inline-block date-filter" style="width: 225px;">
          <DateRangePicker style="width: 100% !important;" @on-change="onChangeDateRangePicker($event)"></DateRangePicker>
        </div>
      </div>
      <div class="pt-4 pb-4 row section-one">
        <div class="col-sm-12 col-md-4">
          <div
            v-if="isLoading.sectionOne==false"
            class="p-3 overflow-hidden text-white rounded bg-primary-300 position-relative">
            <div class="">
              <h3 class="m-0 d-block l-h-n fw-500">
                <small class= "m-0 l-h-n">Pengaduan</small>
                {{ dashboard.jmlPengaduan }}
              </h3>
            </div>
            <i class="fal fa-envelope position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1" style="font-size:6rem"></i>
          </div>
          <div
            v-if="isLoading.sectionOne==true"
            class="skeleton-loading-screen">
            <div class="p-5 overflow-hidden text-white rounded bg-light position-relative animated-background">
              <div class="background-masker">
                <h3 class="m-0 d-block l-h-n fw-500">
                </h3>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-12 col-md-4">
          <div
            v-if="isLoading.sectionOne==false"
            class="p-3 overflow-hidden text-white rounded bg-danger-400 position-relative">
            <div class="">
              <h3 class="m-0 d-block l-h-n fw-500">
                <small class="m-0 l-h-n">Layanan</small>
                {{ dashboard.jmlLayanan }}
              </h3>
            </div>
            <i class="fal fa-comment position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4" style="font-size: 6rem;"></i>
          </div>
          <div
            v-if="isLoading.sectionOne==true"
            class="skeleton-loading-screen">
            <div class="p-5 overflow-hidden text-white rounded bg-light position-relative animated-background">
              <div class="background-masker">
                <h3 class="m-0 d-block l-h-n fw-500">
                </h3>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-12 col-md-4">
          <div
            v-if="isLoading.sectionOne==false"
            class="p-3 overflow-hidden text-white rounded bg-info-200 position-relative">
            <div class="">
              <h3 class="m-0 d-block l-h-n fw-500">
                <small class="m-0 l-h-n">Extend Tindak Lanjut</small>
                {{ dashboard.jmlExtendTl }}
              </h3>
            </div>
            <i class="fal fa-bell position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4" style="font-size: 6rem;"></i>
          </div>
          <div
            v-if="isLoading.sectionOne==true"
            class="skeleton-loading-screen">
            <div class="p-5 overflow-hidden text-white rounded bg-light position-relative animated-background">
              <div class="background-masker">
                <h3 class="m-0 d-block l-h-n fw-500">
                </h3>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row section-two">
      <div class="col-sm-6 col-xl-3">
        <div
          v-if="isLoading.sectionTwo==false"
          class="panel">
          <div class="mb-0 panel-tag">
            <h6 class="fw-900">Layanan By Status</h6>
          </div>
          <div id="pieChart">
          </div>
        </div>
        <div
          v-if="isLoading.sectionTwo==true"
          class="skeleton-loading-screen">
          <div class="panel animated-background" style="height: 250px;">
            <div class="background-masker">
            </div>
          </div>
        </div>

      </div>
      <div class="col-sm-6 col-xl-3">
        <div
          v-if="isLoading.sectionTwo==false"
          class="panel">
          <div class="mb-0 panel-tag">
            <h6 class="fw-900">Pengaduan By Status</h6>
          </div>
          <div id="donutChart">
          </div>
        </div>
        <div
          v-if="isLoading.sectionTwo==true"
          class="skeleton-loading-screen">
          <div class="panel animated-background" style="height: 250px;">
            <div class="background-masker">
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-xl-3">
        <div
          v-if="isLoading.sectionTwo==false"
          class="panel">
          <div class="mb-0 panel-tag">
            <h6 class="fw-900">Top 5 Pengaduan</h6>
          </div>
          <div id="topPengaduan">
            <table
              v-if="dashboard.top5Pengaduan.length > 0"
              class="table mt-4 cursor-pointer table-striped table-hover">
              <tr
                v-for="item in dashboard.top5Pengaduan" :key="item.name">
                <th>{{ item.name }}</th>
                <td>{{ item.total }}</td>
              </tr>
            </table>
            <div
              v-if="dashboard.top5Pengaduan.length < 1"
              class="p-3 text-center"
              style="font-family: sans-serif;font-size: 1.4em !important;color: #888888;"
            ><span>Data tidak ditemukan</span></div>
          </div>
        </div>
        <div
          v-if="isLoading.sectionTwo==true"
          class="skeleton-loading-screen">
          <div class="panel animated-background" style="height: 250px;">
            <div class="background-masker">
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-xl-3">
        <div
          v-if="isLoading.sectionTwo==false"
          class="panel">
          <div class="mb-0 panel-tag">
            <h6 class="fw-900">Top 5 Layanan</h6>
          </div>
          <div id="topLayanan">
            <table
              v-if="dashboard.top5Layanan.length > 0"
              class="table mt-4 cursor-pointer table-striped table-hover">
              <tr
                v-for="item in dashboard.top5Layanan" :key="item.name">
                <th>{{ item.name }}</th>
                <td>{{ item.total }}</td>
              </tr>
            </table>
            <div
              v-if="dashboard.top5Layanan.length < 1"
              class="p-3 text-center"
              style="font-family: sans-serif;font-size: 1.4em !important;color: #888888;"
            ><span>Data tidak ditemukan</span></div>
          </div>
        </div>
        <div
          v-if="isLoading.sectionTwo==true"
          class="skeleton-loading-screen">
          <div class="panel animated-background" style="height: 250px;">
            <div class="background-masker">
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row section-three">
      <div class="col-sm-12 col-xl-6">
        <div
          v-if="isLoading.sectionTwo==false"
          class="panel">
          <div class="mb-0 panel-tag">
            <h6 class="fw-900">Pengaduan By Kategori</h6>
          </div>
          <div id="pengaduanBarChart">
          </div>
        </div>
        <div
          v-if="isLoading.sectionTwo==true"
          class="skeleton-loading-screen">
          <div class="panel animated-background" style="height: 250px;">
            <div class="background-masker">
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-12 col-xl-6">
        <div
          v-if="isLoading.sectionTwo==false"
          class="panel">
          <div class="mb-0 panel-tag">
            <h6 class="fw-900">Layanan By Kategori</h6>
          </div>
          <div id="layananBarChart">
          </div>
        </div>
        <div
          v-if="isLoading.sectionTwo==true"
          class="skeleton-loading-screen">
          <div class="panel animated-background" style="height: 250px;">
            <div class="background-masker">
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row section-four">
      <div class="col-sm-12 col-xl-12">
        <div
          v-if="isLoading.sectionFour==false"
          class="panel">
          <div class="mb-0 panel-tag">
            <h6 class="fw-900">Layanan By SKPD</h6>
          </div>
          <div id="layananSKPDBarChart">
          </div>
        </div>
        <div
          v-if="isLoading.sectionFour==true"
          class="skeleton-loading-screen">
          <div class="panel animated-background" style="height: 250px;">
            <div class="background-masker">
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row section-five">
      <div class="col-sm-12 col-xl-12">
        <div
          v-if="isLoading.sectionFive==false"
          class="panel">
          <div class="mb-0 panel-tag">
            <h6 class="fw-900">Pengaduan By SKPD</h6>
          </div>
          <div id="pengaduanSKPDBarChart">
          </div>
        </div>
        <div
          v-if="isLoading.sectionFive==true"
          class="skeleton-loading-screen">
          <div class="panel animated-background" style="height: 250px;">
            <div class="background-masker">
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row section-six">
      <div class="col-sm-12 col-xl-12">
        <div class="panel">
          <div>
            <!-- <b-tabs content-class="pl-3 pr-3 mt-3" class="tabs-dashboard">
              <b-tab active>
                <template #title>
                  <h6 class="fw-900">Daftar Pengajuan Layanan</h6>
                </template>
                <GridDaftarLayanan :filter-val="dashboard.dateFilter"/>
              </b-tab>
              <b-tab>
                <template #title>
                  <h6 class="fw-900">Daftar Pengajuan Aduan</h6>
                </template>
                <GridDaftarPengaduan :filter-val="dashboard.dateFilter" is-user/>
              </b-tab>
              <b-tab>
                <template #title>
                  <h6 class="fw-900">Daftar Extend Tindak Lanjut</h6>
                </template>
                <GridDaftarExtendTl :filter-val="dashboard.dateFilter" is-user/>
              </b-tab>
            </b-tabs> -->
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style lang="scss">
  .dashboard-wrapper{
    .panel{
      .panel-tag{
        opacity: 1;
      }
    }

    .tabs-dashboard{
      a{
        text-decoration: none !important;
      }
    }

    .head-section-one{
      position: relative !important;
      z-index: 9 !important;

      .date-filter{
        width: 200px;

      }
    }



  }
</style>
