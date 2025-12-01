# Portal Berita Flutter

Aplikasi berita Flutter yang terintegrasi dengan Laravel API.

## ✨ Fitur Utama

- ✅ **Autentikasi** - Login, Register, Logout dengan Laravel Sanctum
- ✅ **Cross-Platform Auth** - Register di web PHP bisa login di Flutter, dan sebaliknya
- ✅ **Komentar** - Lihat, tambah, dan hapus komentar di artikel
- ✅ **Berita** - Homepage dengan featured, trending, dan latest articles
- ✅ **Detail Artikel** - Tampilan lengkap artikel dengan komentar & related articles
- ✅ **Profil** - Manajemen user profile dan logout

## 🚀 Quick Start

### Menggunakan Script Otomatis (Recommended)

```powershell
# Jalankan script quick start
.\start.ps1
```

Script akan otomatis:
- ✅ Cek Laravel API server
- ✅ Install dependencies
- ✅ Konfigurasi base URL
- ✅ Jalankan aplikasi

### Manual Setup

1. **Jalankan Laravel API**
   ```powershell
   cd c:\laragon\www\projekberita\projeklaravel1
   php artisan serve
   ```

2. **Install Flutter Dependencies**
   ```powershell
   cd c:\laragon\www\projekberita\portal_berita_flutter
   flutter pub get
   ```

3. **Konfigurasi API URL**
   
   Edit `lib/config/api_config.dart`:
   ```dart
   // Android Emulator (Default)
   static const String baseUrl = 'http://10.0.2.2:8000/api/v1';
   
   // iOS Simulator
   static const String baseUrl = 'http://127.0.0.1:8000/api/v1';
   
   // Physical Device (ganti dengan IP Anda)
   static const String baseUrl = 'http://192.168.1.100:8000/api/v1';
   ```

4. **Run Aplikasi**
   ```powershell
   flutter run
   ```

## 📖 Dokumentasi Lengkap

- **[SETUP_GUIDE.md](SETUP_GUIDE.md)** - Panduan setup lengkap dengan troubleshooting
- **[FLUTTER_GUIDE.md](FLUTTER_GUIDE.md)** - Panduan Flutter development
- **[TROUBLESHOOTING.md](TROUBLESHOOTING.md)** - Solusi masalah umum
- **[FIX_CONNECTION.md](FIX_CONNECTION.md)** - Fix masalah koneksi API

## 🔌 API Endpoints

Base URL: `http://127.0.0.1:8000/api/v1`

### Public Endpoints
- `GET /news` - Homepage (featured, trending, latest)
- `GET /categories` - Daftar kategori
- `GET /category/{slug}` - Artikel per kategori
- `GET /article/{slug}` - Detail artikel
- `GET /search?q=keyword` - Cari artikel
- `GET /trending` - Artikel trending
- `GET /popular` - Artikel populer
- `GET /article/{slug}/comments` - Komentar artikel

### Protected Endpoints (Perlu Login)
- `POST /register` - Registrasi user
- `POST /login` - Login
- `POST /logout` - Logout
- `GET /me` - Data user
- `POST /article/{slug}/comments` - Kirim komentar
- `DELETE /comments/{id}` - Hapus komentar

## 🛠️ Tech Stack

- **Flutter** 3.9.2
- **Laravel** 11.x (Backend API)
- **Packages:**
  - `http` - HTTP requests
  - `provider` - State management
  - `shared_preferences` - Local storage
  - `cached_network_image` - Image caching
  - `intl` - Date formatting

## 📁 Struktur Folder

```
lib/
├── config/
│   └── api_config.dart       # Konfigurasi API
├── models/
│   ├── article.dart
│   ├── category.dart
│   ├── comment.dart
│   └── user.dart
├── services/
│   ├── api_service.dart      # Service untuk artikel
│   ├── auth_service.dart     # Service autentikasi
│   └── comment_service.dart  # Service komentar
├── screens/
│   ├── home_screen.dart
│   ├── article_detail_screen.dart
│   ├── login_screen.dart
│   └── register_screen.dart
├── widgets/
│   └── ...
└── main.dart
```

## ⚡ Quick Commands

```powershell
# Run app
flutter run

# Run di device tertentu
flutter run -d chrome
flutter run -d emulator-5554

# Hot reload
r (di terminal yang sedang run)

# Hot restart
R (di terminal yang sedang run)

# Build APK
flutter build apk

# Clean project
flutter clean
flutter pub get
```

## 🔧 Troubleshooting Cepat

### Error koneksi ke API

**Android Emulator:**
```dart
// Gunakan 10.0.2.2 bukan localhost
static const String baseUrl = 'http://10.0.2.2:8000/api/v1';
```

**Physical Device:**
1. Cari IP komputer: `ipconfig`
2. Start Laravel: `php artisan serve --host=0.0.0.0`
3. Update base URL dengan IP Anda

### Laravel server tidak berjalan

```powershell
cd c:\laragon\www\projekberita\projeklaravel1
php artisan serve
```

### Dependencies error

```powershell
flutter clean
flutter pub get
flutter run
```

## 📱 Testing

Test API langsung di browser:
```
http://localhost/projekberita/projeklaravel1/public/api/v1/news
http://localhost/projekberita/projeklaravel1/public/api/v1/categories
```

Jika berhasil di browser tapi gagal di Flutter, periksa base URL di `api_config.dart`.

## 🎯 Panduan Lengkap

- 📘 **[COMMENT_LOGIN_GUIDE.md](COMMENT_LOGIN_GUIDE.md)** - Panduan lengkap login & komentar
- 📗 **[QUICK_COMMENT_GUIDE.md](QUICK_COMMENT_GUIDE.md)** - Quick reference
- 🔐 **[CROSS_PLATFORM_AUTH.md](CROSS_PLATFORM_AUTH.md)** - **Integrasi Web ↔️ Flutter** ⭐
- 🧪 **[INTEGRATION_TEST_PROOF.md](INTEGRATION_TEST_PROOF.md)** - Bukti test integrasi
- 📙 **[QUICKSTART.md](QUICKSTART.md)** - Setup awal
- 📕 **[SETUP_GUIDE.md](SETUP_GUIDE.md)** - Konfigurasi detail
- 📔 **[TROUBLESHOOTING.md](TROUBLESHOOTING.md)** - Solusi masalah

## 📞 Support

Untuk bantuan lebih lanjut, lihat dokumentasi di folder atau hubungi developer.

---

**Happy Coding! 🚀**
