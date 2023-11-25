// function compareAjax(incr, id_pays, pays_not) {
//     $.ajax({
//         method:"GET",
//         url:"ajax.php",
//         data:{id_pays:id_pays,pays_not:pays_not},
//         success:function(result) {
//             bandeau = result["bandeau"]
//             container = $("#bandeau"+incr)
        
//             infos = container.children(".infos")
//             infos.children("#arriveesTotal").html(bandeau[0]) 
//             infos.children("#co2").html(bandeau[1]) 
//             infos.children("#pibParHab").html(bandeau[2]) 
//             infos.children("#gpi").html(bandeau[3]) 
        
//             container.children(".nom").html(result["nom"])
//             container.children(".flag").attr("src","../assets/twemoji/"+result["id_pays"]+".svg")
//             container.children(".capital").html("Capitale : "+result["capitale"])
//             container.children(".img").attr("src","../assets/img/"+result["id_pays"]+".jpg")

//             spiderAjax(incr, JSON.parse(result["spider"]))
//             console.log(JSON.parse(result["line"]))
//             lineAjax(JSON.parse(result["line"]))
//         }
//     })    
// }


$(document).ready(function() {
    $("#pays1").on("change", function() {
        incr = 0
        p1 = countrySeries.getDataItemById($(this).val())
        p1._settings.mapPolygon.set("active",true)
        compareAjax("0", $(this).val(), $("#pays2").val())
    })
    
    $("#pays2").on("change", function() {
        incr = 1
        p2 = countrySeries.getDataItemById($(this).val())
        p2._settings.mapPolygon.set("active",true)
        compareAjax("1", $(this).val(), $("#pays1").val())
    })
})