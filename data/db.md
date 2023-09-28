# La base de données
## Tables
- [`pays`](https://github.com/L3S518-LLHAR-kek/projet_L3/blob/main/data/db.md#pays) : table centrale, contient des informations sur les pays de nos tables
- [`villes`](https://github.com/L3S518-LLHAR-kek/projet_L3/blob/main/data/db.md#villes) : liste de villes, avec leur population
- [`guerre`](https://github.com/L3S518-LLHAR-kek/projet_L3/blob/main/data/db.md#guerre) : liste d'état de guerre qu'un pays peut avoir
- [`arrivees`](https://github.com/L3S518-LLHAR-kek/projet_L3/blob/main/data/db.md#arrivees) : statistiques d'arrivées touristique dans chaque pays, en milliers
- [`departs`](https://github.com/L3S518-LLHAR-kek/projet_L3/blob/main/data/db.md#departs) : statistiques de départs touristiques dans chaque pays, en milliers
- [`argent`](https://github.com/L3S518-LLHAR-kek/projet_L3/blob/main/data/db.md#argent) : statistiques sur l'argent généré par le tourisme entrant et sortant
- [`emploi`](https://github.com/L3S518-LLHAR-kek/projet_L3/blob/main/data/db.md#emploi) : statistiques des emplois liant le tourisme dans chaque pays
- [`cpi`](https://github.com/L3S518-LLHAR-kek/projet_L3/blob/main/data/db.md#cpi) : statistiques sur le Consumer Price Index, prix global de la vie dans un pays
- [`pib`](https://github.com/L3S518-LLHAR-kek/projet_L3/blob/main/data/db.md#pib) : statistiques sur le Produit Interieur Brut de chaque pays
- [`gpi`](https://github.com/L3S518-LLHAR-kek/projet_L3/blob/main/data/db.md#gpi) : statistiques sur le Global Peace Index, calculant la sureté d'un pays

## Schémas
### `pays`
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
|id_guerre|INT|ID du statut de guerre du pays, clé étrangère de `guerre`|

Sources :
- https://github.com/dr5hn/countries-states-cities-database
- https://developers.google.com/public-data/docs/canonical/countries_csv?hl=fr
- https://developers.google.com/public-data/docs/canonical/countries_csv?hl=en
- https://wisevoter.com/country-rankings/countries-currently-at-war/

### `villes`
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
- ?

### `guerre`
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
- 5 : Guerre ethnique

Sources : 
- https://wisevoter.com/country-rankings/countries-currently-at-war/

### `arrivees`
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

Sources :
- https://www.unwto.org/tourism-statistics/key-tourism-statistics

### `departs`
|Champ|Type|Description|
|--|--|--|
|*id*|INT|Clé primaire, id numérique de la ligne.|
|id_pays|VARCHAR|Clé étrangère, code du pays concerné par la ligne|
|annee|INT|Date des données|
|departs|INT|Nombre total de départs depuis le pays sur l'année, en milliers|

Sources :
- https://www.unwto.org/tourism-statistics/key-tourism-statistics

### `argent`
|Champ|Type|Description|
|--|--|--|
|*id*|INT|Clé primaire, id numérique de la ligne.|
|id_pays|VARCHAR|Clé étrangère, code du pays concerné par la ligne|
|annee|INT|Date des données|
|depenses|INT|Argent dépensé lors des voyages de personnes parties du pays, en millions de US$|
|recettes|INT|Argent récolté lors des voyages de personnes venant dans le pays, en millions de US$|

Sources :
- https://www.unwto.org/tourism-statistics/key-tourism-statistics

### `emploi`
|Champ|Type|Description|
|--|--|--|
|*id*|INT|Clé primaire, id numérique de la ligne.|
|id_pays|VARCHAR|Clé étrangère, code du pays concerné par la ligne|
|annee|INT|Date des données|
|departs|INT|Nombre d'emploi généré par le tourisme|

Sources :
- https://www.unwto.org/tourism-statistics/key-tourism-statistics

### `cpi`
|Champ|Type|Description|
|--|--|--|
|*id*|INT|Clé primaire, id numérique de la ligne.|
|id_pays|VARCHAR|Clé étrangère, code du pays concerné par la ligne|
|annee|INT|Date des données|
|cpi|INT|Indice des prix de consommation, en US$|

Sources :
- https://data.un.org/Default.aspx

### `pib`
|Champ|Type|Description|
|--|--|--|
|*id*|INT|Clé primaire, id numérique de la ligne.|
|id_pays|VARCHAR|Clé étrangère, code du pays concerné par la ligne|
|annee|INT|Date des données|
|pib|BIGINT|Produit intérieur brut en US$|

Sources :
- ?

### `gpi`
|Champ|Type|Description|
|--|--|--|
|*id*|INT|Clé primaire, id numérique de la ligne.|
|id_pays|VARCHAR|Clé étrangère, code du pays concerné par la ligne|
|annee|INT|Date des données|
|cpi|INT|Indice de paix|

Sources : 
- https://www.visionofhumanity.org/maps/#/
- https://en.wikipedia.org/wiki/Global_Peace_Index


