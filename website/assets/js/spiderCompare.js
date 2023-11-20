var rootSpider
var spider
var xAxisSpi;
var yAxisSpi;

function serieSpider(color, nom) {
    series = spider.series.push(am5radar.RadarLineSeries.new(rootSpider, {
        name:nom,
        xAxis: xAxis,
        yAxis: yAxis,
        valueYField: "value",
        categoryXField: "var",
        tooltip:am5.Tooltip.new(rootSpider, {
            labelText:"{name} : {valueY}",
        }),
            stroke:color,
            fill:color,
        }));
    
    
        series.strokes.template.setAll({
            strokeWidth: 2,
        });
    
        series.bullets.push(function () {
        return am5.Bullet.new(rootSpider, {
            sprite: am5.Circle.new(rootSpider, {
            radius: 5,
            fill: color
            })
        });
    });

    return series
}

function spider(data1, data2, name1, name2) {

    rootSpider = am5.Root.new("spider");

    rootSpider.setThemes([
        am5themes_Animated.new(rootSpider)
    ]);

    spider = rootSpider.container.children.push(am5radar.RadarChart.new(rootSpider, {
        panX: false,
        panY: false,
        wheelX: "panX",
        wheelY: "zoomX"
    }));

    cursor = spider.set("cursor", am5radar.RadarCursor.new(rootSpider, {
        behavior: "zoomX"
    }));

    cursor.lineY.set("visible", false);

    xRenderer = am5radar.AxisRendererCircular.new(rootSpider, {});
        xRenderer.labels.template.setAll({
        radius: 10,
        fill:"#FFFFFF"
    });

    yRenderer = am5radar.AxisRendererRadial.new(rootSpider, {});
        yRenderer.labels.template.setAll({
        fill:"#FFFFFF"
    });

    xAxis = spider.xAxes.push(am5xy.CategoryAxis.new(rootSpider, {
        maxDeviation: 0,
        categoryField: "var",
        renderer: xRenderer,
        tooltip: am5.Tooltip.new(rootSpider, {})
    }));

    yAxis = spider.yAxes.push(am5xy.ValueAxis.new(rootSpider, {
        renderer: yRenderer
    }));

    var series1 = serieSpider("#52796F",name1)
    var series2 = serieSpider("#83A88B",name2)
    
    series1.data.setAll(data1["2020"]);
    series2.data.setAll(data2["2020"]);
    xAxis.data.setAll(data1["2020"]);

    // Create controls
    var container = spider.children.push(am5.Container.new(rootSpider, {
        y: am5.p100,
        centerX: am5.p50,
        centerY: am5.p100,
        x: am5.p50,
        width: am5.percent(90),
        layout: rootSpider.horizontalLayout,
        paddingBottom: 10
    }));

    var firstYear = 2008
    var lastYear = 2020

    var slider = container.children.push(am5.Slider.new(rootSpider, {
        orientation: "horizontal",
        start: 1,
        centerY: am5.p50,
    }));

    slider.get("background").setAll(
        {fill:"#52796F"}
    )

    slider.startGrip.get("icon").set("forceHidden", true);
    slider.startGrip.set("label", am5.Label.new(rootSpider, {
        text: firstYear + "",
        paddingTop: 0,
        paddingRight: 0,
        paddingBottom: 0,
        paddingLeft: 0,
        fill:"#000000",
    }));

    // updateData(2020);
    var yearTemp = 2020

    slider.events.on("rangechanged", function () {
        var year = firstYear + Math.round(slider.get("start", 0) * (lastYear - firstYear));
        slider.startGrip.get("label").set("text", year + "");
        if (year != yearTemp) {
            updateData(year);
            console.log(year, yearTemp)
            yearTemp = year
        }
    });

    function updateData(year) {
        if (data1[year]) {
            series1.data.setAll(data1[year]);
            series2.data.setAll(data2[year])
        }
    }

    series1.appear(1000);
    series2.appear(1000);
    spider.appear(1000, 100);

}