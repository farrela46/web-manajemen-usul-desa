<script>
import axios from "axios";
import BASE_URL from '@/api/config-api';
import Breadcrumbs from '@/components/Vuetify/Breadcrumbs.vue';
import ArgonButton from "@/components/ArgonButton.vue";

export default {
  components: {
    Breadcrumbs,
    ArgonButton
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
          title: 'Home',
          disabled: false,
          href: '/dashboard',
        },
        {
          title: 'Usulan',
          disabled: true,
          href: '/',
        }
      ],
      usulan: [],
      selectedFilter: 'latest',
    };
  },
  mounted() {
    import('vuetify/styles').then(() => {
      this.retrieveUsulan();
    })
  },
  // props: {
  //   usulan: Array
  // },
  methods: {
    mapStatus(status) {
      switch (status) {
        case 'pending':
          return 'Pending';
        case 'approved':
          return 'Disetujui';
        case 'rejected':
          return 'Ditolak';
        default:
          return 'Tidak Diketahui';
      }
    },
    formatDate(date) {
      if (!date) return "";
      const options = { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' };
      return new Date(date).toLocaleDateString(undefined, options);
    },
    goUsulan() {
      this.$router.push('/usulan/input')
    },
    navigateToUsulan(id) {
      this.$router.push(`/usulan/${id}`);
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
    formatPrice(price) {
      const numericPrice = parseFloat(price);
      return numericPrice.toLocaleString('id-ID');
    },
    async retrieveUsulan() {
      try {
        this.overlay = true;

        let params = {};
        if (this.selectedFilter === 'upvote') {
          params.rank = true;
        }

        const response = await axios.get(`${BASE_URL}/suggestion/get`, {
          headers: {
            Authorization: "Bearer " + localStorage.getItem('access_token')
          },
          params: params // Include params in request
        });

        this.usulan = response.data.data;

        if (response.data.data.length > 0) {
          this.fotoUrl = response.data.data[0].foto;
        }
      } catch (error) {
        console.error(error);
      } finally {
        this.overlay = false;
      }
    },
    async upvote(id) {
      try {
        const response = await axios.get(`${BASE_URL}/suggestion/${id}/upvote`, {
          headers: {
            Authorization: "Bearer " + localStorage.getItem('access_token')
          }
        });
        console.log(response)
        this.retrieveUsulan();
      } catch (error) {
        console.error(error);
      }
    },
    async downvote(id) {
      try {
        const response = await axios.get(`${BASE_URL}/suggestion/${id}/downvote`, {
          headers: {
            Authorization: "Bearer " + localStorage.getItem('access_token')
          }
        });
        console.log(response)
        this.retrieveUsulan();
      } catch (error) {
        console.error(error);
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
        <div class="row">
          <div class="col-12">
            <div class="card px-4" style="background-color: #E9F5E9;">
              <div class="row mt-2 mb-2">
                <div class=" row  mt-2 mb-2">
                  <div class="col-2">
                    <argon-button size="sm" @click="goUsulan"> <i class="fas fa-plus mx-2"></i> Tambah
                      Usulan</argon-button>
                  </div>
                  <div class="col">
                    <div class="row ps-3 mb-2">
                      <div class="col-2">
                        <select class="form-select form-select-sm" aria-label="Small select example"
                          v-model="selectedFilter" @change="retrieveUsulan">
                          <option value="latest">Latest</option>
                          <option value="upvote">Most Upvote</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div v-for="item in usulan" :key="item.id" style="color: black; cursor: pointer;">
                  <div class="row mt-2">
                    <div class="col-12">
                      <div class="card px-4 py-3" @click="navigateToUsulan(item.id)">
                        <div class="row">
                          <div class="d-flex align-items-center mt-2">
                            <div class="avatar avatar-sm position-relative me-2">
                              <img :src="require('@/assets/img/team-1.jpg')" alt="profile_image"
                                class="shadow-sm w-100 border-radius-lg" />
                            </div>
                            <div class="mt-2">
                              <a class="text-black">{{ item.nama }}</a>
                              <a class="ms-3 text-black" style="font-size: 12px;">{{ formatDate(item.tanggal) }}</a>
                              <v-chip class="mx-3">
                                Status: {{ mapStatus(item.status) }}
                              </v-chip>
                            </div>
                          </div>
                          <h4 class="mt-2">{{ item.saran }}</h4>
                          <div class="row mt-2">
                            <p class="text-black">{{ item.deskripsi }}</p>
                          </div>

                          <!-- Nested Response -->
                          <div v-if="item.suggestion_asal" class="row mt-3 ms-4">
                            <div class="card px-3 py-2" style="background: #f5f5f5;"
                              @click.stop="navigateToUsulan(item.suggestion_asal.id)">
                              <div class="d-flex align-items-center mt-2">
                                <div class="avatar avatar-sm position-relative me-2">
                                  <img :src="require('@/assets/img/team-1.jpg')" alt="profile_image"
                                    class="shadow-sm w-100 border-radius-lg" />
                                </div>
                                <div class="mt-2">
                                  <a class="text-black">{{ item.suggestion_asal.nama }}</a>
                                  <a class="ms-3 text-black" style="font-size: 12px;">{{
        formatDate(item.suggestion_asal.tanggal) }}</a>
                                </div>
                              </div>
                              <h6 class="mt-2">{{ item.suggestion_asal.saran }}</h6>
                              <div class="row mt-2">
                                <p class="text-black">{{ item.suggestion_asal.deskripsi }}</p>
                              </div>
                            </div>
                          </div>

                          <div class="row mb-2 mt-2">
                            <v-chip class="no-hover" :color="item.user_vote === 'upvote' ? 'green' : ''"
                              style="width: 70px; cursor: pointer;" @click.stop="upvote(item.id)">
                              <v-icon
                                :icon="item.user_vote === 'upvote' ? 'mdi-arrow-up-bold' : 'mdi-arrow-up-bold-outline'"
                                start></v-icon>
                              {{ item.upvote }}
                            </v-chip>
                            <v-chip class="no-hover" :color="item.user_vote === 'downvote' ? 'red' : ''"
                              style="width: auto; margin-left: 5px; cursor: pointer;" @click.stop="downvote(item.id)">
                              <v-icon
                                :icon="item.user_vote === 'downvote' ? 'mdi-arrow-down-bold' : 'mdi-arrow-down-bold-outline'"
                                start></v-icon>
                              {{ item.downvote }}
                            </v-chip>
                            <v-chip class="no-hover" style="width: auto; margin-left: 5px; cursor: pointer;">
                              <v-icon icon="mdi-comment-multiple-outline" start></v-icon>
                              {{ item.comment }}
                            </v-chip>
                          </div>
                        </div>
                      </div>
                    </div>
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
<style scoped>
.user-select-none {
  user-select: none;
}

a {
  text-decoration: none;
  color: black;
}

.router-link {
  text-decoration: none;
  /* Remove default underline */
  color: black;
}

.router-link:hover {
  color: black;
}

.router-link p {
  margin: 0;
  /* Remove default margin */
}

.router-link p:hover {
  color: inherit;
  /* Inherit parent color on hover for <p> */
}
</style>