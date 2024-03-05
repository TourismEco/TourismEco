function barreLine(id) {
    b =  new Graphique(id, "bar")
    b.createXAxis("year")
    b.createYAxis()
    b.createYAxis(null, {}, true)
    b.addSerie("bar","year", "value", null,"{year} : {valueY}","#83A88B" );
    b.addSerie("line","year", "valueLeft", null,"{year} : {valueY}","#52796F",{},true );
}

var color = ["#52796F","#83A88B"]


function barreLineHTMX(data,name) {
    console.log(data);
    b.updateSerie(0, data, name);
    b.updateSerie(1, data, name);
    b.setDataXAxis(data)
}
