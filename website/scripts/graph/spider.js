function spider(id) {
    g = new Graphique(id, "radar")
    g.createXAxis("var")
    g.createYAxis()
    g.setDataXAxis([{"var":"pib"},{"var":"Enr"},{"var":"co2"},{"var":"arrivees"},{"var":"departs"},{"var":"gpi"},{"var":"cpi"}])
    g.addSlider(updateSpider,400,-20,50,50,90,2008,2020)

    g.addSerie("radar", "var", "value", null, "{name} : {valueY}", "#52796F")
}

var color = ["#52796F", "#83A88B"];
function spiderHTMX(data, dataComp, name) {
    g.updateSerie(0, data, name, dataComp );
    updateTable(dataComp[g.getYear()]);
}

function updateSpider(year) {
    for (var s of g.getSeries()) {
        s.setDataSerie(s.data[year]);
        updateTable(s.comp[year]);
    }
}

function updateTable(data) {
    if (data) {
        for (var i=0;i<data.length;i++) {
            if (isNaN(data[i]["value"] )) {
                $("#td_"+data[i]["var"]+"_0").html("Nan")
            } else{
                $("#td_"+data[i]["var"]+"_0").html(data[i]["value"])
            }
        }
    }
}