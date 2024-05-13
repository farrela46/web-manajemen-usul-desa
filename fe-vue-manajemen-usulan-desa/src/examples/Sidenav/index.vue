<script>
import { computed } from "vue";
import { useStore } from "vuex";
import SidenavList from "./SidenavList.vue";


export default {
  components: {
    SidenavList
  },
  mounted() {
    const store = useStore();
    this.isRTL = computed(() => store.state.isRTL);
    this.layout = computed(() => store.state.layout);
    this.sidebarType = computed(() => store.state.sidebarType);
    this.darkMode = computed(() => store.state.darkMode);
    this.userRole = computed(() => store.state.userRole);
    // console.log('User Role:', this.userRole.value);
    // console.log("indexside");
  },
  data() {
    return {
      isRTL: false,
      layout: null,
      sidebarType: null,
      darkMode: false,
      userRole: null
    };
  },

};
</script>

<template>
  <div v-show="layout === 'default'" class="min-height-300 position-absolute w-100"
    :class="`${darkMode ? 'bg-transparent' : 'bg-langit'}`" />

  <aside class="my-3 overflow-auto border-0 sidenav navbar navbar-vertical navbar-expand-xs border-radius-md" :class="`${isRTL ? 'me-3 rotate-caret fixed-end' : 'fixed-start ms-3'}    
      ${layout === 'landing' ? 'bg-transparent shadow-none' : ' '
      } ${sidebarType}`" id="sidenav-main">
    <div class="sidenav-header">
      <i class="top-0 p-3 cursor-pointer fas fa-times text-secondary opacity-5 position-absolute end-0 d-none d-xl-none"
        aria-hidden="true" id="iconSidenav"></i>

      <router-link class="m-0 navbar-brand" to="/">
        <img :src="require('@/assets/img/telyu.png')" class="navbar-brand-img h-100" alt="main_logo" />
        <span class="ms-2 font-weight-bold me-2">Dinovasi</span>
      </router-link>

    </div>

    <hr class="mt-0 horizontal dark" />
    <sidenav-list :user-role="userRole" />
  </aside>
</template>

<style>
.bg-langit {
  /* background-image: url(../../assets/img/telyusu.jpg); */
  background-color: #ff6d62;
  /* background-size: cover; */
}
</style>