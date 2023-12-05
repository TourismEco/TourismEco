var year;
var serieSp1;
var serieSp2;

function spider(data1, data2, name1, name2) {

    g = new Spider("spider")
    g.initXAxis("var", data1["2020"])
    g.initYAxis()
    g.addLegend()

    seriesSp1 = g.addSerie(data1["2020"], name1, "#52796F", "var", "value")
    seriesSp2 = g.addSerie(data2["2020"], name2, "#83A88B", "var", "value")

    // Create controls
    var container = g.graph.children.push(am5.Container.new(g.root, {
        centerX: am5.p0,
        centerY: am5.p50,
        width: 400,
        layout: g.root.horizontalLayout,
        paddingBottom: 50,
        paddingRight:50,
        paddingLeft:50,
        rotation:90
    }));

    var firstYear = 2008
    var lastYear = 2020

    var slider = container.children.push(am5.Slider.new(g.root, {
        orientation: "horizontal",
        start: 1,
        centerX: am5.p50,
    }));

    slider.get("background").setAll(
        {fill:"#52796F"}
    )

    slider.startGrip.get("icon").set("forceHidden", true);
    slider.startGrip.set("label", am5.Label.new(g.root, {
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
    g.graph.appear(1000, 100);
    

}


function spiderAjax(serie,data) {
    if (serie == 0) {
        seriesSp1.data.setAll(data[year]);
    } else {
        seriesSp2.data.setAll(data[year]);
    }
}