<script>
import MiniStatisticsCard from "@/examples/Cards/MiniStatisticsCard.vue";




export default {
  components: {
    MiniStatisticsCard,
  },
  data() {
    return {
      donut: {
        options: {
          theme: "light2",
          animationEnabled: true,
          title: {
            text: "Persetujuan"
          },
          data: [{
            type: "pie",
            indexLabel: "{label} (#percent%)",
            yValueFormatString: "#,##0",
            toolTipContent: "<span style='\"'color: {color};'\"'>{label}</span> {y}(#percent%)",
            dataPoints: [
              { label: "Setuju", y: 130631, color: "#4DDA5B" },
              { label: "Menolak", y: 28874, color: "#F85555" },
            ]
          }]
        },
        styleOptions: {
          width: "100%",
          height: "360px"
        }
      },
      cards: [
        {
          title: "Total Usulan",
          value: "130",
          description: "Total Usulan Warga",
          icon: {
            component: "fas fa-vote-yea",
            background: "bg-gradient-primary",
            shape: "rounded-circle",
          },
        },
        {
          title: "Total Program",
          value: "26",
          description: "Total Program Dilaksanakan",
          icon: {
            component: "fas fa-th-list",
            background: "bg-gradient-danger",
            shape: "rounded-circle",
          },
        },
        {
          title: "Program Disetujui",
          value: "3,462",
          description: "Jumlah Program Disetujui",
          icon: {
            component: "fas fa-check",
            background: "bg-gradient-success",
            shape: "rounded-circle",
          },
        },
        {
          title: "Program Ditolak",
          value: "103,430",
          description: "Jumlah Program Ditolak",
          icon: {
            component: "fas fa-times",
            background: "bg-gradient-danger",
            shape: "rounded-circle",
          },
        },
      ],
      program: [
        {
          nama: 'Perbaikan Gapura',
          progress: 'Pembacaan Doa'
        },
        {
          nama: 'Pembuatan Jamban',
          progress: 'Peletakan kayu pertama'
        },
        {
          nama: 'Perbaikan Rumah Pak Romli',
          progress: 'Pembersihan Lahan'
        },
        {
          nama: 'Penangkapan Jamet',
          progress: 'Pembacaan Ikrar Jamet'
        }
      ]
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
    }
  }
};

</script>
<template>
  <div class="py-4 container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="row">
          <div class="col-lg-3 col-md-6 col-12" v-for="card in cards" :key="card.title">
            <mini-statistics-card :title="card.title" :value="card.value" :description="card.description"
              :icon="card.icon" />
          </div>
          <div class="col-lg-7 mt-2">
            <div class="card h-100">
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
                        <span class="text-black text-md font-weight-bold">{{ program.nama }}</span>
                      </td>
                      <td class="align-left text-start">
                        <span class="text-black text-md font-weight-bold">{{ program.progress }}</span>
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
