// AM Tools Version 2.0
// Réencapsulation des graphiques AMCharts pour faciliter la manipulation des graphiques

class Graphique {
    // Graphique est une classe Interface qui définit tous les comportements par défaut des graphiques. Sont laissés vide à chaque fois les objets AM Charts qui doivent être ajustés en fonction du graphiques voulu
    constructor(id, option, dict = {}) {
        // id : ID HTML du graphique, option : type de graphique ('radar', 'pie', 'bar', 'line'), dictSup : donner des arguments supplémentaires au graphique 
        this.root = am5.Root.new(id)
        
        this.option = option
        if (option == "radar" || option == "jauge") {
            this.graph = this.root.container.children.push(am5radar.RadarChart.new(this.root, dict))
            var cursor = this.graph.set("cursor", am5radar.RadarCursor.new(this.root, {}));
            cursor.lineY.set("visible", false);
        } else if (option == "pie") {
            this.graph = this.root.container.children.push(am5percent.PieChart.new(this.root, dict));
        } else if (option == "bar" || option == "line" || option == "xy") {
            this.graph = this.root.container.children.push(am5xy.XYChart.new(this.root, dict))
            var cursor = this.graph.set("cursor", am5xy.XYCursor.new(this.root, {}));
            cursor.lineY.set("visible", false);
        } else {
            console.error(`Type de graphique non reconnu. \nType donné : ${option} \nTypes acceptés : radar, pie, bar, line, xy`)
        }

        this.xAxis = null
        this.yAxis = null
        this.yAxisLeft = null
        this.axisType = {x:null, y:null, yLeft:null}

        this.series = []

        this.year = null
        this.type = null

        this.root.setThemes([
            am5themes_Animated.new(this.root)
        ]);

        this.root.numberFormatter.setAll({
            numberFormat:"#.###a",
            bigNumberPrefixes: [
                { "number": 1e+6, "suffix": "M" },
                { "number": 1e+9, "suffix": "B" }
            ],
            smallNumberPrefixes: []
        })
    }

    createXAxis(field = null, title = null, dictSup = {}) {
        // field : nom de champ dans les données. Si null, valeurs numériques automatique. dictSup : arguments supplémentaires pour la construction de l'axe
        var base = this

        var rendererObj = am5xy.AxisRendererX
        if (this.option == "radar" || this.option == "jauge") {
            rendererObj = am5radar.AxisRendererCircular
        }

        var xRenderer = rendererObj.new(this.root, {
            cellStartLocation: 0.1,
            cellEndLocation: 0.9,
            innerRadius: this.option == "jauge" ? -25 : 0 
        });
        xRenderer.labels.template.setAll({
            fill:"#222",
        });

        var xAx
        if (field == null) {
            xAx = am5xy.ValueAxis.new(base.root, {
                renderer: xRenderer,
                ...dictSup
            })
            this.axisType["x"] = "value"
        } else {
            xAx = am5xy.CategoryAxis.new(base.root, {
                categoryField: field,
                renderer: xRenderer,
                ...dictSup
            })
            this.axisType["x"] = "category"
        }

        this.xAxis = this.graph.xAxes.push(xAx);

        if (title != null) {
            this.xAxis.children.push(
                am5.Label.new(base.root, {
                    text: title,
                    x: am5.p50,
                    centerX:am5.p50
                })
            );
        }
    }

    createYAxis(field = null, title = null, dictSup = {}, opposite = false) {
        // field : nom de champ dans les données. Si null, valeurs numériques automatique. dictSup : arguments supplémentaires pour la construction de l'axe. opposite : si l'axe doit être opposé
        var base = this

        var rendererObj = am5xy.AxisRendererY
        if (this.option == "radar" || this.option == "jauge") {
            rendererObj = am5radar.AxisRendererRadial
        }

        var yRenderer = rendererObj.new(this.root, {
            opposite:opposite,
        });
        yRenderer.labels.template.setAll({
            fill:"#222",
        });

        var yAx
        var type
        if (field == null) {
            yAx = am5xy.ValueAxis.new(base.root, {
                renderer: yRenderer,
                ...dictSup
            })
            type = "value"
        } else {
            yAx = am5xy.CategoryAxis.new(base.root, {
                categoryField: field,
                renderer: yRenderer,
                ...dictSup
            })
            type = "category"
        }

        if (opposite) {
            this.yAxisLeft = this.graph.yAxes.push(yAx);
            this.axisType["yLeft"] = type

            if (title != null) {
                this.yAxisLeft.children.push(
                    am5.Label.new(base.root, {
                        rotation: 90,
                        text: title,
                        y: am5.p50,
                        centerX: am5.p50
                    })
                );
            }

        } else {
            this.yAxis = this.graph.yAxes.push(yAx);
            this.axisType["y"] = type

            if (title != null) {
                this.yAxis.children.unshift(
                    am5.Label.new(base.root, {
                        rotation: -90,
                        text: title,
                        y: am5.p50,
                        centerX: am5.p50
                    })
                );
            }
        }
    }

