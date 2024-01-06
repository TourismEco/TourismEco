function compareAjax(incr, id_pays) {
    $.ajax({
        method:"GET",
        url:"ajax.php",
        data:{id_pays:id_pays},
        async:false,
        success:function(result) {

            container = $("#bandeau"+incr)
            if (container.length) {
                $(container).remove()
            }

            createBandeau(result["id_pays"],incr,result["nom"],result["capitale"])
            $('#nom_'+ incr).html(result["nom"]);

            result["spider"] = JSON.parse(result["spider"])
            result["tab"] = JSON.parse(result["tab"])
            result["line"] = JSON.parse(result["line"])
            result["bar"] = JSON.parse(result["bar"])
            
            spiderAjax(incr, result["spider"], result["tab"], result["nom"])
            BarAjax(incr, result["bar"], result["nom"])
            lineAjax(incr, result["line"], result["nom"])

        },
        error:function(mess){
            console.log(mess)
        }
    })    
}


function createBandeau(id,nb,nom,capitale) {
    $("#bandeau").append(`
        <div class="bandeau-container" id="bandeau${nb}">     
        <img class="img" src='../assets/img/${id}.jpg' alt="Bandeau">
        <img class="flag" src='../assets/twemoji/${id}.svg'>
        <h1 class="nom">${nom}</h1>
        <p class="capital">Capitale : ${capitale}</p>
    `)

    if (nb == 0) {
        $("#bandeau0").addClass("marg-r")
    } else {
        $("#bandeau1").addClass("marg-l")
    }
}
