# CREER UN PROJET SYMFONY BLOG

# ENTITY : User, Post, Comment

# User : propriété -> email, password, name(string), pseudo(string)

# Post : propriété -> title(string), content(text), image(string), createdAt(datetime_immutable)

# Comment : propriété -> post(relation : ManyToOne), user(relation : ManyToOne), content(string), createdAt(datetime_immutable)

1. Installer Symfony version 5.4
-> symfony new blog --webapp --version=5.4

2. Creer la bdd 
-> Configurer les variables d'environnement de la bdd
-> symfony console doctrine:database:create  ( symfony console d:d:c )

3. Mettre en place la page d'accueil
-> On créé un Controller : symfony console make:controller HomeController
-> On installe bootstrap dans le fichier base.html.twig
-> On mets en place la navbar

4. Mettre en place la page admin
-> On créé un AdminController : symfony console make:controller AdminController

5. Mettre en place l'entité Post
-> symfony console make:entity Post

6. Créer le crud pour l'entité Post
-> symfony console make:crud Post

7. Afficher les posts dans la page d'accueil du site

8. Créer l'entité User
-> symfony console make:user User

9. Créer le systeme d'auth
-> symfony console make:auth

10. Créer le systeme de register
-> symfony console make:registration-form

11. Changer l'affichage des liens dans la navbar en fonction du ROLE
-> Et aussi sécuriser l'administration

12. Créer l'entité Comment et le CRUD de Comment

13. Ajouter la fonctionnalité d'ajouter des commentaires par un user connecté