var year;

function barCompare(id) {
    b = new Graphique(id, "bar")
    b.createXAxis("var")
    b.createYAxis(null,"% de croissance")
    b.setDataXAxis([{"var":"pibParHab"},{"var":"co2"},{"var":"arriveesTotal"},{"var":"gpi"},{"var":"cpi"}])
    b.addSlider(updateBar,700,10,50,50,0,2008,2020)
    b.setNumberFormat("# '%'")
    b.addSerie("bar", "var", "value", null, "{name} : {valueY}", "#52796F")
    b.addSerie("bar", "var", "value", null, "{name} : {valueY}", "#83A88B")
}

function barHTMX(index,data,name) {
    b.updateSerie(index, data, name)
    updateTable_bar(index,data[b.getYear()]);
}

function updateBar(year) {
    for (var s of b.getSeries()) {
        s.setDataSerie(s.data[year])
        updateTable_bar(s.getIndex(),s.data[year]);
    }  
}

function updateTable_bar(index, data) {
    if (data) {      
        for (var i=0;i<data.length;i++) {
            if (data[i]["value"] == null) {
                st = "/"
            } else {
                if (data[i]["var"]) {
                    st = data[i]["value"].toFixed(2)+" %"
                }
            }
            $("#bar_"+data[i]["var"]+"_"+index).html(st)
        }
    }
}