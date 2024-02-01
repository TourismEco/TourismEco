<?php

require('../../functions.php');

if (isset($_GET["id"])) {
    $id_ville = $_GET["id"];
    $id_pays = $_GET["id_pays"];
    $nom = $_GET["nom"];
    $sens = $_GET["sens"];

    inputVilles($id_pays, $nom, $sens);
    emptyOptions("city_options_$sens");
}

?>