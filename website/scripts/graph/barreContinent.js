function barreContinent(id){
    bc = new Bar(id)
    bc.initXAxis("name")
    bc.initYAxis()
}

function barreContinentHTMX(data, name){
    bc.addSerie(0, data, name, null, "name", "value")
    bc.setDataXAxis(data)
}