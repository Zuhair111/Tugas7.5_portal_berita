# Portal Berita - Dokumentasi Lengkap

## Deskripsi Proyek
Aplikasi portal berita dengan backend Laravel dan aplikasi mobile Flutter yang terintegrasi penuh.

**Struktur Folder:**
- `projeklaravel1/` - Backend Laravel API
- `portal_berita_flutter/` - Aplikasi Flutter Mobile

---

## Requirements

### Backend (Laravel)
- PHP 8.3.28 atau lebih tinggi
- Composer
- SQLite (sudah include)
- Laravel 12.0
- Laravel Passport ^13.4

### Frontend (Flutter)
- Flutter 3.9.2 atau lebih tinggi
- Dart SDK
- Android Studio / VS Code
- Android Device atau Emulator (API 35 / Android 15)

### Server
- Laragon (untuk Windows)
- Apache Web Server
- WiFi Network (untuk testing di physical device)

---

## Instalasi Backend (Laravel)

### 1. Masuk ke folder Laravel
```powershell
cd c:\laragon\www\projekberita\projeklaravel1
```

### 2. Install Dependencies
```powershell
composer install
```

### 3. Setup Environment
File `.env` sudah tersedia, pastikan konfigurasi database:
```env
DB_CONNECTION=sqlite
DB_DATABASE=C:\laragon\www\projekberita\projeklaravel1\database\database.sqlite
```

### 4. Generate Application Key
```powershell
.\artisan.bat key:generate
```

### 5. Run Migrasi Database (Optional - Database sudah include)
Database SQLite sudah disertakan dengan sample data. Jika ingin reset database:
```powershell
# Backup database lama (optional)
Copy-Item database\database.sqlite database\database.backup.sqlite

# Reset database
.\artisan.bat migrate:fresh --seed
```

Jika database belum ada, jalankan:
```powershell
.\artisan.bat migrate
.\artisan.bat db:seed
```

### 6. Install Laravel Passport
```powershell
composer require laravel/passport --ignore-platform-req=ext-sodium
.\artisan.bat passport:install --force
```

### 7. Create Personal Access Client
```powershell
.\artisan.bat passport:client --personal
```
Jawab:
- Nama client: **Personal Access Client** (atau tekan Enter)

### 8. Seed Data (Optional)
Jika ingin mengisi data dummy:
```powershell
.\artisan.bat db:seed
```

### 9. Start Laravel Server
Pastikan Laragon Apache sudah running. API akan tersedia di:
```
http://localhost/projekberita/projeklaravel1/public/api/v1
```

### 10. Cek IP Address PC
Untuk akses dari device Android:
```powershell
ipconfig
```
Cari **IPv4 Address** pada adapter WiFi (contoh: `192.168.100.35`)

---

## Instalasi Frontend (Flutter)

### 1. Masuk ke folder Flutter
```powershell
cd c:\laragon\www\projekberita\portal_berita_flutter
```

### 2. Install Dependencies
```powershell
flutter pub get
```

### 3. Konfigurasi API URL
Edit file `lib/config/api_config.dart`:
```dart
class ApiConfig {
  // Pilih salah satu sesuai kebutuhan:
  
  // Untuk emulator Android
  static const String emulator = 'http://10.0.2.2/projekberita/projeklaravel1/public/api/v1';
  
  // Untuk physical device (ganti IP sesuai PC Anda)
  static const String physicalDevice = 'http://192.168.100.35/projekberita/projeklaravel1/public/api/v1';
  
  // Untuk localhost
  static const String localhost = 'http://localhost/projekberita/projeklaravel1/public/api/v1';
  
  // Aktif: pilih salah satu
  static const String baseUrl = physicalDevice; // Ubah sesuai kebutuhan
}
```

### 4. Cek Device Terhubung
**Untuk Emulator:**
```powershell
flutter emulators
flutter emulators --launch <emulator_id>
```

**Untuk Physical Device:**
- Aktifkan Developer Mode di Android
- Aktifkan USB Debugging
- Sambungkan via USB atau WiFi
- Pastikan PC dan device di jaringan WiFi yang sama

Cek device:
```powershell
flutter devices
```

### 5. Run Aplikasi
**Untuk Emulator:**
```powershell
flutter run
```

**Untuk Physical Device (contoh device ID: 24069PC21G):**
```powershell
flutter run -d 24069PC21G
```

