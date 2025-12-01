# Portal Berita - Laravel + Flutter

Aplikasi portal berita dengan backend Laravel dan support untuk aplikasi Flutter mobile.

## 🚀 Fitur Backend (Laravel)

- ✅ Web Portal dengan design responsive (Tailwind CSS)
- ✅ REST API lengkap untuk aplikasi Flutter
- ✅ CRUD Artikel & Kategori
- ✅ Featured & Trending Articles
- ✅ Search & Filter
- ✅ View Counter
- ✅ Related Articles
- ✅ Pagination
- ✅ CORS enabled untuk mobile apps

## 📱 Integrasi Flutter

Backend ini sudah siap digunakan untuk aplikasi Flutter dengan REST API yang lengkap.

### API Endpoints Tersedia:

```
GET /api/v1/news              - Homepage (featured, trending, latest)
GET /api/v1/categories        - Daftar kategori
GET /api/v1/category/{slug}   - Artikel per kategori
GET /api/v1/article/{slug}    - Detail artikel
GET /api/v1/search?q=keyword  - Pencarian artikel
GET /api/v1/trending          - Artikel trending
GET /api/v1/popular           - Artikel populer (berdasarkan views)
```

### Quick Start Flutter:

1. **Base URL untuk Flutter:**
   - Android Emulator: `http://10.0.2.2:8000/api/v1`
   - iOS Simulator: `http://127.0.0.1:8000/api/v1`
   - Physical Device: `http://YOUR_IP:8000/api/v1`

2. **Contoh Request:**
```dart
import 'package:http/http.dart' as http;
import 'dart:convert';

Future<void> getNews() async {
  final response = await http.get(
    Uri.parse('http://10.0.2.2:8000/api/v1/news')
  );
  
  if (response.statusCode == 200) {
    final data = json.decode(response.body);
    print(data['data']['latest']);
  }
}
```

3. **Dokumentasi Lengkap:**
   Lihat file `API_DOCUMENTATION.md` untuk dokumentasi API lengkap dengan contoh kode Flutter.

## 🛠️ Instalasi & Setup

### Backend Laravel:

```bash
# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate key
php artisan key:generate

# Setup database di .env
DB_DATABASE=portal_berita
DB_USERNAME=root
DB_PASSWORD=

# Migrate & seed
php artisan migrate:fresh --seed

# Run server
php artisan serve
```

Server akan berjalan di: `http://127.0.0.1:8000`

### Test API:

```bash
# Test homepage API
curl http://127.0.0.1:8000/api/v1/news

# Test categories
curl http://127.0.0.1:8000/api/v1/categories

# Test search
curl http://127.0.0.1:8000/api/v1/search?q=banjir
```

## 📚 Database Schema

### Categories Table:
- id
- name
- slug
- timestamps

### Articles Table:
- id
- category_id
- title
- slug
- excerpt
- content
- image
- author
- views
- is_trending
- is_featured
- published_at
- timestamps

## 🎨 Web Portal

Akses web portal di: `http://127.0.0.1:8000`

Fitur web:
- Homepage dengan featured article
- Trending topics bar
- Grid layout artikel
- Kategori & filter
- Detail artikel
- Related articles
- Responsive design

## 📖 API Response Format

Semua API response menggunakan format JSON standar:

```json
{
  "success": true,
  "data": {
    // Your data here
  }
}
```

## 🔧 Konfigurasi untuk Production

1. **Update CORS** di `config/cors.php`:
```php
'allowed_origins' => [
    'https://yourapp.com',
    'http://localhost:*',
],
```

2. **Set APP_URL** di `.env`:
```
APP_URL=https://api.yourapp.com
```

3. **Optimize Laravel**:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 📱 Next Steps untuk Flutter

1. Buat project Flutter baru
2. Install dependencies: `http`, `cached_network_image`, `provider`
3. Buat models sesuai API response
4. Buat API service layer
5. Implementasi UI menggunakan data dari API
6. Lihat contoh lengkap di `API_DOCUMENTATION.md`

---

**Tech Stack:**
- Backend: Laravel 11
- Database: MySQL
- Frontend Web: Blade + Tailwind CSS
- Mobile: Ready for Flutter
- API: RESTful JSON

