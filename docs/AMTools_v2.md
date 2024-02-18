# AM Tools 2.0

## Introduction
Bienvenue dans cette nouvelle documentation, qui couvre le fonctionnement de la nouvelle version d'AM Tools, permettant de généraliser la création de nos graphiques. Ce document présente aussi les différences avec la version initiale, et comment migrer correctement vers cette mise à jour. Commençons !

## Pourquoi ces changements ?

Car la version précédante était un amas d'ajouts imbriqués au fur et à mesure, qui au final répondait mal. Cette version ne pouvait pas être maintenue en l'état, et méritait une réécriture au moins partielle. Au final, près de 70% d'AM Tools a été retravaillé, pour mieux correspondre à nos besoins.

## Basculement d'une version à une autre
Cette mise à jour d'AM Tools est **majeure**, et apporte des "*breaking changes*",  des modifications qui nécessitent une adaptation du code qui utilisait l'ancienne version.

Tant que vous n'aurez pas totalement appréhendé les changements et les nouvelles manipulations, et que les graphiques n'auront pas tous basculé sur le nouveau modèle apporté, les deux version coexisteront.

C'est pourquoi vous trouverez les fichiers `amTools.js`, la version originale, et `amTools20.js`, qui abrite tout le code de la 2.0.

Tous les graphiques liés à la page Comparateur ont déjà été migrés vers AM Tools 2.0, vous pouvez vous en inspirer.

## Changements majeurs 
Voici une liste exhaustive des changements majeurs à prendre en compte :

- **Suppression de *presque* toutes les sous-classes `Spider`, `Bar`, ...**
  
  - Ce changement majeur est un choix très réfléchi que je vais argumenter. Ces classes étaient importantes au début pour bien comprendre l'architecture d'AM Charts, et produisait un code très esthétique, clair dans les noms et fonctionnel. Néanmoins, j'ai remarqué que vous n'arrivez pas à comprendre leur fonctionnement réel et savoir quand et comment créer de nouvelles classes. C'était déjà un handicap, et de +, avoir des classes fermées avec des fonctionnalités spéciales était un frein pour la flexibilité, car certaines améliorations pouvaient être transversales.

  - Beaucoup de répétitions dans le code étaient aussi présent, car les graphiques se répètent encore plus que ce que je ne pensais dans leur implémentation. *(cf doc v1)*

  - J'ai donc opté pour une approche plus volatile, peut-être plus brouillone dans l'écriture (je suis pas 100% satisfait du résultat) mais permettant de créer plus facilement tout type de graphique depuis une seule classe que vous n'êtes plus censé toucher seul sauf cas très spécial.

  - C'est donc une seule boîte noire qui embarque tous les objets AM Charts nécessaire pour créer n'importe quel graphique. Cette nouvelle classe s'appelle pour le moment `Graphique20`. Quand la mise à jour sera validée par tout le monde, son nom changera pour remplacer définitivement `Graphique`. 
  
  - Seule la classe `Jauge` est maintenue, car son fonctionnement particulier justifie des méthodes uniques.
  
- **Types de séries et de graphiques**
  - La suppression des classes filles nécessite tout de même de pouvoir spécifier le type de graphique et de données on veut spécifier. Cela se passe désormais dans le constructeur des graphiques et dans la méthode `addSerie()`. Ils permettront à l'aide d'un simple mot clé de modifier l'apparence des figures.
  - Pour le constructeur voici les options possible : `radar`, `pie`, `bar`, `line`, `xy`
  - Pour les séries de données : `bar`, `radar`, `line`, `dot`, `pie`

- **`initXAxis()` et `initYAxis()` remaniés**

  - Les méthodes de créations d'axe ont d'abord été **renommés** : 
    - `initXAxis()` devient `createXAxis()`
    - `initYAxis()` devient `createYAxis()`
  - Les méthodes `newXRenderer()` et `newYRenderer()` ont été supprimées, pour être incorporés directement dans `createXAxis()` et `createYAxis()`
  - Changement des arguments pour embarquer la possibilité d'avoir des axes de valeur ou de catégorie plus facilement que ce soit sur X ou Y
  - Création d'un axe Y opposé simplifié

