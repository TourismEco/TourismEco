function createLine(id) {
    l = new Line(id)
    l.initXAxis("year")
    l.initYAxis()
    l.addLegend()
    l.setType("co2")
}

var color = ["#52796F","#83A88B"]
function linePaysHTMX(data, dataMean, name) {
    l.addSerie(0, data, name, color[0], "year", l.getType())
    l.addSerie(1, dataMean, "Moyenne mondiale", color[1], "year", l.getType())
    resetAnnees()
}