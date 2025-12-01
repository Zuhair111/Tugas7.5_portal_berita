import 'dart:convert';
import 'package:http/http.dart' as http;
import '../models/comment.dart';
import '../config/api_config.dart';
import 'auth_service.dart';

class CommentService {
  static String get baseUrl => ApiConfig.baseUrl;
  final AuthService _authService = AuthService();

  // Ambil komentar untuk artikel tertentu (gunakan slug, bukan ID)
  Future<List<Comment>> getComments(String articleSlug) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/article/$articleSlug/comments'),
      );

      if (response.statusCode == 200) {
        final jsonData = jsonDecode(response.body);
        // Response format: {success: true, data: {data: [...], ...}}
        final paginatedData = jsonData['data'];
        final List<dynamic> commentsJson = paginatedData['data'];
        return commentsJson.map((json) => Comment.fromJson(json)).toList();
      } else {
        throw Exception('Gagal memuat komentar');
      }
    } catch (e) {
      throw Exception('Error: $e');
    }
  }

  // Post komentar baru (memerlukan login)
  Future<Map<String, dynamic>> postComment(String articleSlug, String content) async {
    try {
      final token = await _authService.getToken();
      
      if (token == null) {
        return {'success': false, 'message': 'Silakan login terlebih dahulu'};
      }

      final response = await http.post(
        Uri.parse('$baseUrl/article/$articleSlug/comments'),
        headers: ApiConfig.getHeaders(token: token),
        body: jsonEncode({
          'content': content,
        }),
      );

      if (response.statusCode == 201) {
        final data = jsonDecode(response.body);
        return {
          'success': true,
          'message': data['message'],
          'comment': Comment.fromJson(data['data']),
        };
      } else if (response.statusCode == 401) {
        // Token expired or invalid - logout user
        await _authService.logout();
        return {'success': false, 'message': 'Sesi Anda berakhir. Silakan login kembali', 'logout': true};
      } else {
        final error = jsonDecode(response.body);
        return {'success': false, 'message': error['message'] ?? 'Gagal mengirim komentar'};
      }
    } catch (e) {
      return {'success': false, 'message': 'Error: $e'};
    }
  }

  // Hapus komentar (hanya pemilik komentar)
  Future<Map<String, dynamic>> deleteComment(int commentId) async {
    try {
      final token = await _authService.getToken();
      
      if (token == null) {
        return {'success': false, 'message': 'Silakan login terlebih dahulu'};
      }

      final response = await http.delete(
        Uri.parse('$baseUrl/comments/$commentId'),
        headers: ApiConfig.getHeaders(token: token),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        return {'success': true, 'message': data['message']};
      } else if (response.statusCode == 401) {
        // Token expired or invalid - logout user
        await _authService.logout();
        return {'success': false, 'message': 'Sesi Anda berakhir. Silakan login kembali', 'logout': true};
      } else {
        final error = jsonDecode(response.body);
        return {'success': false, 'message': error['message'] ?? 'Gagal menghapus komentar'};
      }
    } catch (e) {
      return {'success': false, 'message': 'Error: $e'};
    }
  }
}