- **Différenciation de `addSerie()` et `updateSerie()`**
  - Depuis les dernières versions, l'ajout de données fonctionnait de cette façon : on devait fournir un index à la méthode `addSerie()`, et si à cet index aucune série n'a été créée, on l'ajoutait, sinon on modifiait les données de la série.
  - Ce schéma était efficace mais invalide en terme de """sémantique""". On ne savait jamais réellement quand une série allait être créée ou non, qui résulte une perte de maîtrise du code, surtout dans un contexte très dynamique.
  - Pour régler ça, voici la nouvelle approche : utilisation d'une méthode pour **créer** les séries de données, au tout début de la création du graphique (`addSerie()`), et une autre conçue pour y insérer des données (`updateSerie()`). Plus de détails après.

- **Suppression des légendes**
  - Choix fait en vue de la future maquette, qui prévoit que les légendes soient à l'extérieur des graphiques. A discuter.

**Ces changements impactent vos scripts ! Faites bien les modifications nécessaires vis à vis de cela !** Les références de chaque méthode est à suivre.

## Changements mineurs
- `Jauge` devient une classe fille de `Graphique20`

- `annee` et `type` sont désormais des attributs de `Graphique20`, ils étaient avant spécifiques respectivement aux graphiques `Spider` et `Line`
- Quelques messages d'erreur si des arguments sont mal entrés
- Si le graphique est orienté à la verticale, le Tooltip au survol des données passe sur le côté
- Les barres ont un border-radius de 10px

## Nouvelles possibilités
- Scatter Plot, Pie Plot (entier ou non)

- Créer un graphique qui possède plusieurs séries de différentes formes *(cf BarLine)*
- Créer sa propre palette de couleurs qui peut être appliquée par défaut au graphique
- Slider généralisé
- Axe Y catégorisé et axe X de valeurs, pour retourner les graphiques


## Flow de création à suivre

**Exemble de barCompare.js :**
```js
function barCompare(id) {
    // On crée un graphique avec des axes classiques
    b = new Graphique20(id, "bar")

    // On génère nos axes. Notre axe X suit des catégories, qui seront dans un dictionnaire et indiqué par la clé 'var'. Notre axe Y lui suivra nos valeurs automatiquement
    b.createXAxis("var")
    b.createYAxis()

    // On ajoute nos catégories, qui sont statiques pour ce graphiques. 
    b.setDataXAxis([{"var":"pib"},{"var":"co2"},{"var":"arrivees"},{"var":"gpi"},{"var":"cpi"}])

    // On ajoute un slider, qui répond à la fonction updateBar quand il est bougé
    b.addSlider(updateBar,700,10,50,50,0,2008,2020)

    // On affiche des pourcentages, donc on change l'affichage des valeurs
    b.setNumberFormat("# '%'")

    // On ajoute les séries que l'on veut, ce sont à chaque fois des séries en barres. Les données qu'on lui transmettra devront contenir les clés 'var' et 'value'
    b.addSerie("bar", "var", "value", null, "{name} : {valueY}", "#52796F")
    b.addSerie("bar", "var", "value", null, "{name} : {valueY}", "#83A88B")
}

function barHTMX(index,data,name) {
    // Avec HTMX, on met à jour les données 
    b.updateSerie(index, data, name)
}
```

## Documentation détaillée : `Graphique20`

Tous les changements dûs à la 2.0 sous <u>soulignés</u>
### Créer un graphique : le constructeur

- **Arguments :** 
  - `id` : l'ID HTML du graphique

  - <u>`option`</u> : quel type de graphique créer. Selon ce qui est donné, c'est principalement la forme des axes qui va changer.
    - `"radar"` : Radar Chart, axe unique circulaire, forme idéale pour un Spider
  
    - `"pie"` : Pie Chart, aucun axe à proprement parler, forme idéale pour un graphique en camembert
    - `"bar"`, `line`, `xy` : ces trois options sont équivalents et créent un graphique avec des axes X et Y classiques
  - <u>`dictSup`</u> : argument **optionnel**, dictionnaire contenant des arguments supplémentaires pour configurer la figure. Référez vous aux exemples AM Charts ou à leur documentation pour savoir ce que vous pouvez y mettre. Ce dictionnaire est inséré dans la création des objets `chart`.

