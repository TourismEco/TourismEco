function spider(id) {
    g = new Spider(id)
    g.initXAxis("var")
    g.initYAxis()
    g.addLegend()
    g.setDataXAxis([{"var":"pib"},{"var":"Enr"},{"var":"co2"},{"var":"arrivees"},{"var":"departs"},{"var":"gpi"},{"var":"cpi"}])
    g.addSlider(updateSpider,400,-20,50,50,90,2008,2020)
}

var color = ["#52796F", "#83A88B"];
function spiderHTMX(data, dataComp, name) {
    g.addSerie(0, data, dataComp, name, color[0], "var", "value");
    g.setDataSerie(0, data[g.getYear()])
    // updateTable(0, dataComp[g.getYear()]);
}

function updateSpider(year) {
    var i = 0
    for (var s of g.getSeries()) {
        s.setDataSerie(s.data[year]);
        // updateTable(i, s.comp[year]);
        i++
    }  
}

function updateTable(data) {
    if (data) {      
        for (var i=0;i<data.length;i++) {
            if (isNaN(data[i]["value"] )) {
                $("#td_"+data[i]["var"]+"_").html("Nan")
            } else{
                $("#td_"+data[i]["var"]+"_").html(data[i]["value"])
            }
        }
    }
}