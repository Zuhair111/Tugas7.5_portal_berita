<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = [
            [
                'category' => 'Berita Utama',
                'title' => 'Sumatera Utara dan Aceh Lumpuh Total: Dihantam Banjir dan Gempa',
                'excerpt' => 'Wilayah Sumatera Utara dan Aceh mengalami bencana ganda dengan banjir bandang dan gempa bumi yang melanda beberapa daerah.',
                'content' => 'Wilayah Sumatera Utara dan Aceh mengalami bencana ganda dengan banjir bandang dan gempa bumi yang melanda beberapa daerah. Ribuan warga mengungsi dan infrastruktur rusak parah. Tim SAR masih melakukan pencarian korban yang hilang.',
                'image' => 'https://images.unsplash.com/photo-1547683905-f686c993aae5?w=800',
                'is_featured' => true,
                'is_trending' => true,
                'minutes_ago' => 53,
            ],
            [
                'category' => 'Politik',
                'title' => 'Alex Iskandar Tersangka Pembunuhan Alvaro Kiano Tewas Bunuh Diri, Keluarga Bantah',
                'excerpt' => 'Alex Iskandar yang merupakan tersangka dalam kasus pembunuhan Alvaro Kiano ditemukan tewas di sel tahanan.',
                'content' => 'Alex Iskandar yang merupakan tersangka dalam kasus pembunuhan Alvaro Kiano ditemukan tewas di sel tahanan. Pihak keluarga membantah keras dugaan bunuh diri dan meminta investigasi menyeluruh.',
                'image' => 'https://images.unsplash.com/photo-1577741314755-048d8525d31e?w=800',
                'is_trending' => true,
                'minutes_ago' => 38,
            ],
            [
                'category' => 'Teknologi',
                'title' => 'Biar Paham, Ini Perbedaan EV, HEV, dan PHEV',
                'excerpt' => 'Mengenal berbagai jenis kendaraan listrik dan hybrid yang kini semakin populer di Indonesia.',
                'content' => 'Kendaraan listrik kini hadir dalam berbagai jenis. EV (Electric Vehicle) sepenuhnya menggunakan listrik, HEV (Hybrid Electric Vehicle) menggabungkan mesin bensin dan motor listrik, sedangkan PHEV (Plug-in Hybrid Electric Vehicle) dapat diisi ulang dari sumber eksternal.',
                'image' => 'https://images.unsplash.com/photo-1593941707882-a5bba14938c7?w=800',
                'is_trending' => true,
                'minutes_ago' => 44,
            ],
            [
                'category' => 'Teknologi',
                'title' => 'Deteksi TBC Pakai AI, Wamenkes Dante Ingatkan Bukan untuk Diagnosis Penyakit',
                'excerpt' => 'Wakil Menteri Kesehatan Dante Saksono mengingatkan bahwa AI hanya alat bantu deteksi, bukan pengganti diagnosis dokter.',
                'content' => 'Penggunaan kecerdasan buatan (AI) untuk mendeteksi tuberkulosis (TBC) terus dikembangkan. Namun, Wakil Menteri Kesehatan Dante Saksono menegaskan bahwa AI hanya sebagai alat bantu deteksi awal, bukan untuk diagnosis akhir yang tetap memerlukan pemeriksaan dokter.',
                'image' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=800',
                'minutes_ago' => 92,
            ],
            [
                'category' => 'Internasional',
                'title' => 'Rencana Perjalanan Kate Middleton dan Pangeran William ke Amerika, Bakal Bertemu Trump',
                'excerpt' => 'Pangeran William dan Kate Middleton dijadwalkan melakukan kunjungan kenegaraan ke Amerika Serikat.',
                'content' => 'Pangeran William dan Kate Middleton dijadwalkan melakukan kunjungan kenegaraan ke Amerika Serikat dalam waktu dekat. Kunjungan ini termasuk pertemuan dengan Presiden Trump dan berbagai acara diplomatik lainnya.',
                'image' => 'https://images.unsplash.com/photo-1529107386315-e1a2ed48a620?w=800',
                'minutes_ago' => 105,
            ],
            [
                'category' => 'Olahraga',
                'title' => 'Timnas Indonesia Raih Kemenangan Telak 3-0 Atas Malaysia',
                'excerpt' => 'Timnas Indonesia berhasil mengalahkan Malaysia dengan skor 3-0 dalam pertandingan persahabatan.',
                'content' => 'Dalam laga persahabatan yang berlangsung seru, Timnas Indonesia berhasil meraih kemenangan telak 3-0 atas Malaysia. Gol-gol dicetak oleh pemain muda yang menunjukkan performa impresif.',
                'image' => 'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?w=800',
                'is_trending' => true,
                'minutes_ago' => 120,
            ],
            [
                'category' => 'Ekonomi',
                'title' => 'Rupiah Menguat Tajam, Dolar AS Tembus Rp 15.500',
                'excerpt' => 'Nilai tukar rupiah menguat signifikan terhadap dolar Amerika Serikat.',
                'content' => 'Rupiah menguat tajam hari ini mencapai Rp 15.500 per dolar AS. Penguatan ini didorong oleh sentimen positif pasar terhadap kebijakan ekonomi pemerintah dan membaiknya kondisi ekonomi global.',
                'image' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=800',
                'minutes_ago' => 145,
            ],
            [
                'category' => 'Hiburan',
                'title' => 'Film Indonesia Raih Penghargaan di Festival Film Internasional',
                'excerpt' => 'Sineas Indonesia kembali mengharumkan nama bangsa di kancah internasional.',
                'content' => 'Film Indonesia berhasil meraih penghargaan bergengsi di festival film internasional. Pencapaian ini membuktikan kualitas perfilman Indonesia yang semakin diakui dunia.',
                'image' => 'https://images.unsplash.com/photo-1616530940355-351fabd9524b?w=800',
                'minutes_ago' => 178,
            ],
            [
                'category' => 'Terkini',
                'title' => 'Pemerintah Luncurkan Program Bantuan untuk UMKM',
                'excerpt' => 'Program stimulus baru diluncurkan untuk membantu pelaku usaha mikro, kecil, dan menengah.',
                'content' => 'Pemerintah resmi meluncurkan program bantuan baru untuk UMKM dengan total anggaran triliunan rupiah. Program ini diharapkan dapat membantu pemulihan ekonomi dan pertumbuhan usaha kecil menengah di seluruh Indonesia.',
                'image' => 'https://images.unsplash.com/photo-1556761175-b413da4baf72?w=800',
                'minutes_ago' => 200,
            ],
            [
                'category' => 'Populer',
                'title' => 'Tips Mengelola Keuangan di Masa Inflasi',
                'excerpt' => 'Strategi cerdas mengatur keuangan pribadi saat inflasi tinggi.',
                'content' => 'Para ahli keuangan memberikan berbagai tips untuk mengelola keuangan pribadi di tengah inflasi yang meningkat. Mulai dari mengatur prioritas pengeluaran, investasi yang tepat, hingga mencari sumber pendapatan tambahan.',
                'image' => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=800',
                'is_trending' => true,
                'minutes_ago' => 230,
            ],
        ];

        foreach ($articles as $articleData) {
            $category = Category::where('name', $articleData['category'])->first();
            
            Article::create([
                'category_id' => $category->id,
                'title' => $articleData['title'],
                'slug' => Str::slug($articleData['title']),
                'excerpt' => $articleData['excerpt'],
                'content' => $articleData['content'],
                'image' => $articleData['image'],
                'author' => 'Admin',
                'views' => rand(100, 5000),
                'is_trending' => $articleData['is_trending'] ?? false,
                'is_featured' => $articleData['is_featured'] ?? false,
                'published_at' => now()->subMinutes($articleData['minutes_ago']),
            ]);
        }
    }
}
