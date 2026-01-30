<template>
  <div class="dashboard-anime">
    <div class="snow"></div>
    <div class="dashboard-card">
      <h2 class="title">Dashboard</h2>

      <!-- aman dari error postcss -->
      <p v-if="user && user.name">Selamat datang, {{ user.name }}!</p>
      <p v-else>Selamat datang, Pengguna!</p>

      <div class="button-group">
        <button @click="goTo('/profile')" class="btn-primary">
          Profil
        </button>

        <button @click="goTo('/settings')" class="btn-primary">
          Settings
        </button>

        <!-- Tombol khusus untuk USER biasa -->
        <button
          v-if="user && user.role === 'user'"
          @click="goTo('/tasks')"
          class="btn-primary"
        >
          Task
        </button>

        <!-- Tombol khusus untuk ADMIN -->
        <button
          v-if="user && user.role === 'admin'"
          @click="goTo('/role-user')"
          class="btn-primary"
        >
          Role User
        </button>
      </div>

      <button @click="logout" class="btn-logout">
        Logout
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const user = ref(null);

onMounted(() => {
  const userData = localStorage.getItem('user');
  if (userData) {
    user.value = JSON.parse(userData);
  } else {
    router.push('/login');
  }
});

const goTo = (path) => {
  router.push(path);
};

const logout = () => {
  localStorage.removeItem('token');
  localStorage.removeItem('user');
  router.push('/login');
};
</script>

<style scoped>
/* === BACKGROUND DARK ANIME === */
.dashboard-anime {
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
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
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
  from {
    background-position: 0 0, 100px 200px, 200px 400px, 300px 100px, 400px 300px, 500px 500px;
  }
  to {
    background-position: 0 600px, 100px 800px, 200px 1000px, 300px 700px, 400px 900px, 500px 1100px;
  }
}

/* === GLOW === */
.dashboard-anime::before {
  content: '';
  position: absolute;
  width: 140%;
  height: 140%;
  background: radial-gradient(circle, rgba(139, 92, 246, 0.2) 0%, transparent 70%),
              radial-gradient(circle at 80% 20%, rgba(56, 189, 248, 0.15) 0%, transparent 60%);
  filter: blur(80px);
  animation: floatGlow 6s ease-in-out infinite alternate;
  z-index: 0;
}

@keyframes floatGlow {
  0% { transform: translateY(0); opacity: 0.8; }
  100% { transform: translateY(-30px); opacity: 1; }
}

/* === CARD === */
.dashboard-card {
  position: relative;
  z-index: 2;
  background: rgba(20, 20, 35, 0.85);
  padding: 2.5rem;
  border-radius: 16px;
  border: 1px solid rgba(139, 92, 246, 0.3);
  box-shadow: 0 0 25px rgba(124, 58, 237, 0.35), 0 0 60px rgba(56, 189, 248, 0.15);
  backdrop-filter: blur(10px);
  width: 100%;
  max-width: 450px;
  animation: fadeIn 0.5s ease-out;
  text-align: center;
}

/* === TITLE === */
.title {
  margin-bottom: 1rem;
  font-size: 1.8rem;
  font-weight: 600;
  background: linear-gradient(to right, #a78bfa, #38bdf8);
  color: transparent;
  background-clip: text;
}

p {
  margin-bottom: 1.5rem;
  color: #cbd5e1;
}

/* === BUTTON === */
.button-group {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  margin-bottom: 1.5rem;
}

.btn-primary {
  padding: 0.75rem;
  border: none;
  border-radius: 12px;
  background: linear-gradient(90deg, #8b5cf6, #3b82f6);
  color: #fff;
  font-size: 1rem;
  cursor: pointer;
  font-weight: 600;
  transition: transform 0.15s ease, box-shadow 0.3s ease;
  box-shadow: 0 0 20px rgba(139, 92, 246, 0.25);
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 0 25px rgba(139, 92, 246, 0.45);
}

.btn-logout {
  padding: 0.75rem;
  border: none;
  border-radius: 12px;
  background: #c026d3;
  color: #fff;
  font-size: 1rem;
  cursor: pointer;
  font-weight: 600;
  width: 100%;
  box-shadow: 0 0 15px rgba(192, 38, 211, 0.3);
  transition: background 0.2s ease, transform 0.15s ease;
}

.btn-logout:hover {
  background: #a21caf;
  transform: translateY(-1px);
}

/* === ANIMASI === */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 480px) {
  .dashboard-card {
    padding: 1.5rem;
    margin: 0 1rem;
  }
}
</style>