    addSerie(option, xField, yField, valField, labelText, color, dictSup = {}, opposite = false) {
        // option : type de série (dot, bar, radar, any)
        var base = this

        var dict = {
            xAxis:this.xAxis,
            tooltip: am5.Tooltip.new(base.root, {      
                labelText: labelText,
                pointerOrientation: this.axisType["x"] == "value" && this.axisType["y"] == "category" ? "horizontal" : "vertical" 
            }),
            fill:color,
            ...dictSup
        }

        if (this.xAxis == null) {
            dict["valueField"] = xField
            dict["categoryField"] = yField
        } else {
            dict["value"] = valField
            dict[this.axisType["x"]+"XField"] = xField
            if (opposite) {
                if (this.yAxisLeft == null) {
                    console.error("Pour assigner une série à un axe opposé, vous devez d'abord créer l'axe en question.")
                    return null
                }
                dict[this.axisType["yLeft"]+"YField"] = yField
                dict["yAxis"] = this.yAxisLeft
            } else {
                dict[this.axisType["y"]+"YField"] = yField
                dict["yAxis"] = this.yAxis
            }
        }
        
        if (option != "dot") {
            dict["stroke"] = color
        } 
        
        if (option == "bar") {
            var serie = this.graph.series.push(am5xy.ColumnSeries.new(base.root, dict));
            if (color == null) {
                serie.columns.template.adapters.add("fill", function (fill, target) {
                    return base.graph.get("colors").getIndex(serie.columns.indexOf(target));
                });
                serie.columns.template.adapters.add("stroke", function (stroke, target) {
                    return base.graph.get("colors").getIndex(serie.columns.indexOf(target));
                });
            }
            serie.columns.template.setAll({
                cornerRadiusTL: 10,
                cornerRadiusTR: 10
            });
        } else if (option == "radar") {
            var serie = this.graph.series.push(am5radar.RadarLineSeries.new(base.root, dict));
            this.addBullets(serie, color)
        } else if (option == "line" || option == "dot"){
            var serie = this.graph.series.push(am5xy.LineSeries.new(base.root, dict));
            this.addBullets(serie, color)
        } else if (option == "pie") {
            var serie = this.graph.series.push(am5percent.PieSeries.new(base.root, dict));
            serie.slices.template.setAll({
                cornerRadius: 5
            });
            serie.ticks.template.setAll({
                forceHidden: true
            });
            serie.labels.template.setAll({
                forceHidden: true
            });
            serie.set("colors", am5.ColorSet.new(base.root,{
                colors:color
            }))
        } else {
            console.error(`Type de données non reconnu. \nType donné : ${option} \ntypes acceptés : radar, pie, bar, line, dot`)
            return false
        }

        if (this.legend != null) {              // Ajoute à la légende si elle existe. Par conséquent, il faut absolument que la légende soit ajoutée AVANT les données
            this.legend.data.push(serie)
        }
        

        var s = new Serie(serie, this.getSeriesLength())
        this.series.push(s)
        return s
    }

    updateSerie(index, data, name, dataComp = null) {
        if (index > this.getSeriesLength()) {
            console.error(`Index de données invalide. La série n'a pas été ajoutée. Index donné : ${index}, index max : ${this.getSeriesLength()-1}`)
        }

        if (this.year != null) {
            this.series[index].setDataSerie(data[this.year])
        } else {
            this.series[index].setDataSerie(data)
        }
        this.series[index].setData(data)
        this.series[index].setName(name)

        if (dataComp != null) {
            this.series[index].setComp(dataComp)
        }
    }

    addBullets(serie, color) {
        // Ajoute des points sur la série de données voulue
        var base = this

        serie.bullets.push(function() {
            return am5.Bullet.new(base.root, {
                sprite: am5.Circle.new(base.root, {
                    radius: 4,
                    stroke: color,
                    fill: color
                })
            });
        });
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

        var yearTemp = last
        this.year = last
    
        slider.events.on("rangechanged", function () {
            yearTemp = first + Math.round(slider.get("start", 0) * (last - first));
            if (base.year != yearTemp) {
                base.year = yearTemp
                fun(yearTemp);
                slider.startGrip.get("label").set("text", yearTemp + "");
            }
        });
    }

    changeColor(colors) {
        var c = []
        colors.forEach(element => {
            c.push(am5.color(parseInt(element,16)))
        })
        this.graph.set("colors", c)
    }

    // Ensemble de setter et de getter
    setDataXAxis(data) {
        this.xAxis.data.setAll(data)
    }
    setDataYAxis(data) {
        this.yAxis.data.setAll(data)
    }
    setNumberFormat(format) {
        // Si il y a besoin de changer l'affichage des nombres (en % ou retirer des décimales par exemple) https://www.amcharts.com/docs/v5/concepts/formatters/formatting-numbers/
        this.root.numberFormatter.set("numberFormat", format);
    }
    setDataSerie(index, data) {
        this.series[index].setDataSerie(data)
    }
    setType(type) {
        this.type = type
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
    getType() {
        return this.type
    }
}

class Serie {
    // Cette classe permet de stocker des informations supplémentaires vis à vis des données passées au graphique, et de les réutiliser
    constructor(serie, index) {
        this.serie = serie 
        this.index = index
        this.data = null
        this.comp = null
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
    getIndex() {
        return this.index
    }
}