- **Exemple :** 
```js
// Crée un Spider avec comme valeur minimale 0 et maximale 100
g = new Graphique20("id", "radar", {min:0, max:100})

// Crée un Bar Chart
b = new Graphique20("id", "bar")
```
- **Ancienne version :**
```js
// Le minimum et le maximum étaient des valeurs par défaut dans la classe Spider
g = new Spider("id")

b = new Bar("id")
```

- **Équivalent AM Charts :** 
```js
var root = am5.Root.new("id");
var chart = root.container.children.push(
    am5radar.RadarChart.new(root, {
        min:0,
        max:100
    })
);

var root = am5.Root.new("id");
var chart = root.container.children.push(
    am5xy.XYChart.new(root, {})
);
```

### Attributs
- `root` : objet AMC qui gère la racine du graphique

- `graph` : objet AMC qui gère la figure en elle même
- `xAxis` : objet AMC qui gère l'axe X
- `yAxis` : objet AMC qui gère l'axe Y
- <u>`yAxisLeft`</u> : objet AMC qui gère l'axe Y opposé
- `year` : si un Slider est ajouté, stockage de l'année courante
- `series` : liste de séries de données, contient des objets `Series20`
- <u>`option`</u> : type de graphique créé
- <u>`type`</u> : variable qui permet de stocker quel type de données est affiché par exemple

### Créer l'axe X : `createXAxis()`

Ajoute un axe X à la série.

- **Arguments :**
  - <u>`field`</u> : argument **optionnel**, par défaut à `null`
    - par défaut si rien n'est fourni, alors l'axe sera un axe de **valeurs**, c'est à dire qu'il sera construit et mis à jour automatiquement selon les valeurs affichées

    - si un champ est donné, alors l'axe sera un axe de **catégories**. AM Charts n'est pas capable de deviner les catégories que l'on veut, il faudra lui donner des données avec la méthode `setDataXAxis()`. Il récupèrera alors dans ces données toutes les valeurs ayant pour clé la valeur donnée à `field`.

  - <u>`dictSup`</u> : argument **optionnel**, par défaut à `{}`. Dictionnaire contenant des arguments supplémentaires pour configurer l'axe. Référez vous aux exemples AM Charts ou à leur documentation pour savoir ce que vous pouvez y mettre. 
    - paramètres prédéfinis et inmutables : `renderer` et `categoryField`
  
    - paramètres courants : `min`, `max`, `baseValue`, `maxPrecision`, `numberFormat`, ...

- **Exemple :** 
```js
// L'axe X représente des données, qui vont être récupérées dans les données par la clé "year" : 2018, 2019, 2020
g.createXAxis("year")
g.setDataXAxis([{"year":2018, "val":15}, {"year":2019, "val":17}, {"year":2020, "val":30}])
```

- **Ancienne version :**
```js
g.initXAxis("year")
g.setDataXAxis([{"year":2018, "val":15}, {"year":2019, "val":17}, {"year":2020, "val":30}])
```

- **Équivalent AM Charts :**
```js
var xRenderer = am5xy.AxisRendererX.new(root, {
    cellStartLocation: 0.1,
    cellEndLocation: 0.9,
    minorGridEnabled: true
})

var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
    categoryField: "year",
    renderer: xRenderer,
    tooltip: am5.Tooltip.new(root, {})
}));

xAxis.data.setAll(data);
```

### Créer l'axe Y : `createYAxis()`

Ajoute un axe Y à la figure. Cet axe peut être opposé.

- **Arguments :**
  - <u>`field`</u> : argument **optionnel**, par défaut à `null`
    - par défaut si rien n'est fourni, alors l'axe sera un axe de **valeurs**, c'est à dire qu'il sera construit et mis à jour automatiquement selon les valeurs affichées

    - si un champ est donné, alors l'axe sera un axe de **catégories**. AM Charts n'est pas capable de deviner les catégories que l'on veut, il faudra lui donner des données avec la méthode `setDataYAxis()`. Il récupèrera alors dans ces données toutes les valeurs ayant le nom de clé `field`.
  - <u>`opposite`</u> : argument **optionnel**, par défaut à `false`. Si l'argument est `true`, alors l'axe sera opposé, c'est à dire à droite plutôt qu'à gauche. Pratique pour créer des axes doubles.


  - <u>`dictSup`</u> : argument **optionnel**, par défaut à `{}`. Dictionnaire contenant des arguments supplémentaires pour configurer l'axe. Référez vous aux exemples AM Charts ou à leur documentation pour savoir ce que vous pouvez y mettre. 
    - paramètres prédéfinis et inmutables : `renderer` et `categoryField`

    - paramètres courants : `min`, `max`, `baseValue`, `maxPrecision`, `numberFormat`, ...

