import 'category.dart';
import 'package:intl/intl.dart';

class Article {
  final int id;
  final String title;
  final String slug;
  final String excerpt;
  final String content;
  final String? image;
  final String author;
  final int views;
  final bool isTrending;
  final bool isFeatured;
  final DateTime publishedAt;
  final Category category;

  Article({
    required this.id,
    required this.title,
    required this.slug,
    required this.excerpt,
    required this.content,
    this.image,
    required this.author,
    required this.views,
    required this.isTrending,
    required this.isFeatured,
    required this.publishedAt,
    required this.category,
  });

  factory Article.fromJson(Map<String, dynamic> json) {
    return Article(
      id: json['id'],
      title: json['title'],
      slug: json['slug'],
      excerpt: json['excerpt'],
      content: json['content'],
      image: json['image'],
      author: json['author'],
      views: json['views'],
      isTrending: json['is_trending'] ?? false,
      isFeatured: json['is_featured'] ?? false,
      publishedAt: DateTime.parse(json['published_at']),
      category: Category.fromJson(json['category']),
    );
  }

  String get timeAgo {
    final now = DateTime.now();
    final difference = now.difference(publishedAt);

    if (difference.inMinutes < 60) {
      return '${difference.inMinutes} menit lalu';
    } else if (difference.inHours < 24) {
      return '${difference.inHours} jam lalu';
    } else {
      return '${difference.inDays} hari lalu';
    }
  }

  String get formattedDate {
    return DateFormat('d MMM yyyy, HH:mm', 'id_ID').format(publishedAt);
  }
}
