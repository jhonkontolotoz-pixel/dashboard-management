# Dashboard Management System (Laravel + Vue SPA)

Sistem Dashboard Management yang dibangun dengan Laravel (sebagai API) dan Vue.js. Proyek ini memiliki alur keamanan ketat yang mencakup verifikasi email dan autentikasi dua faktor (OTP/Password).

## 🚀 Fitur Utama

* **Autentikasi API-Based:** Menggunakan Route API dan Controller API untuk proses login/register.
* **Sistem Verifikasi Email:** Pengguna yang baru mendaftar wajib melakukan verifikasi email. Jika belum diverifikasi, akses login akan ditolak.
* **Dual Login Method:** Mendukung login menggunakan password standar atau kode OTP.
* **Role Management:** * **Register:** Secara default, semua pengguna baru akan mendapatkan role `User`.
    * **Dashboard:** Manajemen akses berdasarkan role masing-masing untuk fitur-fitur tertentu.
* **Frontend SPA:** Dibangun menggunakan Vue.js untuk pengalaman pengguna yang responsif.

## 🛠️ Persyaratan Sistem

* PHP >= 8.x
* Composer
* Node.js & NPM
* MySQL/PostgreSQL
* Mail Server (Mailtrap/SMTP/Gmail) untuk pengiriman OTP & Verifikasi Email.

## 📥 Instalasi

1. **Clone Repositori**
   ```bash
   git clone [https://github.com/jhonkontolotoz-pixel/dashboard-management.git](https://github.com/jhonkontolotoz-pixel/dashboard-management.git)
   cd dashboard-management
   composer install
   npm install
