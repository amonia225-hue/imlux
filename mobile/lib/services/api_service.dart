import 'dart:convert';
import 'package:http/http.dart' as http;

import '../config.dart';
import '../models/app_notification.dart';
import '../models/souscription.dart';
import '../models/souscripteur.dart';

class ApiException implements Exception {
  final String message;
  final int? statusCode;
  ApiException(this.message, [this.statusCode]);
  @override
  String toString() => message;
}

/// Client HTTP de l'API IM'LUX.
class ApiService {
  static String? token;

  static Map<String, String> get _headers => {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        if (token != null) 'Authorization': 'Bearer $token',
      };

  static Uri _uri(String path) => Uri.parse('${AppConfig.apiBaseUrl}$path');

  static Future<dynamic> _decode(http.Response res) async {
    dynamic body;
    try {
      body = jsonDecode(res.body);
    } catch (_) {
      body = null;
    }

    if (res.statusCode >= 200 && res.statusCode < 300) {
      return body;
    }

    final msg = body is Map && body['message'] != null
        ? body['message'].toString()
        : 'Une erreur est survenue (${res.statusCode}).';
    throw ApiException(msg, res.statusCode);
  }

  // ===== Auth =====
  static Future<Map<String, dynamic>> login(
    String email,
    String password, {
    String? fcmToken,
    String? platform,
  }) async {
    final res = await http.post(
      _uri('/login'),
      headers: _headers,
      body: jsonEncode({
        'email': email,
        'password': password,
        'device_name': 'mobile',
        if (fcmToken != null) 'fcm_token': fcmToken,
        if (platform != null) 'platform': platform,
      }),
    );
    final data = await _decode(res);
    return data as Map<String, dynamic>;
  }

  static Future<Souscripteur> me() async {
    final res = await http.get(_uri('/me'), headers: _headers);
    return Souscripteur.fromJson(await _decode(res));
  }

  static Future<void> logout() async {
    try {
      await http.post(_uri('/logout'), headers: _headers);
    } catch (_) {
      // on ignore : la session locale sera de toute façon effacée
    }
  }

  // ===== Souscriptions =====
  static Future<List<Souscription>> souscriptions() async {
    final res = await http.get(_uri('/souscriptions'), headers: _headers);
    final data = await _decode(res);
    return ((data['data'] ?? []) as List)
        .map((e) => Souscription.fromJson(e))
        .toList();
  }

  static Future<Souscription> souscription(int id) async {
    final res = await http.get(_uri('/souscriptions/$id'), headers: _headers);
    return Souscription.fromJson(await _decode(res));
  }

  // ===== Notifications =====
  static Future<List<AppNotification>> notifications() async {
    final res = await http.get(_uri('/notifications'), headers: _headers);
    final data = await _decode(res);
    return ((data['data'] ?? []) as List)
        .map((e) => AppNotification.fromJson(e))
        .toList();
  }

  static Future<void> markNotificationsRead({int? id}) async {
    await http.post(
      _uri('/notifications/read'),
      headers: _headers,
      body: jsonEncode(id != null ? {'id': id} : {}),
    );
  }

  // ===== Appareils (FCM) =====
  static Future<void> registerDevice(String deviceToken, String platform) async {
    await http.post(
      _uri('/device-tokens'),
      headers: _headers,
      body: jsonEncode({'token': deviceToken, 'platform': platform}),
    );
  }
}
