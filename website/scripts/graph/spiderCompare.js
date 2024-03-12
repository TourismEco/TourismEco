function spiderCompare(id) {
    g = new Graphique(id, "radar")
    g.createXAxis("var")
    g.createYAxis(null)
    g.setDataXAxis([{"var":"PIB/Hab"},{"var":"% énergies ren."},{"var":"Emissions de CO2"},{"var":"Arrivées touristiques"},{"var":"Départs"},{"var":"Global Peace Index"},{"var":"CPI"}])
    g.addSlider(updateCSpider,400,-20,50,50,90,2008,2020)
    g.addSerie("radar", "var", "value", null, "{name} : {valueY}", "#52796F")
    g.addSerie("radar", "var", "value", null, "{name} : {valueY}", "#83A88B")
}

var color = ["#52796F", "#83A88B"];
function spiderCHTMX(index, data, dataComp, name) {
    console.log(data);
    g.updateSerie(index, data, name, dataComp)
    updateCTable(index, dataComp[g.getYear()]);
    updateBold(g.getSeries(),g.getYear())
}

function updateCSpider(year) {
    for (var s of g.getSeries()) {
        s.setDataSerie(s.data[year]);
        updateCTable(s.getIndex(), s.comp[year]);
    }
    updateBold(g.getSeries(),year)
}

function updateCTable(index, data) {
    if (data) {      
        for (var i=0;i<data.length;i++) {
            if (data[i]["value"] == null) {
                st = "/"
            } else {
                if (data[i]["var"] == "Enr") {
                    st = data[i]["value"].toFixed(2)+" %"
                } else if (data[i]["var"] == "gpi" || data[i]["var"] == "cpi") {
                    st = data[i]["value"]
                } else {
                    if (data[i]["value"] > Math.pow(10,9)) {
                        st = (data[i]["value"]/Math.pow(10,9)).toFixed(2)+"Ma"
                    } else if (data[i]["value"] > Math.pow(10,6)) {
                        st = (data[i]["value"]/Math.pow(10,6)).toFixed(2)+"M"
                    } else if (data[i]["value"] > Math.pow(10,3)) {
                        st = data[i]["value"].toFixed(0).toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, " ")
                    } else {
                        st = data[i]["value"].toFixed(0)
                    }

                    if (data[i]["var"] == "pib") {
                        st += " $"
                    }
                }
            }

            $("#td_"+data[i]["var"]+"_"+index).html(st)
        }
    }
}



function updateBold(series,year) {
    if (series[1].comp != null) {
        $(".bold").removeClass("bold")
        for (var i=0;i<series[0].comp[year].length;i++) {
            v = series[0].comp[year][i]["var"]
            if (series[0].comp[year][i]["value"] > series[1].comp[year][i]["value"]) {
                $("#td_"+v+"_0").addClass("bold")
            } else if (series[0].comp[year][i]["value"] < series[1].comp[year][i]["value"]) {
                $("#td_"+v+"_1").addClass("bold")
            }
        }
    } 
}