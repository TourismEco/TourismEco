<?php
    require("../../functions.php");

    if (isset($_POST["country_register"], $_POST["city_register"], $_SESSION["user"])) {
        $pays = htmlspecialchars($_POST["country_register"]);
        $ville = htmlspecialchars($_POST["city_register"]);
        $id = $_SESSION['user']["username"];

        try {
            $conn = getDB();

            $stmtCheckCityCountry = $conn->prepare("SELECT COUNT(*) FROM villes v
            JOIN pays p ON v.id_pays = p.id
            WHERE v.nom = ? AND p.nom = ?");
            $stmtCheckCityCountry->bindParam(1, $ville);
            $stmtCheckCityCountry->bindParam(2, $pays);
            $stmtCheckCityCountry->execute();
            $cityCountryCount = $stmtCheckCityCountry->fetchColumn();

            if ($cityCountryCount == 0) {
                echo <<<HTML
                    <div id="error" class="form-warning" hx-swap-oob="outerHTML">Il y a une incohérence dans la ville entrée. Elle semble ne pas appartenir au pays choisi...</div>
                HTML;
                exit;
            }

            $stmt = $conn->prepare("UPDATE client SET pays=:pays, ville=:ville WHERE nom = :id");
            $stmt->bindParam(':pays', $pays, PDO::PARAM_STR);
            $stmt->bindParam(':ville', $ville, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $_SESSION['user']['country'] = $pays;
                $_SESSION['user']['city'] = $ville;
                echo <<<HTML
                    <div id="error" class="form-success" hx-swap-oob="outerHTML">Localisation changée avec succès !</div>
                HTML;
            } else {
                echo <<<HTML
                    <div id="error" class="form-warning" hx-swap-oob="outerHTML">Erreur inconnue.</div>
                HTML;
            }

            $stmt= null;
            $conn = null;
        } catch (Exception $e) {
            echo <<<HTML
                <div id="error" class="form-warning" hx-swap-oob="outerHTML">Erreur inconnue.</div>
            HTML;
        }
    } else {
        echo <<<HTML
            <div id="error" class="form-warning" hx-swap-oob="outerHTML">Des données sont manquantes.</div>
        HTML;
    }
?>