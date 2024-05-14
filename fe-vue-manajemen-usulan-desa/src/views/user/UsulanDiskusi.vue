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
      usulan: {
        id: 1,
        nama: 'Saleh Von Forst',
        date: '15 Hours Ago',
        subject: 'Ruang tebuka untuk mandi',
        text: 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Reiciendis rem dolorem necessitatibus omnis quasi fugit dolore aspernatur, rerum voluptates voluptatem deleniti consequatur soluta veritatis excepturi quia in temporibus odio eum. Labore aspernatur, id asperiores veritatis a adipisci voluptate voluptas, quibusdam aperiam, tempora incidunt dolore! Voluptate sequi iste totam modi animi.'
      },
      komentar: [
        {
          id: 1,
          nama: 'Saleh Von Forst',
          date: '15 Hours Ago',
          subject: 'Ruang tebuka untuk mandi',
          text: 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Reiciendis rem dolorem necessitatibus omnis quasi fugit dolore aspernatur, rerum voluptates voluptatem deleniti consequatur soluta veritatis excepturi quia in temporibus odio eum. Labore aspernatur, id asperiores veritatis a adipisci voluptate voluptas, quibusdam aperiam, tempora incidunt dolore! Voluptate sequi iste totam modi animi.'
        },
        {
          id: 2,
          nama: 'Bin Abdul Salim',
          date: '22 Hours Ago',
          subject: 'Penyediaan Ruang untuk modifikasi',
          text: 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Reiciendis rem dolorem necessitatibus omnis quasi fugit dolore aspernatur, rerum voluptates voluptatem deleniti consequatur soluta veritatis excepturi quia in temporibus odio eum. Labore aspernatur, id asperiores veritatis a adipisci voluptate voluptas, quibusdam aperiam, tempora incidunt dolore! Voluptate sequi iste totam modi animi.'
        },
        {
          id: 3,
          nama: 'Juna Foll Kadotz',
          date: '1089 Hours Ago',
          subject: 'Jamet diusir aja plzz',
          text: 'Banyak orang merasa terganggu oleh perilaku jamet yang mengganggu ketertiban dan keamanan lingkungan. Penting untuk bertindak mengusir mereka agar ketenangan kembali terjaga. Langkah-langkah seperti meningkatkan patroli keamanan dan mendukung kegiatan sosial untuk mengurangi perilaku ini sangat diperlukan demi kenyamanan bersama.'
        },
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
        <div class="row">
          <div class="col-12">
            <div class="card px-4" style="border-radius: 10px; 
            background-color: #E9F5E9;
            ">
              <div class="row mt-2 mb-2">
                <div class="d-flex align-items-center mt-2" style="border-bottom: 1px solid black;">
                  Menu
                </div>
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
                              <a class="ms-3 text-black" style="font-size: 12px;">{{ usulan.date }}</a>
                            </div>
                          </div>
                          <h4>{{ usulan.subject }}</h4>
                          <div class="row mt-2">
                            <p class="text-black">{{ usulan.text }}</p>
                          </div>
                          <div class="row mb-2">
                            <v-chip style="width: 70px; cursor: pointer;">
                              <v-icon icon="mdi-arrow-up-bold-outline" start></v-icon>
                              12
                            </v-chip>
                            <v-chip style="width: auto; margin-left: 5px; cursor: pointer;">
                              <v-icon icon="mdi-arrow-down-bold-outline" start></v-icon>
                              12
                            </v-chip>
                            <v-chip style="width: auto; margin-left: 5px; cursor: pointer;">
                              <v-icon icon="mdi-comment-multiple-outline" start></v-icon>
                              123
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

                          <div class="row" v-for="item in komentar" :key="item.id">
                            <div class="d-flex align-items-center mt-2">
                              <div class="avatar avatar-sm position-relative me-2">
                                <img :src="require('@/assets/img/team-1.jpg')" alt="profile_image"
                                  class="shadow-sm w-100 border-radius-lg" />
                              </div>
                              <div class="mt-2">
                                <a class="text-black">{{ item.nama }}</a>
                                <a class="ms-3 text-black" style="font-size: 12px;">{{ item.date }}</a>
                              </div>
                            </div>
                            <h4>{{ item.subject }}</h4>
                            <div class="row mt-2">
                              <p class="text-black">{{ item.text }}</p>
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
                                <argon-input placeholder="Tambahkan Komentar . . ." icon="far fa-comments fa-lg" iconDir="left" size=lg />
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
    </div>
  </div>
</template>
<style></style>