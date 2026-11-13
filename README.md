# Stage2024 — Boutique e-commerce

Application Laravel 10, Vue 3 et Bootstrap : catalogue, catégories, panier,
commandes, factures, livraisons et tableau de bord.

## Lancement sous Windows

1. Télécharger le projet avec **Code → Download ZIP**, puis extraire le ZIP,
   ou cloner ce dépôt.
2. Double-cliquer sur **`lancer-projet.bat`**.
3. Le navigateur s'ouvre sur **http://127.0.0.1:8000**.

Au premier démarrage, le lanceur récupère les outils manquants (PHP 8.3,
Composer et Node.js 24) dans le dossier local `runtime`, installe les dépendances
avec `composer install` et `npm ci`, puis compile le site. Les téléchargements des
outils sont vérifiés avec les empreintes SHA256 publiées par leurs éditeurs.
Une connexion Internet est nécessaire pour cette installation.

La base SQLite, les tables et les données de démonstration sont créées
automatiquement. **Aucune configuration MySQL n'est nécessaire.** Les données
restent enregistrées après fermeture. Pour arrêter, faire **Ctrl+C** dans la
fenêtre du serveur.

Windows 64 bits est requis. Si PHP indique qu'une DLL Visual C++ manque, installer
le redistribuable Microsoft Visual C++ x64. Les outils déjà installés sur le PC
peuvent être réutilisés.

## Comptes de démonstration

| Compte | Email | Mot de passe |
| --- | --- | --- |
| Administrateur | `admin@example.test` | `Demo2024!` |
| Client | `client@example.test` | `Demo2024!` |

Le lanceur sert l'application uniquement sur l'interface locale `127.0.0.1`.

## Installation et vérification

```bat
rem Installer sans demarrer le serveur
lancer-projet.bat --prepare

rem Verifier une installation existante
lancer-projet.bat --check

rem Lancer sans ouvrir le navigateur
lancer-projet.bat --no-browser
```

Après une modification du JavaScript ou du CSS, exécuter `npm run build`.
Les tests utilisent une base SQLite en mémoire :

```bat
php -d extension=pdo_sqlite vendor/phpunit/phpunit/phpunit
```

Le PHP utilisé doit être compatible avec le projet. Le lanceur choisit
automatiquement un PHP 8.1 minimum ; `PHP_BIN` permet de le sélectionner.

## Partager une copie prête à lancer

Double-cliquer sur **`partager-projet.bat`** pour générer une archive dans `dist`.
Elle contient les sources, les dépendances, les fichiers compilés et PHP Windows.
L'ami doit extraire l'archive avant de lancer le fichier `.bat`.

Les environnements privés, bases, sessions, caches et logs ne sont pas inclus dans
Git. Le mode de démonstration utilise `.env.portable` et
`database/portable.sqlite`, indépendamment du `.env` MySQL d'origine.

Voir [DEMARRAGE.md](DEMARRAGE.md) pour les détails.
