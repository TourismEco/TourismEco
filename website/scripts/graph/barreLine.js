function barreLine(id) {
    b =  new Graphique(id, "bar")
    b.createXAxis("year","Année")
    b.createYAxis(null,"PIB par habitant ($)")
    b.createYAxis(null, "Arrivées touristiques", {}, true)
    b.addSerie("bar","year", "value", null,"{year} : {valueY}","#83A88B" );
    b.addSerie("line","year", "valueLeft", null,"{year} : {valueY}","#52796F",{},true );
}

var color = ["#52796F","#83A88B"]


function barreLineHTMX(data,name) {
    console.log(data)
    b.updateSerie(0, data["data"], name);
    b.updateSerie(1, data["data"], name);
    b.setDataXAxis(data["data"])
    updateInfoBL(1,data)
}


function updateInfoBL(index, data) {
    if (data) {      
       
        if (!("covidImpactPib" in data) || isNaN(data["covidImpactPib"])) {
            $("#covidImpactPib"+index).html("-")
        } else{
            $("#covidImpactPib"+index).html(formatNumber(data["covidImpactPib"],"%"))
        }

        if (!("covidImpactTourisme" in data) || isNaN(data["covidImpactTourisme"])) {
            $("#covidImpactTourisme"+index).html("-")
        } else{
            $("#covidImpactTourisme"+index).html(formatNumber(data["covidImpactTourisme"],"%"))
        }

        if (!("minTourisme" in data) || isNaN(data["minTourisme"]["year"])) {
            $("#minTourisme"+index).html("-")
        } else{
            $("#minTourisme"+index).html(data["minTourisme"]["year"])
            $("#minTourisme_detail1").html(`(${formatNumber(data["minTourisme"]["value"],"arriveesTotal")})`)
        }

        if (!("maxTourisme" in data) || isNaN(data["maxTourisme"]["year"])) {
            $("#maxTourisme"+index).html("-")
        } else{
            $("#maxTourisme"+index).html(data["maxTourisme"]["year"])
            $("#maxTourisme_detail1").html(`(${formatNumber(data["maxTourisme"]["value"],"arriveesTotal")})`)
        }

        if (!("maxPib" in data) || isNaN(data["maxPib"]["year"])) {
            $("#maxPib"+index).html("-")
        } else{
            $("#maxPib"+index).html(data["maxPib"]["year"])
            $("#max_detail1").html(`(${formatNumber(data["maxPib"]["value"],"pibParHab")})`)
        }

        if (!("minPib" in data) || isNaN(data["minPib"]["year"])) {
            $("#minPib"+index).html("-")
        } else{
            $("#minPib"+index).html(data["minPib"]["year"])
            $("#min_detail1").html(`(${formatNumber(data["minPib"]["value"],"pibParHab")})`)
        }   
    }
}
