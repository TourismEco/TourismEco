function barreLine(){
   
    b = new BarLine("barreLine")
    b.initXAxis("var",  [{"var":"2008"},{"var":"2009"},{"var":"2010"},{"var":"2011"},{"var":"2012"},{"var":"2013"}])
    b.initYAxis()
    b.addLegend()
    b.graph.appear(1000, 100);
    
    
}

var color = ["#52796F","#83A88B"]

function barreLineAjax(incr,data,name) {
    console.log(data, year, name);

    if (b.series.length == incr) {
        b.addSerie(data, name, color[incr], "var", "value");
        b.addSerie2(data, name, color[incr], "var", "value");
    } else {
        b.addSerie2(data, name, color[incr], "var", "line");
        
        b.series[incr].data = data
        b.series[incr].serie.data.setAll(data);
        b.series[incr].serie.setAll({
            name:name
        })
        
    }
}

