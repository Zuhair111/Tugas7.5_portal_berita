class Comment {
  final int id;
  final int articleId;
  final int userId;
  final String content;
  final bool isApproved;
  final String createdAt;
  final User user;

  Comment({
    required this.id,
    required this.articleId,
    required this.userId,
    required this.content,
    required this.isApproved,
    required this.createdAt,
    required this.user,
  });

  factory Comment.fromJson(Map<String, dynamic> json) {
    return Comment(
      id: json['id'],
      articleId: json['article_id'],
      userId: json['user_id'],
      content: json['content'],
      isApproved: json['is_approved'] == 1 || json['is_approved'] == true,
      createdAt: json['created_at'],
      user: User.fromJson(json['user']),
    );
  }
}

class User {
  final int id;
  final String name;

  User({
    required this.id,
    required this.name,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'],
      name: json['name'],
    );
  }
}
