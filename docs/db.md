# La base de données
Version actuelle : 3.0

Dernière mise à jour : 20/10/23
## Sommaire
### Tables principales
- [`pays`](https://github.com/L3S518-LLHAR-kek/projet_L3/blob/main/data/db.md#pays) : table centrale, contient des informations sur les pays de nos tables
- [`villes`](https://github.com/L3S518-LLHAR-kek/projet_L3/blob/main/data/db.md#villes) : liste de villes, avec leur population
- [`guerre`](https://github.com/L3S518-LLHAR-kek/projet_L3/blob/main/data/db.md#guerre) : liste d'état de guerre qu'un pays peut avoir
- [`continents`](https://github.com/L3S518-LLHAR-kek/projet_L3/blob/main/data/db.md#continents) : liste des continents qu'un pays peut appartenir
- [`checking`](https://github.com/L3S518-LLHAR-kek/projet_L3/blob/main/data/db.md#checking) : table de vérité affichant la présence ou non des pays dans les différentes tables

### Tables de stats
- [`tourisme`](https://github.com/L3S518-LLHAR-kek/projet_L3/blob/main/data/db.md#tourisme) : statistiques liées au tourisme
- [`economie`](https://github.com/L3S518-LLHAR-kek/projet_L3/blob/main/data/db.md#economie) : statistiques liées à l'économie
- [`ecologie`](https://github.com/L3S518-LLHAR-kek/projet_L3/blob/main/data/db.md#ecologie) : statistiques liées à l'écologie
- [`surete`](https://github.com/L3S518-LLHAR-kek/projet_L3/blob/main/data/db.md#surete) : statistiques sur le Global Peace Index, calculant la sureté d'un pays  


## Schémas
### `pays`
Notre table principale, chaque table possède une clé étrangère qui se réfère à la colonne `id`.
|Champ|Type|Description|
|--|--|--|
|*id*|VARCHAR|Clé primaire, code de chaque pays. Suit la norme ISO 3166-1 alpha-2|
|lat|DOUBLE|Latitude géographique du pays|
|lon|DOUBLE|Longitude géographique du pays|
|nom|VARCHAR|Nom complet du pays|
|iso_3|VARCHAR|Code ISO 3166-1 alpha-3 du pays|
|iso_alpha|INT|Code ISO 3166-1 numérique du pays|
|emoji|VARCHAR|Emoji associé au pays|
|emojiU|VARCHAR|Emoji associé au pays en Unicode|
|emojiSVG|VARCHAR|Nom du fichier .svg associé au drapeau|
|id_guerre|INT|ID du statut de guerre du pays, clé étrangère de `guerre`|

Sources :
- https://github.com/dr5hn/countries-states-cities-database
- https://developers.google.com/public-data/docs/canonical/countries_csv?hl=fr
- https://developers.google.com/public-data/docs/canonical/countries_csv?hl=en
- https://wisevoter.com/country-rankings/countries-currently-at-war/
- https://github.com/twitter/twemoji/releases/tag/v14.0.2

### `villes`
Liste de villes, avec leur populations et coordonnées géographiques.

Deux pays n'ont aucune villes référencées : Macao et Territoires palestiniens.
|Champ|Type|Description|
|--|--|--|
|*id*|INT|Clé primaire, id numérique de la ligne|
|nom|VARCHAR|Nom de la ville|
|lat|DOUBLE|Latitude géographique de la ville|
|lon|DOUBLE|Longitude géographique de la ville|
|id_pays|VARCHAR|ID du pays d'où vient la ville|
|capitale|BOOLEAN|Vrai si la ville est une capitale|
|population|INT|Nombre d'habitant dans la ville|

Source :
- https://simplemaps.com/data/world-cities

### `guerre`
Décrit les statuts de guerre possibles.

|Champ|Type|Description|
|--|--|--|
|*id*|INT|Clé primaire, id numérique de la ligne|
|statut|VARCHAR|Intitulé du statut|

Statuts possibles : 
- 0 : En paix
- 1 : En guerre
- 2 : Guerre de la drogue
- 3 : Guerre civile
- 4 : Terrorisme

Sources : 
- https://wisevoter.com/country-rankings/countries-currently-at-war/

### `continents`
Décrit les statuts de guerre possibles.

|Champ|Type|Description|
|--|--|--|
|*id*|INT|Clé primaire, id numérique de la ligne|
|nom|VARCHAR|Nom du continent|

### `tourisme`
Statistiques sur le tourisme de chaque pays.

|Champ|Type|Description|
|--|--|--|
|*id*|INT|Clé primaire, id numérique de la ligne.|
|id_pays|VARCHAR|Clé étrangère, code du pays concerné par la ligne|
|annee|INT|Date des données|
|arriveesTotal|INT|Nombre total d'arrivées dans le pays sur l'année, en milliers|
|arriveesAF|INT|Nombre d'arrivées dans le pays **_venant d'Afrique_** sur l'année, en milliers|
|arriveesAM|INT|Nombre d'arrivées dans le pays **_venant d'Amérique_** sur l'année, en milliers|
|arriveesEA|INT|Nombre d'arrivées dans le pays **_venant d'Asie de l'Est_** sur l'année, en milliers|
|arriveesEU|INT|Nombre d'arrivées dans le pays **_venant d'Europe_** sur l'année, en milliers|
|arriveesME|INT|Nombre d'arrivées dans le pays **_venant du Moyen Orient_** sur l'année, en milliers|
|arriveesSA|INT|Nombre d'arrivées dans le pays **_venant d'Asie du Sud_** sur l'année, en milliers|
|arriveesAutre|INT|Nombre d'arrivées dans le pays **_venant d'ailleurs_** (non classifié) sur l'année, en milliers|
|arriveesPerso|INT|Nombre d'arrivées dans le pays **_pour des raisons personnelles_** sur l'année, en milliers|
|arriveesPro|INT|Nombre d'arrivées dans le pays **_pour des raisons professionnelles_** sur l'année, en milliers|
|arriveesAvion|INT|Nombre d'arrivées dans le pays **_par voie aérienne_** sur l'année, en milliers|
|arriveesEau|INT|Nombre d'arrivées dans le pays **_par voie maritime_** sur l'année, en milliers|
|arriveesTerre|INT|Nombre d'arrivées dans le pays **_par voie terrestre_** sur l'année, en milliers|
|departs|INT|Nombre total de départs depuis le pays sur l'année, en milliers|
|depenses|INT|Argent dépensé lors des voyages de personnes parties du pays, en millions de US$|
|recettes|INT|Argent récolté lors des voyages de personnes venant dans le pays, en millions de US$|
|emploi|INT|Nombre d'emploi généré par le tourisme|

Sources :
- https://www.unwto.org/tourism-statistics/key-tourism-statistics

### `economie`
Statistiques économique de chaque pays. 

|Champ|Type|Description|
|--|--|--|
|*id*|INT|Clé primaire, id numérique de la ligne.|
|id_pays|VARCHAR|Clé étrangère, code du pays concerné par la ligne|
|annee|INT|Date des données|
|cpi|INT|Indice des prix de consommation, en US$|
|pib|BIGINT|Produit intérieur brut en US$|
|pibParHab|BIGINT|Produit intérieur brut par habitant en US$|

Sources :
- https://data.un.org/Default.aspx
- https://databank.worldbank.org/reports.aspx?source=2&series=NY.GDP.MKTP.CD&country#
- https://datacatalog.worldbank.org/search/dataset/0037712/World-Development-Indicators

### `surete`
Indice de paix (et de sûreté) de chaque pays
|Champ|Type|Description|
|--|--|--|
|*id*|INT|Clé primaire, id numérique de la ligne.|
|id_pays|VARCHAR|Clé étrangère, code du pays concerné par la ligne|
|annee|INT|Date des données|
|cpi|INT|Indice de paix|

Sources : 
- https://www.visionofhumanity.org/maps/#/
- https://en.wikipedia.org/wiki/Global_Peace_Index


### `ecologie`
Statistiques sur l'écologie
|Champ|Type|Description|
|--|--|--|
|*id*|INT|Clé primaire, id numérique de la ligne.|
|id_pays|VARCHAR|Clé étrangère, code du pays concerné par la ligne|
|annee|INT|Date des données|
|co2|DOUBLE|Émissions de CO2 du pays|
|ges|DOUBLE|Émissions de gaz à effet de serre du pays|
|elecRenew|DOUBLE|% d'électricité renouvelable produite dans le pays (1990-2015)|

Sources :
- https://data.worldbank.org/topic/environment
- https://ourworldindata.org/grapher/share-electricity-renewables?time=1986

### `checking`
|Champ|Type|Description|
|--|--|--|
|*id*|VARCHAR|Clé primaire, id du pays. Tous les id sont ceux listés par AMCharts|
|//|//|Chaque colonne représente une table. Valeur 0 si le pays est absent de la table, 1 en cas de présence|
