<!-- Styles -->
<?php
require("../getDB.php");
$cur = getDB();

$result = $cur->query("SELECT * FROM villes WHERE id_pays = 'FR'");

$data = array();
while ($rs = $result->fetch_assoc()) {
    $data[] = "{id:".$rs['id'] .",
                title:'".$rs['nom']."',
                geometry:{type:'Point', coordinates:[".$rs['lon'] .",".$rs['lat'] ."]}}";
}

$data = implode(",", $data);
?>

<!-- Styles -->
<style>
    #chartdiv {
        width: 100%;
        height: 95%;
        background-color: #354F52;
    }
</style>
    
    <!-- Resources -->
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/map.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/lang/FR.js"></script>
    
    <!-- Chart code -->
<script>
        
    am5.ready(function() {
        
        // Set up
        var root = am5.Root.new("chartdiv");

        root.setThemes([
            am5themes_Animated.new(root)
        ]);
        
        var chart = root.container.children.push(am5map.MapChart.new(root, {
            panX: "rotateX",
            projection: am5map.geoNaturalEarth1()
        }));
        
        
        // Continents
        var continentSeries = chart.series.push(am5map.MapPolygonSeries.new(root, {
            geoJSON: am5geodata_continentsLow,
            geodataNames:am5geodata_lang_FR,
            exclude: ["antarctica"],
            fill:am5.color("#84A98C")
        }));
        
        // Pays
        var countrySeries = chart.series.push(am5map.MapPolygonSeries.new(root, {
            geoJSON: am5geodata_worldLow,
            geodataNames:am5geodata_lang_FR,
            exclude: ["AX","BL","BQ","BV","CW","HM","MF","SJ","SS","SX","TL","UM","AF","AQ","CC","CX","EH","FK","FO","GG","GI","GL","GQ","GS","IM","IO","JE","KP","LR","NF","NR","PM","PN","SH","SO","SZ","TF","TK","VA","WF","YT"],
            visible: false,
            fill:am5.color("#84A98C")
        }));
        

        var previousPolygon;
        function setActionsSeries(serie) {

            serie.mapPolygons.template.setAll({
                tooltipText: "{name}",
                toggleKey: "active",
                interactive: true
            });
            
            serie.mapPolygons.template.states.create("hover", {
                fill: am5.color("#CAD2C5"),
            });

            serie.mapPolygons.template.events.on("click", function (ev) {
                serie.zoomToDataItem(ev.target.dataItem);
            });

            serie.mapPolygons.template.states.create("active", {
                fill: am5.color("#52796F")
            });

            
            serie.mapPolygons.template.on("active", function(active, target) {
                target.set("interactive",false)
                if (previousPolygon && previousPolygon != target) {
                    previousPolygon.set("active", false);  
                    previousPolygon.set("interactive",true)
                }
                previousPolygon = target;
            });

        }

        setActionsSeries(countrySeries)
        setActionsSeries(continentSeries)

    
        // Home
        var homeButton = chart.children.push(am5.Button.new(root, {
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
            chart.goHome();
        });

        // Fonctions
        function switchToCountries(){
            continentSeries.hide();
            countrySeries.show();
            chart.goHome();
        }

        function switchToContinent(){
            countrySeries.hide();
            continentSeries.show();
            chart.goHome();
        }

        // Add projection buttons
        var buttons = chart.children.push(am5.Container.new(root, {
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

        function createButton(text, fun) {
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
            }
        ));
        
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

        // Villes
        var citySeries = chart.series.push(
            am5map.MapPointSeries.new(root, {})
        );

        // visible city circles
        citySeries.bullets.push(function() {
            var circle = am5.Circle.new(root, {
                radius: 5,
                tooltipText: "{title}",
                tooltipY: 0,
                fill: am5.color(0xffba00),
                stroke: root.interfaceColors.get("background"),
                strokeWidth: 2
            });

            return am5.Bullet.new(root, {
                sprite: circle
            });
        });

        var data = [<?= $data;?>];

        citySeries.data.setAll(data);

        createButton("Monde", switchToContinent);
        createButton("Continents", switchToContinent);
        createButton("Pays", switchToCountries);
    
    }); // end am5.ready()

</script>
    
    <!-- HTML -->
<div id="chartdiv"></div>