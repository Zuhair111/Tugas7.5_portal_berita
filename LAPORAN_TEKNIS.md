# LAPORAN TEKNIS - PORTAL BERITA

**Aplikasi:** Portal Berita (Laravel + Flutter)  
**Tanggal:** 5 Desember 2025  
**Developer:** Zuhair111  
**Repository:** https://github.com/Zuhair111/Tugas7.5_portal_berita

---

## DAFTAR ISI
1. [Arsitektur Aplikasi](#arsitektur-aplikasi)
2. [Backend Laravel - Kode Penting](#backend-laravel)
3. [Frontend Flutter - Kode Penting](#frontend-flutter)
4. [Database Schema](#database-schema)
5. [Flow Authentication](#flow-authentication)
6. [API Documentation](#api-documentation)

---

## ARSITEKTUR APLIKASI

```
┌─────────────────────────────────────────────────────┐
│                  FRONTEND                           │
│         Flutter Mobile Application                  │
│  (Android, iOS, Web, Windows, Linux, macOS)        │
└──────────────────┬──────────────────────────────────┘
                   │
                   │ HTTP REST API
                   │ JSON Response
                   │ OAuth2 Token
                   │
┌──────────────────▼──────────────────────────────────┐
│                  BACKEND                            │
│            Laravel 12.0 API                         │
│        Laravel Passport (OAuth2)                    │
└──────────────────┬──────────────────────────────────┘
                   │
                   │
┌──────────────────▼──────────────────────────────────┐
│                 DATABASE                            │
│              SQLite (18 Tables)                     │
│   Users, Articles, Categories, Comments, OAuth     │
└─────────────────────────────────────────────────────┘
```

---

## BACKEND LARAVEL

### 1. Authentication Controller (`projeklaravel1/app/Http/Controllers/Api/AuthController.php`)

#### Register User
```php
public function register(Request $request)
{
    // Validasi input dari user
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    // Enkripsi password dan buat user baru
    $validatedData['password'] = Hash::make($validatedData['password']);
    $user = User::create($validatedData);

    // Generate OAuth2 token menggunakan Passport
    $token = $user->createToken('auth_token')->accessToken;

    // Return response dengan user data dan token
    return response()->json([
        'access_token' => $token,
        'token_type' => 'Bearer',
        'user' => $user,
    ], 201);
}
```

**Penjelasan:**
- Validasi input: nama, email (harus unik), password minimal 8 karakter dengan konfirmasi
- Hash password menggunakan bcrypt untuk keamanan
- Buat user baru di database
- Generate access token OAuth2 menggunakan Laravel Passport
- Return token dan data user untuk disimpan di Flutter

#### Login User
```php
public function login(Request $request)
{
    // Validasi kredensial login
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Cek apakah email dan password cocok
    if (!Auth::attempt($credentials)) {
        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }

    // Ambil user yang sudah terautentikasi
    $user = Auth::user();
    
    // Generate OAuth2 access token
    $token = $user->createToken('auth_token')->accessToken;

    return response()->json([
        'access_token' => $token,
        'token_type' => 'Bearer',
        'user' => $user,
    ]);
}
```

**Penjelasan:**
- Validasi email dan password
- `Auth::attempt()` mengecek kredensial di database
- Jika gagal, return 401 Unauthorized
- Jika berhasil, generate token baru untuk session
- Token ini akan digunakan untuk request berikutnya

#### Logout User
```php
public function logout(Request $request)
{
    // Revoke (hapus) token yang sedang digunakan
    $request->user()->token()->revoke();

    return response()->json([
        'message' => 'Successfully logged out'
    ]);
}
```

**Penjelasan:**
- `$request->user()` mendapatkan user dari token yang dikirim
- `token()->revoke()` menghapus token dari database OAuth
- User harus login ulang untuk mendapat token baru

---

### 2. News Controller (`projeklaravel1/app/Http/Controllers/Api/NewsController.php`)

#### Get All Articles
```php
public function index()
{
    // Ambil semua artikel dengan relasi category
    // Urutkan dari yang terbaru
    // Eager loading untuk optimasi query
    $articles = Article::with('category')
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json($articles);
}
```

**Penjelasan:**
- `Article::with('category')` menggunakan eager loading untuk menghindari N+1 query problem
- Tanpa `with()`: 1 query artikel + N query kategori = N+1 queries
- Dengan `with()`: 1 query artikel + 1 query kategori = 2 queries (lebih efisien)
- `orderBy('created_at', 'desc')` mengurutkan artikel terbaru di atas

#### Get Article by Slug
```php
public function show($slug)
{
    // Cari artikel berdasarkan slug
    $article = Article::with('category')
        ->where('slug', $slug)
        ->firstOrFail(); // Throw 404 jika tidak ada

    // Increment views counter
    $article->increment('views');

    return response()->json($article);
}
```

**Penjelasan:**
- `where('slug', $slug)` mencari artikel berdasarkan slug (URL-friendly)
- `firstOrFail()` return artikel atau throw 404 Not Found
- `increment('views')` menambah counter views artikel
- Ini menghindari race condition dibanding `$article->views++`

#### Get Articles by Category
```php
public function category($slug)
{
    // Cari kategori berdasarkan slug
    $category = Category::where('slug', $slug)->firstOrFail();

    // Ambil semua artikel dalam kategori ini
    $articles = Article::with('category')
        ->where('category_id', $category->id)
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json($articles);
}
```

**Penjelasan:**
- Dua-langkah query: cari kategori dulu, lalu filter artikel
- Validasi kategori exist dengan `firstOrFail()`
- Filter artikel berdasarkan `category_id`

#### Search Articles
```php
public function search(Request $request)
{
    $query = $request->input('q'); // Ambil keyword dari query string

    // Cari di title atau content yang mengandung keyword
    $articles = Article::with('category')
        ->where('title', 'LIKE', "%{$query}%")
        ->orWhere('content', 'LIKE', "%{$query}%")
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json($articles);
}
```

**Penjelasan:**
- `LIKE "%keyword%"` mencari substring di database (case-insensitive di SQLite)
- `orWhere()` mencari di title ATAU content
- `%` adalah wildcard untuk "apapun sebelum/sesudah keyword"
- Contoh: keyword "politik" akan match "Berita Politik Terkini"

---

### 3. Comment Controller (`projeklaravel1/app/Http/Controllers/Api/CommentController.php`)

#### Get Comments
```php
public function index($slug)
{
    // Cari artikel berdasarkan slug
    $article = Article::where('slug', $slug)->firstOrFail();

    // Ambil komentar artikel dengan data user
    $comments = Comment::with('user')
        ->where('article_id', $article->id)
        ->orderBy('created_at', 'desc')
        ->paginate(20); // 20 komentar per halaman

    return response()->json($comments);
}
```

**Penjelasan:**
- `with('user')` eager load data user yang berkomentar
- `paginate(20)` membagi hasil jadi halaman dengan 20 item
- Return format: `{data: [...], current_page: 1, last_page: 3, ...}`
- Pagination mengurangi load database untuk artikel dengan banyak komentar

#### Post Comment (Protected)
```php
public function store(Request $request, $slug)
{
    // Cari artikel
    $article = Article::where('slug', $slug)->firstOrFail();

    // Validasi isi komentar
    $validatedData = $request->validate([
        'content' => 'required|string|max:1000',
    ]);

    // Buat komentar baru
    // $request->user() mendapat user dari token
    $comment = Comment::create([
        'article_id' => $article->id,
        'user_id' => $request->user()->id,
        'content' => $validatedData['content'],
    ]);

    // Load relasi user untuk response
    $comment->load('user');

    return response()->json($comment, 201);
}
```

**Penjelasan:**
- Route protected oleh middleware `auth:api` (harus login)
- `$request->user()` otomatis mendapat user dari Bearer token
- Validasi max 1000 karakter untuk mencegah spam
- `load('user')` lazy load relasi setelah create
- Return 201 Created status code

#### Delete Comment (Protected)
```php
public function destroy($id)
{
    // Cari komentar berdasarkan ID
    $comment = Comment::findOrFail($id);

    // Cek apakah user adalah pemilik komentar
    if ($comment->user_id !== request()->user()->id) {
        return response()->json([
            'message' => 'Unauthorized'
        ], 403); // Forbidden
    }

    // Hapus komentar
    $comment->delete();

    return response()->json([
        'message' => 'Comment deleted successfully'
    ]);
}
```

**Penjelasan:**
- Authorization: hanya pemilik komentar yang bisa hapus
- Check `$comment->user_id !== request()->user()->id` mencegah user lain hapus komentar
- Return 403 Forbidden jika bukan pemilik
- Soft delete bisa diaktifkan dengan trait `SoftDeletes` di model

---

### 4. User Model (`projeklaravel1/app/Models/User.php`)

```php
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
```

**Penjelasan:**
- `HasApiTokens` dari Passport: enable OAuth2 token generation
- `$fillable`: field yang boleh di-mass assignment (create/update)
- `$hidden`: field yang tidak muncul di JSON response (keamanan)
- `casts`: konversi tipe data otomatis (password auto-hashed)

---

### 5. Article Model (`projeklaravel1/app/Models/Article.php`)

```php
class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'content',
        'excerpt',
        'image',
        'author',
        'views',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // Relasi ke Category (Many to One)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi ke Comments (One to Many)
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Accessor untuk format waktu relatif
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
```

**Penjelasan:**
- `belongsTo(Category::class)`: setiap artikel punya 1 kategori
- `hasMany(Comment::class)`: setiap artikel punya banyak komentar
- `getTimeAgoAttribute()`: accessor untuk mendapat "2 jam yang lalu"
- `diffForHumans()` menggunakan Carbon library untuk format waktu

---

### 6. Routes API (`projeklaravel1/routes/api.php`)

```php
// Public routes (tanpa auth)
Route::prefix('v1')->group(function () {
    // Authentication
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // News
    Route::get('/news', [NewsController::class, 'index']);
    Route::get('/news/{slug}', [NewsController::class, 'show']);
    Route::get('/news/category/{slug}', [NewsController::class, 'category']);
    Route::get('/news/search', [NewsController::class, 'search']);

    // Categories
    Route::get('/categories', [NewsController::class, 'categories']);

    // Comments (read only)
    Route::get('/news/{slug}/comments', [CommentController::class, 'index']);
});

// Protected routes (perlu auth)
Route::prefix('v1')->middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Comments (create/delete)
    Route::post('/news/{slug}/comments', [CommentController::class, 'store']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
});
```

**Penjelasan:**
- `prefix('v1')`: semua route punya prefix `/api/v1`
- Public routes: bisa diakses tanpa login
- `middleware('auth:api')`: route harus kirim Bearer token
- Passport driver `api` mengecek token di header `Authorization: Bearer {token}`

---

### 7. App Service Provider (`projeklaravel1/app/Providers/AppServiceProvider.php`)

```php
use Laravel\Passport\Passport;

public function boot(): void
{
    // Nonaktifkan route default Passport (kita pakai custom)
    Passport::ignoreRoutes();

    // Set token expiry time
    Passport::tokensExpireIn(now()->addDays(15));      // 15 hari
    Passport::refreshTokensExpireIn(now()->addDays(30)); // 30 hari
    Passport::personalAccessTokensExpireIn(now()->addMonths(6)); // 6 bulan
}
```

**Penjelasan:**
- `tokensExpireIn()`: access token expired setelah 15 hari
- `refreshTokensExpireIn()`: refresh token untuk perpanjang session
- `personalAccessTokensExpireIn()`: token untuk personal access client
- Token expired otomatis tidak valid, user harus login ulang

---

## FRONTEND FLUTTER

### 1. API Config (`portal_berita_flutter/lib/config/api_config.dart`)

```dart
class ApiConfig {
  // Base URL untuk berbagai environment
  static const String localhost = 'http://localhost/projekberita/projeklaravel1/public/api/v1';
  static const String emulator = 'http://10.0.2.2/projekberita/projeklaravel1/public/api/v1';
  static const String physicalDevice = 'http://192.168.100.35/projekberita/projeklaravel1/public/api/v1';

  // URL aktif (ganti sesuai kebutuhan)
  static const String baseUrl = physicalDevice;

  // Generate headers dengan Bearer token
  static Future<Map<String, String>> getHeaders() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('auth_token') ?? '';

    return {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      if (token.isNotEmpty) 'Authorization': 'Bearer $token',
    };
  }
}
```

**Penjelasan:**
- `localhost`: untuk testing di browser PC
- `emulator`: IP khusus Android Emulator (10.0.2.2 = localhost PC)
- `physicalDevice`: IP PC di jaringan WiFi (harus diganti sesuai `ipconfig`)
- `getHeaders()`: baca token dari SharedPreferences dan masukkan ke header
- `if (token.isNotEmpty)`: conditional header, hanya kirim jika ada token
- Token disimpan setelah login, digunakan untuk request protected routes

---

### 2. Auth Service (`portal_berita_flutter/lib/services/auth_service.dart`)

#### Login
```dart
Future<Map<String, dynamic>> login(String email, String password) async {
  try {
    // Kirim POST request ke /api/v1/login
    final response = await http.post(
      Uri.parse('${ApiConfig.baseUrl}/login'),
      headers: await ApiConfig.getHeaders(),
      body: json.encode({
        'email': email,
        'password': password,
      }),
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      
      // Simpan token dan user data ke local storage
      final prefs = await SharedPreferences.getInstance();
      await prefs.setString('auth_token', data['access_token']);
      await prefs.setString('user_name', data['user']['name']);
      await prefs.setString('user_email', data['user']['email']);

      return {'success': true};
    } else {
      // Parse error message dari server
      final error = json.decode(response.body);
      return {'success': false, 'message': error['message']};
    }
  } catch (e) {
    return {'success': false, 'message': e.toString()};
  }
}
```

**Penjelasan:**
- `http.post()`: kirim HTTP POST request
- `json.encode()`: convert Map ke JSON string
- `response.statusCode == 200`: cek apakah request berhasil
- `SharedPreferences`: local storage seperti LocalStorage di web
- Token disimpan untuk request berikutnya
- Return Map dengan `success` flag untuk UI feedback

#### Logout
```dart
Future<void> logout() async {
  try {
    // Kirim request logout ke server (revoke token)
    await http.post(
      Uri.parse('${ApiConfig.baseUrl}/logout'),
      headers: await ApiConfig.getHeaders(),
    );
  } catch (e) {
    print('Logout error: $e');
  } finally {
    // Hapus semua data local (walaupun request gagal)
    final prefs = await SharedPreferences.getInstance();
    await prefs.clear();
  }
}
```

**Penjelasan:**
- `try-catch-finally`: pastikan local storage selalu dihapus
- Request ke server untuk revoke token di database
- `prefs.clear()`: hapus semua data di SharedPreferences
- Walaupun request gagal, user tetap logout di client side

#### Check Login Status
```dart
Future<bool> isLoggedIn() async {
  final prefs = await SharedPreferences.getInstance();
  return prefs.getString('auth_token') != null;
}
```

**Penjelasan:**
- Cek apakah token tersimpan di local storage
- Tidak validasi ke server (cek offline)
- Digunakan untuk redirect user atau show/hide fitur

---

### 3. API Service (`portal_berita_flutter/lib/services/api_service.dart`)

#### Get Articles
```dart
Future<List<Article>> getArticles() async {
  try {
    final response = await http.get(
      Uri.parse('${ApiConfig.baseUrl}/news'),
      headers: await ApiConfig.getHeaders(),
    );

    if (response.statusCode == 200) {
      // Parse JSON array ke List<Article>
      final List<dynamic> data = json.decode(response.body);
      return data.map((json) => Article.fromJson(json)).toList();
    } else {
      throw Exception('Failed to load articles');
    }
  } catch (e) {
    throw Exception('Error: $e');
  }
}
```

**Penjelasan:**
- `http.get()`: GET request tanpa body
- `json.decode()`: parse JSON string ke Dart object
- `data.map()`: transform setiap item JSON jadi Article object
- `Article.fromJson()`: factory constructor untuk parsing
- `throw Exception()`: lempar error untuk ditangkap di UI

#### Get Article by Slug
```dart
Future<Article> getArticleBySlug(String slug) async {
  final response = await http.get(
    Uri.parse('${ApiConfig.baseUrl}/news/$slug'),
    headers: await ApiConfig.getHeaders(),
  );

  if (response.statusCode == 200) {
    return Article.fromJson(json.decode(response.body));
  } else {
    throw Exception('Article not found');
  }
}
```

**Penjelasan:**
- String interpolation `$slug` dalam URL
- Return single Article object (bukan List)
- 404 error akan throw exception

---

### 4. Comment Service (`portal_berita_flutter/lib/services/comment_service.dart`)

#### Post Comment
```dart
Future<Map<String, dynamic>> postComment(String slug, String content) async {
  try {
    final response = await http.post(
      Uri.parse('${ApiConfig.baseUrl}/news/$slug/comments'),
      headers: await ApiConfig.getHeaders(),
      body: json.encode({'content': content}),
    );

    if (response.statusCode == 201) {
      return {'success': true, 'comment': json.decode(response.body)};
    } else if (response.statusCode == 401) {
      // Token expired atau tidak valid
      // Logout otomatis
      final authService = AuthService();
      await authService.logout();
      return {'success': false, 'logout': true};
    } else {
      return {'success': false, 'message': 'Failed to post comment'};
    }
  } catch (e) {
    return {'success': false, 'message': e.toString()};
  }
}
```

**Penjelasan:**
- Status 201 Created: komentar berhasil dibuat
- Status 401 Unauthorized: token invalid/expired
- Auto-logout jika 401 untuk keamanan
- `logout: true` flag untuk UI redirect ke login

#### Delete Comment
```dart
Future<Map<String, dynamic>> deleteComment(int commentId) async {
  try {
    final response = await http.delete(
      Uri.parse('${ApiConfig.baseUrl}/comments/$commentId'),
      headers: await ApiConfig.getHeaders(),
    );

    if (response.statusCode == 200) {
      return {'success': true};
    } else if (response.statusCode == 401) {
      final authService = AuthService();
      await authService.logout();
      return {'success': false, 'logout': true};
    } else if (response.statusCode == 403) {
      return {'success': false, 'message': 'Unauthorized to delete'};
    } else {
      return {'success': false, 'message': 'Failed to delete'};
    }
  } catch (e) {
    return {'success': false, 'message': e.toString()};
  }
}
```

**Penjelasan:**
- `http.delete()`: DELETE request
- Status 403 Forbidden: bukan pemilik komentar
- Auto-logout jika 401
- Return berbagai status untuk UI feedback

---

### 5. Article Model (`portal_berita_flutter/lib/models/article.dart`)

```dart
class Article {
  final int id;
  final int categoryId;
  final String title;
  final String slug;
  final String content;
  final String? excerpt;
  final String? image;
  final String author;
  final int views;
  final DateTime createdAt;
  final Category category;

  Article({
    required this.id,
    required this.categoryId,
    required this.title,
    required this.slug,
    required this.content,
    this.excerpt,
    this.image,
    required this.author,
    required this.views,
    required this.createdAt,
    required this.category,
  });

  // Parse JSON ke Article object
  factory Article.fromJson(Map<String, dynamic> json) {
    return Article(
      id: json['id'],
      categoryId: json['category_id'],
      title: json['title'],
      slug: json['slug'],
      content: json['content'],
      excerpt: json['excerpt'],
      image: json['image'],
      author: json['author'],
      views: json['views'] ?? 0,
      createdAt: DateTime.parse(json['created_at']),
      category: Category.fromJson(json['category']),
    );
  }

  // Accessor untuk waktu relatif
  String get timeAgo {
    final difference = DateTime.now().difference(createdAt);
    
    if (difference.inDays > 0) {
      return '${difference.inDays} hari yang lalu';
    } else if (difference.inHours > 0) {
      return '${difference.inHours} jam yang lalu';
    } else if (difference.inMinutes > 0) {
      return '${difference.inMinutes} menit yang lalu';
    } else {
      return 'Baru saja';
    }
  }
}
```

**Penjelasan:**
- `String?`: nullable field (bisa null)
- `required`: field wajib diisi
- `factory`: constructor khusus untuk parsing JSON
- `DateTime.parse()`: convert string ISO 8601 ke DateTime
- `get timeAgo`: computed property untuk format waktu
- `Category.fromJson()`: nested object parsing

---

### 6. Home Screen (`portal_berita_flutter/lib/screens/home_screen.dart`)

#### Tab Controller
```dart
class _HomeScreenState extends State<HomeScreen> with SingleTickerProviderStateMixin {
  late TabController _tabController;

  @override
  void initState() {
    super.initState();
    // Buat TabController dengan 4 tabs
    _tabController = TabController(length: 4, vsync: this);
    _loadArticles();
  }

  @override
  void dispose() {
    _tabController.dispose(); // Cleanup
    super.dispose();
  }
}
```

**Penjelasan:**
- `SingleTickerProviderStateMixin`: required untuk TabController animation
- `vsync: this`: sinkronisasi animasi dengan widget lifecycle
- `dispose()`: cleanup untuk mencegah memory leak
- 4 tabs: Berita Utama, Terkini, Populer, Rekomendasi

#### Load Articles
```dart
Future<void> _loadArticles() async {
  try {
    setState(() {
      _loading = true;
    });

    final articles = await _apiService.getArticles();

    setState(() {
      _articles = articles;
      _loading = false;
    });
  } catch (e) {
    setState(() {
      _error = e.toString();
      _loading = false;
    });
  }
}
```

**Penjelasan:**
- `setState()`: rebuild widget dengan state baru
- `_loading = true`: show loading indicator
- `await`: tunggu async request selesai
- `try-catch`: tangkap error network atau parsing
- Update state dengan data atau error

#### Logout Handler
```dart
Future<void> _logout() async {
  final confirm = await showDialog<bool>(
    context: context,
    builder: (context) => AlertDialog(
      title: const Text('Konfirmasi'),
      content: const Text('Yakin ingin keluar?'),
      actions: [
        TextButton(
          onPressed: () => Navigator.pop(context, false),
          child: const Text('Batal'),
        ),
        TextButton(
          onPressed: () => Navigator.pop(context, true),
          child: const Text('Keluar'),
        ),
      ],
    ),
  );

  if (confirm == true && mounted) {
    await _authService.logout();
    
    if (mounted) {
      Navigator.pushReplacementNamed(context, '/login');
    }
  }
}
```

**Penjelasan:**
- `showDialog()`: tampilkan dialog konfirmasi
- `await showDialog<bool>()`: tunggu user pilih
- `Navigator.pop(context, value)`: tutup dialog dengan return value
- `if (mounted)`: cek widget masih ada sebelum navigasi
- `pushReplacementNamed()`: replace current route (tidak bisa back)

---

### 7. Comment Section Widget (`portal_berita_flutter/lib/widgets/comment_section.dart`)

```dart
Future<void> _postComment() async {
  if (_commentController.text.trim().isEmpty) {
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(content: Text('Komentar tidak boleh kosong')),
    );
    return;
  }

  final result = await _commentService.postComment(
    widget.articleSlug,
    _commentController.text,
  );

  if (result['success']) {
    _commentController.clear();
    _loadComments(); // Reload comments
    
    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Komentar berhasil ditambahkan')),
      );
    }
  } else if (result['logout'] == true) {
    // Token expired, redirect to login
    if (mounted) {
      Navigator.pushReplacementNamed(context, '/login');
    }
  } else {
    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(result['message'] ?? 'Gagal menambahkan komentar')),
      );
    }
  }
}
```

**Penjelasan:**
- `trim().isEmpty`: cek string kosong atau hanya spasi
- `ScaffoldMessenger`: show toast notification
- `clear()`: kosongkan TextField setelah submit
- `_loadComments()`: refresh daftar komentar
- Auto redirect ke login jika token expired
- Multiple conditional untuk berbagai response

---

## DATABASE SCHEMA

### Struktur Database SQLite (18 Tables)

#### 1. **users** - Data Pengguna
```sql
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Penjelasan:**
- `AUTOINCREMENT`: ID otomatis bertambah
- `UNIQUE`: email tidak boleh duplikat
- `password`: disimpan dalam bentuk hash bcrypt
- `remember_token`: untuk "remember me" feature

**Sample Data:**
```
id | name        | email                | password (hashed)
1  | Admin User  | admin@example.com    | $2y$12$...
2  | John Doe    | user@example.com     | $2y$12$...
3  | Jane Smith  | jane@example.com     | $2y$12$...
```

---

#### 2. **categories** - Kategori Berita
```sql
CREATE TABLE categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Penjelasan:**
- `slug`: URL-friendly version dari name (contoh: "Politik" → "politik")
- Digunakan untuk filter artikel per kategori

**Sample Data:**
```
id | name       | slug
1  | Politik    | politik
2  | Ekonomi    | ekonomi
3  | Olahraga   | olahraga
4  | Teknologi  | teknologi
```

---

#### 3. **articles** - Data Artikel Berita
```sql
CREATE TABLE articles (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    category_id INTEGER NOT NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content TEXT NOT NULL,
    excerpt TEXT NULL,
    image VARCHAR(255) NULL,
    author VARCHAR(255) NOT NULL,
    views INTEGER DEFAULT 0,
    published_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);
```

**Penjelasan:**
- `category_id`: relasi ke tabel categories
- `slug`: unique identifier untuk URL artikel
- `content`: isi artikel lengkap (TEXT untuk data besar)
- `excerpt`: ringkasan artikel (untuk preview)
- `views`: counter berapa kali dibaca
- `ON DELETE CASCADE`: jika kategori dihapus, artikel ikut terhapus

**Sample Data:**
```
id | category_id | title                          | slug                  | views
1  | 1          | Pemilu 2025 Akan Segera Tiba   | pemilu-2025          | 150
2  | 2          | Harga Minyak Dunia Naik        | harga-minyak-naik    | 89
3  | 3          | Timnas Indonesia Menang        | timnas-menang        | 200
```

---

#### 4. **comments** - Komentar Artikel
```sql
CREATE TABLE comments (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    article_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

**Penjelasan:**
- `article_id`: komentar untuk artikel mana
- `user_id`: siapa yang berkomentar
- `ON DELETE CASCADE`: jika artikel/user dihapus, komentar ikut terhapus

**Sample Data:**
```
id | article_id | user_id | content                    | created_at
1  | 1         | 2       | Artikel yang menarik!      | 2025-12-01 10:30:00
2  | 1         | 3       | Terima kasih infonya       | 2025-12-01 11:15:00
```

---

#### 5. **oauth_clients** - OAuth Client Passport
```sql
CREATE TABLE oauth_clients (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NULL,
    name VARCHAR(255) NOT NULL,
    secret VARCHAR(100) NULL,
    provider VARCHAR(255) NULL,
    redirect TEXT NOT NULL,
    personal_access_client BOOLEAN NOT NULL,
    password_client BOOLEAN NOT NULL,
    revoked BOOLEAN NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Penjelasan:**
- OAuth2 client configuration
- `personal_access_client`: untuk mobile/desktop app
- `password_client`: untuk password grant
- `secret`: client secret untuk authentication

---

#### 6. **oauth_access_tokens** - Token Akses
```sql
CREATE TABLE oauth_access_tokens (
    id VARCHAR(100) PRIMARY KEY,
    user_id INTEGER NULL,
    client_id INTEGER NOT NULL,
    name VARCHAR(255) NULL,
    scopes TEXT NULL,
    revoked BOOLEAN NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NULL,
    FOREIGN KEY (client_id) REFERENCES oauth_clients(id) ON DELETE CASCADE
);
```

**Penjelasan:**
- Menyimpan access token yang aktif
- `revoked`: token yang sudah di-logout
- `expires_at`: waktu token expired (15 hari)
- Token ini yang dikirim di header `Authorization: Bearer {id}`

---

#### 7. **oauth_refresh_tokens** - Refresh Token
```sql
CREATE TABLE oauth_refresh_tokens (
    id VARCHAR(100) PRIMARY KEY,
    access_token_id VARCHAR(100) NOT NULL,
    revoked BOOLEAN NOT NULL DEFAULT 0,
    expires_at TIMESTAMP NULL,
    FOREIGN KEY (access_token_id) REFERENCES oauth_access_tokens(id) ON DELETE CASCADE
);
```

**Penjelasan:**
- Untuk perpanjang session tanpa login ulang
- Expired 30 hari (lebih lama dari access token)

---

### Relasi Database

```
┌─────────────┐
│   users     │
└──────┬──────┘
       │
       │ 1:N (One to Many)
       │
       ▼
┌─────────────┐      ┌──────────────┐
│  comments   │◄─────┤  articles    │
└─────────────┘  N:1 └──────┬───────┘
                            │
                            │ N:1 (Many to One)
                            │
                            ▼
                     ┌──────────────┐
                     │ categories   │
                     └──────────────┘

┌─────────────┐      ┌──────────────────────┐
│   users     │◄─────┤ oauth_access_tokens  │
└─────────────┘  1:N └──────────┬───────────┘
                                │
                                │ 1:1
                                │
                                ▼
                     ┌──────────────────────┐
                     │ oauth_refresh_tokens │
                     └──────────────────────┘
```

**Penjelasan Relasi:**
1. **User → Comments**: Satu user bisa punya banyak komentar
2. **Article → Comments**: Satu artikel bisa punya banyak komentar
3. **Category → Articles**: Satu kategori bisa punya banyak artikel
4. **User → OAuth Tokens**: Satu user bisa punya banyak token (multi-device)
5. **Access Token → Refresh Token**: Setiap access token punya 1 refresh token

---

## FLOW AUTHENTICATION

### 1. Register Flow

```
┌──────────┐                 ┌──────────┐                 ┌──────────┐
│  Flutter │                 │  Laravel │                 │ Database │
└────┬─────┘                 └────┬─────┘                 └────┬─────┘
     │                            │                            │
     │  POST /api/v1/register     │                            │
     │  {name, email, password}   │                            │
     ├───────────────────────────►│                            │
     │                            │                            │
     │                            │  Validate Input            │
     │                            │  Hash Password             │
     │                            │                            │
     │                            │  INSERT INTO users         │
     │                            ├───────────────────────────►│
     │                            │                            │
     │                            │  Generate OAuth2 Token     │
     │                            ├───────────────────────────►│
     │                            │  (oauth_access_tokens)     │
     │                            │                            │
     │  Response:                 │                            │
     │  {access_token, user}      │                            │
     │◄───────────────────────────┤                            │
     │                            │                            │
     │  Save to SharedPreferences │                            │
     │  - auth_token              │                            │
     │  - user_name               │                            │
     │  - user_email              │                            │
     │                            │                            │
```

---

### 2. Login Flow

```
┌──────────┐                 ┌──────────┐                 ┌──────────┐
│  Flutter │                 │  Laravel │                 │ Database │
└────┬─────┘                 └────┬─────┘                 └────┬─────┘
     │                            │                            │
     │  POST /api/v1/login        │                            │
     │  {email, password}         │                            │
     ├───────────────────────────►│                            │
     │                            │                            │
     │                            │  SELECT FROM users         │
     │                            │  WHERE email = ?           │
     │                            ├───────────────────────────►│
     │                            │                            │
     │                            │  Verify Password Hash      │
     │                            │  (bcrypt compare)          │
     │                            │                            │
     │                            │  Generate New Token        │
     │                            ├───────────────────────────►│
     │                            │                            │
     │  Response:                 │                            │
     │  {access_token, user}      │                            │
     │◄───────────────────────────┤                            │
     │                            │                            │
     │  Save to SharedPreferences │                            │
     │                            │                            │
```

---

### 3. Protected Request Flow (Post Comment)

```
┌──────────┐                 ┌──────────┐                 ┌──────────┐
│  Flutter │                 │  Laravel │                 │ Database │
└────┬─────┘                 └────┬─────┘                 └────┬─────┘
     │                            │                            │
     │  POST /api/v1/news/{slug}/comments                      │
     │  Header: Authorization: Bearer {token}                  │
     │  Body: {content}           │                            │
     ├───────────────────────────►│                            │
     │                            │                            │
     │                            │  Middleware: auth:api      │
     │                            │  Validate Token            │
     │                            ├───────────────────────────►│
     │                            │  SELECT FROM               │
     │                            │  oauth_access_tokens       │
     │                            │  WHERE id = ? AND          │
     │                            │  expires_at > NOW()        │
     │                            │                            │
     │                            │  Get User from Token       │
     │                            │◄───────────────────────────┤
     │                            │                            │
     │                            │  INSERT INTO comments      │
     │                            ├───────────────────────────►│
     │                            │                            │
     │  Response: {comment}       │                            │
     │◄───────────────────────────┤                            │
     │                            │                            │
```

---

### 4. Logout Flow

```
┌──────────┐                 ┌──────────┐                 ┌──────────┐
│  Flutter │                 │  Laravel │                 │ Database │
└────┬─────┘                 └────┬─────┘                 └────┬─────┘
     │                            │                            │
     │  POST /api/v1/logout       │                            │
     │  Header: Authorization: Bearer {token}                  │
     ├───────────────────────────►│                            │
     │                            │                            │
     │                            │  UPDATE oauth_access_tokens│
     │                            │  SET revoked = 1           │
     │                            │  WHERE id = ?              │
     │                            ├───────────────────────────►│
     │                            │                            │
     │  Response: {message}       │                            │
     │◄───────────────────────────┤                            │
     │                            │                            │
     │  Clear SharedPreferences   │                            │
     │  - Remove all saved data   │                            │
     │                            │                            │
     │  Navigate to Login Screen  │                            │
     │                            │                            │
```

---

## API DOCUMENTATION

### Base URL
```
http://192.168.100.35/projekberita/projeklaravel1/public/api/v1
```

### Authentication Endpoints

#### 1. Register
```http
POST /register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response 201:**
```json
{
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "token_type": "Bearer",
  "user": {
    "id": 4,
    "name": "John Doe",
    "email": "john@example.com",
    "created_at": "2025-12-05T10:30:00.000000Z"
  }
}
```

#### 2. Login
```http
POST /login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password"
}
```

**Response 200:**
```json
{
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "token_type": "Bearer",
  "user": {
    "id": 2,
    "name": "John Doe",
    "email": "user@example.com"
  }
}
```

#### 3. Logout (Protected)
```http
POST /logout
Authorization: Bearer {token}
```

**Response 200:**
```json
{
  "message": "Successfully logged out"
}
```

---

### News Endpoints

#### 1. Get All Articles
```http
GET /news
```

**Response 200:**
```json
[
  {
    "id": 1,
    "category_id": 1,
    "title": "Pemilu 2025 Akan Segera Tiba",
    "slug": "pemilu-2025",
    "content": "...",
    "excerpt": "Ringkasan artikel...",
    "image": "https://example.com/image.jpg",
    "author": "Admin",
    "views": 150,
    "created_at": "2025-12-01T08:00:00.000000Z",
    "category": {
      "id": 1,
      "name": "Politik",
      "slug": "politik"
    }
  }
]
```

#### 2. Get Article by Slug
```http
GET /news/{slug}
```

**Response 200:** (sama seperti item array di atas)

#### 3. Get Articles by Category
```http
GET /news/category/{category_slug}
```

#### 4. Search Articles
```http
GET /news/search?q=keyword
```

---

### Comment Endpoints

#### 1. Get Comments
```http
GET /news/{slug}/comments
```

**Response 200:**
```json
{
  "data": [
    {
      "id": 1,
      "article_id": 1,
      "user_id": 2,
      "content": "Artikel yang menarik!",
      "created_at": "2025-12-01T10:30:00.000000Z",
      "user": {
        "id": 2,
        "name": "John Doe",
        "email": "user@example.com"
      }
    }
  ],
  "current_page": 1,
  "last_page": 1,
  "per_page": 20,
  "total": 2
}
```

#### 2. Post Comment (Protected)
```http
POST /news/{slug}/comments
Authorization: Bearer {token}
Content-Type: application/json

{
  "content": "Komentar saya..."
}
```

**Response 201:**
```json
{
  "id": 3,
  "article_id": 1,
  "user_id": 2,
  "content": "Komentar saya...",
  "created_at": "2025-12-05T14:20:00.000000Z",
  "user": {
    "id": 2,
    "name": "John Doe"
  }
}
```

#### 3. Delete Comment (Protected)
```http
DELETE /comments/{id}
Authorization: Bearer {token}
```

**Response 200:**
```json
{
  "message": "Comment deleted successfully"
}
```

---

## KESIMPULAN

### Teknologi yang Digunakan

**Backend:**
- Laravel 12.0
- Laravel Passport (OAuth2)
- SQLite Database
- PHP 8.3.28

**Frontend:**
- Flutter 3.9.2
- Dart SDK
- http package
- SharedPreferences
- cached_network_image

### Fitur Utama

1. **Authentication**
   - Register dengan validasi
   - Login dengan token OAuth2
   - Logout dengan token revocation
   - Auto-logout jika token expired

2. **News Management**
   - List artikel dengan kategori
   - Detail artikel dengan view counter
   - Filter artikel per kategori
   - Search artikel

3. **Comment System**
   - View comments dengan pagination
   - Post comment (authenticated)
   - Delete own comment (authorization)
   - Auto-logout on 401

4. **Security**
   - Password hashing (bcrypt)
   - OAuth2 token authentication
   - Token expiry (15 days)
   - CSRF protection
   - Authorization check

### Best Practices yang Diterapkan

1. **Clean Architecture**
   - Separation of concerns (Model-Controller-Service)
   - Reusable services
   - Centralized configuration

2. **Error Handling**
   - Try-catch di setiap async operation
   - User-friendly error messages
   - Auto-logout on authentication errors

3. **Performance**
   - Eager loading untuk avoid N+1 queries
   - Pagination untuk large datasets
   - Image caching di Flutter
   - Lazy loading comments

4. **Security**
   - Token-based authentication
   - Password hashing
   - Authorization checks
   - Hidden sensitive fields

---

**Laporan ini menjelaskan kode-kode penting dari aplikasi Portal Berita, mulai dari backend Laravel hingga frontend Flutter, serta struktur database yang digunakan.**

**Dibuat oleh:** GitHub Copilot  
**Tanggal:** 5 Desember 2025
