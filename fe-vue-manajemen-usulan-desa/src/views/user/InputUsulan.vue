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
      usulan: '',
      subject: '',
      validate: false,
      overlay: false,
      breadcrumbsItems: [
        {
          title: 'Home',
          disabled: false,
          href: '/dashboard',
        },
        {
          title: 'Usulan',
          disabled: false,
          href: '/usulan',
        },
        {
          title: 'Input Usulan',
          disabled: true,
          href: '/',
        }
      ],
    };
  },
  mounted() {

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
      if (!this.subject || !this.usulan) {
        this.validate = true;
      } else {
        this.submitForm();
      }
    },
    async submitForm() {
      this.overlay = true;
      try {
        const response = await axios.post(`${BASE_URL}/suggestion/add`, {
          suggestion: this.subject,
          description: this.usulan
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
        this.$router.push('/usulan')
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
  },
};
</script>

<template>
  <div class="py-4 container-fluid">
    <div class="row mt-3">
      <v-overlay v-model="overlay" class="d-flex align-items-center justify-content-center">
        <v-progress-circular color="primary" size="96" indeterminate></v-progress-circular>
      </v-overlay>
      <div class="container">
        <Breadcrumbs class="d-flex align-items-center" :items="breadcrumbsItems" />
        <div class="row">
          <div class="col-12">
            <div class="card px-4" style="border-radius: 10px; 
            background-color: #E9F5E9;
            ">
              <div class="row mt-2 mb-2">
                <div class="row  mt-2">
                  <div class="col-12">
                    <div class="card p-4">
                      <div class="row">
                        <h2>Tambah Usulan Baru</h2>
                        <a>Bagikan konsep inovatif Anda dengan kami dan kerja sama untuk membawa ide Anda menjadi
                          kenyataan dan
                          dapat dilihat oleh kepala desa</a>
                      </div>
                      <div class="row mt-1">
                        <div class="form-floating">
                          <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="floatingInput" v-model="subject"
                              placeholder="Subject Usulan">
                            <label for="floatingInput">Subject Usulan</label>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-floating">
                          <textarea class="form-control" v-model="usulan" placeholder="Deskripsikan Usulanmu"
                            id="floatingTextarea2" style="height: 300px"></textarea>
                          <label for="floatingTextarea2">Deskripsi Usulan</label>
                        </div>
                      </div>
                      <div class="row mt-2 text-end">
                        <div class="col">
                          <argon-button size="sm" color="info" variant="gradient"
                            @click="validateForm">Post</argon-button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
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
  </div>
</template>
<style></style>