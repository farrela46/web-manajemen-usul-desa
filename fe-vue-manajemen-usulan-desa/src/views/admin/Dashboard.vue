<script>
import axios from "axios";
import BASE_URL from '@/api/config-api';
import MiniStatisticsCard from "@/examples/Cards/MiniStatisticsCard.vue";

export default {
  components: {
    MiniStatisticsCard,
  },
  data() {
    return {
      overlay: false,
      dashboard: false,
      dashboardData: {},
      donut: {
        options: {
          theme: 'light2',
          animationEnabled: true,
          title: {
            text: 'Persetujuan',
          },
          data: [
            {
              type: 'pie',
              indexLabel: '{label} (#percent%)',
              yValueFormatString: '#,##0',
              toolTipContent: "<span style='\"'color: {color};'\"'>{label}</span> {y}(#percent%)",
              dataPoints: [],
            },
          ],
        },
        styleOptions: {
          width: '100%',
          height: '360px',
        },
      },
      cards: [],
      program: []
    };
  },
  created() {
    this.store = this.$store;
    this.body = document.getElementsByTagName("body")[0];
    this.setupPage();
  },
  beforeUnmount() {
    this.restorePage();
  },
  mounted() {
    this.retrieveDashboard();
  },
  methods: {
    setupPage() {
      this.store.state.hideConfigButton = true;
      this.store.state.showNavbar = true;
      this.store.state.showSidenav = true;
      this.store.state.showFooter = false;
      this.body.classList.remove("bg-gray-100");
    },
    restorePage() {
      this.store.state.hideConfigButton = false;
      this.store.state.showNavbar = true;
      this.store.state.showSidenav = true;
      this.store.state.showFooter = true;
      this.body.classList.add("bg-gray-100");
    },
    async retrieveDashboard() {
      try {
        this.overlay = true;
        const response = await axios.get(`${BASE_URL}/program/dashboard`, {
          headers: {
            Authorization: "Bearer " + localStorage.getItem('access_token')
          }
        });
        this.dashboardData = response.data;
        this.program = response.data.programs;

        this.donut.options.data[0].dataPoints = [
          { label: 'Setuju', y: this.dashboardData.persetujuan.disetujui, color: '#4DDA5B' },
          { label: 'Menolak', y: this.dashboardData.persetujuan.ditolak, color: '#F85555' },
        ];

        this.cards = [
          {
            title: 'Total Usulan',
            value: this.dashboardData.total_usulan,
            description: 'Total Usulan Warga',
            icon: {
              component: 'fas fa-vote-yea',
              background: 'bg-gradient-primary',
              shape: 'rounded-circle',
            },
          },
          {
            title: 'Total Program',
            value: this.dashboardData.total_program,
            description: 'Total Program Dilaksanakan',
            icon: {
              component: 'fas fa-th-list',
              background: 'bg-gradient-danger',
              shape: 'rounded-circle',
            },
          },
          {
            title: 'Program Disetujui',
            value: this.dashboardData.usulan_disetujui,
            description: 'Jumlah Program Disetujui',
            icon: {
              component: 'fas fa-check',
              background: 'bg-gradient-success',
              shape: 'rounded-circle',
            },
          },
          {
            title: 'Program Ditolak',
            value: this.dashboardData.usulan_ditolak,
            description: 'Jumlah Program Ditolak',
            icon: {
              component: 'fas fa-times',
              background: 'bg-gradient-danger',
              shape: 'rounded-circle',
            },
          },
        ];
      } catch (error) {
        console.error(error);
      } finally {
        this.overlay = false;
        this.dashboard = true;
      }
    },
  }
};

</script>
<template>
  <div class="py-4 container-fluid">
    <v-overlay :model-value="overlay" class="d-flex align-items-center justify-content-center">
      <v-progress-circular color="primary" size="96" indeterminate></v-progress-circular>
    </v-overlay>
    <div class="row" v-if="dashboard">
      <div class="col-lg-12">
        <div class="row">
          <div class="col-lg-3 col-md-6 col-12" v-for="card in cards" :key="card.title">
            <mini-statistics-card :title="card.title" :value="card.value" :description="card.description"
              :icon="card.icon" />
          </div>
          <div class="col-lg-7 mt-2">
            <div class="card h-100 w-100">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-center text-secondary text-sm font-weight-bolder opacity-7">
                        No
                      </th>
                      <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                        Nama Program
                      </th>
                      <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7 ps-2">
                        Progress
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(program, index) in program" :key="index">
                      <td>
                        <div class="px-2 py-1">
                          <div class="d-flex justify-content-center">
                            <h6 class="mb-0 text-md">{{ index + 1 }}</h6>
                          </div>
                        </div>
                      </td>
                      <td class="align-left text-start">
                        <span class="text-black text-md font-weight-bold ">{{ program.name }}</span>
                      </td>
                      <td class="align-middle text-start">
                        <div class="d-flex px-2 py-1">
                          <div style="width: 300px;">
                            <h6 class="text-black text-md font-weight-bold text-truncate mb-0">
                              {{ program.progress }}
                            </h6>
                          </div>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-lg-5 mt-2">
            <div class="card p-2" style="border-radius: 20px;">
              <CanvasJSChart :options="donut.options" :styles="donut.styleOptions" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