- **Exemple :** 
```js
// Axe pricipal, aucun paramètre car ce sont des valeurs numériques indéterminées, axe non opposé et pas de paramètre spécial voulu
g.createYAxis()

// Axe secondaire, qui doit être opposé. C'est toujours un axe de valeurs.
g.createYAxis(null, true)
```

- **Ancienne version :**
```js
g.initYAxis()
g.initYAxisLeft()
```

- **Équivalent AM Charts :**
```js
var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
    renderer: am5xy.AxisRendererY.new(root, {
        strokeOpacity: 0.1,
    })
}));

var yAxisLeft = chart.yAxes.push(am5xy.ValueAxis.new(root, {
    renderer: am5xy.AxisRendererY.new(root, {
        strokeOpacity: 0.1,
        opposite:true
    })
}));
```

### Ajouter une série de données : `addSerie()`

Ajoute **une** série de données au graphique. Cette série de données est vide, c'est seulement la forme que l'on initialise. Il faut ensuite y ajouter des données avec la méthode `updateSerie()`. 

Cette méthode doit être appelée autant de fois que de séries voulues sur votre graphique.

L'idée est de donner tous les paramètres de la série pour ajuster comment elle apparaitra, et à quelles données elle correspondra. Toutes ces options ne peuvent pas être dynamiques et sont fixées en amont, lors de la création du graphique.

- **Arguments :**
  - <u>`option`</u> : type de série à ajouter, c'est à dire son apparence

    - `dot` : des points, sans lignes

    - `line` : courbe
    - `bar` : barres
    - `radar` : série polaire
    - `pie` : partie de Pie Chart
  - `xField` : le nom de la variable qui prend l'axe X pour cette série
  - `yField` : le nom de la variable qui prend l'axe Y pour cette série
  - <u>`valField`</u> : le nom de la variable qui prend une valeur annexe pour cette série (souvent à `null`)
  - `labelText` : le texte qui doit apparaître au survol d'un point de la série
  - `color` : couleur de la série
    - si cet argument vaut `null`, alors la palette par défaut sera appliquée

    - sinon, il faut donner une couleur en format hexadécimal, en commençant par un # (#FFFFFF par exemple)
  - <u>`dictSup`</u> : argument **optionnel**, par défaut à `{}`. Dictionnaire contenant des arguments supplémentaires pour configurer la série. Référez vous aux exemples AM Charts ou à leur documentation pour savoir ce que vous pouvez y mettre. 
    - paramètres prédéfinis ou inmutables : `xAxis`, `yAxis`, `tooltip`, `fill`, `stroke`, `valueField`, `valueXField`, `valueYField`, `categoryField`, `categoryXField`, `categoryYField`, `value`, `name`

    - paramètres courants : `stacked`, `startAngle`, `endAngle`, `alignLabels`
  - <u>`opposite`</u> : argument **optionnel**, par défaut à `false`. Si l'argument est `true`, alors la série suivra l'axe opposé. Vous devez d'abord avoir créé un axe opposé.

- **Exemple :**

```js
// Ajout d'une série en barres
b.addSerie("bar", "var", "value", null, "{name} : {valueY}", "#52796F")

// Ajout d'une série en ligne, sur l'axe opposé
b.addSerie("line", "var", "value", null, "{name} : {valueY}", "#83A88B", {}, true)
```

- **Ancienne version :**
  
```js
// L'ajout de série de formes différentes était exclusif à la classe Bar via la méthode addLine()
b.addSerie(0, data, "PIB/Hab", color[0], "year", "value");
b.addLine(1, data, "Arrivées touristique", color[1], "year", "valueLeft");
```

- **Équivalent AMCharts :**

```js
var series = chart.series.push(am5xy.ColumnSeries.new(root, {
    name: "Series 1",
    xAxis: xAxis,
    yAxis: yAxis,
    valueYField: "value",
    sequencedInterpolation: true,
    categoryXField: "country",
    tooltip: am5.Tooltip.new(root, {
        labelText: "{valueY}"
    })
}));
```

