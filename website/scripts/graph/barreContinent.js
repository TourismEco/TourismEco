function barreContinent(id){
    bc = new Graphique(id, "bar")
    bc.createXAxis("name","Pays")
    bc.createYAxis()
    bc.setType("co2")
    bc.addSerie("bar","name", "co2", null,"{name} : {valueY}",null );
}

function barreContinentHTMX(data, name){
    dataFilter = filterData(data["data"], bc.getType())
    bc.setDataXAxis(dataFilter)
    bc.updateSerie(0, dataFilter, name, data)
    updateInfoBC(0, data, bc.getType())
}

function changeVarContinent(type) {
    bc.setType(type)

    for (var s of bc.series) {
        data = filterData(s.getData(), type)
        bc.setDataXAxis(data)
        s.serie.set("valueYField",type)
        s.setDataSerie(data)
        updateInfoBC(s.getIndex(),s.comp,bc.getType())
    }
}

function filterData(data, type) {
    data = data.sort((a,b) => b[type] - a[type])
    return data.filter(d => d[type] != null)
}

function updateInfoBC(index, data, type) {
    if (data) {      
        if (!("max" in data) || isNaN(data["max"][type]["val"])) {
            $("#max"+index).html("-")
        } else{
            $("#max"+index).html(data["max"][type]["name"])
            $("#max_detail").html(`(${formatNumber(data["max"][type]["val"],type)})`)
        }

        if (!("min" in data) || isNaN(data["min"][type]["val"])) {
            $("#min"+index).html("-")
        } else{
            $("#min"+index).html(data["min"][type]["name"])
            $("#min_detail").html(`(${formatNumber(data["min"][type]["val"],type)})`)
        }

        if (!("avg" in data) || isNaN(data["avg"][type])) {
            $("#avg"+index).html("-")
        } else{
            $("#avg"+index).html(formatNumber(data["avg"][type],type))
            $("#avg_detail").html(`(${data["avg"][type]})`)
        }

        if (!("median" in data) || isNaN(data["median"][type]["val"])) {
            $("#med"+index).html("-")
        } else{
            $("#med"+index).html(data["median"][type]["name"])
            $("#med_detail").html(`(${formatNumber(data["median"][type]["val"],type)})`)
        }
    }
}


function addDetail(data,name,nameValue,index,type) {
    if (data == undefined) {
        $("#"+name+index).html("-")
    } else{
        $("#"+name+index).html(data[name][type]["name"])
        $("#"+name+"_detail").html(`(${formatNumber(data[name][type]["value"],type)})`)
    }
}