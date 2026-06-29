/// Configuration de l'application IM'LUX.
class AppConfig {
  /// URL de base de l'API.
  ///
  /// - Production : https://imlux.lornyconseils.com/api  (par défaut)
  /// - Émulateur Android en dev : surcharger avec http://10.0.2.2:8788/api
  /// - Appareil physique en dev : http://IP_LAN_DU_PC:8788/api
  ///
  /// Surchargable au build :
  ///   flutter run --dart-define=API_BASE_URL=http://10.0.2.2:8788/api   (dev)
  static const String apiBaseUrl = String.fromEnvironment(
    'API_BASE_URL',
    defaultValue: 'https://imlux.lornyconseils.com/api',
  );

  static const String appName = "PROJET IM'LUX";
  static const String appTagline = 'Logements & Gestion';
}
