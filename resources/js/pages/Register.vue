<template>
  <div class="register-anime">
    <div class="snow"></div>

    <form @submit.prevent="register" class="register-card">
      <h2 class="title">✨ DAFTAR AKUN ✨</h2>

      <div class="form-group">
        <label for="name">Nama</label>
        <input type="text" id="name" v-model="form.name" required />
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" v-model="form.email" required />
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" v-model="form.password" required />
      </div>

      <div class="form-group">
        <label for="password_confirmation">Konfirmasi Password</label>
        <input type="password" id="password_confirmation" v-model="form.password_confirmation" required />
      </div>

      <button type="submit" :disabled="isLoading" class="btn-primary">
        {{ isLoading ? 'Memuat...' : 'Daftar' }}
      </button>

      <p v-if="successMessage" class="message success">{{ successMessage }}</p>
      <p v-if="errorMessage" class="message error">{{ errorMessage }}</p>

      <p class="register-link">
        Sudah punya akun?
        <router-link to="/login">Masuk di sini</router-link>
      </p>

      <router-link to="/" class="back-link">Kembali ke Home</router-link>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

axios.defaults.baseURL = 'http://127.0.0.1:8000/api';

const router = useRouter();

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
});

const isLoading = ref(false);
const successMessage = ref('');
const errorMessage = ref('');

const register = async () => {
  isLoading.value = true;
  successMessage.value = '';
  errorMessage.value = '';

  if (form.value.password !== form.value.password_confirmation) {
    errorMessage.value = 'Konfirmasi password tidak sesuai.';
    isLoading.value = false;
    return;
  }

  try {
    const res = await axios.post('/register', form.value);

    successMessage.value = res.data.message || 'Registrasi berhasil!';
    
    // Simpan email untuk verifikasi OTP nanti
    localStorage.setItem('pendingEmail', form.value.email);

    // Redirect ke halaman verifikasi OTP email
    setTimeout(() => {
      router.push({ name: 'verify-email', query: { email: form.value.email } });
    }, 1000);

  } catch (err) {
    errorMessage.value =
      err.response?.data?.message || 'Registrasi gagal. Coba lagi nanti.';
  } finally {
    isLoading.value = false;
  }
};
</script>


<style scoped>
.register-anime {
  position: fixed; inset: 0;
  display: flex; justify-content: center; align-items: center;
  background: radial-gradient(circle at 20% 20%, #111827, #0b0b12 70%);
  overflow: hidden; font-family: 'Poppins', sans-serif;
}

.snow {
  position: absolute; top:0; left:0; width:100%; height:100%;
  background-image: 
    radial-gradient(2px 2px at 20px 30px, white, transparent),
    radial-gradient(3px 3px at 100px 150px, white, transparent),
    radial-gradient(2px 2px at 200px 80px, white, transparent),
    radial-gradient(3px 3px at 300px 120px, white, transparent),
    radial-gradient(2px 2px at 400px 50px, white, transparent),
    radial-gradient(3px 3px at 500px 180px, white, transparent);
  background-repeat: repeat;
  background-size: 600px 600px;
  animation: snowAnim 15s linear infinite;
  opacity: 0.6;
  pointer-events: none;
  z-index: 0;
}

@keyframes snowAnim {
  from { background-position: 0 0,100px 200px,200px 400px,300px 100px,400px 300px,500px 500px; }
  to   { background-position: 0 600px,100px 800px,200px 1000px,300px 700px,400px 900px,500px 1100px; }
}

.register-card {
  position: relative; z-index: 2;
  background: rgba(20, 20, 35, 0.9); padding: 2.5rem;
  border-radius: 16px; border: 1px solid rgba(139,92,246,0.3);
  box-shadow: 0 0 25px rgba(124,58,237,0.35),0 0 60px rgba(56,189,248,0.15);
  backdrop-filter: blur(10px); width:100%; max-width:400px; text-align:center;
  animation: fadeIn 0.5s ease-out;
}

.title {
  margin-bottom:1.5rem; font-size:1.6rem; font-weight:600;
  background: linear-gradient(to right,#a78bfa,#38bdf8);
  -webkit-background-clip:text; -webkit-text-fill-color:transparent;
}

.form-group { margin-bottom:1.2rem; text-align:left; }
label { color:#d1d5db; font-size:0.95rem; margin-bottom:0.5rem; display:block; }
input {
  width:100%; padding:0.75rem 1rem; border-radius:10px;
  border:1px solid rgba(255,255,255,0.2); background-color: rgba(255,255,255,0.1);
  color:#fff; outline:none; transition:0.3s;
}
input:focus { border-color:#8b5cf6; box-shadow:0 0 12px rgba(139,92,246,0.5); }

.btn-primary {
  width:100%; padding:0.8rem; margin-top:0.5rem;
  background:linear-gradient(90deg,#8b5cf6,#3b82f6); color:#fff; border:none; border-radius:12px;
  font-weight:600; font-size:1rem; cursor:pointer;
  transition:transform 0.15s ease,box-shadow 0.3s ease;
  box-shadow:0 0 20px rgba(139,92,246,0.25);
}
.btn-primary:hover:enabled { transform:translateY(-2px); box-shadow:0 0 25px rgba(139,92,246,0.45); }
.btn-primary:disabled { opacity:0.6; }

.register-link,.back-link { margin-top:1.5rem; text-align:center; color:#f8fafc; font-size:0.95rem; }
.register-link a { color:#a5b4fc; font-weight:600; text-decoration:none; }
.register-link a:hover { color:#c4b5fd; text-decoration:underline; }
.back-link { display:block; margin-top:1rem; color:#cbd5e1; }
.message { margin-top:1rem; text-align:center; font-size:0.9rem; }
.message.success { color:#7fff7f; }
.message.error { color:#f87171; }

@keyframes fadeIn { from {opacity:0; transform:translateY(10px);} to {opacity:1; transform:translateY(0);} }
</style>





