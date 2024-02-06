function spiderCompare(id) {
    g = new Spider(id)
    g.initXAxis("var")
    g.initYAxis()
    g.addLegend()
    g.setDataXAxis([{"var":"pib"},{"var":"Enr"},{"var":"co2"},{"var":"arrivees"},{"var":"departs"},{"var":"gpi"},{"var":"cpi"}])
    g.addSlider(updateCSpider,400,-20,50,50,90,2008,2020)
}

var color = ["#52796F", "#83A88B"];
function spiderCHTMX(index, data, dataComp, name) {
    g.addSerie(index, data, dataComp, name, color[index], "var", "value");
    g.setDataSerie(index, data[g.getYear()])
    updateCTable(index, dataComp[g.getYear()]);
}

function updateCSpider(year) {
    var i = 0
    for (var s of g.getSeries()) {
        s.setDataSerie(s.data[year]);
        updateCTable(i, s.comp[year]);
        i++
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