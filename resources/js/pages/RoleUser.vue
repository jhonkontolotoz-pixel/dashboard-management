<template>
  <div class="roleuser-anime">
    <div class="snow"></div>

    <div class="roleuser-card">
      <h2 class="title">👑 Manajemen User</h2>
      <p>Hanya admin yang dapat mengelola data user</p>

      <table class="user-table">
        <thead>
          <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id">
            <td>{{ user.name }}</td>
            <td>{{ user.email }}</td>
            <td>
              <span class="role-badge" :class="user.role">{{ user.role }}</span>
            </td>
            <td>
              <button @click="deleteUser(user.id)" class="btn-delete">Hapus</button>
            </td>
          </tr>
        </tbody>
      </table>

      <button class="btn-back" @click="goBack">Kembali ke Dashboard</button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();
const users = ref([]);

onMounted(async () => {
  const token = localStorage.getItem('token');
  if (!token) {
    router.push('/login');
    return;
  }

  try {
    const res = await axios.get('http://127.0.0.1:8000/api/users', {
      headers: { Authorization: `Bearer ${token}` },
    });
    users.value = res.data.users;
  } catch (error) {
    console.error(error);
  }
});

const deleteUser = async (id) => {
  const token = localStorage.getItem('token');
  if (confirm('Yakin ingin menghapus user ini?')) {
    try {
      await axios.delete(`http://127.0.0.1:8000/api/users/${id}`, {
        headers: { Authorization: `Bearer ${token}` },
      });
      users.value = users.value.filter((u) => u.id !== id);
      alert('User berhasil dihapus.');
    } catch (error) {
      console.error(error);
      alert('Gagal menghapus user.');
    }
  }
};

const goBack = () => router.push('/dashboard');
</script>

<style scoped>
/* === BACKGROUND DARK ANIME === */
.roleuser-anime {
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
  z-index: 0;
}

@keyframes snowAnim {
  from { background-position: 0 0; }
  to { background-position: 0 600px; }
}

/* === CARD === */
.roleuser-card {
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
  max-width: 700px;
  text-align: center;
  animation: fadeIn 0.5s ease-out;
}

/* === TITLE === */
.title {
  margin-bottom: 1rem;
  font-size: 1.8rem;
  font-weight: 600;
  background: linear-gradient(to right, #a78bfa, #38bdf8);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

/* === TABLE === */
.user-table {
  width: 100%;
  border-collapse: collapse;
  margin: 1.5rem 0;
  color: #e2e8f0;
  background: rgba(255, 255, 255, 0.03);
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 0 15px rgba(124, 58, 237, 0.25);
}

.user-table th,
.user-table td {
  padding: 0.75rem 1rem;
  border-bottom: 1px solid rgba(139, 92, 246, 0.15);
  text-align: left;
}

.user-table th {
  color: #a78bfa;
  font-weight: 600;
  background: rgba(30, 30, 50, 0.7);
}

.user-table tr:hover td {
  background: rgba(139, 92, 246, 0.08);
  transition: background 0.2s ease;
}

/* === ROLE BADGE === */
.role-badge {
  padding: 4px 10px;
  border-radius: 10px;
  font-weight: 600;
  font-size: 0.85rem;
  text-transform: capitalize;
  box-shadow: 0 0 8px rgba(139, 92, 246, 0.3);
}
.role-badge.admin { background: #dc2626; color: #fff; }
.role-badge.user { background: #16a34a; color: #fff; }

/* === BUTTON === */
.btn-delete {
  background: linear-gradient(90deg, #ef4444, #dc2626);
  border: none;
  padding: 0.4rem 0.8rem;
  border-radius: 8px;
  color: white;
  font-weight: 600;
  cursor: pointer;
  box-shadow: 0 0 12px rgba(239, 68, 68, 0.4);
  transition: transform 0.2s ease, box-shadow 0.3s ease;
}
.btn-delete:hover {
  transform: translateY(-2px);
  box-shadow: 0 0 16px rgba(239, 68, 68, 0.6);
}

/* === BUTTON BACK === */
.btn-back {
  width: 100%;
  padding: 0.8rem;
  border: none;
  border-radius: 12px;
  background: linear-gradient(90deg, #8b5cf6, #3b82f6);
  color: #fff;
  font-size: 1rem;
  font-weight: 600;
  margin-top: 1rem;
  cursor: pointer;
  box-shadow: 0 0 20px rgba(139, 92, 246, 0.25);
  transition: transform 0.15s ease, box-shadow 0.3s ease;
}
.btn-back:hover {
  transform: translateY(-2px);
  box-shadow: 0 0 25px rgba(139, 92, 246, 0.45);
}

/* === ANIMASI MASUK === */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

/* === RESPONSIF === */
@media (max-width: 600px) {
  .roleuser-card { padding: 1.5rem; margin: 0 1rem; }
  .user-table th, .user-table td { font-size: 0.85rem; }
}
</style>


