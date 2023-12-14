var serieLine1;
var serieLine2;


function createGraph(data1, data2, name1, name2) {
    console.log(data1)
    l = new Line("chartdiv")
    l.initXAxis("year", getAnnees(data1, data2, "co2"))
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

function getMin(data,type) {
    min = 0
    while (min < data.length && data[min][type] == null) {
        min++
    }   
    return data[min]["year"]
}

function getMax(data, type) {
    max = data.length - 1
    while (max > 0 && data[max][type] == null) {
        max--
    }
    return data[max]["year"]
}

function getAnnees(data1, data2, type) {
    min = Math.min(getMin(data1,type),getMin(data2,type))
    max = Math.max(getMax(data1,type),getMax(data2,type))

    annees = []
    for (var i = min;i<max+1;i++) {
        annees.push({"year":i.toString()})
    }
    return annees
}

function changeVar(type) {
    
    serieLine1.set("valueYField",type)
    serieLine2.set("valueYField",type)
    serieLine1.data.setAll(serieLine1.data._values)
    serieLine2.data.setAll(serieLine2.data._values)

    l.xAxis.data.setAll(getAnnees(serieLine1.data._values,serieLine2.data._values,type))
}