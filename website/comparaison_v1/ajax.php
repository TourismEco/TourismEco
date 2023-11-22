<?php
require("../functions.php");
$cur = getDB();

$id_pays = $_GET["id_pays"];
$pays_not = $_GET["pays_not"];

// Nom
$query = "SELECT * FROM pays WHERE id = :id_pays";
$sth = $cur->prepare($query);
$sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
$sth->execute();
$ligne = $sth->fetch();
$nom = $ligne["nom"];

// Capitale
$query = "SELECT * FROM villes WHERE id_pays = :id_pays and capitale = :is_capitale";
$sth = $cur->prepare($query);
$is_capitale = 1;
$sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
$sth->bindParam(":is_capitale", $is_capitale, PDO::PARAM_INT);
$sth->execute();
$ligne = $sth->fetch();
$capitale = $ligne["nom"];

// Bandeau
$search = array(array("tourisme","arriveesTotal","Arrivées"), array("ecologie","co2","CO2"), array("economie","pibParHab","PIB/Hab"), array("surete","gpi","Indice de sureté"));
$dataBandeau = array();

foreach ($search as $key => $value) {
    $table = $value[0];
    $arg = $value[1];
    $text = $value[2];
    $query = "SELECT ".$arg." FROM ".$table." WHERE id_pays = :id_pays AND ".$arg." IS NOT NULL ORDER BY Annee DESC";
    $sth = $cur->prepare($query);
    $sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
    $sth->execute();

    $ligne = $sth->fetch();
    $dataBandeau[] = $ligne[$arg];
}

// Spider
$dataSpider = dataSpider($id_pays);

// Line
$query = "
SELECT eco1.annee, eco1.co2 as eco1, eco2.co2 as eco2
FROM ecologie as eco1, ecologie as eco2
WHERE eco1.id_pays = '$id_pays'
AND eco2.id_pays = '$pays_not'
AND eco1.annee = eco2.annee;
";

$result = $cur->query($query);
$dataLine = array();

while ($rs = $result->fetch()) {
    $dataLine[] = <<<END
        {year:'{$rs['annee']}',
            value:{$rs['eco1']},
            value2:{$rs['eco2']},
        }
    END;
}

$dataLine = implode(",", $dataLine);

// Bar
$query2 = "
SELECT eco1.annee, eco1.pibParHab as eco1, eco2.pibParHab as eco2
FROM economie as eco1, economie as eco2
WHERE eco1.id_pays = '$id_pays'
AND eco2.id_pays = '$pays_not'
AND eco1.annee = eco2.annee;
";

$result2 = $cur->query($query2);

$dataBar = array();
while ($rs = $result2->fetch()) {
    foreach (array('eco1','eco2') as $key => $value) {
        if (!isset($rs[$value])){
            $rs[$value]=0;
        } 
    } 
    $dataBar[] = <<<END
        {year:'{$rs['annee']}',
            value:{$rs['eco1']},
            value2:{$rs['eco2']},
        }
    END;
}

$dataBar = implode(",", $dataBar);












// SPIDER
function dataSpider($pays) {
    $conn = getDB();

    $query = "
    SELECT ecologie.annee as annee,
    economie.pibParHab as pib,
    ecologie.elecRenew * 1000 as Enr,
    ecologie.co2/10000 as co2, 
    tourisme.arriveesTotal as arrivees, 
    tourisme.departs as departs, 
    surete.gpi * 10000 as gpi, 
    economie.cpi as cpi

    FROM ecologie, economie, tourisme, surete
    WHERE ecologie.id_pays = economie.id_pays
    AND economie.id_pays = tourisme.id_pays
    AND tourisme.id_pays = surete.id_pays
    AND surete.id_pays = '$pays'

    AND ecologie.annee = economie.annee
    AND economie.annee = tourisme.annee
    AND tourisme.annee = surete.annee  
    ORDER BY `ecologie`.`annee` DESC;
    ";

    $result = $conn->query($query);

    $data = array();
    while ($rs = $result->fetch(PDO::FETCH_ASSOC)) {
        foreach (array("pib","Enr","co2","arrivees","departs","gpi","cpi") as $key => $value) {
            if (!isset($rs[$value])){
                $rs[$value]=0;
            } 
        }
        
        $data[] = <<<END
            $rs[annee]:
                [{"var":"PIB","value":$rs[pib],"val2":100},
                {"var":"% renew","value":$rs[Enr],"val2":100},
                {"var":"CO2","value":$rs[co2],"val2":100},
                {"var":"Arrivées","value":$rs[arrivees],"val2":100},
                {"var":"Départs","value":$rs[departs],"val2":100},
                {"var":"GPI","value":$rs[gpi],"val2":100},
                {"var":"CPI","value":$rs[cpi],"val2":100}]

        END;
    }

    return implode(",", $data);
}

$dataAjax = array("nom"=>$nom,"capitale"=>$capitale,"bandeau"=>$dataBandeau,"spider"=>$dataSpider,"line"=>$dataLine,"bar"=>$dataBar,"id_pays"=>$id_pays);

header('Content-Type: application/json; charset=utf-8');
echo json_encode($dataAjax);

?>