<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma Page Web</title>
    <link rel="stylesheet" href="styles/styles-bandeau.css">
</head>
<style>
  #chartdiv {
    width: 100%;
    height: 100%;
    background-color: #f2f2f2;
  }
</style>

<body>
    <header>
        <div id="bandeau-container">
            <img class="img" src="images/paris4.jpg" alt="Bandeau">

            <?php
                // Connexion à la base de données en utilisant la fonction getDB() du fichier functions.php
               require_once "../functions.php";

                $conn = getDB();

                $query = "SELECT * FROM pays WHERE id = :id_pays";
                $id_pays = "FR";
                $sth = $conn->prepare($query);
                $sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
                $sth->execute();

                $ligne = $sth->fetch();
                echo <<<HTML
                    <div class= one>
                    <h1>$ligne[nom]</h1>
                    </div>
                    <div class= logo>
                        <img src='../assets/twemoji/$ligne[emojiSVG].svg'>
                    </div>
HTML;
                
                $query = "SELECT * FROM villes WHERE id_pays = :id_pays and capitale = :is_capitale";
                $sth = $conn->prepare($query);
                $is_capitale = 1;
                $sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
                $sth->bindParam(":is_capitale", $is_capitale, PDO::PARAM_INT);
                $sth->execute();

                $ligne = $sth->fetch();
                echo <<<HTML
                        <div class= 'capital'>
                            <p >Capital : $ligne[nom]</p>
                        </div>
HTML;
                
                $query = "SELECT * FROM pib WHERE id_pays = :id_pays and annee = :annee";
                $sth = $conn->prepare($query);
                $annee = 2021;
                $sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
                $sth->bindParam(":annee", $annee, PDO::PARAM_INT);
                $sth->execute();

                $ligne = $sth->fetch();
                echo <<<HTML
                    <p class='two'>PIB : $ligne[pib]</p>
HTML;
                                
                    $query = "SELECT * FROM ecologie WHERE id_pays = :id_pays and annee = :annee";
                    $sth = $conn->prepare($query);
                    $annee = 2020;
                    $sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
                    $sth->bindParam(":annee", $annee, PDO::PARAM_INT);
                    $sth->execute();

                    while ($ligne = $sth->fetch()) {
                        echo <<<HTML
                        <p class='three'>CO2 : $ligne[co2]</p>
HTML;
                    }
                
                    $query = "SELECT * FROM pib WHERE id_pays = :id_pays and annee = :annee";
                    $sth = $conn->prepare($query);
                    $annee = 2021;
                    $sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
                    $sth->bindParam(":annee", $annee, PDO::PARAM_INT);
                    $sth->execute();
                    while ($ligne = $sth->fetch()) {
                        echo <<<HTML
                        <p class='four'>PIB : $ligne[pibParHab]</p>
HTML;
                    }

                    $query = "SELECT * FROM gpi WHERE id_pays = :id_pays and annee = :annee";
                    $sth = $conn->prepare($query);
                    $annee = 2023;
                    $sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
                    $sth->bindParam(":annee", $annee, PDO::PARAM_INT);
                    $sth->execute();

                    while ($ligne = $sth->fetch()) {
                        echo <<<HTML
                          <p class='five'>GPI: $ligne[gpi]</p>
HTML;
                    }
            ?>
        </div>
    </header>
    <main>
        <!-- Le contenu principal de votre page -->
    </main>
</body>
</html>

