#Installation du projet

1. Installer le projet dans le dossier de votre choix.

2. Créer un virtual host pour ce projet :

        <VirtualHost *:80>
            DocumentRoot /path/to/MyEvents/web
            ServerName my-events.local
            ErrorLog /path/to/logs/my-events.local-error.log
            TransferLog /path/to/logs/my-events.local-access.log
            AddDefaultCharset UTF-8
        </VirtualHost>

    N'oubliez pas de renseigner votre fichier hosts :

        127.0.0.1  my-events.local

3. Lancer l'installation des dépendances du projet via composer :

        cd /path/to/MyEvents
        composer install

4. Installer les assets :

        php app/console assets:install web

5. Créer la BDD :

        php app/console doctrine:database:create
        php app/console doctrine:schema:create

L'installation est maintenant finie. Vous pouvez consulter le site en local a l'adresse [http://my-events.local/app_dev.php](http://my-events.local/app_dev.php)

#Installation de NodeJS pour le chat

1. Télécharger et installer NodeJs : [http://nodejs.org/](http://nodejs.org/)
2. Ouvrir un terminal et entrer :

        cd /path/to/MyEvents/web/js

        # installation des modules nécessaires
        npm install

        # création du serveur NodeJS
        node nodeServer.js

