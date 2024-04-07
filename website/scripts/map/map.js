class EcoMap {
    constructor (id, option, mini, index=0) {
        this.root = am5.Root.new(id);
        this.countries = null
        this.continents = null
        this.allContinents = {}
        this.continentActive = null
        this.poly = []
        this.incr = 0
        this.cities = null
        this.capitals = null
        this.option = option
        this.mini = mini
        this.index = index
        this.heatLegend = null
        this.data = null

        this.root.setThemes([
            am5themes_Animated.new(this.root)
        ]);

        this.map = this.root.container.children.push(am5map.MapChart.new(this.root, {
            panX: "translateX",
            panY: "translateY",
            wheelX: "none",
            projection: am5map.geoNaturalEarth1(),
        }));
    }

    addCountries() {
        var base = this

        this.countries = this.map.series.push(am5map.MapPolygonSeries.new(base.root, {
            geoJSON: am5geodata_worldLow,
            geodataNames:am5geodata_lang_FR,
            exclude: ["AX","BL","BQ","BV","CW","HM","MF","SJ","SS","SX","TL","UM","AF","AQ","CC","CX","EH","FK","FO","GG","GI","GL","GQ","GS","IM","IO","JE","KP","LR","NF","NR","PM","PN","SH","SO","SZ","TF","TK","VA","WF","YT","AI","CK","GF","GP","KN","MQ","MS","NU","PS","RE","TW","ST","MR"],
            valueField:"score",
            calculateAggregates: true,
            fill:am5.color("#222")
        }));    
        this.behaviorSerie(this.countries)
        this.activePays(this.countries)
    }

    addContinents() {
        var base = this

        this.continents = this.map.series.push(am5map.MapPolygonSeries.new(base.root, {
            geoJSON: am5geodata_continentsLow,
            geodataNames:am5geodata_lang_FR,
            exclude: ["AX","BL","BQ","BV","CW","HM","MF","SJ","SS","SX","TL","UM","AF","AQ","CC","CX","EH","FK","FO","GG","GI","GL","GQ","GS","IM","IO","JE","KP","LR","NF","NR","PM","PN","SH","SO","SZ","TF","TK","VA","WF","YT","AI","CK","GF","GP","KN","MQ","MS","NU","PS","RE","TW","ST","MR"],
            visible: false,
            fill:am5.color("#222")
        }));
    }

    addThisContinent(id) {
        var json
        var base = this
        if (id == "asia") {
            json = am5geodata_region_world_asiaLow
        } else if (id == "america") {
            json = am5geodata_region_world_northAmericaLow
        } else if (id == "africa") {
            json = am5geodata_region_world_africaLow
        } else if (id == "europe") {
            json = am5geodata_region_world_europeLow
        } else if (id == "oceania") {
            json = am5geodata_region_world_oceaniaLow
        }

        var s = this.map.series.push(am5map.MapPolygonSeries.new(base.root, {
            geoJSON: json,
            geodataNames:am5geodata_lang_FR,
            exclude: ["AX","BL","BQ","BV","CW","HM","MF","SJ","SS","SX","TL","UM","AF","AQ","CC","CX","EH","FK","FO","GG","GI","GL","GQ","GS","IM","IO","JE","KP","LR","NF","NR","PM","PN","SH","SO","SZ","TF","TK","VA","WF","YT","AI","CK","GF","GP","KN","MQ","MS","NU","PS","RE","TW","ST","MR"],
            visible: false,
            fill:am5.color("#222")
        }));

        this.allContinents[id] = s
        this.behaviorSerie(s)
        this.activePays(s)
    }

    behaviorSerie(serie) {
        var base = this

        serie.mapPolygons.template.setAll({
            tooltipText: "{name}",
            toggleKey: "active",
            interactive: true,
            tooltip: am5.Tooltip.new(base.root, {
                labelHTML: `<div style="display:flex;gap:10px"><img style="width: 25px;height: 25px;" src='assets/twemoji/{id}.svg'> <p style="margin:auto">{name}</p></div>`,
            }),
        });
        
        serie.mapPolygons.template.states.create("hover", {
            fill: am5.color("#52796F"),
        });
    
        serie.mapPolygons.template.states.create("active", {
            fill: am5.color("#52796F")
        });
    }

    activePays(serie) {
        var base = this

        serie.mapPolygons.template.on("active", function(active, target) {
            if (active) {
                var max = 1
                target.set("interactive",false)
                if (base.incr == max) {
                    base.incr = 0
                }
                if (base.poly.length < max) {
                    base.poly.push(target)
                } else {
                    base.poly[base.incr].set("active", false)
                    base.poly[base.incr].set("interactive", true)
                    base.poly[base.incr] = target
                }
                base.incr++
            }
        })

        serie.mapPolygons.template.events.on("click", function (ev) {
            if (base.mini) {
                if (base.option == "pays") {
                    htmx.ajax("GET","scripts/htmx/getPays.php",{values:{id_pays:ev.target.dataItem._settings.id},swap:"beforeend"})
                } else if (base.option == "comparateur") {
                    htmx.ajax("GET","scripts/htmx/getCompare.php",{values:{id_pays:ev.target.dataItem._settings.id,incr:base.index},swap:"beforeend"})
                }
            
            } else {
                if (base.option == "pays") {
                    htmx.ajax("GET","pays.php",{values:{id_pays:ev.target.dataItem._settings.id},swap:"outerHTML swap:0.5s",target:"#zones",select:"#zones"})
                } else if (base.option == "comparateur") {
                    htmx.ajax("GET","scripts/htmx/appendCompare.php",{values:{id_pays:ev.target.dataItem._settings.id,incr:getIncr()},swap:"beforeend"})
                } else if (base.option == "explorer") {
                    htmx.ajax("GET","scripts/htmx/getExplore.php",{values:{id_pays:ev.target.dataItem._settings.id},swap:"beforeend"})
                }
            }
        });
    }
    
    addZoom() {
        var base = this

        var zoom = this.map.set("zoomControl", am5map.ZoomControl.new(base.root, {}));
        zoom.homeButton.set("visible", true)
        zoom.minusButton.get("background").setAll({
            fill: am5.color("#52796F")
        })
        zoom.plusButton.get("background").setAll({
            fill: am5.color("#52796F")
        })
        zoom.homeButton.get("background").setAll({
            fill: am5.color("#52796F")
        })
    }

    addBullets(radius,color,data) {
        var base = this

        var serie = this.map.series.push(
            am5map.MapPointSeries.new(base.root, {})
        );
    
        serie.bullets.push(function() {
            var circle = am5.Circle.new(base.root, {
                radius: radius,
                tooltipText: "{title}",
                tooltipY: 0,
                fill: color,
                stroke: base.root.interfaceColors.get("background"),
                strokeWidth: 2
            });
    
            return am5.Bullet.new(base.root, {
                sprite: circle
            });
        });
    
        serie.data.setAll(data);
    
        return serie
    }  

    addCities(data) {
        if (this.cities != null) {
            this.cities.data.setAll(data)
        } else {
            this.cities = this.addBullets(5,am5.color(0xffba00),data)
        }
    }

    addCapitals(data) {
        if (this.capitals != null) {
            this.capitals.data.setAll(data)
        } else {
            this.capitals = this.addBullets(6,am5.color(0xb8602e),data)
        }
    }

    zoomTo(id_pays) {
        this.countries.zoomToDataItem(this.setActive(id_pays));
    }

    setActive(id_pays) {
        var elem = this.countries.getDataItemById(id_pays)
        elem._settings.mapPolygon.set("active",true)
        return elem
    }

    zoomToContinent(id_continent) {
        if (id_continent in this.allContinents == false) {
            this.addThisContinent(id_continent)
        }
        if (this.continentActive != null) {
            this.allContinents[this.continentActive].hide()
        }
        
        this.continentActive = id_continent
        this.allContinents[id_continent].show()
        var elem = this.continents.getDataItemById(id_continent)
        this.continents.zoomToDataItem(elem)
    }

    addHeat(data) {
        var base = this
        this.data = data

        this.countries.set("heatRules", [{
            target: base.countries.mapPolygons.template,
            dataField: "value",
            min: am5.color(0xCAD2C5),
            max: am5.color(0x354F52),
            key: "fill",
        }]);
            
        this.countries.mapPolygons.template.events.on("pointerover", function(ev) {
            if (ev.target.dataItem.get("value") == null) {
                base.heatLegend.showValue(0, "Pas de données");
            } else {
                base.heatLegend.showValue(ev.target.dataItem.get("value"));
            }
            
        });

        this.countries.events.on("datavalidated", function () {
            base.heatLegend.set("startValue", base.countries.getPrivate("valueLow"));
            base.heatLegend.set("endValue", base.countries.getPrivate("valueHigh"));
        });

        this.heatLegend = this.map.children.push(am5.HeatLegend.new(base.root, {
            orientation: "horizontal",
            startColor: am5.color(0xCAD2C5),
            endColor: am5.color(0x354F52),
            startText: base.countries.getPrivate("valueLow"),
            endText: base.countries.getPrivate("valueHigh"),
            stepCount: 5
        }));
          
        this.heatLegend.startLabel.setAll({
            fontSize: 12,
            fill: base.heatLegend.get("startColor")
        });
          
        this.heatLegend.endLabel.setAll({
            fontSize: 12,
            fill: base.heatLegend.get("endColor")
        });

        this.countries.data.setAll(data)
        updatePodium("score",data)
    }
}

var map = undefined
var miniMap = {0:undefined,1:undefined}

function createMiniMap(index,option) {
    mi = new EcoMap("miniMap"+index,option,true,index)
    mi.addCountries()
    miniMap[index] = mi
}

function createMapCatalogue(option) {
    map = new EcoMap("map",option,false)
    map.addContinents()
    map.addZoom()

    map.root.events.on("frameended",() => {
        map.zoomToContinent("europe")
        map.root.events.off("frameended")
    })
}

function createMapExplorer(data) {
    map = new EcoMap("map","explorer",false)
    map.addCountries()
    map.addZoom()
    map.addHeat(data)
}
