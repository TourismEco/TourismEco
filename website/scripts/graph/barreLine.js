function barreLine(id) {
    b = new Bar("barreLine")
    b.initXAxis("year")
    b.initYAxis()
    b.addLegend()
    b.initYAxisLeft()
}

var color = ["#52796F","#83A88B"]


function barreLineHTMX(data,name) {
    b.addSerie(0, data, "PIB/Hab", color[0], "year", "value");
    b.addLine(1, data, "Arrivées touristique", color[1], "year", "valueLeft");
    b.setDataXAxis(data)
}
