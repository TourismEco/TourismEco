function barreLine(id) {
    b =  new Graphique(id, "bar")
    b.createXAxis("year","Année")
    b.createYAxis(null,"PIB par habitant ($)")
    b.createYAxis(null, "Arrivées touristiques", {}, true)
    b.addSerie("bar","year", "value", null,"{year} : {valueY}","#83A88B" );
    b.addSerie("line","year", "valueLeft", null,"{year} : {valueY}","#52796F",{},true );
}

var color = ["#52796F","#83A88B"]


function barreLineHTMX(data,name) {
    b.updateSerie(0, data, name);
    b.updateSerie(1, data, name);
    b.setDataXAxis(data)
}
