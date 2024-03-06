<script setup>
import { onMounted, onBeforeMount, onBeforeUnmount } from "vue";
import { useStore } from "vuex";
// import Sidenav from "@/examples/Sidenav";
import AppFooter from "@/examples/Footer.vue";
// import Navbar from "@/examples/Navbars/HomeNavbar.vue";
import Navbar from "@/examples/PageLayout/HomeNavbar.vue";
import setTooltip from "@/assets/js/tooltip.js";

const body = document.getElementsByTagName("body")[0];

const store = useStore();

// const sidebarMinimize = () => store.commit("sidebarMinimize");
// const toggleConfigurator = () => store.commit("toggleConfigurator");

onMounted(() => {
  setTooltip();
});

onBeforeMount(() => {
  store.state.layout = "vr";
  store.state.showNavbar = false;
  store.state.showSidenav = false;
  store.state.showFooter = false;
  body.classList.add("virtual-reality");
  store.state.isTransparent = "bg-white";
});
onBeforeUnmount(() => {
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
});
</script>

<template>
  <div class="container top-0 position-sticky z-index-sticky">
    <div class="row">
      <div class="col-12">
        <navbar isBlur="blur  border-radius-lg my-3 py-2 start-0 end-0 mx-4 shadow" v-bind:darkMode="true" />
      </div>
    </div>
  </div>
  <!-- Header Section -->
  <div class="mx-3 mt-2 position-relative" :style="{
          backgroundImage: 'linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5) ) ,url(' + require('@/assets/img/desa.jpg') + ')',
          backgroundSize: 'cover',
          backgroundPosition: 'center',
          height: '65vh',
          borderRadius: '30px 30px 0 0'
        }">
    <div class="container-fluid h-100">
      <div class="row h-100 justify-content-center align-items-center">
        <div class="col-auto text-center">
          <h1 class="text-white">Welcome to</h1>
          <h4 class="text-white">WEBSITE USUL DESA</h4>
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
              <h1 class="text-gradient text-success"><span>25</span></h1>
              <h5 class="mt-3">Usulan Masuk</h5>
              <p class="text-sm font-weight-normal">Usulan berisi ide yang dikemukakan masyarakat <br> berdasarkan
                permasalahan yang ada</p>
            </div>
            <hr class="vertical dark">
          </div>
          <div class="col-md-4 position-relative">
            <div class="text-center">
              <h1 class="text-gradient text-success"><span>12</span></h1>
              <h5 class="mt-3">Usulan di Setujui</h5>
              <p class="text-sm font-weight-normal">Usulan yang di proses oleh perangkat desa</p>
            </div>
            <hr class="vertical dark">
          </div>
          <div class="col-md-4 position-relative">
            <div class="text-center">
              <h1 class="text-gradient text-success"><span>70</span>+</h1>
              <h5 class="mt-3">Pengguna Aktif</h5>
              <p class="text-sm font-weight-normal">From buttons, to inputs, navbars, alerts or cards, you are
                covered</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <app-footer class="py-3 bg-white border-radius-lg" />
</template>
