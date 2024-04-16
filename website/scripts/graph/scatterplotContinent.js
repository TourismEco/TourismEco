function scatterplotContinent(id) {
    s = new Graphique(id, "xy")
    s.createXAxis()
    s.createYAxis()
    s.addLegend()
    s.addSerie("dot","var", "value", null,"{nom}",color[0] );
}

function scatterHTMX(data) {
    s.updateSerie(0, data, "Emission de CO2 ")
}