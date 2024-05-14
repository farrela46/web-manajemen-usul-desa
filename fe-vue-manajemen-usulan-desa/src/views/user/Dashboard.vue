<script>
import axios from "axios";
import BASE_URL from '@/api/config-api';
import ArgonButton from "@/components/ArgonButton.vue";
import Breadcrumbs from '@/components/Vuetify/Breadcrumbs.vue';

export default {
  components: {
    ArgonButton,
    Breadcrumbs
  },
  data() {
    return {
      products: [],
      overlay: false,
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
  created() {
    this.store = this.$store;
    this.body = document.getElementsByTagName("body")[0];
    this.setupPage();
  },
  beforeUnmount() {
    this.restorePage();
  },
  mounted() {

  },
  methods: {
    goUsulan() {
      this.$router.push('/usulan');
    },
    formatPrice(price) {
      const numericPrice = parseFloat(price);
      return numericPrice.toLocaleString('id-ID');
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
    }
  },
};
</script>

<template>
  <div class="py-4 container-fluid " >
    <div class="row mt-3">
      <v-overlay :model-value="overlay" class="d-flex align-items-center justify-content-center">
        <v-progress-circular color="primary" size="96" indeterminate></v-progress-circular>
      </v-overlay>
      <div class="container">
        <Breadcrumbs class="d-flex align-items-center" :items="breadcrumbsItems" />
        <div class="row">
          <div class="col-12">
            <div class="card px-4" style="border-radius: 10px; 
            background-color: #E9F5E9;
            ">
              <div class="row mt-2 mb-2" style="border-bottom: 1px solid black;">
                <div class="d-flex align-items-center mt-2" style="border-bottom: 1px solid black;">
                  Menu
                </div>
                <div class="row">
                  <div class="col-md-6 d-flex align-items-center">
                    <v-col cols="auto">
                      <h3>List Usulan Warga</h3>
                      <argon-button size="lg" @click="goUsulan"
                        style="height: 130px; border-radius: 30px; background-color: #4D2C5E; font-size: 25px;"><i
                          class="fas fa-users fa-lg" style="font-size: 25px;"></i> LIST USULAN WARGA</argon-button>
                    </v-col>
                  </div>
                  <div class="col-md-6 d-flex align-items-center justify-content-center">
                    <a><strong> Partisipasi dan tanggapi macam-macam Usulan berisi ide yang diberikan oleh masyarakat
                        berdasarkan permasalahan yang ada, vote dan tambah ide anda untuk berpatisipasi</strong>
                    </a>
                  </div>
                </div>
                <hr style="font-weight: bold;">
                <div class="row">
                  <div class="col-md-6 d-flex align-items-center">
                    <v-col cols="auto">
                      <h3>List Progress Warga</h3>
                      <argon-button size="lg"
                        style="height: 130px; border-radius: 30px; background-color: #4D2C5E; font-size: 25px;"><i
                          class="fas fa-users fa-lg" style="font-size: 25px;"></i> PROGRAM KERJA DESA</argon-button>
                    </v-col>
                  </div>
                  <div class="col-md-6 d-flex align-items-center justify-content-center">
                    <a><strong> Partisipasi dan tanggapi macam-macam Usulan berisi ide yang diberikan oleh masyarakat
                        berdasarkan permasalahan yang ada, vote dan tambah ide anda untuk berpatisipasi</strong>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style>
.user-select-none {
  user-select: none;
}

a {
  text-decoration: none;
  color: unset;
}
</style>