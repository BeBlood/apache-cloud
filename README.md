# Introduction

Système de cloud

# Remarques

### Projet

Les scripts php ne sont pas optimisés (tout est fait en procédural) afin d'exploiter au minimum ce que nous offre php et tenter de n'utiliser que apache pour les gestions des utilisateurs. Nous n'utilisons donc pas la session, les varibles utilisateurs, les cookies, etc...

Nous aurions également pu utiliser "authn_dbd" pour la connexion avec une bdd, ce que nous avons tester mais nous avons abandonné pendant que ce n'était pas le but de l'exercice (nous n'utilisions plus le fichier .htpasswd alors qu'il était indiqué dans la présentation du TP).

Nous avons également ajouter une gestion des groupes (création, ajout d'utilisateur, gestion des fichiers, ...).

Il y a également un backoffice à votre disposition vers l'url /backoffice afin de tester pleinement l'application
Quelques particularités sur le backoffice:
    - Quand vous créer un utilisateur, son mot de passe est par défaut le même que le nom d'utilisateur donné.
    - Seul l'administrateur à accès au backoffice
    - Les groupes "admin" et "moderator" ne sont pas supprimable (pour des raisons de sécurité). Et l'utilisateur "admin" ne l'est pas non plus.

Accès administrateurs par défaut:
    - Username: admin
    - Password: admin

### Stack

La stack est configuré pour fonctionner avec apache et php-fpm pour la démarrer il faut construire dans un premier temps les images

```shell
$ make build # Cela peut prendre un peu de temps pour php
```

Une fois les images construites, démarrez la stack

```shell
$ make start
```

# Objectifs
    - [X] Créer un vhost "monsite-cloud.fr" de stockage de fichiers
    - [X] Créer les groupes "admin", "moderator" et "user" (Ils sont dynamique dans notre cas)
    - [X] Créer un dossier personnel pour chaque utilisateur
    - [X] Un administrateur ou un modérateur peut créer un nouvel utilisateur
    - [X] Seulement un administrateur peut supprimer un utilisateur
    - [X] Seulement un administrateur peut ajouter un utilisateur à un groupe
    - [X] Seulement un administrateur peut supprimer un utilisateur d'un groupe
    - [X] Tous les utilisateurs valides peuvent voir la liste des dossiers personnels
    - [X] Tous les utilisateurs valides peuvent voir la liste des groupes
    - [X] Les URL du site doivent valider le méthode HTTP utilisée
    - [X] Créer les urls (les paramètres de certaines routes sont à passer dans le body)
        # Users
        - [X] GET /users : Afficher la liste des dossiers personnels
        - [X] GET /users/{username} : Afficher le contenu d’un dossier personnel
        # Groups
        - [X] GET /groups : Afficher la liste des dossiers personnels
        - [X] GET /groups/{groupname} : Afficher le contenu d’un dossier personnel
        # Admin
        - [X] POST /users : Crée un nouvel utilisateur
        - [X] DELETE /users : Supprimer un utilisateur et son dossier
        - [X] DELETE /groups : Supprimer un groupe et son dossier
        - [X] PUT /users/{username}/group : Ajouter un utilisateur à un groupe
        - [X] DELETE /users/{username}/group : Supprimer un utilisateur à un groupe
        - [X] POST /user/{username}/file : Téléverse un fichier dans le dossier personnel
        - [X] DELETE /user/{username}/file/{filename} : Supprimer un fichier du dossier personnel
        - [X] POST /group/{groupname}/file : Téléverse un fichier dans le dossier personnel
        - [X] DELETE /group/{groupname}/file/{filename} : Supprimer un fichier du dossier personnel
    - [ ] Créer un repo github
    - [ ] Envoyé le lien du repo à kevinbalicot@gmail.com avec les noms des l'élèves
