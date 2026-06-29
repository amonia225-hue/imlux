import 'dart:io' show Platform;

import 'package:firebase_core/firebase_core.dart';
import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter/foundation.dart';

import 'api_service.dart';

/// Gestionnaire de notifications push (Firebase Cloud Messaging).
///
/// Le service est tolérant aux pannes : tant que Firebase n'est pas
/// configuré (pas de google-services.json / GoogleService-Info.plist),
/// il reste inactif et l'application fonctionne normalement.
class NotificationService {
  NotificationService._();
  static final NotificationService instance = NotificationService._();

  bool _available = false;
  String? _token;

  static String get platformName {
    if (kIsWeb) return 'web';
    if (Platform.isIOS) return 'ios';
    return 'android';
  }

  /// À appeler au démarrage de l'app (dans main).
  Future<void> init() async {
    try {
      await Firebase.initializeApp();
      final messaging = FirebaseMessaging.instance;

      await messaging.requestPermission(alert: true, badge: true, sound: true);
      _token = await messaging.getToken();
      _available = true;

      messaging.onTokenRefresh.listen((t) {
        _token = t;
        syncToken();
      });
    } catch (e) {
      _available = false;
      debugPrint('FCM non configuré, notifications désactivées : $e');
    }
  }

  Future<String?> currentToken() async {
    if (!_available) return null;
    _token ??= await FirebaseMessaging.instance.getToken();
    return _token;
  }

  /// Enregistre le jeton de l'appareil côté serveur (si connecté).
  Future<void> syncToken() async {
    if (!_available || _token == null || ApiService.token == null) return;
    try {
      await ApiService.registerDevice(_token!, platformName);
    } catch (_) {
      // sans gravité : on réessaiera à la prochaine connexion
    }
  }
}
