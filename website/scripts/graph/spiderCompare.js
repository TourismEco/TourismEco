function spiderCompare(id) {
    g = new Graphique(id, "radar")
    g.createXAxis("var")
    g.createYAxis(null, {min:0, max:100})
    g.setDataXAxis([{"var":"pib"},{"var":"Enr"},{"var":"co2"},{"var":"arrivees"},{"var":"departs"},{"var":"gpi"},{"var":"cpi"}])
    g.addSlider(updateCSpider,400,-20,50,50,90,2008,2020)
    g.addSerie("radar", "var", "value", null, "{name} : {valueY}", "#52796F")
    g.addSerie("radar", "var", "value", null, "{name} : {valueY}", "#83A88B")
}

var color = ["#52796F", "#83A88B"];
function spiderCHTMX(index, data, dataComp, name) {
    g.updateSerie(index, data, name, dataComp)
    updateCTable(index, dataComp[g.getYear()]);
}

function updateCSpider(year) {
    for (var s of g.getSeries()) {
        s.setDataSerie(s.data[year]);
        updateCTable(s.getIndex(), s.comp[year]);
    }
}

function updateCTable(index, data) {
    if (data) {      
        for (var i=0;i<data.length;i++) {
            if (isNaN(data[i]["value"] )) {
                $("#td_"+data[i]["var"]+"_"+index).html("Nan")
            } else{
                $("#td_"+data[i]["var"]+"_"+index).html(data[i]["value"])
            }
        }
    }
}