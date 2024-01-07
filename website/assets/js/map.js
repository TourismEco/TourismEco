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

class EcoMap {
    constructor (id) {
        this.root = am5.Root.new(id);
        this.countries = null
        this.continents = null
        this.poly = []
        this.incr = 0
        this.cities = null
        this.capitals = null

        this.root.setThemes([
            am5themes_Animated.new(this.root)
        ]);

        this.map = this.root.container.children.push(am5map.MapChart.new(this.root, {
            panX: "rotateX",
            wheelX: "none",
            wheelY: "none",
            projection: am5map.geoNaturalEarth1()
        }));
    }

    toggleActive(serie, compare) {
        if (compare) {
            this.activeCompare(serie)
        } else {
            this.incr = 1
            for (var i=1; i<this.poly.length; i++) {
                this.poly[i].set("active",false)
                this.poly[i].set("interactive",true)
            }
            this.poly = [this.poly[0]]
            this.activePays(serie)
        }
    }

    addCountries(compare) {
        var base = this

        this.countries = this.map.series.push(am5map.MapPolygonSeries.new(base.root, {
            geoJSON: am5geodata_worldLow,
            geodataNames:am5geodata_lang_FR,
            exclude: ["AX","BL","BQ","BV","CW","HM","MF","SJ","SS","SX","TL","UM","AF","AQ","CC","CX","EH","FK","FO","GG","GI","GL","GQ","GS","IM","IO","JE","KP","LR","NF","NR","PM","PN","SH","SO","SZ","TF","TK","VA","WF","YT","AI","CK","GF","GP","KN","MQ","MS","NU","PS","RE","TW","ST","MR"],
            fill:am5.color("#84A98C")
        }));    
        this.behaviorSerie(this.countries)
        if (compare) {
            this.activeCompare(this.countries)
        } else {
            this.activePays(this.countries)
        }
        
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
                labelHTML: html,
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
                target.set("interactive",false)
                if (base.poly.length != 0 && base.poly[0] != target) {
                    base.poly[0].set("active", false);  
                    base.poly[0].set("interactive",true)
                }
                base.poly[0] = target;
                var result = base.getCities(target.dataItem._settings.id)
                base.addCapitals(result["capitals"])
                base.addCities(result["cities"])
            }
        })

        serie.mapPolygons.template.events.on("click", function (ev) {
            serie.zoomToDataItem(ev.target.dataItem);
            // htmx.ajax("GET","ajax.php",{values:{incr:0,id_pays:ev.target.dataItem._settings.id},swap:"beforeend"})
        });
    }

    activeCompare(serie) {
        var base = this
        var max = 2

        serie.mapPolygons.template.on("active", function(active, target) {
            if (active) {
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
            if (base.incr == 2) {
                htmx.ajax("GET","ajax.php",{values:{incr:0,id_pays:ev.target.dataItem._settings.id},swap:"beforeend"})
            } else {
                htmx.ajax("GET","ajax.php",{values:{incr:base.incr,id_pays:ev.target.dataItem._settings.id},swap:"beforeend"})
            }
        })
    }

    switchToCountries() {
        if (this.countries != null && this.continents != null) {
            this.continents.hide();
            this.countries.show();
            this.map.goHome();
        }
        if (this.cities != null) {
            this.cities.show()
            this.capitals.show()
        }
    }
    
    switchToContinent() {
        if (this.countries != null && this.continents != null) {
            this.countries.hide();
            this.continents.show();
            this.map.goHome();
        }
        if (this.cities != null) {
            this.cities.hide()
            this.capitals.hide()
        }
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

    addSwitch() {
        var base = this

        var cont = this.map.children.push(
            am5.Container.new(base.root, {
                layout: base.root.horizontalLayout,
                x: 20,
                y: 40,
            })
        );
          
        cont.children.push(
            am5.Label.new(base.root, {
                centerY: am5.p50,
                text: "Vue continents",
                fill:"#FFFFFF"
                })
        );
          
        var switchButton = cont.children.push(
            am5.Button.new(base.root, {
                themeTags: ["switch"],
                centerY: am5.p50,
                icon: am5.Circle.new(base.root, {
                    themeTags: ["icon"]
                })
            })
        );
          
        switchButton.on("active", function () {
            if (switchButton.get("active")) {
                base.switchToContinent()
            } else {
                base.switchToCountries()
            }
        });
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

    getCities(id_pays) {
        var d
        $.ajax({
            url:"../getCities.php",
            data:{id_pays:id_pays},
            method:"GET",
            async:false,
            success:function(response) {
                response["cities"] = JSON.parse(response["cities"])
                response["capitals"] = JSON.parse(response["capitals"])
                d = response
            },
            error:function(mess){
                console.log(mess.responseText)
            }
        })
        return d
    }

}


function createMap(id_pays) {
    map = new EcoMap("map")
    map.addContinents()
    map.addCountries(false)
    map.addSwitch()
    map.addZoom()    

    if (id_pays != undefined) {
        map.root.events.on("frameended",() => {
            map.zoomTo(id_pays)
            map.root.events.off("frameended")
        })
    }
}

function createMapCompare(pays) {
    map = new EcoMap("map")
    map.addCountries(true)
    map.addZoom()

    map.root.events.on("frameended",() => {
        for (var i of pays) {
            map.setActive(i)
            map.root.events.off("frameended")
        }
    })
}