<script>
// import { ref } from "vue";
// import { useStore } from "vuex";
import axios from 'axios';
import BASE_URL from "@/api/config-api";
import AppFooter from "@/examples/Footer.vue";
import Navbar from "@/examples/PageLayout/HomeNavbar.vue";
import setTooltip from "@/assets/js/tooltip.js";

export default {
  components: {
    AppFooter,
    Navbar
  },
  data() {
    return {
      images: [
        { link: require("@/assets/img/telkom1.jpg") },
        { link: require("@/assets/img/telkom2.jpg") }
      ],
      overlay: true, // Overlay state
      data: '',
    };
  },
  methods: {
    async retrieveProgram() {
      try {
        this.overlay = true;
        const response = await axios.get(`${BASE_URL}/landingpage`, {
          headers: {
            Authorization: "Bearer " + localStorage.getItem('access_token')
          }
        });
        this.data = response.data;
      } catch (error) {
        console.error(error);
      } finally {
        this.overlay = false;
      }
    },
    setInitialStates() {
      const body = document.getElementsByTagName("body")[0];
      const store = this.$store;

      store.state.layout = "vr";
      store.state.showNavbar = false;
      store.state.showSidenav = false;
      store.state.showFooter = false;
      body.classList.add("virtual-reality");
      store.state.isTransparent = "bg-white";
    },
    resetStates() {
      const body = document.getElementsByTagName("body")[0];
      const store = this.$store;

      store.state.layout = "default";
      store.state.showNavbar = true;
      store.state.showSidenav = true;
      store.state.showFooter = true;
      body.classList.remove("virtual-reality");

      if (store.state.isPinned === false) {
        const sidenav_show = document.querySelector(".g-sidenav-show");
        sidenav_show.classList.remove("g-sidenav-hidden");
        sidenav_show.classList.add("g-sidenav-pinned");
        store.state.isPinned = true;
      }
      store.state.isTransparent = "bg-transparent";
    }
  },
  mounted() {
    setTooltip();
    this.retrieveProgram();
  },
  beforeMount() {
    this.setInitialStates();
  },
  beforeUnmount() {
    this.resetStates();
  }
};
</script>

