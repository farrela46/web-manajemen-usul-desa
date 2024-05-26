<script>
// import { useRoute } from "vue-router";
import axios from "axios";
import BASE_URL from '@/api/config-api';
// import ArgonButton from "@/components/ArgonButton.vue";
// import Breadcrumbs from '@/components/Vuetify/Breadcrumbs.vue';
import * as bootstrap from 'bootstrap';

export default {
  components: {
    // ArgonButton,
    // Breadcrumbs
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
      showModal: false,
      selectedUsulanId: null,
      breadcrumbsItems: [
        {
          title: 'Home',
          disabled: false,
          href: '/dashboard',
        },
        {
          title: '',
          disabled: true,
          href: '/',
        }
      ],
      usulan: [
        {
          usulan: 'Ruang tebuka untuk bernapas',
          tanggal: '15-08-2024',
          komentar: '49',
          upvote: '19890',
          downvote: '289'
        },
        {
          usulan: 'Penyediaan Ruang untuk modifikasi',
          tanggal: '15-08-2024',
          komentar: '49',
          upvote: '210239',
          downvote: '289'
        },
        {
          usulan: 'Pengusiran Fikri',
          tanggal: '15-08-2024',
          komentar: '49',
          upvote: '81989999',
          downvote: '0'
        },
      ]
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
    goUsulan() {
      this.$router.push('/usulan');
    },
    formatPrice(price) {
      const numericPrice = parseFloat(price);
      return numericPrice.toLocaleString('id-ID');
    },
    openAcceptConfirmation(id) {
      this.selectedUsulanId = id;
      let modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('acceptConfirmationModal'))
      modal.show();
    },
    closeModalAccept() {
      let modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('acceptConfirmationModal'))
      modal.hide();
    },
    confirmAccept() {
      if (this.selectedUsulanId) {
        this.AcceptUsulan(this.selectedUsulanId);
        this.closeModalAccept();
      }
    },
    openRejectConfirmation(id) {
      this.selectedUsulanId = id;
      let modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('rejectConfirmationModal'))
      modal.show();
    },
    closeModalReject() {
      let modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('rejectConfirmationModal'))
      modal.hide();
    },
    confirmReject() {
      if (this.selectedUsulanId) {
        this.RejectUsulan(this.selectedUsulanId);
        this.closeModalReject();
      }
    },
    async AcceptUsulan(id) {
      try {
        const response = await axios.delete(`${BASE_URL}/deleteUser/` + id, {
          headers: {
            Authorization: 'Bearer ' + localStorage.getItem('access_token'),
          },
        });
        console.log(response)
        this.$notify({
          type: 'success',
          title: 'Success',
          text: 'Usulan berhasil diterima',
          color: 'green'
        });
        this.getAllUser();
      } catch (error) {
        console.error(error);
      }
    },
    async RejectUsulan(id) {
      try {
        const response = await axios.delete(`${BASE_URL}/deleteUser/` + id, {
          headers: {
            Authorization: 'Bearer ' + localStorage.getItem('access_token'),
          },
        });
        console.log(response)
        this.$notify({
          type: 'success',
          title: 'Success',
          text: 'Usulan berhasil ditolak',
          color: 'green'
        });
        this.getAllUser();
      } catch (error) {
        console.error(error);
      }
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
                          <th class="text-uppercase text-center text-secondary text-xxs font-weight-bolder opacity-7">
                            No
                          </th>
                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            Usulan
                          </th>
                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                            Tanggal
                          </th>
                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                            Komentar
                          </th>
                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                            <v-icon icon="mdi-arrow-up-bold-outline" start></v-icon> Up Vote
                          </th>
                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                            <v-icon icon="mdi-arrow-down-bold-outline" start></v-icon> Down Vote
                          </th>
                          <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            Aksi
                          </th>
                          <th class="text-secondary opacity-7"></th>
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
                            <span class="text-black text-xs font-weight-bold">{{ item.usulan }}</span>
                          </td>
                          <td class="align-middle text-start">
                            <span class="text-black text-xs font-weight-bold">{{ item.tanggal }}</span>
                          </td>
                          <td class="align-middle text-start">
                            <span class="text-black text-xs font-weight-bold">{{ item.komentar }}</span>
                          </td>
                          <td class="align-middle text-start">
                            <span class="text-black text-xs font-weight-bold">{{ item.upvote }}</span>
                          </td>
                          <td class="align-middle text-start">
                            <span class="text-black text-xs font-weight-bold">{{ item.downvote }}</span>
                          </td>
                          <td class="align-middle text-center">
                            <span style="font-size: 1rem; cursor: pointer;" @click="editUser(user.id)">
                              <span style="color: black;">
                                <i class="fa fa-pencil-square-o"></i>
                              </span>
                            </span>
                            <span class="mx-3" style="font-size: 1rem; cursor: pointer;"
                              @click="openAcceptConfirmation(item.id)">
                              <span style="color:green;">
                                <i class="fas fa-check-circle"></i>
                              </span>
                            </span>
                            <span style="font-size: 1rem; cursor: pointer;" @click="openRejectConfirmation(item.id)">
                              <span style="color:red;">
                                <i class="fas fa-times-circle"></i>
                              </span>
                            </span>
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
      <div class="modal fade" id="acceptConfirmationModal" tabindex="-1" aria-labelledby="usulanConfirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title text-black" id="deleteConfirmationModalLabel">Konfirmasi</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              Setujui Usulan ini??
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-danger" @click="confirmAccept">Accept</button>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="rejectConfirmationModal" tabindex="-1" aria-labelledby="usulanConfirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title text-black" id="deleteConfirmationModalLabel">Konfirmasi Upvote</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              Tolak Usulan ini??
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-danger" @click="confirmReject">Tolak</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style></style>