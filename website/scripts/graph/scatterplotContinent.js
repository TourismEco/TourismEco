function scatterplotContinent(id) {
    s = new Graphique(id, "xy")
    s.createXAxis(null, "Arrivées Touristiques")
    s.createYAxis(null, "Emissions de CO2")
    s.addSerie("dot","var", "value", null,"{nom}",color[0] );
}

function scatterHTMX(data) {
    s.updateSerie(0, data, "Emission de CO2 ")
}