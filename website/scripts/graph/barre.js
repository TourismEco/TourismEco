function barre(id) {
    //console.log("jkdjkkqdjs");
    f = new Graphique(id, "bar")
    f.createXAxis("var")
    f.createYAxis()
    f.addSerie("bar","var", "value", null,"{name} : {valueY}","#83A88B" );
    
}


function topHTMX(data,name) {
    f.updateSerie(0, data, name);
    f.setDataXAxis(data)
}
