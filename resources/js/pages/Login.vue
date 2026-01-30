<template>
  <div class="login-anime">
    <div class="snow"></div>
    <div class="login-card">
      <h2 class="title">✨ SILAHKAN LOGIN ✨</h2>

      <!-- Pilih metode login -->
      <div class="tab-buttons">
        <button :class="{active: loginType==='otp'}" @click="loginType='otp'">Login OTP</button>
        <button :class="{active: loginType==='password'}" @click="loginType='password'">Login Password</button>
      </div>

      <!-- LOGIN OTP -->
      <form v-if="loginType==='otp'" @submit.prevent="handleLoginOtp">
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" v-model="otpCredentials.email" required />
        </div>
        <button type="submit" :disabled="isLoading" class="btn-primary">
          {{ isLoading ? 'Mengirim OTP...' : 'Kirim OTP ke Email' }}
        </button>
      </form>

      <!-- LOGIN PASSWORD -->
      <form v-if="loginType==='password'" @submit.prevent="handleLoginPassword">
        <div class="form-group">
          <label for="emailPwd">Email</label>
          <input type="email" id="emailPwd" v-model="pwdCredentials.email" required />
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" v-model="pwdCredentials.password" required />
        </div>
        <button type="submit" :disabled="isLoading" class="btn-primary">
          {{ isLoading ? 'Login...' : 'Login' }}
        </button>
      </form>

      <p v-if="error" class="message">{{ error }}</p>

      <p class="register-link">
        Belum punya akun?
        <router-link to="/register">Daftar di sini</router-link>
      </p>     
      <router-link to="/forgot-password">Lupa Password?</router-link>
      <router-link to="/" class="back-link">Kembali ke Home</router-link>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const loginType = ref('otp') // 'otp' atau 'password'
const isLoading = ref(false)
const error = ref(null)

const otpCredentials = ref({ email: '' })
const pwdCredentials = ref({ email: '', password: '' })

// LOGIN OTP
const handleLoginOtp = async () => {
  isLoading.value = true
  error.value = null
  try {
    await axios.post('http://127.0.0.1:8000/api/login-otp-request', otpCredentials.value)
    localStorage.setItem('login_email', otpCredentials.value.email)
    router.push('/verify-login-otp')
  } catch (err) {
    error.value = err.response?.data?.message || 'Gagal mengirim OTP. Coba lagi.'
  } finally {
    isLoading.value = false
  }
}

// LOGIN PASSWORD
const handleLoginPassword = async () => {
  isLoading.value = true;
  error.value = null;

  try {
    const res = await axios.post('http://127.0.0.1:8000/api/login-password', pwdCredentials.value);

    const user = res.data.user;
    const token = res.data.token;

    if (!user || !token) throw new Error('Respons API tidak lengkap');

    // Simpan token dan user ke localStorage
    localStorage.setItem('token', token);
    localStorage.setItem('user', JSON.stringify({
      id: user.id,
      name: user.name,
      email: user.email,
      role: user.role
    }));

    // Redirect ke dashboard
    router.push('/dashboard');
  } catch (err) {
    console.error(err);
    if (err.response) {
      if (err.response.status === 401) {
        error.value = 'Email atau password salah';
      } else if (err.response.status === 403) {
        error.value = 'Email belum diverifikasi. Silahkan cek email dan OTP';
      } else {
        error.value = err.response.data.message || 'Login gagal';
      }
    } else {
      error.value = 'Tidak bisa terhubung ke server';
    }
  } finally {
    isLoading.value = false;
  }
};
</script>

<style scoped>
/* BACKGROUND & SNOW */
.login-anime {
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

/* CARD */
.login-card {
  position: relative; z-index: 2;
  background: rgba(20, 20, 35, 0.9); padding: 2.5rem;
  border-radius: 16px; border: 1px solid rgba(139,92,246,0.3);
  box-shadow: 0 0 25px rgba(124,58,237,0.35),0 0 60px rgba(56,189,248,0.15);
  backdrop-filter: blur(10px); width:100%; max-width:400px; text-align:center;
  animation: fadeIn 0.5s ease-out;
}

/* TITLE */
.title {
  margin-bottom:1.5rem; font-size:1.6rem; font-weight:600;
  background: linear-gradient(to right,#a78bfa,#38bdf8);
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;
}

/* INPUT */
.form-group { margin-bottom:1.2rem; text-align:left; }
label { color:#d1d5db; font-size:0.95rem; margin-bottom:0.5rem; display:block; }
input {
  width:100%; padding:0.75rem 1rem; border-radius:10px;
  border:1px solid rgba(255,255,255,0.2); background-color: rgba(255,255,255,0.1);
  color:#fff; outline:none; transition:0.3s;
}
input:focus { border-color:#8b5cf6; box-shadow:0 0 12px rgba(139,92,246,0.5); }

/* BUTTON */
.btn-primary {
  width:100%; padding:0.8rem; margin-top:0.5rem;
  background:linear-gradient(90deg,#8b5cf6,#3b82f6); color:#fff; border:none; border-radius:12px;
  font-weight:600; font-size:1rem; cursor:pointer;
  transition:transform 0.15s ease,box-shadow 0.3s ease;
  box-shadow:0 0 20px rgba(139,92,246,0.25);
}
.btn-primary:hover:enabled { transform:translateY(-2px); box-shadow:0 0 25px rgba(139,92,246,0.45); }
.btn-primary:disabled { opacity:0.6; }

/* LINK & PESAN */
.register-link,.back-link { margin-top:1.5rem; text-align:center; color:#f8fafc; font-size:0.95rem; }
.register-link a { color:#a5b4fc; font-weight:600; text-decoration:none; }
.register-link a:hover { color:#c4b5fd; text-decoration:underline; }
.back-link { display:block; margin-top:1rem; color:#cbd5e1; }
.message { margin-top:1rem; color:#f87171; text-align:center; font-size:0.9rem; }

/* TAB BUTTONS */
.tab-buttons {
  display:flex; justify-content:center; margin-bottom:1.2rem;
}
.tab-buttons button {
  flex:1; padding:0.5rem 0; border:none; background:#3b4252; color:#cbd5e1;
  cursor:pointer; font-weight:600; transition:0.3s; border-radius:8px 8px 0 0;
}
.tab-buttons button.active {
  background:linear-gradient(90deg,#8b5cf6,#3b82f6); color:#fff;
}
.tab-buttons button:not(:last-child) { margin-right:4px; }

/* ANIMATIONS */
@keyframes fadeIn { from {opacity:0; transform:translateY(10px);} to {opacity:1; transform:translateY(0);} }
</style>








