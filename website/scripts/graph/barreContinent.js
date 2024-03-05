function barreContinent(id){
    bc = new Graphique(id, "bar")
    bc.createXAxis("name")
    bc.createYAxis()
    bc.addSerie("bar","name", "value", null,"{name} : {valueY}",null );
}

function barreContinentHTMX(data, name){
    bc.updateSerie(0, data, name)
    bc.setDataXAxis(data)
}