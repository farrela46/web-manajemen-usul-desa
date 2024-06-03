<script>
// import { useRoute } from "vue-router";
import axios from "axios";
import BASE_URL from '@/api/config-api';
import ArgonButton from "@/components/ArgonButton.vue";
// import Breadcrumbs from '@/components/Vuetify/Breadcrumbs.vue';
import * as bootstrap from 'bootstrap';
import moment from 'moment';

export default {
  components: {
    ArgonButton,
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
      users: [],
      overlay: false,
      showModal: false,
      dialog: false,
      dialogTolak: false,
      selectedUsulanId: null,
      selectedFilter: '',
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

    };
  },
  mounted() {
    this.retrieveUser();
  },
  methods: {
    formatDate(data_date) {
      return moment.utc(data_date).format('YYYY-MM-DD')
    },
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
        this.AcceptWarga(this.selectedUsulanId);
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
        this.RejectWarga(this.selectedUsulanId);
        this.closeModalReject();
      }
    },
    async AcceptWarga(id) {
      try {
        const response = await axios.get(`${BASE_URL}/auth/verify/` + id, {
          headers: {
            Authorization: 'Bearer ' + localStorage.getItem('access_token'),
          },
        });
        // this.$notify({
        //   type: 'success',
        //   title: 'Success',
        //   text: response.data.message,
        //   color: 'green'
        // });
        console.log(response)
        this.closeModalAccept();
        this.dialog = true
        this.retrieveUser();
      } catch (error) {
        console.error(error);
      }
    },
    async RejectWarga(id) {
      try {
        const response = await axios.get(`${BASE_URL}/auth/reject/` + id, {
          headers: {
            Authorization: 'Bearer ' + localStorage.getItem('access_token'),
          },
        });
        this.retrieveUser();
        console.log(response)
        // this.$notify({
        //   type: 'success',
        //   title: 'Success',
        //   text: 'Warga telah diverifikasi',
        //   color: 'green'
        // });
        this.closeModalReject();
        this.dialogTolak = true
      } catch (error) {
        console.error(error);
      }
    },
    async retrieveUser() {
      try {
        this.overlay = true;
        const response = await axios.get(`${BASE_URL}/auth/index`, {
          headers: {
            Authorization: "Bearer " + localStorage.getItem('access_token')
          },
          params: {
            status: this.selectedFilter // Include the selected filter in the query parameters
          }
        });
        this.users = response.data;

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
            <div class="row ps-3 mb-2">
              Filter:
              <div class="col-2">
                <select class="form-select form-select-sm" aria-label="Small select example" v-model="selectedFilter"  @change="retrieveUser">
                  <option value="" selected>Semua</option>
                  <option value="unverified">Unverified</option>
                  <option value="verified">Verified</option>
                  <option value="rejected">Rejected</option>
                </select>
              </div>
            </div>
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
                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                            NIK
                          </th>
                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            Nama
                          </th>
                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                            Tanggal Daftar
                          </th>
                          <th
                            class="text-uppercase text-center text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                            Status
                          </th>
                          <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            Aksi
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(item, index) in users" :key="item.id">
                          <td>
                            <div class="px-2 py-1">
                              <div class="d-flex justify-content-center">
                                <h6 class="mb-0 text-sm">{{ index + 1 }}</h6>
                              </div>
                            </div>
                          </td>
                          <td class="align-middle text-start">
                            <span class="text-black text-xs font-weight-bold">{{ item.NIK }}</span>
                          </td>
                          <td class="align-middle text-start">
                            <span class="text-black text-xs font-weight-bold">{{ item.nama }}</span>
                          </td>
                          <td class="align-middle text-start">
                            <span class="text-black text-xs font-weight-bold">{{ formatDate(item.created_at) }}</span>
                          </td>
                          <td class="align-middle text-center">
                            <span class="text-black text-xs font-weight-bold"> <v-chip v-if="item.status === 'verified'"
                                color="green">
                                Verified
                              </v-chip>
                              <v-chip v-else color="red">
                                Pending
                              </v-chip>
                            </span>
                          </td>
                          <td v-if="item.status === 'unverified'" class="align-middle text-center">
                            <span class="p-2">
                              <argon-button size="sm" @click="openAcceptConfirmation(item.id)">Terima
                              </argon-button>
                            </span>
                            <span class="p-2">
                              <argon-button color="danger" size="sm" @click="openRejectConfirmation(item.id)">
                                Tolak</argon-button>
                            </span>
                            <!-- <span class="mx-3" style="font-size: 1rem; cursor: pointer;" @click="openAcceptConfirmation(item.id)">
                              <span style="color:green;">
                                <i class="fas fa-check-circle" ></i>
                              </span>
                            </span>
                            <span style="font-size: 1rem; cursor: pointer;" @click="openRejectConfirmation(item.id)">
                              <span style="color:red;">
                                <i class="fas fa-times-circle" ></i>
                              </span>
                            </span> -->
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
              Setujui warga ini??
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-danger" @click="confirmAccept">Terima</button>
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
              Tolak warga ini??
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-danger" @click="confirmReject">Tolak</button>
            </div>
          </div>
        </div>
      </div>
      <v-dialog v-model="dialog" max-width="400">
        <v-card class="text-center">
          <v-card-text>
            <div class="p-2">
              <h3>Verifikasi pengguna berhasil!</h3>
              <v-icon color="blue" size="80">mdi-checkbox-marked-circle-outline</v-icon>
            </div>
          </v-card-text>
        </v-card>
      </v-dialog>
      <v-dialog v-model="dialogTolak" max-width="400">
        <v-card class="text-center">
          <v-card-text>
            <div class="p-2">
              <h3>Pengguna berhasil ditolak!</h3>
              <v-icon color="blue" size="80">mdi-checkbox-marked-circle-outline</v-icon>
            </div>
          </v-card-text>
        </v-card>
      </v-dialog>
    </div>
  </div>
</template>
<style></style>