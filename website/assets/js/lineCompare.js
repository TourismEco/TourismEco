var serieLine1;
var serieLine2;


function createGraph(data, name1, name2) {
    g = new Line("chartdiv")
    g.initXAxis("year", data)
    g.initYAxis()

    serieLine1 = g.addSerie(data, name1, "#52796F", "year", "value")
    serieLine2 = g.addSerie(data, name2, "#83A88B", "year", "value2")

    g.graph.set("scrollbarX", am5.Scrollbar.new(g.root, {
        orientation: "horizontal"
    }));

    serieLine1.appear(1000);
    serieLine2.appear(1000);
    g.graph.appear(1000, 100);

}

function lineAjax(data) {
    serieLine1.data.setAll(data);
    serieLine2.data.setAll(data);
}