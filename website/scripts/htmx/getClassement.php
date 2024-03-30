<?php
    require("../../functions.php");

    $cur = getDB();

    $var = $_GET["var"];

    $dic = array(
        "arriveesTotal" => "tourisme",
        "score" => "pays"
    );

    if (isset($dic[$var])) {
        $table = $dic[$var];

        $query = "SELECT * FROM $table JOIN pays ON pays.id = $table.id_pays ORDER BY $table.annee DESC , $table.$var DESC LIMIT 10;";
        $sth = $cur->prepare($query);
        $sth->execute();
        $results = $sth->fetchAll();
        
        foreach ($results as $row) {
            $nom = $row['nom'];
            $valeur = $row[$var];
        }
        

    } else {
        echo "Invalid value for 'var'";
    }
    return $results;

?>