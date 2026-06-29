# Déploiement — PROJET IM'LUX

Checklist de mise en production du backend Laravel (web + API mobile).

## 1. Prérequis serveur
- PHP 8.3+ avec extensions : `pdo_mysql`, `mbstring`, `gd` (ou `imagick`), `zip`, `intl`, `curl`, `openssl`
- MySQL 8+
- Composer, Node.js (build des assets)
- HTTPS (certificat SSL) — **obligatoire** (l'app force le HTTPS en production)

## 2. Configuration
```bash
cp .env.production.example .env
php artisan key:generate
# Éditer .env : DB, MAIL, APP_URL, FCM...
```
Vérifier impérativement :
- `APP_ENV=production` et `APP_DEBUG=false`
- `APP_URL=https://...`
- Identifiants BDD et `SESSION_SECURE_COOKIE=true`

## 3. Installation
```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan db:seed --force        # crée l'admin (PAS les données de démo en prod)
php artisan storage:link
```

## 4. Optimisation (à relancer après chaque déploiement)
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```
> Sur cPanel/ModSecurity : si les requêtes PATCH/DELETE sont bloquées, l'app utilise déjà des routes POST. Lancer `php artisan optimize:clear` après chaque `git pull`.

## 5. Sécurité — à faire absolument
- [ ] **Changer le mot de passe admin** (le seed crée `admin@immo-gest.ci` / `immogest2026`)
- [ ] `APP_DEBUG=false`
- [ ] HTTPS actif + redirection http→https au niveau serveur
- [ ] Droits fichiers : `storage/` et `bootstrap/cache/` accessibles en écriture, le reste en lecture seule
- [ ] Le docroot pointe sur `public/` (jamais la racine du projet)

## 6. Sauvegardes BDD
```bash
php artisan db:backup           # sauvegarde manuelle -> storage/app/backups
```
Sauvegarde automatique quotidienne (02h00) via le planificateur. Ajouter le cron :
```
* * * * * cd /chemin/du/projet && php artisan schedule:run >> /dev/null 2>&1
```
Définir `MYSQLDUMP_PATH` dans `.env` si `mysqldump` n'est pas dans le PATH.

## 7. Notifications push (FCM) — optionnel
1. Créer un projet sur https://console.firebase.google.com
2. Déposer le JSON du compte de service dans `storage/app/firebase/service-account.json`
3. Renseigner `FCM_PROJECT_ID` et `FCM_CREDENTIALS` dans `.env`
4. App mobile : ajouter `google-services.json` (Android) / `GoogleService-Info.plist` (iOS)

## 8. Application mobile
```bash
cd mobile
flutter build apk --release --dart-define=API_BASE_URL=https://imlux.ci/api
# ou App Bundle pour le Play Store :
flutter build appbundle --release --dart-define=API_BASE_URL=https://imlux.ci/api
```

## 9. Vérifications post-déploiement
- [ ] `https://imlux.ci/login` s'affiche, connexion admin OK
- [ ] `https://imlux.ci/up` (health check) répond 200
- [ ] `https://imlux.ci/api/login` répond (test avec un compte souscripteur)
- [ ] Génération d'un PDF (facture/attestation/reçu frais) OK
- [ ] `php artisan db:backup` fonctionne
