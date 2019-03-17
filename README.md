# Docker Apache2

Vous avez besoin de Docker pour faire fonctionner le serveur HTTP dans un terminal bash

Présentation sur [Google drive](https://docs.google.com/presentation/d/179TCChjhxwXM-YmV00hwJKBQFr5-L1mUE66mmZ-J-AQ/edit?usp=sharing)

## Installation

Lancer le conteneur

```
$ ./start.sh
```

Lancer Apache2 dans le conteneur Docker

```
$ apachectl start
```

Votre site sera disponible via `http://localhost:8080`; ATTENTION, le conteneur docker écoute le port 8080 !

### TP1

### Exercice 1

Objectif : Créer un dossier `data` accessible via `http://localhost:8080/data` en libre lecture du contenu.

Pour ce faire vous devez :
 - Créer un dossier `data` dans `html`
 - Ajouter du contenu dans le dossier `data` (un fichier .txt, une image etc ...)
 - Modifier le fichier de configuration `default.conf` dans `site-available`
 - Activer votre configuration via la commande `a2ensite`
 - Recharger votre configuration via `service apache2 reload`

### Exercice 2

Objectif : Créer un dossier `private` accessible via `http://localhost:8080/private` qui indexe seulement une liste de fichiers

Pour ce faire vous devez :
 - Créer un dossier `private` dans `html`
 - Ajouter du contenu dans le dossier `private` (au moins 3 fichiers)
 - Modifier le fichier de configuration `default.conf` dans `site-available`
  - On doit pouvoir lister 2 fichiers sur 3 (le 3ème doit être interdit de lecture)
 - Recharger votre configuration via `service apache2 reload`

### Exercice 3

Objectif : Créer un dossier `profiles` accessible via `http://localhost:8080/profiles` qui indexe une liste de dossiers de profils

Pour ce faire vous devez :
 - Créer un dossier `profiles` dans `html`
 - Créer 3 sous-dossiers dans `profiles` : `Jean`, `Joe`, `Janne`
 - Ajouter du contenu dans chaque sous-dossier
 - `Jean` doit être indexable
 - `Joe` doit avoir une page par défaut autre que `index.html` (exemple : `home.html`) accessible via `http://localhost:8080/profiles/joe`
 - `Janne` ne doit pas être accessible
  - La page de 403 doit être une page customisée (403.html)
 - La racine `Profiles` ne doit pas être indexable
 - Pour cette exercice, vous devez utiliser la directive `Location`

## TP2

Objectif : Créer un dossier `security` avec 3 sous-dossier `admin`, `jean` et `janne` et sécuriser le dossier `admin` pour le groupe `admin`, le dossier `jean` pour l'utilisateur `jean` et le dossier `janne` pour l'utilisatrice `janne`. Le tout accessible via `http://localhost:8080/security/admin|jean|janne`

Pour ce faire vous devez :
 - Activer le module `authz_groupfile` via `a2enmod`
 - Créer le dossier `security` dans `html`
 - Créer les sous-dossiers `admin`, `jean` et `janne` dans `security`
 - Créer un fichier `users` via `htpasswd` dans `html`
 - Créer un fichier `groups` avec un groupe `admin` et les utilisateurs `jean` et `janne`
 - Utiliser les directives `Auth*` pour sécuriser les 3 sous-dossiers
 - Le dossier `security` doit être indexable pas n'importe quel utilisateur valide
 - BONUS : Faire le TP avec le moins de directive `Auth*` possible

## TP3

### Exercice 1

Objectif : Créer 2 sites `monsite-a.fr:8080` et `monsite-b.fr:8080` contenu dans les dossiers `a` et `b` chacun avec leur fichiers de logs.

Pour ce faire vous devez :
 - Créer 2 dossiers `a` et `b` dans `html`
 - Configurer pour faire pointer `monsite-a.fr` vers `a` et `monsite-b.fr` vers `b`
 - Configurer les logs `a-error.log` et `a.log` pour le site `monsite-a.fr` et les logs `b-error.log` et `b.log` pour le site `monsite-b.fr`
 - Vous devez utiliser la directive `VirtualHost`
 - ATTENTION, modifiez votre fichier `hosts` (`/etc/hosts` sur linux) pour faire pointer `monsite-a.fr` et `monsite-b.fr` sur `127.0.0.1`

### Exercice 2

Objectif : Créer un site `monsite-php.fr:8080` qui afficher une page HTML via PHP

Pour ce faire vous devez :
 - Installer `php` dans le conteneur Docker (`apt install php`)
 - Créer un dossier `php` dans `html`
 - Faire pointer `monsite-php.fr` sur `php`
 - Créer un fichier `index.php` qui fait un `phpinfo();`
 - Le site `monsite-php.fr` doit avoir la page par défaut `index.php`

## TP4

Objectif : Créer un site `monsite-info.fr:8080` contenu dans `info` et réécrire les URL de ce format : `monsite-info.fr/info/{user}` en ce chemin `monsite-info.fr/index.php?user={user}&ip={ip}`

Pour ce faire vous devez :
 - Activer le module `rewrite`
 - Créer un dossier `info` dans `html`
 - Faire pointer `monsite-info.fr` sur `info`
 - Créer un fichier `index.php` qui affiche les infos suivantes : `$_GET['user']` et `$_GET['ip']`
 - Utiliser `RewriteRule` pour transformer `/info/{user}` en `index.php?user={user}&ip={ip}`
 - Utiliser `RewriteCond` avec `%{REMOTE_ADDR}` pour avoir l'IP du client
