class ApiConfig {
  // PENTING: Pilih base URL sesuai kebutuhan
  
  // Laragon Apache untuk browser/localhost
  static const String laragonApache = 'http://localhost/projekberita/projeklaravel1/public/api/v1';
  
  // Untuk Android Emulator dengan Laragon
  static const String androidEmulator = 'http://10.0.2.2/projekberita/projeklaravel1/public/api/v1';
  
  // Untuk iOS Simulator atau browser
  static const String iosSimulator = 'http://127.0.0.1:8000/api/v1';
  
  // Untuk Physical Device (ganti dengan IP komputer Anda)
  static const String physicalDevice = 'http://192.168.100.35/projekberita/projeklaravel1/public/api/v1';
  
  // Base URL yang digunakan - untuk physical device Android
  static const String baseUrl = physicalDevice;
  
  // Timeout duration
  static const Duration timeoutDuration = Duration(seconds: 30);
  
  // Helper method untuk mendapatkan headers
  static Map<String, String> getHeaders({String? token}) {
    final headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    };
    
    if (token != null) {
      headers['Authorization'] = 'Bearer $token';
    }
    
    return headers;
  }
}
