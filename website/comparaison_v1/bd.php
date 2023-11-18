<?php
    function getBD(){
        $servername = "127.0.0.1";
        $username = "root";
        $password = "";
        $database = "projetl3";
        $port = 3306;

        $conn = new mysqli($servername, $username, $password, $database, $port);

        if ($conn->connect_error) {
            die("Erreur de connexion à la base de données : " . $conn->connect_error);
        }
    }
?>