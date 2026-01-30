<template>
  <div class="profile-dark">
    <div class="profile-card">
      <h2>Pengaturan Akun</h2>

      <div v-if="isLoading" class="loading-indicator">
        Memuat data pengguna...
      </div>

      <form v-else @submit.prevent="updateName">
        <div class="profile-item">
          <label for="name" class="label name-label">Nama:</label>
          <input
            id="name"
            v-model="userName"
            type="text"
            class="input-dark"
            placeholder="Masukkan nama baru"
            required
          />
        </div>

        <div class="profile-item">
          <label class="label email-label">Email:</label>
          <span class="value">{{ userEmail }}</span>
        </div>

        <button type="submit" class="btn-primary" :disabled="isUpdating">
          {{ isUpdating ? "Menyimpan..." : "Simpan Perubahan" }}
        </button>

        <router-link to="/dashboard" class="btn-secondary">
          Kembali ke Dashboard
        </router-link>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue"
import { useRouter } from "vue-router"
import axios from "axios"

const router = useRouter()
const userName = ref("")
const userEmail = ref("")
const isLoading = ref(true)
const isUpdating = ref(false)

const fetchUser = async () => {
  const token = localStorage.getItem("token")
  if (!token) {
    router.push("/login")
    return
  }

  try {
    const { data } = await axios.get("http://127.0.0.1:8000/api/dashboard", {
      headers: { Authorization: `Bearer ${token}` },
    })

    const user = data.user || data
    userName.value = user.name
    userEmail.value = user.email
  } catch (error) {
    console.error("Gagal memuat data:", error)
    alert("Sesi berakhir, silakan login ulang.")
    router.push("/login")
  } finally {
    isLoading.value = false
  }
}

const updateName = async () => {
  const token = localStorage.getItem("token")
  if (!token) return router.push("/login")

  isUpdating.value = true
  try {
    await axios.put(
      "http://127.0.0.1:8000/api/update-name",
      { name: userName.value },
      { headers: { Authorization: `Bearer ${token}` } }
    )

    alert("Nama berhasil diperbarui!")
  } catch (error) {
    console.error("Gagal memperbarui nama:", error)
    alert("Gagal memperbarui nama, coba lagi nanti.")
  } finally {
    isUpdating.value = false
  }
}

onMounted(fetchUser)
</script>

<style scoped>
.profile-dark {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  background: linear-gradient(180deg, #0b0b10, #111827);
  font-family: "Inter", sans-serif;
  color: #e6eef8;
}

.profile-card {
  background: rgba(255, 255, 255, 0.03);
  border: 1px solid rgba(255, 255, 255, 0.05);
  padding: 2rem;
  border-radius: 12px;
  width: 100%;
  max-width: 450px;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.6);
  backdrop-filter: blur(8px);
  animation: fadeIn 0.4s ease-out;
}

h2 {
  text-align: center;
  margin-bottom: 1.5rem;
  color: #c4b5fd;
  font-weight: 600;
}

.profile-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.25rem;
}

.label {
  font-weight: 600;
  margin-right: 1rem;
  min-width: 80px;
}

.name-label {
  color: #facc15;
}
.email-label {
  color: #3b82f6;
}

.input-dark {
  flex: 1;
  padding: 0.5rem 0.75rem;
  border-radius: 8px;
  border: 1px solid rgba(255, 255, 255, 0.1);
  background: rgba(255, 255, 255, 0.05);
  color: #fff;
  outline: none;
}
.input-dark:focus {
  border-color: #8b5cf6;
  box-shadow: 0 0 0 2px rgba(139, 92, 246, 0.3);
}

.value {
  color: #e6eef8;
  font-weight: 500;
}

.btn-primary {
  display: block;
  margin: 1.5rem auto 0.5rem;
  padding: 0.75rem;
  border: none;
  border-radius: 10px;
  background: #8b5cf6;
  color: #fff;
  font-size: 1rem;
  cursor: pointer;
  font-weight: 600;
  text-align: center;
  width: 100%;
}
.btn-primary:hover {
  background: #7c3aed;
}

.btn-secondary {
  display: block;
  text-align: center;
  margin-top: 0.5rem;
  color: #e6eef8;
  text-decoration: underline;
  font-weight: 500;
}

.loading-indicator {
  text-align: center;
  color: #8b5cf6;
  font-style: italic;
  padding: 1rem;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(8px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@media (max-width: 480px) {
  .profile-card {
    padding: 1.5rem;
    margin: 0 1rem;
  }
}
</style>

