<script>
// import { useRoute } from "vue-router";
import axios from "axios";
import BASE_URL from '@/api/config-api';
// import ArgonButton from "@/components/ArgonButton.vue";
import Breadcrumbs from '@/components/Vuetify/Breadcrumbs.vue';
import ArgonInput from "@/components/ArgonInput.vue";

export default {
  components: {
    // ArgonButton,
    Breadcrumbs,
    ArgonInput
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
          disabled: false,
          href: '/usulan',
        },
        {
          title: 'Usulan Diskusi',
          disabled: true,
          href: '/',
        }
      ],
      usulan: {},
      komentar: [],
      comment: '',
      usulanRow: false,
      cekKomen: true,
      loadingcomment: false
    };
  },
  mounted() {
    this.retrieveUsulan();
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
    async retrieveUsulan() {
      try {
        this.overlay = true;
        const response = await axios.get(`${BASE_URL}/suggestion/get/` + this.$route.params.id, {
          headers: {
            Authorization: "Bearer " + localStorage.getItem('access_token')
          }
        });
        this.usulan = response.data.suggestion;
        this.komentar = response.data.comments

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
        const response = await axios.get(`${BASE_URL}/suggestion/${id}/downvote`,  {
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
    async tambahUsulan() {
      this.loadingcomment = true;
      try {
        const response = await axios.post(`${BASE_URL}/suggestion/` + this.$route.params.id + `/comment`, {
          comment: this.comment,
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
        this.retrieveUsulan();
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
        this.loadingcomment = false;
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
            <div class="card px-4" style="border-radius: 10px; 
            /* background-color: #E9F5E9; */
            ">
              <div class="row mt-2 mb-2" v-if="usulanRow">

                <div class="row">
                  <div class="row mt-2">
                    <div class="col-12">
                      <div class="card px-4">
                        <div class="row">
                          <div class="d-flex align-items-center mt-2">
                            <div class="avatar avatar-sm position-relative me-2">
                              <img :src="require('@/assets/img/team-1.jpg')" alt="profile_image"
                                class="shadow-sm w-100 border-radius-lg" />
                            </div>
                            <div class="mt-2">
                              <a class="text-black">{{ usulan.nama }}</a>
                              <a class="ms-3 text-black" style="font-size: 12px;">{{ usulan.tanggal }}</a>
                            </div>
                          </div>
                          <h4>{{ usulan.saran }}</h4>
                          <div class="row mt-2">
                            <p class="text-black">{{ usulan.deskripsi }}</p>
                          </div>
                          <div class="row mb-2">
                            <v-chip class="no-hover" :color="usulan.user_vote === 'upvote' ? 'green' : ''"
                              style="width: 70px; cursor: pointer;" @click.stop="upvote(usulan.id_suggestion)">
                              <v-icon
                                :icon="usulan.user_vote === 'upvote' ? 'mdi-arrow-up-bold' : 'mdi-arrow-up-bold-outline'"
                                start></v-icon>
                              {{ usulan.upvote }}
                            </v-chip>
                            <v-chip class="no-hover" :color="usulan.user_vote === 'downvote' ? 'red' : ''"
                              style="width: auto; margin-left: 5px; cursor: pointer;"
                              @click.stop="downvote(usulan.id_suggestion)">
                              <v-icon
                                :icon="usulan.user_vote === 'downvote' ? 'mdi-arrow-down-bold' : 'mdi-arrow-down-bold-outline'"
                                start></v-icon>
                              {{ usulan.downvote }}
                            </v-chip>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row mt-2">
                    <div class="col-12">
                      <div class="card px-4">
                        <div class="row">
                          <div class="col-md-12 d-flex justify-content-center">
                            <a class="py-2"><i class="far fa-comments fa-lg"></i> <strong>KOMENTAR &
                                DISKUSI</strong></a>
                          </div>
                        </div>
                        <div class="container">
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
                  <div class="row mt-2">
                    <div class="col-12">
                      <div class="card px-4">
                        <div class="row py-2">
                          <div class="col-md-12">
                            <div class="d-flex align-items-center mt-2">
                              <div class="avatar avatar-lg position-relative me-2">
                                <img :src="require('@/assets/img/team-1.jpg')" alt="profile_image"
                                  class="shadow-sm w-100 border-radius-lg" />
                              </div>
                              <div class="mt-2 w-100">
                                <div class="">
                                  <argon-input v-model="comment" placeholder="Tambahkan Komentar . . ."
                                    icon="far fa-comments fa-lg" iconDir="left" size=lg />
                                </div>
                              </div>
                              <span class="mdi mdi-send" @click="tambahUsulan"
                                style="font-size: 24px; cursor: pointer; padding-left:10px"></span>
                            </div>
                            <v-progress-linear v-if="loadingcomment" indeterminate></v-progress-linear>
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
<style></style>