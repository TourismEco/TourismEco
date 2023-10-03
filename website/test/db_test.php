<!DOCTYPE html>
<html lang="FR-fr">
    <head>
        <title>Database test</title>
        <meta charset="utf-8">
        <link rel="stylesheet"  href="../assets/css/style.css">
    </head>
    
    <body>
        <?php
        require "../getDB.php";
        $conn = getDB();
        $query = "SELECT * FROM PAYS";
        $result = $conn->query($query);
        print_r($result) ?>
    </body>
</html>