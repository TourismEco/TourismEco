 # TourismEco

# Présentation générale

TourismEco est une plateforme web dédiée à l'analyse et à la visualisation des données économiques et écologiques des pays du monde. Le projet vise à fournir une interface intuitive pour explorer les indicateurs clés de développement, les tendances économiques et les impacts environnementaux, permettant aux utilisateurs de prendre des décisions éclairées pour leurs voyages.

Le projet est développé en Python, PHP, JavaScript et utilise des bibliothèques telles que HTMX, AMCharts, Plotly et Pandas pour l'analyse de données. Il inclut également des fonctionnalités de calcul d'empreinte carbone pour les trajets, une analyse des scores de développement humain, et des visualisations interactives des données économiques et écologiques.

# Bibliothèques nécessaires

- **Python** : Utilisé pour l'analyse de données, la modélisation statistique et la visualisation avec des bibliothèques comme Pandas, NumPy, Matplotlib, Scikit-learn, Statsmodels et Plotly.
- **PHP** : Utilisé pour le backend, la gestion des requêtes SQL et l'interaction avec la base de données.
- **JavaScript** : Utilisé pour les fonctionnalités interactives côté client, notamment avec HTMX pour les requêtes AJAX et AMCharts pour les graphiques.
- **Bases de données** : MySQL pour stocker et gérer les données économiques et écologiques.
- **Outils de développement** : Git pour le contrôle de version, et des environnements de développement intégrés (IDE) comme Visual Studio Code.

# Fonctionnement global

Le projet se compose de plusieurs modules principaux :

1. **Analyse de données** : Utilisation de Python pour l'analyse statistique, la modélisation prédictive (comme ARIMA) et la visualisation des données avec Plotly.
2. **Backend PHP** : Gestion des requêtes SQL, interaction avec la base de données et traitement des données côté serveur.
3. **Frontend JavaScript** : Création d'interfaces utilisateur interactives avec HTMX pour les requêtes AJAX et AMCharts pour les visualisations.
4. **Calcul d'empreinte carbone** : Estimation des émissions de CO2 pour les trajets en utilisant des données sur les modes de transport et les distances.
5. **Visualisation des scores** : Affichage des scores de développement humain et des indicateurs économiques et écologiques sous forme de cartes interactives et de graphiques.

# Résumé fichier par fichier

## Fichiers Python

- **ACP.ipynb** : Script pour l'analyse en composantes principales (ACP) des données économiques et écologiques.
- **ARIMA.ipynb** : Script pour la modélisation ARIMA (AutoRegressive Integrated Moving Average) pour la prédiction des séries temporelles.
- **ARIMA_parametre.ipynb** : Script pour déterminer les paramètres optimaux du modèle ARIMA.
- **ARIMA_prediction.ipynb** : Script pour les prédictions futures à l'aide du modèle ARIMA.
- **clutering.ipynb** : Script pour le clustering des données économiques et écologiques.
- **clutering_8.ipynb** : Script pour le clustering avec 8 clusters.
- **regression_lineaire.ipynb** : Script pour la régression linéaire entre les variables économiques et écologiques.
- **Score.ipynb** : Script pour le calcul des scores de développement humain et des indicateurs économiques et écologiques.

## Fichiers PHP

- **config.php** : Fichier de configuration pour la connexion à la base de données.
- **functions.php** : Fichier contenant des fonctions utilitaires pour le projet.
- **navbar.php** : Fichier pour la barre de navigation du site.
- **footer.html** : Fichier pour le pied de page du site.
- **index.php** : Page d'accueil du site.
- **explorer.php** : Page pour explorer les données économiques et écologiques.
- **pays.php** : Page pour afficher les détails d'un pays spécifique.
- **comparateur.php** : Page pour comparer les données de deux pays.
- **calculateur.php** : Page pour calculer l'empreinte carbone des trajets.
- **continent.php** : Page pour afficher les données économiques et écologiques par continent.
- **inscription.php** : Page pour l'inscription des utilisateurs.
- **connexion.php** : Page pour la connexion des utilisateurs.
- **profil.php** : Page pour afficher et modifier le profil de l'utilisateur.
- **deconnexion.php** : Page pour la déconnexion des utilisateurs.
- **analyse.php** : Page pour l'analyse des données économiques et écologiques.

