<!DOCTYPE html>
<html lang="FR-fr">
    <head>
        <title>Database test</title>
        <meta charset="utf-8">
        <link rel="stylesheet"  href="../assets/css/style.css">
    </head>
    
    <body>
        <?php
        require_once "../functions.php";

        $conn = getDB();

        foreach(getStats("FR", $conn) as $stat){
            echo $stat."  ";
        }

        $conn = null;
    ?>
    </body>
</html>
