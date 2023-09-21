# La base de données
## Tables
- `pays` : table centrale, contient des informations sur les pays de nos tables
- `arrivees` : statistiques d'arrivées touristique dans chaque pays, en milliers
- `departs` : statistiques de départs touristiques dans chaque pays, en milliers
- `argent` : statistiques sur l'argent généré par le tourisme entrant et sortant

## Remarques
Sur les données de départs et d'arrivées, les montants peuvent dépasser la population totale, car on compte le nombre de voyages et pas le nombre de voyageurs uniques.

## Sources
- https://www.unwto.org/tourism-statistics/key-tourism-statistics
- https://developers.google.com/public-data/docs/canonical/countries_csv?hl=fr
- https://developers.google.com/public-data/docs/canonical/countries_csv?hl=en

## Schémas
### `pays`
|Champ|Description|
|--|--|
|*id*|Clé primaire, code de chaque pays. Suit la norme ISO 3166-1 alpha-2|
|lat|Latitude géographique du pays|
|lon|Longitude géographique du pays|
|nom|Nom complet du pays|
|A3|Code ISO 3166-1 alpha-3 du pays|
|Num|Code ISO 3166-1 numérique du pays|

### `arrivees`
|Champ|Description|
|--|--|
|*id*|Clé primaire, id numérique de la ligne.|
|id_pays|Clé étrangère, code du pays concerné par la ligne|
|annee|Date des données|
|arriveesTotal|Nombre total d'arrivées dans le pays sur l'année, en milliers|
|arriveesAF|Nombre d'arrivées dans le pays **_venant d'Afrique_** sur l'année, en milliers|
|arriveesAM|Nombre d'arrivées dans le pays **_venant d'Amérique_** sur l'année, en milliers|
|arriveesEA|Nombre d'arrivées dans le pays **_venant d'Asie de l'Est_** sur l'année, en milliers|
|arriveesEU|Nombre d'arrivées dans le pays **_venant d'Europe_** sur l'année, en milliers|
|arriveesME|Nombre d'arrivées dans le pays **_venant du Moyen Orient_** sur l'année, en milliers|
|arriveesSA|Nombre d'arrivées dans le pays **_venant d'Asie du Sud_** sur l'année, en milliers|
|arriveesAutre|Nombre d'arrivées dans le pays **_venant d'ailleurs_** (non classifié) sur l'année, en milliers|
|arriveesPerso|Nombre d'arrivées dans le pays **_pour des raisons personnelles_** sur l'année, en milliers|
|arriveesPro|Nombre d'arrivées dans le pays **_pour des raisons professionnelles_** sur l'année, en milliers|
|arriveesAvion|Nombre d'arrivées dans le pays **_par voie aérienne_** sur l'année, en milliers|
|arriveesEau|Nombre d'arrivées dans le pays **_par voie maritime_** sur l'année, en milliers|
|arriveesTerre|Nombre d'arrivées dans le pays **_par voie terrestre_** sur l'année, en milliers|

### `departs`
|Champ|Description|
|--|--|
|*id*|Clé primaire, id numérique de la ligne.|
|id_pays|Clé étrangère, code du pays concerné par la ligne|
|annee|Date des données|
|departs|Nombre total de départs depuis le pays sur l'année, en milliers|

### `argent`
|Champ|Description|
|--|--|
|*id*|Clé primaire, id numérique de la ligne.|
|id_pays|Clé étrangère, code du pays concerné par la ligne|
|annee|Date des données|
|depenses|Argent dépensé lors des voyages de personnes parties du pays, en millions de US$|
|recettes|Argent récolté lors des voyages de personnes venant dans le pays, en millions de US$|

## Vis-à-vis du Excel de UNTWO
Tables utilisées : 

![test](https://cdn.discordapp.com/attachments/363670399700959233/1151904838410584164/image.png)
