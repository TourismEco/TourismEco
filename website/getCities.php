<?php
require("functions.php");
header('Content-Type: application/json; charset=utf-8');

if (!isset($_GET["id_pays"])) {
    echo json_encode(array("success"=>false, "reason"=>"Pas de pays donné"));
    exit;
} 

$cur = getDB();

$query = "SELECT * FROM villes WHERE id_pays = :id_pays";
$id_pays = $_GET["id_pays"];
$sth = $cur -> prepare($query);
$sth -> bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
$sth -> execute();

$cities = array();
$capitals = array();
while ($rs = $sth->fetch()) {
    if (!$rs["capitale"]) {
        $cities[] = array(
            "id"=>$rs["id"], 
            "title"=>$rs["nom"], 
            "geometry"=>array(
                "type"=>"Point",
                "coordinates"=>array($rs["lon"],$rs["lat"])
            )
        );
    } else {
        $capitals[] = array(
            "id"=>$rs["id"], 
            "title"=>$rs["nom"], 
            "geometry"=>array(
                "type"=>"Point",
                "coordinates"=>array($rs["lon"],$rs["lat"])
            )
        );
    }
    
}

echo json_encode(array("success"=>true, "cities"=>json_encode($cities), "capitals"=>json_encode($capitals), "id_pays"=>$id_pays));

?>