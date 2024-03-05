function createLine(id) {
    l = new Graphique(id, "line")
    l.createXAxis("year")
    l.createYAxis()
    l.setType("co2")
    l.addSerie("line","year", "co2", null,"{name} : {valueY}","#52796F" );
    l.addSerie("line","year", "co2", null,"Moyenne mondiale : {valueY}","#83A88B" );

}

var color = ["#52796F","#83A88B"]
function linePaysHTMX(data, dataMean, name) {
    l.updateSerie(0, data, name)
    l.updateSerie(1, dataMean)
    resetAnnees()
}