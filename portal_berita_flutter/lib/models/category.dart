class Category {
  final int id;
  final String name;
  final String slug;
  final int? articlesCount;

  Category({
    required this.id,
    required this.name,
    required this.slug,
    this.articlesCount,
  });

  factory Category.fromJson(Map<String, dynamic> json) {
    return Category(
      id: json['id'],
      name: json['name'],
      slug: json['slug'],
      articlesCount: json['articles_count'],
    );
  }
}
