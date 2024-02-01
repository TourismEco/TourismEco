# Processus de création d'une page
Ou bien comprendre les intéractions entre PHP, HTMX et AMCharts.

## Introduction
Cette partie de documentation vise à expliquer le fonctionnement total d'une page de notre projet, et de comment l'implémenter au mieux, afin d'avoir des graphiques dynamiques et efficaces.

Elle va comprendre une explication de comment faire la page PHP principale, comment fonctionne HTMX,  et enfin comment bien se servir des graphiques.

L'exemple qui sera pris tout au long de ce texte est la page Pays, avec quelques extraits de la page Comparateur, car elle est totalement aboutie et suit ce modèle.

Pour vous aider, ouvrez les différents fichiers cités dans le texte, ainsi que les pages Pays et Comparateur dans votre navigateur.

## Concepts
Le fonctionnement de cette page se base sur plusieurs fichiers distincts, ayant chacun son rôle dans le rendu de la page.
- `pays.php` : le *squelette* de la page, où les graphiques et conteneurs HTML sont générés mais vides
- `getPays.php` : le fichier qui va charger toutes les données des pays que l'on veut, et notamment remplir les graphiques
- `amTools.js`, `spider.js`, ... : tous les scripts JavaScript qui vont s'occuper des graphiques de la création à la gestion des données

L'idée est de pouvoir changer de pays sans avoir à recharger la page et les graphiques. En effet, nos graphiques et notre carte sont assez lourds en espace mémoire, et avoir à les recharger en permanence est une perte de temps phénoménale. Pour remédier à ça, on va vouloir seulement changer les données qui sont affichées.

On va donc utiliser HTMX. C'est une bibliothèque JS qui permet de simplifier les requêtes AJAX et la gestion de ses résultats. De manière très simple, on va demander à HTMX d'effectuer des requêtes POST ou GET à une adresse donnée. Le résultat de cette requête sera des éléments HTML, qui seront insérés dans notre page de manière automatique par HTMX aux endroits que l'on désigne. Par conséquent, pas besoin de renvoyer des objets JSON interminables, et de faire du JQuery à l'infini pour ajuster notre page, HTMX s'en occupe pour nous, notre job est seulement d'écrire du HTML.

Si vous ne voyez pas vraiment l'intêret de cette technologie, n'hésitez pas à m'en parler pour que je vous explique en détail les implications.

## Remarque : stockage du pays courant
Une des méthodes évidentes pour consulter la page d'un pays spécifique serait de passer en argument de notre requête GET l'ID du pays, qui serait donc mis dans l'URL.

Sauf que nous ne sommes pas des gens normaux, et que Hugo a proposé de passer par des variables de session. Donc on fait avec des variables de session. C'est comme ça. Pour l'instant cet élément n'est pas négociable. Cela permet des transitions entre les pages qui sont favorables. 

Le pays courant est donc stocké dans `$_SESSION["pays"]`.

## Le "squelette"
Nous avons donc notre page `pays.php`. Cette page ne comporte que du HTML pur, qui forme donc une armature, ou un squelette, pour notre page que nous devrons ensuite remplir. Cette page ne fournit en aucun cas des données ! Pourquoi ? Car nous aurons déjà une page qui s'en occupe : `getPays.php`. Pour éviter des conflits et de la copie de code, ***uniquement*** cette page pourra accéder à la base de données et fournir des données ou des informations sur le pays que l'on veut.

Cela fait que si on ouvre la page sans choisir de pays, on vera les différents espaces, mais vides.

L'important est que chaque élément que l'on est suceptible de modifier selon le pays visité est nommé par un ID. Ici, cela va concerner les bandeaux : `bandeau0`, `mini0`, et plus tard nos textes. Les graphiques sont mis à part pour l'instant.

Nous avons une structure arborescente : 
- la navbar
- notre carte `<div class="container-map"> ... </div>`
- notre grille principale : `<div class="grille" id="grille"> ... </div>`
  - la partie latérale avec les mini bandeaux et l'accès au catalogue : `<div class="sidebar"> ... </div>`
  - la partie principale où tout le reste est affiché : `<div class="main" id="main"> ... </div>`

    - l'espace réservé au catalogue : `<div id="catalogue"></div>`
    - l'espace réservé aux bandeaux : `<div class="container-bandeaux"> ... </div>`
    - puis des containers, que l'on peut créer à l'infini : `<div class="container-simple bg-52796F"> ... </div>`. Ce sont dans ces containers que l'on va ajouter les différentes sections de notre page.
    - les scripts que l'on veut executer : `<script id=scripting> ... </script>`.
- le footer

La partie `scripting` est importante. C'est ici que nous allons faire tous nos appels de fonctions JS pour générer nos graphiques vides et modifier la carte : 

