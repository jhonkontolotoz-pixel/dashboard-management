<template>
  <div class="forgot-anime">
    <div class="snow"></div>
    <div class="forgot-card">
      <h2 class="title">Lupa Password</h2>
      <p class="desc">Masukkan email kamu untuk mendapatkan OTP reset.</p>

      <form @submit.prevent="sendOTP">
        <input
          type="email"
          v-model="email"
          placeholder="Email"
          class="input-dark"
          required
        />
        <button type="submit" class="btn-primary" :disabled="loading">
          {{ loading ? "Mengirim..." : "Kirim OTP" }}
        </button>
      </form>

      <div class="links">
        <router-link to="/login" class="back-link">Kembali ke Login</router-link>
      </div>

      <p v-if="message" class="success">{{ message }}</p>
      <p v-if="error" class="error">{{ error }}</p>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";

const router = useRouter();

const email = ref("");
const message = ref("");
const error = ref("");
const loading = ref(false);

const sendOTP = async () => {
  message.value = "";
  error.value = "";
  loading.value = true;

  try {
    const res = await axios.post("http://127.0.0.1:8000/api/forgot-password", {
      email: email.value,
    });

    message.value = res.data.message;
    router.push({ path: "/verify-otp", query: { email: email.value } });
  } catch (err) {
    error.value = err.response?.data?.message || "Gagal mengirim OTP. Cek koneksi kamu.";
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
/* === BACKGROUND DARK ANIME === */
.forgot-anime {
  position: fixed;
  inset: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  background: radial-gradient(circle at 20% 20%, #111827, #0b0b12 70%);
  font-family: 'Poppins', sans-serif;
  color: #e6eef8;
  overflow: hidden;
}

/* === SALJU === */
.snow {
  position: absolute;
  top: 0; left: 0;
  width: 100%; height: 100%;
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
  from { background-position: 0 0, 100px 200px, 200px 400px, 300px 100px, 400px 300px, 500px 500px; }
  to { background-position: 0 600px, 100px 800px, 200px 1000px, 300px 700px, 400px 900px, 500px 1100px; }
}

/* === CAHAYA GLOW === */
.forgot-anime::before {
  content: '';
  position: absolute;
  width: 140%; height: 140%;
  background: radial-gradient(circle, rgba(139,92,246,0.2) 0%, transparent 70%),
              radial-gradient(circle at 80% 20%, rgba(56,189,248,0.15) 0%, transparent 60%);
  filter: blur(80px);
  animation: floatGlow 6s ease-in-out infinite alternate;
  z-index: 0;
}

@keyframes floatGlow {
  0% { transform: translateY(0); opacity: 0.8; }
  100% { transform: translateY(-30px); opacity: 1; }
}

/* === CARD === */
.forgot-card {
  position: relative;
  z-index: 2;
  background: rgba(20, 20, 35, 0.85);
  padding: 2.5rem;
  border-radius: 16px;
  border: 1px solid rgba(139,92,246,0.3);
  box-shadow: 0 0 25px rgba(124,58,237,0.35), 0 0 60px rgba(56,189,248,0.15);
  backdrop-filter: blur(10px);
  width: 100%;
  max-width: 420px;
  animation: fadeIn 0.5s ease-out;
  text-align: center;
}

/* === TITLE === */
.title {
  margin-bottom: 1rem;
  font-size: 1.5rem;
  font-weight: 600;
  background: linear-gradient(to right, #a78bfa, #38bdf8);
  color: transparent;
  background-clip: text;
}

::v-deep(.title) {
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.desc { margin-bottom: 1.5rem; color: #cbd5e1; }

/* === INPUT === */
.input-dark {
  width: 100%;
  padding: 0.75rem 1rem;
  margin-top: 1rem;
  border-radius: 10px;
  border: 1px solid rgba(255,255,255,0.1);
  background: rgba(255,255,255,0.05);
  color: #fff;
  outline: none;
  transition: 0.3s;
}

.input-dark:focus {
  border-color: #8b5cf6;
  box-shadow: 0 0 10px rgba(139,92,246,0.4);
}

/* === BUTTON === */
.btn-primary {
  width: 100%;
  margin-top: 1rem;
  padding: 0.75rem;
  border-radius: 12px;
  background: linear-gradient(90deg, #8b5cf6, #3b82f6);
  color: #fff;
  font-weight: 600;
  font-size: 1rem;
  cursor: pointer;
  box-shadow: 0 0 20px rgba(139,92,246,0.25);
  transition: transform 0.15s ease, box-shadow 0.3s ease;
}

.btn-primary:hover:enabled {
  transform: translateY(-2px);
  box-shadow: 0 0 25px rgba(139,92,246,0.45);
}

/* === LINK === */
.links { margin-top: 1rem; text-align: left; }
.back-link { color: #a5b4fc; text-decoration: underline; font-size: 0.9rem; }

/* === PESAN === */
.success { margin-top: 1rem; color: #10b981; font-size: 0.9rem; }
.error { margin-top: 1rem; color: #ef4444; font-size: 0.9rem; }

/* === ANIMASI MASUK === */
@keyframes fadeIn { from { opacity:0; transform: translateY(10px); } to { opacity:1; transform: translateY(0); } }

/* === RESPONSIF === */
@media (max-width: 480px) {
  .forgot-card { padding: 1.5rem; margin: 0 1rem; }
}
</style>






