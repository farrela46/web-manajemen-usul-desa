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
      user: {},
      dialog: false,
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
      usulan: []
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
    this.retrieveProgram();
  },
  methods: {
    handleClick(action) {
      if (this.user && this.user.status === 'unverified') {
        this.dialog = true;
        this.dialogMessage = 'Anda harus terverifikasi untuk mengakses fitur ini.';
      } else {
        if (action === 'usulan') {
          this.goUsulan();
        } else if (action === 'program') {
          this.goProgram();
        }
      }
    },
    back() {
      this.$router.push('/dashboard')
    },
    goUsulan() {
      this.$router.push('/usulan');
    },
    lihatProgress(idprogram) {
      this.$router.push('/program/' + idprogram);
    },
    async getUser() {
      try {
        const response = await axios.get(`${BASE_URL}/user`, {
          headers: {
            Authorization: "Bearer " + localStorage.getItem('access_token')
          }
        });
        this.user = response.data
      } catch (error) {
        console.error(error);

        if (error.response && error.response.data.message) {
          const errorMessage = error.response.data.message;
          console.log(errorMessage)
        }
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
    },
    async retrieveProgram() {
      try {
        this.overlay = true;
        const response = await axios.get(`${BASE_URL}/program/index`, {
          headers: {
            Authorization: "Bearer " + localStorage.getItem('access_token')
          }
        });
        this.usulan = response.data.data;
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
  <div class="py-4 container-fluid ">
    <div class="row mt-3">
      <v-overlay :model-value="overlay" class="d-flex align-items-center justify-content-center">
        <v-progress-circular color="primary" size="96" indeterminate></v-progress-circular>
      </v-overlay>
      <div class="container">
        <Breadcrumbs class="d-flex align-items-center" :items="breadcrumbsItems" />
        <div class="row">
          <div class="col-12">
            <div class="card px-4" style="border-radius: 10px; 
            /* background-color: #E9F5E9; */
            ">
              <div class="row mt-2 mb-2">
                <div class="d-flex align-items-center mt-2 justify-content-start row">
                  <div class="col">
                    <argon-button @click="back" color="light" size="sm" class="ms-auto"><i
                        class="fas fa-chevron-left"></i>
                      Back</argon-button>
                  </div>
                </div>
                <div class="row mt-2 p-2">
                  <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                      <thead>
                        <tr>
                          <th class="text-uppercase text-center text-secondary text-xxs font-weight-bolder opacity-7">
                            No
                          </th>
                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            Nama Program
                          </th>
                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                            Tanggal Mulai
                          </th>
                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                            Tanggal Selesai
                          </th>
                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                            Status
                          </th>
                          <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
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
                            <span class="text-black text-xs font-weight-bold">{{ item.status }}</span>
                          </td>
                          <td class="align-middle text-center">
                            <argon-button @click="lihatProgress(item.id)" size="sm" class="ms-auto" style="background-color: #514094;">
                              Progress</argon-button>
                            <span class="mx-2" style="font-size: 1rem; cursor: pointer;" @click="lihatProgress(item.id)">
                              <span style="color: green;">
                                <i class="fas fa-eye fa-lg"></i>
                              </span>
                            </span>
                            <!-- <v-tooltip text="Tambah Progress">
                              <template v-slot:activator="{ props }">
                                <span v-bind="props" class="mx-2" style="font-size: 1rem; cursor: pointer;"
                                  @click="tambahProgress(item.id)">
                                  <span style="color: blue;">
                                    <i class="fas fa-plus-circle"></i> Progress
                                  </span>
                                </span>
                              </template>
</v-tooltip> -->
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