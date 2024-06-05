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
          title: 'Progress Program',
          disabled: false,
          href: '/program/' + this.$route.params.idprogram + '/progress'
        },
        {
          title: 'Edit Progress',
          disabled: true,
          href: '/',
        }
      ],
      form: {
        idprogram: '',
        idprogress: '',
        namaProgram: '',
        name: '',
        description: '',
        start_date: '',
        end_date: '',
        files: []
      }
    };
  },
  mounted() {
    this.retrieveProgress();
  },
  methods: {
    back() {
      this.$router.push('/program/' + this.$route.params.idprogram + '/progress')
    },
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
    validateForm() {
      if (
        !this.form.name ||
        !this.form.description ||
        !this.form.start_date ||
        !this.form.end_date

      ) {
        this.validate = true;
      } else {
        this.saveForm();
      }
    },
    handleFileChange(event) {
      const files = event.target.files;
      this.form.files = [];
      for (let i = 0; i < files.length; i++) {
        if (files[i].type.startsWith('image/')) {
          this.form.files.push(files[i]);
        }
      }
    },
    async saveForm() {
      try {
        const formData = new FormData();

        formData.append('name', this.form.name);
        formData.append('description', this.form.description);
        formData.append('start_date', this.form.start_date);
        formData.append('end_date', this.form.end_date);

        if (this.form.files && this.form.files.length > 0) {
          for (let i = 0; i < this.form.files.length; i++) {
            formData.append('gambar[]', this.form.files[i]);
          }
        }
        const response = await axios.post(BASE_URL + '/progress/update/' + this.$route.params.idprogress, formData, {
          headers: {
            Authorization: "Bearer " + localStorage.getItem('access_token')
          }
        });
        console.log(response)
        this.$notify({
          type: 'success',
          title: 'Success',
          text: 'Progress berhasil diubah',
          color: 'green'
        });
        this.$router.push('/admin/program/progress/' + this.$route.params.idprogram);

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
        const id = this.$route.params.idprogress;
        const response = await axios.delete(`${BASE_URL}/progress/delete/` + id, {
          headers: {
            Authorization: 'Bearer ' + localStorage.getItem('access_token'),
          },
        });

        console.log(response)
        this.$notify({
          type: 'success',
          title: 'Success',
          text: 'Progress berhasil di hapus',
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
        this.$router.push('/admin/program/progress/' + this.$route.params.idprogram)
    },
    async retrieveProgress() {
      try {
        this.overlay = true;
        const response = await axios.get(`${BASE_URL}/progress/` + this.$route.params.idprogress, {
          headers: {
            Authorization: "Bearer " + localStorage.getItem('access_token')
          }
        });
        this.form = response.data.progresses;
        this.form.namaProgram = response.data.nama_program


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
          <div class="row mt-2 mb-2">
            <div class="d-flex align-items-center mt-2 justify-content-start row">
              <div class="col">
                <argon-button @click="back" color="light" size="sm" class="ms-auto"><i class="fas fa-chevron-left"></i>
                  Back</argon-button>
              </div>
            </div>
          </div>
          <h3>{{ form.namaProgram }}</h3>
          <div class="mb-3 row">
            <div class="col-sm-2">
              <label for="nama program" class="col-form-label">Nama Progress</label>
            </div>
            <div class="col-sm-10" style="padding-right: 20px">
              <input type="text" class="form-control" v-model="form.name">
            </div>
          </div>
          <div class="mb-3 row">
            <div class="col-sm-2">
              <label for="deskripsi" class="ol-form-label">Deskripsi</label>
            </div>
            <div class="col-sm-10" style="padding-right: 20px">
              <textarea class="form-control" rows="3" v-model="form.description"></textarea>
            </div>
          </div>
          <div class="mb-3 row">
            <div class="col-sm-2">
              <label for="tanggal mulai" class="col-form-label">Tanggal Mulai</label>
            </div>
            <div class="col-sm-10" style="padding-right: 20px">
              <input type="date" class="form-control" v-model="form.start_date">
            </div>
          </div>
          <div class="mb-3 row">
            <div class="col-sm-2">
              <label for="tanggal selesai" class="col-form-label">Tanggal Selesai</label>
            </div>
            <div class="col-sm-10" style="padding-right: 20px">
              <input type="date" class="form-control" v-model="form.end_date">
            </div>
          </div>
          <div class="mb-3 row">
            <div class="col-sm-2">
              <label for="tanggal selesai" class="col-form-label">Foto</label>
            </div>
            <div class="col-sm-10" style="padding-right: 20px">
              <img v-for="(url, imgIndex) in form.imageUrls" :key="imgIndex" :src="url" class="img-thumbnail" alt="..."
                style=" margin-right: 2px;">
            </div>
          </div>

          <!-- <div class="form-actions mt-4 d-flex justify-content-end">
            <button class="btn btn-success mx-2" @click="validateForm">Simpan</button>
            <button class="btn btn-danger me-2" @click="hapusModal">Hapus</button>
          </div> -->
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