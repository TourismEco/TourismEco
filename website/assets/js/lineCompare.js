var serieLine1;
var serieLine2;


function createGraph() {
    l = new Line("chartdiv")

    l.initXAxis("year", getAnnees(1990,2024))
    l.initYAxis()
    l.addLegend()

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
        return 2077
    }   
    return data[min]["year"]
}

function getMax(data, type) {
    max = data.length - 1
    while (max > 0 && data[max][type] == null) {
        max--
    }
    if (max <= 0) {
        return 1984
    } 
    return data[max]["year"]
}

function getAnnees(min,max) {
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

    min = Math.min(getMin(data1,type),getMin(data2,type))
    max = Math.max(getMax(data1,type),getMax(data2,type))
    l.xAxis.data.setAll(getAnnees(min,max))
}