var countrySeries;
var continentSeries;
var previousPolygon;
var map;
var root;
var buttons;
var incr = 0

var html = 
        `<div style="display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: repeat(1,100px);
            width: 240px;
            height: 80px;
            justify-content: center;
            align-items: center;
            word-break: break-word;
            text-align: center;
            font-family: Arial, Helvetica, sans-serif;">
            <img style="grid-column: 1 / 3;
                grid-row: 1;
                width: 240px;
                height: 80px;
                object-fit: cover;" 
                src='../assets/img/{id}.jpg'>

            <div style="grid-column: 2;
                grid-row: 1;
                justify-content: center;">
                    <h1 style="font-size: 20px;
                    color: white;  ">{name}</h1>
            </div>

            <div style="grid-column: 1;
                grid-row: 1;
                width: 100%;">
                <img style="width: 70px;
                    height: 70px;" src='../assets/twemoji/{id}.svg'>
            </div>
        </div>
        `

function switchToCountries(){
    /* 
    Fonction qui passe de la vue continent à la vue pays
    */
    continentSeries.hide();
    countrySeries.show();
    map.goHome();
}

function switchToContinent(){
    /* 
    Fonction qui passe de la vue pays à la vue continent
    */
    countrySeries.hide();
    continentSeries.show();
    map.goHome();
}

function setActionsSeries(serie,compare=false) {
    /* 
    Fonction détermine les actions au clic et au survol des séries. Comportement spécial si la carte est celle des comparaisons, automatiquement FALSE 
    */

    serie.mapPolygons.template.setAll({
        tooltipText: "{name}",
        toggleKey: "active",
        interactive: true,
        tooltip: am5.Tooltip.new(root, {
            labelHTML: html,
        }),
    });
    
    serie.mapPolygons.template.states.create("hover", {
        fill: am5.color("#CAD2C5"),
    });

    serie.mapPolygons.template.states.create("active", {
        fill: am5.color("#52796F")
    });


    if (compare) {
        serie.mapPolygons.template.on("active", function(active, target) {
            if (active) {
                incr++
                if (incr == 1) {
                    poly1.set("active", false);  
                    poly1.set("interactive",true)
                    poly1 = target
                    $("#pays1").val(poly1._dataItem.dataContext.id)
                    compareAjax("0", poly1._dataItem.dataContext.id, $("#pays2").val())

                } else if (incr == 2) {
                    poly2.set("active", false);  
                    poly2.set("interactive",true)
                    poly2 = target
                    $("#pays2").val(poly2._dataItem.dataContext.id)
                    incr = 0
                    compareAjax("1", poly2._dataItem.dataContext.id, $("#pays1").val())
                }
            }
        })
    } else {
        serie.mapPolygons.template.on("active", function(active, target) {
            if (active) {
                target.set("interactive",false)
                if (previousPolygon && previousPolygon != target) {
                    previousPolygon.set("active", false);  
                    previousPolygon.set("interactive",true)
                }
                previousPolygon = target;
            }
        })

        serie.mapPolygons.template.events.on("click", function (ev) {
            serie.zoomToDataItem(ev.target.dataItem);
        });
    }
}

function createButton(text, fun) {
    /* 
    Fonction qui crée un bouton, avec un texte et une fonction qui sera appliquée
    */
    var button = buttons.children.push(am5.Button.new(root, {
        paddingTop: 0,
        paddingRight: 0,
        paddingBottom: 0,
        paddingLeft: 0,
        marginLeft: 5,
        marginRight: 5,
        label: am5.Label.new(root, {
            text: text,
            fill:am5.color("#000000")
        })
    }));

    button.get("background").setAll({
        fill: am5.color("#CAD2C5")
    })

    button.get("background").states.create("hover", {}).setAll({
        fill:am5.color("#84A98C"),
    });

    button.events.on("click", function() {
        fun();
    });
}  