---

## Struktur Database

Database SQLite memiliki **18 tabel**:

### Tabel Utama
1. `users` - Data pengguna (3 users)
2. `articles` - Data artikel berita (10 articles)
3. `categories` - Kategori berita (10 categories)
4. `comments` - Komentar artikel (2 comments)

### Tabel OAuth (Laravel Passport)
5. `oauth_access_tokens` - Token akses OAuth
6. `oauth_auth_codes` - Authorization codes
7. `oauth_clients` - OAuth clients
8. `oauth_device_codes` - Device codes
9. `oauth_refresh_tokens` - Refresh tokens

### Tabel Laravel
10. `personal_access_tokens` - Token Sanctum lama (tidak digunakan)
11. `password_reset_tokens` - Reset password
12. `sessions` - User sessions
13. `cache` - Application cache
14. `cache_locks` - Cache locks
15. `failed_jobs` - Failed queue jobs
16. `job_batches` - Job batches
17. `jobs` - Queue jobs
18. `migrations` - Migration history

---

## Fitur Aplikasi

### 1. Autentikasi
- **Register** - Daftar akun baru
- **Login** - Masuk dengan email & password
- **Logout** - Keluar dari aplikasi
- **Profile** - Lihat profil user

### 2. Berita
- **Home** - Tampilan berita utama dengan tab:
  - Berita Utama
  - Terkini
  - Populer
  - Rekomendasi
- **Detail Artikel** - Baca berita lengkap
- **Trending Topics** - Topik berita trending (bisa diklik)
- **Category** - Filter berita per kategori
- **Search** - Cari berita

### 3. Komentar
- **View Comments** - Lihat semua komentar artikel
- **Post Comment** - Tambah komentar baru (perlu login)
- **Delete Comment** - Hapus komentar sendiri (perlu login)
- **Auto-logout on 401** - Otomatis logout jika token expired

### 4. Cross-Platform
- Autentikasi shared antara web dan mobile
- Token OAuth2 menggunakan Laravel Passport
- Login di web bisa dipakai di mobile (vice versa)

---

## API Endpoints

### Authentication
```
POST   /api/v1/register           - Daftar akun baru
POST   /api/v1/login              - Login
POST   /api/v1/logout             - Logout (protected)
GET    /api/v1/user               - Get user profile (protected)
```

### News
```
GET    /api/v1/news               - List semua berita
GET    /api/v1/news/{slug}        - Detail berita
GET    /api/v1/news/category/{slug} - Berita per kategori
GET    /api/v1/news/search?q=...  - Cari berita
```

### Comments
```
GET    /api/v1/news/{slug}/comments - List komentar artikel
POST   /api/v1/news/{slug}/comments - Post komentar (protected)
DELETE /api/v1/comments/{id}         - Hapus komentar (protected)
```

### Categories
```
GET    /api/v1/categories         - List semua kategori
```

---

## Testing & Debugging

### Test API dengan cURL
```powershell
# Test endpoint tanpa auth
curl http://localhost/projekberita/projeklaravel1/public/api/v1/news

# Test login
curl -X POST http://localhost/projekberita/projeklaravel1/public/api/v1/login -H "Content-Type: application/json" -d "{\"email\":\"user@example.com\",\"password\":\"password\"}"
```

### Hot Reload Flutter
Saat aplikasi running, tekan `r` untuk hot reload atau `R` untuk hot restart:
```
r - Hot reload (cepat, preserve state)
R - Hot restart (reload penuh)
q - Quit aplikasi
```

### Flutter DevTools
URL DevTools akan muncul saat running app:
```
http://127.0.0.1:9101?uri=http://127.0.0.1:56367/...
```

### Check Database
```powershell
cd c:\laragon\www\projekberita\projeklaravel1
.\artisan.bat db:show --counts
```

---

## Troubleshooting

### 1. Error "Unauthenticated" setelah login
**Penyebab:** Token Sanctum lama tidak kompatibel dengan Passport

**Solusi:**
1. Logout dari aplikasi
2. Login kembali untuk mendapat token Passport baru
3. Token baru akan otomatis disimpan

### 2. Connection refused / API tidak bisa diakses
**Penyebab:** IP address berubah (DHCP)