```HTML
<!-- Code tiré de comparateur.php -->
<script id=scripting>
    spiderCompare() // fonction qui crée le Spider, venant de spiderCompare.js
    lineCompare() // lineCompare.js
    barCimpare() // barCompare.js
    createMapCompare(<?=$pays?>) // map.js
</script>
```
Si vous avez parcouru le fichier, vous avez dû remarquer que j'ai oublié un élément *(j'espère...)*. Au tout début de la partie `main`, un script PHP est rédigé. Ce script est primordial, car il vérifie si un pays est stocké dans la session, et s'il faut donc l'afficher !

Si c'est le cas, il va appeler `getPays.php` pour charger les données, et sinon il va appeler le catalogue.

```php
if ($pays == "") {
    echo <<<HTML
        <div hx-get="catalogue.php" hx-trigger="load" hx-select="#catalogue" hx-target="#catalogue" hx-vals="js:{page:'Pays'}"></div>
    HTML;
} else {
    echo <<<HTML
        <div hx-get="scripts/htmx/getPays.php" hx-vals="js:{id_pays:'$pays'}" hx-trigger="load delay:1s"></div>
    HTML;
}
```
Dans les deux cas, on fait un appel à HTMX pour récupérer les données que l'on veut, et les insérer dans la page. `hx-vals` correspond à `data` pour la fonction `$.ajax()` de JQuery.

Cet appel de `getPays.php` avec en argument l'ID du pays va faire afficher tout ce qu'on a rédigé dans ce fichier. Elle peut être appelée au chargement (comportement que vous ne pouvez pas gérer), en cliquant sur la carte, ou par le catalogue.

## HTMX et `getPays.php`

Le fichier `getPays.php` ne peut être appelé que par HTMX, par méthode GET. 

La première action effectuée est de changer le pays courant stocké dans la session.

```php
$id_pays = $_GET["id_pays"];
$_SESSION["pays"][0] = $id_pays;
```

Ensuite, s'en suit des appels de base de données pour obtenir toutes les informations que l'on veut : nom du pays, capitale, stats principales, et des données des graphiques.

Toutes les données qui seraient dans des array et qui doivent être transmises à des scripts JS doivent être encodés en JSON, avec `json_encode()`. L'argument `JSON_NUMERIC_CHECK` force les entiers à être des entiers, sinon ils seraient automatiquement des chaînes de caractères.

```php
$dataSpider = json_encode(dataSpider($id_pays, $cur), JSON_NUMERIC_CHECK);
```

Et à la fin nous avons notre rendu : un `echo` HEREDOC qui contient tout le code HTML que nous souhaitons modifier.

Exemple : création du bandeau
```html
<div class="bandeau" id="bandeau0" hx-swap-oob="outerHTML">     
    <img class="img" src='assets/img/$id_pays.jpg' alt="Bandeau">
    <img class="flag" src='assets/twemoji/$id_pays.svg'>
    <h1 class="nom">$nom</h1>
    <p class="capital">Capitale : $capitale</p>
    <img id="favorite" src="assets/img/heart.png" hx-get="scripts/htmx/getFavorite.php" hx-trigger="click" hx-swap="outerHTML">
</div>
```

Ce qui faut remarquer ici est l'attribut `hx-swap-oob` dans la déclaration de la `div` principale. Cet attribut, quand il est affecté à `outerHTML`, va aller chercher l'élement de notre page qui possède l'ID `bandeau0`, pour remplacer tout son contenu, par ce qu'on a rédigé en haut.

C'est l'un des concepts d'HTMX qui sont très puissants, et que nous allons utiliser en permanence pour tous les objets à utiliser. Cette instruction seule permet de changer les éléments que l'on veut, à partir du moment qu'ils sont bien nommés dans la page. En effet, pour que ce bandeau s'affiche correctement, il faut qu'une `div` avec l'ID `bandeau0` soit déjà existante (vide ou non).

## Les graphiques
Passons maintenant à la partie majeure : comment créer efficacement les graphiques avec la version POO faite maison.

Il faut distinguer trois parties :
- la classe Objet, notre boîte noire qui permet de controler les éléments montrés sur le graphique, qui est détaillé dans la précédente documentation. (je ne reviens pas en détail là dessus)
- la fonction qui va créer le graphique vide
- la fonction qui va ajuster les données : les créer ou les modifier

Il faut forcément ces trois parties pour que tout fonctionne correctement.

### Classe objet
Il n'est pas nécessaire de recréer une classe objet si une existe déjà et fait exactement ce dont vous avez besoin. Si jamais vous avez besoin de choses en plus, ou de modifications de style significantes, alors créez une nouvelle classe en surchargeant les méthodes que vous voulez.

