var serieLine1;
var serieLine2;


function createGraph(data1, data2, name1, name2) {
    console.log(data1)
    l = new Line("chartdiv")
    l.initXAxis("year", data1)
    l.initYAxis()
    l.addLegend()

    serieLine1 = l.addSerie(data1, name1, "#52796F", "year", "co2")
    serieLine2 = l.addSerie(data2, name2, "#83A88B", "year", "co2")

    // g.graph.set("scrollbarX", am5.Scrollbar.new(g.root, {
    //     orientation: "horizontal"
    // }));

    l.graph.appear(1000, 100);
    console.log("25")
}

function lineAjax(incr, data, name) {

    if (incr == 0) {
        serieLine1.data.setAll(data);
        serieLine1.setAll({
            name:name
        })
    } else {
        serieLine2.data.setAll(data);
        serieLine2.setAll({
            name:name
        })
    }
}

function changeVar(type) {
    serieLine1.set("valueYField",type)
    serieLine2.set("valueYField",type)
}