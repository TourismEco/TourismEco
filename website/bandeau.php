<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma Page Web</title>
    <link rel="stylesheet" href="styles-bandeau.css">
</head>
<body>
    <header>
        <div id="bandeau-container">
            <img class="img" src="paris4.jpg" alt="Bandeau">

            <?php
                // Connexion à la base de données en utilisant la fonction getBD() du fichier bd.php
                require("getDB.php");
                $conn = getDb();

                if ($conn) {
                    $mysql = "SELECT * FROM pays WHERE id = 'FR'";
                    $result = $conn->query($mysql);

                    if ($result) {
                        $ligne = $result->fetch();

                        if ($ligne) {
                            echo "<div class= one>";
                            echo "<h1 >" . $ligne["nom"] . "</h1>";
                            echo "</div>";
                        } else {
                            echo "Aucun article trouvé.";
                        }

                        // Fermer la résultat de la requête
                        $result->closeCursor();
                    } else {
                        echo "Erreur dans la requête SQL.";
                    }
                }
                if ($conn) {
                    $mysql = "SELECT * FROM pays WHERE id = 'FR'";
                    $result = $conn->query($mysql);

                    if ($result) {
                        $ligne = $result->fetch();

                        if ($ligne) {
                            echo "<div class= logo>";
                            echo "<img src='assets/twemoji/".$ligne["emojiSVG"].".svg'>";
                            echo "</div>";
                        } else {
                            echo "Aucun article trouvé.";
                        }

                        // Fermer la résultat de la requête
                        $result->closeCursor();
                    } else {
                        echo "Erreur dans la requête SQL.";
                    }
                }
                if ($conn) {
                    $mysql = "SELECT * FROM villes WHERE id_pays = 'FR' and capitale=1";
                    $result = $conn->query($mysql);

                    if ($result) {
                        $ligne = $result->fetch();

                        if ($ligne) {
                            echo "<div class= 'capital'>";
                            echo "<p >" ."Capital : ". $ligne["nom"] . "</p>";
                            echo "</div>";
                        } else {
                            echo "Aucun article trouvé.";
                        }

                        // Fermer la résultat de la requête
                        $result->closeCursor();
                    } else {
                        echo "Erreur dans la requête SQL.";
                    }
                }
                if ($conn) {
                    $mysql = "SELECT * FROM pib WHERE id_pays = 'FR' and annee = 2021";
                    $result = $conn->query($mysql);

                    if ($result) {
                        $ligne = $result->fetch();

                        if ($ligne) {
                            while ($ligne) {
                                echo "<p class='two'>" ."PIB : ". $ligne["pib"] . "</p>";
                                $ligne = $result->fetch();
                            }

                        } else {
                            echo "Aucun article trouvé.";
                        }

                        // Fermer la résultat de la requête
                        $result->closeCursor();
                    } else {
                        echo "Erreur dans la requête SQL.";
                    }
                }
                if ($conn) {
                    $mysql = "SELECT * FROM ecologie WHERE id_pays = 'FR' and annee = 2020";
                    $result = $conn->query($mysql);

                    if ($result) {
                        $ligne = $result->fetch();

                        if ($ligne) {
                            while ($ligne) {
                                echo "<p class='tree'>" ."CO2 : ". $ligne["co2"] . "</p>";
                                $ligne = $result->fetch();
                            }

                        } else {
                            echo "Aucun article trouvé.";
                        }

                        // Fermer la résultat de la requête
                        $result->closeCursor();
                    } else {
                        echo "Erreur dans la requête SQL.";
                    }
                } 
                if ($conn) {
                    $mysql = "SELECT * FROM pib WHERE id_pays = 'FR' and annee = 2021";
                    $result = $conn->query($mysql);

                    if ($result) {
                        $ligne = $result->fetch();

                        if ($ligne) {
                            while ($ligne) {
                                echo "<p class='four'>" ."PIB : ". $ligne["pibParHab"] . "</p>";
                                $ligne = $result->fetch();
                            }

                        } else {
                            echo "Aucun article trouvé.";
                        }

                        // Fermer la résultat de la requête
                        $result->closeCursor();
                    } else {
                        echo "Erreur dans la requête SQL.";
                    }
                }
                if ($conn) {
                    $mysql = "SELECT * FROM gpi WHERE id_pays = 'FR' and annee = 2023";
                    $result = $conn->query($mysql);

                    if ($result) {
                        $ligne = $result->fetch();

                        if ($ligne) {
                            while ($ligne) {
                                echo "<p class='five'>" ." GPI: ". $ligne["gpi"] . "</p>";
                                $ligne = $result->fetch();
                            }

                        } else {
                            echo "Aucun article trouvé.";
                        }

                        // Fermer la résultat de la requête
                        $result->closeCursor();
                    } else {
                        echo "Erreur dans la requête SQL.";
                    }
                }
            ?>        
        </div>
    </header>
    <main>
        <!-- Le contenu principal de votre page -->
    </main>
</body>
</html>

