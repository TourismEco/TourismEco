<?php
require("../functions.php");
$cur = getDB();

$query = "SELECT * FROM villes WHERE id_pays = :id_pays";
$id_pays = "CA";
$sth = $cur -> prepare($query);
$sth -> bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
$sth -> execute();

$cities = array();
$capitals = array();
while ($rs = $sth->fetch()) {
    if (!$rs["capitale"]) {
        $cities[] = <<<END
            {id:{$rs['id']},
                title:'{$rs['nom']}',
                geometry:{type:'Point', coordinates:[{$rs['lon']},{$rs['lat']}]},
            }
        END;
    } else {
        $capitals[] = <<<END
            {id:{$rs['id']},
                title:'{$rs['nom']}',
                geometry:{type:'Point', coordinates:[{$rs['lon']},{$rs['lat']}]},
            }
        END;
    }
    
}

$cities = implode(",", $cities);
$capitals = implode(",", $capitals);

?>

<style>
    #map {
        width: 100%;
        height: 95%;
        background-color: #354F52;
    }
</style>
    
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/map.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/lang/FR.js"></script>
<script src="../assets/js/map.js"></script>

<div id="map"></div>

<script>
    createMap(fun=addCountry,args=["<?=$id_pays?>",[<?= $cities;?>],[<?= $capitals;?>]])
</script>
