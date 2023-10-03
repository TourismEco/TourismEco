<?php 

    function getDB(){
        $servername = "localhost";
        $username = "root";
        $password = "root";
        $dbname = "Ecotourisme";

        return new mysqli($servername, $username, $password, $dbname);
    }

?>
