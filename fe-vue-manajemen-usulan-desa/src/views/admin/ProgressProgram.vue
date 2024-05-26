<script>
// import { useRoute } from "vue-router";
import axios from "axios";
import BASE_URL from '@/api/config-api';
import ArgonButton from "@/components/ArgonButton.vue";
import Breadcrumbs from '@/components/Vuetify/Breadcrumbs.vue';

export default {
  components: {
    ArgonButton,
    Breadcrumbs
  },
  created() {
    this.store = this.$store;
    this.body = document.getElementsByTagName("body")[0];
    this.setupPage();
  },
  beforeUnmount() {
    this.restorePage();
  },
  data() {
    return {
      products: [],
      overlay: false,
      breadcrumbsItems: [
        {
          title: 'Program',
          disabled: false,
          href: '/admin/program',
        },
        {
          title: 'Tambah Progress',
          disabled: true,
          href: '/',
        }
      ],
      progress: {
        id: 1,
        title: 'Pembaruan mesin RB22'
      },
      usulan: [
        {
          id: 1,
          progress: 'Pengadaan Klep Mesin',
          tanggal_mulai: '20-04-2022',
          tanggal_selesai: '22-04-2022',
          images: [
            '../../assets/img/bruce-mars.jpg',
            '../../assets/img/bruce-mars.jpg',
            '../../assets/img/bruce-mars.jpg',
            '../../assets/img/bruce-mars.jpg',
            '../../assets/img/bruce-mars.jpg'
          ]
        },
        {
          id: 2,
          progress: 'Pengadaan Mesin',
          tanggal_mulai: '20-04-2022',
          tanggal_selesai: '22-04-2022',
          images: [
            '../../assets/img/bruce-mars.jpg',
            '../../assets/img/bruce-mars.jpg',
            '../../assets/img/bruce-mars.jpg',
            '../../assets/img/bruce-mars.jpg',
            '../../assets/img/bruce-mars.jpg'
          ]
        },
      ],

    };
  },
  mounted() {

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
    goTambah() {
      this.$router.push('/admin/program/progress/' + this.$route.params.idprogram + '/tambah');
    },
    
    formatPrice(price) {
      const numericPrice = parseFloat(price);
      return numericPrice.toLocaleString('id-ID');
    },
    async retrieveBuku() {
      try {
        this.overlay = true;
        const response = await axios.get(`${BASE_URL}/buku/get`, {
          headers: {
            Authorization: "Bearer " + localStorage.getItem('access_token')
          }
        });
        this.products = response.data;

        if (response.data.length > 0) {
          this.fotoUrl = response.data[0].foto;
        }
      } catch (error) {
        console.error(error);
      } finally {
        this.overlay = false
      }
    },
    editProgress(idprogress) {
      this.$router.push('/admin/program/progress/' + this.$route.params.idprogram + '/edit/' + idprogress);
    },
  },
};
</script>

<template>
  <div class="py-4 container-fluid">
    <div class="row mt-3">
      <v-overlay :model-value="overlay" class="d-flex align-items-center justify-content-center">
        <v-progress-circular color="primary" size="96" indeterminate></v-progress-circular>
      </v-overlay>
      <div class="container">
        <Breadcrumbs class="d-flex align-items-center" :items="breadcrumbsItems" />
        <div class="card ps-3 pt-2">
          <h3>{{ progress.title }}</h3>
          <div class="p-2">
            <div class="row mb-2">
              <div class="col-12 d-flex justify-content-end">
                <argon-button color="primary" size="sm" @click="goTambah"> <i class="fas fa-plus mx-2"></i> Tambah
                  Progress</argon-button>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="card px-4" style="border-radius: 10px; 
              /* background-color: #E9F5E9; */
              ">
                  <div class="row mt-2 mb-2">
                    <div class="row">
                      <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                          <thead>
                            <tr>
                              <th
                                class="text-uppercase text-center text-secondary text-xxs font-weight-bolder opacity-7">
                                No
                              </th>
                              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Progress
                              </th>
                              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                Tanggal Mulai
                              </th>
                              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                Tanggal Selesai
                              </th>
                              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                Gambar
                              </th>
                              <th
                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Aksi
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(item, index) in usulan" :key="item.id">
                              <td>
                                <div class="px-2 py-1">
                                  <div class="d-flex justify-content-center">
                                    <h6 class="mb-0 text-sm">{{ index + 1 }}</h6>
                                  </div>
                                </div>
                              </td>
                              <td class="align-middle text-start">
                                <span class="text-black text-xs font-weight-bold">{{ item.progress }}</span>
                              </td>
                              <td class="align-middle text-start">
                                <span class="text-black text-xs font-weight-bold">{{ item.tanggal_mulai }}</span>
                              </td>
                              <td class="align-middle text-start">
                                <span class="text-black text-xs font-weight-bold">{{ item.tanggal_selesai }}</span>
                              </td>
                              <td class="align-middle text-start">
                                <div class="d-flex">
                                  <img src="../../assets/img/bruce-mars.jpg" class="img-thumbnail" alt="...">
                                  <img src="../../assets/img/bruce-mars.jpg" class="img-thumbnail" alt="...">
                                  <img src="../../assets/img/bruce-mars.jpg" class="img-thumbnail" alt="...">
                                </div>
                              </td>
                              <td class="align-middle text-center">
                                <argon-button color="white" @click="editProgress(item.id)">Edit</argon-button>
                                <argon-button class="mx-2" color="danger">Hapus</argon-button>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style>
.img-thumbnail {
  width: 70px;
  height: 70px;
  object-fit: cover;
}
</style>