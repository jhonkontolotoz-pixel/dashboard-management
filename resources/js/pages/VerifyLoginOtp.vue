<template>
  <div class="verify-anime">
    <div class="snow"></div>
    <form @submit.prevent="verifyOtp" class="verify-card">
      <h2 class="title">🔐 Verifikasi OTP</h2>
      <p>Masukkan kode OTP yang dikirim ke email kamu.</p>

      <div class="form-group">
        <label for="otp">Kode OTP</label>
        <input type="text" id="otp" v-model="otp" maxlength="6" required placeholder="MULYADI TAYUPAN"/>
      </div>

      <button type="submit" :disabled="isLoading" class="btn-primary">
        {{ isLoading ? 'Memverifikasi...' : 'Verifikasi OTP' }}
      </button>

      <p v-if="message" class="message success">{{ message }}</p>
      <p v-if="error" class="message error">{{ error }}</p>

      <button class="btn-resend" @click.prevent="resendOtp" :disabled="resending">
        {{ resending ? 'Mengirim ulang...' : 'Kirim Ulang OTP' }}
      </button>

      <router-link to="/login" class="back-link">Kembali ke Login</router-link>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const otp = ref('')
const isLoading = ref(false)
const message = ref('')
const error = ref('')
const resending = ref(false)

const email = localStorage.getItem('login_email')

const verifyOtp = async () => {
  if (!email) {
    error.value = 'Email login tidak ditemukan. Silakan login ulang.'
    router.push('/login')
    return
  }

  isLoading.value = true
  message.value = ''
  error.value = ''

  try {
    const res = await axios.post('http://127.0.0.1:8000/api/login-otp-verify', { email, otp: otp.value })
    const token = res.data.token
    const user = res.data.user

    localStorage.setItem('token', token)
    localStorage.setItem('user', JSON.stringify(user))

    message.value = 'OTP berhasil diverifikasi! Mengarahkan ke dashboard...'
    setTimeout(() => router.push('/dashboard'), 1500)
  } catch (err) {
    error.value = err.response?.data?.message || 'Kode OTP salah atau sudah kadaluarsa.'
  } finally {
    isLoading.value = false
  }
}

const resendOtp = async () => {
  if (!email) { error.value = 'Email tidak ditemukan. Silakan login ulang.'; return }

  resending.value = true
  message.value = ''
  error.value = ''

  try {
    const res = await axios.post('http://127.0.0.1:8000/api/login-otp-request', { email })
    message.value = res.data.message || 'Kode OTP baru telah dikirim ke email.'
  } catch (err) {
    error.value = err.response?.data?.message || 'Gagal mengirim ulang OTP.'
  } finally {
    resending.value = false
  }
}
</script>

<style scoped>
/* === LAYOUT DARK ANIME === */
.verify-anime {
  position: fixed;
  inset: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  background: radial-gradient(circle at 20% 20%, #111827, #0b0b12 70%);
  font-family: 'Poppins', sans-serif;
  overflow: hidden;
}

/* === EFEK SALJU === */
.snow {
  position: absolute;
  width: 100%;
  height: 100%;
  background-image:
    radial-gradient(2px 2px at 20px 30px, white, transparent),
    radial-gradient(3px 3px at 100px 150px, white, transparent),
    radial-gradient(2px 2px at 200px 80px, white, transparent),
    radial-gradient(3px 3px at 300px 120px, white, transparent);
  background-repeat: repeat;
  background-size: 600px 600px;
  animation: snowAnim 15s linear infinite;
  opacity: 0.6;
  pointer-events: none;
  z-index: 0;
}

@keyframes snowAnim {
  from { background-position: 0 0; }
  to { background-position: 0 600px; }
}

/* === CARD === */
.verify-card {
  position: relative;
  z-index: 2;
  background: rgba(20, 20, 35, 0.85);
  padding: 2.5rem;
  border-radius: 16px;
  border: 1px solid rgba(139, 92, 246, 0.3);
  box-shadow: 0 0 25px rgba(124, 58, 237, 0.35),
              0 0 60px rgba(56, 189, 248, 0.15);
  backdrop-filter: blur(10px);
  width: 100%;
  max-width: 400px;
  animation: fadeIn 0.5s ease-out;
  text-align: center;
}

/* === TITLE === */
.title {
  margin-bottom: 1rem;
  font-size: 1.5rem;
  font-weight: 600;
  background: linear-gradient(to right, #a78bfa, #38bdf8);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

/* === INPUT === */
.form-group {
  margin-bottom: 1.2rem;
  text-align: left;
}
label {
  color: #9ca3af;
  font-size: 0.9rem;
  margin-bottom: 0.5rem;
  display: block;
}
input {
  width: 100%;
  padding: 0.75rem 1rem;
  border-radius: 10px;
  border: 1px solid rgba(255, 255, 255, 0.1);
  background-color: rgba(255, 255, 255, 0.05);
  color: #fff;
  outline: none;
  transition: 0.3s;
  text-align: center;
  font-size: 1.1rem;
  letter-spacing: 4px;
}
input:focus {
  border-color: #8b5cf6;
  box-shadow: 0 0 10px rgba(139, 92, 246, 0.4);
}

/* === BUTTONS === */
.btn-primary, .btn-resend {
  width: 100%;
  padding: 0.8rem;
  border: none;
  border-radius: 12px;
  font-weight: 600;
  font-size: 1rem;
  cursor: pointer;
  margin-top: 0.75rem;
  transition: transform 0.15s ease, box-shadow 0.3s ease;
}
.btn-primary {
  background: linear-gradient(90deg, #8b5cf6, #3b82f6);
  color: #fff;
  box-shadow: 0 0 20px rgba(139, 92, 246, 0.25);
}
.btn-resend {
  background: rgba(255, 255, 255, 0.05);
  color: #a78bfa;
  border: 1px solid rgba(139, 92, 246, 0.3);
}
.btn-primary:hover, .btn-resend:hover {
  transform: translateY(-2px);
  box-shadow: 0 0 25px rgba(139, 92, 246, 0.45);
}

/* === PESAN === */
.message {
  margin-top: 1rem;
  text-align: center;
  font-size: 0.9rem;
}
.message.success { color: #10b981; }
.message.error { color: #f87171; }

/* === LINK === */
.back-link {
  display: block;
  margin-top: 1.2rem;
  text-align: center;
  color: #a78bfa;
  font-size: 0.9rem;
  transition: color 0.2s;
}
.back-link:hover { color: #c4b5fd; text-decoration: underline; }

/* === ANIMASI === */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>