function createMap(fun=null,args=[]) {
    /* 
    Création de la carte. Si fun est spécifié, une fonction sera executée une fois que toute la carte et les données sont initialisés, pour la modifier. Pour passer des données à fun, il faut spécifier args (array)
    */

    root = am5.Root.new("map");

    root.setThemes([
        am5themes_Animated.new(root)
    ]);
    
    map = root.container.children.push(am5map.MapChart.new(root, {
        panX: "rotateX",
        wheelX: "none",
        wheelY: "none",
        projection: am5map.geoNaturalEarth1()
    }));

    zoom = map.set("zoomControl", am5map.ZoomControl.new(root, {}));
    zoom.minusButton.setAll({fill:"#000000"})
    zoom.minusButton.get("background").setAll({
        fill: am5.color("#CAD2C5")
    })
    zoom.plusButton.get("background").setAll({
        fill: am5.color("#CAD2C5")
    })
     
    // Continents
    continentSeries = map.series.push(am5map.MapPolygonSeries.new(root, {
        geoJSON: am5geodata_continentsLow,
        geodataNames:am5geodata_lang_FR,
        exclude: ["antarctica"],
        fill:am5.color("#84A98C")
    }));
    
    // Pays
    countrySeries = map.series.push(am5map.MapPolygonSeries.new(root, {
        geoJSON: am5geodata_worldLow,
        geodataNames:am5geodata_lang_FR,
        exclude: ["AX","BL","BQ","BV","CW","HM","MF","SJ","SS","SX","TL","UM","AF","AQ","CC","CX","EH","FK","FO","GG","GI","GL","GQ","GS","IM","IO","JE","KP","LR","NF","NR","PM","PN","SH","SO","SZ","TF","TK","VA","WF","YT","AI","CK","GF","GP","KN","MQ","MS","NU","PS","RE","TW","ST","MR"],
        visible: false,
        fill:am5.color("#84A98C")
    }));    

    setActionsSeries(countrySeries, compare=(fun==compare))
    setActionsSeries(continentSeries)

    // Home
    var homeButton = map.children.push(am5.Button.new(root, {
        paddingTop: 10,
        paddingBottom: 10,
        x: am5.percent(100),
        centerX: am5.percent(100),
        interactiveChildren: false,
        icon: am5.Graphics.new(root, {
        svgPath: "M16,8 L14,8 L14,16 L10,16 L10,10 L6,10 L6,16 L2,16 L2,8 L0,8 L8,0 L16,8 Z M16,8",
        fill: am5.color(0x000000)
        })
    }));

    homeButton.get("background").setAll({
        fill: am5.color("#CAD2C5")
    })

    homeButton.get("background").states.create("hover", {}).setAll({
        fill:am5.color("#84A98C"),
    });
    
    homeButton.events.on("click", function() {
        map.goHome();
    });

    // Add projection buttons
    buttons = map.children.push(am5.Container.new(root, {
        x: am5.p50,
        centerX: am5.p50,
        y: am5.p100,
        dy: -10,
        centerY: am5.p100,
        layout: root.horizontalLayout,
        paddingTop: 5,
        paddingRight: 8,
        paddingBottom: 5,
        paddingLeft: 8,
        background: am5.RoundedRectangle.new(root, {
            fill: am5.color(0xffffff),
            fillOpacity: 0.3
        })
    }));  
    
    createButton("Monde", switchToContinent);
    createButton("Continents", switchToContinent);
    createButton("Pays", switchToCountries);

    if (fun != null) {
        root.events.on("frameended",() => {
            fun(...args)
            root.events.off("frameended")
        })
    }
}

function addBullets(radius,color, data) {
    /* 
    Ajout des points
    */

    serie = map.series.push(
        am5map.MapPointSeries.new(root, {})
    );

    serie.bullets.push(function() {
        var circle = am5.Circle.new(root, {
            radius: radius,
            tooltipText: "{title}",
            tooltipY: 0,
            fill: color,
            stroke: root.interfaceColors.get("background"),
            strokeWidth: 2
        });

        return am5.Bullet.new(root, {
            sprite: circle
        });
    });

    serie.data.setAll(data);

    return serie
}  

function addCountry(idpays,cities,capitals) {
    /* 
    Fait le focus sur un pays et ajoute ses villes
    */

    console.log(idpays, cities, capitals)

    addBullets(5,am5.color(0xffba00),cities)
    addBullets(6,am5.color(0xb8602e),capitals)

    switchToCountries()
    
    elem = countrySeries.getDataItemById(idpays)
    console.log(elem)
    countrySeries.zoomToDataItem(elem);
    elem._settings.mapPolygon.set("active",true)
}


var poly1
var poly2
function compare(pays1,pays2) {
    buttons.removeAll()
    switchToCountries()

    poly1 = countrySeries.getDataItemById(pays1)
    poly2 = countrySeries.getDataItemById(pays2)

    poly1._settings.mapPolygon.set("active",true)
    poly2._settings.mapPolygon.set("active",true)
}