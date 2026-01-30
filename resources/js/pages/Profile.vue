<template>
  <div class="profile-dark">
    <div class="profile-card">
      <h2>Profil Pengguna</h2>

      <div v-if="isLoading" class="loading-indicator">
        Memuat data profil...
      </div>

      <div v-else>
        <div class="profile-item">
          <span class="label name-label">Nama:</span>
          <span class="value">{{ userProfile.name || '-' }}</span>
        </div>

        <div class="profile-item">
          <span class="label email-label">Email:</span>
          <span class="value">{{ userProfile.email || '-' }}</span>
        </div>

        <div class="profile-item">
          <span class="label role-label">Role:</span>

          <!-- Kalau user admin, bisa ubah role -->
          <template v-if="isAdmin">
            <select v-model="userProfile.role" class="role-select" @change="updateRole">
              <option value="admin">Admin</option>
              <option value="user">User</option>
            </select>
          </template>

          <!-- Kalau user biasa, cuma bisa lihat -->
          <template v-else>
            <span
              class="role-badge"
              :class="{
                admin: userProfile.role === 'admin',
                user: userProfile.role === 'user'
              }"
            >
              {{ userProfile.role || '-' }}
            </span>
          </template>
        </div>

        <div class="profile-item">
          <span class="label date-label">Terdaftar Sejak:</span>
          <span class="value">{{ userProfile.memberSince || '-' }}</span>
        </div>

        <router-link to="/dashboard" class="btn-primary">Kembali ke Dashboard</router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const isLoading = ref(true);
const userProfile = ref({ name: '', email: '', role: '', memberSince: '' });
const isAdmin = ref(false);

const token = localStorage.getItem('token');
const localUser = JSON.parse(localStorage.getItem('user') || '{}');

if (!token) router.push('/login');
if (localUser.role === 'admin') isAdmin.value = true;

const fetchProfileData = async () => {
  try {
    const { data } = await axios.get('http://127.0.0.1:8000/api/dashboard', {
      headers: { Authorization: `Bearer ${token}` },
    });

    const user = data.user || data;
    userProfile.value = {
      id: user.id,
      name: user.name,
      email: user.email,
      role: user.role || 'user',
      memberSince: new Date(user.created_at).toLocaleDateString('id-ID'),
    };
  } catch (error) {
    console.error('Gagal mengambil data profil:', error);
    alert('Sesi Anda berakhir, silakan login kembali.');
    localStorage.removeItem('token');
    router.push('/login');
  } finally {
    isLoading.value = false;
  }
};

const updateRole = async () => {
  if (!isAdmin.value) return alert('Kamu tidak memiliki izin untuk mengubah role.');

  try {
    await axios.put(
      `http://127.0.0.1:8000/api/users/${userProfile.value.id}/role`,
      { role: userProfile.value.role },
      { headers: { Authorization: `Bearer ${token}` } }
    );
    alert('Role berhasil diperbarui!');
  } catch (error) {
    console.error(error);
    alert('Gagal memperbarui role.');
  }
};

onMounted(fetchProfileData);
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
  background: radial-gradient(circle at 20% 20%, #111827, #0b0b12 70%);
  font-family: "Inter", sans-serif;
  color: #e6eef8;
}

/* CARD */
.profile-card {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(139, 92, 246, 0.3);
  padding: 2rem;
  border-radius: 14px;
  width: 100%;
  max-width: 450px;
  box-shadow: 0 0 25px rgba(124, 58, 237, 0.35);
  backdrop-filter: blur(10px);
  animation: fadeIn 0.4s ease-out;
}

h2 {
  text-align: center;
  margin-bottom: 1.5rem;
  background: linear-gradient(to right, #a78bfa, #38bdf8);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  font-weight: 600;
}

/* ITEM */
.profile-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.label {
  font-weight: 600;
}

.name-label { color: #facc15; }
.email-label { color: #3b82f6; }
.date-label { color: #10b981; }
.role-label { color: #8b5cf6; }

.value { color: #e6eef8; font-weight: 500; }

/* ROLE BADGE & SELECT */
.role-badge {
  color: #fff;
  padding: 4px 10px;
  border-radius: 12px;
  font-size: 0.85rem;
  font-weight: 600;
}
.role-badge.admin { background-color: #dc2626; }
.role-badge.user { background-color: #16a34a; }

.role-select {
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(139, 92, 246, 0.3);
  color: #fff;
  border-radius: 8px;
  padding: 5px 8px;
  font-weight: 600;
  cursor: pointer;
}

/* BUTTON */
.btn-primary {
  display: block;
  margin: 1.5rem auto 0;
  padding: 0.75rem;
  border: none;
  border-radius: 10px;
  background: linear-gradient(90deg, #8b5cf6, #3b82f6);
  color: #fff;
  font-size: 1rem;
  cursor: pointer;
  font-weight: 600;
  text-align: center;
  width: 100%;
  box-shadow: 0 0 20px rgba(139, 92, 246, 0.25);
}
.btn-primary:hover { transform: translateY(-1px); background: #7c3aed; }

/* LOADING */
.loading-indicator {
  text-align: center;
  color: #8b5cf6;
  font-style: italic;
  padding: 1rem;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(8px); }
  to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 480px) {
  .profile-card { padding: 1.5rem; margin: 0 1rem; }
}
</style>


