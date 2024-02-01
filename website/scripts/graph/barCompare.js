var year;

function barCompare(id) {
    b = new Bar(id)
    b.initXAxis("var")
    b.initYAxis()
    b.addLegend()
    b.setDataXAxis([{"var":"pib"},{"var":"co2"},{"var":"arrivees"},{"var":"gpi"},{"var":"cpi"}])
    b.addSlider(updateBar,700,10,50,50,0,2008,2020)
    b.setNumberFormat("# '%'")
}

var color = ["#52796F","#83A88B"]
function barHTMX(index,data,name) {
    b.addSerie(index, data, name, color[index], "var", "value")
    b.setDataSerie(index, data[b.getYear()])
}

function updateBar(year) {
    for (var s of b.getSeries()) {
        s.setDataSerie(s.data[year])
    }  
}