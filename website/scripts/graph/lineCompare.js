function lineCompare(id) {
    l = new Line(id)
    l.initXAxis("year")
    l.initYAxis()
    l.addLegend()
    l.setType("co2")
}

var color = ["#52796F","#83A88B"]
function lineHTMX(index, data, name) {
    l.addSerie(index, data, name, color[index], "year", l.getType())
    resetAnnees()
}

function changeVar(type) {
    for (var s of l.series) {
        s.serie.set("valueYField",type)
        s.setDataSerie(s.data)
    }
    l.setType(type)
    resetAnnees()
}

function resetAnnees() {
    if (l.getSeriesLength() == 2) {
        min = Math.min(getMin(l.series[0].getData(),l.getType()), getMin(l.series[1].getData(),l.getType()))
        max = Math.max(getMax(l.series[0].getData(),l.getType()), getMax(l.series[1].getData(),l.getType()))
        l.setDataXAxis(getAnnees(min,max))
    } else {
        l.setDataXAxis(getAnnees(getMin(l.series[0].getData(),l.getType()),getMax(l.series[0].getData(),l.getType())))
    }
}

function getAnnees(min,max) {
    annees = []
    for (var i = min;i<max+1;i++) {
        annees.push({"year":i.toString()})
    }
    return annees
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