<template>
  <div class="container top-0 position-sticky z-index-sticky">
    <div class="row">
      <div class="col-12">
        <navbar isBlur="blur  border-radius-lg my-3 py-2 start-0 end-0 mx-4 shadow" v-bind:darkMode="true" />
      </div>
    </div>
  </div>
  <div class="mx-3 mt-2 position-relative" :style="{
          backgroundImage: 'linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5) ) ,url(' + require('@/assets/img/background.png') + ')',
          backgroundSize: 'cover',
          backgroundPosition: 'center',
          height: '65vh',
          borderRadius: '30px 30px 0 0'
        }">
    <div class="container-fluid h-100">
      <div class="row h-100 justify-content-center align-items-center">
        <div class="col-auto text-center">
          <h1 class="text-white">Welcome to</h1>
          <h4 class="text-white">WEBSITE USUL DESA TELKOM SURABAYA</h4>
        </div>
      </div>
    </div>
  </div>
  <main class="main-content mt-5">
    <div class="section min-vh-85 position-relative">
      <div class="container-fluid">
        <div class="row mx-5 mt-2">
          <div class="col-md-4 position-relative">
            <div class="text-center">
              <h1 class="text-gradient text-success"><span>{{ data.usulan_masuk }}</span></h1>
              <h5 class="mt-3">Usulan Masuk</h5>
              <p class="text-sm font-weight-normal">Usulan berisi ide yang dikemukakan masyarakat <br> berdasarkan
                permasalahan yang ada</p>
            </div>
            <hr class="vertical dark">
          </div>
          <div class="col-md-4 position-relative">
            <div class="text-center">
              <h1 class="text-gradient text-success"><span>{{ data.usulan_disetujui }}</span></h1>
              <h5 class="mt-3">Usulan di Setujui</h5>
              <p class="text-sm font-weight-normal">Usulan yang di proses oleh perangkat desa</p>
            </div>
            <hr class="vertical dark">
          </div>
          <div class="col-md-4 position-relative">
            <div class="text-center">
              <h1 class="text-gradient text-success"><span>{{ data.pengguna_aktif }}</span></h1>
              <h5 class="mt-3">Pengguna Aktif</h5>
              <p class="text-sm font-weight-normal">From buttons, to inputs, navbars, alerts or cards, you are
                covered</p>
            </div>
          </div>
        </div>
      </div>
      <br>
      <div class="wrapper">
        <div class="mx-3 mt-4 mb-5">
          <div class="row h-100 justify-content-center align-items-center m-5">
            <div class="col-auto text-center m-2">
              <h2 class="text-dark mb-4 mt-5 pb-5">Website Usul Desa Telkom Surabaya</h2>
              <div class="row">
                <div class="col-md-6">
                  <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                      <div v-for="(image, index) in images" :key="index"
                        :class="['carousel-item', { active: index === 0 }]">
                        <img :src="image.link" class="d-block w-100" :alt="'Slide ' + (index + 1)"
                          style="max-width: 400px; object-fit: cover; border-radius: 30px;">
                      </div>
                    </div>
                    <div class="carousel-indicators">
                      <button v-for="(image, index) in images" :key="index" type="button"
                        :data-bs-target="'#carouselExampleIndicators'" :data-bs-slide-to="index"
                        :class="['thumbnail', { 'first-two': index === 0 || index === 1 }, { active: index === 0 }]"
                        aria-label="'Slide ' + (index + 1)">
                        <img :src="image.link" class="d-block" :alt="'Slide ' + (index + 1)"
                          style="width: 90px; height: auto;">
                      </button>
                    </div>
                  </div>

                </div>
                <div class="col-md-6 d-flex align-items-center">
                  <p class="lead"> Desa Telkom Surabaya merupakan salah satu kampus Universitas Telkom yaitu sebuah
                    perguruan
                    tinggi swasta di Indonesia yang dikelola oleh Yayasan Pendidikan Telkom dalam afiliasi Telkom
                    Indonesia
                    yang berada di Kota Surabaya. Desa Telkom Surabaya Terletak di Jl. Ketintang No.156, Ketintang, Kec.
                    Gayungan, Surabaya, Jawa Timur 60231 </p>
                </div>
              </div>
            </div>
            <br>
            <div class="col-auto text-center m-4 px-3">
              <h2 class="text-dark mb-4 mt-5">Tujuan</h2>
              <p class="lead"> Website ini adalah sebuah platform digital berbasis website yang dapat memfasilitasi
                proses pengajuan, penilaian, pengesahan, dan pelaporan usulan-usulan desa secara transparan dan
                akuntabel.
              </p>
            </div>
            <section class="py-5 ">
              <div class="row align-items-center">
                <div class="col-md-12">
                  <div class="row justify-content-start">
                    <div class="col-md-6">
                      <div color="info" class="info">
                        <span style="font-size: 3rem;">
                          <span style="color: green;">
                            <i class="fas fa-globe"></i>
                          </span>
                        </span>
                        <h5 class="font-weight-bolder mt-3">Informasi Desa</h5>
                        <p class="pe-5">Website Usul Desa ini meliputi informasi pembangunan dan usulan masyarakat Desa
                          Telkom Surabaya</p>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div color="info" class="info"><span style="font-size: 3rem;">
                          <span style="color: green;">
                            <i class="fa fa-users"></i>
                          </span>
                        </span>
                        <h5 class="font-weight-bolder mt-3">Layanan Masyarakat</h5>
                        <p class="pe-5">Website ini menyediakan layanan pengusulan ide masyarakat dari permasalahan yang
                          ada di lapangan</p>
                      </div>
                    </div>
                  </div>
                  <div class="row justify-content-start mt-4">
                    <div class="col-md-6">
                      <div color="info" class="info">
                        <span style="font-size: 3rem;">
                          <span style="color: green;">
                            <i class="fa fa-hand-peace-o"></i>
                          </span>
                        </span>
                        <h5 class="font-weight-bolder mt-3">Kemudahan</h5>
                        <p class="pe-5">Kemudahan dalam penggunaan website sehingga masyarakat tidak bingung dalam
                          menyampaikan ide atau usulan.</p>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div color="info" class="info">
                        <span style="font-size: 3rem;">
                          <span style="color: green;">
                            <i class="fa fa-lock"></i>
                          </span>
                        </span>
                        <h5 class="font-weight-bolder mt-3">Keamanan</h5>
                        <p class="pe-5">Kami akan mengusahakan data pengguna aman pada kami </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </section>
            <hr class="my-5">
            <section class="py-5 mb-5">
              <div class="row align-items-center">
                <div class="col-md-6 d-flex justify-content-center align-items-center">
                  <div class="row">
                    <div class="col m-3">
                      <h2 class="font-weight-normal">Ayo aspirasikan idemu!</h2>
                      <p style="font-size: 18px;">Dengan ide masyarakat tentu membantu pertumbuhan Desa Telkom Surabaya
                        menjadi Desa yang maju dan lebih baik. Perangkat desa akan mendengarkan idemu dan berusaha
                        merealisasikannya
                      </p>
                      <router-link to="/login">
                        <button type="button" class="btn btn-sm mb-0 mt-3 bg-gradient-success">Usulkan
                          Sekarang!</button>
                      </router-link>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 mt-6 d-flex justify-content-center align-items-center">
                  <div class="card">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2"><a
                        class="d-block blur-shadow-image"><img :src="require('@/assets/img/coc.jpg')"
                          alt="Steve Reading Book" class="img-fluid border-radius-lg"></a></div>
                    <div class="card-body text-center">
                    </div>
                  </div>
                </div>
              </div>
            </section>
            <app-footer class="py-3 border-radius-lg" />
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<style scoped>
.thumbnail {
  padding: 5px;
}

.first-two {
  margin-right: 10px;
}

.carousel-indicators button.thumbnail {
  width: 100px;
}

.carousel-indicators button.thumbnail:not(.active) {
  opacity: 0.7;
}

.carousel-indicators {
  position: static;
  margin-right: 30%;
  margin-bottom: 100px;
}


.details-snippet1 {
  color: #585656;
}


.details-snippet1 .mini-preview img {
  border: 1px solid #585656;
  border: 1px solid purple;
  margin-bottom: 100px;
}


@media screen and (min-width: 992px) {
  .carousel {
    max-width: 70%;
    margin: 0 auto;
    margin-right: 5%;
  }

}


@media screen and (max-width: 840px) {
  .carousel {
    max-width: 80%;
    margin: 0 auto;
    margin-right: 5%;
  }

  .carousel-indicators {
    position: static;
    margin-right: 20%;
    margin-bottom: 100px;
  }

}
</style>
