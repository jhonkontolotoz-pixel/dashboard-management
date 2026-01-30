<template>
  <div class="about-dark">
    <!-- Salju -->
    <div class="snow"></div>

    <div class="about-card">
      <h2>Tentang Aplikasi</h2>
      <p class="desc">
        Ini adalah halaman About. kalau kamu bukan dari keluarga rudi ya yaudah.
      </p>

      <p class="desc">
        Selamat menjelajahi aplikasi dan jangan lupa gunakan insto supaya tidak sakit mata!
      </p>

      <router-link to="/login" class="btn-primary">Silahkan Masuk</router-link>
    </div>
  </div>
</template>

<script setup>
// Tidak ada script tambahan
</script>

<style scoped>
.about-dark {
  position: fixed;
  inset: 0;
  display: flex; justify-content: center; align-items: center;
  background: linear-gradient(180deg, #0b0b10, #111827);
  font-family: "Inter", sans-serif;
  color: #e6eef8;
  overflow: hidden;
  z-index: 0;
}

/* ==== SALJU HALUS ==== */
.snow {
  position: absolute; inset: 0;
  background-image:
    radial-gradient(2px 2px at 20px 30px, white, transparent),
    radial-gradient(3px 3px at 100px 150px, white, transparent),
    radial-gradient(2px 2px at 200px 80px, white, transparent);
  background-repeat: repeat;
  background-size: 600px 600px;
  animation: snowAnim 15s linear infinite;
  opacity: 0.6; pointer-events: none;
  z-index: 0;
}
@keyframes snowAnim {
  from { background-position:0 0,100px 200px,200px 400px; }
  to   { background-position:0 600px,100px 800px,200px 1000px; }
}

/* ==== CARD ==== */
.about-card {
  position: relative; z-index: 1;
  background: rgba(255,255,255,0.03);
  border:1px solid rgba(255,255,255,0.05);
  padding:2rem;
  border-radius:12px;
  width:100%; max-width:500px;
  box-shadow:0 8px 24px rgba(0,0,0,0.6);
  backdrop-filter: blur(8px);
  text-align:center;
  animation: fadeIn 0.4s ease-out;
}

/* ==== JUDUL & PARAGRAF GLOW ==== */
h2 {
  margin-bottom:1rem;
  color:#c4b5fd;
  font-weight:600;
  text-shadow: 0 0 10px rgba(139,92,246,0.5);
  animation: glow 2s infinite alternate;
}

.desc {
  margin-bottom:1rem;
  color:#cbd5e1;
  font-size:0.95rem;
  text-shadow: 0 0 6px rgba(139,92,246,0.3);
  animation: glow 3s infinite alternate;
}

/* ==== BUTTON ==== */
.btn-primary {
  display:inline-block;
  padding:0.75rem 1rem;
  border:none;
  border-radius:10px;
  background: linear-gradient(180deg, #8b5cf6, #6d28d9);
  color:#fff;
  font-size:1rem;
  cursor:pointer;
  font-weight:600;
  text-decoration:none;
  box-shadow:0 6px 20px rgba(124,58,237,0.25);
  transition: transform 0.1s ease, box-shadow 0.2s ease;
}
.btn-primary:hover {
  transform: translateY(-1px);
  box-shadow:0 8px 25px rgba(124,58,237,0.35);
}

/* ==== ANIMASI ==== */
@keyframes fadeIn {
  from { opacity:0; transform:translateY(8px); }
  to { opacity:1; transform:translateY(0); }
}
@keyframes glow {
  from { text-shadow: 0 0 6px rgba(139,92,246,0.3); }
  to   { text-shadow: 0 0 16px rgba(139,92,246,0.7); }
}

/* ==== RESPONSIF ==== */
@media (max-width:480px){
  .about-card { padding:1.5rem; margin:0 1rem; }
}
</style>
