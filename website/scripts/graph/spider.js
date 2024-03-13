function spider(id, nb) {
    g = new Graphique(id, "radar")
    g.createXAxis("var")
    g.createYAxis(null)
    g.setDataXAxis([{"var":"PIB/Hab"},{"var":"% énergies ren."},{"var":"Emissions de CO2"},{"var":"Arrivées touristiques"},{"var":"Départs"},{"var":"Global Peace Index"},{"var":"CPI"}])
    g.addSlider(updateSpider,400,-20,50,50,90,2008,2020)

    for (i=0;i<nb;i++) {
        g.addSerie("radar", "var", "value", null, "{name} : {valueY}", color[i])
    }
}

var color = ["#52796F", "#83A88B"];
function spiderHTMX(index, data, dataComp, name) {
    g.updateSerie(index, data, name, dataComp);
    updateTable(index, dataComp[g.getYear()]);

    if (g.getSeriesLength() == 1) {
        updateGrow(dataComp, g.getYear())
    } else if (g.getSeriesLength() == 2) {
        updateBold(g.getSeries(),g.getYear())
    }
}

function updateSpider(year) {
    for (var s of g.getSeries()) {
        s.setDataSerie(s.data[year]);
        updateTable(s.getIndex(), s.comp[year]);
    }

    if (g.getSeriesLength() == 1) {
        updateGrow(g.getSeries()[0].comp, year)
    } else if (g.getSeriesLength() == 2) {
        updateBold(g.getSeries(),year)
    }
}

function updateTable(index, data) {
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

function updateGrow(data, year) {
    for (var i=0;i<data[year].length;i++) {
        if (data[year][i]["value"] == null || data[year-1][i]["value"] == null) {
            grow = "/"
            rank = "/"
            evolRank = "/"
        } else {
            grow = 100*(data[year][i]["value"] - data[year-1][i]["value"])/data[year-1][i]["value"]
            rank = data[year][i]["rank"]
            evolRank = data[year-1][i]["rank"] - data[year][i]["rank"]
        }

        $("#td_"+data[year][i]["var"]+"_grow").html(grow)
        $("#td_"+data[year][i]["var"]+"_rank").html(rank)
        $("#td_"+data[year][i]["var"]+"_rankEvol").html(rank)
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