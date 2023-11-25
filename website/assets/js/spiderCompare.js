var rootSpider
var spider
var xAxisSpi;
var yAxisSpi;
var year;
var serieSp1;
var serieSp2;

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

    rootSpider = newRoot("spider")
    fig = am5radar.RadarChart.new(rootSpider, {})

    spider = addToRoot(rootSpider, fig, am5radar.RadarCursor);

    xAxis = spider.xAxes.push(am5xy.CategoryAxis.new(rootSpider, {
        maxDeviation: 0,
        categoryField: "var",
        renderer: newXRenderer(rootSpider, am5radar.AxisRendererCircular),
        tooltip: am5.Tooltip.new(rootSpider, {})
    }));

    yAxis = spider.yAxes.push(am5xy.ValueAxis.new(rootSpider, {
        renderer: newYRenderer(rootSpider, am5radar.AxisRendererRadial)
    }));

    seriesSp1 = serieSpider("#52796F",name1)
    seriesSp2 = serieSpider("#83A88B",name2)
    
    seriesSp1.data.setAll(data1["2020"]);
    seriesSp2.data.setAll(data2["2020"]);
    xAxis.data.setAll(data1["2020"]);

    // Create controls
    var container = spider.children.push(am5.Container.new(rootSpider, {
        centerX: am5.p0,
        centerY: am5.p50,
        width: 400,
        layout: rootSpider.horizontalLayout,
        paddingBottom: 50,
        paddingRight:50,
        paddingLeft:50,
        rotation:90
    }));

    var firstYear = 2008
    var lastYear = 2020

    var slider = container.children.push(am5.Slider.new(rootSpider, {
        orientation: "horizontal",
        start: 1,
        centerX: am5.p50,
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
        rotation:-90
    }));

    // updateData(2020);
    var yearTemp = 2020

    slider.events.on("rangechanged", function () {
        year = firstYear + Math.round(slider.get("start", 0) * (lastYear - firstYear));
        slider.startGrip.get("label").set("text", year + "");
        if (year != yearTemp) {
            updateData(year);
            yearTemp = year
        }
    });

    function updateData(year) {
        if (data1[year]) {
            seriesSp1.data.setAll(data1[year]);
            seriesSp2.data.setAll(data2[year])
        }
    }

    seriesSp1.appear(1000);
    seriesSp2.appear(1000);
    spider.appear(1000, 100);

}


function spiderAjax(serie,data) {
    if (serie == 0) {
        seriesSp1.data.setAll(data[year]);
    } else {
        seriesSp2.data.setAll(data[year]);
    }
}