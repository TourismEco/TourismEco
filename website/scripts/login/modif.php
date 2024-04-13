<?php
    require("../functions.php");

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST["country_register"], $_POST["city_register"], $_SESSION["user"])) {
            $pays = htmlspecialchars($_POST["country_register"]);
            $ville = htmlspecialchars($_POST["city_register"]);
            $id = $_SESSION['user']["username"];

            try {
                $conn = getDB();

                $stmt = $conn->prepare("UPDATE client SET pays=:pays, ville=:ville WHERE nom = :id");
                $stmt->bindParam(':pays', $pays, PDO::PARAM_STR);
                $stmt->bindParam(':ville', $ville, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_STR);

                if ($stmt->execute()) {
                    $_SESSION['user']['country'] = $pays;
                    $_SESSION['user']['city'] = $ville;
                    header('Location: ../profil.php');
                    exit;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour : ' . $stmt->errorInfo()[2]]);
                }

                $stmt= null;
                $conn = null;
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Une erreur inattendue s\'est produite : ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Données manquantes dans la requête.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Méthode de requête non autorisée.']);
    }
?>