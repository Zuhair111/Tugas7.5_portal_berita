import 'dart:convert';
import 'package:http/http.dart' as http;
import '../models/article.dart';
import '../models/category.dart';
import '../config/api_config.dart';

class ApiService {
  static String get baseUrl => ApiConfig.baseUrl;
  static Duration get timeoutDuration => ApiConfig.timeoutDuration;

  Future<Map<String, dynamic>> getHomePage() async {
    try {
      print('🔍 API Request: $baseUrl/news');
      final response = await http
          .get(Uri.parse('$baseUrl/news'))
          .timeout(timeoutDuration);

      print('📡 Response Status: ${response.statusCode}');
      
      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        return {
          'featured': data['data']['featured'] != null
              ? Article.fromJson(data['data']['featured'])
              : null,
          'trending': (data['data']['trending'] as List)
              .map((json) => Article.fromJson(json))
              .toList(),
          'latest': (data['data']['latest'] as List)
              .map((json) => Article.fromJson(json))
              .toList(),
          'categories': (data['data']['categories'] as List)
              .map((json) => Category.fromJson(json))
              .toList(),
        };
      } else {
        print('❌ Error Response: ${response.body}');
        throw Exception('Failed to load news: ${response.statusCode}');
      }
    } catch (e) {
      print('❌ Exception: $e');
      throw Exception('Error: $e');
    }
  }

  Future<List<Category>> getCategories() async {
    try {
      final response = await http.get(Uri.parse('$baseUrl/categories'));

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        return (data['data'] as List)
            .map((json) => Category.fromJson(json))
            .toList();
      } else {
        throw Exception('Failed to load categories');
      }
    } catch (e) {
      throw Exception('Error: $e');
    }
  }

  Future<Map<String, dynamic>> getArticle(String slug) async {
    try {
      final response = await http.get(Uri.parse('$baseUrl/article/$slug'));

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        return {
          'article': Article.fromJson(data['data']['article']),
          'related': (data['data']['related'] as List)
              .map((json) => Article.fromJson(json))
              .toList(),
        };
      } else {
        throw Exception('Failed to load article');
      }
    } catch (e) {
      throw Exception('Error: $e');
    }
  }

  Future<List<Article>> searchArticles(String query) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/search?q=$query'),
      );

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        return (data['data']['data'] as List)
            .map((json) => Article.fromJson(json))
            .toList();
      } else {
        throw Exception('Failed to search articles');
      }
    } catch (e) {
      throw Exception('Error: $e');
    }
  }

  Future<List<Article>> getCategoryArticles(String slug) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/category/$slug'),
      );

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        return (data['data']['articles']['data'] as List)
            .map((json) => Article.fromJson(json))
            .toList();
      } else {
        throw Exception('Failed to load category articles');
      }
    } catch (e) {
      throw Exception('Error: $e');
    }
  }

  // Alias untuk getCategoryArticles
  Future<List<Article>> getArticlesByCategory(String slug) async {
    return getCategoryArticles(slug);
  }

  Future<List<Article>> getTrendingArticles() async {
    try {
      final response = await http.get(Uri.parse('$baseUrl/trending'));

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        return (data['data']['data'] as List)
            .map((json) => Article.fromJson(json))
            .toList();
      } else {
        throw Exception('Failed to load trending articles');
      }
    } catch (e) {
      throw Exception('Error: $e');
    }
  }
}
