var serieLine1;
var serieLine2;


function createGraph() {
    l = new Line("chartdiv")

    annees = []
    for (var i = 1990;i<2024;i++) {
        annees.push({"year":i.toString()})
    }
    l.initXAxis("year", annees)

    l.initYAxis()
    l.addLegend()

    // g.graph.set("scrollbarX", am5.Scrollbar.new(g.root, {
    //     orientation: "horizontal"
    // }));

    l.graph.appear(1000, 100);
    console.log("25")
}

var color = ["#52796F","#83A88B"]
function lineAjax(incr, data, name) {
    console.log(data)
    if (l.series.length == incr) {
        l.addSerie(data, name, color[incr], "year", "co2")
        if (incr == 1) {
            l.xAxis.data.setAll(getAnnees(l.series[0].data,l.series[1].data,l.type))
        }
    } else {
        l.series[incr].data = data
        l.series[incr].serie.data.setAll(data);
        l.series[incr].serie.setAll({
            name:name
        })
    }
}

function getMin(data,type) {
    min = 0
    while (min < data.length && data[min][type] == null) {
        min++
    }
    if (min == data.length) {
        return 2026
    }   
    return data[min]["year"]
}

function getMax(data, type) {
    max = data.length - 1
    while (max > 0 && data[max][type] == null) {
        max--
    }
    if (max <= 0) {
        return 1800
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
    for (var s of l.series) {
        s.serie.set("valueYField",type)
        s.serie.data.setAll(s.data)
    }
    l.type = type
    l.xAxis.data.setAll(getAnnees(l.series[0].data,l.series[1].data,type))
}