// AM Tools Version 1.4
// Réencapsulation des graphiques AMCharts pour faciliter la manipulation des graphiques

class Graphique {
    // Graphique est une classe Interface qui définit tous les comportements par défaut des graphiques. Sont laissés vide à chaque fois les objets AM Charts qui doivent être ajustés en fonction du graphiques voulu
    constructor(id, figObj, cursorObj) {
        this.root = am5.Root.new(id)
        this.graph = this.root.container.children.push(figObj.new(this.root, {}))
        this.xAxis = null
        this.yAxis = null
        this.yAxisLeft = null
        this.legend = null
        this.year = null
        this.series = []

        var cursor = this.graph.set("cursor", cursorObj.new(this.root, {}));
        cursor.lineY.set("visible", false);

        this.root.setThemes([
            am5themes_Animated.new(this.root)
        ]);
    }

    newXRenderer(obj) {
        // Rendu de l'axe X : rend le texte blanc
        var xRenderer = obj.new(this.root, {
            cellStartLocation: 0.1,
            cellEndLocation: 0.9
        });
        xRenderer.labels.template.setAll({
            fill:"#FFFFFF",
        });
        return xRenderer
    }

    newYRenderer(obj, opposite = false) {
        // Rendu de l'axe Y : rend le texte blanc
        var yRenderer = obj.new(this.root, {
            opposite:opposite
        });
        yRenderer.labels.template.setAll({
            fill:"#FFFFFF",
        });
        return yRenderer
    }

    initXAxis(rendererObj, field) {
        // Instancie l'axe X
        var base = this
        this.xAxis = this.graph.xAxes.push(am5xy.CategoryAxis.new(base.root, {
            categoryField: field,   // Le plus important : le nom de la colonne dans les données qui sera utilisé pour les abscisses
            renderer: base.newXRenderer(rendererObj),
            tooltip: am5.Tooltip.new(base.root, {})
        }));
    }

    initYAxis(rendererObj) {
        // Instancie l'axe Y, rien de particulier par défaut
        var base = this
        this.yAxis = this.graph.yAxes.push(am5xy.ValueAxis.new(base.root, {
            renderer: base.newYRenderer(rendererObj)
        }));
    }

    initYAxisLeft(rendererObj) {
        // Instancie un axe opposé
        var base = this
        this.yAxisLeft = this.graph.yAxes.push(am5xy.ValueAxis.new(base.root, {
            renderer: base.newYRenderer(rendererObj, true)
        }));
    }

    addSerie(index, data, name, color, xField, yField, obj, labelText, opposite = false) {
        // Ajoute une série de données. Si à l'index donné une série est déjà présente, écrase les données précédentes.
        var base = this

        if (this.series.length == index) {
            var serie = this.graph.series.push(obj.new(base.root, {     // Toutes les options principales. Rien n'est fixe, tout est déterminable par passage en arguments
                name: name,             // Nom de la série (des pays)
                xAxis: base.xAxis,      // Axes AMCharts
                yAxis: opposite ? base.yAxisLeft : base.yAxis,
                categoryXField: xField, // Nom de la colonne dans les données pour les valeurs en X
                valueYField: yField,    // Nom de la colonne dans les données pour les valeurs en Y
                tooltip: am5.Tooltip.new(base.root, {       // Le Tooltip est ce qui est affiché au survol des données
                    labelText: labelText
                }),
                stroke:color,           // Couleur de la série
                fill:color,
            }));

            serie.data.setAll(data)     

            if (color == null) {                    // !! Si color vaut null, alors les couleurs par défaut d'AMCharts sont utilisées.
                serie.columns.template.adapters.add("fill", function (fill, target) {
                    return base.graph.get("colors").getIndex(serie.columns.indexOf(target));
                });
                serie.columns.template.adapters.add("stroke", function (stroke, target) {
                    return base.graph.get("colors").getIndex(serie.columns.indexOf(target));
                });
            }
            
            if (this.legend != null) {              // Ajoute à la légende si elle existe. Par conséquent, il faut absolument que la légende soit ajoutée AVANT les données
                this.legend.data.push(serie)
            }
            var s = new Serie(data,serie)           // La série de données AMCharts est encapsulée dans une classe personnalisée
            this.series.push(s)                     // On la stocke de notre côté afin de pouvoir s'en resservir

            return s
        } else {
            this.series[index].setData(data)        // On change les données et le nom de la série
            this.series[index].setDataSerie(data)
            this.series[index].setName(name)
            return this.series[index]
        }
    }

