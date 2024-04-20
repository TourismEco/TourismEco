function getSearchValue(id) {
    var s = document.getElementById(id)
    return s.value
}

function getIdContinent() {
    return id_continent
}

function getIncr() {
    return incr
}

// Fonctions pour explorer.php
function changeVarExplorer(type) {
    data = map.data
    map.countries.set("valueField",type)
    map.countries.data.setAll(data)
    map.behaviorSerie(map.countries)
    map.activePays(map.countries)
    map.map.goHome()
    updatePodium(type,data)
    typeC = type
    if (id_pays != null) {
        updateRanking(id_pays, type, data)
    }
}

function updatePodium(type, data) {
    dataSort = data.sort((p1, p2) => (p1[type] < p2[type]) ? 1 : (p1[type] > p2[type]) ? -1 : 0)

    $("#rank").empty()
    cla = ["first","second","third","fourth","other","other","other","other","other","other"]
    sty = ["premier","deuxieme","troisieme","quatrieme","otherclassement","otherclassement","otherclassement","otherclassement","otherclassement","otherclassement"]
    for (i = 0;i<10;i++) {
        $("#rank").append(`
        <div class ="classement ${cla[i]}" hx-get="">
            <div class="${sty[i]}">${i+1}</div>
            <div class="classement-pays">${dataSort[i]["nom"]}</div>
            <img src="assets/twemoji/${dataSort[i]["id"]}.svg" alt="${dataSort[i]["nom"]}" class="flagClassement">
            <img src="assets/img/${dataSort[i]["id"]}.jpg" alt="${dataSort[i]["nom"]}" class="imgClassement">
            <div class="value">${formatNumber(dataSort[i][type],type)}</div>
        </div>
        `)
    }
}

function updateRanking(id_pays, type, data) {
    ligne = data.filter((val) => {return val.id == id_pays})[0]
    var rank = ligne[type + 'rank'];
    var suffix = (rank == 1) ? " er" : " ème";
    $("#rang").addClass("centered-content").html("Rang du pays <br>" + rank + suffix);    $("#rank_pays").html(`
        <div class ="classement other" hx-get="">
            <div class="otherclassement">${ligne[type+"rank"] == 667 ? "/" : ligne[type+"rank"]}</div>
            <div class="classement-pays">${ligne["nom"]}</div>
            <img src="assets/twemoji/${ligne["id"]}.svg" alt="${ligne["nom"]}" class="flagClassement">
            <img src="assets/img/${ligne["id"]}.jpg" alt="${ligne["nom"]}" class="imgClassement">
            <div class="value">${formatNumber(ligne[type],type)}</div>
        </div>
    `)
}

function changeScore(option) {
    vars = ["pibParHab","gesHab","arriveesTotal","gpi","idh","elecRenew"]
    poids = {   
        Global:{pibParHab:2,gesHab:6,arriveesTotal:2,gpi:4,idh:4,elecRenew:3},
        Decouverte:{pibParHab:0,gesHab:1,arriveesTotal:2,gpi:0,idh:2,elecRenew:0},
        Ecologique:{pibParHab:0,gesHab:3,arriveesTotal:0,gpi:0,idh:0,elecRenew:2},
        Economique:{pibParHab:2,gesHab:0,arriveesTotal:2,gpi:0,idh:2,elecRenew:0}
    }

    inter = {
        Global:[0.22, 0.26, 0.34, 0.42, 0.50, 0.60],
        Decouverte:[0.18, 0.27, 0.36, 0.445, 0.54, 0.80],
        Ecologique:[0, 0.17, 0.34, 0.51, 0.68, 0.87],
        Economique:[0.14, 0.28, 0.42, 0.56, 0.7, 0.79382]
    }

    $(".infos-scores").hide();
     $("#score-" + option).show();

    p = poids[option]

    $(".poids-active").removeClass("poids-active")
    $(".opaque").removeClass("opaque")

    vars.forEach(element => {
        if (p[element] == 0) {
            $("#textp-"+element).html("Pas pris en compte dans ce score.")
            $("#sco-"+element).addClass("opaque")
        } else {
            div = $("#poids-"+element).children()
            $("#textp-"+element).html("Poids : "+p[element])
            for(i=0;i<p[element];i++) {
                $(div[i]).addClass("poids-active")
            }
        }
    });

    $(".score-active").removeClass("score-active")
    $("#score"+option).addClass("score-active")

    letter = $("#score"+option).data("letter")
    $("#bigScore").removeClass("score-A")
    $("#bigScore").removeClass("score-B")
    $("#bigScore").removeClass("score-C")
    $("#bigScore").removeClass("score-D")
    $("#bigScore").removeClass("score-E")
    $("#bigScore").removeClass("score-NA")
    $("#bigScore").addClass("score-"+letter)

    if (letter == "NA") {
        $("#bigScore-letter").html("<img src='assets/icons/bd.svg'>")
    } else {
        $("#bigScore-letter").html(letter)
    }
    
    $("#bigScore-text").html("Score "+option)

    for(i=0;i<6;i++) {
        $("#lim"+i).html(inter[option][i])
    }

    value = $("#score"+option).data("value")
    for(i=1;i<6;i++) {
        if (value <= inter[option][i]) {
            min = inter[option][i-1]
            max = inter[option][i] - min
            val = value - min
            plus = val*60/max
            if (i <= 2) {
                tr = -30-plus-60*(i-1)
            } else if (i >= 4) {
                tr = 30+plus+60*(i-4)
            } else {
                if (plus > 30) {
                    tr = plus/2
                } else {
                    tr = -plus/2
                }
            }
            i = 100
        }
    }

    $("#tscor").css("transform",`translateX(${tr}px)`)

}