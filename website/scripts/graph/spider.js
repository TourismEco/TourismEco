var year;

function spider() {

    g = new Spider("spider")
    g.initXAxis("var", [{"var":"pib"},{"var":"Enr"},{"var":"co2"},{"var":"arrivees"},{"var":"departs"},{"var":"gpi"},{"var":"cpi"}])
    g.initYAxis()
    g.addLegend()

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
    year = 2020

    slider.events.on("rangechanged", function () {
        year = firstYear + Math.round(slider.get("start", 0) * (lastYear - firstYear));
        slider.startGrip.get("label").set("text", year + "");
        if (year != yearTemp) {
            updateData(year);
            yearTemp = year
        }
    });

    function updateData(year) {
        var i = 0
        for (var s of g.series) {
            s.serie.data.setAll(s.data[year]);
            updateTable(i, s.comp[year]);
            i++
        }
            
    }

    g.graph.appear(1000, 100);
    

}

var color = ["#52796F", "#83A88B"];
function spiderAjax(incr, data, dataComp, name) {
    console.log(data[year], year, name);

    if (g.series.length == incr) {
        g.addSerie(data, dataComp, name, color[incr], "var", "value");
        g.series[incr].serie.data.setAll(data[year]);
        updateTable(incr,dataComp[year]);
    } else {
        g.series[incr].data = data;
        g.series[incr].comp = dataComp;
        g.series[incr].serie.data.setAll(data[year]);
        g.series[incr].serie.setAll({
            name: name,
        });
        updateTable(incr,dataComp[year]);
    }
}
function updateTable(incr,data) {
    if (data) {      
        for (var i=0;i<data.length;i++) {
            if (isNaN(data[i]["value"] )){
                $("#td_"+data[i]["var"]+"_"+incr).html("Nan")
            }else{
                $("#td_"+data[i]["var"]+"_"+incr).html(data[i]["value"])
            }
        }
    }
}
