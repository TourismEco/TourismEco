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
        $query = "SELECT * FROM Pays WHERE emoji = :emoji AND id_continent= :id_continent";
        $stmt = $conn->prepare($query);
        $emoji = "??";
        $continent = 2;
        $stmt->bindParam(":emoji", $emoji, PDO::PARAM_STR);
        $stmt->bindParam(":id_continent", $continent, PDO::PARAM_INT);
        $stmt->execute();

        while ($row = $stmt->fetch()){
            print_r($row);
            echo "<br><br>";
        }

        $stmt->closeCursor();
        $conn = null;
    ?>
    </body>
</html>
