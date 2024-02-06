

function barreLine(id){
   
    b = new Bar("barreLine")
    b.initXAxis("var")
    b.initYAxis()
    b.addLegend()
    b.graph.appear(1000, 100);
    b.initYAxisLeft()
    
}

var color = ["#52796F","#83A88B"]


function barreLineHTMX(data,name) {
    b.addSerie(0, data, name, color[0], "var", "value");
    b.addLine(1, data, name, color[1], "var", "valueLeft");
    b.setDataXAxis(getAnnees(getMin(data,"var"), getMax(data,"var")))
}

function getAnnees(min,max) {
    annees = []
    for (var i = min;i<max+1;i++) {
        annees.push({"var":i.toString()})
    }
    return annees
}

function getMin(data,type) {
    min = 0
    while (min < data.length && data[min][type] == null) {
        min++
    }
    if (min == data.length) {
        return 2077
    }   
    return data[min][type]
}

function getMax(data, type) {
    max = data.length - 1
    while (max > 0 && data[max][type] == null) {
        max--
    }
    if (max <= 0) {
        return 1984
    } 
    return data[max][type]
}

