<template>
  <div class="profile-dark">
    <div class="profile-card">
      <h2>Verifikasi OTP</h2>
      <p class="desc">Masukkan OTP yang dikirim ke email kamu.</p>

      <form @submit.prevent="verifyOTP">
        <input
          type="text"
          v-model="otp"
          placeholder="Kode OTP"
          class="input-dark"
          maxlength="6"
          required
        />
        <button type="submit" class="btn-primary" :disabled="loading">
          {{ loading ? "Memverifikasi..." : "Verifikasi OTP" }}
        </button>
      </form>

      <div class="links">
        <router-link to="/forgot-password" class="back-link">Kembali ke Lupa Password</router-link>
      </div>

      <p v-if="message" class="success">{{ message }}</p>
      <p v-if="error" class="error">{{ error }}</p>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter, useRoute } from "vue-router";
import axios from "axios";

const route = useRoute();
const router = useRouter();
const otp = ref("");
const message = ref("");
const error = ref("");
const loading = ref(false);

// Ambil email dari query string
const email = route.query.email || "";

const verifyOTP = async () => {
  message.value = "";
  error.value = "";
  loading.value = true;

  try {
    const res = await axios.post("http://127.0.0.1:8000/api/verify-otp", {
      email,
      otp: otp.value,
    });
    message.value = res.data.message;

    // Jika OTP valid, lanjut ke halaman reset password
    router.push({ path: "/reset-password", query: { email } });

  } catch (err) {
    if (err.response?.data?.message) {
      error.value = err.response.data.message;
    } else {
      error.value = "Gagal memverifikasi OTP. Cek koneksi.";
    }
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.profile-dark {
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100vh;
  display: flex; justify-content: center; align-items: center;
  background: linear-gradient(180deg, #0b0b10, #111827);
  font-family: "Inter", sans-serif;
  color: #e6eef8;
}
.profile-card {
  background: rgba(255, 255, 255, 0.03);
  border: 1px solid rgba(255, 255, 255, 0.05);
  padding: 2rem;
  border-radius: 12px;
  width: 100%; max-width: 450px;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.6);
  backdrop-filter: blur(8px);
  text-align: center;
  animation: fadeIn 0.4s ease-out;
}
.input-dark {
  width: 100%;
  padding: 0.75rem;
  margin-top: 1rem;
  border: none;
  border-radius: 10px;
  background: rgba(255, 255, 255, 0.05);
  color: #fff;
}
.btn-primary {
  width: 100%;
  margin-top: 1rem;
  padding: 0.75rem;
  border-radius: 10px;
  background: #8b5cf6;
  color: #fff;
  font-weight: 600;
}
.btn-primary:hover { background: #7c3aed; }

.links {
  margin-top: 1rem;
  display: flex;
  justify-content: flex-start;
}
.back-link {
  color: #a5b4fc;
  text-decoration: underline;
  font-size: 0.9rem;
}
.success { margin-top: 1rem; color: #10b981; }
.error { margin-top: 1rem; color: #ef4444; }

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(8px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
