# Guide d'installation — LORNY CONSEILS MANAGEMENT

Ce guide explique, pas à pas, comment installer et lancer l'application
(dashboard d'administration + espace client de suivi + API mobile).
Pour la mise en production rapide, voir aussi [`DEPLOY.md`](DEPLOY.md).

---

## 1. Ce dont vous avez besoin

| Outil | Version | Pourquoi |
|-------|---------|----------|
| PHP | 8.3 ou + | moteur de l'application |
| Composer | 2.x | dépendances PHP |
| Node.js + npm | 18 ou + | compilation des styles (Vite/Tailwind) |
| MySQL / MariaDB | 8+ / 10.4+ | base de données |
| Git | — | récupération du code |

> 💡 **Sous Windows avec Laragon** : tout est déjà fourni (PHP, MySQL, Node).
> Démarrez Laragon puis ouvrez un terminal dans le dossier du projet.

Extensions PHP requises : `pdo_mysql`, `mbstring`, `gd` (ou `imagick`),
`zip`, `intl`, `curl`, `openssl`.

---

## 2. Récupérer le code

```bash
git clone https://github.com/amonia225-hue/imlux.git
cd imlux
```

(ou copiez simplement le dossier du projet dans `C:\laragon\www\immo-gest`)

---

## 3. Installer les dépendances

```bash
composer install
npm install
```

---

## 4. Configuration de l'environnement

```bash
cp .env.example .env        # Windows : copy .env.example .env
php artisan key:generate
```

Ouvrez le fichier `.env` et renseignez la base de données :

```env
APP_NAME="Lorny Conseils Management"
APP_URL=http://localhost:8788

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=immo_gest
DB_USERNAME=root
DB_PASSWORD=
```

> Créez la base `immo_gest` vide avant l'étape suivante
> (via phpMyAdmin/HeidiSQL, ou `CREATE DATABASE immo_gest;`).

---

## 5. Base de données + données de démonstration

```bash
php artisan migrate
php artisan db:seed
php artisan storage:link
```

Le seed crée :
- le compte **administrateur**,
- 2 programmes, des îlots/lots, des adhérents et des versements de démonstration
  (uniquement en local — en production, seul l'admin est créé).

---

## 6. Compiler les styles de l'application

L'application utilise Vite (Tailwind). Compilez les assets :

```bash
npm run build       # production (génère public/build)
# — ou, en développement, avec rechargement automatique :
npm run dev
```

> ⚠️ La charte couleur de l'application (bleu/orange) est définie dans les
> vues Blade (`resources/views/admin`, `resources/views/auth`,
> `resources/views/consultation`). Après une modification de couleur,
> videz le cache des vues : `php artisan view:clear`.

---

## 7. Lancer l'application

```bash
php artisan serve --port=8788
```

Puis ouvrez **http://localhost:8788**.

| Espace | URL | Identifiants par défaut |
|--------|-----|--------------------------|
| Connexion admin | `/login` | `admin@immo-gest.ci` / `immogest2026` |
| Dashboard | `/admin/dashboard` | (après connexion admin) |
| Suivi client | `/suivi` | UID `IMM-2026-XXXX`, ou téléphone + date de naissance |
| Espace adhérent démo | `/login` | `client@imlux.ci` / `client2026` |

---

## 8. Charte graphique de l'application

L'application suit l'identité **LORNY** :

| Rôle | Couleur | Code |
|------|---------|------|
| Fond de l'application | Bleu clair | `#EEF1F7` |
| Bleu principal (sidebar, titres, dégradés) | Bleu royal / navy | `#1E40AF` → `#16329B` |
| Accent (boutons, liens, focus, badges) | Orange | `#ED8B1C` |
| Accent foncé | Orange foncé | `#C9710E` |

> Le **fond est bleu**, les **éléments d'action (boutons, accents) sont orange**.
> Le site vitrine public (`resources/views/public`) garde sa propre charte
> et n'est **pas** concerné par l'application.

---

## 9. Application mobile (Flutter) — optionnel

```bash
cd mobile
flutter pub get
flutter run                              # sur émulateur/appareil
# build de release :
flutter build apk --release --dart-define=API_BASE_URL=http://10.0.2.2:8788/api
```

---

## 10. Problèmes fréquents

| Symptôme | Solution |
|----------|----------|
| Page sans styles | `npm run build` puis vérifier que `public/build` existe |
| Ancienne couleur affichée | `php artisan view:clear` (cache des vues Blade) |
| `Connection refused` MySQL | démarrer MySQL/Laragon, vérifier le `.env` |
| Erreur `vendor/autoload` | relancer `composer install` |
| Images non affichées | `php artisan storage:link` |

---

Pour la mise en production (HTTPS, sécurité, sauvegardes, FCM),
suivez [`DEPLOY.md`](DEPLOY.md).