Il faut savoir que la classe `Graphique` embarque tout ce qu'il faut pour créer des graphiques simples et uniformisés. Pareil pour `Spider`, `Line` et `Bar`, ils permettent de créer des graphiques basiques sans trop d'artifices, mais pour autant facilement personnalisables. Si un aspect de personnalisation très important ou une fonctionnalité n'est pas implémentée, je vous invite à prendre connaissance avec AM Tools pour créer votre propre classe, avec les méthodes réajustées qu'il vous faut.

Cf documentation `Graphiques.md`

### Fonction de création

La fonction qui crée le graphique vide est très simple.

```js
// Fonction venant de lineCompare.js
function lineCompare(id) {
    l = new Line(id)        // On instancie un graphique
    l.initXAxis("year")     // On précise que l'axe X va suivre la colonne Year
    l.initYAxis()           // On initialise Y, sans paramètres 
    l.addLegend()           // On ajoute notre légende
    l.setType("co2")        // Ce graphique est soumis à des modifications externes, via des boutons. Le type de stat affiché actuellement sur le graphique est stocké dans un attribut type, que nous affectons par défaut à 'co2'
}

// Fonction venant de spiderCompare.js
function spiderCompare(id) {
    g = new Spider(id)
    g.initXAxis("var")
    g.initYAxis()
    g.addLegend()
    g.setDataXAxis([{"var":"pib"},{"var":"Enr"},{"var":"co2"},{"var":"arrivees"},{"var":"departs"},{"var":"gpi"},{"var":"cpi"}])        // On précise à l'avance les valeurs que va prendre l'axe X, car il est statique sur ce graphique. Pour le Line Chart, elles sont dynamiques, car chaque statistique possède des bornes d'années différentes : cette méthode ne peut pas être appelée à l'initialisation dans ce cas.
    g.addSlider(updateSpider,400,50,50,50,90,2008,2020)     // On ajoute un slider
}
```

Elle consiste simplement à appeler les méthodes nécessaire pour construire les axes et la légende. A vous d'ajuster les méthodes que vous avez besoin, et quoi leur donner ! Ici la particularité principale est la méthode `setType()`, qui est **spécifique** à la classe `Line`.

Il faut comprendre ce qu'on veut faire, et comment avoir un graphique le plus efficace possible. L'exemple de la méthode `setDataXAxis()` est très parlant ! C'est une méthode de base de `Graphique`, mais qu'on ne doit pas forcément utiliser dès la création du graphique ! Chaque figure a ses spécificités, et nos classes possèdent une flexibilité pour gérer comme on souhaite les données.

Pour rappel, ces fonctions doivent être appelées dans la balise `id = scripting` de `pays.php`.

### Fonction pour les données et où l'appeler

Maintenant, on doit afficher des données sur notre graphique ! Nous allons alors créer des fonctions que nous allons appeler dans `getPays.php`, à un endroit précis.

```html
<script id=scripting hx-swap-oob=outerHTML>
    spiderHTMX($incr, $dataSpider, $dataTab, "$nom")
    lineHTMX($incr, $dataLine, "$nom")
    barHTMX($incr, $dataBar, "$nom")
</script>
```
Cette balise script va s'exécuter dès que le HTMX aura fini d'infuser dans notre page. Et ces 3 fonctions vont ajouter les données à chaque graphique. On donne en argument tout ce qu'on a récupéré juste avant avec PHP.

*Remarque :* elle va remplacer le contenu précédent de `scripting`, où on avait placé la création des graphiques. Cela ne change rien, car une fois créés ils sont stockés en mémoire. La trace de l'appel ne compte pas.

Voici à quoi ressemble ces fonctions :
```js
// Fonction venant de lineCompare.js
function lineHTMX(index, data, name) {
    l.addSerie(index, data, name, color[index], "year", l.getType())    // On ajoute nos données
    resetAnnees()       // Fonction qui s'occupe de changer les valeurs de l'axe X
}

// Fonction venant de spiderCompare.js
function spiderHTMX(index, data, dataComp, name) {
    g.addSerie(index, data, dataComp, name, color[index], "var", "value");  // On ajoute nos données
    g.setDataSerie(index, data[g.getYear()])    // Les graphiques ayant le temps variable nécessite une modification de données après ajout
    updateTable(index, dataComp[g.getYear()]);   // Le Spider gère aussi le tableau à côté
}
```

L'action principale est l'ajout de données, qui est déjà géré par AM Tools et les classes que l'on a créé. Si un procédé spécial est nécessaire, il est possible de la surcharger !

Ensuite, on effectue des actions annexes, mais nécessaires, et souvent spécifique à chaque graphique.

## Conclusion
C'est à peu près tout ce qu'il faut savoir pour créer efficacement la page et les graphiques. Tout dépend énormément de votre compréhension du code et des graphiques. De manière évidente, ce que vous voudrez afficher n'est pas forcément déjà codé, alors il faut adapter ce qui existe !