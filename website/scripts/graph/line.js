function line(id) {
    l = new Graphique(id, "line")
    l.createXAxis("year")
    l.createYAxis()
    l.setType("value")
    l.addSerie("line","year", "value", null,"Moyenne : {valueY}",color[0] );
    l.addSerie("line","year", "value", "nom","Maximum : {valueY}",color[1] );
    l.addSerie("line","year", "value", "nom","Minimum : {valueY}",color[2] );
}

var color = ["#52796F","#eb984e","#7fb3d5"]
function lineHTMX(data0, data1, data2) {
    l.updateSerie(0, data0, "Moyenne")
    l.updateSerie(1, data1, "Maximum")
    l.updateSerie(2, data2, "Minimum")
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
