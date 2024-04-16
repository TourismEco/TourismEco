function halfpie(id) {
    f = new Graphique(id, "pie", {startAngle:180,endAngle:360,innerRaduis:am5.percent(40)})
    f.changeColor(["006700","446700","E49C15","BB5C00","AA0000"])
    f.addSerie("pie","value", "name", null,"{name} : {value}","#83A88B",{startAngle:180,endAngle:360} );
}

function halfpieHTMX(data, name){
    f.updateSerie(0, data, name)
}