import { createRouter, createWebHistory } from "vue-router";
import LandingPage from "../views/LandingPage.vue";

//USER
import UserDashboard from "../views/user/Dashboard.vue";
import UserUsulan from "../views/user/Usulan.vue";
import ProgramDesa from "../views/user/Program.vue";
import DetailProgramDesa from "../views/user/DetailProgram.vue";
import ProgressProgramDesa from "../views/user/ProgressProgram.vue";
import DetailProgressProgramDesa from "../views/user/DetailProgressProgram.vue";
import InputUsulan from "../views/user/InputUsulan.vue";
import UserUsulanDiskusi from "../views/user/UsulanDiskusi.vue";

//ADMIN
import AdminDashboard from "../views/admin/Dashboard.vue";
import DetailUsulan from "../views/admin/DetailUsulan.vue";
import ManageUsulan from "../views/admin/ManageUsulan.vue";
import VerifikasiWarga from "../views/admin/VeirfikasiWarga.vue";
import Program from "../views/admin/Program.vue";
import DetailProgram from "../views/admin/DetailProgram.vue";
import TambahProgram from "../views/admin/TambahProgram.vue";
import ProgressProgram from "../views/admin/ProgressProgram.vue";
import EditProgressProgram from "../views/admin/EditProgressProgram.vue";
import TambahProgressProgram from "../views/admin/TambahProgressProgram.vue";

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
  {
    path: "/admin/dashboard",
    name: "Admin Dashboard",
    component: AdminDashboard,
  },
  {
    path: "/admin/usulan",
    name: "Manage Usulan",
    component: ManageUsulan,
  },
  {
    path: "/admin/usulan/:id",
    name: "Detail Usulan",
    component: DetailUsulan,
  },
  {
    path: "/admin/verifikasi",
    name: "Verifikasi Warga",
    component: VerifikasiWarga,
  },
  {
    path: "/admin/program",
    name: "Program",
    component: Program,
  },
  {
    path: "/admin/program/:id",
    name: "Detail Program",
    component: DetailProgram,
  },
  {
    path: "/admin/program/tambah",
    name: "Tambah Program",
    component: TambahProgram,
  },
  {
    path: "/admin/program/:idprogram/progress/tambah",
    name: "Tambah Progress Program", 
    component: TambahProgressProgram,
  },
  {
    path: "/admin/program/:idprogram/progress",
    name: "Progress Program", 
    component: ProgressProgram,
  },
  {
    path: "/admin/program/:idprogram/progress/:idprogress/edit",
    name: "Edit Progress Program",
    component: EditProgressProgram,
  },

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
    path: "/program",
    name: "Program Desa",
    component: ProgramDesa,
  },
  {
    path: "/program/:idprogram",
    name: "Detail Program Desa",
    component: DetailProgramDesa,
  },
  {
    path: "/program/:idprogram/progress",
    name: "Progress Program Desa",
    component: ProgressProgramDesa,
  },
  {
    path: "/program/:idprogram/progress/:idprogress",
    name: "Detail Progress Program Desa",
    component: DetailProgressProgramDesa,
  },
  {
    path: "/usulan/input",
    name: "Input Usulan Warga",
    component: InputUsulan,
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
