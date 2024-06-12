use bootstrap;
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
      validate: false,
      dialog: false,
      products: [],
      overlay: false,
      breadcrumbsItems: [
        {
          title: 'Usulan',
          disabled: false,
          href: '/admin/usulan',
        },
        {
          title: 'Detail Usulan',
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
    this.retrieveUsulan();
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

    async retrieveUsulan() {
      try {
        this.overlay = true;
        const response = await axios.get(`${BASE_URL}/suggestion/admin/get/` + this.$route.params.id, {
          headers: {
            Authorization: "Bearer " + localStorage.getItem('access_token')
          }
        });
        this.form = response.data.suggestion;
        this.komentar = response.data.comments

        if (this.form.tanggal) {
          const date = new Date(this.form.tanggal);
          const formattedDate = date.toISOString().split('T')[0]; // Format to YYYY-MM-DD
          this.form.tanggal = formattedDate;
        }

        if (response.data.length > 0) {
          this.fotoUrl = response.data[0].foto;
        }
      } catch (error) {
        console.error(error);
      } finally {
        this.overlay = false;
        this.usulanRow = true;
        this.cekKomen = this.komentar.length === 0;
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
              <label for="nama program" class="col-form-label">Nama Usulan</label>
            </div>
            <div class="col-sm-10" style="padding-right: 20px">
              <input type="text" class="form-control" v-model="form.saran">
            </div>
          </div>
          <div class="mb-3 row">
            <div class="col-sm-2">
              <label for="deskripsi" class="ol-form-label">Deskripsi</label>
            </div>
            <div class="col-sm-10" style="padding-right: 20px">
              <textarea class="form-control" rows="4" v-model="form.deskripsi"></textarea>
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
              <input type="date" class="form-control" v-model="form.tanggal" disab>
            </div>
          </div>
          <div class="mb-3 row">
            <div class="col-sm-2">
              <label for="status" class="col-form-label">User</label>
            </div>
            <div class="col-sm-10" style="padding-right: 20px">
              <input type="text" class="form-control" v-model="form.nama" disabled>
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
            <h4> Komentar ({{ komentar.length }}) </h4>
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
