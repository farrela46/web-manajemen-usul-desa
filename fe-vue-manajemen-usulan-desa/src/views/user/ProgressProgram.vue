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
      overlay: false,
      selectedProgressId: null,
      breadcrumbsItems: [
        {
          title: 'Program',
          disabled: false,
          href: '/program',
        },
        {
          title: 'Progress Program',
          disabled: true,
          href: '/',
        }
      ],
      progress: {},
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
      this.store.state.showSidenav = false;
      this.store.state.showFooter = false;
      this.body.classList.remove("bg-gray-100");
    },
    restorePage() {
      this.store.state.hideConfigButton = false;
      this.store.state.showNavbar = true;
      this.store.state.showSidenav = false;
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
        
        const response = await axios.delete(`${BASE_URL}/progress/delete/` + idprogress, {
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
        this.retrieveProgress();
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
        <div class="card ps-3 pt-2" style="background-color: #E9F5E9;">
          <h3>{{ progress.title }}</h3>
          <div class="p-2">
            <div class="row">
              <div class="col-12">
                <div class="card px-4">
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
                                <span class="text-black text-xs font-weight-bold">{{ item.name }}</span>
                              </td>
                              <td class="align-middle text-start">
                                <span class="text-black text-xs font-weight-bold">{{ item.start_date }}</span>
                              </td>
                              <td class="align-middle text-start">
                                <span class="text-black text-xs font-weight-bold">{{ item.end_date }}</span>
                              </td>
                              <td class="align-middle text-start">
                                <div class="d-flex">
                                  <img v-for="(url, imgIndex) in item.imageUrls" :key="imgIndex" :src="url"
                                    class="img-thumbnail" alt="..." style=" margin-right: 2px;">
                                </div>
                              </td>
                              <td class="align-middle text-center">
                                <argon-button  @click="editProgress(item.id_progress)" style="background-color: #514094;">Lihat Progress</argon-button>
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
        <div class="modal fade" id="hapusConfirmationModal" tabindex="-1" aria-labelledby="usulanConfirmationModalLabel"
          aria-hidden="true">
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
  width: 100px;
  height: 70px;
  object-fit: cover;
}
</style>