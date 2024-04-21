function lineCompare(id) {
    l = new Graphique(id, "line")
    l.createXAxis("year")
    l.createYAxis()
    l.setType("co2")
    l.addSerie("line", "year", "co2", null, "{name} : {valueY}", "#52796F")
    l.addSerie("line", "year", "co2", null, "{name} : {valueY}", "#83A88B")
}

var color = ["#52796F","#83A88B"]
function lineHTMX(index, data, name) {
    l.updateSerie(index, data["data"], name, data)
    updateInfo(index,data,l.getType())
    resetAnnees()
}

function changeVar(type) {
    l.setType(type)

    for (var s of l.series) {
        s.serie.set("valueYField",type)
        s.setDataSerie(s.data)
        updateInfo(s.getIndex(),s.comp,l.getType())
    }
    resetAnnees()
}

function resetAnnees() {
    min = Math.min(getMin(l.series[0].getData(),l.getType()), getMin(l.series[1].getData(),l.getType()))
    max = Math.max(getMax(l.series[0].getData(),l.getType()), getMax(l.series[1].getData(),l.getType()))
    l.setDataXAxis(getAnnees(min,max))
}

function updateComp(data0,data1,type) {
    end = data0.length-1;
    while (end >= 0 && data1[end][type] == null) {
        end--;
    }

    if (end == -1) {
        return
    } else {
        year = data1.length-1;
        i = count(data2)-1;
        while (i >= 0 && data1[i]["year"] != year) {
            i--;
        }

        if ($i == -1 || data1[i][type] == 0) {
            return
        } else {
            comparaison = data0[end][type]/data1[i][type];
        }   
    } 

    
}

function updateInfo(index, data, type) {
    if (data) {      
        if (!("rank" in data) || isNaN(data["rank"][type]["rank"])) {
            $("#rank"+index).html("-")
        } else{
            $("#rank"+index).html(data["rank"][type]["rank"]+"e")
        }

        if (!("evol" in data) || isNaN(data["evol"][type]["val"])) {
            $("#evol"+index).html("-")
        } else{
            $("#evol"+index).html(formatNumber(data["evol"][type]["val"],"%"))
            $("#evol_detail").html("entre "+data["evol"][type]["start"]+" et "+data["evol"][type]["end"])
        }

        if (!("covid" in data) || isNaN(data["covid"][type])) {
            $("#covid"+index).html("-")
        } else{
            $("#covid"+index).html(formatNumber(data["covid"][type],"%"))
        }

        if (!("max" in data) || isNaN(data["max"][type]["year"])) {
            $("#max"+index).html("-")
        } else{
            $("#max"+index).html(data["max"][type]["year"])
            $("#max_detail").html(`(${formatNumber(data["max"][type]["val"],type)})`)
        }

        if (!("min" in data) || isNaN(data["min"][type]["year"])) {
            $("#min"+index).html("-")
        } else{
            $("#min"+index).html(data["min"][type]["year"])
            $("#min_detail").html(`(${formatNumber(data["min"][type]["val"],type)})`)
        }

        if (!("comp" in data) || isNaN(data["comp"][type]["val"])) {
            $("#comp"+index).html("-")
        } else{
            if (data["comp"][type]["val"] < 1) {
                $("#comp"+index).html(Math.pow(data["comp"][type]["val"],-1).toFixed(2)+" fois")
                $("#comp_detail").html("inférieure à la moyenne")
            } else {
                $("#comp"+index).html(data["comp"][type]["val"].toFixed(2)+" fois")
                $("#comp_detail").html("supérieure à la moyenne")
            }
            
        }
    }
}

function getAnnees(min,max) {
    annees = []
    for (var i = min;i<max+1;i++) {
        annees.push({"year":i})
    }
    return annees
}

function getMin(data,type) {
    if (data == null) {
        return 2077
    }
    min = 0
    while (min < data.length && data[min][type] == null) {
        min++
    }
    if (min == data.length) {
        return 2077
    }
    return data[min]["year"]
}

function getMax(data, type) {
    if (data == null) {
        return 1984
    }
    max = data.length - 1
    while (max > 0 && data[max][type] == null) {
        max--
    }
    if (max <= 0) {
        return 1984
    } 
    return data[max]["year"]
}