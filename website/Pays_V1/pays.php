<?php
// Votre connexion MySQLi
require 'functions.php';

    try {
        $conn = getDB();
        // Définissez le mode d'erreur PDO sur exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        echo "Erreur de connexion à la base de données: " . $e->getMessage();
    }


$query = "
SELECT eco.annee, eco.co2 as eco
FROM ecologie as eco
WHERE eco.id_pays = 'FR'
";

$result = $conn->query($query);

$report_data = array();
while ($rs = $result->fetch()) {
    $report_data[] = array(
        "year" => $rs['annee'],
        "value" => $rs['eco'],
    );
}

// Fermez la connexion à la base de données
$conn= null;
$report_data = json_encode($report_data);
?>

<meta charset="utf-8">
<html lang="fr">

    <head>
        <link rel="stylesheet" href="styles/styles.css" type="text/css" media="screen" />
        <title>Pays</title>
    </head>
    <body >
        <div class="topnav">
            <img class="logoNavBar" src="images/logo.png" alt="logo">
            <a class="active" href="#home">Accueil</a>
            <a classe="no-active" href="#news">Comparateur</a>
            <a classe="no-active" href="#contact">Analyses</a>

            <input class ="search" type="search" id="site-search" name="q" />

            <button class="favorite styled" type="button">S'inscrire</button>
            <button class="favorite styled2" type="button">Se connecter</button>

        </div>
    
        <?php
            require_once "functions.php";
            $cur = getDB();

            $query = "SELECT * FROM villes WHERE id_pays = :id_pays";
            $id_pays = "CA";
            $sth = $cur -> prepare($query);
            $sth -> bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
            $sth -> execute();

            $cities = array();
            $capitals = array();
            while ($rs = $sth->fetch()) {
                if (!$rs["capitale"]) {
                    $cities[] = <<<END
                        {id:{$rs['id']},
                            title:'{$rs['nom']}',
                            geometry:{type:'Point', coordinates:[{$rs['lon']},{$rs['lat']}]},
                        }
                    END;
                } else {
                    $capitals[] = <<<END
                        {id:{$rs['id']},
                            title:'{$rs['nom']}',
                            geometry:{type:'Point', coordinates:[{$rs['lon']},{$rs['lat']}]},
                        }
                    END;
                }
                
            }

            $cities = implode(",", $cities);
            $capitals = implode(",", $capitals);

        ?>

            <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
            <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
            <script src="https://cdn.amcharts.com/lib/5/map.js"></script>
            <script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
            <script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
            <script src="https://cdn.amcharts.com/lib/5/geodata/lang/FR.js"></script>
            
            <script src="../assets/js/map.js"></script>

        <div id="map"></div>
        <style>
            #map {
                width: 100%;
                height: 60%;
            }
        </style>

        <script>
            createMap(fun=addCountry,args=["<?=$id_pays?>",[<?= $cities;?>],[<?= $capitals;?>]])
        </script>


            <div id="bandeau-container">
                <img class="img" src="paris.jpg" alt="Bandeau">
                <?php
                // Connexion à la base de données en utilisant la fonction getDB() du fichier functions.php
            require_once "functions.php";

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
                        <img src='../assets/twemoji/$ligne[id].svg'>
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
                
                $query = "SELECT * FROM economie WHERE id_pays = :id_pays and annee = :annee";
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
                
                    $query = "SELECT * FROM economie WHERE id_pays = :id_pays and annee = :annee";
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

                    $query = "SELECT * FROM surete WHERE id_pays = :id_pays and annee = :annee";
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

        <div class="score">
            <p>EcoToursim Score
                </br>La France a un score A ! C’est excellent !
                </br>Le pays se place au 5ème rang mondial, et 1er à l’échelle de son continent.

            </p>
        </div>

    
        <div class="containers">
            <div class="texte">
                <p>Evolution du PIB de la France dans le temps</p>
                <p>Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un imprimeur anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte. Il n'a pas fait que survivre cinq siècles, mais s'est aussi adapté à la bureautique informatique, sans que son contenu n'en soit modifié. Il a été popularisé dans les années 1960 grâce à la vente de feuilles Letraset contenant des passages du Lorem Ipsum, et, plus récemment, par son inclusion dans des applications de mise en page de texte, comme Aldus PageMaker.
                    Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un imprimeur anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte. Il n'a pas fait que survivre cinq siècles, mais s'est aussi adapté à la bureautique informatique, sans que son contenu n'en soit modifié. Il a été popularisé dans les années 1960 grâce à la vente de feuilles Letraset contenant des passages du Lorem Ipsum, et, plus récemment, par son inclusion dans des applications de mise en page de texte, comme Aldus PageMaker.                
                </p>
            </div>
            
            <div class="graphique">
            

            </div>
        </div>

        <div class="containers">

            <div class="graphique">
