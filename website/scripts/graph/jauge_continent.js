function halfpie(id) {
    f = new Graphique(id, "pie", {startAngle:180,endAngle:360,innerRadius:am5.percent(60)})
    f.addSerie("pie","value", "name", null,"{name} : {value}",["#006700","#446700","#E49C15","#BB5C00","#AA0000"],{startAngle:180,endAngle:360} );
}

function halfpieHTMX(data, name){
    f.updateSerie(0, data, name)
}