    addBullets(serie, color) {
        // Ajoute des points sur la série de données voulue
        var base = this

        serie.serie.bullets.push(function() {
            return am5.Bullet.new(base.root, {
                sprite: am5.Circle.new(base.root, {
                    radius: 4,
                    stroke: color,
                    fill: color
                })
            });
        });
    }

    addLegend() {
        // Ajoute la légende
        this.legend = this.graph.children.push(
            am5.Legend.new(this.root, {
                centerX: am5.p50,
                x: am5.p50,
                fill:"#FFFFFF"
            })
        );
    }

    addSlider(fun, width, padT, padR, padL, rotation, first, last) {
        // Ajoute un slider. Position personnalisable (width, padT, padR, parL). Bornes à fournir (first, last). La fonction executée lors du mouvement du slider est donnée dans fun. En JS et en Python, les fonctions sont des objets comme les autres. On peut donc les donner en argument d'une autre fonction, en passant simplement son nom.
        var base = this

        var container = this.graph.children.push(am5.Container.new(base.root, {
            centerX: am5.p0,
            centerY: am5.p50,
            width: width,
            layout: base.root.horizontalLayout,
            paddingTop: padT,
            paddingRight: padR,
            paddingLeft: padL,
            rotation: rotation
        }));
    
        var slider = container.children.push(am5.Slider.new(base.root, {
            orientation: "horizontal",
            start: 1,
            centerX: am5.p50,
        }));
    
        slider.get("background").setAll(
            {fill:"#52796F"}
        )
    
        slider.startGrip.get("icon").set("forceHidden", true);
        slider.startGrip.set("label", am5.Label.new(base.root, {
            text: last + "",
            paddingTop: 0,
            paddingRight: 0,
            paddingBottom: 0,
            paddingLeft: 0,
            fill: "#000000",
            rotation: -rotation
        }));

        var yearTemp = 2020
        this.year = 2020
    
        slider.events.on("rangechanged", function () {
            yearTemp = first + Math.round(slider.get("start", 0) * (last - first));
            if (base.year != yearTemp) {
                base.year = yearTemp
                fun(yearTemp);
                slider.startGrip.get("label").set("text", yearTemp + "");
            }
        });
    }

    // Ensemble de setter et de getter
    setDataXAxis(data) {
        this.xAxis.data.setAll(data)
    }
    setNumberFormat(format) {
        // Si il y a besoin de changer l'affichage des nombres (en % ou retirer des décimales par exemple) https://www.amcharts.com/docs/v5/concepts/formatters/formatting-numbers/
        this.root.numberFormatter.set("numberFormat", format);
    }
    setDataSerie(index, data) {
        this.series[index].setDataSerie(data)
    }

    getSeriesLength() {
        return this.series.length
    }
    getYear() {
        return this.year
    }
    getSeries() {
        return this.series
    }
}

class Serie {
    // Cette classe permet de stocker des informations supplémentaires vis à vis des données passées au graphique, et de les réutiliser
    constructor(data,serie) {
        this.data = data        // Données
        this.serie = serie      // Objet AMCharts de base
        this.comp = null        // Données complémentaires, si besoin. (utilisé dans spiderCompare, pour gérer le tableau de stats)
    }

    setData(data) {
        this.data = data
    }
    setComp(data) {
        this.comp = data
    }
    setDataSerie(data) {
        this.serie.data.setAll(data)
    }
    setName(name) {
        this.serie.setAll({
            name:name
        })
    }

    getData() {
        return this.data
    }
}

class Spider extends Graphique {
    // Réimplémentation des Spider Plot
    constructor(id) {
        super(id, am5radar.RadarChart, am5radar.RadarCursor)
    }
    initXAxis(field) {
        super.initXAxis(am5radar.AxisRendererCircular, field)
    }

    initYAxis() {
        // Spécificité : on doit préciser le minimum et le maximum
        var base = this
        this.yAxis = this.graph.yAxes.push(am5xy.ValueAxis.new(base.root, {
            min:0,
            max:100,
            renderer: base.newYRenderer(am5radar.AxisRendererRadial)
        }));
    }

    addSerie(index, data, dataComp, name, color, xField, yField) {
        // Spécificité : on ajoute les données complémentaires et les points
        var serie = super.addSerie(index, data, name, color, xField, yField, am5radar.RadarLineSeries, "{name} : {valueY}")
        serie.setComp(dataComp)
        super.addBullets(serie, color)
        return serie
    }

}

