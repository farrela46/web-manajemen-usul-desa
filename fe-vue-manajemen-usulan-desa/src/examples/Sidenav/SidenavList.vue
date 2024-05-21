<script>
import { useRoute } from "vue-router";
import SidenavItem from "./SidenavItem.vue";
import axios from "axios";
import BASE_URL from '@/api/config-api';

export default {
  components: {
    SidenavItem
  },
  data() {
    return {
      userRole: ''
    };
  },
  mounted() {
    // this.getUser()
  },
  methods: {
    getRoute() {
      const route = useRoute();
      const routeArr = route.path.split("/");
      return routeArr[1];
    },
    async getUser() {
      try {
        const response = await axios.get(`${BASE_URL}/user`, {
          headers: {
            Authorization: "Bearer " + localStorage.getItem('access_token')
          }
        });
        this.id = response.data.user.id;
        this.username = response.data.user.name;
        this.userRole = response.data.user.role;
      } catch (error) {
        console.error(error);

        if (error.response && error.response.data.message) {
          const errorMessage = error.response.data.message;
          console.log(errorMessage)
        }
      }
    }
  },
};
</script>
<template>
  <div class="collapse navbar-collapse w-auto h-auto h-100 " style="color: white" id="sidenav-collapse-main">
    <ul class="navbar-nav " >
      <li class="nav-item" >
        <sidenav-item to="/admin/dashboard" :class="getRoute() === 'dashboard' ? 'active' : ''" navText="Dashboard">
          <template v-slot:icon>
            <i class="fas fa-chart-pie text-white text-sm opacity-10"></i>
          </template>
        </sidenav-item>
      </li>
      <li class="nav-item" >
        <sidenav-item to="/admin/usulan" :class="getRoute() === 'manage usulan' ? 'active' : ''" navText="Usulan">
          <template v-slot:icon>
            <i class="ni ni-tv-2 text-white text-sm opacity-10"></i>
          </template>
        </sidenav-item>
      </li>
      <li class="nav-item" >
        <sidenav-item to="/admin/program" :class="getRoute() === 'program' ? 'active' : ''" navText="Program">
          <template v-slot:icon>
            <i class="ni ni-tv-2 text-white text-sm opacity-10"></i>
          </template>
        </sidenav-item>
      </li>
      <li class="nav-item" >
        <sidenav-item to="/admin/verifikasi" :class="getRoute() === 'verifikasiZ' ? 'active' : ''" navText="Verifikasi">
          <template v-slot:icon>
            <i class="far fa-check-circle text-white text-sm opacity-10"></i>
          </template>
        </sidenav-item>
      </li>
    </ul>
  </div>
</template>
