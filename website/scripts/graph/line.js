function line(id) {
    l = new Line(id)
    l.initXAxis("year")
    l.initYAxis("value")
    l.addLegend()
    l.setType("value")
}

var color = ["#52796F","#eb984e","#7fb3d5"]
function lineHTMX(data0, data1, data2) {
    l.addSerie(0, data0, "Moyenne", color[0], "year", l.getType())
    l.addSerie(1, data1, "Maximum", color[1], "year", l.getType())
    l.addSerie(2, data2, "Minimum", color[2], "year", l.getType())
    resetAnneesL()
}

function changeVar(type) {
    for (var s of l.series) {
        s.serie.set("valueYField",type)
        s.setDataSerie(s.data)
    }
    l.setType(type)
    resetAnneesL()
}


function resetAnneesL() {
    if (l.getSeriesLength() == 3) {
        min = Math.min(getMin(l.series[0].getData(),l.getType()), getMin(l.series[1].getData(),l.getType()))
        max = Math.max(getMax(l.series[0].getData(),l.getType()), getMax(l.series[1].getData(),l.getType()))
        l.setDataXAxis(getAnnees(min,max))
    } else {
        l.setDataXAxis(getAnnees(getMin(l.series[0].getData(),l.getType()),getMax(l.series[0].getData(),l.getType())))
    }
}
