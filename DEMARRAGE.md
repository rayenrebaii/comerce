# Lancer et partager la boutique

## Lancer directement

Double-cliquer sur **lancer-projet.bat**. Le navigateur s'ouvre sur
**http://127.0.0.1:8000** lorsque le serveur est prêt.

Au premier lancement, le script crée une configuration `.env.portable`, une base
SQLite `database/portable.sqlite`, les tables manquantes et quelques produits.
Les données sont conservées aux lancements suivants. Aucun serveur MySQL n'est
nécessaire. La configuration `.env` et la base MySQL d'origine restent disponibles
pour le développement habituel avec Artisan.

| Compte de démonstration | Email | Mot de passe |
| --- | --- | --- |
| Administrateur | admin@example.test | Demo2024! |
| Client | client@example.test | Demo2024! |

Pour arrêter le serveur, faire **Ctrl+C**, puis fermer la fenêtre.

## Donner le projet à un ami

1. Double-cliquer sur **partager-projet.bat**.
2. Envoyer le fichier **dist/stage2024-partage-… .zip** créé par le script.
3. L'ami doit **extraire entièrement le ZIP**, puis double-cliquer sur
   **stage2024/lancer-projet.bat**. Ne pas lancer le fichier depuis l'intérieur du ZIP.

L'archive contient le code source, les dépendances PHP, les fichiers JavaScript/CSS
compilés et PHP Windows 64 bits. L'ami n'a donc pas à installer Composer, Node.js,
XAMPP ou MySQL. Les bases, mots de passe personnels, `.env`, logs, sessions et
caches de ta copie sont exclus. Une nouvelle clé et une nouvelle base de
démonstration sont générées chez l'ami.

Le PC destinataire doit être sous Windows 64 bits. Si Windows signale que
`VCRUNTIME140.dll` manque, installer le redistribuable Microsoft Visual C++ x64
requis par PHP. Le site utilise aussi certaines ressources externes : leur
affichage peut nécessiter une connexion Internet. Le serveur et les comptes de
démonstration sont prévus pour un usage local.

## Commandes utiles

```bat
rem Installer/configurer sans ouvrir le navigateur ni demarrer le serveur
lancer-projet.bat --prepare

rem Verifier les fichiers et l'etat des migrations
lancer-projet.bat --check

rem Demarrer sans ouverture automatique du navigateur
lancer-projet.bat --no-browser
```

Le lanceur privilégie `runtime/php/php.exe` s'il est inclus, puis recherche PHP
8.1 ou 8.2 dans le PATH, Laragon et XAMPP. Pour choisir un exécutable précis :

```bat
set "PHP_BIN=C:\chemin\vers\php.exe"
lancer-projet.bat
```

Sur une copie du code sans dépendances, le lanceur télécharge les outils manquants
(PHP, Composer, Node.js) dans `runtime`, puis lance `composer install`, `npm ci`
et `npm run build`. Ces téléchargements nécessitent Internet et leurs empreintes
SHA256 officielles sont vérifiées. Aucun outil n'est installé globalement.
Après modification du JavaScript ou du CSS, refaire `npm run build`, puis recréer
l'archive. Le lanceur utilise les fichiers compilés, sans serveur Vite séparé.

Pour travailler avec Artisan sur la base portable, employer `--env=portable` et
un PHP avec `pdo_sqlite` activé. Par exemple :

```bat
php -d extension=pdo_sqlite artisan migrate:status --env=portable
php -d extension=pdo_sqlite vendor/phpunit/phpunit/phpunit
```

Si le port 8000 est occupé, arrêter l'autre serveur avant de relancer.
Pour les erreurs de l'application, consulter `storage/logs/laravel.log`.
