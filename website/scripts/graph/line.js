function line(id) {
    color = ["#52796F","#eb984e","#7fb3d5"]
    l = new Graphique(id, "line")
    l.createXAxis("year")
    l.createYAxis()
    l.setType("co2")
    l.addSerie("line","year", "co2", null,"Moyenne : {valueY} ", color[0] );
    l.addSerie("line","year", "co2", null,"Maximum : {valueY}", color[1] );
    l.addSerie("line","year", "co2", null,"Minimum : {valueY}", color[2] );
}


function lineHTMXContient(data0, data1, data2) {
    console.log("m",data0,data1,data2)
    l.updateSerie(0, data0, "Moyenne")
    l.updateSerie(1, data1["data"], "Maximum", data1)
    l.updateSerie(2, data2["data"], "Minimum", data2)
    updateInfoMax(data1,l.getType())
    updateInfoMin(data2,l.getType())
    resetAnneesL()
}


function updateInfoMax(data, type) {
    if (data) {      
        console.log(data);
        if (!("max" in data) || isNaN(data["max"][type]["year"])) {
            $("#maxLine").html("-")
        } else{
            $("#maxLine").html(data["max"][type]["year"])
            $("#maxLine_detail").html(`(${formatNumber(data["max"][type]["val"],type)})`)
        }

        if (!("evol" in data) || isNaN(data["evol"][type]["val"])) {
            $("#maxEvol").html("-")
        } else{
            $("#maxEvol").html(formatNumber(data["evol"][type]["val"],"%"))
            $("#maxEvol_detail").html("entre "+data["evol"][type]["start"]+" et "+data["evol"][type]["end"])
        }
    }
}

function updateInfoMin(data, type) {
    if (data) {      
        if (!("min" in data) || isNaN(data["min"][type]["year"])) {
            $("#minLine").html("-")
        } else{
            $("#minLine").html(data["max"][type]["year"])
            $("#minLine_detail").html(`(${formatNumber(data["max"][type]["val"],type)})`)
        }

        if (!("evol" in data) || isNaN(data["evol"][type]["val"])) {
            $("#minEvol").html("-")
        } else{
            $("#minEvol").html(formatNumber(data["evol"][type]["val"],"%"))
            $("#minEvol_detail").html("entre "+data["evol"][type]["start"]+" et "+data["evol"][type]["end"])
        }
    }
}

function changeVarL(type) {
    for (var s of l.series) {
        s.serie.set("valueYField",type)
        s.setDataSerie(s.data)
    }
    updateInfoMax(l.series[1].comp,type)
    updateInfoMin(l.series[2].comp,type)
    l.setType(type)
    resetAnneesL()
    
}


function resetAnneesL() {
    if (l.getSeriesLength() == 3) {
        min = Math.min(getMin(l.series[0].getData(),l.getType()), getMin(l.series[1].getData(),l.getType()))
        max = Math.max(getMax(l.series[0].getData(),l.getType()), getMax(l.series[1].getData(),l.getType()))
        l.setDataXAxis(getAnnees(min,max))
    } else {
        l.setDataXAxis(getAnnees(getMin(l.series[0].getData(),l.getType()),getMax(l.series[0].getData(),l.getType())))
    }
}
