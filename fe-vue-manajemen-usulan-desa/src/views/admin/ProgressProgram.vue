<script>
// import { useRoute } from "vue-router";
import axios from "axios";
import BASE_URL from '@/api/config-api';
import ArgonButton from "@/components/ArgonButton.vue";
import Breadcrumbs from '@/components/Vuetify/Breadcrumbs.vue';
import * as bootstrap from 'bootstrap';

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
      selectedProgressId: null,
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
      usulan: [],

    };
  },
  mounted() {
this.retrieveProgress()
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
    async retrieveProgress() {
      try {
        this.overlay = true;
        const response = await axios.get(`${BASE_URL}/progress/index/` + this.$route.params.idprogram, {
          headers: {
            Authorization: "Bearer " + localStorage.getItem('access_token')
          }
        });
        this.usulan = response.data.progresses;
this.progress.title = response.data.nama_program
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
    openHapusConfirmation(idprogress) {
      this.selectedProgressId = idprogress;
      let modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('hapusConfirmationModal'))
      modal.show();
    },
    closeModalDelete() {
      let modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('hapusConfirmationModal'))
      modal.hide();
    },
    confirmDelete() {
      if (this.selectedProgressId) {
        this.deleteProgress(this.selectedProgressId);
        this.closeModalDelete();
      }
    },
    async deleteProgress(idprogress) {
      try {
        const response = await axios.delete(`${BASE_URL}/deleteUser/` + idprogress, {
          headers: {
            Authorization: 'Bearer ' + localStorage.getItem('access_token'),
          },
        });
        console.log(response)
        this.$notify({
          type: 'success',
          title: 'Success',
          text: 'Progress berhasil dihapus',
          color: 'green'
        });
        this.getAllUser();
      } catch (error) {
        console.error(error);
      }
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
                                <argon-button class="mx-2" color="danger"
                                  @click="openHapusConfirmation(item.id)">Hapus</argon-button>
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
        <div class="modal fade" id="hapusConfirmationModal" tabindex="-1"
          aria-labelledby="usulanConfirmationModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title text-black" id="deleteConfirmationModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                Hapus progress ini??
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" @click="confirmDelete">Hapus</button>
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