**P6_SnowTricks** 
#### Développez de A à Z le site communautaire SnowTricks ####

### Environnement du développement ###


<ul>
<li> Symfony CLI version v4.24.1 (c) 2017-2021 Symfony SAS </li>
<li> @symfony/webpack-encore 1.0.0"</li>
<li> webpack-notifier 1.6.0</li>
<li> Yarn install v1.22.5</li>
<li> npm version 6.14.13</li>
<li> Wampserver - MySql 5.7.31 </li>
<li> PhpMAdmin 5.0.2</li>
<li> Apache 2.4.46</li>
<li> PHP 7.4.9</li>


</ul>

 require php >= 7.2.5"
 fzaninotto/faker 1.9

 
 Name "data base": <strong>projet6</strong>
 
 ### Installation du projet: ###
 
 1. Clonez ou téléchargez le repository GitHub dans le dossier :
  git clone https://github.com/pascalinecte91/P6_SnowTricks.git
  
 2. Téléchargez et installez les dépendances back-end du projet avec Yarn :
    - Yarn install
    
 3. Téléchargez et installez les dépendances front-end du projet avec Npm :
    - npm install
    
 4. Créer un build d'assets (avec Webpack Encore) avec npm :
    - npm run build


Si vous souhaitez  faire fonctionner Mailhog pour les reinitialisations:
https://github.com/mailhog/MailHog/releases/download/v1.0.1/MailHog_windows_amd64.exe
Mailhog pour windows
    
 #### Connexion au site ####
- ONGLET  Connexion  Administrator : 
  - LOGIN : pascaline
  - Email : pascaline@gmail.com
  - Mot de passe : 999999
 
- ONGLET  Connexion  User : 
  - LOGIN : bravo
  - Email : bravo@gmail.com
  - Mot de passe : 123456
 
 ### Commandes Symfony pour installations diverses ###
 
 - Creation de la Data base
    - php bin/console doctrine:database:create
 - Faire les migrations
    - php bin/console make:migration
    - php bin/console doctrine:migrations migrate
 - Faire les "fixtures"
    - symfony console doctrine:fixtures:load
 
 - Vider cache si besoin
    - php bin/console c:c   ( cache:clear )
    
 - Voir toutes les routes du projet
    - php bin/console debug:router

###Lancer yarn watch  pour tout lancer sinon yarn --dev
- Lancer symfony serve
    
 ### Fin de l'installation ###
    

