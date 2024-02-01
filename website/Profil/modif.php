<?php
// modif.php
require("../functions.php");

// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Assurez-vous que les clés existent avant d'essayer de les utiliser
    if (isset($_POST["country"], $_POST["cityInput"], $_SESSION["username"])) {
        // Récupère les valeurs du formulaire
        $pays = htmlspecialchars($_POST["country"]);
        $ville = htmlspecialchars($_POST["cityInput"]);
        $id = $_SESSION['client']["username"];

        try {
            $conn = getDB();

            // Utilisez les déclarations préparées pour éviter les injections SQL
            $stmt = $conn->prepare("UPDATE client SET pays=:pays, ville=:ville WHERE nom = :id");
            $stmt->bindParam(':pays', $pays);
            $stmt->bindParam(':ville', $ville);
            $stmt->bindParam(':id', $id);

            // Exécute la mise à jour
            if ($stmt->execute()) {
                // Mise à jour réussie
                $_SESSION['client']['country'] = $pays;
                $_SESSION['client']['city'] = $ville;
                header('Location: ../Profil/profil.php');
                exit;
            } else {
                // Erreur lors de la mise à jour
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour : ' . $stmt->errorInfo()[2]]);
            }

            // Ferme la connexion
            $stmt= null;
            $conn = null;
        } catch (Exception $e) {
            // Gère les exceptions
            echo json_encode(['success' => false, 'message' => 'Une erreur inattendue s\'est produite : ' . $e->getMessage()]);
        }
    } else {
        // Les clés nécessaires ne sont pas définies
        echo json_encode(['success' => false, 'message' => 'Données manquantes dans la requête.']);
    }
} else {
    // La page a été accédée sans soumission de formulaire
    echo json_encode(['success' => false, 'message' => 'Accès non autorisé.']);
}
?>
