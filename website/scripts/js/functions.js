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
    $("#rang").html(ligne[type])
    $("#rank_pays").html(`
        <div class ="classement other" hx-get="">
            <div class="otherclassement">${ligne[type+"rank"] == 667 ? "/" : ligne[type+"rank"]}</div>
            <div class="classement-pays">${ligne["nom"]}</div>
            <img src="assets/twemoji/${ligne["id"]}.svg" alt="${ligne["nom"]}" class="flagClassement">
            <img src="assets/img/${ligne["id"]}.jpg" alt="${ligne["nom"]}" class="imgClassement">
            <div class="value">${formatNumber(ligne[type],type)}</div>
        </div>
    `)
}