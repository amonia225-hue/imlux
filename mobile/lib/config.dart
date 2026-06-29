/// Configuration de l'application IM'LUX.
class AppConfig {
  /// URL de base de l'API.
  ///
  /// - Émulateur Android : http://10.0.2.2:8788/api  (10.0.2.2 = localhost de la machine hôte)
  /// - Appareil physique en dev : `http://IP_LAN_DU_PC:8788/api`
  /// - Production : https://imlux.ci/api
  ///
  /// Surchargable au build : flutter run --dart-define=API_BASE_URL=https://imlux.ci/api
  static const String apiBaseUrl = String.fromEnvironment(
    'API_BASE_URL',
    defaultValue: 'http://10.0.2.2:8788/api',
  );

  static const String appName = "PROJET IM'LUX";
  static const String appTagline = 'Logements & Gestion';
}
