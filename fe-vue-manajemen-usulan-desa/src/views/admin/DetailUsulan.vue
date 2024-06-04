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
        id: '',
        usulan: '',
        description: '',
        tanggal_usulan: '',
        status: '',
        user: '',
        upvote: '',
        downvote: ''
      },
      komentar: [],
    };
  },
  mounted() {
    this.retrieveProgram();
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
    async retrieveProgram() {
      try {
        this.overlay = true;
        const response = await axios.get(`${BASE_URL}/program/` + this.$route.params.id, {
          headers: {
            Authorization: "Bearer " + localStorage.getItem('access_token')
          }
        });
        this.form = response.data;
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
              <label for="status" class="col-form-label">Status</label>
            </div>
            <div class="col-sm-10" style="padding-right: 20px">
              <input type="text" class="form-control" v-model="form.status">
            </div>
          </div>
          <div class="mb-3 row">
            <div class="col-sm-2">
              <label for="tanggal mulai" class="col-form-label">Tanggal Usulan</label>
            </div>
            <div class="col-sm-10" style="padding-right: 20px">
              <input type="date" class="form-control" v-model="form.start_date" disab>
            </div>
          </div>
          <div class="mb-3 row">
            <div class="col-sm-2">
              <label for="status" class="col-form-label">User</label>
            </div>
            <div class="col-sm-10" style="padding-right: 20px">
              <input type="text" class="form-control" v-model="form.user" disabled>
            </div>
          </div>
          <div class="mb-3 row">
            <div class="col-sm-2">
              <label for="status" class="col-form-label">Upvote</label>
            </div>
            <div class="col-sm-10" style="padding-right: 20px">
              <input type="text" class="form-control" v-model="form.upvote" disabled>
            </div>
          </div>
          <div class="mb-3 row">
            <div class="col-sm-2">
              <label for="status" class="col-form-label">Downvote</label>
            </div>
            <div class="col-sm-10" style="padding-right: 20px">
              <input type="text" class="form-control" v-model="form.downvote" disabled>
            </div>
          </div>
          <div class="row mt-4">
            <h4> Komentar {{ komentar.length }} </h4>
          </div>
          <div class="row">
            <h6 v-if="komentar.length === 0" class="text-center">Belum ada komentar, jadilah yang pertama
            </h6>
            <div class="row" v-for="item in komentar" :key="item.id">
              <div class="d-flex align-items-center mt-2">
                <div class="avatar avatar-sm position-relative me-2">
                  <img :src="require('@/assets/img/team-1.jpg')" alt="profile_image"
                    class="shadow-sm w-100 border-radius-lg" />
                </div>
                <div class="mt-2">
                  <a class="text-black">{{ item.nama_komen }}</a>
                  <a class="ms-3 text-black" style="font-size: 12px;">{{ item.tanggal_komen }}</a>
                </div>
              </div>
              <div class="row mt-2">
                <p class="text-black">{{ item.comment }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
