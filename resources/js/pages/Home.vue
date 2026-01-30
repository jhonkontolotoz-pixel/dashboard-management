<template>
  <div class="home-dark">
    <!-- Efek salju -->
    <div class="snow"></div>

    <div class="container">
      <!-- Form area hiasan di kiri -->
      <div class="form-area">
        <div class="home-card">
          <h2>Home</h2>
          <p class="about-text">
            Tentang Kami: Aplikasi ini menampilkan beberapa fitur yang hanya bisa diakses oleh keluarga rudi.
          </p>
          <button class="btn-primary" @click="goAbout">Tentang Kami</button>
        </div>
      </div>

      <!-- Image slider -->
      <div class="image-slider">
        <transition name="fade" mode="out-in">
          <img
            :src="currentImage"
            :key="currentImage"
            class="slider-img"
            @click="nextImage"
          />
        </transition>
        <p class="slider-text">Klik gambar untuk ganti</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

const images = [
  '/images/yoi.jpeg',
  '/images/bahlil.jpg',
  '/images/rud.jpg'
];

const currentIndex = ref(0);
const currentImage = ref(images[currentIndex.value]);

const nextImage = () => {
  currentIndex.value = (currentIndex.value + 1) % images.length;
  currentImage.value = images[currentIndex.value];
};

const goAbout = () => router.push('/about');
</script>

<style scoped>
.home-dark {
  position: fixed; inset:0;
  display: flex; justify-content: center; align-items: center;
  background: linear-gradient(180deg, #0b0b10, #111827);
  font-family: 'Inter', sans-serif; color:#e6eef8; overflow:hidden;
}

/* SALJU HALUS */
.snow {
  position:absolute; top:0; left:0; width:100%; height:100%;
  background-image:
    radial-gradient(2px 2px at 20px 30px, white, transparent),
    radial-gradient(3px 3px at 100px 150px, white, transparent),
    radial-gradient(2px 2px at 200px 80px, white, transparent);
  background-repeat: repeat;
  background-size: 600px 600px;
  animation: snowAnim 15s linear infinite;
  opacity:0.6; pointer-events:none; z-index:0;
}
@keyframes snowAnim {
  from { background-position:0 0,100px 200px,200px 400px; }
  to { background-position:0 600px,100px 800px,200px 1000px; }
}

/* LAYOUT FLEX */
.container {
  display:flex;
  gap: 2rem;
  z-index:2;
  align-items: center;
}

/* FORM AREA DI KIRI SEBAGAI HIASAN */
.form-area {
  display:flex; flex-direction: column;
  gap:1rem;
}

.home-card {
  background: rgba(255,255,255,0.03);
  border:1px solid rgba(255,255,255,0.05);
  padding:2rem; border-radius:12px;
  max-width:300px;
  backdrop-filter: blur(8px);
  box-shadow:0 8px 24px rgba(0,0,0,0.6);
  animation: fadeIn 0.4s ease-out;
  text-align: center;
}

h2 { color:#c4b5fd; font-weight:600; text-shadow:0 0 10px rgba(139,92,246,0.5); margin-bottom:1rem; }
.about-text { margin-bottom:1rem; font-size:0.9rem; color:#cbd5e1; }

/* TOMBOL HIASAN MENUJU /ABOUT */
.btn-primary {
  width:100%; padding:0.75rem; border-radius:12px;
  background: linear-gradient(90deg,#8b5cf6,#3b82f6); color:#fff; font-weight:600;
  cursor:pointer; box-shadow:0 0 20px rgba(139,92,246,0.25); transition: transform 0.15s ease, box-shadow 0.3s ease;
}
.btn-primary:hover { transform: translateY(-2px); box-shadow:0 0 25px rgba(139,92,246,0.45); }

/* IMAGE SLIDER DI KANAN */
.image-slider { display:flex; flex-direction:column; align-items:center; gap:0.5rem; }
.slider-img { width:250px; border-radius:12px; cursor:pointer; box-shadow:0 0 20px rgba(124,58,237,0.4); transition: transform 0.3s; }
.slider-img:hover { transform: scale(1.05); }
.slider-text { font-size:0.9rem; color:#a5b4fc; }

.fade-enter-active, .fade-leave-active { transition: opacity 0.5s; }
.fade-enter-from, .fade-leave-to { opacity:0; }

@keyframes fadeIn { from{opacity:0;transform:translateY(8px);} to{opacity:1;transform:translateY(0);} }

@media(max-width:768px){
  .container { flex-direction: column; gap:1.5rem; }
  .slider-img { width:180px; }
  .home-card { max-width:90%; }
}
</style>



