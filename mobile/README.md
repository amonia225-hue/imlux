# IM'LUX — Application mobile souscripteur

Application Flutter de l'espace souscripteur **PROJET IM'LUX**.
Permet de consulter ses souscriptions, l'historique des versements, de télécharger
les attestations/reçus PDF et de suivre l'avancement du chantier.

## Stack
- Flutter (Dart 3.11+), Material 3, thème « Or & Émeraude »
- `provider` (état), `http` (API), `flutter_secure_storage` (jeton)
- `google_fonts` (Cormorant Garamond + Inter), `cached_network_image`, `url_launcher`
- `firebase_core` + `firebase_messaging` (notifications push — voir plus bas)

## Configuration de l'API
L'URL de l'API est dans `lib/config.dart` (`AppConfig.apiBaseUrl`).

| Contexte | URL |
|---|---|
| Émulateur Android | `http://10.0.2.2:8788/api` (défaut) |
| Appareil physique (dev) | `http://IP_LAN_DU_PC:8788/api` |
| Production | `https://imlux.ci/api` |

Surcharge possible au build, sans modifier le code :
```bash
flutter run --dart-define=API_BASE_URL=https://imlux.ci/api
```

## Lancer en développement
```bash
cd mobile
flutter pub get
flutter run
```
Compte de démo : **client@imlux.ci** / **client2026**
(le serveur Laravel doit tourner : `php artisan serve --port=8788`).

## Build de production
```bash
flutter build apk --release --dart-define=API_BASE_URL=https://imlux.ci/api
# ou App Bundle pour le Play Store :
flutter build appbundle --release --dart-define=API_BASE_URL=https://imlux.ci/api
```

## Activer les notifications push (FCM)
Le code FCM est déjà intégré ; il reste **inactif** tant que Firebase n'est pas branché
(l'app fonctionne normalement sans). Pour l'activer :

1. Créer un projet sur https://console.firebase.google.com
2. Ajouter une app Android (`ci.imlux.app`) → télécharger `google-services.json`
   dans `android/app/`, et appliquer le plugin Google Services au build Gradle.
   (idem iOS : `GoogleService-Info.plist`).
3. Côté serveur Laravel, renseigner dans `.env` :
   ```
   FCM_PROJECT_ID=<id-du-projet>
   FCM_CREDENTIALS=storage/app/firebase/service-account.json
   ```
   et déposer le JSON du **compte de service** Firebase à ce chemin.

Une fois configuré : notification automatique à chaque **versement enregistré**
et à chaque **étape de chantier terminée**.

## Structure
```
lib/
  config.dart                  URL de l'API, constantes
  theme/app_theme.dart         charte Or & Émeraude
  models/                      Souscripteur, Souscription, Versement, Etape, Travaux
  services/
    api_service.dart           client HTTP
    auth_service.dart          connexion / session (jeton sécurisé)
    notification_service.dart  FCM (tolérant à l'absence de config)
  screens/
    splash_screen.dart
    login_screen.dart
    home_screen.dart           profil + liste des biens
    souscription_detail_screen.dart  onglets Versements / Travaux / Infos
  widgets/ui.dart              composants (logo, bouton or, barres, puces)
```
