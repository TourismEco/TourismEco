<?php
    require("../../functions.php");

    if (!isset($_SESSION["user"])) {
        header("HTTP/1.1 400 Bad Request");
        exit;
    }

    if (isset($_POST["preference"])) {
        switch ($_POST["preference"]) {
            case 1:
                $value = "Economique";
                break;
            case 2:
                $value = "Decouverte";
                break;
            case 3:
                $value = "Ecologique";
                break;
            default:
                $value = "Global";
                break;
        }
    } else {
        echo <<<HTML
            <div id="errorScore" class="form-warning" hx-swap-oob="outerHTML">Le formulaire est incohérent...</div>
        HTML;
        exit;
    }

    $id = $_SESSION['user']["username"];

    try {
        $conn = getDB();

        $stmt = $conn->prepare("UPDATE client SET score=:score WHERE nom = :id");
        $stmt->bindParam(':score', $value, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $_SESSION['user']['score'] = $value;
            echo <<<HTML
                <div id="errorScore" class="form-success" hx-swap-oob="outerHTML">Score changé avec succès !</div>
            HTML;
        } else {
            echo <<<HTML
                <div id="errorScore" class="form-warning" hx-swap-oob="outerHTML">Erreur inconnue.</div>
            HTML;
        }

        $stmt= null;
        $conn = null;
    } catch (Exception $e) {
        echo <<<HTML
            <div id="errorScore" class="form-warning" hx-swap-oob="outerHTML">Erreur inconnue.</div>
        HTML;
    }
?>