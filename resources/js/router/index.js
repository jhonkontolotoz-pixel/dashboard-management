import { createRouter, createWebHistory } from 'vue-router';

// ======= IMPORT SEMUA HALAMAN ======= //
import Home from '../pages/Home.vue';
import About from '../pages/About.vue';
import Login from '../pages/Login.vue';
import Register from '../pages/Register.vue';
import Dashboard from '../pages/Dashboard.vue';
import Profile from '../pages/Profile.vue';
import Settings from '../pages/Setting.vue';
import ForgotPassword from '../pages/ForgotPassword.vue';
import ResetPassword from '../pages/ResetPassword.vue';
import VerifyEmail from '../pages/VerifyEmail.vue';
import VerifyOtp from '../pages/VerifyOtp.vue';
import Tasks from '../pages/Tasks.vue';
import RoleUser from '../pages/RoleUser.vue';

// 🔹 Tambahan baru untuk sistem login OTP
import VerifyLoginOtp from '../pages/VerifyLoginOtp.vue';

// ======= DEFINISI ROUTES ======= //
const routes = [
  { path: '/', name: 'home', component: Home },
  { path: '/about', name: 'about', component: About },

  // 🔹 Register & Verifikasi Email
  { path: '/register', name: 'register', component: Register },
  { path: '/verify-email', name: 'verify-email', component: VerifyEmail },

  // 🔹 Login OTP
  { path: '/login', name: 'login', component: Login },
  { path: '/verify-login-otp', name: 'verify-login-otp', component: VerifyLoginOtp },

  // 🔹 Dashboard & fitur utama
  {
    path: '/dashboard',
    name: 'dashboard',
    component: Dashboard,
    meta: { requiresAuth: true },
  },
  {
    path: '/profile',
    name: 'Profile',
    component: Profile,
    meta: { requiresAuth: true },
  },
  {
    path: '/settings',
    name: 'Settings',
    component: Settings,
    meta: { requiresAuth: true },
  },

  // 🔹 Fitur tambahan
  {
    path: '/tasks',
    name: 'Tasks',
    component: Tasks,
    meta: { requiresAuth: true, role: 'user' },
  },
  {
    path: '/role-user',
    name: 'RoleUser',
    component: RoleUser,
    meta: { requiresAuth: true, role: 'admin' },
  },

  // 🔹 Lupa Password & Reset
  { path: '/forgot-password', name: 'ForgotPassword', component: ForgotPassword },
  { path: '/verify-otp', name: 'VerifyOtp', component: VerifyOtp },
  { path: '/reset-password', name: 'ResetPassword', component: ResetPassword },
];

// ======= SETUP ROUTER ======= //
const router = createRouter({
  history: createWebHistory(),
  routes,
});

// ======= NAVIGATION GUARD ======= //
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token');
  const userData = localStorage.getItem('user');
  const requiresAuth = to.matched.some((record) => record.meta.requiresAuth);

  // Jika halaman butuh login tapi token belum ada
  if (requiresAuth && !token) {
    alert('Sesi kamu habis atau belum login.');
    return next({ name: 'login' });
  }

  // Jika sudah login tapi coba buka login/register/verify-login-otp
  if (
    (to.name === 'login' || to.name === 'register' || to.name === 'verify-login-otp') &&
    token
  ) {
    return next({ name: 'dashboard' });
  }

  // Cek role untuk akses halaman tertentu
  if (to.meta.role && userData) {
    const user = JSON.parse(userData);
    if (to.meta.role !== user.role) {
      alert('Kamu tidak punya akses ke halaman ini.');
      return next({ name: 'dashboard' });
    }
  }

  next();
});

export default router;