class Line extends Graphique {
    // Réimplémentation des Line Chart
    constructor(id) {
        super(id, am5xy.XYChart, am5xy.XYCursor)
        this.type = null        // Gestion des boutons pour Compare, susceptible de bouger dans Graphique
    }
    initXAxis(field) {
        super.initXAxis(am5xy.AxisRendererX, field)
    }
    initYAxis() {
        super.initYAxis(am5xy.AxisRendererY)
    }
    addSerie(index, data, name, color, xField, yField) {
        // Spécificité : ajout des points
        var serie = super.addSerie(index, data, name, color, xField, yField, am5xy.LineSeries, "{name} : {valueY}")
        super.addBullets(serie, color)
        return serie
    }
    getType() {
        return this.type
    }
    setType(type) {
        this.type = type
    }
}

class Bar extends Graphique {
    constructor(id) {
        super(id, am5xy.XYChart, am5xy.XYCursor)
    }
    initXAxis(field) {
        super.initXAxis(am5xy.AxisRendererX, field)
    }
    initYAxis() {
        super.initYAxis(am5xy.AxisRendererY)
    }
    initYAxisLeft() {
        super.initYAxisLeft(am5xy.AxisRendererY)
    }
    addSerie(index, data, name, color, xField, yField) {
        return super.addSerie(index, data, name, color, xField, yField, am5xy.ColumnSeries, "{name} : {valueY}")
    }
    addLine(index, data, name, color, xField, yField) {
        var serie = super.addSerie(index, data, name, color, xField, yField, am5xy.LineSeries, "{name} : {valueY}", true)
        super.addBullets(serie, color)
        return serie
    }
}

class Jauge {
    // La Jauge n'est pas enfant de Graphique, car trop différent dans le code.
    constructor(id) {
        this.root = am5.Root.new(id)
        this.graph = this.root.container.children.push(am5radar.RadarChart.new(this.root, {
            startAngle: 160,
            endAngle: 380
        }))
        
        this.xAxis = null
        this.data = null
        this.clock = null
        this.label = null

        this.root.setThemes([
            am5themes_Animated.new(this.root)
        ]);
    }

    newXRenderer() {
        var xRenderer = am5radar.AxisRendererCircular.new(this.root, {
            innerRadius: -25 // épaisseur jauge
        });
        xRenderer.labels.template.setAll({
            fill:"#FFFFFF",
        });
        return xRenderer
    }

    initXAxis() {
        var base = this
        this.xAxis = this.graph.xAxes.push(am5xy.ValueAxis.new(base.root, {
            min: 0,
            max: 100,
            renderer: base.newXRenderer()
        }));
    }

    addClock() {
        var base = this

        this.data = this.xAxis.makeDataItem({});

        this.clockHand = am5radar.ClockHand.new(this.root, {
            pinRadius: am5.percent(15),   // grandeur rond
            radius: am5.percent(95),     // hauteur trait
            bottomWidth: 40   // taille base
        })

        this.data.set("bullet", am5xy.AxisBullet.new(base.root, {
            sprite: base.clockHand
        }));

        this.xAxis.createAxisRange(this.data);

        this.label = this.graph.radarContainer.children.push(am5.Label.new(base.root, {
            fill: am5.color(0xffffff),
            centerX: am5.percent(50),
            centerY: am5.percent(50),
            textAlign: "center",
            fontSize: "35px"
        }));

        var bandsData = [
            {color: "#AA0000", lowScore: 0, highScore: 20}, 
            {color: "#BB5C00", lowScore: 20, highScore: 40}, 
            {color: "#E49C15", lowScore: 40, highScore: 60}, 
            {color: "#446700", lowScore: 60, highScore: 80}, 
            {color: "#006700", lowScore: 80, highScore: 100}
        ];
          
        am5.array.each(bandsData, function (data) {
            var axisRange = base.xAxis.createAxisRange(base.xAxis.makeDataItem({}));
            
            axisRange.setAll({
                value: data.lowScore,
                endValue: data.highScore
            });
            
            axisRange.get("axisFill").setAll({
                fill: am5.color(data.color),
            });
        }); 
    }

    changeValue(value) {
        this.data.animate({
            key: "value",
            to: value,
            duration: 500,
            easing: am5.ease.out(am5.ease.cubic)
        });

        var fill = "#FFFFFF"
        this.xAxis.axisRanges.each(function (axisRange) {
            if (value >= axisRange.get("value") && value <= axisRange.get("endValue")) {
                fill = axisRange.get("axisFill").get("fill");
            }
        })

        this.label.set("text", value.toString());

        this.clockHand.pin.animate({key: "fill", to: fill, duration: 500, easing: am5.ease.out(am5.ease.cubic)})
        this.clockHand.hand.animate({key: "fill", to: fill, duration: 500, easing: am5.ease.out(am5.ease.cubic)})
    }
}