## Fichiers JavaScript

- **functions.js** : Fichier contenant des fonctions utilitaires pour le frontend.
- **carousel-home.js** : Script pour le carrousel sur la page d'accueil.
- **map.js** : Script pour la carte interactive.
- **amTools.js** : Script pour les outils de visualisation avec AMCharts.
- **line.js** : Script pour les graphiques linéaires.
- **lineCompare.js** : Script pour comparer les graphiques linéaires de deux pays.
- **spider.js** : Script pour les graphiques radar (spider charts).
- **barCompare.js** : Script pour comparer les graphiques en barres de deux pays.
- **jauge_continent.js** : Script pour les jauges de performance par continent.
- **barreLine.js** : Script pour les graphiques en barres et lignes.
- **barreContinent.js** : Script pour les graphiques en barres par continent.
- **linePays.js** : Script pour les graphiques linéaires par pays.
- **scatterplotContinent.js** : Script pour les graphiques de dispersion par continent.

## Fichiers HTMX

- **appendCompare.php** : Script pour ajouter un pays à la comparaison.
- **selectPays.php** : Script pour sélectionner un pays.
- **getCompare.php** : Script pour obtenir les données de comparaison de deux pays.
- **getExplore.php** : Script pour obtenir les données d'exploration.
- **getFavorite.php** : Script pour obtenir les pays favoris de l'utilisateur.
- **listVilles.php** : Script pour lister les villes d'un pays.
- **getCatalogue.php** : Script pour obtenir le catalogue des pays.
- **appendContinent.php** : Script pour ajouter un pays à un continent.
- **search.php** : Script pour la recherche de pays.
- **selectVille.php** : Script pour sélectionner une ville.
- **getContinent.php** : Script pour obtenir les données d'un continent.
- **getTravelOptions.php** : Script pour obtenir les options de voyage.
- **more.php** : Script pour afficher plus de résultats.
- **getPays.php** : Script pour obtenir les données d'un pays.
- **listPays.php** : Script pour lister les pays.

## Fichiers de calcul

- **transport-car.php** : Script pour le calcul de l'empreinte carbone des trajets en voiture.
- **transport-train.php** : Script pour le calcul de l'empreinte carbone des trajets en train.
- **transport-plane.php** : Script pour le calcul de l'empreinte carbone des trajets en avion.
- **calcul.php** : Script pour le calcul de l'empreinte carbone globale des trajets.

# Comment lancer le projet

Pour lancer le projet, suivez ces étapes :

1. **Installer les dépendances** :
   - Assurez-vous d'avoir Python, PHP et MySQL installés sur votre machine.
   - Installez les bibliothèques Python nécessaires en exécutant `pip install -r requirements.txt`.
   - Installez les dépendances PHP nécessaires en exécutant `composer install`.

2. **Configurer la base de données** :
   - Importez le fichier SQL de la base de données dans votre serveur MySQL.
   - Configurez les paramètres de connexion à la base de données dans le fichier `config.php`.

3. **Lancer le serveur** :
   - Placez le projet dans le répertoire racine de votre serveur web (par exemple, `htdocs` pour XAMPP).
   - Lancez le serveur web (par exemple, Apache) et le serveur MySQL.

4. **Accéder au site** :
   - Ouvrez votre navigateur et accédez à l'URL du projet (par exemple, `http://localhost/tourismeco`).

5. **Utiliser le site** :
   - Explorez les différentes pages pour analyser les données économiques et écologiques, comparer les pays, calculer l'empreinte carbone des trajets, et afficher les détails des pays.