### Mettre à jour les données d'une série : `updateSerie()`

Met à jour une série de données en changeant les données affichées.

- **Arguments :**
  - `index` : index de la série de données à changer

  - `data` : les données à insérer sur la série
  - `name` : nouveau nom de la série
  - `dataComp` : argument **optionnel**, par défaut à `null`. Vous pouvez transmettre des données complémentaires si besoin. C'est à vous ensuite de gérer leur utilisation. 

- **Exemple :**

```js
function barHTMX(index,data,name) {
    b.updateSerie(index, data, name)
}
```

- **Équivalent AM Charts :**

```js
series.data.setAll(data);
```

### Ajouter un slider : `addSlider()`

Ajoute un slider pour faire changer l'année des données d'un graphique. On doit lui préciser sa position, ses bornes et surtout son comportement quand l'année change.

- **Arguments :**
  - `fun` : la fonction à exécuter lors du changement d'années

  - `width` : largeur de la barre du slider
  - `padT`, `padR`, `padL` : padding Top, Right et Left pour ajuster la position du slider (dur à maîtriser)
  - `rotation` : rotation du slider, pour le mettre sur le côté par exemple ( == 90 alors)
  - `first` et `last` : respectivement la première et dernière année possible pour le slider

- **Exemple :**

```js
// Exemple de spiderCompare.js, quand le slider bouge, on change les données de la série, ainsi que le tableau qui figure à côté, en utilisant les données complémentaires de la série.
function updateCSpider(year) {
    for (var s of g.getSeries()) {
        s.setDataSerie(s.data[year]);
        updateCTable(s.getIndex(), s.comp[year]);
    }
}

g.addSlider(updateCSpider,400,-20,50,50,90,2008,2020)
```

### Ajuster la palette de couleur du graphique : `changeColor()`

Écrase la palette de couleur par défaut d'AM Charts pour le graphique pour une nouvelle. Cette méthode est utile si pour votre série vous n'avez pas de couleur unique à donner, mais un ensemble de couleur prédéfini.

Cette méthode doit être appelée avant l'ajout de séries de données.

- **Argument :**
  - `colors` : liste de couleurs. Cette liste peut être de taille infinie. Elle doit être de la forme : `["FFFFFF","000000","2C2C2C"]` c'est à dire les valeurs hexadécimales des couleurs, sans # en préfixe. Cette liste sera ensuite traitée pour coller avec la syntaxe d'AM Charts.

- **Exemple :**

```js
// Palette des scores
b.changeColor(["006700","446700","E49C15","BB5C00","AA0000"])
```

- **Équivalent AM Charts :**

```js
chart.set("colors", am5.ColorSet.new(root, {
    colors: [
        am5.color(0x006700),
        am5.color(0x446700),
        am5.color(0xE49C15),
        am5.color(0xBB5C00),
        am5.color(0xAA0000)
    ]
}))
```

## L'objet `Serie20`

Quand vous ajoutez à vos graphiques une série de données, vous créez un objet AM Charts spécial qui est lui aussi ré-encapsulé dans un objet personnalisé : `Serie20`. Vous n'avez pas à maîtriser sa création, mais seulement ses attributs.

- `serie` : l'objet AM Charts de base, que l'on pourrait vouloir manipuler

- <u>`index`</u> : la position de la série dans le graphique 
- `data` : les données qui composent la série
- `comp` : les données complémentaires de la série, si elles existent

A l'aide des getters et setters, vous pouvez jouer sur ces attributs pour récupérer ou modifier les données comme vous le souhaitez !

## And Beyond...

Il y a des choses qui ne sont pas évidentes à expliquer ou à expliciter dans une documentation, mais cette classe `Graphique20` possède des subtilités et des attributs qui peuvent facilement être utilisés pour arriver à vos fins.

Je pense notamment à l'accès aux séries, aux données complémentaires, à l'attribut `type` ou tout autre information qui est stocké lors de la génération de vos figures.

C'est à vous d'explorer un peu les codes et d'essayer de les comprendre, pour tirer un maximum de ces objets ! N'hésitez pas à me poser des questions pour que je vous aiguille sur comment créer au mieux les graphiques.