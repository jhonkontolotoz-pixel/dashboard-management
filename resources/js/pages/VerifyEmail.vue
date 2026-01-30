<template>
  <div class="verify-anime">
    <div class="snow"></div>

    <form @submit.prevent="verifyOtp" class="verify-card">
      <h2 class="title">✨ VERIFIKASI EMAIL ✨</h2>

      <p class="desc">
        Masukkan kode OTP yang dikirim ke email kamu: <strong>{{ email }}</strong>
      </p>

      <div class="form-group">
        <label for="otp">Kode OTP</label>
        <input type="text" id="otp" v-model="otp" maxlength="6" required />
      </div>

      <button type="submit" :disabled="isLoading" class="btn-primary">
        {{ isLoading ? 'Memverifikasi...' : 'Verifikasi' }}
      </button>

      <p v-if="successMessage" class="message success">{{ successMessage }}</p>
      <p v-if="errorMessage" class="message error">{{ errorMessage }}</p>

      <router-link to="/login" class="back-link">Kembali ke Login</router-link>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';

axios.defaults.baseURL = 'http://127.0.0.1:8000/api';

const route = useRoute();
const router = useRouter();

const email = ref('');
const otp = ref('');
const isLoading = ref(false);
const successMessage = ref('');
const errorMessage = ref('');

// Ambil email dari query param
onMounted(() => {
  email.value = route.query.email || '';
});

const verifyOtp = async () => {
  if (!otp.value || otp.value.length !== 6) {
    errorMessage.value = 'Kode OTP harus 6 digit.';
    return;
  }

  isLoading.value = true;
  successMessage.value = '';
  errorMessage.value = '';

  try {
    const res = await axios.post('/verify-email', {
      email: email.value,
      otp: otp.value
    });

    successMessage.value = res.data.message;

    // delay supaya user lihat message
    setTimeout(() => {
      router.push({ name: 'login' });
    }, 800);
  } catch (err) {
    if (err.response) {
      errorMessage.value = err.response.data.message || 'Verifikasi gagal. Coba lagi.';
    } else {
      errorMessage.value = 'Gagal terhubung ke server.';
    }
  } finally {
    isLoading.value = false;
  }
};
</script>

<style scoped>
.verify-anime {
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

.verify-card {
  position: relative; z-index: 2;
  background: rgba(20, 20, 35, 0.9); padding: 2.5rem;
  border-radius: 16px; border: 1px solid rgba(139,92,246,0.3);
  box-shadow: 0 0 25px rgba(124,58,237,0.35),0 0 60px rgba(56,189,248,0.15);
  backdrop-filter: blur(10px); width:100%; max-width:400px; text-align:center;
  animation: fadeIn 0.5s ease-out;
}

.title {
  margin-bottom:1rem; font-size:1.6rem; font-weight:600;
  background: linear-gradient(to right,#a78bfa,#38bdf8);
  -webkit-background-clip:text; -webkit-text-fill-color:transparent;
}

.desc {
  color:#d1d5db; font-size:0.95rem; margin-bottom:1.5rem;
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

.back-link { display:block; margin-top:1.5rem; color:#cbd5e1; text-align:center; }
.message { margin-top:1rem; text-align:center; font-size:0.9rem; }
.message.success { color:#7fff7f; }
.message.error { color:#f87171; }

@keyframes fadeIn { from {opacity:0; transform:translateY(10px);} to {opacity:1; transform:translateY(0);} }
</style>




