function barre(id) {
    //console.log("jkdjkkqdjs");
    f = new Bar(id)
    f.initXAxis("name")
    f.initYAxis()
    
}

var color = ["#52796F","#83A88B"]


function topHTMX(data,name) {
    f.addSerie(0, data, name, null, "name", "value");
    f.setDataXAxis(data)
}
