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
                src='assets/img/{id}.jpg'>

            <div style="grid-column: 2;
                grid-row: 1;
                justify-content: center;">
                    <h1 style="font-size: 20px;
                    color: white;  ">{name}</h1>
            </div>

            <div style="grid-column: 1;
                grid-row: 1;
                width: 100%;">
                <img style="width: 50px;
                    height: 50px;" src='assets/twemoji/{id}.svg'>
            </div>
        </div>
        `

class EcoMap {
    constructor (id, option, mini, index=0) {
        this.root = am5.Root.new(id);
        this.countries = null
        this.continents = null
        this.poly = []
        this.incr = 0
        this.cities = null
        this.capitals = null
        this.option = option
        this.mini = mini
        this.max = {"pays":1, "compare":1, "continent":1}
        this.index = index

        this.root.setThemes([
            am5themes_Animated.new(this.root)
        ]);

        this.map = this.root.container.children.push(am5map.MapChart.new(this.root, {
            panX: mini ? "none" : "rotateX",
            panY : mini ? "none" : "rotateY",
            wheelX: "none",
            wheelY: "none",
            projection: am5map.geoNaturalEarth1()
        }));
    }

    togglePays() {
        this.option = "pays"
        this.incr = 1
        for (var i=1; i<this.poly.length; i++) {
            this.poly[i].set("active",false)
            this.poly[i].set("interactive",true)
        }
        this.poly = [this.poly[0]]

        this.showCountries()
    }

    toggleCompare() {
        this.option = "compare"
        this.cities.data.clear()
        this.capitals.data.clear()
        this.map.goHome();

        this.showCountries()
    }

    toggleContinent() {
        this.option = "continent"
        this.cities.data.clear()
        this.capitals.data.clear()
        this.map.goHome();

        this.showContinents()
    }

    showCountries() {
        this.continents.hide();
        this.countries.show();
    }
    showContinents() {
        this.countries.hide();
        this.continents.show();
    }

    addCountries() {
        var base = this

        this.countries = this.map.series.push(am5map.MapPolygonSeries.new(base.root, {
            geoJSON: am5geodata_worldLow,
            geodataNames:am5geodata_lang_FR,
            exclude: ["AX","BL","BQ","BV","CW","HM","MF","SJ","SS","SX","TL","UM","AF","AQ","CC","CX","EH","FK","FO","GG","GI","GL","GQ","GS","IM","IO","JE","KP","LR","NF","NR","PM","PN","SH","SO","SZ","TF","TK","VA","WF","YT","AI","CK","GF","GP","KN","MQ","MS","NU","PS","RE","TW","ST","MR"],
            fill:am5.color("#84A98C")
        }));    
        this.behaviorSerie(this.countries)
        this.activePays(this.countries)
    }

    addContinents() {
        var base = this

        this.continents = this.map.series.push(am5map.MapPolygonSeries.new(base.root, {
            geoJSON: am5geodata_continentsLow,
            geodataNames:am5geodata_lang_FR,
            exclude: ["antarctica"],
            visible: false,
            fill:am5.color("#84A98C")
        }));
        this.behaviorSerie(this.continents)
        this.activePays(this.continents)
    }

    behaviorSerie(serie) {
        var base = this

        serie.mapPolygons.template.setAll({
            tooltipText: "{name}",
            toggleKey: "active",
            interactive: true,
            tooltip: am5.Tooltip.new(base.root, {
                labelHTML: base.mini ? "{name}" : html,
            }),
        });
        
        serie.mapPolygons.template.states.create("hover", {
            fill: am5.color("#CAD2C5"),
        });
    
        serie.mapPolygons.template.states.create("active", {
            fill: am5.color("#52796F")
        });
    }

    activePays(serie) {
        var base = this

        serie.mapPolygons.template.on("active", function(active, target) {
            if (active) {
                var max = base.max[base.option]
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
            if (base.option == "compare") {
                serie.zoomToDataItem(ev.target.dataItem);
                htmx.ajax("GET","scripts/htmx/getCompare.php",{values:{map:true,id_pays:ev.target.dataItem._settings.id,incr:base.index},swap:"beforeend"})
            } else if (base.option == "pays") {
                serie.zoomToDataItem(ev.target.dataItem);
                htmx.ajax("GET","scripts/htmx/getPays.php",{values:{map:true,id_pays:ev.target.dataItem._settings.id},swap:"beforeend"})
            } else {
                serie.zoomToDataItem(ev.target.dataItem);
                htmx.ajax("GET","scripts/htmx/getContinent.php",{values:{map:true,id_pays:ev.target.dataItem._settings.id},swap:"beforeend"})
            }
        });
    }
    
    addZoom() {
        var base = this

        var zoom = this.map.set("zoomControl", am5map.ZoomControl.new(base.root, {}));
        zoom.homeButton.set("visible", true)
        zoom.minusButton.get("background").setAll({
            fill: am5.color("#CAD2C5")
        })
        zoom.plusButton.get("background").setAll({
            fill: am5.color("#CAD2C5")
        })
        zoom.homeButton.get("background").setAll({
            fill: am5.color("#CAD2C5")
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
}

var map = undefined
var miniMap = {0:undefined,1:undefined}

function createMap() {
    $("#container-map").removeClass("hide")
    if (map == undefined) {
        map = new EcoMap("map","pays",false)
        map.addContinents()
        map.addCountries()
        map.addZoom()    
    } else {
        map.togglePays()
        map.map.show()
    }
}

function createMiniMap(index,option) {
    if (miniMap[index] == undefined) {
        mi = new EcoMap("miniMap"+index,option,true,index)
        mi.addContinents()
        mi.addCountries()
        miniMap[index] = mi
    } else {
        miniMap[index].togglePays()
        miniMap[index].map.show()
    }
}

function createMapCompare() {
    $("#container-map").removeClass("hide")
    if (map == undefined) {
        map = new EcoMap("map","compare",false)
        map.addContinents()
        map.addCountries()
        map.addZoom()
    } else {
        map.toggleCompare()
        map.map.show()
    }
}

function createMapContinent() {
    $("#container-map").removeClass("hide")
    if (map == undefined) {
        map = new EcoMap("map","continent",false)
        map.addContinents()
        map.addCountries()
        map.addZoom()    
        map.showContinents()
    } else {
        map.toggleContinent()
        map.map.show()
    }
}

function hideMap() {
    $("#container-map").addClass("hide")
    if (map != undefined) {
        map.map.hide()
    }
}