**Solusi:**
1. Cek IP PC: `ipconfig`
2. Update `lib/config/api_config.dart`
3. Ganti `physicalDevice` dengan IP baru
4. Hot reload aplikasi (tekan `r`)

### 3. Card overflow / Layout error
**Solusi:** Sudah diperbaiki dengan padding optimal (8px) dan font size (12px/9px)

### 4. Navigator.pop null error
**Solusi:** Sudah ditambahkan pengecekan `if (mounted)` sebelum Navigator

### 5. PHP not found di PowerShell
**Solusi:** Gunakan `.\artisan.bat` instead of `php artisan`

### 6. Passport sodium extension
**Solusi:** Install dengan flag ignore:
```powershell
composer require laravel/passport --ignore-platform-req=ext-sodium
```

### 7. Device tidak terdeteksi
**Android (USB):**
- Cek USB Debugging aktif
- Reinstall ADB drivers
- Coba kabel USB lain

**Android (WiFi):**
- Pastikan di jaringan WiFi yang sama
- Cek firewall PC tidak block port

---

## Update IP Address (untuk Physical Device)

Jika IP PC berubah:

### 1. Cek IP baru
```powershell
ipconfig
```

### 2. Update Flutter config
Edit `portal_berita_flutter/lib/config/api_config.dart`:
```dart
static const String physicalDevice = 'http://IP_BARU/projekberita/projeklaravel1/public/api/v1';
```

### 3. Hot reload
Jika aplikasi sudah running, tekan `r` di terminal

Jika belum running:
```powershell
flutter run -d 24069PC21G
```

---

## Maintenance

### Clear Cache
```powershell
cd c:\laragon\www\projekberita\projeklaravel1
.\artisan.bat cache:clear
.\artisan.bat config:clear
.\artisan.bat route:clear
```

### Reset Database
```powershell
.\artisan.bat migrate:fresh --seed
.\artisan.bat passport:install --force
.\artisan.bat passport:client --personal
```

### Update Dependencies
**Laravel:**
```powershell
composer update
```

**Flutter:**
```powershell
flutter pub upgrade
```

---

## Production Build

### Build APK
```powershell
cd c:\laragon\www\projekberita\portal_berita_flutter
flutter build apk --release
```
Output: `build/app/outputs/flutter-apk/app-release.apk`

### Build App Bundle (untuk Play Store)
```powershell
flutter build appbundle --release
```
Output: `build/app/outputs/bundle/release/app-release.aab`

---

## Informasi Pengembang

**Dibuat:** November 30, 2025  
**Terakhir Update:** December 1, 2025  
**Laravel Version:** 12.0  
**Flutter Version:** 3.9.2  
**Authentication:** Laravel Passport OAuth2

---

## Catatan Penting

1. **Token Expiry:**
   - Access Token: 15 hari
   - Refresh Token: 30 hari
   - Personal Access Token: 6 bulan

2. **Network Requirements:**
   - PC dan device harus di WiFi yang sama
   - Firewall PC harus allow connection
   - Apache Laragon harus running

3. **Database:**
   - SQLite file: `projeklaravel1/database/database.sqlite`
   - Backup database sebelum migrate:fresh

4. **Security:**
   - Jangan commit file `.env`
   - Jangan expose encryption keys
   - Ganti APP_KEY di production

---

## Quick Start (TL;DR)

```powershell
# 1. Start Laragon Apache

# 2. Backend (database sudah include, tinggal install Passport)
cd c:\laragon\www\projekberita\projeklaravel1
composer install
.\artisan.bat passport:install --force

# 3. Frontend
cd c:\laragon\www\projekberita\portal_berita_flutter
flutter pub get

# 4. Update IP di lib/config/api_config.dart

# 5. Run
flutter run -d DEVICE_ID
```

**Database sudah include dengan sample data:**
- 3 users (email: user@example.com, password: password)
- 10 artikel
- 10 kategori
- 2 komentar

**Untuk reset database (optional):**
```powershell
cd projeklaravel1
.\artisan.bat migrate:fresh --seed
```

**Login Credentials (default):**
```
Email: user@example.com
Password: password
```

---

## Support

Untuk masalah atau pertanyaan, cek section **Troubleshooting** di atas atau review kode di:
- Backend: `projeklaravel1/app/Http/Controllers/Api/`
- Frontend: `portal_berita_flutter/lib/`
