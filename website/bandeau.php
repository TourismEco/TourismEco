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
            <img id="bandeau-image" src="paris3.jpg" alt="Bandeau">
            <p id="bandeau-info">Informations sous l'image</p>

            <?php
            if (isset($_GET['id'])) {
                $id = $_GET['id']; // Use 'id' instead of 'FR'

                // Connexion à la base de données en utilisant la fonction getBD() du fichier bd.php
                require("path/to/getDB.php");
                $bdd = getBD();

                // Sélectionner les données de l'article avec l'ID correspondant
                $sql = "SELECT * FROM pays WHERE id = $id";
                $result = $bdd->query($sql);

                if ($ligne = $result->fetch()) {
                    echo "<h1 id='bandeau-title'>" . $ligne["nom"] . "</h1>";
                    echo "<p id='bandeau-info-t'>" . "Informations sous titre" . "</p>";
                    
                } else {
                    echo "Aucun article trouvé.";
                }

                // Fermer la connexion à la base de données
                $result->closeCursor();
            } else {
                echo "ID de l'article non spécifié.";
            }
            ?>
        
        </div>
    </header>
    <main>
        <!-- Le contenu principal de votre page -->
    </main>
</body>
</html>

