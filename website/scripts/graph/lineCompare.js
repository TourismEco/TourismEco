function lineCompare(id) {
    l = new Graphique(id, "line")
    l.createXAxis("year")
    l.createYAxis()
    l.setType("co2")
    l.addSerie("line", "year", "co2", null, "{name} : {valueY}", "#52796F")
    l.addSerie("line", "year", "co2", null, "{name} : {valueY}", "#83A88B")
}

var color = ["#52796F","#83A88B"]
function lineHTMX(index, data, name) {
    l.updateSerie(index, data, name)
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
    min = Math.min(getMin(l.series[0].getData(),l.getType()), getMin(l.series[1].getData(),l.getType()))
    max = Math.max(getMax(l.series[0].getData(),l.getType()), getMax(l.series[1].getData(),l.getType()))
    l.setDataXAxis(getAnnees(min,max))
}

function getAnnees(min,max) {
    annees = []
    for (var i = min;i<max+1;i++) {
        annees.push({"year":i})
    }
    return annees
}

function getMin(data,type) {
    if (data == null) {
        return 2077
    }
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
    if (data == null) {
        return 1984
    }
    max = data.length - 1
    while (max > 0 && data[max][type] == null) {
        max--
    }
    if (max <= 0) {
        return 1984
    } 
    return data[max]["year"]
}