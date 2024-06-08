
import { createRouter, createWebHistory } from 'vue-router';
import axios from 'axios';
import BASE_URL from '@/api/config-api';
import LandingPage from '../views/LandingPage.vue';

// USER
import UserDashboard from '../views/user/Dashboard.vue';
import UserUsulan from '../views/user/Usulan.vue';
import ProgramDesa from '../views/user/Program.vue';
import DetailProgramDesa from '../views/user/DetailProgram.vue';
import ProgressProgramDesa from '../views/user/ProgressProgram.vue';
import DetailProgressProgramDesa from '../views/user/DetailProgressProgram.vue';
import InputUsulan from '../views/user/InputUsulan.vue';
import UserUsulanDiskusi from '../views/user/UsulanDiskusi.vue';

// ADMIN
import AdminDashboard from '../views/admin/Dashboard.vue';
import DetailUsulan from '../views/admin/DetailUsulan.vue';
import ManageUsulan from '../views/admin/ManageUsulan.vue';
import TanggapiUsulan from '../views/admin/TanggapiUsulan.vue';
import VerifikasiWarga from '../views/admin/VeirfikasiWarga.vue';
import Program from '../views/admin/Program.vue';
import DetailProgram from '../views/admin/DetailProgram.vue';
import TambahProgram from '../views/admin/TambahProgram.vue';
import ProgressProgram from '../views/admin/ProgressProgram.vue';
import EditProgressProgram from '../views/admin/EditProgressProgram.vue';
import TambahProgressProgram from '../views/admin/TambahProgressProgram.vue';

import Tables from '../views/Tables.vue';
import Billing from '../views/Billing.vue';
import RTL from '../views/Rtl.vue';
import Profile from '../views/Profile.vue';
import Signup from '../views/Signup.vue';
import Login from '../views/Loginn.vue';
import Signin from '../views/Signin.vue';

// Error Page
import Unauthorized from '../views/Unauthorized.vue'; // Create this component

const routes = [
  {
    path: '/',
    name: '/',
    redirect: '/home',
  },
  {
    path: '/home',
    name: 'Landing Page',
    component: LandingPage,
  },
  {
    path: '/signup',
    name: 'Signup',
    component: Signup,
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
  },

  // ADMIN
  {
    path: '/admin/dashboard',
    name: 'Admin Dashboard',
    component: AdminDashboard,
    meta: { requiresAuth: true, role: 'admin' },
  },
  {
    path: '/admin/usulan',
    name: 'Manage Usulan',
    component: ManageUsulan,
    meta: { requiresAuth: true, role: 'admin' },
  },
  {
    path: '/admin/usulan/:id',
    name: 'Detail Usulan',
    component: DetailUsulan,
    meta: { requiresAuth: true, role: 'admin' },
  },
  {
    path: '/admin/usulan/:id/tanggapi',
    name: 'Tanggapi Usulan',
    component: TanggapiUsulan,
    meta: { requiresAuth: true, role: 'admin' },
  },
  {
    path: '/admin/verifikasi',
    name: 'Verifikasi Warga',
    component: VerifikasiWarga,
    meta: { requiresAuth: true, role: 'admin' },
  },
  {
    path: '/admin/program',
    name: 'Program',
    component: Program,
    meta: { requiresAuth: true, role: 'admin' },
  },
  {
    path: '/admin/program/:id',
    name: 'Detail Program',
    component: DetailProgram,
    meta: { requiresAuth: true, role: 'admin' },
  },
  {
    path: '/admin/program/tambah',
    name: 'Tambah Program',
    component: TambahProgram,
    meta: { requiresAuth: true, role: 'admin' },
  },
  {
    path: '/admin/program/:idprogram/progress/tambah',
    name: 'Tambah Progress Program',
    component: TambahProgressProgram,
    meta: { requiresAuth: true, role: 'admin' },
  },
  {
    path: '/admin/program/:idprogram/progress',
    name: 'Progress Program',
    component: ProgressProgram,
    meta: { requiresAuth: true, role: 'admin' },
  },
  {
    path: '/admin/program/:idprogram/progress/:idprogress/edit',
    name: 'Edit Progress Program',
    component: EditProgressProgram,
    meta: { requiresAuth: true, role: 'admin' },
  },

  // USER
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: UserDashboard,
    meta: { requiresAuth: true, role: 'user' },
  },
  {
    path: '/usulan',
    name: 'Usulan Warga',
    component: UserUsulan,
    meta: { requiresAuth: true, role: 'user' },
  },
  {
    path: '/program',
    name: 'Program Desa',
    component: ProgramDesa,
    meta: { requiresAuth: true, role: 'user' },
  },
  {
    path: '/program/:idprogram',
    name: 'Detail Program Desa',
    component: DetailProgramDesa,
    meta: { requiresAuth: true, role: 'user' },
  },
  {
    path: '/program/:idprogram/progress',
    name: 'Progress Program Desa',
    component: ProgressProgramDesa,
    meta: { requiresAuth: true, role: 'user' },
  },
  {
    path: '/program/:idprogram/progress/:idprogress',
    name: 'Detail Progress Program Desa',
    component: DetailProgressProgramDesa,
    meta: { requiresAuth: true, role: 'user' },
  },
  {
    path: '/usulan/input',
    name: 'Input Usulan Warga',
    component: InputUsulan,
    meta: { requiresAuth: true, role: 'user' },
  },
  {
    path: '/usulan/:id',
    name: 'Diskusi Usulan Warga',
    component: UserUsulanDiskusi,
    meta: { requiresAuth: true, role: 'user' },
  },

  // TEMP
  {
    path: '/tables',
    name: 'Tables',
    component: Tables,
  },
  {
    path: '/billing',
    name: 'Billing',
    component: Billing,
  },

  {
    path: '/rtl-page',
    name: 'RTL',
    component: RTL,
  },
  {
    path: '/profile',
    name: 'Profile',
    component: Profile,
  },
  {
    path: '/signin',
    name: 'Signin',
    component: Signin,
  },

  // Unauthorized
  {
    path: '/unauthorized',
    name: 'Unauthorized',
    component: Unauthorized,
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
  linkActiveClass: 'active',
});

router.beforeEach(async (to, from, next) => {
  const isAuthenticated = localStorage.getItem('access_token') !== null;
  const userRole = await getUserRole();

  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!isAuthenticated) {
      next({
        path: '/login',
        query: { redirect: to.fullPath },
      });
    } else {
      const requiredRole = to.meta.role;
      if (userRole !== requiredRole) {
        next({ path: '/unauthorized' });
      } else {
        next();
      }
    }
  } else {
    next();
  }
});

async function getUserRole() {
  try {
    const response = await axios.get(`${BASE_URL}/user`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('access_token')}`,
      },
    });
    return response.data.role;
  } catch (error) {
    console.error(error);
    return null;
  }
}

export default router;