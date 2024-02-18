var year;

function barCompare(id) {
    b = new Graphique20(id, "bar")
    b.createXAxis("var")
    b.createYAxis()
    b.setDataXAxis([{"var":"pib"},{"var":"co2"},{"var":"arrivees"},{"var":"gpi"},{"var":"cpi"}])
    b.addSlider(updateBar,700,10,50,50,0,2008,2020)
    b.setNumberFormat("# '%'")
    b.addSerie("bar", "var", "value", null, "{name} : {valueY}", "#52796F")
    b.addSerie("bar", "var", "value", null, "{name} : {valueY}", "#83A88B")
}

function barHTMX(index,data,name) {
    b.updateSerie(index, data, name)
}

function updateBar(year) {
    for (var s of b.getSeries()) {
        s.setDataSerie(s.data[year])
    }  
}