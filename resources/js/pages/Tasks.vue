<template>
  <div class="tasks-anime">
    <div class="snow"></div>

    <div class="tasks-card">
      <h2 class="title">📋 Daftar Tugas</h2>
      <p>Tulis dan kelola tugas-tugasmu hari ini!</p>

      <!-- Input tambah tugas -->
      <div class="task-input">
        <input
          v-model="newTask"
          @keyup.enter="addTask"
          type="text"
          placeholder="Tulis tugas baru dan tekan Enter..."
        />
        <button class="btn-add" @click="addTask">Tambah</button>
      </div>

      <!-- Daftar tugas -->
      <ul class="task-list">
        <li
          v-for="(task, index) in tasks"
          :key="index"
          class="task-item"
          :class="{ done: task.done }"
        >
          <div class="task-left" @click="toggleTask(task)">
            <input type="checkbox" v-model="task.done" />
            <span>{{ task.title }}</span>
          </div>

          <button class="btn-delete" @click="deleteTask(index)">Hapus</button>
        </li>
      </ul>

      <button class="btn-back" @click="goBack">Kembali ke Dashboard</button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const tasks = ref([]);
const newTask = ref('');

// Ambil dari localStorage saat pertama kali masuk
onMounted(() => {
  const saved = localStorage.getItem('tasks');
  if (saved) {
    tasks.value = JSON.parse(saved);
  }
});

// Simpan otomatis setiap kali tasks berubah
watch(tasks, (newVal) => {
  localStorage.setItem('tasks', JSON.stringify(newVal));
}, { deep: true });

// Tambah tugas baru
const addTask = () => {
  if (!newTask.value.trim()) return;
  tasks.value.push({ title: newTask.value, done: false });
  newTask.value = '';
};

// Toggle status selesai
const toggleTask = (task) => {
  task.done = !task.done;
};

// Hapus tugas
const deleteTask = (index) => {
  tasks.value.splice(index, 1);
};

// Kembali ke dashboard
const goBack = () => router.push('/dashboard');
</script>

<style scoped>
/* === BACKGROUND DARK ANIME === */
.tasks-anime {
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

/* === SALJU HALUS === */
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
.tasks-card {
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
  max-width: 480px;
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

/* === INPUT AREA === */
.task-input {
  display: flex;
  gap: 0.5rem;
  margin: 1rem 0 1.5rem;
}

.task-input input {
  flex: 1;
  padding: 0.7rem 1rem;
  border-radius: 10px;
  border: 1px solid rgba(139, 92, 246, 0.4);
  background: rgba(255, 255, 255, 0.05);
  color: #fff;
  outline: none;
  transition: 0.3s;
}
.task-input input:focus {
  border-color: #8b5cf6;
  box-shadow: 0 0 12px rgba(139, 92, 246, 0.3);
}
.btn-add {
  background: linear-gradient(90deg, #8b5cf6, #3b82f6);
  border: none;
  padding: 0.7rem 1rem;
  border-radius: 10px;
  color: white;
  font-weight: 600;
  cursor: pointer;
  box-shadow: 0 0 12px rgba(139, 92, 246, 0.25);
  transition: 0.2s;
}
.btn-add:hover {
  transform: translateY(-2px);
  box-shadow: 0 0 20px rgba(139, 92, 246, 0.4);
}

/* === TASK LIST === */
.task-list {
  list-style: none;
  padding: 0;
  margin: 1.5rem 0;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}
.task-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: rgba(30, 30, 50, 0.7);
  padding: 0.75rem 1rem;
  border-radius: 12px;
  border: 1px solid rgba(139, 92, 246, 0.3);
  color: #e5e7eb;
  box-shadow: 0 0 10px rgba(124, 58, 237, 0.25);
  transition: transform 0.2s ease, box-shadow 0.3s ease;
}
.task-item:hover {
  transform: translateY(-2px);
  box-shadow: 0 0 18px rgba(139, 92, 246, 0.4);
}

/* === TASK CONTENT === */
.task-left {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  cursor: pointer;
}
.task-item.done span {
  text-decoration: line-through;
  color: #9ca3af;
}

/* === DELETE BUTTON === */
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

/* === BACK BUTTON === */
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
</style>