<!-- Styles -->
<style>
    #chartdiv2 {
        width: 100%;
        height: auto;
    }
</style>

<!-- Resources -->
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

<!-- Chart code -->

<!-- HTML -->
<div id="chartdiv2"></div>
<!-- Chart code -->
<script src="double_courbe.js"></script>

<!-- HTML -->
<script>
    double_courbe(<?=$report_data?>)
</script>

            </div>
            <div class="texte">
                <p>Evolution du Co2 en France dans le temps</p>
                <p>Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un imprimeur anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte. Il n'a pas fait que survivre cinq siècles, mais s'est aussi adapté à la bureautique informatique, sans que son contenu n'en soit modifié. Il a été popularisé dans les années 1960 grâce à la vente de feuilles Letraset contenant des passages du Lorem Ipsum, et, plus récemment, par son inclusion dans des applications de mise en page de texte, comme Aldus PageMaker.
                    Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un imprimeur anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte. Il n'a pas fait que survivre cinq siècles, mais s'est aussi adapté à la bureautique informatique, sans que son contenu n'en soit modifié. Il a été popularisé dans les années 1960 grâce à la vente de feuilles Letraset contenant des passages du Lorem Ipsum, et, plus récemment, par son inclusion dans des applications de mise en page de texte, comme Aldus PageMaker.                
                </p>
            </div>
        </div>

        <div class="containers">
            <div class="texte">
                <p>Evolution du PIB par rapport au tourisme</p>
                <p>Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un imprimeur anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte. Il n'a pas fait que survivre cinq siècles, mais s'est aussi adapté à la bureautique informatique, sans que son contenu n'en soit modifié. Il a été popularisé dans les années 1960 grâce à la vente de feuilles Letraset contenant des passages du Lorem Ipsum, et, plus récemment, par son inclusion dans des applications de mise en page de texte, comme Aldus PageMaker.
                    Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un imprimeur anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte. Il n'a pas fait que survivre cinq siècles, mais s'est aussi adapté à la bureautique informatique, sans que son contenu n'en soit modifié. Il a été popularisé dans les années 1960 grâce à la vente de feuilles Letraset contenant des passages du Lorem Ipsum, et, plus récemment, par son inclusion dans des applications de mise en page de texte, comme Aldus PageMaker.                
                </p>
            </div>
            
            <div class="graphique">

            </div>
        </div>

        <div id="comparateur">
            <div id="text">
                <h3> COMPARATEUR RAPIDE </h3>
                <p>
                    Voici quelques pays pertinents que vous pouvez comparer avec la France
                </p>

            </div>
            <div id="graph_comp">
                <?php
                    require_once "functions.php";
                    $conn = getDB();
                    $query = "
                        SELECT pays.nom, ROUND(SUM(co2)) as total
                        FROM ecologie, pays
                        WHERE pays.id = ecologie.id_pays
                        GROUP BY id_pays
                        ORDER BY `total` DESC
                        LIMIT 10;
                    ";
                    $result = $conn->query($query);

                    $report_data = array();
                    while ($rs = $result->fetch()) {
                        $report_data[] = '
                        {country:' . '"' . $rs['nom'] . '"' . ',
                        ' . 'value:' . $rs['total'] . '}';
                    }

                    // Concaténer les données
                    $report_data = implode(",", $report_data);

                ?>

                <style>
                    #chartdiv4 {
                        width: 100%;
                        height: 50%;
                    }
                </style>

                <div id="chartdiv4"></div>


                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
                <!-- amcharts devbanban.com -->
                <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
                <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
                <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
                
                <script src="barre.js"></script>
                <script>
                    barre(<?= $report_data;?>)
                </script>


            </div>

        </div>
            
    </body>
</html>