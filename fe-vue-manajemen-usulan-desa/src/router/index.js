import { createRouter, createWebHistory } from "vue-router";
import LandingPage from "../views/LandingPage.vue";

//USER
import UserDashboard from "../views/user/Dashboard.vue";
import UserUsulan from "../views/user/Usulan.vue";
import UserUsulanDiskusi from "../views/user/UsulanDiskusi.vue";

import Tables from "../views/Tables.vue";
import Billing from "../views/Billing.vue";
import RTL from "../views/Rtl.vue";
import Profile from "../views/Profile.vue";
import Signup from "../views/Signup.vue";
import Login from "../views/Loginn.vue";
import Signin from "../views/Signin.vue";

const routes = [
  {
    path: "/",
    name: "/",
    redirect: "/home",
  },
  {
    path: "/home",
    name: "Landing Page",
    component: LandingPage,
  },
  {
    path: "/signup",
    name: "Signup",
    component: Signup,
  },
  {
    path: "/login",
    name: "Login",
    component: Login,
  },


  //ADMIN

  //USER
  {
    path: "/dashboard",
    name: "Dashboard",
    component: UserDashboard,
  },
  {
    path: "/usulan",
    name: "Usulan Warga",
    component: UserUsulan,
  },
  {
    path: "/usulan/:id",
    name: "DIskusi Usulan Warga",
    component: UserUsulanDiskusi,
  },


  //TEMP
  {
    path: "/tables",
    name: "Tables",
    component: Tables,
  },
  {
    path: "/billing",
    name: "Billing",
    component: Billing,
  },

  {
    path: "/rtl-page",
    name: "RTL",
    component: RTL,
  },
  {
    path: "/profile",
    name: "Profile",
    component: Profile,
  },
  {
    path: "/signin",
    name: "Signin",
    component: Signin,
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
  linkActiveClass: "active",
});

export default router;
