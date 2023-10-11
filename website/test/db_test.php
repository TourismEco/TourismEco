<!DOCTYPE html>
<html lang="FR-fr">
    <head>
        <title>Database test</title>
        <meta charset="utf-8">
        <link rel="stylesheet"  href="../assets/css/style.css">
    </head>
    
    <body>
        <?php
        require_once "../Mysql.php";
        require_once "../MysqlStatement.php";

        //A VOIR PLUS TARD
        $conn = Mysql::getDB();
        /*
        $query = "SELECT * FROM Pays WHERE emoji = ? AND id_continent= ?";
        $stmnt = new MysqlStatement($conn->prepare($query));
        $stmnt->bindParam("si", $condition1, $condition2);
        $condition1 = "??";
        $condition2 = 2;
        $stmnt->execute();
        $stmnt->bindResult($col1, $col2, $col3);
        $conn = new mysqli("localhost", "root", "root", "projet");
        $query = "SELECT * FROM Pays WHERE emoji = ? AND id_continent= ?";
        $stmnt = $conn->prepare($query);
        $stmnt->execute(["??", 2]);
        */
        
        $conn = Mysql::getDB();
        $query = "SELECT * FROM Pays WHERE emoji = ? AND id_continent= ?";
        $stmnt = $conn->prepare($query);
        $stmnt->execute(["??", 2]);

        while ($row = $stmnt->fetch()){
            print_r($row);
            echo "<br><br>";
            var_dump($row);
        }
        $stmnt->close();

        $conn->close();
    ?>
    </body>
</html>
