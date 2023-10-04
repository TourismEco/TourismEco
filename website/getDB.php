<?php 

    function getDB(){
        $servername = "localhost";
        $username = "root";
        $password = "root";
        $dbname = "Ecotourisme";

        return new mysqli($servername, $username, $password, $dbname);
    }

    /* Pour utiliser la database:
    Aller dans le fichier ou l'on souhaite l'utiliser
    require "path/to/getDB.php";
    $conn = getDb();
    $mysql = "METTRE VOTRE REQUETE";
    $conn->query($mysql);

    n'oubliez pas de changer "path/to/ par le chemin adapte
    variable $mysql nomme comme ca car j'aurais voulu l'appeler
    query mais c'est deja le nom de la fonction qui fait la requete
    */
?>
