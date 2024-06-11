<script>
// import { useRoute } from "vue-router";
import axios from "axios";
import BASE_URL from '@/api/config-api';
// import ArgonButton from "@/components/ArgonButton.vue";
import Breadcrumbs from '@/components/Vuetify/Breadcrumbs.vue';

export default {
  components: {
    // ArgonButton,
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
          title: 'Tambah Program',
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
      },
      validate: false
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
      if (!this.form.namaProgram || !this.form.deskripsi || !this.form.tanggalMulai
        || !this.form.tanggalSelesai || !this.form.target
      ) {
        this.validate = true;
      } else {
        this.submitForm();
      }
    },
    async submitForm() {
      this.overlay = true;
      try {
        const response = await axios.post(`${BASE_URL}/program/add`, {
          name: this.form.namaProgram,
          description: this.form.deskripsi,
          target: this.form.target,
          start_date: this.form.tanggalMulai,
          end_date: this.form.tanggalSelesai

        }, {
          headers: {
            Authorization: "Bearer " + localStorage.getItem('access_token')
          }
        });

        this.$notify({
          type: 'success',
          title: 'Success',
          text: response.data.message,
          color: 'green'
        });
        console.log('response.data');
        this.$router.push('/admin/program')
      } catch (error) {
        console.error(error);

        if (error.response && error.response.data.message) {
          const errorMessage = error.response.data.message;
          this.$notify({
            type: 'error',
            title: 'Error',
            text: errorMessage,
            color: 'red'
          });
        }
      } finally {
        this.overlay = false;
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
        <Breadcrumbs class="d-flex align-items-center" :items="breadcrumbsItems" />
        <div class="card ps-3 pt-2">
          <div class="mb-3 row">
            <div class="col-sm-2">
              <label for="nama program" class="col-form-label">Nama Program</label>
            </div>
            <div class="col-sm-10" style="padding-right: 20px">
              <input type="text" class="form-control" v-model="form.namaProgram">
            </div>
          </div>
          <div class="mb-3 row">
            <div class="col-sm-2">
              <label for="deskripsi" class="ol-form-label">Deskripsi</label>
            </div>
            <div class="col-sm-10" style="padding-right: 20px">
              <textarea class="form-control" rows="3" v-model="form.deskripsi"></textarea>
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
          <!-- <div class="mb-3 row">
            <div class="col-sm-2">
              <label for="status" class="col-form-label">Status</label>
            </div>
            <div class="col-sm-10" style="padding-right: 20px">
              <input type="text" class="form-control" v-model="form.status">
            </div>
          </div> -->
          <div class="mb-3 row">
            <div class="col-sm-2">
              <label for="target" class="col-form-label">Target</label>
            </div>
            <div class="col-sm-10" style="padding-right: 20px">
              <textarea class="form-control" rows="3" v-model="form.target"></textarea>
            </div>
          </div>
          <div class="form-actions mt-4 d-flex justify-content-end">
            <button class="btn btn-success mx-2" @click="validateForm">Tambah</button>
          </div>
        </div>
      </div>
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
</template>
