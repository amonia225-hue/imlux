import 'package:flutter/foundation.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

import '../models/souscripteur.dart';
import 'api_service.dart';
import 'notification_service.dart';

enum AuthStatus { unknown, authenticated, unauthenticated }

class AuthService extends ChangeNotifier {
  static const _storage = FlutterSecureStorage();
  static const _tokenKey = 'imlux_token';

  AuthStatus status = AuthStatus.unknown;
  Souscripteur? souscripteur;

  /// Au démarrage : restaure la session si un jeton est stocké.
  Future<void> bootstrap() async {
    final saved = await _storage.read(key: _tokenKey);
    if (saved == null) {
      status = AuthStatus.unauthenticated;
      notifyListeners();
      return;
    }

    ApiService.token = saved;
    try {
      souscripteur = await ApiService.me();
      status = AuthStatus.authenticated;
      NotificationService.instance.syncToken();
    } catch (_) {
      await _clear();
      status = AuthStatus.unauthenticated;
    }
    notifyListeners();
  }

  Future<void> login(String email, String password) async {
    final fcmToken = await NotificationService.instance.currentToken();
    final data = await ApiService.login(
      email.trim(),
      password,
      fcmToken: fcmToken,
      platform: NotificationService.platformName,
    );

    final token = data['token'] as String;
    await _storage.write(key: _tokenKey, value: token);
    ApiService.token = token;
    souscripteur = Souscripteur.fromJson(data['souscripteur']);
    status = AuthStatus.authenticated;
    NotificationService.instance.syncToken();
    notifyListeners();
  }

  Future<void> logout() async {
    await ApiService.logout();
    await _clear();
    status = AuthStatus.unauthenticated;
    notifyListeners();
  }

  Future<void> _clear() async {
    await _storage.delete(key: _tokenKey);
    ApiService.token = null;
    souscripteur = null;
  }
}
