use bootstrap;
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
      validate: false,
      dialog: false,
      products: [],
      overlay: false,
      breadcrumbsItems: [
        {
          title: 'Program',
          disabled: false,
          href: '/admin/program',
        },
        {
          title: 'Detail Program',
          disabled: true,
          href: '/',
        }
      ],
      form: {
        namaProgram: '',
        deskripsi: '',
        tanggalMulai: '',
        tanggalSelesai: '',
        status: '',
        target: ''
      }
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
    validateForm() {
      if (
        !this.form.namaProgram ||
        !this.form.deskripsi ||
        !this.form.tanggalMulai ||
        !this.form.tanggalSelesai ||
        !this.form.status ||
        !this.form.target
      ) {
        this.validate = true;
      } else {
        this.saveForm();
      }
    },
    async saveFrom() {
      try {

        // const response = await axios.put(BASE_URL + '/user/update', updatedUserData, {
        //   headers: {
        //     Authorization: "Bearer " + localStorage.getItem('access_token')
        //   }
        // });
        this.$notify({
          type: 'success',
          title: 'Success',
          text: 'Program berhasil diubah',
          color: 'green'
        });
        this.$router.push('/admin/program');

      } catch (error) {
        console.error('Error updating profile:', error);
        if (error.response && error.response.data.message) {
          const errorMessage = error.response.data.message;
          this.$notify({
            type: 'error',
            title: 'Error',
            text: errorMessage,
            color: 'red'
          });
        }
      }
    },
    closeModal() {
      let modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('deleteModal'))
      modal.hide();
    },
    hapusModal() {
      let modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('deleteModal'))
      modal.show();
    },
    async confirmHapus() {
      try {
        const id = this.$route.params.id;
        // const response = await axios.delete(`${BASE_URL}/deleteUser/` + id, {
        //   headers: {
        //     Authorization: 'Bearer ' + localStorage.getItem('access_token'),
        //   },
        // });
        console.log(id)
        this.$notify({
          type: 'success',
          title: 'Success',
          text: 'Program berhasil di hapus',
          color: 'green'
        });
        this.closeModal();
        this.dialog = true
      } catch (error) {
        console.error(error);
      }
    },
    closeDialog() {
      this.dialog = false,
        this.$router.push('/admin/program')
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
        this.$route.params.id;
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
        <Breadcrumbs class="d-flex align-items-center" :items="breadcrumbsItems" />
        <div class="card ps-3 pt-2">
          <div class="mb-3 row">
            <div class="col-sm-2">
              <label for="nama program" class="col-form-label">Nama Program</label>
            </div>
            <div class="col-sm-10" style="padding-right: 20px">
              <input type="text" class="form-control" v-model="form.namaProgram" placeholder="Perbaikan Gapura">
            </div>
          </div>
          <div class="mb-3 row">
            <div class="col-sm-2">
              <label for="deskripsi" class="ol-form-label">Deskripsi</label>
            </div>
            <div class="col-sm-10" style="padding-right: 20px">
              <textarea class="form-control" rows="3" v-model="form.deskripsi"
                placeholder="Pekerjaan Konstruksi - Perbaikan Gapura Utara - 2024"></textarea>
            </div>
          </div>
          <div class="mb-3 row">
            <div class="col-sm-2">
              <label for="tanggal mulai" class="col-form-label">Tanggal Mulai</label>
            </div>
            <div class="col-sm-10" style="padding-right: 20px">
              <input type="date" class="form-control" v-model="form.tanggalMulai">
            </div>
          </div>
          <div class="mb-3 row">
            <div class="col-sm-2">
              <label for="tanggal selesai" class="col-form-label">Tanggal Selesai</label>
            </div>
            <div class="col-sm-10" style="padding-right: 20px">
              <input type="date" class="form-control" v-model="form.tanggalSelesai">
            </div>
          </div>
          <div class="mb-3 row">
            <div class="col-sm-2">
              <label for="status" class="col-form-label">Status</label>
            </div>
            <div class="col-sm-10" style="padding-right: 20px">
              <input type="text" class="form-control" v-model="form.status" placeholder="on progress">
            </div>
          </div>
          <div class="mb-3 row">
            <div class="col-sm-2">
              <label for="target" class="col-form-label">Target</label>
            </div>
            <div class="col-sm-10" style="padding-right: 20px">
              <textarea class="form-control" rows="3" v-model="form.target"
                placeholder="Memperbaiki semua gapura yang rusak di wilayah tertentu dalam jangka waktu tertentu, dengan memberikan prioritas kepada gapura yang merupakan pintu gerbang masuk ke kota atau desa."></textarea>
            </div>
          </div>
          <div class="form-actions mt-4 d-flex justify-content-end">
            <button class="btn btn-success mx-2" @click="validateForm">Simpan</button>
            <button class="btn btn-danger me-2" @click="hapusModal">Hapus</button>
          </div>
        </div>
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title text-black" id="deleteConfirmationModalLabel">Konfirmasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                Anda yakin ingin menghapus program ini??
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" @click="confirmHapus">Hapus</button>
              </div>
            </div>
          </div>
        </div>
        <v-dialog v-model="dialog" max-width="400">
          <v-card class="text-center">
            <v-card-text>
              <div class="p-2">
                <h3>Program berhasil di hapus</h3>
                <v-icon color="blue" size="80">mdi-checkbox-marked-circle-outline</v-icon>
              </div>
            </v-card-text>
            <template v-slot:actions>
              <argon-button class="ms-auto" color="secondary" size="sm" variant="outline"
                @click="closeDialog">Close</argon-button>
            </template>
          </v-card>
        </v-dialog>
        <v-dialog v-model="validate" max-width="400">
          <v-card class="text-center">
            <v-card-text>
              <div class="p-2">
                <v-icon color="blue" size="100">mdi-close-circle-outline</v-icon>
                <h5>Form belum sepenuhnya terisi, silahkan cek kembali</h5>
              </div>
            </v-card-text>
          </v-card>
        </v-dialog>

      </div>
    </div>
  </div>
</template>
