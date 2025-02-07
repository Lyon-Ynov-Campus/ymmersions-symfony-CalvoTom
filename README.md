# 🚀 **MVP**

Bienvenue dans **MVP**, une application pour participer a des tournois divers et varier dans le monde de l'e-sport. Explorez, jouez, crée et bien plus encore. Devenez le MVP !

## ⚙️ **Features**

- **Rejoindre des tournois avec des équipes** : Inscris-toi à des tournois existants et rejoins des équipes pour participer à des compétitions.

- **Participer à des matchs** : Prends part à des matchs excitants et suis les résultats en temps réel.

- **Créer des tournois** : Organise tes propres tournois et invite des équipes à participer  grace au compte admin.

- **Gérer les matchs** : Consulte et gérez les matchs, y compris la planification, la modification des résultats et l'ajout de nouveaux matchs grace au compte admin.

## 🔧 **Installation**

1. **Cloner le dépôt** :
    ```bash
    git clone git@github.com:TonNom/  ProjetMVP-Symfony.git
    ```

2. **Naviguer dans le répertoire** :
    ```bash
    cd ProjetMVP-Symfony
    ```

3. **Installer les dépendances avec Composer** :
    ```bash
    composer install
    ```

4. Exécuter les migrations pour créer le schéma de la base de données :

    ```bash
    php bin/console console d:d:c
    php bin/console d:s:u -f
    ```

5. Charger les fixtures (données de test) :
    ```bash
    php bin/console doctrine:fixtures:load
    ``` 
Cela va charger des données par défaut dans la base de données, telles que des matchs, des équipes et des utilisateurs pour tester l'application. Attention ajouter votre lien database dans le fichier .env !
## 📊 Utilisation

Démarrer le serveur Symfony :
```bash
symfony server:start
```

Accéder à l'interface d'administration :

Ouvrez un navigateur et allez sur :
http://localhost:8000/landing.

Explorer les fonctionnalités :

Gérez les matchs et les résultats des tournois.
Ajoutez de nouvelles équipes et associez-les à des tournois.
Modifiez les informations des utilisateurs et des inscriptions.

Gérer les entités via EasyAdmin :

L'interface vous permettra de créer, modifier et supprimer des MATCHS, des EQUIPES, des TOURNAMENTS et des USERS.

Pour tester l'application pus en profondeur un compte admin est mis a votre disposition :

Nom : **Admin**
Mdp : **Admin**

## ✏ Authors

- [@CalvoTom](https://www.github.com/CalvoTom)

